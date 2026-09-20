from __future__ import annotations

import datetime as dt
import textwrap
import zipfile
from pathlib import Path
from xml.sax.saxutils import escape

import subprocess


ROOT = Path(__file__).resolve().parents[1]
DOCS_DIR = ROOT / "docs"
ASSETS_DIR = DOCS_DIR / "assets_mobile"
OUTPUT_PATH = DOCS_DIR / "Aplicaciones_Moviles_SAICO_APP_MOVIL.docx"


MONTHS_ES = {
    1: "enero",
    2: "febrero",
    3: "marzo",
    4: "abril",
    5: "mayo",
    6: "junio",
    7: "julio",
    8: "agosto",
    9: "septiembre",
    10: "octubre",
    11: "noviembre",
    12: "diciembre",
}


def read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="ignore")


def normalize_indent(value: str) -> str:
    return textwrap.dedent(value).strip("\n")


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


def code_snippet(title: str, path: Path, start_marker: str, end_marker: str) -> tuple[str, str]:
    text = read_text(path)
    start = text.find(start_marker)
    if start == -1:
        raise ValueError(f"No se encontro el inicio del fragmento en {path}: {start_marker}")
    end = text.find(end_marker, start)
    if end == -1:
        raise ValueError(f"No se encontro el fin del fragmento en {path}: {end_marker}")
    return title, text[start:end].rstrip()


def format_date_es(value: dt.datetime) -> str:
    return f"{value.day} de {MONTHS_ES[value.month]} de {value.year}"


def generate_assets() -> dict[str, Path]:
    ASSETS_DIR.mkdir(parents=True, exist_ok=True)
    asset_script = r"""
Add-Type -AssemblyName System.Drawing

$assets = Join-Path "__ROOT__" "docs\assets_mobile"
if (!(Test-Path $assets)) {
    New-Item -ItemType Directory -Path $assets | Out-Null
}

function New-Canvas([int]$width, [int]$height) {
    $bmp = New-Object System.Drawing.Bitmap $width, $height
    $g = [System.Drawing.Graphics]::FromImage($bmp)
    $g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
    $g.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::AntiAliasGridFit
    return @($bmp, $g)
}

function Save-Canvas($bmp, $g, [string]$path) {
    $bmp.Save($path, [System.Drawing.Imaging.ImageFormat]::Png)
    $g.Dispose()
    $bmp.Dispose()
}

function Brush([string]$hex) {
    return New-Object System.Drawing.SolidBrush ([System.Drawing.ColorTranslator]::FromHtml($hex))
}

function PenObj([string]$hex, [float]$width = 1.0) {
    return New-Object System.Drawing.Pen ([System.Drawing.ColorTranslator]::FromHtml($hex), $width)
}

function FontObj([string]$name, [float]$size, [System.Drawing.FontStyle]$style = [System.Drawing.FontStyle]::Regular) {
    return New-Object System.Drawing.Font($name, $size, $style)
}

function Draw-RoundedBox($g, [string]$fill, [string]$border, [int]$x, [int]$y, [int]$w, [int]$h, [int]$radius = 18) {
    $path = New-Object System.Drawing.Drawing2D.GraphicsPath
    $diam = $radius * 2
    [void]$path.AddArc($x, $y, $diam, $diam, 180, 90)
    [void]$path.AddArc($x + $w - $diam, $y, $diam, $diam, 270, 90)
    [void]$path.AddArc($x + $w - $diam, $y + $h - $diam, $diam, $diam, 0, 90)
    [void]$path.AddArc($x, $y + $h - $diam, $diam, $diam, 90, 90)
    [void]$path.CloseFigure()
    [void]$g.FillPath((Brush $fill), $path)
    [void]$g.DrawPath((PenObj $border 2), $path)
    $path.Dispose()
}

function Draw-Arrow($g, [int]$x1, [int]$y1, [int]$x2, [int]$y2, [string]$hex) {
    $pen = PenObj $hex 3
    $pen.CustomEndCap = New-Object System.Drawing.Drawing2D.AdjustableArrowCap(5, 7, $true)
    [void]$g.DrawLine($pen, $x1, $y1, $x2, $y2)
    $pen.Dispose()
}

function Draw-Phone($g, [int]$x, [int]$y, [int]$w, [int]$h, [string]$fill, [string]$border) {
    Draw-RoundedBox $g $fill $border $x $y $w $h 40
    Draw-RoundedBox $g "#ffffff" "#D9E2EC" ($x + 18) ($y + 40) ($w - 36) ($h - 72) 28
    [void]$g.FillRectangle((Brush "#CBD5E1"), ($x + ($w / 2) - 38), ($y + 16), 76, 8)
}

$titleFont = FontObj "Segoe UI" 30 ([System.Drawing.FontStyle]::Bold)
$subFont = FontObj "Segoe UI" 18
$textFont = FontObj "Segoe UI" 14
$smallFont = FontObj "Segoe UI" 12

# 1. Arquitectura movil
$canvas = New-Canvas 1800 980
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
[void]$g.DrawString("Arquitectura de la APK SAICO-BETA", $titleFont, (Brush "#0F172A"), 55, 35)
[void]$g.DrawString("Flutter + Riverpod + GoRouter + Dio + API REST + Sanctum", $subFont, (Brush "#475569"), 58, 88)

Draw-RoundedBox $g "#DBEAFE" "#2563EB" 80 180 280 130
Draw-RoundedBox $g "#DCFCE7" "#16A34A" 430 180 280 130
Draw-RoundedBox $g "#FEF3C7" "#D97706" 780 180 280 130
Draw-RoundedBox $g "#FEE2E2" "#DC2626" 1130 180 280 130
Draw-RoundedBox $g "#EDE9FE" "#7C3AED" 1480 180 240 130

[void]$g.DrawString("Presentacion`nPantallas Flutter`nWidgets responsivos", $subFont, (Brush "#1D4ED8"), 125, 215)
[void]$g.DrawString("Estado y navegacion`nRiverpod + GoRouter", $subFont, (Brush "#166534"), 470, 225)
[void]$g.DrawString("Datos y red`nRepositorios + Dio", $subFont, (Brush "#92400E"), 840, 225)
[void]$g.DrawString("Servicios REST`n/api/mobile/v1", $subFont, (Brush "#991B1B"), 1190, 225)
[void]$g.DrawString("Sanctum`nBearer Token", $subFont, (Brush "#6D28D9"), 1525, 225)

Draw-Arrow $g 360 245 430 245 "#2563EB"
Draw-Arrow $g 710 245 780 245 "#2563EB"
Draw-Arrow $g 1060 245 1130 245 "#2563EB"
Draw-Arrow $g 1410 245 1480 245 "#2563EB"

Draw-RoundedBox $g "#F8FAFC" "#334155" 310 450 1180 340
[void]$g.DrawString("Capas funcionales observadas en el repositorio movil", $subFont, (Brush "#0F172A"), 350, 485)
$lines = @(
    "- features/auth: acceso, token, sesion segura y perfil actual",
    "- features/vehicle_requests: dashboard, solicitud de salida, catalogos y historial",
    "- features/checklists: salida, entrada, detalle y captura de evidencias",
    "- core/network: baseUrl, timeouts, interceptor y formateo de errores",
    "- core/layout + core/theme: experiencia responsive y consistencia visual Android"
)
$yy = 535
foreach ($line in $lines) {
    [void]$g.DrawString($line, $textFont, (Brush "#334155"), 365, $yy)
    $yy += 40
}
[void]$g.DrawString("La APK no replica modulos administrativos web; se especializa en operacion vehicular en movilidad.", $textFont, (Brush "#334155"), 360, 740)
Save-Canvas $bmp $g (Join-Path $assets "arquitectura-movil-saico.png")

# 2. Navegacion movil
$canvas = New-Canvas 1800 980
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
[void]$g.DrawString("Mapa de navegacion de la APK", $titleFont, (Brush "#0F172A"), 55, 35)
[void]$g.DrawString("Rutas definidas en app_router.dart", $subFont, (Brush "#475569"), 58, 88)

Draw-RoundedBox $g "#DBEAFE" "#2563EB" 730 120 280 90
[void]$g.DrawString("/login", $subFont, (Brush "#1D4ED8"), 840, 152)

Draw-RoundedBox $g "#DCFCE7" "#16A34A" 730 280 280 100
[void]$g.DrawString("/home`nDashboard operativo", $subFont, (Brush "#166534"), 805, 314)

Draw-RoundedBox $g "#FEF3C7" "#D97706" 300 470 280 110
Draw-RoundedBox $g "#FEF3C7" "#D97706" 730 470 280 110
Draw-RoundedBox $g "#FEF3C7" "#D97706" 1160 470 280 110
[void]$g.DrawString("/request`nNueva salida", $subFont, (Brush "#92400E"), 380, 510)
[void]$g.DrawString("/checklists/departure/:id", $subFont, (Brush "#92400E"), 755, 510)
[void]$g.DrawString("/checklists/arrival/:id", $subFont, (Brush "#92400E"), 1205, 510)

Draw-RoundedBox $g "#EDE9FE" "#7C3AED" 730 690 280 110
[void]$g.DrawString("/checklists/view/:type/:id`nConsulta sin edicion", $subFont, (Brush "#6D28D9"), 760, 725)

Draw-Arrow $g 870 210 870 280 "#334155"
Draw-Arrow $g 790 380 440 470 "#334155"
Draw-Arrow $g 870 380 870 470 "#334155"
Draw-Arrow $g 950 380 1300 470 "#334155"
Draw-Arrow $g 870 580 870 690 "#334155"
[void]$g.DrawString("El dashboard centraliza las decisiones de movilidad y abre el flujo de solicitud o checklist segun el estado de la salida.", $textFont, (Brush "#334155"), 255, 880)
Save-Canvas $bmp $g (Join-Path $assets "navegacion-movil-saico.png")

# 3. API REST y token
$canvas = New-Canvas 1850 980
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
[void]$g.DrawString("Consumo de API REST y autenticacion", $titleFont, (Brush "#0F172A"), 55, 35)
[void]$g.DrawString("Flujo tecnico entre Android, Dio y Laravel Sanctum", $subFont, (Brush "#475569"), 58, 88)

Draw-Phone $g 120 210 360 620 "#F8FAFC" "#CBD5E1"
[void]$g.DrawString("APK Android", $subFont, (Brush "#111827"), 220, 165)
[void]$g.DrawString("1. Login POST /auth/login", $textFont, (Brush "#334155"), 170, 300)
[void]$g.DrawString("2. Token guardado en", $textFont, (Brush "#334155"), 170, 345)
[void]$g.DrawString("   FlutterSecureStorage", $textFont, (Brush "#334155"), 170, 372)
[void]$g.DrawString("3. Interceptor agrega", $textFont, (Brush "#334155"), 170, 425)
[void]$g.DrawString("   Authorization: Bearer token", $textFont, (Brush "#334155"), 170, 452)
[void]$g.DrawString("4. Request GET/POST hacia", $textFont, (Brush "#334155"), 170, 505)
[void]$g.DrawString("   /api/mobile/v1", $textFont, (Brush "#334155"), 170, 532)
[void]$g.DrawString("5. Si 401 => cierre de sesion", $textFont, (Brush "#334155"), 170, 585)

Draw-RoundedBox $g "#DCFCE7" "#16A34A" 700 280 430 170
Draw-RoundedBox $g "#DBEAFE" "#2563EB" 700 540 430 170
[void]$g.DrawString("Laravel API movil", $subFont, (Brush "#166534"), 850, 315)
[void]$g.DrawString("Rutas: auth, catalogos, salidas, checklist-salida, checklist-entrada", $textFont, (Brush "#334155"), 740, 365)
[void]$g.DrawString("Respuesta JSON normalizada para Flutter", $textFont, (Brush "#334155"), 740, 405)
[void]$g.DrawString("Sanctum valida token y contexto del usuario", $textFont, (Brush "#334155"), 735, 575)
[void]$g.DrawString("Backend devuelve 401 si el token ya no es valido", $textFont, (Brush "#334155"), 735, 615)

Draw-RoundedBox $g "#FEF3C7" "#D97706" 1390 360 320 170
[void]$g.DrawString("Servicios REST", $subFont, (Brush "#92400E"), 1490, 395)
$endpoints = @(
    "/auth/login",
    "/auth/me",
    "/catalogos/vehiculos-disponibles",
    "/salidas",
    "/salidas/:id/checklist-salida",
    "/salidas/:id/checklist-entrada"
)
$yy = 440
foreach ($line in $endpoints) {
    [void]$g.DrawString($line, $smallFont, (Brush "#7C2D12"), 1450, $yy)
    $yy += 24
}

Draw-Arrow $g 480 520 700 365 "#2563EB"
Draw-Arrow $g 480 570 700 625 "#2563EB"
Draw-Arrow $g 1130 420 1390 420 "#16A34A"
[void]$g.DrawString("Base URL observada: http://192.168.1.242:8000/api/mobile/v1", $textFont, (Brush "#334155"), 690, 860)
Save-Canvas $bmp $g (Join-Path $assets "api-rest-movil-saico.png")

# 4. Flujo JSON y multipart
$canvas = New-Canvas 1750 980
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
[void]$g.DrawString("Flujo de datos moviles", $titleFont, (Brush "#0F172A"), 55, 35)
[void]$g.DrawString("JSON para consulta y multipart para evidencias fotograficas", $subFont, (Brush "#475569"), 58, 88)

Draw-RoundedBox $g "#DBEAFE" "#2563EB" 90 210 440 180
[void]$g.DrawString("Entrada JSON", $subFont, (Brush "#1D4ED8"), 260, 245)
[void]$g.DrawString("{id, placa, marca, modelo, estatus, kilometraje_actual, documentacion_estatus}", $textFont, (Brush "#334155"), 120, 305)

Draw-RoundedBox $g "#DCFCE7" "#16A34A" 650 210 440 180
[void]$g.DrawString("Modelos Dart", $subFont, (Brush "#166534"), 820, 245)
[void]$g.DrawString("CatalogVehicle, CatalogUser, VehicleExitSummary,", $textFont, (Brush "#334155"), 690, 305)
[void]$g.DrawString("VehicleChecklistSnapshot y ChecklistPayload", $textFont, (Brush "#334155"), 690, 340)

Draw-RoundedBox $g "#FEF3C7" "#D97706" 1210 210 440 180
[void]$g.DrawString("Presentacion Flutter", $subFont, (Brush "#92400E"), 1360, 245)
[void]$g.DrawString("Dropdowns, cards, badges, fotos, tabs y detalle operativo", $textFont, (Brush "#334155"), 1240, 315)

Draw-RoundedBox $g "#EDE9FE" "#7C3AED" 360 560 1030 240
[void]$g.DrawString("Envio multipart del checklist", $subFont, (Brush "#6D28D9"), 760, 600)
$multipart = @(
    "nivel_gasolina, kilometraje, limpio_exterior, limpio_interior",
    "liquido_limpiaparabrisas, aceite, anticongelante, liquido_frenos",
    "estado_llantas y calibracion de cada rueda",
    "herramientas[llantas], herramientas[extintor], ...",
    "evidencias[] => 3 imagenes comprimidas"
)
$yy = 650
foreach ($line in $multipart) {
    [void]$g.DrawString("- $line", $textFont, (Brush "#334155"), 430, $yy)
    $yy += 34
}

Draw-Arrow $g 530 300 650 300 "#2563EB"
Draw-Arrow $g 1090 300 1210 300 "#2563EB"
Draw-Arrow $g 870 390 870 560 "#2563EB"
Save-Canvas $bmp $g (Join-Path $assets "flujo-json-multipart-saico.png")

# 5. Login
$canvas = New-Canvas 900 1600
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
Draw-Phone $g 160 50 580 1460 "#0F172A" "#0F172A"
[void]$g.FillRectangle((Brush "#163C77"), 178, 92, 544, 360)
[void]$g.DrawString("SAICO", (FontObj "Segoe UI" 38 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 360, 165)
[void]$g.DrawString("Gestion movil de vehiculos", $subFont, (Brush "#E0ECFF"), 260, 225)
[void]$g.DrawString("Solicitudes, checklist de salida y checklist de entrada", $textFont, (Brush "#E0ECFF"), 218, 265)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 210 500 480 640 26
[void]$g.DrawString("Iniciar sesion", (FontObj "Segoe UI" 26 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 275, 560)
Draw-RoundedBox $g "#F8FAFC" "#D9E2EC" 250 655 400 82 18
Draw-RoundedBox $g "#F8FAFC" "#D9E2EC" 250 785 400 82 18
[void]$g.DrawString("Correo", $textFont, (Brush "#64748B"), 285, 685)
[void]$g.DrawString("Contrasena", $textFont, (Brush "#64748B"), 285, 815)
Draw-RoundedBox $g "#0D6EFD" "#0D6EFD" 250 935 400 86 18
[void]$g.DrawString("Entrar al modulo", (FontObj "Segoe UI" 19 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 350, 965)
[void]$g.DrawString("Pantalla reconstruida desde login_screen.dart", $smallFont, (Brush "#64748B"), 280, 1085)
Save-Canvas $bmp $g (Join-Path $assets "screen-login-saico.png")

# 6. Home/dashboard
$canvas = New-Canvas 900 1700
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
Draw-Phone $g 160 50 580 1560 "#F8FAFC" "#CBD5E1"
[void]$g.DrawString("Salidas y checklists", $subFont, (Brush "#111827"), 290, 115)
[void]$g.DrawString("Hola, operador", (FontObj "Segoe UI" 26 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 220, 170)
[void]$g.DrawString("Administra salidas, checklist de salida y checklist de entrada.", $textFont, (Brush "#475569"), 220, 220)
Draw-RoundedBox $g "#163C77" "#163C77" 205 280 490 180 24
[void]$g.DrawString("Modulo gestion de vehiculos", $subFont, (Brush "#FFFFFF"), 260, 330)
[void]$g.DrawString("Consulta salidas activas y completa checklists", $textFont, (Brush "#E0ECFF"), 245, 382)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 500 490 180 22
[void]$g.DrawString("Resumen operativo", (FontObj "Segoe UI" 22 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 250, 545)
[void]$g.DrawString("Rol actual: Operativo", $textFont, (Brush "#334155"), 245, 595)
[void]$g.DrawString("Salidas activas: 1", $textFont, (Brush "#334155"), 245, 628)
[void]$g.DrawString("Vehiculos disponibles: 1", $textFont, (Brush "#334155"), 245, 661)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 720 490 290 22
[void]$g.DrawString("Vehiculo activo", (FontObj "Segoe UI" 22 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 250, 760)
[void]$g.DrawString("YH-241-A - Nissan NP300", $textFont, (Brush "#111827"), 245, 815)
[void]$g.DrawString("Chofer: Carlos Mendoza", $textFont, (Brush "#334155"), 245, 850)
[void]$g.DrawString("Solicitado por: Francisco Cruz", $textFont, (Brush "#334155"), 245, 883)
Draw-RoundedBox $g "#EAF2FF" "#BFD4FF" 245 925 190 58 16
Draw-RoundedBox $g "#0D6EFD" "#0D6EFD" 455 925 190 58 16
[void]$g.DrawString("Checklist salida", $smallFont, (Brush "#1D4ED8"), 280, 945)
[void]$g.DrawString("Checklist entrada", $smallFont, (Brush "#FFFFFF"), 490, 945)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 1050 490 390 22
[void]$g.DrawString("Ultimas salidas", (FontObj "Segoe UI" 22 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 250, 1090)
for ($i = 0; $i -lt 3; $i++) {
    $yy = 1150 + ($i * 88)
    [void]$g.FillRectangle((Brush "#F8FAFC"), 235, $yy, 430, 66)
    [void]$g.DrawString("SV-2026050$($i+7)-0$($i+1)", $textFont, (Brush "#111827"), 255, ($yy + 12))
    [void]$g.DrawString("Finalizado / Toyota Hilux", $smallFont, (Brush "#475569"), 255, ($yy + 38))
}
Save-Canvas $bmp $g (Join-Path $assets "screen-home-saico.png")

# 7. Request
$canvas = New-Canvas 900 1720
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
Draw-Phone $g 160 50 580 1580 "#F8FAFC" "#CBD5E1"
[void]$g.DrawString("Nueva salida", $subFont, (Brush "#111827"), 345, 115)
Draw-RoundedBox $g "#163C77" "#163C77" 205 170 490 180 24
[void]$g.DrawString("Solicitud de vehiculo", $subFont, (Brush "#FFFFFF"), 285, 225)
[void]$g.DrawString("Replica del formulario web para captura movil", $textFont, (Brush "#E0ECFF"), 240, 278)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 390 490 760 22
[void]$g.DrawString("Nueva salida de vehiculo", (FontObj "Segoe UI" 22 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 250, 430)
$labels = @("Vehiculo","Chofer","Solicitado por","Fecha de salida","Motivo")
$tops = @(520, 650, 780, 910, 1040)
for ($i = 0; $i -lt $labels.Count; $i++) {
    Draw-RoundedBox $g "#F8FAFC" "#D9E2EC" 245 $tops[$i] 410 82 18
    [void]$g.DrawString($labels[$i], $textFont, (Brush "#64748B"), 275, ($tops[$i] + 29))
}
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 1180 490 280 22
[void]$g.DrawString("Validaciones visibles", (FontObj "Segoe UI" 20 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 255, 1220)
[void]$g.DrawString("- Vehiculo con documentacion completa", $textFont, (Brush "#166534"), 250, 1285)
[void]$g.DrawString("- Chofer con licencia vigente", $textFont, (Brush "#166534"), 250, 1320)
[void]$g.DrawString("- Solicitante con permiso operativo", $textFont, (Brush "#166534"), 250, 1355)
Draw-RoundedBox $g "#0D6EFD" "#0D6EFD" 245 1490 410 86 18
[void]$g.DrawString("Guardar salida", (FontObj "Segoe UI" 20 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 355, 1520)
Save-Canvas $bmp $g (Join-Path $assets "screen-request-saico.png")

# 8. Checklist
$canvas = New-Canvas 900 1780
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
Draw-Phone $g 160 50 580 1640 "#F8FAFC" "#CBD5E1"
[void]$g.DrawString("Checklist de salida", $subFont, (Brush "#111827"), 300, 115)
Draw-RoundedBox $g "#163C77" "#163C77" 205 170 490 160 24
[void]$g.DrawString("Checklist de salida #18", $subFont, (Brush "#FFFFFF"), 270, 220)
[void]$g.DrawString("Captura condiciones y evidencias con el mismo flujo del sistema SAICO", $textFont, (Brush "#E0ECFF"), 220, 270)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 360 490 220 22
[void]$g.DrawString("Datos de la unidad", (FontObj "Segoe UI" 20 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 255, 400)
[void]$g.DrawString("YH-241-A - Nissan NP300", $textFont, (Brush "#334155"), 250, 450)
[void]$g.DrawString("Chofer: Carlos Mendoza", $textFont, (Brush "#334155"), 250, 485)
[void]$g.DrawString("Solicitado por: Francisco Cruz", $textFont, (Brush "#334155"), 250, 520)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 610 490 820 22
[void]$g.DrawString("Pestanas del formulario", (FontObj "Segoe UI" 20 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 255, 650)
$chips = @("Datos generales","Herramientas","Documentos","Evidencias")
$chipX = @(250, 425, 255, 425)
$chipY = @(700, 700, 760, 760)
for ($i = 0; $i -lt $chips.Count; $i++) {
    Draw-RoundedBox $g "#EAF2FF" "#BFD4FF" $chipX[$i] $chipY[$i] 145 48 18
    [void]$g.DrawString($chips[$i], $smallFont, (Brush "#1D4ED8"), ($chipX[$i] + 10), ($chipY[$i] + 15))
}
[void]$g.DrawString("Nivel de gasolina, kilometraje, liquidos, aceite,", $textFont, (Brush "#334155"), 250, 855)
[void]$g.DrawString("anticongelante, frenos, llantas y limpieza.", $textFont, (Brush "#334155"), 250, 888)
[void]$g.DrawString("Herramientas: llantas, extintor, cables, gato,", $textFont, (Brush "#334155"), 250, 942)
[void]$g.DrawString("llave de cruz y llanta de refaccion.", $textFont, (Brush "#334155"), 250, 975)
[void]$g.DrawString("Documentos: licencia, tarjeta y poliza.", $textFont, (Brush "#334155"), 250, 1028)
[void]$g.DrawString("Evidencias: exactamente 3 fotografias.", $textFont, (Brush "#334155"), 250, 1081)
Draw-RoundedBox $g "#F8FAFC" "#D9E2EC" 245 1140 410 190 18
[void]$g.DrawString("Camara", $textFont, (Brush "#111827"), 300, 1195)
[void]$g.DrawString("Galeria", $textFont, (Brush "#111827"), 500, 1195)
[void]$g.DrawString("3 de 3 minimas", $textFont, (Brush "#475569"), 300, 1260)
Draw-RoundedBox $g "#0D6EFD" "#0D6EFD" 245 1460 410 86 18
[void]$g.DrawString("Guardar checklist", (FontObj "Segoe UI" 20 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 320, 1490)
Save-Canvas $bmp $g (Join-Path $assets "screen-checklist-saico.png")

# 9. Detalle
$canvas = New-Canvas 900 1700
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
Draw-Phone $g 160 50 580 1560 "#F8FAFC" "#CBD5E1"
[void]$g.DrawString("Ver checklist salida", $subFont, (Brush "#111827"), 290, 115)
Draw-RoundedBox $g "#163C77" "#163C77" 205 170 490 160 24
[void]$g.DrawString("Consulta sin edicion", $subFont, (Brush "#FFFFFF"), 310, 220)
[void]$g.DrawString("El detalle se obtiene desde la API y se muestra en modo solo lectura", $textFont, (Brush "#E0ECFF"), 215, 270)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 360 490 300 22
[void]$g.DrawString("Detalle registrado", (FontObj "Segoe UI" 22 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 255, 400)
[void]$g.DrawString("Vehiculo: YH-241-A - Nissan NP300", $textFont, (Brush "#334155"), 250, 455)
[void]$g.DrawString("Chofer: Carlos Mendoza", $textFont, (Brush "#334155"), 250, 495)
[void]$g.DrawString("Nivel gasolina: 1/2", $textFont, (Brush "#334155"), 250, 535)
[void]$g.DrawString("Kilometraje: 128440", $textFont, (Brush "#334155"), 250, 575)
Draw-RoundedBox $g "#FFFFFF" "#D9E2EC" 205 690 490 690 22
[void]$g.DrawString("Estado del vehiculo", (FontObj "Segoe UI" 22 ([System.Drawing.FontStyle]::Bold)), (Brush "#111827"), 255, 730)
[void]$g.DrawString("Liquido limpia parabrisas: suficiente", $textFont, (Brush "#334155"), 250, 790)
[void]$g.DrawString("Aceite: suficiente", $textFont, (Brush "#334155"), 250, 825)
[void]$g.DrawString("Anticongelante: suficiente", $textFont, (Brush "#334155"), 250, 860)
[void]$g.DrawString("Herramientas y documentos en badges", $textFont, (Brush "#334155"), 250, 920)
[void]$g.DrawString("Evidencias fotograficas desde URL remota", $textFont, (Brush "#334155"), 250, 955)
for ($i = 0; $i -lt 3; $i++) {
    [void]$g.FillRectangle((Brush "#EAF2FF"), (250 + ($i * 125)), 1040, 110, 110)
}
[void]$g.DrawString("Evidencias", $textFont, (Brush "#111827"), 250, 1010)
Save-Canvas $bmp $g (Join-Path $assets "screen-detail-saico.png")

# 10. Analitica movil
$canvas = New-Canvas 1400 900
$bmp = $canvas[0]
$g = $canvas[1]
$g.Clear([System.Drawing.Color]::White)
[void]$g.DrawString("Dashboard operativo movil", $titleFont, (Brush "#0F172A"), 55, 35)
[void]$g.DrawString("Indicadores visibles o derivables desde la APK y su API", $subFont, (Brush "#475569"), 58, 88)
Draw-RoundedBox $g "#163C77" "#163C77" 70 170 280 150 24
Draw-RoundedBox $g "#0D6EFD" "#0D6EFD" 390 170 280 150 24
Draw-RoundedBox $g "#16A34A" "#16A34A" 710 170 280 150 24
Draw-RoundedBox $g "#D97706" "#D97706" 1030 170 280 150 24
[void]$g.DrawString("1`nSalida activa", (FontObj "Segoe UI" 30 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 170, 210)
[void]$g.DrawString("1`nVehiculo disponible", (FontObj "Segoe UI" 30 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 455, 210)
[void]$g.DrawString("3`nEvidencias por checklist", (FontObj "Segoe UI" 30 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 765, 210)
[void]$g.DrawString("1`nHistorial visible inmediato", (FontObj "Segoe UI" 24 ([System.Drawing.FontStyle]::Bold)), (Brush "#FFFFFF"), 1085, 220)
Draw-RoundedBox $g "#F8FAFC" "#CBD5E1" 90 420 1220 330 18
[void]$g.DrawString("Metricas moviles documentables", $subFont, (Brush "#111827"), 125, 460)
$metrics = @(
    "- salidas activas mostradas al operador",
    "- vehiculos disponibles consultados desde catalogos",
    "- checklists de salida y entrada completados",
    "- kilometraje inicial y final capturado",
    "- validez documental visible en el flujo",
    "- evidencia fotografica minima obligatoria"
)
$yy = 520
foreach ($line in $metrics) {
    [void]$g.DrawString($line, $textFont, (Brush "#334155"), 135, $yy)
    $yy += 34
}
[void]$g.DrawString("La analitica movil es operativa: ayuda a decidir, validar y cerrar procesos en campo sin entrar al modulo administrativo.", $textFont, (Brush "#334155"), 125, 700)
Save-Canvas $bmp $g (Join-Path $assets "dashboard-analitico-movil.png")
""".replace("__ROOT__", str(ROOT))
    run_powershell(asset_script)
    return {
        "logo": ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "assets" / "branding" / "saico_logo.png",
        "wave": ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "assets" / "branding" / "saico_wave_bg.png",
        "architecture": ASSETS_DIR / "arquitectura-movil-saico.png",
        "navigation": ASSETS_DIR / "navegacion-movil-saico.png",
        "api": ASSETS_DIR / "api-rest-movil-saico.png",
        "data_flow": ASSETS_DIR / "flujo-json-multipart-saico.png",
        "login": ASSETS_DIR / "screen-login-saico.png",
        "home": ASSETS_DIR / "screen-home-saico.png",
        "request": ASSETS_DIR / "screen-request-saico.png",
        "checklist": ASSETS_DIR / "screen-checklist-saico.png",
        "detail": ASSETS_DIR / "screen-detail-saico.png",
        "analytics": ASSETS_DIR / "dashboard-analitico-movil.png",
    }


class DocBuilder:
    def __init__(self) -> None:
        self.parts: list[str] = []
        self.image_rels: list[dict[str, str]] = []
        self.image_counter = 1

    @staticmethod
    def _run_props(*, bold: bool = False, italic: bool = False, size: int | None = None, color: str | None = None, font: str | None = None) -> str:
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
                f'<w:rFonts w:ascii="{escape(font)}" w:hAnsi="{escape(font)}" w:eastAsia="{escape(font)}" w:cs="{escape(font)}"/>'
            )
        return f"<w:rPr>{''.join(parts)}</w:rPr>" if parts else ""

    def paragraph(self, text: str, *, style: str | None = None, align: str | None = None, bold: bool = False, italic: bool = False, size: int | None = None, color: str | None = None, font: str | None = None, page_break_before: bool = False, space_before: int | None = None, space_after: int | None = None) -> None:
        props = []
        if style:
            props.append(f'<w:pStyle w:val="{style}"/>')
        if align:
            props.append(f'<w:jc w:val="{align}"/>')
        if page_break_before:
            props.append("<w:pageBreakBefore/>")
        if space_before is not None or space_after is not None:
            props.append(f'<w:spacing w:before="{space_before or 0}" w:after="{space_after or 0}"/>')
        p_pr = f"<w:pPr>{''.join(props)}</w:pPr>" if props else ""

        run_props = self._run_props(bold=bold, italic=italic, size=size, color=color, font=font)
        runs = []
        for index, line in enumerate(text.split("\n")):
            t_attr = ' xml:space="preserve"' if line.startswith(" ") or line.endswith(" ") else ""
            runs.append(f"<w:r>{run_props}<w:t{t_attr}>{escape(line)}</w:t></w:r>")
            if index < len(text.split("\n")) - 1:
                runs.append("<w:r><w:br/></w:r>")
        self.parts.append(f"<w:p>{p_pr}{''.join(runs)}</w:p>")

    def heading(self, text: str, level: int) -> None:
        self.paragraph(text, style=f"Heading{level}", space_before=180, space_after=80)

    def page_break(self) -> None:
        self.parts.append('<w:p><w:r><w:br w:type="page"/></w:r></w:p>')

    def toc(self) -> None:
        self.parts.append('<w:p><w:pPr><w:pStyle w:val="TOCHeading"/></w:pPr><w:r><w:t>Indice</w:t></w:r></w:p>')
        self.parts.append(
            '<w:p>'
            '<w:r><w:fldChar w:fldCharType="begin"/></w:r>'
            '<w:r><w:instrText xml:space="preserve"> TOC \\o "1-3" \\h \\z \\u </w:instrText></w:r>'
            '<w:r><w:fldChar w:fldCharType="separate"/></w:r>'
            '<w:r><w:t>Actualice el indice en Word si no se visualiza inmediatamente.</w:t></w:r>'
            '<w:r><w:fldChar w:fldCharType="end"/></w:r>'
            '</w:p>'
        )

    def code_block(self, code: str) -> None:
        for line in code.rstrip("\n").split("\n"):
            self.paragraph(line.replace("\t", "    "), style="CodeBlock", font="Consolas", size=18, space_after=0)

    def table(self, headers: list[str], rows: list[list[str]]) -> None:
        def cell(text: str, header: bool = False) -> str:
            style = "TableHeader" if header else "BodyText"
            return (
                "<w:tc><w:tcPr><w:tcW w:w=\"0\" w:type=\"auto\"/></w:tcPr>"
                f'<w:p><w:pPr><w:pStyle w:val="{style}"/></w:pPr><w:r><w:t>{escape(text)}</w:t></w:r></w:p>'
                "</w:tc>"
            )

        rows_xml = [f"<w:tr>{''.join(cell(item, True) for item in headers)}</w:tr>"]
        rows_xml.extend(f"<w:tr>{''.join(cell(item) for item in row)}</w:tr>" for row in rows)
        self.parts.append(
            "<w:tbl>"
            "<w:tblPr><w:tblStyle w:val=\"TableGrid\"/><w:tblW w:w=\"0\" w:type=\"auto\"/>"
            "<w:tblBorders>"
            "<w:top w:val=\"single\" w:sz=\"8\" w:color=\"BFC5D1\"/><w:left w:val=\"single\" w:sz=\"8\" w:color=\"BFC5D1\"/>"
            "<w:bottom w:val=\"single\" w:sz=\"8\" w:color=\"BFC5D1\"/><w:right w:val=\"single\" w:sz=\"8\" w:color=\"BFC5D1\"/>"
            "<w:insideH w:val=\"single\" w:sz=\"6\" w:color=\"D7DCE5\"/><w:insideV w:val=\"single\" w:sz=\"6\" w:color=\"D7DCE5\"/>"
            "</w:tblBorders></w:tblPr>"
            + "".join(rows_xml)
            + "</w:tbl>"
        )

    def image(self, path: Path, title: str, width_inches: float, height_inches: float) -> None:
        rid = f"rIdImage{self.image_counter}"
        media_name = f"image{self.image_counter}{path.suffix.lower()}"
        self.image_rels.append({"rid": rid, "path": str(path), "name": media_name})
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
      <wp:inline distT="0" distB="0" distL="0" distR="0" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">
        <wp:extent cx="{cx}" cy="{cy}"/>
        <wp:docPr id="{pic_id}" name="{escape(title)}"/>
        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
          <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
            <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
              <pic:nvPicPr><pic:cNvPr id="{pic_id}" name="{escape(title)}"/><pic:cNvPicPr/></pic:nvPicPr>
              <pic:blipFill><a:blip r:embed="{rid}"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>
              <pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="{cx}" cy="{cy}"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr>
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
            "<w:sectPr><w:pgSz w:w=\"12240\" w:h=\"15840\"/>"
            "<w:pgMar w:top=\"1134\" w:right=\"850\" w:bottom=\"1134\" w:left=\"1134\" w:header=\"708\" w:footer=\"708\" w:gutter=\"0\"/>"
            "</w:sectPr>"
        )
        body = "".join(self.parts) + section_props
        return (
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            '<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" '
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
    <w:rPrDefault><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:eastAsia="Calibri" w:cs="Calibri"/><w:sz w:val="22"/><w:szCs w:val="22"/><w:color w:val="1F2937"/></w:rPr></w:rPrDefault>
    <w:pPrDefault><w:pPr><w:spacing w:after="120" w:line="276" w:lineRule="auto"/></w:pPr></w:pPrDefault>
  </w:docDefaults>
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/></w:style>
  <w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/><w:pPr><w:jc w:val="center"/><w:spacing w:after="120"/></w:pPr><w:rPr><w:b/><w:color w:val="0F172A"/><w:sz w:val="34"/><w:szCs w:val="34"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Subtitle"><w:name w:val="Subtitle"/><w:pPr><w:jc w:val="center"/><w:spacing w:after="80"/></w:pPr><w:rPr><w:color w:val="475569"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:qFormat/><w:pPr><w:spacing w:before="200" w:after="90"/></w:pPr><w:rPr><w:b/><w:color w:val="0F172A"/><w:sz w:val="30"/><w:szCs w:val="30"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading2"><w:name w:val="heading 2"/><w:qFormat/><w:pPr><w:spacing w:before="160" w:after="60"/></w:pPr><w:rPr><w:b/><w:color w:val="1D4ED8"/><w:sz w:val="26"/><w:szCs w:val="26"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading3"><w:name w:val="heading 3"/><w:qFormat/><w:pPr><w:spacing w:before="140" w:after="50"/></w:pPr><w:rPr><w:b/><w:color w:val="0F766E"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="BodyText"><w:name w:val="Body Text"/><w:pPr><w:spacing w:after="110" w:line="300" w:lineRule="auto"/></w:pPr></w:style>
  <w:style w:type="paragraph" w:styleId="CodeBlock"><w:name w:val="Code Block"/><w:pPr><w:spacing w:after="0"/><w:ind w:left="280" w:right="140"/><w:shd w:val="clear" w:color="auto" w:fill="F8FAFC"/></w:pPr><w:rPr><w:rFonts w:ascii="Consolas" w:hAnsi="Consolas" w:eastAsia="Consolas" w:cs="Consolas"/><w:sz w:val="18"/><w:szCs w:val="18"/><w:color w:val="0F172A"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Caption"><w:name w:val="Caption"/><w:pPr><w:jc w:val="center"/><w:spacing w:before="40" w:after="120"/></w:pPr><w:rPr><w:i/><w:color w:val="475569"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="TOCHeading"><w:name w:val="TOC Heading"/><w:basedOn w:val="Heading1"/></w:style>
  <w:style w:type="paragraph" w:styleId="TableHeader"><w:name w:val="Table Header"/><w:rPr><w:b/><w:color w:val="0F172A"/></w:rPr></w:style>
</w:styles>
"""


def settings_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:updateFields w:val="true"/><w:zoom w:percent="100"/></w:settings>
"""


def web_settings_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:webSettings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:optimizeForBrowser/></w:webSettings>
"""


def theme_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="Office Theme">
  <a:themeElements>
    <a:clrScheme name="SAICO Mobile">
      <a:dk1><a:srgbClr val="0F172A"/></a:dk1>
      <a:lt1><a:srgbClr val="FFFFFF"/></a:lt1>
      <a:dk2><a:srgbClr val="163C77"/></a:dk2>
      <a:lt2><a:srgbClr val="F8FAFC"/></a:lt2>
      <a:accent1><a:srgbClr val="0D6EFD"/></a:accent1>
      <a:accent2><a:srgbClr val="16A34A"/></a:accent2>
      <a:accent3><a:srgbClr val="D97706"/></a:accent3>
      <a:accent4><a:srgbClr val="DC2626"/></a:accent4>
      <a:accent5><a:srgbClr val="7C3AED"/></a:accent5>
      <a:accent6><a:srgbClr val="0F766E"/></a:accent6>
      <a:hlink><a:srgbClr val="0D6EFD"/></a:hlink>
      <a:folHlink><a:srgbClr val="7C3AED"/></a:folHlink>
    </a:clrScheme>
    <a:fontScheme name="SAICO Fonts"><a:majorFont><a:latin typeface="Calibri"/></a:majorFont><a:minorFont><a:latin typeface="Calibri"/></a:minorFont></a:fontScheme>
    <a:fmtScheme name="SAICO Mobile Format"/>
  </a:themeElements>
</a:theme>
"""


def app_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>OpenAI Codex</Application><DocSecurity>0</DocSecurity><ScaleCrop>false</ScaleCrop>
  <HeadingPairs><vt:vector size="2" baseType="variant"><vt:variant><vt:lpstr>Titulos</vt:lpstr></vt:variant><vt:variant><vt:i4>20</vt:i4></vt:variant></vt:vector></HeadingPairs>
  <TitlesOfParts><vt:vector size="20" baseType="lpstr"><vt:lpstr>Aplicaciones Moviles</vt:lpstr></vt:vector></TitlesOfParts>
  <Company>SAICO</Company><LinksUpToDate>false</LinksUpToDate><SharedDoc>false</SharedDoc><HyperlinksChanged>false</HyperlinksChanged><AppVersion>16.0000</AppVersion>
</Properties>
"""


def core_xml() -> str:
    created = dt.datetime.now(dt.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>Aplicaciones Moviles - SAICO-BETA APP MOVIL</dc:title>
  <dc:subject>Reporte tecnico universitario de una APK Flutter real</dc:subject>
  <dc:creator>OpenAI Codex</dc:creator>
  <cp:keywords>Flutter, Dart, Android, APK, API REST, Sanctum, SAICO</cp:keywords>
  <dc:description>Documento academico enfocado exclusivamente en la APK movil SAICO-BETA.</dc:description>
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
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' + "".join(defaults) + "".join(overrides) + "</Types>"


def document_rels_xml(image_rels: list[dict[str, str]]) -> str:
    base = [
        '<Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>',
        '<Relationship Id="rIdSettings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>',
        '<Relationship Id="rIdTheme" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/>',
        '<Relationship Id="rIdWebSettings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings" Target="webSettings.xml"/>',
    ]
    for rel in image_rels:
        base.append(f'<Relationship Id="{rel["rid"]}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/{rel["name"]}"/>')
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' + "".join(base) + "</Relationships>"


def paragraph_block(builder: DocBuilder, text: str) -> None:
    for paragraph in normalize_indent(text).split("\n\n"):
        builder.paragraph(paragraph.strip(), style="BodyText")


def caption(builder: DocBuilder, text: str) -> None:
    builder.paragraph(text, style="Caption")


def add_code_example(builder: DocBuilder, title: str, code: str) -> None:
    builder.heading(title, 3)
    builder.code_block(code)
    caption(builder, f"Fragmento real de la APK movil: {title}.")


def build_content(builder: DocBuilder, assets: dict[str, Path]) -> None:
    now = dt.datetime.now()
    fecha = format_date_es(now)

    router_title, router_code = code_snippet(
        "Navegacion declarativa con GoRouter",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "app" / "router" / "app_router.dart",
        "final GoRouter appRouter = GoRouter(",
        "\n\nclass _StartupScreen",
    )
    auth_title, auth_code = code_snippet(
        "Autenticacion movil y apertura de sesion",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "auth" / "application" / "auth_controller.dart",
        "class AuthController extends AsyncNotifier<void>",
        "}\n",
    )
    session_title, session_code = code_snippet(
        "Persistencia segura del token con FlutterSecureStorage",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "auth" / "application" / "session_provider.dart",
        "class SessionNotifier extends Notifier<SessionState>",
        "\n\nfinal sessionProvider",
    )
    request_title, request_code = code_snippet(
        "Validaciones de negocio en la pantalla de nueva salida",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "vehicle_requests" / "presentation" / "request_vehicle_screen.dart",
        "Future<void> _submit() async {",
        "\n\n  @override\n  Widget build",
    )
    interceptor_title, interceptor_code = code_snippet(
        "Interceptor HTTP para Bearer token y control 401",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "core" / "network" / "dio_interceptor.dart",
        "class DioInterceptor extends Interceptor",
        "}\n",
    )
    formdata_title, formdata_code = code_snippet(
        "Envio multipart del checklist con evidencias fotograficas",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "checklists" / "data" / "checklist_repository.dart",
        "FormData _buildChecklistFormData(",
        "\n}\n",
    )
    photos_title, photos_code = code_snippet(
        "Captura y compresion de evidencias fotograficas",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "core" / "widgets" / "photo_evidence_picker.dart",
        "class _PhotoEvidencePickerState extends State<PhotoEvidencePicker>",
        "\n}\n\nclass _ActionTile",
    )
    home_title, home_code = code_snippet(
        "Dashboard operativo movil",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "vehicle_requests" / "presentation" / "vehicle_operations_home_screen.dart",
        "class VehicleOperationsHomeScreen extends ConsumerWidget",
        "\n}\n\nclass _SummaryCard",
    )
    checklist_title, checklist_code = code_snippet(
        "Shell responsive del checklist de salida y entrada",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "checklists" / "presentation" / "checklist_form_shell.dart",
        "class _ChecklistFormShellState extends State<ChecklistFormShell>",
        "\n}\n\nclass _VehiclePanel",
    )
    models_title, models_code = code_snippet(
        "Modelado Dart para vehiculos, usuarios y salidas",
        ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "features" / "vehicle_requests" / "domain" / "vehicle_exit_models.dart",
        "class CatalogVehicle {",
        "\n\nDateTime? _parseDateTime",
    )

    builder.paragraph("Tecnologico Nacional de Mexico", style="Title")
    builder.paragraph("Instituto Tecnologico Superior de Macuspana", style="Subtitle")
    builder.paragraph("Ingenieria en Sistemas Computacionales", style="Subtitle")
    builder.paragraph("Materia: Aplicaciones Moviles", style="Subtitle")
    builder.paragraph("Proyecto: SAICO-BETA APP MOVIL", style="Subtitle")
    if assets["logo"].exists():
        builder.image(assets["logo"], "Logo SAICO movil", 2.7, 2.7)
    builder.paragraph("Reporte tecnico universitario enfocado exclusivamente en la APK Android", style="Subtitle")
    builder.paragraph("Alumno: [Campo editable]", style="Subtitle")
    builder.paragraph("Docente: MIDS. Jorge Chico Pozo", style="Subtitle")
    builder.paragraph("Semestre: [Campo editable]", style="Subtitle")
    builder.paragraph(f"Fecha: {fecha}", style="Subtitle")
    builder.page_break()

    builder.toc()
    builder.page_break()

    builder.heading("Introduccion general", 1)
    paragraph_block(
        builder,
        """
        Las aplicaciones moviles empresariales han dejado de ser una extension secundaria del software corporativo para convertirse en un frente operativo critico. En escenarios donde el personal trabaja en campo, la movilidad no solo mejora comodidad: reduce tiempos de captura, disminuye errores de transcripcion, acelera el cierre de procesos y permite tomar decisiones donde ocurre la operacion. La APK SAICO-BETA APP MOVIL responde precisamente a esta necesidad al trasladar el flujo de gestion vehicular a dispositivos Android mediante una experiencia enfocada en salidas operativas, checklists y evidencias fotograficas.

        A diferencia de una aplicacion movil promocional o de consumo masivo, la APK analizada fue concebida como una herramienta de operacion empresarial. Su valor no reside en la cantidad de pantallas, sino en la coherencia del flujo funcional: iniciar sesion, consultar la salida activa, crear una nueva solicitud si no existe una salida abierta, registrar checklist de salida, capturar fotografias, completar checklist de entrada y consultar los datos guardados sin alterar la evidencia historica. Todo ello se ejecuta sobre Flutter y Dart, con una capa de integracion REST que conecta la experiencia Android con una API protegida por Sanctum.

        El presente documento se centra exclusivamente en esa APK, en su arquitectura movil, en su experiencia de uso, en su consumo de servicios y en la forma en que implementa realmente cada unidad de la materia Aplicaciones Moviles. No se documentan modulos administrativos web ni flujos ajenos a la operacion Android.
        """,
    )

    builder.heading("Unidad 1. Introduccion a las tecnologias moviles", 1)
    paragraph_block(
        builder,
        """
        El contexto tecnologico de la APK SAICO-BETA parte de una realidad evidente: las operaciones vehiculares no siempre se ejecutan frente a una computadora de escritorio. Choferes, tecnicos y responsables de salida requieren registrar estados, kilometrajes y evidencias en el lugar donde ocurre el movimiento de la unidad. Este escenario vuelve indispensable el uso de dispositivos moviles con camara, conectividad, almacenamiento local seguro y una interfaz suficientemente simple para ser utilizada bajo presion operativa.

        Android es una plataforma especialmente pertinente en este caso por su disponibilidad en equipos de distintos costos, su compatibilidad con hardware heterogeneo y su integracion natural con despliegues empresariales basados en APK. En la app analizada, el uso de Android no se limita al sistema operativo subyacente; define decisiones de empaquetado, permisos, ciclo de vida de la actividad principal y distribucion por depuracion USB o por red Wi-Fi, segun los scripts de operacion incluidos en el proyecto.
        """,
    )
    builder.table(
        ["Tecnologia movil", "Uso real en la APK SAICO-BETA", "Aporte operativo"],
        [
            ["Android", "Ejecucion como APK nativa sobre dispositivos de campo", "Movilidad inmediata y acceso a camara."],
            ["Flutter", "Construccion de interfaces, navegacion y experiencia responsive", "Una sola base de codigo con alta velocidad de desarrollo."],
            ["Dart", "Modelado de datos, controladores y widgets", "Legibilidad, composicion y tipado en cliente movil."],
            ["Image Picker", "Captura desde camara o galeria", "Registro visual de la condicion vehicular."],
            ["Flutter Secure Storage", "Almacenamiento del token de sesion", "Persistencia segura de autenticacion."],
        ],
    )
    paragraph_block(
        builder,
        """
        En esta unidad tambien resulta relevante la movilidad empresarial. La APK no fue construida para entretenimiento ni para consulta pasiva; fue disenada para sostener una cadena de valor concreta: solicitud, validacion, inspeccion, evidencia y seguimiento. El dispositivo movil se transforma en una terminal de captura operativa. Por eso la aplicacion incorpora controles especificos como validacion de kilometraje, restricciones de evidencia minima, resumen del estado actual y acceso directo al checklist pertinente segun la salida activa.
        """,
    )
    builder.image(assets["login"], "Pantalla de acceso movil", 3.0, 5.35)
    caption(builder, "Figura 1. Reconstruccion visual de la pantalla de acceso movil basada en login_screen.dart.")
    builder.image(assets["home"], "Dashboard movil", 3.0, 5.7)
    caption(builder, "Figura 2. Reconstruccion visual del dashboard operativo que concentra el flujo de movilidad vehicular.")

    builder.heading("1.1 Flutter y Dart como stack de movilidad moderna", 2)
    paragraph_block(
        builder,
        """
        Flutter se eligio como framework de desarrollo porque permite construir interfaces ricas, responsivas y visualmente consistentes a partir de widgets declarativos. En esta APK el beneficio es directo: las mismas pantallas pueden adaptarse a diferentes anchos mediante LayoutBuilder y componentes reutilizables, sin fragmentar la experiencia entre telefonos compactos y dispositivos mas amplios. Dart, por su parte, estructura el proyecto con clases de dominio, controladores de estado, recursos de sesion y formularios altamente configurables.

        La organizacion por features observada en el repositorio confirma un enfoque arquitectonico maduro para una aplicacion universitaria avanzada. La carpeta `features/auth` encapsula autenticacion y sesion; `features/vehicle_requests` concentra solicitud, dashboard e historial; y `features/checklists` contiene el flujo mas critico de la app. Esta segmentacion no solo mejora mantenimiento; tambien facilita la trazabilidad academica entre requerimiento funcional y modulo de implementacion.
        """,
    )
    add_code_example(builder, router_title, router_code)

    builder.heading("1.2 Movilidad Android aplicada a operacion vehicular", 2)
    paragraph_block(
        builder,
        """
        El valor de una aplicacion movil se mide por la reduccion de friccion en la operacion. La APK SAICO-BETA logra esto al eliminar la necesidad de registrar datos vehiculares en papel o en una estacion fija. El operador puede validar la salida, capturar el estado del vehiculo y adjuntar evidencias desde el mismo dispositivo con el que se desplaza.

        Esta movilidad se refleja incluso en el manual rapido incluido en el proyecto, donde se documentan dos modos de despliegue: conexion por Wi-Fi y conexion por cable USB usando `adb reverse`. Eso demuestra que el proyecto fue pensado para presentacion, pruebas y ejecucion en entornos reales donde la red puede variar. Desde la perspectiva de la materia, este detalle es relevante porque la movilidad no termina en el codigo; incluye estrategias de instalacion y conectividad.
        """,
    )

    builder.heading("Unidad 2. Arquitectura y entorno", 1)
    paragraph_block(
        builder,
        """
        La arquitectura de la APK SAICO-BETA combina una capa de presentacion Flutter, una capa de estado y navegacion, una capa de acceso a datos y una capa de integracion REST contra la API movil. Esta organizacion distribuye responsabilidades de manera coherente y hace posible que el flujo operativo se mantenga estable aun cuando la aplicacion crezca con nuevas pantallas o mas indicadores.
        """,
    )
    builder.image(assets["architecture"], "Arquitectura movil", 6.4, 3.45)
    caption(builder, "Figura 3. Arquitectura completa de la APK movil con sus capas principales.")

    builder.heading("2.1 Capa de autenticacion y sesion", 2)
    paragraph_block(
        builder,
        """
        La aplicacion autentica al usuario mediante credenciales enviadas al endpoint `/auth/login`. Una vez recibido el token, este se almacena usando `FlutterSecureStorage`, de modo que la sesion persiste de forma segura. El `SessionNotifier` es responsable de bootstrapear el estado al arrancar la app, recuperar el token almacenado, solicitar el perfil actual al endpoint `/auth/me` y cerrar sesion automaticamente si la informacion deja de ser valida.

        Esta arquitectura es correcta para una APK operativa porque reduce la necesidad de autenticarse en cada apertura y, al mismo tiempo, protege el acceso mediante almacenamiento seguro. Desde un punto de vista tecnico, la solucion equilibra usabilidad con seguridad movil.
        """,
    )
    add_code_example(builder, auth_title, auth_code)
    add_code_example(builder, session_title, session_code)

    builder.heading("2.2 Capa de red y consumo REST", 2)
    paragraph_block(
        builder,
        """
        La app usa `Dio` como cliente HTTP principal. El proveedor `dioProvider` define el `baseUrl`, los timeouts y el interceptor compartido. Este interceptor agrega el encabezado `Authorization: Bearer <token>` a todas las solicitudes cuando existe una sesion abierta, registra trazas en modo debug y fuerza el cierre de sesion si el servidor responde con `401 Unauthorized`.

        Este comportamiento resulta esencial en una aplicacion movil de campo porque centraliza la seguridad y evita duplicar logica en cada repositorio. En lugar de que cada pantalla gestione manualmente los encabezados o la expiracion de sesion, la red se comporta como una capa transversal reusable.
        """,
    )
    add_code_example(builder, interceptor_title, interceptor_code)
    builder.image(assets["api"], "REST y token", 6.5, 3.45)
    caption(builder, "Figura 4. Flujo de autenticacion y consumo REST entre la APK y la API movil.")

    builder.heading("2.3 Endpoints y arquitectura cliente-servidor", 2)
    paragraph_block(
        builder,
        """
        La arquitectura cliente-servidor observada es clara y especializada. La APK no consume un backend generico, sino una API movil versionada bajo `/api/mobile/v1`. Los endpoints cubren exactamente las necesidades del cliente Android: autenticacion, catalogos de vehiculos y usuarios, listado de salidas, salida activa, detalle de salida y envio de checklist de salida o entrada.

        Esta precision reduce acoplamiento, porque la app movil recibe respuestas adecuadas a su contexto. Tambien mejora la mantenibilidad, ya que el contrato API se organiza por casos de uso y no por dependencias administrativas externas.
        """,
    )
    builder.code_block(read_text(ROOT / "mobile_apps" / "saico_vehiculos_mobile" / "lib" / "core" / "constants" / "api_endpoints.dart").strip())
    caption(builder, "Fragmento real con los endpoints REST que utiliza la APK.")
    builder.image(assets["navigation"], "Navegacion movil", 6.5, 3.55)
    caption(builder, "Figura 5. Flujo de navegacion de la APK construido desde las rutas reales de GoRouter.")

    builder.heading("Unidad 3. Desarrollo de aplicaciones moviles", 1)
    paragraph_block(
        builder,
        """
        La tercera unidad se materializa en la construccion misma de las pantallas y flujos de la app. SAICO-BETA APP MOVIL utiliza widgets declarativos, componentes reutilizables, layout adaptativo y controladores de estado para ofrecer una experiencia alineada con la operacion vehicular. Lejos de limitarse a formularios basicos, la app integra dashboard, tarjetas de resumen, validaciones visuales, tabs y componentes especializados para evidencia fotografica.
        """,
    )

    builder.heading("3.1 Login movil y experiencia de acceso", 2)
    paragraph_block(
        builder,
        """
        La pantalla de login concentra dos objetivos: identidad visual y acceso rapido. El `SaicoBrandHeader` da contexto al usuario mediante titulo y subtitulo funcionales, mientras que el formulario aplica validaciones locales para correo y contrasena antes de iniciar la llamada al backend. El uso de `AdaptivePageScaffold` permite que el contenido se mantenga centrado y con paddings adecuados segun el ancho disponible.

        Esta experiencia es importante porque en movilidad la primera impresion condiciona la adopcion. Una pantalla cargada o tecnicamente ambigua ralentizaria la operacion; en cambio, el diseño implementado prioriza claridad, jerarquia visual y accion unica.
        """,
    )

    builder.heading("3.2 Dashboard operativo y navegacion contextual", 2)
    paragraph_block(
        builder,
        """
        El dashboard `VehicleOperationsHomeScreen` es la pieza central de la experiencia movil. Desde esta pantalla el usuario conoce su contexto actual, ve si existe una salida activa, observa un resumen operativo y consulta el historial mas reciente. La vista no se limita a mostrar informacion; decide que acciones presentar segun el estado actual de la salida. Si hay una salida activa, abre el checklist correspondiente; si no existe, habilita una nueva solicitud.

        Esta navegacion contextual es un acierto de diseno porque reduce pasos y evita que el operador tenga que interpretar el estado por cuenta propia. La interfaz misma traduce la logica del flujo a acciones disponibles.
        """,
    )
    add_code_example(builder, home_title, home_code)
    builder.image(assets["request"], "Formulario de nueva salida", 3.0, 5.75)
    caption(builder, "Figura 6. Reconstruccion visual del formulario movil para registrar una nueva salida.")

    builder.heading("3.3 Formularios, layouts y validaciones moviles", 2)
    paragraph_block(
        builder,
        """
        La pantalla de nueva salida evidencia buenas practicas de desarrollo movil. Utiliza `DropdownButtonFormField` para controlar selecciones validas, `showDatePicker` y `showTimePicker` para la fecha de salida, y mensajes inmediatos mediante `SnackBar` cuando la fecha es invalida o el backend devuelve un error. Adicionalmente, en la columna lateral se muestran tarjetas de validacion del vehiculo, del chofer y del solicitante, lo que convierte al formulario en una herramienta explicativa y no solo capturadora.

        En terminos de experiencia de usuario, esta decision reduce incertidumbre y previene errores antes del envio. En terminos academicos, demuestra que una app movil bien disenada integra logica, interfaz y feedback inmediato dentro del mismo flujo.
        """,
    )
    add_code_example(builder, request_title, request_code)

    builder.heading("3.4 Checklists de salida y entrada", 2)
    paragraph_block(
        builder,
        """
        El modulo de checklists es el corazon funcional de la APK. La clase `ChecklistFormShell` abstrae la mayor parte del flujo compartido entre salida y entrada: carga de datos de la unidad, control de tabs, captura de kilometraje, seleccion de niveles y estados, registro de observaciones y envio de evidencias. Este enfoque evita duplicidad de pantallas y demuestra una reutilizacion de alto nivel.

        En el checklist de entrada se agrega una regla adicional: el kilometraje final debe ser mayor al kilometraje de referencia. Tambien se fuerza que existan exactamente tres evidencias fotograficas. Estas restricciones no son cosmeticas; representan controles operativos sobre el proceso real de devolucion.
        """,
    )
    add_code_example(builder, checklist_title, checklist_code)
    builder.image(assets["checklist"], "Checklist movil", 3.0, 5.95)
    caption(builder, "Figura 7. Reconstruccion visual del checklist movil con tabs operativas y captura de evidencias.")

    builder.heading("3.5 Captura fotografica y usabilidad Android", 2)
    paragraph_block(
        builder,
        """
        La captura de evidencias fotograficas fue implementada con `ImagePicker`, permitiendo tomar fotos con camara o seleccionarlas desde galeria. Antes de integrarlas al payload, la app comprime los archivos con `FlutterImageCompress`, lo que reduce el peso de la transferencia sin eliminar la utilidad de la evidencia. Esto es especialmente importante en escenarios moviles donde la conectividad puede ser limitada.

        La UI del selector de evidencias se resolvio mediante un componente dedicado (`PhotoEvidencePicker`) que muestra acciones claras, conteo de fotografias y la posibilidad de eliminar una imagen antes del envio. Esta decision mejora la usabilidad y evita que la pantalla principal del checklist se llene de logica accidental.
        """,
    )
    add_code_example(builder, photos_title, photos_code)

    builder.heading("Unidad 4. Administracion de datos en aplicaciones moviles", 1)
    paragraph_block(
        builder,
        """
        La administracion de datos en la APK SAICO-BETA se resuelve mediante modelos Dart, repositorios, serializacion JSON, manejo de `FormData` multipart y persistencia local del token. La app combina lectura de datos estructurados y envio de datos enriquecidos con evidencia fotografica, lo cual la convierte en un caso completo para estudiar consumo de API desde Android.
        """,
    )
    builder.image(assets["data_flow"], "Flujo de datos", 6.3, 3.55)
    caption(builder, "Figura 8. Flujo de datos JSON y multipart dentro de la APK movil.")

    builder.heading("4.1 Modelos y parseo de JSON", 2)
    paragraph_block(
        builder,
        """
        Las clases `CatalogVehicle`, `CatalogUser` y `VehicleExitSummary` modelan la estructura de datos que la app necesita para trabajar. No se limitan a almacenar campos; incorporan comportamiento de dominio como `isInsuranceValidAt`, `isCirculationCardValidAt`, `isLicenseValidAt` y `canRequestVehicle`. Con esto, el modelo movil se convierte en una capa semantica que simplifica la UI y evita repetir interpretaciones en cada pantalla.

        Este punto es esencial desde la materia Aplicaciones Moviles: los modelos no solo reflejan JSON, sino que organizan el conocimiento operativo del cliente. Al parsear fechas, enteros y banderas booleanas, la app transforma respuestas REST en objetos listos para usar.
        """,
    )
    add_code_example(builder, models_title, models_code)
    builder.code_block(
        normalize_indent(
            """
            {
              "id": 18,
              "chofer_id": 2,
              "solicitado_por": 1,
              "folio": "SV-20260508-001",
              "estatus": "activo",
              "vehiculo": {
                "id": 1,
                "placa": "YH-241-A",
                "marca": "Nissan",
                "modelo": "NP300",
                "kilometraje_actual": 128440,
                "documentacion_estatus": "completa"
              },
              "chofer": {
                "id": 2,
                "name": "Carlos Mendoza",
                "licencia_vencimiento": "2026-08-15"
              }
            }
            """
        )
    )
    caption(builder, "Ejemplo JSON representativo del tipo de datos que la app mapea a VehicleExitSummary.")

    builder.heading("4.2 Repositorios y sincronizacion de datos", 2)
    paragraph_block(
        builder,
        """
        Los repositorios desacoplan el origen de datos de las pantallas. `VehicleRequestRepository` concentra la consulta de vehiculos disponibles, usuarios operativos, salidas y detalle de una salida; `ChecklistRepository` encapsula el envio de checklists; y `AuthRepository` gestiona autenticacion y perfil. Este diseno facilita tanto la sincronizacion con la API real como el uso de `MockMobileStore` para pruebas, demostraciones y desarrollo sin dependencia permanente del backend.

        La sincronizacion movil no solo consiste en descargar datos; tambien en refrescar el estado al completar una operacion. El controlador `VehicleOperationsController`, por ejemplo, recarga la informacion despues de crear una nueva salida, logrando que el dashboard refleje inmediatamente el nuevo contexto operativo.
        """,
    )

    builder.heading("4.3 Envio multipart y validaciones de integridad", 2)
    paragraph_block(
        builder,
        """
        La integridad de los datos moviles se protege tanto en la interfaz como en el payload. Antes de enviar el checklist, la app verifica kilometraje valido y numero exacto de fotografias. Despues construye un `FormData` con campos escalares, herramientas opcionales y el arreglo `evidencias[]`. Este diseno es particularmente apropiado para aplicaciones Android de campo porque permite adjuntar archivos binarios y datos estructurados en una sola transaccion.
        """,
    )
    add_code_example(builder, formdata_title, formdata_code)

    builder.heading("Unidad 5. Analitica movil", 1)
    paragraph_block(
        builder,
        """
        La analitica movil de SAICO-BETA es de naturaleza operativa. Aunque la APK no es un BI completo, si ofrece indicadores inmediatos que ayudan a decidir y a ejecutar tareas en campo. El dashboard muestra salidas activas y vehiculos disponibles; las tarjetas del flujo contextualizan al operador; y el historial reciente permite seguimiento rapido de folios y estatus.

        Adicionalmente, la estructura de datos de la app permite derivar metricas mas detalladas: kilometraje capturado, checklists completados, documentos validos, herramientas disponibles y cantidad de evidencias registradas. Desde la perspectiva de la materia, esto demuestra que una app movil puede ser tanto una interfaz de captura como una fuente de informacion operativa para el usuario final.
        """,
    )
    builder.image(assets["analytics"], "Analitica movil", 6.2, 4.0)
    caption(builder, "Figura 9. Sintesis de indicadores operativos visibles o derivables desde la APK movil.")

    builder.table(
        ["Indicador movil", "Origen en la APK", "Uso operativo"],
        [
            ["Salidas activas", "VehicleOperationsHomeScreen", "Saber si la unidad actual requiere checklist o cierre."],
            ["Vehiculos disponibles", "Catalogos REST + summary card", "Determinar si puede iniciarse una nueva solicitud."],
            ["Kilometraje de salida y entrada", "ChecklistPayload", "Trazabilidad del uso de la unidad."],
            ["Evidencias por checklist", "PhotoEvidencePicker + ChecklistRepository", "Respaldar visualmente el estado del vehiculo."],
            ["Historial reciente", "Lista de exits", "Consultar folios, estatus y acceso rapido a detalle."],
        ],
    )

    builder.heading("Arquitectura completa de la APK movil", 1)
    paragraph_block(
        builder,
        """
        La arquitectura completa de la APK puede resumirse en cuatro capas. La primera es presentacion, compuesta por `AdaptivePageScaffold`, `SaicoBrandHeader`, tarjetas, botones y pantallas por feature. La segunda es control y estado, donde `Riverpod` coordina autentificacion, dashboard y envio de formularios. La tercera es acceso a datos, formada por repositorios y modelos Dart. La cuarta es integracion, sostenida por `Dio`, `DioInterceptor`, `ApiEndpoints` y la API REST protegida por Sanctum.

        Este diseño es apropiado para una APK universitaria de nivel avanzado porque separa responsabilidades, promueve reutilizacion y permite evolucionar la app sin mezclar logica de UI con red o persistencia. Tambien facilita la redaccion tecnica: cada modulo tiene una frontera clara y una intencion funcional verificable en el codigo.
        """,
    )

    builder.heading("Consumo de API REST", 1)
    paragraph_block(
        builder,
        """
        El consumo de API REST en la app se apoya en contratos concretos y bien delimitados. El login manda `email`, `password` y `device_name`. Los catalogos descargan listas JSON para vehiculos y usuarios. Las salidas se consultan con `GET /salidas` y se crean con `POST /salidas`. Los checklists se envian como multipart con campos operativos y tres fotografias. La sesion actual se resuelve con `GET /auth/me`.

        En terminos de arquitectura movil, el valor de este diseño reside en que la UI no conoce detalles de sockets, headers o serializacion binaria. Todo ese comportamiento queda encapsulado, mientras las pantallas solo coordinan entradas del usuario y reaccionan ante exito o error.
        """,
    )
    builder.table(
        ["Operacion", "Metodo y endpoint", "Payload / respuesta", "Pantalla asociada"],
        [
            ["Iniciar sesion", "POST /auth/login", "Credenciales y device_name / token + user", "LoginScreen"],
            ["Perfil actual", "GET /auth/me", "Sin payload / usuario actual", "SessionProvider"],
            ["Consultar vehiculos", "GET /catalogos/vehiculos-disponibles", "JSON de unidades", "RequestVehicleScreen"],
            ["Crear salida", "POST /salidas", "vehiculo_id, chofer_id, solicitado_por, fecha_salida, motivo", "RequestVehicleScreen"],
            ["Checklist salida", "POST /salidas/{id}/checklist-salida", "multipart con herramientas y evidencias", "DepartureChecklistScreen"],
            ["Checklist entrada", "POST /salidas/{id}/checklist-entrada", "multipart con evidencias", "ArrivalChecklistScreen"],
        ],
    )

    builder.heading("Evidencias visuales", 1)
    paragraph_block(
        builder,
        """
        Las evidencias visuales integradas en este documento fueron reconstruidas directamente a partir de las pantallas, widgets y flujos reales del proyecto Flutter, debido a que no se conto con un PDF adicional en el workspace. Aun asi, cada lamina mantiene correspondencia con el codigo fuente de la APK, con sus componentes visibles, sus titulos operativos y sus decisiones de interfaz.
        """,
    )
    builder.image(assets["detail"], "Detalle de checklist", 3.0, 5.7)
    caption(builder, "Figura 10. Reconstruccion visual de la vista de detalle de checklist y evidencias.")

    builder.heading("Resultados obtenidos", 1)
    paragraph_block(
        builder,
        """
        La APK SAICO-BETA demuestra que es posible trasladar un flujo vehicular completo a Android sin perder control operativo. El resultado observable es una experiencia movil centrada, coherente y utilizable: el usuario inicia sesion, consulta su contexto, crea salidas solo cuando corresponde, captura checklists con restricciones claras y conserva evidencia visual del estado de la unidad.

        Tecnica y academicamente, el proyecto obtuvo una arquitectura modular, una capa de seguridad basada en token, un modelo de datos consistente, una navegacion declarativa y una integracion REST adecuada para un entorno universitario avanzado. La app tambien incorpora facilidades de demostracion como datos mock, lo cual incrementa su valor pedagogico y su robustez para presentaciones o pruebas.
        """,
    )

    builder.heading("Conclusiones", 1)
    paragraph_block(
        builder,
        """
        La conclusion principal es que la APK SAICO-BETA cumple con los principios esenciales de una aplicacion movil empresarial: especializacion funcional, interfaz orientada a tareas, integracion segura con servicios, captura de evidencia y soporte a movilidad operativa real. El proyecto no se limita a trasladar formularios a Android; reorganiza el flujo para que tenga sentido en campo, usando dashboard contextual, componentes adaptativos y validaciones que previenen errores en origen.

        Tambien se concluye que Flutter y Dart fueron una eleccion adecuada para este caso de estudio. La separacion por features, el uso de Riverpod y GoRouter, la gestion segura de tokens y el envio multipart confirman una base tecnica suficiente para considerarlo un trabajo universitario avanzado en Ingenieria en Sistemas Computacionales. La APK representa un producto funcional, documentable y alineado con las competencias de la materia Aplicaciones Moviles.
        """,
    )

    builder.heading("Recomendaciones", 1)
    paragraph_block(
        builder,
        """
        Se recomienda como siguiente paso incorporar almacenamiento offline de formularios pendientes para operar aun sin conectividad, sincronizacion diferida de evidencias, pruebas de widgets y golden tests para las pantallas mas criticas, asi como indicadores graficos adicionales en el dashboard movil. Tambien seria conveniente formalizar permisos Android si en versiones futuras se amplian las capacidades de camara, ubicacion o archivos.

        En el plano academico, se recomienda conservar la trazabilidad entre requerimiento funcional y archivo fuente, ya que eso permite producir documentacion tecnica de alta calidad. Asimismo, si posteriormente se dispone de capturas reales o PDF de presentacion, pueden integrarse al mismo generador para enriquecer la parte visual sin alterar la estructura principal del documento.
        """,
    )

    builder.heading("Bibliografia", 1)
    for item in [
        "Android Developers. (2026). App fundamentals. https://developer.android.com/guide/components/fundamentals",
        "Dart Team. (2026). Dart language tour. https://dart.dev/language",
        "Flutter. (2026). Architectural overview. https://docs.flutter.dev/resources/architectural-overview",
        "Flutter. (2026). Navigation and routing. https://docs.flutter.dev/ui/navigation",
        "Flutter. (2026). Networking. https://docs.flutter.dev/cookbook/networking/fetch-data",
        "Flutter. (2026). Forms and validation. https://docs.flutter.dev/cookbook/forms/validation",
        "Flutter. (2026). Build a form with validation. https://docs.flutter.dev/cookbook/forms/validation",
        "JSON. (2017). The JavaScript Object Notation (JSON) Data Interchange Format (RFC 8259). https://www.rfc-editor.org/rfc/rfc8259",
        "Fielding, R. T. (2000). Architectural Styles and the Design of Network-based Software Architectures. University of California, Irvine.",
        "OpenJS Foundation. (2026). HTTP semantics overview. https://developer.mozilla.org/en-US/docs/Web/HTTP/Overview",
    ]:
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
