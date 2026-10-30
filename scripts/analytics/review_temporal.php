<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__,2).'/vendor/autoload.php';
$app=require dirname(__DIR__,2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$db=Illuminate\Support\Facades\DB::connection();
if($db->getDriverName()!=='mysql') throw new RuntimeException('MySQL/MariaDB required.');
$db->statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
$db->statement('SET TRANSACTION READ ONLY');
$db->beginTransaction();
try {
    $rows=$db->select("SELECT s.id salida_id,s.vehiculo_id,s.estatus,s.created_at solicitud_creada,s.updated_at solicitud_actualizada,s.fecha_salida,s.fecha_regreso,s.duracion_minutos,
    (SELECT COUNT(*) FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='salida') checklists_salida,
    (SELECT MIN(c.created_at) FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='salida') checklist_salida_creado,
    (SELECT COUNT(*) FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='entrada') checklists_entrada,
    (SELECT MIN(c.created_at) FROM salidas_checklists c WHERE c.salida_vehiculo_id=s.id AND c.tipo='entrada') checklist_entrada_creado,
    TIMESTAMPDIFF(SECOND,s.fecha_salida,s.created_at) retraso_captura_segundos,
    TIMESTAMPDIFF(SECOND,s.fecha_salida,s.fecha_regreso) intervalo_registrado_segundos
    FROM salidas_vehiculos s ORDER BY s.id");
    $clock=$db->selectOne('SELECT CURRENT_TIMESTAMP reloj_bd, @@session.time_zone zona_sesion_bd');
} finally { $db->rollBack(); }
$stamp=now(config('app.timezone'))->format('Ymd_His');
$directory=storage_path('app/analytics/reviews/'.$stamp.'_'.bin2hex(random_bytes(3)));
if(!mkdir($directory,0700,true)) throw new RuntimeException('Cannot create directory.');
$review=[]; $counts=['total'=>count($rows),'captura_posterior'=>0,'captura_posterior_hasta_60s'=>0,'captura_posterior_61s_a_1h'=>0,'captura_posterior_mayor_1h'=>0,'duracion_no_positiva'=>0,'duracion_mayor_24h'=>0,'regreso_coincide_checklist_entrada_hasta_60s'=>0,'checklists_cronologicos'=>0];
foreach($rows as $item) {
    $r=(array)$item; $flags=[];
    $delay=$r['retraso_captura_segundos']; $duration=$r['intervalo_registrado_segundos'];
    if($delay!==null && $delay>0) {
        $counts['captura_posterior']++; $flags[]='captura_posterior_a_fecha_salida';
        $key=$delay<=60?'captura_posterior_hasta_60s':($delay<=3600?'captura_posterior_61s_a_1h':'captura_posterior_mayor_1h');$counts[$key]++;
    }
    if($duration!==null && $duration<=0) {$counts['duracion_no_positiva']++;$flags[]='intervalo_no_positivo';}
    if($duration!==null && $duration>86400) {$counts['duracion_mayor_24h']++;$flags[]='intervalo_mayor_24h';}
    if($r['checklists_salida']==1 && $r['checklists_entrada']==1 && $r['checklist_salida_creado'] && $r['checklist_entrada_creado']) {
        $delta=strtotime($r['checklist_entrada_creado'])-strtotime($r['checklist_salida_creado']);
        $r['intervalo_entre_capturas_segundos']=$delta;
        if($delta>0) $counts['checklists_cronologicos']++;
        else $flags[]='capturas_checklist_no_cronologicas';
    } else {$r['intervalo_entre_capturas_segundos']=null; $flags[]='sin_par_unico_fechado_de_checklists';}
    if($r['checklists_entrada']==1 && $r['checklist_entrada_creado'] && $r['fecha_regreso']) {
        $r['desfase_regreso_vs_captura_segundos']=strtotime($r['fecha_regreso'])-strtotime($r['checklist_entrada_creado']);
        if(abs($r['desfase_regreso_vs_captura_segundos'])<=60) $counts['regreso_coincide_checklist_entrada_hasta_60s']++;
    } else $r['desfase_regreso_vs_captura_segundos']=null;
    $r['hallazgos']=implode('|',$flags);
    $r['procedencia']='pendiente'; // Must be confirmed, never inferred from unusual durations.
    $r['fecha_salida_representa']='pendiente';
    $r['fecha_regreso_representa']='pendiente';
    $r['decision_supervisado']='pendiente';
    $r['evidencia_revision']='';
    $review[]=$r;
}
file_put_contents($directory.'/fuente_temporal.json',json_encode(['timezone_app'=>config('app.timezone'),'database_clock'=>$clock,'rows'=>$rows],JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR));
$csv=fopen($directory.'/revision_por_salida.csv','x');
if(!$csv) throw new RuntimeException('Cannot create CSV.');
if($review) {fputcsv($csv,array_keys($review[0]),',','"','');foreach($review as $r) fputcsv($csv,array_values($r),',','"','');} fclose($csv);
$summary=['generated_at'=>now(config('app.timezone'))->toIso8601String(),'counts'=>$counts,'database_clock'=>$clock,'timezone_app'=>config('app.timezone'),'database_changed'=>false,'provenance_confirmed'=>false,'files'=>[]];
foreach(['fuente_temporal.json','revision_por_salida.csv'] as $file) $summary['files'][$file]=hash_file('sha256',$directory.'/'.$file);
file_put_contents($directory.'/manifest.json',json_encode($summary,JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR));
echo json_encode(['directory'=>$directory,'summary'=>$summary],JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR).PHP_EOL;
