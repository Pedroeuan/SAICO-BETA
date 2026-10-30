<?php
/** Aggregate-only audit. Run from the repository: php scripts/analytics/audit_database.php */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$db = Illuminate\Support\Facades\DB::connection();
if ($db->getDriverName() !== 'mysql') { throw new RuntimeException('This audit requires MySQL/MariaDB.'); }
$db->statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
$db->statement('SET TRANSACTION READ ONLY');
$db->beginTransaction();
$report = ['audited_at'=>now('America/Mexico_City')->toIso8601String(), 'scope'=>'Configured Laravel database; aggregate data only; read-only transaction'];
try {
 foreach (['vehiculos','salidas_vehiculos','salidas_checklists','checklist_condiciones','cargas_combustible','mantenimientos','salidas_eventos_flujo'] as $table) {
  $exists = Illuminate\Support\Facades\Schema::hasTable($table);
  $report['tables'][$table] = $exists ? ['rows'=>$db->table($table)->count(), 'columns'=>Illuminate\Support\Facades\Schema::getColumnListing($table)] : ['missing'=>true];
 }
 $queries = [
 'duration_review'=>"SELECT MIN(fecha_regreso) first_return,MAX(fecha_regreso) latest_return,SUM(estatus='finalizado' AND TIMESTAMPDIFF(MINUTE,fecha_salida,fecha_regreso)>1440) over_24_hours,SUM(estatus='finalizado' AND TIMESTAMPDIFF(MINUTE,fecha_salida,fecha_regreso)>10080) over_7_days,SUM(estatus='finalizado' AND TIMESTAMPDIFF(MINUTE,fecha_salida,fecha_regreso)>43200) over_30_days,SUM(estatus='activo' AND fecha_salida<CURRENT_TIMESTAMP-INTERVAL 30 DAY) active_older_than_30_days FROM salidas_vehiculos",
 'duration_distribution'=>"SELECT CASE WHEN fecha_regreso<fecha_salida THEN 'negative' WHEN TIMESTAMPDIFF(MINUTE,fecha_salida,fecha_regreso)<=60 THEN '0-60 minutes' WHEN TIMESTAMPDIFF(MINUTE,fecha_salida,fecha_regreso)<=1440 THEN '1-24 hours' WHEN TIMESTAMPDIFF(MINUTE,fecha_salida,fecha_regreso)<=10080 THEN '1-7 days' ELSE 'over 7 days' END duration_band,COUNT(*) total FROM salidas_vehiculos WHERE estatus='finalizado' GROUP BY duration_band",
 'exit_status'=>"SELECT estatus, COUNT(*) total FROM salidas_vehiculos GROUP BY estatus",
 'vehicle_status'=>"SELECT estatus, COUNT(*) total FROM vehiculos GROUP BY estatus",
 'exit_months'=>"SELECT DATE_FORMAT(fecha_salida,'%Y-%m') month, COUNT(*) total FROM salidas_vehiculos GROUP BY month ORDER BY month",
 'exit_dates'=>"SELECT MIN(fecha_salida) earliest, MAX(fecha_salida) latest, COUNT(DISTINCT vehiculo_id) vehicles_used, SUM(fecha_salida IS NULL) missing_departure, SUM(estatus='finalizado' AND fecha_regreso IS NULL) completed_without_return, SUM(fecha_regreso < fecha_salida) inverted_dates, SUM(fecha_regreso=fecha_salida) zero_duration, SUM(estatus='activo' AND fecha_regreso IS NOT NULL) active_with_return, SUM(fecha_salida > CURRENT_TIMESTAMP) future_departures, SUM(fecha_regreso > CURRENT_TIMESTAMP) future_returns, SUM(estatus='finalizado' AND duracion_minutos IS NULL) missing_completed_duration, SUM(duracion_minutos < 0) negative_stored_duration, SUM(fecha_regreso IS NOT NULL AND ABS(duracion_minutos-TIMESTAMPDIFF(SECOND,fecha_salida,fecha_regreso)/60.0)>1) duration_mismatch_over_one_minute FROM salidas_vehiculos",
 'valid_date_duration'=>"SELECT COUNT(*) eligible, MIN(TIMESTAMPDIFF(SECOND,fecha_salida,fecha_regreso)/60.0) min_minutes, MAX(TIMESTAMPDIFF(SECOND,fecha_salida,fecha_regreso)/60.0) max_minutes, AVG(TIMESTAMPDIFF(SECOND,fecha_salida,fecha_regreso)/60.0) avg_minutes, SUM(TIMESTAMPDIFF(SECOND,fecha_salida,fecha_regreso)<60) under_one_minute FROM salidas_vehiculos WHERE estatus='finalizado' AND fecha_regreso>fecha_salida AND fecha_regreso<=CURRENT_TIMESTAMP",
 'relations'=>"SELECT (SELECT COUNT(*) FROM salidas_vehiculos s LEFT JOIN vehiculos v ON v.id=s.vehiculo_id WHERE v.id IS NULL) orphan_vehicles, (SELECT COUNT(*) FROM salidas_vehiculos s LEFT JOIN users u ON u.id=s.chofer_id WHERE u.id IS NULL) orphan_drivers, (SELECT COUNT(*) FROM salidas_vehiculos s LEFT JOIN users u ON u.id=s.solicitado_por WHERE u.id IS NULL) orphan_requesters, (SELECT COUNT(*) FROM salidas_checklists c LEFT JOIN salidas_vehiculos s ON s.id=c.salida_vehiculo_id WHERE s.id IS NULL) orphan_checklists, (SELECT COUNT(*) FROM checklist_condiciones c LEFT JOIN salidas_checklists s ON s.id=c.salida_checklist_id WHERE s.id IS NULL) orphan_conditions",
 'checklist_types'=>"SELECT tipo, COUNT(*) total FROM salidas_checklists GROUP BY tipo",
 'duplicates'=>"SELECT (SELECT COUNT(*) FROM (SELECT salida_vehiculo_id,tipo FROM salidas_checklists GROUP BY salida_vehiculo_id,tipo HAVING COUNT(*)>1) t) duplicate_checklist_groups, (SELECT COUNT(*) FROM (SELECT salida_checklist_id FROM checklist_condiciones GROUP BY salida_checklist_id HAVING COUNT(*)>1) t) duplicate_condition_groups, (SELECT COUNT(*) FROM (SELECT vehiculo_id FROM salidas_vehiculos WHERE estatus='activo' GROUP BY vehiculo_id HAVING COUNT(*)>1) t) vehicles_with_multiple_active_exits, (SELECT COUNT(*) FROM (SELECT placa FROM vehiculos GROUP BY placa HAVING COUNT(*)>1) t) duplicate_plate_groups",
 'checklist_coverage'=>"SELECT s.estatus,COUNT(*) exits_count, SUM(EXISTS(SELECT 1 FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='salida')) with_departure, SUM(EXISTS(SELECT 1 FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='entrada')) with_arrival, SUM(EXISTS(SELECT 1 FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='salida') AND EXISTS(SELECT 1 FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='entrada')) with_both FROM salidas_vehiculos s GROUP BY s.estatus",
 'vehicle_history_distribution'=>"SELECT exit_count,COUNT(*) vehicles FROM (SELECT v.id,COUNT(s.id) exit_count FROM vehiculos v LEFT JOIN salidas_vehiculos s ON s.vehiculo_id=v.id GROUP BY v.id) t GROUP BY exit_count ORDER BY exit_count",
 'vehicle_quality'=>"SELECT SUM(anio IS NULL) missing_year, SUM(kilometraje_actual IS NULL) missing_mileage, SUM(kilometraje_actual<0) negative_mileage FROM vehiculos",
 'fuel_quality'=>"SELECT COUNT(*) total,COUNT(DISTINCT vehiculo_id) vehicles,MIN(fecha_carga) earliest,MAX(fecha_carga) latest,SUM(litros IS NULL OR litros<=0) invalid_liters,SUM(costo_total IS NULL OR costo_total<0) invalid_cost,SUM(kilometraje IS NULL OR kilometraje<0) invalid_mileage,SUM(tanque_lleno=1) full_tanks FROM cargas_combustible",
 'maintenance_quality'=>"SELECT COUNT(*) total,COUNT(DISTINCT vehiculo_id) vehicles,MIN(fecha) earliest,MAX(fecha) latest,SUM(costo IS NULL OR costo<0) invalid_cost FROM mantenimientos",
 ];
 foreach ($queries as $key=>$sql) { $report[$key]=$db->select($sql); }
 $pairs = "SELECT s.id,s.estatus,s.fecha_salida,s.fecha_regreso,MAX(CASE WHEN c.tipo='salida' THEN k.kilometraje END) departure_km,MAX(CASE WHEN c.tipo='entrada' THEN k.kilometraje END) arrival_km,COUNT(CASE WHEN c.tipo='salida' THEN k.id END) departure_rows,COUNT(CASE WHEN c.tipo='entrada' THEN k.id END) arrival_rows FROM salidas_vehiculos s LEFT JOIN salidas_checklists c ON c.salida_vehiculo_id=s.id LEFT JOIN checklist_condiciones k ON k.salida_checklist_id=c.id GROUP BY s.id,s.estatus,s.fecha_salida,s.fecha_regreso";
 $report['mileage_pairs']=$db->select("SELECT COUNT(*) completed, SUM(departure_rows=1 AND arrival_rows=1 AND departure_km IS NOT NULL AND arrival_km IS NOT NULL) unique_pairs, SUM(departure_rows=1 AND arrival_rows=1 AND arrival_km<departure_km) decreasing_pairs, SUM(departure_rows=1 AND arrival_rows=1 AND arrival_km=departure_km) unchanged_pairs, SUM(departure_rows=1 AND arrival_rows=1 AND departure_km>=0 AND arrival_km>=departure_km) valid_pairs, SUM(CASE WHEN departure_rows=1 AND arrival_rows=1 AND departure_km>=0 AND arrival_km>=departure_km THEN arrival_km-departure_km END) total_valid_km, MAX(CASE WHEN departure_rows=1 AND arrival_rows=1 THEN arrival_km-departure_km END) max_trip_km FROM ($pairs) p WHERE estatus='finalizado'");
 $report['joint_candidates']=$db->select("SELECT COUNT(*) total FROM ($pairs) p WHERE estatus='finalizado' AND fecha_regreso>fecha_salida AND fecha_regreso<=CURRENT_TIMESTAMP AND departure_rows=1 AND arrival_rows=1 AND departure_km>=0 AND arrival_km>=departure_km");
 foreach (['salidas_vehiculos'=>['vehiculo_id','chofer_id','solicitado_por','fecha_salida','fecha_regreso','duracion_minutos','motivo'],'checklist_condiciones'=>['kilometraje','nivel_gasolina','aceite','anticongelante','liquido_frenos','estado_llantas']] as $table=>$columns) {
  foreach($columns as $column) { $report['missing_values'][$table][$column]=$db->table($table)->whereNull($column)->count();
   $report['blank_values'][$table][$column]=$db->table($table)->whereNotNull($column)->whereRaw("TRIM(CAST(`$column` AS CHAR)) = ''")->count(); }
 }
 foreach (['aceite','anticongelante','liquido_frenos','liquido_limpiaparabrisas','estado_llantas','llanta_delantera_izq_calibracion','llanta_delantera_der_calibracion','llanta_trasera_izq_calibracion','llanta_trasera_der_calibracion','nivel_gasolina'] as $column) {
  $report['arrival_condition_distribution'][$column]=$db->select("SELECT k.`$column` value,COUNT(*) total FROM checklist_condiciones k JOIN salidas_checklists c ON c.id=k.salida_checklist_id WHERE c.tipo='entrada' GROUP BY k.`$column`");
 }
 $report['fuel_relations']=$db->select("SELECT COUNT(*) orphans FROM cargas_combustible c LEFT JOIN vehiculos v ON v.id=c.vehiculo_id WHERE v.id IS NULL");
 $report['maintenance_relations']=$db->select("SELECT COUNT(*) orphans FROM mantenimientos m LEFT JOIN vehiculos v ON v.id=m.vehiculo_id WHERE v.id IS NULL");
 $durations=array_map(fn($r)=>(float)$r->minutes,$db->select("SELECT TIMESTAMPDIFF(SECOND,fecha_salida,fecha_regreso)/60.0 minutes FROM salidas_vehiculos WHERE estatus='finalizado' AND fecha_regreso>fecha_salida AND fecha_regreso<=CURRENT_TIMESTAMP ORDER BY minutes"));
 $n=count($durations);
 $report['median_valid_date_minutes']=$n ? ($durations[(int)floor(($n-1)/2)]+$durations[(int)floor($n/2)])/2 : null;
 echo json_encode($report, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR).PHP_EOL;
} finally { $db->rollBack(); }
