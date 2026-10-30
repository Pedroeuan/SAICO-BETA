<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__,2).'/vendor/autoload.php';
require __DIR__.'/SalidasDataset.php';
$app = require dirname(__DIR__,2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$db = Illuminate\Support\Facades\DB::connection();
if ($db->getDriverName()!=='mysql') throw new RuntimeException('MySQL/MariaDB required.');
$now = new DateTimeImmutable('now',new DateTimeZone(config('app.timezone')));
$db->statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
$db->statement('SET TRANSACTION READ ONLY');
$db->beginTransaction();
try {
    $exits = array_map(fn($r)=>(array)$r,$db->select("SELECT s.id,s.vehiculo_id,s.estatus,s.fecha_salida,s.fecha_regreso,s.duracion_minutos,s.created_at,EXISTS(SELECT 1 FROM vehiculos v WHERE v.id=s.vehiculo_id) vehicle_exists,EXISTS(SELECT 1 FROM users u WHERE u.id=s.chofer_id) driver_exists,EXISTS(SELECT 1 FROM users u WHERE u.id=s.solicitado_por) requester_exists FROM salidas_vehiculos s ORDER BY s.id"));
    $checks = array_map(fn($r)=>(array)$r,$db->table('salidas_checklists')->select('id','salida_vehiculo_id','tipo')->orderBy('id')->get()->all());
    $conditions = array_map(fn($r)=>(array)$r,$db->table('checklist_condiciones')->select(array_merge(['id','salida_checklist_id','kilometraje'],array_keys(SalidasDataset::CONDITIONS)))->orderBy('id')->get()->all());
    $events = $db->table('salidas_tiempos_operativos')->orderBy('id')->get(['salida_vehiculo_id','tipo','ocurrido_at','registrado_at','zona_horaria','canal','entorno'])->map(fn($r)=>(array)$r)->all();
} finally { $db->rollBack(); }
$checkGroups=[]; $conditionGroups=[];
foreach($checks as $row) $checkGroups[$row['salida_vehiculo_id']][]=$row;
foreach($conditions as $row) $conditionGroups[$row['salida_checklist_id']][]=$row;
$eventGroups=[]; foreach($events as $event) $eventGroups[$event['salida_vehiculo_id']][]=$event;
$operationalRows=[];
foreach($exits as $exit) {
    $checksForExit=$checkGroups[$exit['id']]??[];
    $unique=count(array_filter($checksForExit,fn($c)=>$c['tipo']==='salida'))===1 && count(array_filter($checksForExit,fn($c)=>$c['tipo']==='entrada'))===1;
    $operationalRows[]=App\Services\Analytics\OperationalDataset::row($exit,$eventGroups[$exit['id']]??[],$unique,$now);
}
$rows=[];
foreach($exits as $exit) $rows[]=SalidasDataset::row($exit,$checkGroups[$exit['id']]??[],$conditionGroups,$now);
usort($rows,fn($a,$b)=>[$a['fecha_salida']??'', $a['salida_id']]<=>[$b['fecha_salida']??'', $b['salida_id']]);
$directory=storage_path('app/analytics/datasets/'.$now->format('Ymd_His').'_'.bin2hex(random_bytes(3)));
if (!mkdir($directory,0700,true)) throw new RuntimeException('Cannot create export directory.');
function saveJson(string $path,array $data): void {
    if(file_put_contents($path,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR).PHP_EOL)===false) throw new RuntimeException('Cannot write JSON.');
}
function saveCsv(string $path,array $headers,array $rows): void {
    $handle=fopen($path,'x');
    if (!$handle) throw new RuntimeException('Cannot create CSV.');
    try {
        if(fputcsv($handle,$headers,',','"','')===false) throw new RuntimeException('Cannot write CSV header.');
        foreach($rows as $row) {
            if(fputcsv($handle,array_map(fn($key)=>$row[$key]??null,$headers),',','"','')===false) throw new RuntimeException('Cannot write CSV row.');
        }
    } finally { fclose($handle); }
}
$headers=$rows ? array_keys($rows[0]) : array_keys(SalidasDataset::row(['id'=>0,'vehiculo_id'=>0,'estatus'=>null,'fecha_salida'=>null,'fecha_regreso'=>null,'created_at'=>null,'duracion_minutos'=>null,'vehicle_exists'=>0,'driver_exists'=>0,'requester_exists'=>0],[],[],$now));
$durationRows=array_values(array_filter($rows,fn($r)=>$r['candidato_duracion_exploratoria']===1));
$featureNames=['vehiculo_id','salida_hora','salida_dia_semana','salida_mes'];
$trainingHeaders=array_merge(['salida_id','fecha_solicitud','fecha_salida'],$featureNames,['duracion_calculada_minutos','requiere_revision_duracion','procedencia_verificada']);
$trainingHeaders=array_values(array_unique($trainingHeaders));
saveJson($directory.'/fuente_minima.json',['extracted_at'=>$now->format(DATE_ATOM),'timezone'=>$now->getTimezone()->getName(),'salidas'=>$exits,'checklists'=>$checks,'condiciones'=>$conditions,'tiempos_operativos'=>$events]);
saveJson($directory.'/duraciones_operativas.json', ['version_reglas'=>App\Services\Analytics\OperationalDataset::VERSION,'filas'=>$operationalRows,'entrenamiento_habilitado'=>false,'diccionario'=>['duracion_operativa_minutos'=>'Objetivo candidato: diferencia positiva entre horas declaradas; null si excluido. No usar como entrada.','etiqueta_disponible_desde'=>'Hora de cierre administrativo: limite inferior de disponibilidad de la etiqueta para particiones temporales.','salida_real'=>'Hora declarada con zona; no disponible necesariamente al solicitar el vehiculo.','regreso_real'=>'Hora declarada con zona; resultado posterior, no entrada predictiva.','retraso_captura_salida_minutos'=>'Diferencia entre registro y evento de salida; auditoria.','retraso_captura_regreso_minutos'=>'Diferencia entre registro y evento de regreso; auditoria.','motivos_exclusion'=>'Razones separadas por |.','aprobado_entrenamiento'=>'Siempre 0 en esta fase; elegibilidad KPI no aprueba entrenamiento.'],'nota'=>'Etiquetas candidatas. Faltan entradas historicas verificadas y revision de procedencia; la hora del regreso no es una entrada del modelo.']);
saveCsv($directory.'/salidas_preparadas.csv',$headers,$rows);
saveCsv($directory.'/candidatos_duracion.csv',$trainingHeaders,$durationRows);
$issues=[];
foreach($rows as $r) foreach(explode('|',$r['alertas_calidad']) as $flag) if($flag!=='') $issues[]=['salida_id'=>$r['salida_id'],'alerta'=>$flag];
saveCsv($directory.'/incidencias_calidad.csv',['salida_id','alerta'],$issues);
$dictionary=[];
foreach($headers as $field) {
    $role=in_array($field,$featureNames,true)?'entrada_candidata':($field==='duracion_calculada_minutos'?'objetivo_exploratorio':'auditoria_no_usar_como_entrada');
    $dictionary[$field]=['role'=>$role,'missing_representation'=>'empty CSV cell = null; never automatic zero', 'description'=> match(true) {
        $field==='salida_id'=>'Internal linkage identifier; not a training feature.',
        $field==='vehiculo_id'=>'Internal vehicle identifier; categorical, never a numerical magnitude.',
        $field==='salida_hora'=>'Planned departure hour, 0..23 in application timezone.',
        $field==='salida_dia_semana'=>'Planned departure weekday, Monday=1 .. Sunday=7.',
        $field==='salida_mes'=>'Planned departure month, 1..12.',
        $field==='fecha_solicitud'=>'Creation timestamp; used for later temporal validation.',
        $field==='fecha_salida'=>'Recorded/planned departure, not proven physical movement.',
        $field==='fecha_regreso'=>'Closure timestamp; unavailable at prediction time.',
        $field==='duracion_calculada_minutos'=>'Signed (return minus departure) in minutes; not validated physical trip time.',
        $field==='duracion_guardada_minutos'=>'Original numeric duration for reconciliation only.',
        $field==='estatus'=>'Current lifecycle status at extraction, not a predictive feature.',
        $field==='km_recorridos'=>'Signed arrival minus departure mileage; eligibility is a separate flag.',
        str_starts_with($field,'km_')=>'Nonnegative checklist odometer reading, or null if missing/invalid/ambiguous.',
        str_starts_with($field,'motivos_')=>'Pipe-separated reasons; empty means no exclusion for that purpose.',
        $field==='alertas_calidad'=>'Pipe-separated review/exclusion flags, not an incident outcome label.',
        $field==='procedencia_verificada'=>'0: operational versus test origin not verified.',
        $field==='requiere_revision_duracion'=>'1 when duration exceeds 1440 minutes; not automatic exclusion.',
        $field==='candidato_duracion_exploratoria'=>'Valid positive completed interval with existing vehicle and creation timestamp no later than departure; provisional, not certified for deployment.',
        $field==='ambos_checklists_unicos'=>'1 exactly one checklist per type, independent of field completeness.',
        str_starts_with($field,'elegible_')=>'Purpose-specific validity flag; 1 eligible, 0 see reasons.',
        str_starts_with($field,'checklists_')=>'Checklist count by type; duplicates are not collapsed.',
        str_starts_with($field,'condiciones_')=>'Condition count for a unique checklist; 0 if checklist ambiguous.',
        default=>'Normalized checklist category; blank/unknown values become null and are flagged. Source is retained in minimal snapshot.',
    }];
}
saveJson($directory.'/diccionario.json',$dictionary);
$counts=[]; foreach($issues as $issue) $counts[$issue['alerta']]=($counts[$issue['alerta']]??0)+1; ksort($counts);
$manifest=[
    'rule_version'=>SalidasDataset::VERSION,'generated_at'=>$now->format(DATE_ATOM),'timezone'=>$now->getTimezone()->getName(),
    'source'=>'Configured Laravel connection; read-only consistent transaction; minimal allowlisted columns',
    'source_counts'=>['salidas'=>count($exits),'checklists'=>count($checks),'condiciones'=>count($conditions)],
    'prepared_rows'=>count($rows),'completed_rows'=>count(array_filter($rows,fn($r)=>$r['estatus']==='finalizado')),
    'duration_kpi_rows'=>array_sum(array_column($rows,'elegible_kpi_duracion')),
    'mileage_kpi_rows'=>array_sum(array_column($rows,'elegible_kpi_km')),
    'valid_total_km'=>array_sum(array_map(fn($r)=>$r['elegible_kpi_km']?$r['km_recorridos']:0,$rows)),
    'operational_rule_version'=>App\Services\Analytics\OperationalDataset::VERSION,'operational_kpi_rows'=>array_sum(array_column($operationalRows,'elegible_kpi_operativo')),'operational_events'=>count($events),'exploratory_duration_candidates'=>count($durationRows),'quality_counts'=>$counts,
    'training_feature_allowlist'=>$featureNames,
    'training_target'=>'duracion_calculada_minutos','operational_target'=>'duracion_operativa_minutos','operational_training_approved_rows'=>0,'split_performed'=>false,'operationally_validated'=>false,
    'limitations'=>['Small sample; no reliable held-out evaluation yet.','Long durations and record provenance require review.','Feature availability inferred from application flow; mutable historical timestamps are not independently audited.','No imputation, scaling, classification labels or clustering performed.','Internal IDs are pseudonymous, not anonymous; files remain local and outside public storage.'],
];
foreach(['fuente_minima.json','salidas_preparadas.csv','candidatos_duracion.csv','incidencias_calidad.csv','diccionario.json','duraciones_operativas.json'] as $file) $manifest['sha256'][$file]=hash_file('sha256',$directory.'/'.$file);
saveJson($directory.'/manifest.json',$manifest);
echo json_encode(['directory'=>$directory,'summary'=>$manifest],JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR).PHP_EOL;
