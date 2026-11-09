{{-- Componente compartido: recibe la colección de inventario y modoCalendario desde cada vista. --}}
@php
    $eventosEquipo = [];
    // Las fechas ficticias del sistema y valores inválidos se presentan como "Sin fecha".
    // El límite de año conserva el tratamiento histórico; no modifica fechas en la BD.
    $fechaValida = static function ($valor) {
        $valor = substr((string) $valor, 0, 10);
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $partes)
            || (int) $partes[1] < 2002 || !checkdate((int) $partes[2], (int) $partes[3], (int) $partes[1])) {
            return null;
        }
        return $valor;
    };
    // Solo construimos enlaces para archivos existentes en el registro, no para sus marcadores.
    $archivoEquipo = static function ($ruta) {
        return $ruta && !in_array($ruta, ['ESPERA DE DATO', 'N/A', '---']) ? asset('storage/' . $ruta) : null;
    };
    // Un equipo puede producir dos eventos: calibración y verificación.
    // Se usa la próxima fecha para ubicar cada actividad en el calendario.
    foreach ($generalConCertificadosConAlmacenConISOConClasificacion as $equipoCalendario) {
        if (!in_array($equipoCalendario->Tipo, $modoCalendario === 'mantenimiento' ? ['EQUIPOS'] : ['EQUIPOS', 'BLOCK Y PROBETA'])) continue;
        $certificadoEquipo = $equipoCalendario->certificados;
        $tiposCalendario = $modoCalendario === 'mantenimiento' ? ['mantenimiento' => 'Mantenimiento'] : ['calibracion' => 'Calibración', 'verificacion' => 'Verificación'];
        foreach ($tiposCalendario as $campoCalendario => $etiquetaCalendario) {
            $eventosEquipo[] = [
                'nombre' => $equipoCalendario->Nombre_E_P_BP,
                'numero' => $equipoCalendario->No_economico,
                'categoria' => $equipoCalendario->Tipo,
                'marca' => $equipoCalendario->Marca,
                'modelo' => $equipoCalendario->Modelo,
                'serie' => $equipoCalendario->Serie,
                'tipo' => $etiquetaCalendario,
                'ultima' => $fechaValida(optional($certificadoEquipo)->{'Fecha_' . $campoCalendario}),
                'fecha' => $fechaValida(optional($certificadoEquipo)->{'Prox_fecha_' . $campoCalendario}),
                'certificado' => optional($certificadoEquipo)->No_certificado,
                'documento' => $archivoEquipo(optional($certificadoEquipo)->Certificado_Actual),
                'foto' => $archivoEquipo($equipoCalendario->Foto),
                'editar' => route('edicion.editEyC', ['id' => $equipoCalendario->idGeneral_EyC]),
            ];
        }
    }
@endphp
{{-- Estilos compartidos, limitados al calendario y a los contenedores de estas dos pantallas. --}}
<style>
.equipos-agenda-layout{
    width:100%;
    padding:0 20px 24px;
    box-sizing:border-box
}
.equipos-agenda-layout 
.box-body,.equipos-agenda-contenido{
    width:100%;
    min-width:0
}
.equipos-agenda-contenido>h3{
    font-weight:600;
    margin-bottom:20px
}
.equipos-agenda-contenido 
.dt-container{
    overflow-x:auto
}
#equipos-calendario{
    width:100%;
    text-align:left;
    font-size:14px
}
#equipos-calendario [hidden]{
    display:none!important
}
#equipo-cal-panel{
    background:#fff;
    border:1px solid #e1e7ef;
    border-radius:12px;
    padding:20px;
    box-shadow:0 3px 14px #18315308
}
.equipo-cal-filtros{
    display:grid;
    grid-template-columns:minmax(170px,210px) minmax(240px,1fr) minmax(170px,220px) auto;
    gap:16px;
    align-items:end;
    margin-bottom:24px
}
.equipo-cal-filtros label{
    display:block;
    font-weight:600;
    margin:0;
    color:#38465a
}
.equipo-cal-filtros 
.form-control{
    margin-top:6px;
    min-height:40px;
    font-size:14px
}
.equipo-cal-filtros 
.btn{
    min-height:40px;
    margin-bottom:0!important
}
.equipo-cal-navegacion{
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    margin-bottom:12px
}
.equipo-cal-titulo{
    display:flex;
    align-items:center;
    gap:16px
}
.equipo-cal-titulo h4{
    margin:0;
    min-width:190px;
    text-align:center;
    text-transform:capitalize;
    font-size:22px;
    font-weight:600;
    color:#24364b
}
.equipo-cal-titulo .btn{
    font-size:24px;
    line-height:1;
    width:38px;
    height:38px
}
.equipo-cal-leyenda{
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    color:#526174;
    font-size:12px
}
.equipo-cal-leyenda 
span:before{
    content:'';
    display:inline-block;
    width:9px;
    height:9px;
    border-radius:50%;
    margin-right:6px;
    background:#198754
}
.equipo-cal-leyenda 
.proximo:before{
    background:#cc9400
}
.equipo-cal-leyenda 
.vencido:before{
    background:#dc3545
}
.equipo-cal-ayuda{
    font-size:13px;
    margin-bottom:6px
}
#equipo-cal-resumen{
    font-size:13px;
    color:#526174;
    margin-bottom:16px
}
.equipo-cal-scroll{
    overflow-x:auto;
    border:1px solid #dce4ed;
    border-radius:8px
}
.equipo-cal-grid{
    display:grid;
    grid-template-columns:repeat(7,minmax(140px,1fr));
    min-width:980px;
    gap:1px;
    background:#e1e7ef
}
.equipo-cal-dia,
.equipo-cal-cabecera{
    background:white;
    padding:10px;
    min-width:0
}
.equipo-cal-dia{
    min-height:155px
}
.equipo-cal-cabecera{
    text-align:center;
    font-size:13px;
    font-weight:600;
    color:#465a71;
    background:#f2f6fa;
    padding:12px 8px
}
.equipo-cal-dia>strong{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:27px;
    height:27px;
    font-size:13px;
    color:#465a71;
    margin-bottom:4px
}
.equipo-cal-dia:empty,
.equipo-cal-vacio{
    background:#f6f8fb
}
.equipo-cal-dia[data-hoy=true]{
    box-shadow:inset 0 0 0 2px #007bff
}
.equipo-cal-dia[data-hoy=true]>strong{
    background:#007bff;
    color:white;
    border-radius:50%
}
.equipo-cal-evento{
    display:block;
    width:100%;
    text-align:left;
    border:1px solid transparent;
    border-left:4px solid #198754;
    background:#e9f5ef;
    color:#163d29;
    border-radius:6px;
    padding:9px;
    margin:5px 0;
    white-space:normal;
    font-size:12px;
    line-height:1.4;
    overflow-wrap:anywhere;
    cursor:pointer;
    transition:box-shadow .15s,transform .15s
}
.equipo-cal-evento:hover{
    box-shadow:0 3px 9px #18315320;
    transform:translateY(-1px)
}
.equipo-cal-evento:focus-visible{
    outline:2px solid #007bff;
    outline-offset:2px
}
.equipo-cal-evento>strong{
    display:block;
    font-size:13px;
    margin-bottom:4px
}
.equipo-cal-nombre{
    display:block;
    margin-bottom:6px
}
.equipo-cal-tipo,
.equipo-cal-estado{
    display:block;
    font-size:11px
}
.equipo-cal-estado{
    font-weight:600;
    margin-top:3px
}
.equipo-cal-evento[data-estado="Vencido"]{
    border-left-color:#dc3545;
    background:#fcebed;
    color:#842029
}
.equipo-cal-evento[data-estado="Próximo a vencer"]{
    border-left-color:#cc9400;
    background:#fff5d6;
    color:#664d03
}
.equipo-cal-evento[data-estado="Sin fecha"]{
    border-left-color:#6c757d;
    background:#f0f1f2;
    color:#343a40
}
#equipo-cal-panel details{
    border-top:1px solid #e1e7ef;
    padding-top:16px
}
#equipo-cal-panel summary{
    font-weight:600;
    color:#465a71;
    cursor:pointer
}
#equipo-cal-sin-fecha{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:12px;
    margin-top:12px
}
#equipo-cal-detalle{
    border:0;
    border-radius:12px;
    padding:24px;
    max-width:600px;
    width:90%;
    max-height:85vh;
    overflow:auto;
    box-shadow:0 12px 60px #0003
}
#equipo-cal-detalle::backdrop{
    background:#12243c88
}
#equipo-cal-detalle img{
    display:block;
    max-width:100%;
    max-height:240px;
    object-fit:contain;
    margin:12px auto
}
@media(min-width:1600px){
    .equipo-cal-dia{
        min-height:180px
    }
    .equipo-cal-evento{
        font-size:13px
    }
}
@media(max-width:991px){
    .equipo-cal-filtros{
        grid-template-columns:1fr 1fr
    }
    .equipos-agenda-layout{
        padding:0 8px 20px
    }
    #equipo-cal-panel{
        padding:14px
    }
    .equipo-cal-dia{
        min-height:140px
    }
}
@media(max-width:575px){
    .equipo-cal-filtros{
        grid-template-columns:1fr;
        gap:12px
    }
    .equipo-cal-titulo{
        gap:8px
    }
    .equipo-cal-titulo h4{
        font-size:18px;
        min-width:160px
    }
    .equipo-cal-leyenda{
        gap:10px
    }
    .equipo-cal-grid{
        min-width:910px;
        grid-template-columns:repeat(7,minmax(130px,1fr))
    }
    #equipo-cal-panel{
        padding:10px
    }
}
/* Ficha compartida por todos los eventos de calibración, verificación y mantenimiento. */
#equipo-cal-detalle{
    padding:0;
    width:min(860px,calc(100vw - 32px));
    max-width:860px;
    max-height:90vh;
    border:1px solid #c9d5e6;
    border-radius:16px;
    background:#fff;
    color:#172b49;
    box-shadow:0 24px 90px #06152c66;
    font-size:16px;
    line-height:1.5
}
#equipo-cal-detalle::backdrop{
    background:#071b36b8;
    backdrop-filter:blur(3px)
}
.equipo-detalle-cabecera{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    padding:18px 26px;
    background:#123f79;
    color:white;
    border-bottom:5px solid #c62837;
    position:sticky;
    top:0;z-index:1
}
.equipo-detalle-cabecera>span{
    font-size:16px;
    font-weight:700;
    letter-spacing:.3px
}
#equipo-cal-cerrar{
    display:flex;
    align-items:center;
    gap:12px;
    min-height:42px;
    padding:8px 14px;
    border:1px solid #ffffff88;
    border-radius:8px;
    background:#fff;
    color:#123f79;
    font-size:15px;
    font-weight:700;
    cursor:pointer
}
#equipo-cal-cerrar:hover{
    background:#e9f1fc
}
#equipo-cal-cerrar span{
    font-size:24px;
    line-height:1
}
#equipo-cal-contenido{
    padding:26px
}
.equipo-detalle-identidad{
    margin-bottom:24px
}
.equipo-detalle-numero{
    display:inline-block;
    padding:5px 12px;
    margin-bottom:10px;
    border-radius:6px;
    background:#e9f1fc;
    color:#123f79;
    font-size:15px;
    font-weight:700
}
#equipo-cal-detalle 
.equipo-detalle-titulo{
    font-size:25px;
    line-height:1.3;
    font-weight:700;
    color:#12345b;
    margin:0;
    overflow-wrap:anywhere
}
.equipo-detalle-datos{
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:12px;
    margin:0 0 24px
}
.equipo-detalle-campo{
    border:1px solid #dce4ef;
    border-radius:8px;
    padding:13px 16px;
    background:#f8fafd;
    min-width:0
}
.equipo-detalle-campo dt{
    font-size:13px;
    color:#4b607c;
    font-weight:600;
    margin-bottom:4px
}
.equipo-detalle-campo dd{
    font-size:17px;
    color:#172b49;
    font-weight:600;
    margin:0;
    overflow-wrap:anywhere
}
.equipo-detalle-destacado{
    border-left:4px solid #c62837;
    background:#fff7f8
}
.equipo-detalle-foto{
    margin:0 0 24px;
    padding:16px;
    background:#f3f6fa;
    border:1px solid #dce4ef;
    border-radius:10px;
    text-align:center
}
#equipo-cal-detalle 
.equipo-detalle-foto img{
    max-height:340px;
    max-width:100%;
    width:auto;
    margin:0 auto 12px;
    border-radius:6px
}
.equipo-detalle-foto 
figcaption{
    font-size:13px;
    color:#4b607c
}
.equipo-detalle-acciones{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    padding-top:20px;
    border-top:1px solid #dce4ef
}
.equipo-detalle-boton{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:46px;
    padding:11px 18px;
    border:1px solid #123f79;
    border-radius:8px;
    color:#123f79;
    background:white;
    font-size:15px;
    font-weight:600;
    text-decoration:none
}
.equipo-detalle-boton:hover{
    background:#e9f1fc;
    color:#123f79;
    text-decoration:none
}
.equipo-detalle-primario{
    background:#123f79;
    color:#fff;
    margin-left:auto
}
.equipo-detalle-primario:hover{
    background:#0b2e5c;
    color:#fff
}
#equipo-cal-detalle a:focus-visible,
#equipo-cal-detalle button:focus-visible{
    outline:3px solid #c62837;
    outline-offset:3px
}
@media(max-width:575px){
    #equipo-cal-detalle{
        width:calc(100vw - 20px);
        max-height:94vh;border-radius:12px
    }
    .equipo-detalle-cabecera
    {
        padding:14px 16px
    }
    .equipo-detalle-cabecera>span{
        font-size:14px
    }
    #equipo-cal-contenido{
        padding:18px
    }
    #equipo-cal-detalle 
    .equipo-detalle-titulo
    {
        font-size:21px
    }
    .equipo-detalle-datos{
        grid-template-columns:1fr
    }
    .equipo-detalle-boton{
        width:100%
    }.equipo-detalle-primario{
        margin-left:0
    }
}
#equipo-cal-detalle 
.equipo-detalle-foto img{
    margin-bottom:0
}
.equipo-detalle-boton{
    gap:9px
}
.equipo-detalle-boton i{
    font-size:16px;
    flex-shrink:0
}
/* Cabecera de agenda SAICO. */
#equipo-cal-panel{
    padding:0;
    overflow:hidden;
    border-color:#d6e0ed;
    box-shadow:0 6px 24px #123f790c
}
.equipo-cal-encabezado{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    padding:22px 26px;
    background:#123f79;
    color:white;
    border-bottom:4px solid #c62837
}
.equipo-cal-marca{
    display:block;
    font-size:11px;
    font-weight:700;
    letter-spacing:1.5px;
    color:#dce9fc;
    margin-bottom:6px
}
.equipo-cal-encabezado h3{
    font-size:22px;
    line-height:1.3;
    font-weight:600;
    margin:0;
    color:white
}
.equipo-cal-encabezado>i{
    font-size:34px;
    color:#dce9fc
}
.equipo-cal-filtros{
    padding:22px 26px;
    margin:0;
    background:#f6f8fc;
    border-bottom:1px solid #e1e7ef;
    grid-template-columns:minmax(180px,220px) minmax(220px,1fr) minmax(180px,230px) auto;
    gap:18px
}
.equipo-cal-filtros label{
    font-size:13px;
    color:#314a6a
}
.equipo-cal-filtros 
.form-control{
    background-color:white;
    border:1px solid #cbd7e6;
    border-radius:7px;
    min-height:44px;
    font-size:14px;
    color:#19365a;
    padding:9px 12px;
    box-shadow:none
}
.equipo-cal-filtros 
.form-control:focus{
    border-color:#123f79;
    box-shadow:0 0 0 3px #123f791a
}
.equipo-cal-filtros 
#equipo-cal-hoy{
    background:#123f79;
    color:white;
    border-color:#123f79;
    min-height:44px;
    border-radius:7px;
    font-size:14px;
    padding:10px 16px;
    white-space:nowrap
}
.equipo-cal-filtros 
#equipo-cal-hoy:hover{
    background:#0b2e5c
}
.equipo-cal-filtros 
#equipo-cal-hoy i{
    margin-right:6px
}
.equipo-cal-navegacion{
    padding:22px 26px 12px;
    margin:0
}
.equipo-cal-titulo h4{
    font-size:24px;
    min-width:220px;
    color:#173f72;
    font-weight:700
}
.equipo-cal-titulo .btn{
    width:40px;
    height:40px;
    border-color:#d1ddec;
    color:#123f79;
    background:#fff;
    border-radius:8px
}
.equipo-cal-titulo .btn:hover{
    background:#e9f1fc;
    border-color:#123f79
}
.equipo-cal-leyenda{
    gap:8px
}
.equipo-cal-leyenda span{
    padding:7px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    background:#f3f6fa;
    color:#3a4e68
}
.equipo-cal-leyenda .vigente{
    background:#e9f5ef;
    color:#17633f
}
.equipo-cal-leyenda .proximo{
    background:#fff5d6;
    color:#745700
}
.equipo-cal-leyenda .vencido{
    background:#fcebed;
    color:#9b2634
}
#equipo-cal-panel .equipo-cal-ayuda{
    margin:0 26px 10px;
    font-size:13px;
    color:#526174!important
}
#equipo-cal-resumen{
    display:inline-block;
    margin:0 26px 18px;
    padding:8px 12px;
    border-radius:6px;
    background:#edf3fb;
    color:#234b7c;
    font-size:13px;
    font-weight:500
}
#equipo-cal-panel .equipo-cal-scroll{
    margin:0 26px 22px
}
#equipo-cal-panel details{
    margin:0 26px 22px!important
}
#equipos-calendario [data-vista]{
    padding:10px 18px;
    font-size:14px;
    font-weight:600;
    border-color:#123f79;
    color:#123f79;
    background:#fff
}
#equipos-calendario [data-vista][aria-pressed=true]{
    background:#123f79;
    color:white
}
#equipos-calendario [data-vista] i{margin-right:6px}
@media(max-width:1100px){
    .equipo-cal-filtros{
        grid-template-columns:1fr 1fr
    }
    .equipo-cal-navegacion{
        gap:18px
    }
    .equipo-cal-titulo h4{
        font-size:21px;
        min-width:190px
    }
}
@media(max-width:575px){
    .equipo-cal-encabezado{
        padding:18px 16px
    }
    .equipo-cal-encabezado h3{
        font-size:19px
    }
    .equipo-cal-encabezado>i{
        display:none
    }
    .equipo-cal-filtros{
        grid-template-columns:1fr;
        padding:18px 16px;
        gap:14px
    }
    .equipo-cal-navegacion{
        padding:18px 16px 12px
    }
    .equipo-cal-titulo{
        width:100%;
        justify-content:space-between
    }
    .equipo-cal-titulo h4{
        min-width:0;
        font-size:20px
    }
    #equipo-cal-panel 
    .equipo-cal-ayuda{
        margin-left:16px;
        margin-right:16px
    }
    #equipo-cal-resumen{
        margin-left:16px;
        margin-right:16px
    }
    #equipo-cal-panel 
    .equipo-cal-scroll{
        margin:0 16px 18px
    }
    #equipo-cal-panel details{
        margin:0 16px 18px!important
    }
}
.equipo-cal-anual{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:18px;
    margin:0 26px 22px
}
.equipo-cal-mes-anual{
    border:1px solid #d6e0ed;
    border-radius:10px;
    padding:16px;
    background:#fff;
    min-width:0
}
.equipo-cal-mes-anual h5{
    text-transform:capitalize;
    font-size:18px;
    font-weight:700;
    color:#123f79;
    border-bottom:3px solid #c62837;
    padding-bottom:10px;margin:0 0 12px
}
.equipo-cal-mini{
    display:grid;
    grid-template-columns:repeat(7,minmax(0,1fr));
    gap:4px;
    text-align:center
}
.equipo-cal-mini>*{
    padding:5px 0;
    font-size:12px;
    min-width:0
}
.equipo-cal-mini strong{
    color:#526174
}
.equipo-cal-mini button{
    border:0;
    border-radius:5px;
    background:#123f79;
    color:white;
    font-weight:700;
    cursor:pointer
}
.equipo-cal-mini button:hover{
    background:#c62837
}
.equipo-cal-mini-hoy{
    outline:2px solid #c62837;
    border-radius:5px
}
.equipo-cal-total-mes{
    font-size:12px;
    color:#526174;
    margin:12px 0 6px
}
@media(max-width:1100px){
    .equipo-cal-anual{
        grid-template-columns:repeat(2,minmax(0,1fr))
    }
}
@media(max-width:650px){
    .equipo-cal-anual{
        grid-template-columns:1fr;
        margin:0 16px 18px
    }
}
/* Estado y consulta de actividades por fecha en ambas vistas. */
.equipo-cal-mini button[data-estado="Vencido"],
.equipo-cal-numero-dia[data-estado="Vencido"]{
    background:#bd2638;
    color:#fff
}
.equipo-cal-mini button[data-estado="Próximo a vencer"],
.equipo-cal-numero-dia[data-estado="Próximo a vencer"]{
    background:#f4c64d;
    color:#493600
}
.equipo-cal-mini button[data-estado="Vigente"],
.equipo-cal-numero-dia[data-estado="Vigente"]{
    background:#197247;
    color:#fff
}
.equipo-cal-mini button:hover{
    filter:brightness(.88)
}
.equipo-cal-numero-dia{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:30px;
    height:30px;
    border:0;
    border-radius:6px;
    font-size:13px;
    font-weight:700;
    margin-bottom:4px
}
.equipo-cal-numero-dia[type=button]{
    cursor:pointer
}
.equipo-cal-dia[data-estado="Vencido"]{
    background:#fff9fa
}
.equipo-cal-dia[data-estado="Próximo a vencer"]{
    background:#fffdf5
}
.equipo-cal-dia[data-estado="Vigente"]{
    background:#f8fcfa
}
.equipo-cal-mini button:focus-visible,
.equipo-cal-numero-dia:focus-visible,
.equipo-dia-tarjeta:focus-visible{
    outline:3px solid #123f79;
    outline-offset:3px
}
.equipo-dia-ayuda{
    font-size:14px;
    color:#526174;
    margin:12px 0 20px
}
.equipo-dia-lista{
    display:grid;gap:12px
}
.equipo-dia-tarjeta{
    display:grid;
    grid-template-columns:1fr auto;
    gap:6px 16px;
    width:100%;
    padding:18px;
    text-align:left;
    border:1px solid #d6e0ed;
    border-left:5px solid #197247;
    border-radius:10px;
    background:#f7fafc;
    color:#17345a;
    cursor:pointer
}
.equipo-dia-tarjeta:hover{
    box-shadow:0 4px 12px #123f7920
}
.equipo-dia-tarjeta[data-estado="Vencido"]{
    border-left-color:#bd2638;
    background:#fff7f8
}
.equipo-dia-tarjeta[data-estado="Próximo a vencer"]{
    border-left-color:#cc9400;
    background:#fffdf3
}
.equipo-dia-tarjeta>strong{
    grid-column:1;font-size:17px
}
.equipo-dia-fecha{
    grid-column:1 / -1;
    font-size:13px;
    color:#526174
}
.equipo-dia-nombre{
    grid-column:1 / -1;
    font-size:16px
}
.equipo-dia-tipo{
    grid-column:1;
    font-size:14px;
    font-weight:600
}
.equipo-dia-estado{
    grid-column:2;
    font-size:13px;
    font-weight:700;
    align-self:center
}
.equipo-dia-volver{
    margin-bottom:18px
}
.equipo-dia-tarjeta[data-estado="Vencido"] 
.equipo-dia-estado{
    color:#a71f30
}
.equipo-dia-tarjeta[data-estado="Próximo a vencer"] 
.equipo-dia-estado{
    color:#745700
}
@media(max-width:575px){
    .equipo-dia-tarjeta{
        grid-template-columns:1fr
    }
    .equipo-dia-estado{
        grid-column:1
    }
}
/* Los filtros se ajustan al espacio disponible sin estirar el año. */
#equipos-calendario 
.equipo-cal-filtros{
    display:flex;
    flex-wrap:wrap;
    align-items:flex-end;
    gap:14px
}
#equipos-calendario 
.equipo-cal-filtros>label{
    flex:1 1 170px;
    min-width:0
}
#equipos-calendario 
.equipo-cal-filtros>label:has(#equipo-cal-periodo){
    flex:0 1 180px
}
#equipos-calendario 
.equipo-cal-filtros>label:has(#equipo-cal-anio){
    flex:0 0 100px
}
#equipos-calendario 
.equipo-cal-filtros>label:has(#equipo-cal-mes)
{
    flex:0 1 190px
}
#equipos-calendario 
.equipo-cal-filtros>label:has(#equipo-cal-buscar){
    flex:2 1 240px
}#equipos-calendario 
.equipo-cal-filtros>label:has(#equipo-cal-estado){
    flex:1 1 170px;
    max-width:220px
}
#equipos-calendario 
#equipo-cal-hoy{
    flex:0 0 auto
}
@media(max-width:575px){
    #equipos-calendario 
    .equipo-cal-filtros>label:has(#equipo-cal-periodo){
        flex:1 1 160px
    }
    #equipos-calendario 
    .equipo-cal-filtros>label:has(#equipo-cal-buscar),
    #equipos-calendario 
    .equipo-cal-filtros>label:has(#equipo-cal-mes){
        flex:1 1 100%
    }
    #equipos-calendario 
    .equipo-cal-filtros>label:has(#equipo-cal-estado){
        max-width:none
    }
}
.equipo-detalle-pdf{
    margin-bottom:24px;
    border:1px solid #dce4ef;
    border-radius:10px;
    overflow:hidden;
    background:#f3f6fa
}
.equipo-detalle-pdf object{
    display:block;
    width:100%;
    height:480px;
    height:min(55vh,580px)
}
.equipo-detalle-pdf object>p{
    padding:16px
}
.equipo-detalle-pdf object>a{
    margin:0 16px 16px
}

/* Vista de inventario con el aspecto original de SAICO. */
.equipos-agenda-layout{
    display:block;
    width:100%;
    max-width:100%;
    margin-inline:auto;
    padding:0 24px 24px;
    box-sizing:border-box
}
.equipos-agenda-layout>.box-body{
    display:block!important;
    width:100%;
    min-width:0;
    max-width:100%;
    margin-inline:auto
}
.equipos-agenda-contenido{
    width:100%;
    min-width:0;
    max-width:100%;
    margin-inline:auto;
    box-sizing:border-box
}
.equipos-agenda-contenido 
.dt-container{
    width:100%;
    min-width:0;
    max-width:100%;
    margin-inline:auto;
    overflow:visible
}
.equipos-agenda-contenido 
#tablaJs{
    width:100%!important;
    max-width:100%;
    table-layout:auto;
    margin-inline:auto
}
.equipos-agenda-contenido 
#tablaJs th,
.equipos-agenda-contenido 
#tablaJs td{
    font-size:14px;
    padding:8px;
    white-space:normal;
    overflow-wrap:break-word;
    text-align:center
}
.equipos-agenda-contenido 
#tablaJs th{
    font-weight:700
}
.equipos-agenda-contenido #tablaJs .btn{
    font-size:14px;
    line-height:1.5;
    padding:6px 12px;
    border-radius:4px;
    white-space:normal
}
.equipos-agenda-contenido 
#tablaJs 
.btn-group{
    display:inline-flex
}
.equipos-agenda-contenido 
.dt-layout-row{
    margin-inline:0
}
.equipos-agenda-contenido 
.dt-layout-table .dt-layout-cell{
    min-width:0;width:100%
}
@media(max-width:767px){
    .equipos-agenda-layout{
        padding-inline:10px
    }
    .equipos-agenda-contenido #tablaJs th,.equipos-agenda-contenido #tablaJs td{
        font-size:12px;
        padding:6px 4px;
        overflow-wrap:anywhere
    }
    .equipos-agenda-contenido #tablaJs .btn
    {
        font-size:12px;
        padding:5px
    }
}

/* El contenedor de AdminLTE no debe limitar el ancho de Mantenimientos. */
.container:has(.equipos-vista-mantenimientos){
    width:100%!important;
    max-width:none!important;
    padding-inline:16px;
    box-sizing:border-box
}
.equipos-agenda-layout:has(.equipos-vista-mantenimientos){
    display:block;
    width:100%;
    max-width:100%;
    padding:0 8px 24px;
    box-sizing:border-box;
    margin-inline:auto
}
.equipos-agenda-layout:has(.equipos-vista-mantenimientos)>.box-body{
    display:block!important;
    width:100%;
    max-width:1700px;
    min-width:0;
    margin-inline:auto
}
.equipos-vista-mantenimientos{
    width:100%;
    min-width:0;
    max-width:100%;
    margin-inline:auto
}
.equipos-vista-mantenimientos 
.dt-container{
    width:100%;
    max-width:100%;
    min-width:0;
    margin-inline:auto;
    overflow:visible
}
.equipos-vista-mantenimientos 
#tablaJs{
    width:100%!important;
    min-width:0!important;
    table-layout:auto;
    margin-inline:auto
}
.equipos-vista-mantenimientos 
#tablaJs th,
.equipos-vista-mantenimientos 
#tablaJs td{
    font-size:12px;
    padding:8px 5px;
    white-space:normal!important;
    overflow-wrap:normal;
    word-break:normal;
    hyphens:none
}
.equipos-vista-mantenimientos 
#tablaJs .btn{
    font-size:12px;
    padding:6px;
    line-height:1.5;
    white-space:normal;
    overflow-wrap:normal
}
.equipos-vista-mantenimientos 
#tablaJs th{
    padding-right:12px
}
.equipos-vista-mantenimientos 
.dt-layout-table 
.dt-layout-cell{
    min-width:0;
    width:100%
    }
</style>
<section id="equipos-calendario" class="mb-3" aria-label="Calendario de equipos" data-logo="{{ asset('vendor/adminlte/dist/img/Logos/LOGO-2.png') }}">
    <div class="d-flex flex-wrap align-items-end mb-3" style="gap:12px">
        <div class="btn-group" role="group" aria-label="Vista">
            <button type="button" class="btn btn-primary" data-vista="tabla" aria-pressed="true"><i class="fas fa-list" aria-hidden="true"></i> Tabla</button>
            <button type="button" class="btn btn-outline-primary" data-vista="calendario" aria-pressed="false"><i class="far fa-calendar-alt" aria-hidden="true"></i> Calendario</button>
        </div>
    </div>
    <div id="equipo-cal-panel" hidden>
        <div class="equipo-cal-encabezado"><div><span class="equipo-cal-marca">EQUIPOS</span><h3>Agenda de {{ $modoCalendario === 'mantenimiento' ? 'mantenimientos' : 'calibraciones y verificaciones' }}</h3></div><i class="far fa-calendar-alt" aria-hidden="true"></i></div>
        <div class="equipo-cal-filtros">
            <label>Vista del calendario <select id="equipo-cal-periodo" class="form-control"><option value="mes">Mensual</option><option value="anio">Año completo</option></select></label>
            <label>Año <select id="equipo-cal-anio" class="form-control"></select></label>
            <label>Mes y año <input type="month" id="equipo-cal-mes" class="form-control"></label>
            <label>Buscar equipo <input type="search" id="equipo-cal-buscar" class="form-control" placeholder="Nombre, económico o serie"></label>
            <label>Estado <select id="equipo-cal-estado" class="form-control"><option value="">Todos</option><option>Vigente</option><option>Próximo a vencer</option><option>Vencido</option><option>Sin fecha</option></select></label>
            <button type="button" class="btn btn-outline-secondary mb-2" id="equipo-cal-hoy"><i class="fas fa-calendar-day" aria-hidden="true"></i> Mes actual</button>
            <button type="button" class="equipo-detalle-boton" id="equipo-cal-excel"><i class="fas fa-file-excel" aria-hidden="true"></i> Excel anual</button>
        </div>
        <div class="equipo-cal-navegacion">
            <div class="equipo-cal-titulo"><button type="button" id="equipo-cal-anterior" class="btn btn-outline-secondary" aria-label="Mes anterior">&#8249;</button><h4 id="equipo-cal-titulo"></h4><button type="button" id="equipo-cal-siguiente" class="btn btn-outline-secondary" aria-label="Mes siguiente">&#8250;</button></div>
            <div class="equipo-cal-leyenda"><span class="vigente">Vigente</span><span class="proximo">Vence en 30 días</span><span class="vencido">Vencido</span></div>
        </div>
        <p class="text-muted equipo-cal-ayuda"></p>
        <p id="equipo-cal-resumen" role="status"></p>
        <div class="equipo-cal-scroll"><div id="equipo-cal-grid" class="equipo-cal-grid"></div></div>
        <div id="equipo-cal-anual" class="equipo-cal-anual" hidden></div>
        <details class="mt-3"><summary>Equipos sin fecha asignada</summary><div id="equipo-cal-sin-fecha"></div></details>
    </div>
    <dialog id="equipo-cal-detalle" aria-labelledby="equipo-cal-detalle-titulo"><header class="equipo-detalle-cabecera"><span>SAICO · Ficha de equipo</span><button type="button" id="equipo-cal-cerrar" aria-label="Cerrar ficha del equipo">Cerrar <span aria-hidden="true">×</span></button></header><div id="equipo-cal-contenido"></div></dialog>
    {{-- Serializar con Blade evita concatenar datos del inventario como código JavaScript. --}}
    <script type="application/json" id="equipo-cal-datos">@json($eventosEquipo)</script>
</section>
{{-- El exportador se carga antes del controlador; defer conserva este orden y espera al DOM. --}}
<script src="{{ asset('js/equipos-calendario-excel.js') }}?v={{ filemtime(public_path('js/equipos-calendario-excel.js')) }}" defer></script>
<script src="{{ asset('js/equipos-calendario.js') }}?v={{ filemtime(public_path('js/equipos-calendario.js')) }}" defer></script>
