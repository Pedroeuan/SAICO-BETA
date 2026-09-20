from __future__ import annotations

import datetime as dt
import os
import subprocess
import textwrap
import zipfile
from pathlib import Path
from xml.sax.saxutils import escape


ROOT = Path(__file__).resolve().parents[1]
DOCS_DIR = ROOT / "docs"
ASSETS_DIR = DOCS_DIR / "assets"
OUTPUT_PATH = DOCS_DIR / "Programacion_Logica_y_Funcional_SAICO.docx"


def read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="ignore")


def normalize_indent(value: str) -> str:
    return textwrap.dedent(value).strip("\n")


def code_snippet(title: str, path: Path, start_marker: str, end_marker: str) -> tuple[str, str]:
    text = read_text(path)
    start = text.find(start_marker)
    if start == -1:
        raise ValueError(f"No se encontro el inicio del fragmento: {start_marker} en {path}")
    end = text.find(end_marker, start)
    if end == -1:
        raise ValueError(f"No se encontro el fin del fragmento: {end_marker} en {path}")
    snippet = text[start:end].rstrip()
    return title, snippet


def run_powershell(script: str) -> None:
    command = [
        "powershell",
        "-NoProfile",
        "-ExecutionPolicy",
        "Bypass",
        "-Command",
        script,
    ]
    subprocess.run(command, cwd=ROOT, check=True)


def generate_assets() -> dict[str, Path]:
    ASSETS_DIR.mkdir(parents=True, exist_ok=True)

    asset_script = r"""
Add-Type -AssemblyName System.Drawing

$assets = Join-Path "__ROOT__" "docs\assets"
if (!(Test-Path $assets)) {{
    New-Item -ItemType Directory -Path $assets | Out-Null
}}

function New-Canvas([int]$width, [int]$height) {{
    $bmp = New-Object System.Drawing.Bitmap $width, $height
    $g = [System.Drawing.Graphics]::FromImage($bmp)
    $g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
    $g.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::AntiAliasGridFit
    return @($bmp, $g)
}}

function Save-Canvas($bmp, $g, [string]$path) {{
    $bmp.Save($path, [System.Drawing.Imaging.ImageFormat]::Png)
    $g.Dispose()
    $bmp.Dispose()
}}

function Brush([string]$hex) {{
    return New-Object System.Drawing.SolidBrush ([System.Drawing.ColorTranslator]::FromHtml($hex))
}}

function Pen([string]$hex, [float]$width = 1.0) {{
    return New-Object System.Drawing.Pen ([System.Drawing.ColorTranslator]::FromHtml($hex), $width)
}}

function FontObj([string]$name, [float]$size, [System.Drawing.FontStyle]$style = [System.Drawing.FontStyle]::Regular) {{
    return New-Object System.Drawing.Font($name, $size, $style)
}}

function Draw-RoundedBox($g, [string]$fill, [string]$border, [int]$x, [int]$y, [int]$w, [int]$h, [int]$radius = 18) {{
    $path = New-Object System.Drawing.Drawing2D.GraphicsPath
    $diam = $radius * 2
    [void]$path.AddArc($x, $y, $diam, $diam, 180, 90)
    [void]$path.AddArc($x + $w - $diam, $y, $diam, $diam, 270, 90)
    [void]$path.AddArc($x + $w - $diam, $y + $h - $diam, $diam, $diam, 0, 90)
    [void]$path.AddArc($x, $y + $h - $diam, $diam, $diam, 90, 90)
    [void]$path.CloseFigure()
    [void]$g.FillPath((Brush $fill), $path)
    [void]$g.DrawPath((Pen $border 2), $path)
    $path.Dispose()
}}

function Draw-Arrow($g, [int]$x1, [int]$y1, [int]$x2, [int]$y2, [string]$hex) {{
    $pen = Pen $hex 3
    $pen.CustomEndCap = New-Object System.Drawing.Drawing2D.AdjustableArrowCap(5, 7, $true)
    [void]$g.DrawLine($pen, $x1, $y1, $x2, $y2)
    $pen.Dispose()
}}

# 1) Arquitectura general
$canvas = New-Canvas 1800 980
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$titleFont = FontObj "Segoe UI" 30 ([System.Drawing.FontStyle]::Bold)
$subFont = FontObj "Segoe UI" 17
$textFont = FontObj "Segoe UI" 15
$g.DrawString("Arquitectura del sistema SAICO-BETA", $titleFont, (Brush "#0f172a"), 60, 40)
$g.DrawString("Vista sintetica para el documento de Programacion Logica y Funcional", $subFont, (Brush "#475569"), 62, 92)

Draw-RoundedBox $g "#e0f2fe" "#0284c7" 90 170 300 130
Draw-RoundedBox $g "#dcfce7" "#16a34a" 460 170 300 130
Draw-RoundedBox $g "#fef3c7" "#d97706" 830 170 300 130
Draw-RoundedBox $g "#fee2e2" "#dc2626" 1200 170 300 130
Draw-RoundedBox $g "#ede9fe" "#7c3aed" 560 430 460 180
Draw-RoundedBox $g "#f8fafc" "#334155" 560 700 460 160

$g.DrawString("Capa de Presentacion`nBlade + AdminLTE + DataTables", $subFont, (Brush "#0c4a6e"), 125, 205)
$g.DrawString("Controladores HTTP`nValidacion + flujo", $subFont, (Brush "#166534"), 505, 205)
$g.DrawString("Servicios de dominio`nReglas vehiculares y publicaciones", $subFont, (Brush "#92400e"), 870, 205)
$g.DrawString("Persistencia`nEloquent + Query Builder + MySQL", $subFont, (Brush "#991b1b"), 1245, 205)
$g.DrawString("Integraciones`nAPI movil Sanctum`nDOMPDF / Excel`nScript Python para redes sociales", $subFont, (Brush "#5b21b6"), 665, 470)
$g.DrawString("Base de conocimiento operativa`nvehiculos, salidas, checklists, publicaciones, metricas", $subFont, (Brush "#1e293b"), 620, 750)

Draw-Arrow $g 390 235 460 235 "#0f766e"
Draw-Arrow $g 760 235 830 235 "#0f766e"
Draw-Arrow $g 1130 235 1200 235 "#0f766e"
Draw-Arrow $g 980 300 980 430 "#7c3aed"
Draw-Arrow $g 790 610 790 700 "#334155"
Draw-Arrow $g 980 610 980 700 "#334155"

$g.DrawString("Flujo principal: el usuario captura datos, el backend valida reglas, transforma colecciones y consulta conocimiento persistente.", $textFont, (Brush "#334155"), 105, 915)
Save-Canvas $bmp $g (Join-Path $assets "diagrama-arquitectura-saico.png")

# 2) Flujo de salida vehicular
$canvas = New-Canvas 1600 1400
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$g.DrawString("Flujo logico: solicitud de salida vehicular y checklist", $titleFont, (Brush "#0f172a"), 70, 40)

$boxes = @(
    @{X=530;Y=130;W=520;H=90;F="#dbeafe";B="#2563eb";T="1. El usuario solicita una salida desde web o API movil"},
    @{X=530;Y=280;W=520;H=90;F="#dcfce7";B="#16a34a";T="2. FormRequest valida fecha, chofer y vehiculo"},
    @{X=530;Y=430;W=520;H=110;F="#fef3c7";B="#d97706";T="3. Reglas de negocio: disponibilidad, documentacion, licencia y salida activa"},
    @{X=530;Y=610;W=520;H=90;F="#ede9fe";B="#7c3aed";T="4. DB::transaction crea la salida y cambia el estatus del vehiculo a ocupado"},
    @{X=530;Y=760;W=520;H=100;F="#fee2e2";B="#dc2626";T="5. Checklist de salida: condiciones, documentos, herramientas y 3 evidencias"},
    @{X=530;Y=930;W=520;H=100;F="#e0f2fe";B="#0284c7";T="6. Checklist de entrada: kilometraje mayor, evidencias y cierre de salida"},
    @{X=530;Y=1100;W=520;H=110;F="#f8fafc";B="#334155";T="7. Reporte PDF y panel analitico con duracion, kilometraje, incidencias y costos"}
)

foreach ($box in $boxes) {{
    Draw-RoundedBox $g $box.F $box.B $box.X $box.Y $box.W $box.H
    $g.DrawString($box.T, $subFont, (Brush "#111827"), $box.X + 20, $box.Y + 26)
}}

for ($i = 0; $i -lt $boxes.Count - 1; $i++) {{
    $x = 790
    $y1 = $boxes[$i].Y + $boxes[$i].H
    $y2 = $boxes[$i + 1].Y
    Draw-Arrow $g $x $y1 $x $y2 "#475569"
}}

Draw-RoundedBox $g "#fee2e2" "#dc2626" 120 430 290 100
Draw-RoundedBox $g "#dcfce7" "#16a34a" 120 930 290 100
$g.DrawString("Si falla una regla,`nse retorna con mensaje de error", $subFont, (Brush "#7f1d1d"), 155, 460)
$g.DrawString("Si el retorno es valido,`nse libera el vehiculo", $subFont, (Brush "#166534"), 160, 960)
Draw-Arrow $g 530 485 410 485 "#dc2626"
Draw-Arrow $g 530 980 410 980 "#16a34a"
Save-Canvas $bmp $g (Join-Path $assets "diagrama-flujo-salida-vehicular.png")

# 3) Reglas y permisos
$canvas = New-Canvas 1600 900
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$g.DrawString("Arbol de decisiones para autorizacion y reglas de negocio", $titleFont, (Brush "#0f172a"), 60, 35)

Draw-RoundedBox $g "#dbeafe" "#2563eb" 640 110 320 90
$g.DrawString("Usuario autenticado", $subFont, (Brush "#1d4ed8"), 710, 145)
Draw-RoundedBox $g "#dcfce7" "#16a34a" 240 300 320 120
Draw-RoundedBox $g "#fef3c7" "#d97706" 640 300 320 120
Draw-RoundedBox $g "#fee2e2" "#dc2626" 1040 300 320 120
$g.DrawString("Gate::allows('vehiculos-admin-access')`nAcceso total al panel", $subFont, (Brush "#166534"), 280, 335)
$g.DrawString("Rol operativo`nSolo ve sus salidas y encuestas", $subFont, (Brush "#92400e"), 700, 335)
$g.DrawString("No coincide con reglas`nRedireccion al dashboard", $subFont, (Brush "#991b1b"), 1090, 335)
Draw-Arrow $g 800 200 400 300 "#2563eb"
Draw-Arrow $g 800 200 800 300 "#2563eb"
Draw-Arrow $g 800 200 1200 300 "#2563eb"

Draw-RoundedBox $g "#f8fafc" "#334155" 180 560 1220 180
$g.DrawString("Reglas evaluadas en el backend:", $subFont, (Brush "#0f172a"), 220, 595)
$rules = @(
    "- El vehiculo debe estar disponible",
    "- La documentacion no puede estar vencida o incompleta",
    "- El chofer no debe tener otra salida activa",
    "- La licencia debe existir y estar vigente",
    "- El checklist de entrada exige kilometraje mayor al de salida"
)
$y = 635
foreach ($rule in $rules) {{
    $g.DrawString($rule, $textFont, (Brush "#334155"), 235, $y)
    $y += 26
}}
Save-Canvas $bmp $g (Join-Path $assets "diagrama-decision-permisos.png")

# 4) Modelo entidad relacion simplificado
$canvas = New-Canvas 1700 1050
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$g.DrawString("Modelo de datos simplificado del proyecto analizado", $titleFont, (Brush "#0f172a"), 60, 35)

function Entity($g, [int]$x, [int]$y, [string]$title, [string[]]$lines, [string]$fill, [string]$border) {{
    $height = 70 + ($lines.Count * 24)
    Draw-RoundedBox $g $fill $border $x $y 300 $height 16
    $titleF = FontObj "Segoe UI" 18 ([System.Drawing.FontStyle]::Bold)
    $bodyF = FontObj "Consolas" 13
    $g.DrawString($title, $titleF, (Brush "#111827"), $x + 18, $y + 16)
    $yy = $y + 52
    foreach ($line in $lines) {{
        $g.DrawString($line, $bodyF, (Brush "#334155"), $x + 18, $yy)
        $yy += 22
    }}
}}

Entity $g 80 150 "vehiculos" @("id PK","placa","estatus","documentacion_estatus","kilometraje_actual") "#dbeafe" "#2563eb"
Entity $g 420 150 "salidas_vehiculos" @("id PK","vehiculo_id FK","chofer_id FK","fecha_salida","estatus","duracion_minutos") "#dcfce7" "#16a34a"
Entity $g 800 120 "salidas_checklists" @("id PK","salida_vehiculo_id FK","tipo") "#fef3c7" "#d97706"
Entity $g 1160 80 "checklist_condiciones" @("id PK","salida_checklist_id FK","nivel_gasolina","kilometraje","observaciones") "#fee2e2" "#dc2626"
Entity $g 1160 320 "checklist_documentos" @("id PK","salida_checklist_id FK","documento","estatus") "#fee2e2" "#dc2626"
Entity $g 1160 540 "checklist_herramientas" @("id PK","salida_checklist_id FK","herramienta","disponible") "#fee2e2" "#dc2626"
Entity $g 1160 760 "checklist_evidencias" @("id PK","salida_checklist_id FK","foto") "#fee2e2" "#dc2626"
Entity $g 80 650 "publicaciones" @("id PK","uuid","slug","tipo","redes_objetivo","resultado_publicacion") "#ede9fe" "#7c3aed"
Entity $g 420 690 "publicacion_metricas_historial" @("id PK","publicacion_id FK","fecha_corte","reacciones","comentarios","engagement") "#f5f3ff" "#7c3aed"

Draw-Arrow $g 380 230 420 230 "#334155"
Draw-Arrow $g 720 230 800 230 "#334155"
Draw-Arrow $g 1100 220 1160 180 "#334155"
Draw-Arrow $g 1100 250 1160 380 "#334155"
Draw-Arrow $g 1100 270 1160 600 "#334155"
Draw-Arrow $g 1100 290 1160 820 "#334155"
Draw-Arrow $g 380 730 420 730 "#7c3aed"

$g.DrawString("El subsistema vehicular concentra la mayor densidad de reglas lógicas; el subsistema de publicaciones concentra transformaciones funcionales y analítica.", $textFont, (Brush "#334155"), 95, 970)
Save-Canvas $bmp $g (Join-Path $assets "diagrama-er-saico.png")

# 5) Captura tecnica modulo vehiculos
$canvas = New-Canvas 1600 920
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$g.FillRectangle((Brush "#0f172a"), 0, 0, 1600, 80)
$g.DrawString("SAICO-BETA | Gestion de Vehiculos", $titleFont, (Brush "#ffffff"), 45, 18)
Draw-RoundedBox $g "#f8fafc" "#cbd5e1" 35 105 1530 760 10
Draw-RoundedBox $g "#dbeafe" "#2563eb" 55 130 250 50 8
Draw-RoundedBox $g "#dcfce7" "#16a34a" 320 130 250 50 8
Draw-RoundedBox $g "#fef3c7" "#d97706" 585 130 250 50 8
Draw-RoundedBox $g "#fee2e2" "#dc2626" 850 130 250 50 8
$g.DrawString("Listado", $subFont, (Brush "#1e3a8a"), 145, 145)
$g.DrawString("Documentacion", $subFont, (Brush "#166534"), 380, 145)
$g.DrawString("Estadisticas", $subFont, (Brush "#92400e"), 665, 145)
$g.DrawString("Movimientos", $subFont, (Brush "#991b1b"), 930, 145)

for ($i = 0; $i -lt 4; $i++) {{
    $x = 70 + ($i * 360)
    Draw-RoundedBox $g "#ffffff" "#cbd5e1" $x 215 330 110 8
}}
$g.DrawString("Total vehiculos: 24", $subFont, (Brush "#111827"), 95, 255)
$g.DrawString("Disponibles: 16", $subFont, (Brush "#111827"), 455, 255)
$g.DrawString("Ocupados: 5", $subFont, (Brush "#111827"), 815, 255)
$g.DrawString("Doc. vencida: 3", $subFont, (Brush "#111827"), 1175, 255)

$tableX = 70; $tableY = 365; $tableW = 1450; $rowH = 60
$g.FillRectangle((Brush "#e2e8f0"), $tableX, $tableY, $tableW, $rowH)
$headers = @("Vehiculo","Anio","Estado","Documentacion","Editar","Salida","Mantenimientos","Combustible")
$hx = @(90, 315, 440, 610, 860, 980, 1100, 1325)
for ($i = 0; $i -lt $headers.Count; $i++) {{
    $g.DrawString($headers[$i], $subFont, (Brush "#0f172a"), $hx[$i], $tableY + 17)
}}
for ($r = 0; $r -lt 5; $r++) {{
    $yy = $tableY + $rowH + ($r * 78)
    $fill = if ($r % 2 -eq 0) {{ "#ffffff" }} else {{ "#f8fafc" }}
    $g.FillRectangle((Brush $fill), $tableX, $yy, $tableW, 78)
    $g.DrawRectangle((Pen "#e2e8f0" 1), $tableX, $yy, $tableW, 78)
    $g.DrawString("ABC-10{0}`nNissan Versa" -f $r, $textFont, (Brush "#111827"), 92, $yy + 12)
    $g.DrawString("202{0}" -f (($r + 1) % 4), $textFont, (Brush "#111827"), 322, $yy + 26)
    $g.DrawString((@("Disponible","Ocupado","Disponible","Inactivo","Disponible")[$r]), $textFont, (Brush "#111827"), 446, $yy + 26)
    $g.DrawString((@("Completa","Vencida","Completa","Incompleta","Completa")[$r]), $textFont, (Brush "#111827"), 612, $yy + 26)
    $g.DrawString("Editar", $textFont, (Brush "#2563eb"), 872, $yy + 26)
    $g.DrawString("Nueva", $textFont, (Brush "#16a34a"), 994, $yy + 26)
    $g.DrawString("Ver", $textFont, (Brush "#d97706"), 1140, $yy + 26)
    $g.DrawString("Registrar", $textFont, (Brush "#dc2626"), 1330, $yy + 26)
}}
$g.DrawString("Reconstruccion visual basada en resources/views/vehiculos/index.blade.php", $textFont, (Brush "#64748b"), 78, 845)
Save-Canvas $bmp $g (Join-Path $assets "captura-modulo-vehiculos.png")

# 6) Captura tecnica publicaciones
$canvas = New-Canvas 1600 920
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$g.FillRectangle((Brush "#111827"), 0, 0, 1600, 80)
$g.DrawString("SAICO-BETA | Publicaciones y analitica social", $titleFont, (Brush "#ffffff"), 45, 18)
Draw-RoundedBox $g "#f8fafc" "#cbd5e1" 35 105 1530 770 10
for ($i = 0; $i -lt 4; $i++) {{
    $x = 60 + ($i * 370)
    $colors = @("#0ea5e9","#22c55e","#f59e0b","#7c3aed")
    Draw-RoundedBox $g $colors[$i] $colors[$i] $x 130 320 120 12
}}
$g.DrawString("Total publicaciones`n36", $subFont, (Brush "#ffffff"), 100, 165)
$g.DrawString("Publicadas correctamente`n21", $subFont, (Brush "#ffffff"), 470, 165)
$g.DrawString("Pendientes o parciales`n11", $subFont, (Brush "#ffffff"), 835, 165)
$g.DrawString("Importadas de Facebook`n4", $subFont, (Brush "#ffffff"), 1200, 165)

Draw-RoundedBox $g "#ffffff" "#cbd5e1" 60 295 1480 535 8
$g.FillRectangle((Brush "#e2e8f0"), 80, 320, 1440, 55)
$cols = @("Folio","Titulo","Tipo","Redes","Estado","Interacciones","Acciones")
$cx = @(105, 260, 710, 860, 1080, 1240, 1430)
for ($i = 0; $i -lt $cols.Count; $i++) {{
    $g.DrawString($cols[$i], $subFont, (Brush "#0f172a"), $cx[$i], 336)
}}
for ($r = 0; $r -lt 6; $r++) {{
    $yy = 380 + ($r * 70)
    $fill = if ($r % 2 -eq 0) {{ "#ffffff" }} else {{ "#f8fafc" }}
    $g.FillRectangle((Brush $fill), 80, $yy, 1440, 70)
    $g.DrawString("PUB-2026-0{0}" -f ($r + 1), $textFont, (Brush "#111827"), 100, $yy + 24)
    $g.DrawString("Campana vehicular / mantenimiento", $textFont, (Brush "#111827"), 260, $yy + 24)
    $g.DrawString((@("Informativa","Promocional","Aviso","Recordatorio","Informativa","Promocional")[$r]), $textFont, (Brush "#111827"), 710, $yy + 24)
    $g.DrawString("facebook", $textFont, (Brush "#2563eb"), 870, $yy + 24)
    $g.DrawString((@("exito","parcial","pendiente","exito","error","exito")[$r]), $textFont, (Brush "#111827"), 1095, $yy + 24)
    $g.DrawString((@("86","45","0","120","12","93")[$r]), $textFont, (Brush "#111827"), 1265, $yy + 24)
    $g.DrawString("Ver | Editar", $textFont, (Brush "#7c3aed"), 1430, $yy + 24)
}}
$g.DrawString("Reconstruccion visual basada en resources/views/publicaciones/index.blade.php", $textFont, (Brush "#64748b"), 80, 845)
Save-Canvas $bmp $g (Join-Path $assets "captura-modulo-publicaciones.png")

# 7) Captura tecnica panel vehicular
$canvas = New-Canvas 1600 920
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
$g.FillRectangle((Brush "#1e293b"), 0, 0, 1600, 84)
$g.DrawString("SAICO-BETA | Panel vehicular", $titleFont, (Brush "#ffffff"), 44, 20)
Draw-RoundedBox $g "#ffffff" "#cbd5e1" 40 110 1520 760 12
$tabs = @("Resumen","Graficas","Ranking","Indicadores","Alertas","Exportaciones")
for ($i = 0; $i -lt $tabs.Count; $i++) {{
    $x = 70 + ($i * 240)
    Draw-RoundedBox $g "#e2e8f0" "#94a3b8" $x 140 190 50 8
    $g.DrawString($tabs[$i], $subFont, (Brush "#0f172a"), $x + 42, 154)
}}
for ($i = 0; $i -lt 4; $i++) {{
    $x = 80 + ($i * 365)
    $colors = @("#2563eb","#16a34a","#f59e0b","#dc2626")
    Draw-RoundedBox $g $colors[$i] $colors[$i] $x 235 320 130 12
}}
$g.DrawString("24`nTotal vehiculos", $subFont, (Brush "#ffffff"), 205, 272)
$g.DrawString("16`nDisponibles", $subFont, (Brush "#ffffff"), 560, 272)
$g.DrawString("3`nDoc. vencida", $subFont, (Brush "#ffffff"), 930, 272)
$g.DrawString("2`nLicencias vencidas", $subFont, (Brush "#ffffff"), 1270, 272)

Draw-RoundedBox $g "#f8fafc" "#cbd5e1" 80 430 700 360 10
Draw-RoundedBox $g "#f8fafc" "#cbd5e1" 820 430 680 360 10
$g.DrawString("Graficas operativas", $subFont, (Brush "#0f172a"), 110, 455)
$g.DrawString("Ranking y alertas", $subFont, (Brush "#0f172a"), 850, 455)
$g.DrawEllipse((Pen "#2563eb" 3), 150, 520, 240, 180)
$g.DrawString("Salidas`npor mes", $subFont, (Brush "#2563eb"), 235, 585)
$g.DrawRectangle((Pen "#16a34a" 3), 470, 520, 230, 180)
$g.DrawString("Km y combustible", $subFont, (Brush "#16a34a"), 520, 590)

$g.DrawString("1. Nissan Versa / ABC-101", $textFont, (Brush "#111827"), 860, 530)
$g.DrawString("2. Chevrolet Aveo / DEF-204", $textFont, (Brush "#111827"), 860, 570)
$g.DrawString("3. Toyota Hilux / GHI-332", $textFont, (Brush "#111827"), 860, 610)
$g.DrawString("Alerta: 3 unidades con poliza proxima a vencer", $textFont, (Brush "#b45309"), 860, 690)
$g.DrawString("Alerta: 2 usuarios con licencia vencida", $textFont, (Brush "#b91c1c"), 860, 730)
$g.DrawString("Reconstruccion visual basada en resources/views/vehiculos/panel/index.blade.php", $textFont, (Brush "#64748b"), 83, 835)
Save-Canvas $bmp $g (Join-Path $assets "captura-panel-vehicular.png")
""".replace("__ROOT__", str(ROOT)).replace("{{", "{").replace("}}", "}")

    run_powershell(asset_script)

    assets = {
        "logo": ROOT / "public" / "images" / "Logo_AICO_R.jpg",
        "u1_existing": ASSETS_DIR / "diagrama-unidad-1-programacion-logica-funcional.png",
        "architecture": ASSETS_DIR / "diagrama-arquitectura-saico.png",
        "flow": ASSETS_DIR / "diagrama-flujo-salida-vehicular.png",
        "decision": ASSETS_DIR / "diagrama-decision-permisos.png",
        "er": ASSETS_DIR / "diagrama-er-saico.png",
        "vehiculos_capture": ASSETS_DIR / "captura-modulo-vehiculos.png",
        "publicaciones_capture": ASSETS_DIR / "captura-modulo-publicaciones.png",
        "panel_capture": ASSETS_DIR / "captura-panel-vehicular.png",
    }
    return assets


class DocBuilder:
    def __init__(self) -> None:
        self.parts: list[str] = []
        self.image_rels: list[dict[str, str | int]] = []
        self.image_counter = 1

    @staticmethod
    def _attrs(attrs: dict[str, str] | None = None) -> str:
        if not attrs:
            return ""
        return "".join(f' {name}="{escape(str(value))}"' for name, value in attrs.items())

    @staticmethod
    def _run_props(
        *,
        bold: bool = False,
        italic: bool = False,
        size: int | None = None,
        color: str | None = None,
        font: str | None = None,
        preserve: bool = False,
    ) -> str:
        parts = []
        if bold:
            parts.append("<w:b/>")
        if italic:
            parts.append("<w:i/>")
        if color:
            parts.append(f'<w:color w:val="{color}"/>')
        if size:
            parts.append(f'<w:sz w:val="{size}"/><w:szCs w:val="{size}"/>')
        if font:
            parts.append(
                f'<w:rFonts w:ascii="{escape(font)}" w:hAnsi="{escape(font)}" '
                f'w:eastAsia="{escape(font)}" w:cs="{escape(font)}"/>'
            )
        return f"<w:rPr>{''.join(parts)}</w:rPr>" if parts else ""

    def paragraph(
        self,
        text: str,
        *,
        style: str | None = None,
        align: str | None = None,
        bold: bool = False,
        italic: bool = False,
        size: int | None = None,
        color: str | None = None,
        font: str | None = None,
        page_break_before: bool = False,
        space_before: int | None = None,
        space_after: int | None = None,
    ) -> None:
        props = []
        if style:
            props.append(f'<w:pStyle w:val="{style}"/>')
        if align:
            props.append(f'<w:jc w:val="{align}"/>')
        if page_break_before:
            props.append("<w:pageBreakBefore/>")
        if space_before is not None or space_after is not None:
            before = str(space_before or 0)
            after = str(space_after or 0)
            props.append(f'<w:spacing w:before="{before}" w:after="{after}"/>')

        runs = []
        run_props = self._run_props(
            bold=bold,
            italic=italic,
            size=size,
            color=color,
            font=font,
        )
        lines = text.split("\n")
        for index, line in enumerate(lines):
            line_xml = escape(line)
            t_attr = ' xml:space="preserve"' if line.startswith(" ") or line.endswith(" ") else ""
            runs.append(f"<w:r>{run_props}<w:t{t_attr}>{line_xml}</w:t></w:r>")
            if index < len(lines) - 1:
                runs.append("<w:r><w:br/></w:r>")

        p_pr = f"<w:pPr>{''.join(props)}</w:pPr>" if props else ""
        self.parts.append(f"<w:p>{p_pr}{''.join(runs)}</w:p>")

    def heading(self, text: str, level: int) -> None:
        style = f"Heading{level}"
        self.paragraph(text, style=style, space_before=180, space_after=80)

    def page_break(self) -> None:
        self.parts.append("<w:p><w:r><w:br w:type=\"page\"/></w:r></w:p>")

    def toc(self) -> None:
        self.parts.append(
            '<w:p>'
            '<w:pPr><w:pStyle w:val="TOCHeading"/></w:pPr>'
            '<w:r><w:t>Indice</w:t></w:r>'
            "</w:p>"
        )
        self.parts.append(
            '<w:p>'
            '<w:r><w:fldChar w:fldCharType="begin"/></w:r>'
            '<w:r><w:instrText xml:space="preserve"> TOC \\o "1-3" \\h \\z \\u </w:instrText></w:r>'
            '<w:r><w:fldChar w:fldCharType="separate"/></w:r>'
            '<w:r><w:t>Actualice los campos en Word si el indice no aparece de inmediato.</w:t></w:r>'
            '<w:r><w:fldChar w:fldCharType="end"/></w:r>'
            "</w:p>"
        )

    def code_block(self, code: str) -> None:
        for line in code.rstrip("\n").split("\n"):
            self.paragraph(
                line.replace("\t", "    "),
                style="CodeBlock",
                font="Consolas",
                size=18,
                space_after=0,
            )

    def table(self, headers: list[str], rows: list[list[str]]) -> None:
        def cell(text: str, header: bool = False) -> str:
            style = "TableHeader" if header else "BodyText"
            content = (
                f'<w:p><w:pPr><w:pStyle w:val="{style}"/></w:pPr>'
                f'<w:r><w:t>{escape(text)}</w:t></w:r></w:p>'
            )
            return (
                "<w:tc>"
                "<w:tcPr><w:tcW w:w=\"0\" w:type=\"auto\"/></w:tcPr>"
                f"{content}"
                "</w:tc>"
            )

        rows_xml = []
        header_cells = "".join(cell(value, header=True) for value in headers)
        rows_xml.append(f"<w:tr>{header_cells}</w:tr>")
        for row in rows:
            row_cells = "".join(cell(value) for value in row)
            rows_xml.append(f"<w:tr>{row_cells}</w:tr>")

        table_xml = (
            "<w:tbl>"
            "<w:tblPr>"
            "<w:tblStyle w:val=\"TableGrid\"/>"
            "<w:tblW w:w=\"0\" w:type=\"auto\"/>"
            "<w:tblBorders>"
            "<w:top w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"BFC5D1\"/>"
            "<w:left w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"BFC5D1\"/>"
            "<w:bottom w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"BFC5D1\"/>"
            "<w:right w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"BFC5D1\"/>"
            "<w:insideH w:val=\"single\" w:sz=\"6\" w:space=\"0\" w:color=\"D7DCE5\"/>"
            "<w:insideV w:val=\"single\" w:sz=\"6\" w:space=\"0\" w:color=\"D7DCE5\"/>"
            "</w:tblBorders>"
            "</w:tblPr>"
            f"{''.join(rows_xml)}"
            "</w:tbl>"
        )
        self.parts.append(table_xml)

    def image(self, path: Path, title: str, width_inches: float, height_inches: float) -> None:
        rid = f"rIdImage{self.image_counter}"
        media_name = f"image{self.image_counter}{path.suffix.lower()}"
        self.image_rels.append(
            {
                "rid": rid,
                "path": str(path),
                "name": media_name,
            }
        )
        self.image_counter += 1

        cx = int(width_inches * 914400)
        cy = int(height_inches * 914400)
        pic_id = 1000 + self.image_counter
        self.parts.append(
            f"""
<w:p>
  <w:pPr><w:jc w:val="center"/></w:pPr>
  <w:r>
    <w:drawing>
      <wp:inline distT="0" distB="0" distL="0" distR="0"
        xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">
        <wp:extent cx="{cx}" cy="{cy}"/>
        <wp:docPr id="{pic_id}" name="{escape(title)}"/>
        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
          <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
            <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
              <pic:nvPicPr>
                <pic:cNvPr id="{pic_id}" name="{escape(title)}"/>
                <pic:cNvPicPr/>
              </pic:nvPicPr>
              <pic:blipFill>
                <a:blip r:embed="{rid}"/>
                <a:stretch><a:fillRect/></a:stretch>
              </pic:blipFill>
              <pic:spPr>
                <a:xfrm><a:off x="0" y="0"/><a:ext cx="{cx}" cy="{cy}"/></a:xfrm>
                <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>
              </pic:spPr>
            </pic:pic>
          </a:graphicData>
        </a:graphic>
      </wp:inline>
    </w:drawing>
  </w:r>
</w:p>
""".strip()
        )

    def build_document_xml(self) -> str:
        section_props = (
            "<w:sectPr>"
            "<w:pgSz w:w=\"12240\" w:h=\"15840\"/>"
            "<w:pgMar w:top=\"1134\" w:right=\"850\" w:bottom=\"1134\" w:left=\"1134\" w:header=\"708\" w:footer=\"708\" w:gutter=\"0\"/>"
            "</w:sectPr>"
        )
        body = "".join(self.parts) + section_props
        return (
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            '<w:document '
            'xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" '
            'xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" '
            'xmlns:o="urn:schemas-microsoft-com:office:office" '
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" '
            'xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" '
            'xmlns:v="urn:schemas-microsoft-com:vml" '
            'xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" '
            'xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" '
            'xmlns:w10="urn:schemas-microsoft-com:office:word" '
            'xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" '
            'xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" '
            'xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml" '
            'xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" '
            'xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" '
            'xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" '
            'xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" '
            'mc:Ignorable="w14 wp14 w15">'
            f"<w:body>{body}</w:body></w:document>"
        )


def styles_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:docDefaults>
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:eastAsia="Calibri" w:cs="Calibri"/>
        <w:sz w:val="22"/>
        <w:szCs w:val="22"/>
        <w:color w:val="1F2937"/>
      </w:rPr>
    </w:rPrDefault>
    <w:pPrDefault>
      <w:pPr>
        <w:spacing w:after="120" w:line="276" w:lineRule="auto"/>
      </w:pPr>
    </w:pPrDefault>
  </w:docDefaults>
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal">
    <w:name w:val="Normal"/>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/>
    <w:qFormat/>
    <w:pPr><w:jc w:val="center"/><w:spacing w:after="120"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="0F172A"/><w:sz w:val="34"/><w:szCs w:val="34"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Subtitle">
    <w:name w:val="Subtitle"/>
    <w:pPr><w:jc w:val="center"/><w:spacing w:after="80"/></w:pPr>
    <w:rPr><w:color w:val="475569"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:uiPriority w:val="9"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:before="200" w:after="90"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="0F172A"/><w:sz w:val="30"/><w:szCs w:val="30"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:uiPriority w:val="9"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:before="160" w:after="60"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="1D4ED8"/><w:sz w:val="26"/><w:szCs w:val="26"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading3">
    <w:name w:val="heading 3"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:uiPriority w:val="9"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:before="140" w:after="50"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="0F766E"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="BodyText">
    <w:name w:val="Body Text"/>
    <w:basedOn w:val="Normal"/>
    <w:pPr><w:spacing w:after="110" w:line="300" w:lineRule="auto"/></w:pPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="CodeBlock">
    <w:name w:val="Code Block"/>
    <w:basedOn w:val="Normal"/>
    <w:pPr>
      <w:spacing w:after="0"/>
      <w:ind w:left="280" w:right="140"/>
      <w:shd w:val="clear" w:color="auto" w:fill="F8FAFC"/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Consolas" w:hAnsi="Consolas" w:eastAsia="Consolas" w:cs="Consolas"/>
      <w:sz w:val="18"/><w:szCs w:val="18"/>
      <w:color w:val="0F172A"/>
    </w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Caption">
    <w:name w:val="Caption"/>
    <w:basedOn w:val="Normal"/>
    <w:pPr><w:jc w:val="center"/><w:spacing w:before="40" w:after="120"/></w:pPr>
    <w:rPr><w:i/><w:color w:val="475569"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="TOCHeading">
    <w:name w:val="TOC Heading"/>
    <w:basedOn w:val="Heading1"/>
  </w:style>
  <w:style w:type="paragraph" w:styleId="TableHeader">
    <w:name w:val="Table Header"/>
    <w:basedOn w:val="Normal"/>
    <w:rPr><w:b/><w:color w:val="0F172A"/></w:rPr>
  </w:style>
</w:styles>
"""


def settings_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:updateFields w:val="true"/>
  <w:zoom w:percent="100"/>
</w:settings>
"""


def web_settings_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:webSettings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:optimizeForBrowser/>
</w:webSettings>
"""


def theme_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="Office Theme">
  <a:themeElements>
    <a:clrScheme name="SAICO">
      <a:dk1><a:srgbClr val="0F172A"/></a:dk1>
      <a:lt1><a:srgbClr val="FFFFFF"/></a:lt1>
      <a:dk2><a:srgbClr val="1D4ED8"/></a:dk2>
      <a:lt2><a:srgbClr val="F8FAFC"/></a:lt2>
      <a:accent1><a:srgbClr val="2563EB"/></a:accent1>
      <a:accent2><a:srgbClr val="16A34A"/></a:accent2>
      <a:accent3><a:srgbClr val="D97706"/></a:accent3>
      <a:accent4><a:srgbClr val="DC2626"/></a:accent4>
      <a:accent5><a:srgbClr val="7C3AED"/></a:accent5>
      <a:accent6><a:srgbClr val="0F766E"/></a:accent6>
      <a:hlink><a:srgbClr val="2563EB"/></a:hlink>
      <a:folHlink><a:srgbClr val="7C3AED"/></a:folHlink>
    </a:clrScheme>
    <a:fontScheme name="SAICO Fonts">
      <a:majorFont><a:latin typeface="Calibri"/></a:majorFont>
      <a:minorFont><a:latin typeface="Calibri"/></a:minorFont>
    </a:fontScheme>
    <a:fmtScheme name="SAICO Format"/>
  </a:themeElements>
</a:theme>
"""


def app_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"
 xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>OpenAI Codex</Application>
  <DocSecurity>0</DocSecurity>
  <ScaleCrop>false</ScaleCrop>
  <HeadingPairs>
    <vt:vector size="2" baseType="variant">
      <vt:variant><vt:lpstr>Titulos</vt:lpstr></vt:variant>
      <vt:variant><vt:i4>18</vt:i4></vt:variant>
    </vt:vector>
  </HeadingPairs>
  <TitlesOfParts>
    <vt:vector size="18" baseType="lpstr">
      <vt:lpstr>Programacion Logica y Funcional</vt:lpstr>
    </vt:vector>
  </TitlesOfParts>
  <Company>SAICO</Company>
  <LinksUpToDate>false</LinksUpToDate>
  <SharedDoc>false</SharedDoc>
  <HyperlinksChanged>false</HyperlinksChanged>
  <AppVersion>16.0000</AppVersion>
</Properties>
"""


def core_xml() -> str:
    created = dt.datetime.now(dt.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
 xmlns:dc="http://purl.org/dc/elements/1.1/"
 xmlns:dcterms="http://purl.org/dc/terms/"
 xmlns:dcmitype="http://purl.org/dc/dcmitype/"
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>Programacion Logica y Funcional aplicada al sistema SAICO-BETA</dc:title>
  <dc:subject>Reporte tecnico academico basado en un sistema Laravel real</dc:subject>
  <dc:creator>OpenAI Codex</dc:creator>
  <cp:keywords>Laravel, PHP, MySQL, Programacion Logica, Programacion Funcional, SAICO</cp:keywords>
  <dc:description>Documento universitario que relaciona las unidades de Programacion Logica y Funcional con un proyecto real.</dc:description>
  <cp:lastModifiedBy>OpenAI Codex</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">{created}</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">{created}</dcterms:modified>
</cp:coreProperties>
"""


def rels_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
"""


def content_types_xml(image_suffixes: list[str]) -> str:
    defaults = [
        '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>',
        '<Default Extension="xml" ContentType="application/xml"/>',
    ]
    if ".png" in image_suffixes:
        defaults.append('<Default Extension="png" ContentType="image/png"/>')
    if ".jpg" in image_suffixes or ".jpeg" in image_suffixes:
        defaults.append('<Default Extension="jpg" ContentType="image/jpeg"/>')
        defaults.append('<Default Extension="jpeg" ContentType="image/jpeg"/>')
    overrides = [
        '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>',
        '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>',
        '<Override PartName="/word/settings.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml"/>',
        '<Override PartName="/word/webSettings.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.webSettings+xml"/>',
        '<Override PartName="/word/theme/theme1.xml" ContentType="application/vnd.openxmlformats-officedocument.theme+xml"/>',
        '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>',
        '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>',
    ]
    return (
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
        + "".join(defaults)
        + "".join(overrides)
        + "</Types>"
    )


def document_rels_xml(image_rels: list[dict[str, str | int]]) -> str:
    base = [
        '<Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>',
        '<Relationship Id="rIdSettings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>',
        '<Relationship Id="rIdTheme" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/>',
        '<Relationship Id="rIdWebSettings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings" Target="webSettings.xml"/>',
    ]
    for rel in image_rels:
        base.append(
            f'<Relationship Id="{rel["rid"]}" '
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" '
            f'Target="media/{rel["name"]}"/>'
        )
    return (
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
        + "".join(base)
        + "</Relationships>"
    )


def paragraph_block(builder: DocBuilder, text: str) -> None:
    for paragraph in normalize_indent(text).split("\n\n"):
        builder.paragraph(paragraph.strip(), style="BodyText")


def caption(builder: DocBuilder, text: str) -> None:
    builder.paragraph(text, style="Caption")


def add_code_example(builder: DocBuilder, title: str, code: str) -> None:
    builder.heading(title, 3)
    builder.code_block(code)
    caption(builder, f"Fragmento real extraido del proyecto: {title}.")


def build_content(builder: DocBuilder, assets: dict[str, Path]) -> None:
    today = dt.datetime.now().strftime("%d de %B de %Y")

    vehiculo_request_title, vehiculo_request_code = code_snippet(
        "Validacion tipada de vehiculos",
        ROOT / "app" / "Http" / "Requests" / "Vehiculos" / "VehiculoRequest.php",
        "class VehiculoRequest extends FormRequest",
        "}\n",
    )
    salida_store_title, salida_store_code = code_snippet(
        "Reglas de negocio para registrar salidas",
        ROOT / "app" / "Http" / "Controllers" / "Vehiculos" / "SalidaVehiculoController.php",
        "public function store(SalidaVehiculoRequest $request)",
        "\n\n    /**\n     * Display the specified resource.",
    )
    checklist_title, checklist_code = code_snippet(
        "Registro transaccional de checklist de salida",
        ROOT / "app" / "Http" / "Controllers" / "Vehiculos" / "SalidaChecklistController.php",
        "public function store(Request $request, SalidaVehiculo $salida)",
        "\n\n\n    // CHECKLIST DE ENTRADA",
    )
    publicacion_index_title, publicacion_index_code = code_snippet(
        "Filtrado funcional de publicaciones",
        ROOT / "app" / "Http" / "Controllers" / "Publicaciones" / "PublicacionController.php",
        "public function index(Request $request): View",
        "\n\n    /**\n     * Muestra el formulario de alta.",
    )
    publicacion_service_title, publicacion_service_code = code_snippet(
        "Normalizacion funcional de payload de publicaciones",
        ROOT / "app" / "Services" / "Publicaciones" / "PublicacionService.php",
        "protected function normalizarDatos(array $datos, bool $esNuevo = false): array",
        "\n\n    protected function validarArchivoImagen",
    )
    mobile_resource_title, mobile_resource_code = code_snippet(
        "Transformacion funcional para API movil",
        ROOT / "app" / "Http" / "Resources" / "Mobile" / "MobileChecklistResource.php",
        "class MobileChecklistResource extends JsonResource",
        "}\n",
    )
    auth_title, auth_code = code_snippet(
        "Definicion declarativa de reglas de acceso",
        ROOT / "app" / "Providers" / "AuthServiceProvider.php",
        "class AuthServiceProvider extends ServiceProvider",
        "}\n",
    )
    vehiculo_model_title, vehiculo_model_code = code_snippet(
        "Inferencia logica del estatus documental",
        ROOT / "app" / "Models" / "Vehiculos" / "Vehiculo.php",
        "class Vehiculo extends Model",
        "}\n",
    )
    panel_title, panel_code = code_snippet(
        "Agregaciones y consultas del panel vehicular",
        ROOT / "app" / "Http" / "Controllers" / "Vehiculos" / "PanelController.php",
        "public function index()",
        "\n\n        // INCIDENCIAS: salida con al menos una observacion en checklist de salida/entrada",
    )

    builder.paragraph("Tecnologico Nacional de Mexico", style="Title")
    builder.paragraph("Ingenieria en Sistemas Computacionales", style="Subtitle")
    builder.paragraph("Materia: Programacion Logica y Funcional", style="Subtitle")
    builder.paragraph("Proyecto: SAICO-BETA - Gestion de Vehiculos, Publicaciones y Analitica Operativa", style="Subtitle")
    if assets["logo"].exists():
        builder.image(assets["logo"], "Logo SAICO", 3.0, 1.4)
    builder.paragraph("Reporte tecnico universitario aplicado a un sistema web real", style="Subtitle")
    builder.paragraph("Alumno: Francisco Felix (dato inferido del historial tecnico del repositorio)", style="Subtitle")
    builder.paragraph("Docente: [Campo editable por no existir referencia explicita en el repositorio]", style="Subtitle")
    builder.paragraph(f"Fecha: {today}", style="Subtitle")
    builder.page_break()

    builder.toc()
    builder.page_break()

    builder.heading("Introduccion general", 1)
    paragraph_block(
        builder,
        """
        La materia Programacion Logica y Funcional suele estudiarse como un cuerpo teorico separado del desarrollo web empresarial. En este proyecto ocurre lo contrario: los conceptos de ambas corrientes se manifiestan todos los dias en la ejecucion del sistema SAICO-BETA, un sistema Laravel que administra vehiculos, checklists de salida y entrada, publicaciones en redes sociales, paneles analiticos, reportes PDF y una API movil autenticada con Sanctum. El valor academico del proyecto radica en que los conceptos no se quedaron en ejercicios aislados, sino que fueron convertidos en reglas de negocio, flujos de validacion, consultas de conocimiento persistente, transformaciones de colecciones y estructuras de datos que soportan operaciones reales.

        En el paradigma funcional, el sistema aprovecha colecciones, clausuras, callbacks, transformaciones inmutables de arreglos y composicion de funciones para filtrar publicaciones, generar paneles de analitica, mapear respuestas API y construir graficas y reportes. En el paradigma logico, el mismo sistema toma decisiones a partir de predicados operativos: si un vehiculo esta disponible, si la documentacion esta vigente, si el chofer posee licencia valida, si una salida ya fue finalizada, si un usuario puede acceder a un panel determinado y si una publicacion debe publicarse o mantenerse en cola.

        Esta integracion convierte al proyecto en un caso de estudio pertinente para un informe de nivel universitario, porque permite explicar la teoria desde evidencia tecnica verificable. Cada unidad de la materia se vincula con modulos concretos, archivos reales y fragmentos del codigo fuente que resuelven problemas operativos dentro de la organizacion.
        """,
    )

    builder.heading("Objetivo general", 1)
    paragraph_block(
        builder,
        """
        Analizar de manera integral la aplicacion de los paradigmas de programacion logica y programacion funcional dentro del sistema web SAICO-BETA, demostrando como los temas de la asignatura se implementan en modulos reales, reglas de negocio, consultas de datos, transformaciones de colecciones, dashboards y procesos transaccionales del proyecto.
        """,
    )

    builder.heading("Objetivos especificos", 1)
    for item in [
        "1. Identificar en el codigo fuente los estilos de programacion, funciones, tipos, expresiones y validaciones utilizados en los modulos de vehiculos, salidas y publicaciones.",
        "2. Explicar como las colecciones Laravel, las funciones anonimas, los callbacks y las operaciones de transformacion de datos soportan la analitica y la API movil.",
        "3. Relacionar la logica de primer orden, las reglas, la autorizacion y la resolucion de condiciones con el comportamiento del backend.",
        "4. Examinar como el sistema usa consultas, relaciones Eloquent, filtros y agregaciones SQL para operar como una base de conocimiento empresarial.",
        "5. Integrar evidencias visuales, diagramas, tablas y fragmentos de codigo en un documento tecnico listo para entrega academica.",
    ]:
        builder.paragraph(item, style="BodyText")

    builder.heading("Descripcion general del sistema web", 1)
    paragraph_block(
        builder,
        """
        SAICO-BETA es un sistema web construido con Laravel 11, PHP 8.2 y MySQL, orientado a la gestion operativa y documental de procesos institucionales. Dentro del alcance visible del repositorio se distinguen tres nucleos principales: el modulo de vehiculos, el modulo de publicaciones para redes sociales y el modulo de usuarios y permisos. A estos se suman componentes de movilidad, reportes PDF, exportaciones Excel, dashboards con AdminLTE y tablas interactivas mediante DataTables.

        El modulo vehicular administra unidades, documentacion obligatoria, mantenimientos, pagos, combustible, llantas, salidas operativas, checklists de inspeccion, evidencias fotograficas y encuestas de satisfaccion. El modulo de publicaciones controla el alta, programacion, publicacion y seguimiento analitico de contenido social, utilizando estructuras JSON, soft deletes, filtros funcionales y sincronizacion con procesos externos. La aplicacion movil consume el mismo dominio a traves de recursos JSON y servicios especializados, lo que confirma una arquitectura reutilizable y consistente.
        """,
    )
    builder.table(
        ["Componente", "Tecnologia", "Funcion tecnica dentro del proyecto"],
        [
            ["Backend principal", "Laravel 11 + PHP 8.2", "Orquestacion de rutas, controladores, servicios, validacion, autorizacion y acceso a datos."],
            ["Persistencia", "MySQL + Eloquent ORM + Query Builder", "Gestion de vehiculos, salidas, checklists, publicaciones, metricas y relaciones."],
            ["Interfaz web", "Blade + AdminLTE + Bootstrap", "Construccion del panel administrativo y formularios operativos."],
            ["Tablas dinamicas", "DataTables", "Busqueda, paginacion, ordenamiento y presentacion de registros."],
            ["Reportes", "DOMPDF y Laravel Excel", "Generacion de PDF y exportaciones operativas."],
            ["Movilidad", "API Laravel Sanctum + Flutter", "Consumo movil de catalogos, salidas y checklists."],
            ["Integracion externa", "Script Python y Facebook Graph", "Publicacion automatizada y analitica social."],
        ],
    )
    builder.image(assets["architecture"], "Arquitectura del sistema", 6.5, 3.55)
    caption(builder, "Figura 1. Arquitectura sintetica del sistema SAICO-BETA empleada como marco de referencia del analisis.")

    builder.heading("Unidad 1. Conceptos fundamentales", 1)
    paragraph_block(
        builder,
        """
        La primera unidad de la asignatura aborda estilos de programacion, evaluacion de expresiones, tipos de datos, disciplina de tipos y funciones. En el proyecto estos conceptos no se presentan como definiciones aisladas, sino como una base operativa necesaria para mantener consistencia documental, integridad de salidas vehiculares y confiabilidad en la informacion mostrada al usuario.
        """,
    )

    builder.heading("1.1 Estilos de programacion presentes en el sistema", 2)
    paragraph_block(
        builder,
        """
        El sistema combina varios estilos de programacion. El estilo imperativo aparece cuando un controlador valida paso a paso el estado del dominio y decide si continuar o interrumpir una operacion. El estilo orientado a objetos domina en controladores, modelos, servicios y recursos JSON. El estilo declarativo se aprecia en Eloquent y Query Builder, donde el desarrollador expresa que informacion necesita y bajo que condiciones, mientras el ORM determina la traduccion a SQL. Finalmente, el estilo funcional se observa en el uso de colecciones, closures y transformaciones encadenadas.

        Esta mezcla controlada es importante desde el punto de vista academico porque demuestra que en aplicaciones reales los paradigmas no compiten entre si; se complementan. En SAICO-BETA la parte imperativa resuelve flujo de negocio, la parte declarativa reduce complejidad en consultas, la parte funcional mejora legibilidad al transformar datos y la orientacion a objetos encapsula el dominio.
        """,
    )
    builder.table(
        ["Estilo", "Evidencia real", "Modulo", "Ventaja obtenida"],
        [
            ["Imperativo", "Validaciones secuenciales con retornos tempranos", "SalidaVehiculoController", "Bloquea estados invalidos antes de escribir en la base."],
            ["Declarativo", "Consultas con where, when, with y selectRaw", "Vehiculos y Publicaciones", "Reduce codigo repetitivo y facilita mantenimiento."],
            ["Funcional", "map, filter, collect, closures y transformaciones JSON", "Publicaciones y API movil", "Reutilizacion, composicion y claridad en pipelines de datos."],
            ["Orientado a objetos", "Modelos, servicios y recursos especializados", "Todo el proyecto", "Encapsulamiento del comportamiento del dominio."],
        ],
    )

    builder.heading("1.2 Evaluacion de expresiones y control de flujo", 2)
    paragraph_block(
        builder,
        """
        La evaluacion de expresiones en el sistema tiene una relevancia operativa directa. En el modulo vehicular cada expresion booleana define si un vehiculo puede salir, si una licencia es vigente, si una tabla existe o si una encuesta debe mostrarse al usuario. En este sentido, una expresion no es solo sintaxis; es una formula que gobierna el comportamiento del sistema.

        Un ejemplo claro se encuentra en la evaluacion de la pestaña activa del modulo de vehiculos. El controlador revisa si el parametro recibido pertenece al conjunto permitido y, si no es asi, usa un valor por defecto. Este patron evita estados ilegales de interfaz y refleja una evaluacion controlada de expresiones de pertenencia, una nocion basica en programacion logica aplicada a la navegacion del sistema.
        """,
    )
    add_code_example(builder, vehiculo_request_title, vehiculo_request_code)

    builder.heading("1.3 Tipos de datos y disciplina de tipos", 2)
    paragraph_block(
        builder,
        """
        Aunque PHP es un lenguaje de tipado dinamico, el proyecto impone disciplina de tipos a traves de Form Requests, cast de modelos, validaciones de archivos, enumeraciones y conversiones explicitas. Esta decision es fundamental porque el sistema administra fechas de vencimiento, montos, kilometrajes, banderas booleanas, arreglos JSON y archivos PDF o imagenes. Permitir que estos datos circulen sin control produciria errores operativos y reportes inconsistentes.

        El modulo vehicular es especialmente ilustrativo. Antes de validar, la solicitud normaliza la placa para asegurar comparabilidad semantica. Posteriormente se restringen dominios: el anio debe ser entero, el estatus solo puede pertenecer al conjunto definido y los archivos documentales deben coincidir con MIME types concretos. Esto equivale a una disciplina de tipos aplicada sobre un entorno dinamico, estrategia comun en sistemas empresariales PHP maduros.
        """,
    )
    builder.table(
        ["Dato", "Tipo esperado", "Mecanismo de control", "Impacto funcional"],
        [
            ["placa", "string normalizado", "prepareForValidation + unique", "Evita duplicados y diferencias por mayusculas/minusculas."],
            ["anio", "integer", "min y max dinamico", "Impide anos fuera del dominio operativo."],
            ["foto_principal", "imagen", "mimes y max", "Garantiza evidencia visual compatible."],
            ["redes_objetivo", "array JSON", "rules + cast de modelo", "Permite iteracion funcional y analitica posterior."],
            ["programado_at", "datetime", "after:now + cast", "Controla publicaciones programadas sin ambiguedad temporal."],
        ],
    )

    builder.heading("1.4 Funciones y modularidad", 2)
    paragraph_block(
        builder,
        """
        La funcion, entendida como unidad de transformacion y encapsulamiento, es una pieza estructural del proyecto. El sistema separa responsabilidades mediante funciones de controlador, metodos de servicio, accessors, scopes y callbacks. Esto evita que la logica quede diseminada en vistas o rutas y facilita pruebas parciales del comportamiento.

        En el modulo vehicular destacan funciones como metricas, puedeVerTodasLasSalidas, crearNotificacionesLicencias y los metodos del modelo Vehiculo que calculan el estatus documental. En el modulo de publicaciones destacan resolverPanelAnalitica, normalizarDatos y ejecutarScriptPython. Cada una representa una abstraccion funcional: recibe un estado de entrada, aplica reglas y produce una salida estable.
        """,
    )
    add_code_example(builder, vehiculo_model_title, vehiculo_model_code)
    if assets["u1_existing"].exists():
        builder.image(assets["u1_existing"], "Diagrama de apoyo unidad 1", 6.4, 3.6)
        caption(builder, "Figura 2. Recurso visual existente en el repositorio para apoyar la relacion entre paradigma y modulo.")

    builder.heading("1.5 Aplicacion concreta en interfaz y operaciones", 2)
    paragraph_block(
        builder,
        """
        La vista de gestion vehicular muestra como los conceptos fundamentales aterrizan en una interfaz real. Las pestañas separan conjuntos logicos de informacion; las tarjetas estadisticas encapsulan resultados de expresiones agregadas; la tabla de listado presenta estados documentales y operativos derivados de reglas del dominio; y los accesos contextuales a mantenimientos, pagos, combustible y llantas demuestran modularidad funcional sobre una misma entidad central.
        """,
    )
    builder.image(assets["vehiculos_capture"], "Captura tecnica modulo vehiculos", 6.5, 3.75)
    caption(builder, "Figura 3. Reconstruccion visual del modulo de gestion vehicular a partir de la vista Blade real.")

    builder.heading("Unidad 2. Modelo de programacion funcional", 1)
    paragraph_block(
        builder,
        """
        La segunda unidad se enfoca en funciones, operadores, listas, arboles, evaluacion perezosa y estructuras funcionales. En SAICO-BETA estos conceptos aparecen de forma clara en el tratamiento de colecciones Laravel, los filtros del modulo de publicaciones, la composicion de paneles analiticos, la serializacion de recursos JSON y la preparacion de conjuntos de datos para reportes.
        """,
    )

    builder.heading("2.1 Funciones, callbacks y composicion", 2)
    paragraph_block(
        builder,
        """
        El modulo de publicaciones constituye un caso evidente de composicion funcional. El controlador recibe criterios de filtrado y, mediante when y closures, compone una consulta que solo agrega comportamiento cuando la entrada del usuario lo exige. Este patron evita condicionales extensos, conserva legibilidad y demuestra una forma declarativa de construir comportamiento.

        La composicion tambien aparece en la API movil. El recurso MobileChecklistResource carga relaciones faltantes y luego transforma documentos, herramientas y evidencias mediante map. Cada callback transforma un elemento del dominio en una estructura estable para el cliente movil. Esto se acerca al modelo funcional porque la salida depende del valor de entrada y no de efectos colaterales de la vista.
        """,
    )
    add_code_example(builder, publicacion_index_title, publicacion_index_code)
    add_code_example(builder, mobile_resource_title, mobile_resource_code)

    builder.heading("2.2 Listas, colecciones y operaciones map/filter/reduce", 2)
    paragraph_block(
        builder,
        """
        En la teoria funcional, una lista permite modelar una secuencia procesable mediante operaciones uniformes. En Laravel, las colecciones extienden esta idea con una API rica para mapear, filtrar, agrupar, reducir y ordenar resultados. SAICO-BETA explota esta capacidad en los paneles de publicaciones y en el modulo vehicular, donde se calculan conteos, se toman top N elementos, se convierten arreglos a estructuras listas para graficarse y se reordenan series temporales.

        Por ejemplo, la vista de publicaciones transforma la coleccion paginada con filter para distinguir publicaciones exitosas, parciales o pendientes. El controlador, por su parte, prepara estructuras para graficas construyendo arreglos de labels y values. Este patron tiene un comportamiento funcional porque el pipeline es una sucesion de transformaciones puras sobre colecciones: contar, resumir, etiquetar y presentar.
        """,
    )
    builder.table(
        ["Operacion funcional", "Evidencia del proyecto", "Uso concreto", "Beneficio"],
        [
            ["collect()", "VehiculoController y PublicacionService", "Crear colecciones intermedias para resumenes y estados", "Expresividad y encadenamiento."],
            ["map()", "MobileChecklistResource y PanelController", "Transformar entidades a DTO o series de grafica", "Separacion entre dominio y presentacion."],
            ["filter()", "publicaciones/index.blade.php", "Contar publicaciones exitosas, parciales y pendientes", "Lectura clara de criterios de clasificacion."],
            ["pluck()", "PanelController", "Extraer series de etiquetas y valores", "Preparacion directa de dashboards."],
            ["contains()", "PublicacionService", "Detectar si alguna red publico con exito", "Decision global basada en un conjunto de resultados."],
        ],
    )

    builder.heading("2.3 Operadores y transformaciones de datos", 2)
    paragraph_block(
        builder,
        """
        El proyecto usa operadores aritmeticos, relacionales y logicos no como ejercicios academicos, sino como instrumentos para producir indicadores. El promedio de tiempo de uso, la variacion mensual, el costo por kilometro, el NPS interno y la deteccion de kilometraje anomalo surgen de expresiones compuestas que transforman datos crudos en informacion gerencial.

        En el panel vehicular, por ejemplo, se emplean agregaciones SQL con selectRaw, sumatorias condicionadas y funciones como GREATEST para impedir kilometrajes negativos. Desde el punto de vista funcional, estas expresiones se comportan como transformaciones sobre conjuntos, no sobre variables aisladas. Cada fila o agregado pasa por una funcion implita que construye un nuevo valor de negocio.
        """,
    )
    add_code_example(builder, panel_title, panel_code)

    builder.heading("2.4 Estructuras funcionales y evaluacion diferida", 2)
    paragraph_block(
        builder,
        """
        La evaluacion perezosa no se implementa de forma academica pura como en Haskell, pero el proyecto si aplica una version pragmatica. Las consultas Eloquent solo se ejecutan cuando se invoca get, first, paginate o pluck; hasta ese momento, el sistema construye una descripcion diferida de la operacion. Esta estrategia ahorra costo computacional y permite componer filtros antes de materializar resultados.

        El metodo normalizarDatos del servicio de publicaciones tambien ilustra una estructura funcional reutilizable. En lugar de dispersar reglas de armado del payload en create y update, el sistema concentra la transformacion en una funcion que recibe un arreglo, filtra redes no habilitadas, normaliza banderas booleanas y resuelve la fecha programada. Se trata de una funcion de dominio reusable, determinista y con baja dependencia del contexto visual.
        """,
    )
    add_code_example(builder, publicacion_service_title, publicacion_service_code)

    builder.heading("2.5 Dashboard y evidencia funcional", 2)
    paragraph_block(
        builder,
        """
        La interfaz de publicaciones resume visualmente el resultado del enfoque funcional. Los conteos de publicaciones, el estado por red, la publicacion destacada y las graficas de interaccion parten de pipelines de transformacion sobre colecciones reales. La capa visual solo consume datos ya modelados, lo que reduce acoplamiento entre negocio y presentacion.
        """,
    )
    builder.image(assets["publicaciones_capture"], "Captura tecnica publicaciones", 6.5, 3.75)
    caption(builder, "Figura 4. Reconstruccion visual del panel de publicaciones y analitica social.")

    builder.heading("Unidad 3. Programacion logica", 1)
    paragraph_block(
        builder,
        """
        La tercera unidad estudia logica de primer orden, unificacion, resolucion, clausulas de Horn y reglas. En sistemas empresariales construidos con frameworks imperativos, estas ideas no desaparecen; se encarnan en reglas del dominio que determinan permisos, autorizaciones, consistencia documental y transiciones de estado. SAICO-BETA es especialmente rico en esta dimension porque la seguridad y el flujo vehicular dependen de decisiones booleanas encadenadas.
        """,
    )

    builder.heading("3.1 Logica de primer orden en reglas del negocio", 2)
    paragraph_block(
        builder,
        """
        La logica de primer orden trabaja con predicados sobre individuos. En el proyecto, individuos como vehiculo, chofer, salida o publicacion son evaluados por predicados concretos: disponible(vehiculo), documentacionCompleta(vehiculo), licenciaVigente(chofer), salidaActiva(chofer), puedeVerTodasLasSalidas(usuario), estaProgramada(publicacion). El codigo no usa notacion matematica explicita, pero su estructura es equivalente.

        La operacion de registrar una salida vehicular puede expresarse logicamente asi: se permite crear una salida si y solo si el vehiculo esta disponible, la documentacion es valida, el chofer no tiene otra salida activa y la licencia esta vigente. El controlador implementa esta conjuncion mediante retornos tempranos; si cualquier predicado falla, la resolucion del problema concluye con rechazo.
        """,
    )
    add_code_example(builder, salida_store_title, salida_store_code)

    builder.heading("3.2 Unificacion y resolucion de estados", 2)
    paragraph_block(
        builder,
        """
        La unificacion, entendida de manera aplicada, consiste en hacer coincidir hechos y condiciones para determinar una accion. En el sistema, un hecho como documentacion_estatus = 'vencida' se unifica con una regla que impide la salida y con otra que obliga a mostrar alertas en dashboard. Un mismo hecho del dominio resuelve diferentes consecuencias segun el contexto de ejecucion.

        La resolucion aparece cuando el sistema encadena reglas hasta cerrar una conclusion. Si el usuario consulta el dashboard, primero se revisa autenticacion; despues se consulta Gate; luego se decide a que modulo redirigir. Si el usuario genera un checklist de entrada, primero se valida que exista checklist de salida; luego que la salida no este finalizada; despues que exista kilometraje previo; y finalmente que el kilometraje de retorno sea estrictamente mayor. Cada paso reduce el espacio de estados posibles.
        """,
    )
    builder.image(assets["decision"], "Diagrama de decisiones", 6.5, 3.6)
    caption(builder, "Figura 5. Arbol de decisiones extraido de permisos, gates y reglas de negocio del backend.")

    builder.heading("3.3 Clausulas de Horn y reglas declarativas", 2)
    paragraph_block(
        builder,
        """
        Una clausula de Horn puede leerse como una implicacion del tipo A <- B1 y B2 y B3. Varias reglas del sistema responden exactamente a esa forma. Por ejemplo: permitir_salida <- vehiculo_disponible y documentacion_completa y licencia_vigente y no_existe_salida_activa. Del mismo modo: mostrar_encuesta <- salida_finalizada y usuario_relacionado_con_salida y encuesta_no_respondida.

        Esta observacion es pedagogicamente importante porque demuestra que la programacion logica no necesita un interprete Prolog para ser visible. El programador puede construir un motor de reglas distribuido en controladores, modelos y middleware, siempre que las conclusiones dependan de hechos verificables y combinaciones de predicados.
        """,
    )
    builder.table(
        ["Regla logica", "Hechos evaluados", "Ubicacion en el proyecto", "Resultado"],
        [
            ["Permitir salida vehicular", "estatus, documentacion, licencia, salida activa", "SalidaVehiculoController::store", "Crea la salida o retorna error."],
            ["Permitir checklist de entrada", "existencia de checklist de salida, estatus no finalizado, km final > km inicial", "SalidaChecklistController::storeEntrada", "Cierra la salida y libera el vehiculo."],
            ["Autorizar panel vehicular", "rol del usuario", "AuthServiceProvider + DashboardController", "Redireccion diferenciada por privilegios."],
            ["Mostrar encuesta", "salida finalizada y relacion del usuario con la salida", "salidas/index.blade.php", "Activa boton de respuesta o etiqueta de estado."],
        ],
    )

    builder.heading("3.4 Middleware, permisos y seguridad como logica aplicada", 2)
    paragraph_block(
        builder,
        """
        Los gates de Laravel y el middleware RestrictToCoreModules implementan una capa de logica institucional. El sistema no solo responde a inputs tecnicos; tambien representa politicas de acceso. Un usuario con rol administrativo puede ver paneles globales; uno operativo solo visualiza sus salidas y encuestas; rutas ajenas al alcance de esta version son redirigidas al dashboard con advertencia.

        Desde la perspectiva de la materia, esto equivale a una base de reglas de autorizacion. Cada gate define un predicado sobre el usuario y cada middleware decide la continuidad de la derivacion. Si la condicion es verdadera, la peticion sigue avanzando; si es falsa, el sistema aplica una conclusion alternativa. Ese es el nucleo de la programacion logica en un framework web.
        """,
    )
    add_code_example(builder, auth_title, auth_code)

    builder.heading("3.5 Flujo completo de resolucion logica", 2)
    paragraph_block(
        builder,
        """
        El flujo de salida y checklist sintetiza la unidad completa porque encadena hechos, reglas, transacciones y estados persistentes. Primero se resuelve si la salida puede existir. Despues se genera un folio, se crea la salida y se cambia el estatus del vehiculo. Posteriormente se deriva automaticamente el estado de documentos del checklist y se almacenan herramientas y evidencias. Finalmente, en el retorno se fuerza una condicion adicional: el kilometraje final debe superar al inicial, con lo cual se evita una contradiccion operacional.
        """,
    )
    builder.image(assets["flow"], "Flujo de salida y checklist", 6.0, 5.2)
    caption(builder, "Figura 6. Flujo operativo y logico del proceso vehicular mas representativo del sistema.")

    builder.heading("Unidad 4. Modelo de programacion logica", 1)
    paragraph_block(
        builder,
        """
        La cuarta unidad profundiza en bases de conocimiento, consultas, espacios de busqueda, manipulacion de terminos, listas, arboles y control de busqueda. En SAICO-BETA esta unidad se refleja en la forma de organizar la base de datos, consultar relaciones, filtrar conjuntos, construir reportes y recuperar evidencias para la toma de decisiones.
        """,
    )

    builder.heading("4.1 Base de conocimiento del sistema", 2)
    paragraph_block(
        builder,
        """
        La base de conocimiento operativa del proyecto esta formada por tablas relacionadas que almacenan hechos sobre vehiculos, usuarios, salidas, checklists, publicaciones y metricas. Cada registro no es solo un dato aislado; es un hecho susceptible de ser consultado, relacionado y derivado. Cuando el sistema calcula un dashboard, responde a una consulta sobre esa base. Cuando determina si una unidad puede salir, consulta hechos persistentes y aplica reglas sobre ellos.

        En terminos del modelo logico, las tablas funcionan como repositorios de predicados instanciados. Por ejemplo, vehiculo(placa, estatus, documentacion_estatus), salida(vehiculo_id, chofer_id, fecha_salida, estatus) o publicacion(titulo, tipo, redes_objetivo, publicado_en_redes). Eloquent actua como el lenguaje intermedio que permite interrogar esa base de conocimiento sin abandonar el dominio del proyecto.
        """,
    )
    builder.image(assets["er"], "Modelo entidad relacion simplificado", 6.5, 4.0)
    caption(builder, "Figura 7. Modelo de datos simplificado con las entidades necesarias para el analisis academico.")

    builder.heading("4.2 Consultas, filtros y espacio de busqueda", 2)
    paragraph_block(
        builder,
        """
        Cada consulta delimita un espacio de busqueda. Cuando el usuario abre el listado de publicaciones con filtros por tipo y estado, el controlador restringe el universo mediante when. Cuando el panel vehicular resume costos de combustible por mes, la consulta incorpora whereYear, groupBy y pluck. Cuando la API movil solo muestra salidas visibles para un usuario operativo, el servicio agrega un cierre where que encapsula el criterio de visibilidad.

        Desde la teoria, controlar el espacio de busqueda es esencial para llegar a soluciones relevantes sin explorar combinaciones inutiles. En SAICO-BETA esto se traduce en consultas con relaciones cargadas de forma precisa, agregaciones solo cuando son necesarias y filtros que dependen del rol o del periodo solicitado.
        """,
    )

    builder.heading("4.3 Relaciones entre tablas y manipulacion de terminos", 2)
    paragraph_block(
        builder,
        """
        La manipulacion de terminos en el sistema se observa en la forma de navegar relaciones. Un vehiculo tiene muchas salidas; una salida pertenece a un chofer y a un solicitante; una salida tiene uno o dos checklists segun el punto del flujo; un checklist tiene una condicion, documentos, herramientas y evidencias; una publicacion tiene historico de metricas. Estas relaciones permiten construir terminos compuestos del dominio sin escribir SQL repetitivo.

        Al modelar correctamente belongsTo, hasMany y hasOne, el proyecto convierte la base de datos en un grafo navegable. Esto facilita consultas compuestas, carga ansiosa y serializacion para web o movil. La logica del sistema deja de depender de joins dispersos y se apoya en una estructura semantica reutilizable.
        """,
    )

    builder.heading("4.4 Consultas SQL y analitica derivada", 2)
    paragraph_block(
        builder,
        """
        El proyecto no se limita a CRUD basico. Usa consultas agregadas para derivar conocimiento: total de salidas, salidas activas, promedio de duracion, kilometros recorridos, costo por combustible, llantas activas, tendencia de encuestas y top de vehiculos o solicitantes. Cada una de estas salidas representa una inferencia sobre multiples hechos de la base.

        El uso de selectRaw y joins evidencia un enfoque de programacion logica orientado a consulta. En lugar de cargar todo en memoria y procesarlo con ciclos largos, el sistema delega una parte importante del razonamiento al motor SQL, aprovechando agregaciones, filtros temporales y combinaciones relacionales para obtener respuestas ya estructuradas.
        """,
    )
    add_code_example(builder, checklist_title, checklist_code)
    builder.heading("4.5 API movil y control de busqueda contextual", 2)
    paragraph_block(
        builder,
        """
        La aplicacion movil introduce un caso adicional de control de busqueda: el mismo conocimiento se consulta con una perspectiva restringida al usuario autenticado. El servicio MobileVehiculoService implementa este principio al decidir si un usuario puede ver todas las salidas o solo aquellas donde participa como chofer o solicitante. Esta es una busqueda contextualizada por rol, identidad y estado del flujo.
        """,
    )
    builder.code_block(read_text(ROOT / "routes" / "api_mobile.php").strip())
    caption(builder, "Fragmento real de rutas API que exponen el conocimiento vehicular al cliente movil.")

    builder.heading("Metodologia", 1)
    paragraph_block(
        builder,
        """
        El desarrollo observado en el repositorio responde a una metodologia incremental y modular. La evidencia proviene de las migraciones, que muestran ampliaciones progresivas del dominio vehicular y de publicaciones; de los servicios especializados, que encapsulan evolucion funcional; y de la coexistencia de vistas web, API movil y exportadores, lo cual refleja iteraciones sucesivas sobre un mismo nucleo de negocio.

        En la fase de analisis se identificaron entidades principales, reglas de operacion y restricciones documentales. En diseño se definieron modelos Eloquent, relaciones, requests y rutas segmentadas por modulo. En implementacion se construyeron controladores, servicios, vistas Blade, reportes PDF y una API movil. En pruebas, el repositorio incluye una base de tests y validaciones de entrada que actuan como barrera minima de calidad. Adicionalmente, el uso de transacciones DB::transaction sugiere preocupacion explicita por la consistencia ante fallos parciales.
        """,
    )

    builder.heading("Implementacion en el sistema", 1)
    paragraph_block(
        builder,
        """
        La implementacion real se organiza por capas y modulos. Las rutas web definen accesos protegidos por auth y por gates. Los controladores coordinan casos de uso. Los Form Requests realizan saneamiento y validacion. Los modelos concentran relaciones, casts y eventos de dominio. Los servicios agrupan logica de negocio reutilizable. Las vistas Blade, apoyadas en AdminLTE y DataTables, resuelven la capa operativa de presentacion. DOMPDF y Laravel Excel producen artefactos documentales y de analitica. Finalmente, la API movil replica los casos de uso esenciales del flujo vehicular para consumo externo.

        Este arreglo arquitectonico es relevante para la asignatura porque permite ubicar con claridad donde vive cada paradigma. La logica declarativa y funcional predomina en consultas, recursos y paneles; la logica imperativa y de reglas vive en controladores y servicios; la persistencia representa la base de conocimiento; y la interfaz evidencia como ese conocimiento se vuelve accion para el usuario final.
        """,
    )
    builder.image(assets["panel_capture"], "Captura tecnica panel vehicular", 6.5, 3.75)
    caption(builder, "Figura 8. Reconstruccion visual del panel vehicular, donde confluyen consultas, reglas y analitica.")

    builder.table(
        ["Modulo", "Archivos clave del proyecto", "Aporte a la materia"],
        [
            ["Gestion de vehiculos", "VehiculoController, VehiculoRequest, Vehiculo model, index.blade.php", "Tipos, validacion, funciones y estados."],
            ["Salidas y checklists", "SalidaVehiculoController, SalidaChecklistController, MobileVehiculoService", "Reglas logicas, resolucion, transacciones y control de flujo."],
            ["Publicaciones", "PublicacionController, PublicacionService, Publicacion model", "Colecciones, closures, pipelines funcionales y programacion declarativa."],
            ["Panel analitico", "PanelController, RendimientoExportController", "Consultas agregadas, reduccion de datos y conocimiento derivado."],
            ["Autorizacion", "AuthServiceProvider, DashboardController, middleware", "Predicados de acceso y decisiones por rol."],
        ],
    )

    builder.heading("Evidencias", 1)
    paragraph_block(
        builder,
        """
        Las evidencias integradas en este documento se derivan directamente del repositorio del proyecto. Incluyen diagramas generados a partir de la arquitectura y del flujo observado, reconstrucciones visuales de modulos Blade reales, tablas de relacion teoria-implementacion y fragmentos textuales del codigo fuente que muestran reglas, consultas y transformaciones funcionales.
        """,
    )
    builder.table(
        ["Evidencia", "Origen en el proyecto", "Valor para el analisis academico"],
        [
            ["Figura 1", "Arquitectura sintetizada desde rutas, controladores, servicios y vistas", "Ubica cada paradigma en la arquitectura real."],
            ["Figura 3", "resources/views/vehiculos/index.blade.php", "Relaciona la teoria con una interfaz operativa concreta."],
            ["Figura 4", "resources/views/publicaciones/index.blade.php", "Demuestra transformaciones funcionales y analitica social."],
            ["Figura 6", "SalidaVehiculoController + SalidaChecklistController", "Explica la resolucion logica del proceso vehicular."],
            ["Fragmentos de codigo", "app/Http, app/Models, app/Services, routes", "Prueban que las afirmaciones del informe provienen de implementacion real."],
        ],
    )

    builder.heading("Resultados obtenidos", 1)
    paragraph_block(
        builder,
        """
        El analisis muestra que el sistema SAICO-BETA no solo utiliza conceptos de programacion logica y funcional, sino que depende de ellos para operar correctamente. Las validaciones tipadas y las reglas secuenciales protegen la consistencia de salidas, checklists y publicaciones. Las colecciones, callbacks y transformaciones permiten construir dashboards y respuestas API sin saturar la interfaz con logica accidental. Las consultas agregadas convierten la base de datos en una fuente de conocimiento para decisiones operativas.

        Como resultado, el proyecto exhibe caracteristicas esperables de una implementacion universitaria madura: modularidad, reutilizacion, claridad semantica, consistencia transaccional y evidencia de escalabilidad. La coexistencia de web, movil y exportaciones demuestra que las abstracciones elegidas son suficientemente generales para sostener distintos canales sin reescribir el dominio.
        """,
    )

    builder.heading("Conclusiones", 1)
    paragraph_block(
        builder,
        """
        La principal conclusion es que la programacion logica y funcional no son un contenido teorico aislado, sino un marco practico para construir software empresarial confiable. En SAICO-BETA la logica se materializa como reglas de acceso, validaciones, inferencias documentales y transiciones de estado; la funcionalidad se manifiesta en colecciones, pipelines de datos, recursos JSON y consultas declarativas. Ambos paradigmas conviven en el mismo sistema y explican buena parte de su mantenibilidad.

        Tambien se concluye que Laravel ofrece un terreno especialmente apto para aplicar estos paradigmas de forma pragmatica. Sus Form Requests, Gates, Collections, Eloquent Resources y Query Builder facilitan que una idea academica se convierta en una decision tecnica concreta. El proyecto analizado demuestra que un estudiante de Ingenieria en Sistemas puede traducir la teoria de la asignatura a funcionalidades de uso real, con impacto directo en trazabilidad, seguridad, consistencia y capacidad analitica.
        """,
    )

    builder.heading("Recomendaciones", 1)
    paragraph_block(
        builder,
        """
        Se recomienda profundizar la formalizacion de reglas del dominio mediante policies y clases de regla dedicadas, de modo que la semantica logica quede aun mas centralizada. Tambien conviene ampliar la cobertura de pruebas automatizadas sobre los flujos de salida y checklist, porque son el punto con mayor densidad de reglas. En el plano funcional, seria valioso extraer pipelines analiticos repetidos hacia objetos o servicios especificos para reducir complejidad en controladores extensos.

        Desde el enfoque academico, el proyecto ya ofrece base suficiente para futuras materias como Ingenieria de Software, Bases de Datos Avanzadas, Arquitectura de Software y Desarrollo Movil. La recomendacion final es conservar la disciplina de evidenciar cada regla con codigo y cada conclusion con modulo real, ya que esa practica fortalece tanto la calidad tecnica como la solidez documental del trabajo universitario.
        """,
    )

    builder.heading("Bibliografia", 1)
    bibliography = [
        "Bird, R. (2014). Thinking Functionally with Haskell. Cambridge University Press.",
        "Laravel. (2024). Collections - Laravel 11.x. https://laravel.com/docs/11.x/collections",
        "Laravel. (2024). Database: Query Builder - Laravel 11.x. https://laravel.com/docs/11.x/queries",
        "Laravel. (2024). Validation - Laravel 11.x. https://laravel.com/docs/11.x/validation",
        "Laravel. (2024). Authorization - Laravel 11.x. https://laravel.com/docs/11.x/authorization",
        "Laravel. (2024). Eloquent: Collections - Laravel 11.x. https://laravel.com/docs/11.x/eloquent-collections",
        "Lloyd, J. W. (1987). Foundations of Logic Programming (2nd ed.). Springer.",
        "MDN Web Docs. (2026). Array. https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array",
        "MySQL. (2026). JOIN Clause. https://dev.mysql.com/doc/en/join.html",
        "PHP Documentation Group. (2025). Anonymous functions. https://www.php.net/manual/en/functions.anonymous.php",
        "PHP Documentation Group. (2025). Arrow Functions. https://www.php.net/manual/en/functions.arrow.php",
    ]
    for item in bibliography:
        builder.paragraph(item, style="BodyText")


def write_docx(output_path: Path, builder: DocBuilder) -> None:
    output_path.parent.mkdir(parents=True, exist_ok=True)
    image_suffixes = sorted({Path(rel["path"]).suffix.lower() for rel in builder.image_rels})
    with zipfile.ZipFile(output_path, "w", compression=zipfile.ZIP_DEFLATED) as zf:
        zf.writestr("[Content_Types].xml", content_types_xml(image_suffixes))
        zf.writestr("_rels/.rels", rels_xml())
        zf.writestr("docProps/app.xml", app_xml())
        zf.writestr("docProps/core.xml", core_xml())
        zf.writestr("word/document.xml", builder.build_document_xml())
        zf.writestr("word/styles.xml", styles_xml())
        zf.writestr("word/settings.xml", settings_xml())
        zf.writestr("word/webSettings.xml", web_settings_xml())
        zf.writestr("word/theme/theme1.xml", theme_xml())
        zf.writestr("word/_rels/document.xml.rels", document_rels_xml(builder.image_rels))
        for rel in builder.image_rels:
            zf.write(rel["path"], f'word/media/{rel["name"]}')


def main() -> None:
    assets = generate_assets()
    builder = DocBuilder()
    build_content(builder, assets)
    write_docx(OUTPUT_PATH, builder)
    print(f"Documento generado en: {OUTPUT_PATH}")


if __name__ == "__main__":
    main()
