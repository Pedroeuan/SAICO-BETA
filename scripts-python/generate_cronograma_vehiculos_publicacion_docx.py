from __future__ import annotations

import datetime as dt
import subprocess
import zipfile
from pathlib import Path
from xml.sax.saxutils import escape


ROOT = Path(__file__).resolve().parents[1]
DOCS_DIR = ROOT / "docs"
ASSETS_DIR = DOCS_DIR / "assets"
OUTPUT_PATH = DOCS_DIR / "Cronograma_Gestion_de_Vehiculos_y_Publicacion.docx"
GANTT_IMAGE = ASSETS_DIR / "cronograma_gestion_vehiculos_publicacion.png"


MESES_ES = {
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


TASKS = [
    {
        "id": 1,
        "fase": "Planeación",
        "actividad": "Asignación de equipo, herramientas y calendario base del proyecto",
        "inicio": dt.date(2026, 1, 28),
        "fin": dt.date(2026, 1, 29),
        "entregable": "Plan de trabajo inicial",
        "estado": "Terminado",
    },
    {
        "id": 2,
        "fase": "Planeación",
        "actividad": "Levantamiento de requerimientos para gestión de vehículos y publicaciones",
        "inicio": dt.date(2026, 1, 30),
        "fin": dt.date(2026, 2, 3),
        "entregable": "Matriz de requerimientos",
        "estado": "Terminado",
    },
    {
        "id": 3,
        "fase": "Análisis",
        "actividad": "Análisis de procesos actuales, incidencias y reglas del negocio",
        "inicio": dt.date(2026, 2, 4),
        "fin": dt.date(2026, 2, 6),
        "entregable": "Diagnóstico funcional",
        "estado": "Terminado",
    },
    {
        "id": 4,
        "fase": "Diseño",
        "actividad": "Modelado de casos de uso, diagrama de clases y flujo de autorización",
        "inicio": dt.date(2026, 2, 9),
        "fin": dt.date(2026, 2, 12),
        "entregable": "Diseño funcional",
        "estado": "Terminado",
    },
    {
        "id": 5,
        "fase": "Diseño",
        "actividad": "Diseño de base de datos para salidas, checklists, evidencias y publicaciones",
        "inicio": dt.date(2026, 2, 13),
        "fin": dt.date(2026, 2, 17),
        "entregable": "Esquema relacional aprobado",
        "estado": "Terminado",
    },
    {
        "id": 6,
        "fase": "Back-end",
        "actividad": "Construcción de migraciones, modelos y catálogos del módulo vehicular",
        "inicio": dt.date(2026, 2, 18),
        "fin": dt.date(2026, 2, 24),
        "entregable": "Base estructural del módulo",
        "estado": "Terminado",
    },
    {
        "id": 7,
        "fase": "Back-end",
        "actividad": "Desarrollo de lógica CRUD para vehículos, choferes y documentos",
        "inicio": dt.date(2026, 2, 25),
        "fin": dt.date(2026, 3, 3),
        "entregable": "Servicios principales operativos",
        "estado": "Terminado",
    },
    {
        "id": 8,
        "fase": "Front-end",
        "actividad": "Diseño de formularios, validaciones y vistas administrativas de vehículos",
        "inicio": dt.date(2026, 3, 4),
        "fin": dt.date(2026, 3, 10),
        "entregable": "Interfaz administrativa",
        "estado": "Terminado",
    },
    {
        "id": 9,
        "fase": "Back-end",
        "actividad": "Implementación de solicitudes de salida, asignación de chofer y control de disponibilidad",
        "inicio": dt.date(2026, 3, 11),
        "fin": dt.date(2026, 3, 18),
        "entregable": "Flujo de solicitud de salida",
        "estado": "Terminado",
    },
    {
        "id": 10,
        "fase": "Calidad",
        "actividad": "Automatización de reglas de negocio para unidad ocupada, licencias y documentos vigentes",
        "inicio": dt.date(2026, 3, 19),
        "fin": dt.date(2026, 3, 24),
        "entregable": "Validaciones del negocio",
        "estado": "Terminado",
    },
    {
        "id": 11,
        "fase": "Operación",
        "actividad": "Desarrollo de checklists de salida y entrada con evidencias fotográficas",
        "inicio": dt.date(2026, 3, 25),
        "fin": dt.date(2026, 4, 1),
        "entregable": "Checklists funcionales",
        "estado": "Terminado",
    },
    {
        "id": 12,
        "fase": "Analítica",
        "actividad": "Construcción del panel de indicadores para uso, recorridos, incidencias y disponibilidad",
        "inicio": dt.date(2026, 4, 2),
        "fin": dt.date(2026, 4, 8),
        "entregable": "Panel operativo",
        "estado": "En proceso",
    },
    {
        "id": 13,
        "fase": "Publicación",
        "actividad": "Levantamiento de requerimientos para publicaciones, campañas y redes objetivo",
        "inicio": dt.date(2026, 4, 9),
        "fin": dt.date(2026, 4, 13),
        "entregable": "Alcance del módulo de publicación",
        "estado": "En proceso",
    },
    {
        "id": 14,
        "fase": "Publicación",
        "actividad": "Diseño del flujo editorial, estados del contenido y calendario de difusión",
        "inicio": dt.date(2026, 4, 14),
        "fin": dt.date(2026, 4, 17),
        "entregable": "Flujo editorial aprobado",
        "estado": "Pendiente",
    },
    {
        "id": 15,
        "fase": "Publicación",
        "actividad": "Desarrollo del registro de publicaciones, adjuntos, etiquetas y programación",
        "inicio": dt.date(2026, 4, 20),
        "fin": dt.date(2026, 4, 24),
        "entregable": "Módulo de publicaciones",
        "estado": "Pendiente",
    },
    {
        "id": 16,
        "fase": "Integración",
        "actividad": "Integración con scripts de difusión y consolidación de bitácora de resultados",
        "inicio": dt.date(2026, 4, 27),
        "fin": dt.date(2026, 4, 30),
        "entregable": "Automatización de difusión",
        "estado": "Pendiente",
    },
    {
        "id": 17,
        "fase": "Integración",
        "actividad": "Pruebas integrales entre gestión de vehículos, checklists y publicaciones",
        "inicio": dt.date(2026, 5, 1),
        "fin": dt.date(2026, 5, 5),
        "entregable": "Bitácora de pruebas integrales",
        "estado": "Pendiente",
    },
    {
        "id": 18,
        "fase": "Ajustes",
        "actividad": "Corrección de hallazgos funcionales, visuales y de consistencia de datos",
        "inicio": dt.date(2026, 5, 6),
        "fin": dt.date(2026, 5, 8),
        "entregable": "Versión estabilizada",
        "estado": "Pendiente",
    },
    {
        "id": 19,
        "fase": "Documentación",
        "actividad": "Redacción de manual operativo, evidencias y capacitación de usuarios clave",
        "inicio": dt.date(2026, 5, 11),
        "fin": dt.date(2026, 5, 13),
        "entregable": "Manual y acta de capacitación",
        "estado": "Pendiente",
    },
    {
        "id": 20,
        "fase": "Cierre",
        "actividad": "Publicación productiva, validación final y cierre formal del proyecto",
        "inicio": dt.date(2026, 5, 14),
        "fin": dt.date(2026, 5, 15),
        "entregable": "Liberación y cierre",
        "estado": "Pendiente",
    },
]


def format_date(value: dt.date) -> str:
    return f"{value.day:02d}/{value.month:02d}/{value.year}"


def format_date_long(value: dt.date) -> str:
    return f"{value.day} de {MESES_ES[value.month]} de {value.year}"


def working_days(start: dt.date, end: dt.date) -> int:
    days = 0
    current = start
    while current <= end:
        if current.weekday() < 5:
            days += 1
        current += dt.timedelta(days=1)
    return days


def run_powershell(script: str) -> None:
    subprocess.run(
        [
            "powershell",
            "-NoProfile",
            "-ExecutionPolicy",
            "Bypass",
            "-Command",
            script,
        ],
        cwd=ROOT,
        check=True,
    )


def build_gantt_image() -> Path:
    ASSETS_DIR.mkdir(parents=True, exist_ok=True)
    lines = []
    for task in TASKS:
        actividad = task["actividad"].replace('"', '""')
        fase = task["fase"].replace('"', '""')
        estado = task["estado"].replace('"', '""')
        lines.append(
            f'    [PSCustomObject]@{{Id={task["id"]}; Fase="{fase}"; Actividad="{actividad}"; '
            f'Inicio=[datetime]"{task["inicio"].isoformat()}"; Fin=[datetime]"{task["fin"].isoformat()}"; Estado="{estado}"}}'
        )
    tasks_block = "@(\n" + ",\n".join(lines) + "\n)"
    script = """
Add-Type -AssemblyName System.Drawing
$ErrorActionPreference = "Stop"

$output = "__OUTPUT__"
$tasks = __TASKS__
$start = [datetime]"2026-01-28"
$end = [datetime]"2026-05-15"
$dayCount = (($end - $start).Days + 1)

$leftMargin = 30
$topMargin = 110
$rowHeight = 38
$taskRows = $tasks.Count
$tableWidth = 760
$dayWidth = 12
$width = $leftMargin + $tableWidth + ($dayCount * $dayWidth) + 50
$height = $topMargin + (($taskRows + 2) * $rowHeight) + 70

$bmp = New-Object System.Drawing.Bitmap $width, $height
$g = [System.Drawing.Graphics]::FromImage($bmp)
$g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
$g.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::AntiAliasGridFit
$g.Clear([System.Drawing.Color]::White)

function Brush([string]$hex) {{
    New-Object System.Drawing.SolidBrush ([System.Drawing.ColorTranslator]::FromHtml($hex))
}}

function PenObj([string]$hex, [float]$width = 1) {{
    New-Object System.Drawing.Pen ([System.Drawing.ColorTranslator]::FromHtml($hex), $width)
}}

function FontObj([string]$name, [float]$size, [System.Drawing.FontStyle]$style = [System.Drawing.FontStyle]::Regular) {{
    New-Object System.Drawing.Font($name, $size, $style)
}}

$titleFont = FontObj "Arial" 18 ([System.Drawing.FontStyle]::Bold)
$subFont = FontObj "Arial" 10
$headerFont = FontObj "Arial" 9 ([System.Drawing.FontStyle]::Bold)
$cellFont = FontObj "Arial" 8.4
$miniFont = FontObj "Arial" 7.2

[void]$g.DrawString("Cronograma extendido de gestión de vehículos y publicación", $titleFont, (Brush "#12344D"), 30, 18)
[void]$g.DrawString("Periodo: 28 de enero de 2026 al 15 de mayo de 2026", $subFont, (Brush "#52606D"), 32, 50)

$columns = @(
    @{ Name = "Id"; Width = 34 },
    @{ Name = "Fase"; Width = 105 },
    @{ Name = "Actividad"; Width = 420 },
    @{ Name = "Inicio"; Width = 68 },
    @{ Name = "Fin"; Width = 68 },
    @{ Name = "Estado"; Width = 65 }
)

$headerBg = Brush "#173F5F"
$headerText = Brush "#FFFFFF"
$gridPen = PenObj "#D9E2EC" 1
$weekendBrush = Brush "#F0F4F8"
$todayBrush = Brush "#E6FFFA"
$textBrush = Brush "#102A43"
$mutedBrush = Brush "#486581"
$altBrush = Brush "#FAFBFC"

$x = $leftMargin
foreach ($col in $columns) {{
    [void]$g.FillRectangle($headerBg, $x, $topMargin, $col.Width, $rowHeight)
    [void]$g.DrawString($col.Name, $headerFont, $headerText, [float]($x + 8), [float]($topMargin + 12))
    $x += $col.Width
}}

$timelineX = $leftMargin + $tableWidth
$monthStartX = $timelineX
$cursor = $start
while ($cursor -le $end) {{
    $monthBegin = Get-Date -Year $cursor.Year -Month $cursor.Month -Day 1
    if ($cursor -gt $monthBegin) {{ $monthBegin = $cursor }}
    $monthEnd = Get-Date -Year $cursor.Year -Month $cursor.Month -Day ([datetime]::DaysInMonth($cursor.Year, $cursor.Month))
    if ($monthEnd -gt $end) {{ $monthEnd = $end }}
    $span = (($monthEnd - $monthBegin).Days + 1) * $dayWidth
    [void]$g.FillRectangle((Brush "#173F5F"), $monthStartX, $topMargin, $span, $rowHeight)
    $title = "{0} {1}" -f $monthBegin.ToString("MMMM"), $monthBegin.Year
    [void]$g.DrawString($title, $headerFont, $headerText, [float]($monthStartX + 6), [float]($topMargin + 12))
    $monthStartX += $span
    $cursor = $monthEnd.AddDays(1)
}}

for ($d = 0; $d -lt $dayCount; $d++) {{
    $date = $start.AddDays($d)
    $xDay = $timelineX + ($d * $dayWidth)
    if ($date.DayOfWeek -eq [DayOfWeek]::Saturday -or $date.DayOfWeek -eq [DayOfWeek]::Sunday) {{
        [void]$g.FillRectangle($weekendBrush, $xDay, $topMargin + $rowHeight, $dayWidth, ($taskRows + 1) * $rowHeight)
    }}
    [void]$g.DrawRectangle($gridPen, $xDay, $topMargin + $rowHeight, $dayWidth, ($taskRows + 1) * $rowHeight)
    if ($date.DayOfWeek -eq [DayOfWeek]::Monday) {
        [void]$g.DrawString($date.ToString("dd"), $miniFont, $mutedBrush, [float]$xDay, [float]($topMargin + $rowHeight + 13))
    }
}}

$yRow = $topMargin + ($rowHeight * 2)
foreach ($task in $tasks) {{
    if (($task.Id % 2) -eq 0) {{
        [void]$g.FillRectangle($altBrush, $leftMargin, $yRow, $tableWidth + ($dayCount * $dayWidth), $rowHeight)
    }}

    $rowData = @(
        $task.Id.ToString(),
        $task.Fase,
        $task.Actividad,
        $task.Inicio.ToString("dd/MM/yy"),
        $task.Fin.ToString("dd/MM/yy"),
        $task.Estado
    )

    $xCell = $leftMargin
    for ($i = 0; $i -lt $columns.Count; $i++) {{
        $col = $columns[$i]
        [void]$g.DrawRectangle($gridPen, $xCell, $yRow, $col.Width, $rowHeight)
        if ($i -eq 2) {{
            $actividad = $rowData[$i]
            if ($actividad.Length -gt 66) {{
                $actividad = $actividad.Substring(0, 63) + "..."
            }}
            [void]$g.DrawString($actividad, $cellFont, $textBrush, [float]($xCell + 3), [float]($yRow + 12))
        }} else {{
            [void]$g.DrawString($rowData[$i], $cellFont, $textBrush, [float]($xCell + 6), [float]($yRow + 12))
        }}
        $xCell += $col.Width
    }}

    $startIndex = ($task.Inicio - $start).Days
    $spanDays = (($task.Fin - $task.Inicio).Days + 1)
    $barX = $timelineX + ($startIndex * $dayWidth) + 1
    $barW = ($spanDays * $dayWidth) - 2
    $barY = $yRow + 7
    $barH = $rowHeight - 14

    switch ($task.Estado) {{
        "Terminado" {{ $fill = Brush "#D9827B"; $border = PenObj "#B95E58" 1.2 }}
        "En proceso" {{ $fill = Brush "#5DADE2"; $border = PenObj "#2E86C1" 1.2 }}
        default {{ $fill = Brush "#AAB7B8"; $border = PenObj "#7B8A8B" 1.2 }}
    }}

    [void]$g.FillRectangle($fill, $barX, $barY, $barW, $barH)
    [void]$g.DrawRectangle($border, $barX, $barY, $barW, $barH)
    $yRow += $rowHeight
}}

[void]$g.DrawRectangle($gridPen, $leftMargin, $topMargin + $rowHeight, $tableWidth + ($dayCount * $dayWidth), ($taskRows + 1) * $rowHeight)

$legendY = $height - 35
[void]$g.FillRectangle((Brush "#D9827B"), 35, $legendY, 18, 12)
[void]$g.DrawString("Terminado", $cellFont, $textBrush, 58, $legendY - 2)
[void]$g.FillRectangle((Brush "#5DADE2"), 155, $legendY, 18, 12)
[void]$g.DrawString("En proceso", $cellFont, $textBrush, 180, $legendY - 2)
[void]$g.FillRectangle((Brush "#AAB7B8"), 295, $legendY, 18, 12)
[void]$g.DrawString("Pendiente", $cellFont, $textBrush, 320, $legendY - 2)

$bmp.Save($output, [System.Drawing.Imaging.ImageFormat]::Png)
$g.Dispose()
$bmp.Dispose()
""".replace("__OUTPUT__", str(GANTT_IMAGE)).replace("__TASKS__", tasks_block).replace("{{", "{").replace("}}", "}")
    run_powershell(script)
    return GANTT_IMAGE


class DocBuilder:
    def __init__(self) -> None:
        self.parts: list[str] = []
        self.image_rels: list[dict[str, str]] = []
        self.image_counter = 1

    @staticmethod
    def _run_props(
        *,
        bold: bool = False,
        italic: bool = False,
        size: int | None = None,
        color: str | None = None,
        font: str | None = None,
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
            props.append(f'<w:spacing w:before="{space_before or 0}" w:after="{space_after or 0}"/>')
        p_pr = f"<w:pPr>{''.join(props)}</w:pPr>" if props else ""

        run_props = self._run_props(bold=bold, italic=italic, size=size, color=color, font=font)
        runs = []
        split_lines = text.split("\n")
        for index, line in enumerate(split_lines):
            t_attr = ' xml:space="preserve"' if line.startswith(" ") or line.endswith(" ") else ""
            runs.append(f"<w:r>{run_props}<w:t{t_attr}>{escape(line)}</w:t></w:r>")
            if index < len(split_lines) - 1:
                runs.append("<w:r><w:br/></w:r>")
        self.parts.append(f"<w:p>{p_pr}{''.join(runs)}</w:p>")

    def heading(self, text: str, level: int) -> None:
        self.paragraph(text, style=f"Heading{level}", space_before=180, space_after=80)

    def table(self, headers: list[str], rows: list[list[str]]) -> None:
        def cell(text: str, *, header: bool = False) -> str:
            fill = "D9EAF7" if header else "FFFFFF"
            return (
                "<w:tc>"
                "<w:tcPr>"
                "<w:tcW w:w=\"0\" w:type=\"auto\"/>"
                '<w:vAlign w:val="center"/>'
                f'<w:shd w:val="clear" w:color="auto" w:fill="{fill}"/>'
                "</w:tcPr>"
                "<w:p>"
                f'<w:pPr><w:jc w:val="center"/><w:spacing w:before="30" w:after="30"/></w:pPr>'
                f'<w:r>{self._run_props(bold=header, font="Arial", size=20)}<w:t>{escape(text)}</w:t></w:r>'
                "</w:p>"
                "</w:tc>"
            )

        row_xml = [f"<w:tr>{''.join(cell(item, header=True) for item in headers)}</w:tr>"]
        for row in rows:
            row_xml.append(f"<w:tr>{''.join(cell(item) for item in row)}</w:tr>")
        self.parts.append(
            "<w:tbl>"
            "<w:tblPr>"
            '<w:tblStyle w:val="TableGrid"/>'
            '<w:tblW w:w="0" w:type="auto"/>'
            '<w:jc w:val="center"/>'
            "<w:tblBorders>"
            '<w:top w:val="single" w:sz="8" w:color="B7C6D0"/>'
            '<w:left w:val="single" w:sz="8" w:color="B7C6D0"/>'
            '<w:bottom w:val="single" w:sz="8" w:color="B7C6D0"/>'
            '<w:right w:val="single" w:sz="8" w:color="B7C6D0"/>'
            '<w:insideH w:val="single" w:sz="6" w:color="D9E2EC"/>'
            '<w:insideV w:val="single" w:sz="6" w:color="D9E2EC"/>'
            "</w:tblBorders>"
            "</w:tblPr>"
            + "".join(row_xml)
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
            '<w:sectPr><w:pgSz w:w="15840" w:h="12240" w:orient="landscape"/>'
            '<w:pgMar w:top="850" w:right="850" w:bottom="850" w:left="850" w:header="708" w:footer="708" w:gutter="0"/>'
            '</w:sectPr>'
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
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:eastAsia="Arial" w:cs="Arial"/>
        <w:sz w:val="22"/><w:szCs w:val="22"/><w:color w:val="102A43"/>
      </w:rPr>
    </w:rPrDefault>
    <w:pPrDefault>
      <w:pPr><w:spacing w:after="110" w:line="300" w:lineRule="auto"/><w:jc w:val="both"/></w:pPr>
    </w:pPrDefault>
  </w:docDefaults>
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/></w:style>
  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/><w:pPr><w:jc w:val="center"/><w:spacing w:after="90"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:eastAsia="Arial" w:cs="Arial"/><w:b/><w:color w:val="12344D"/><w:sz w:val="34"/><w:szCs w:val="34"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Subtitle">
    <w:name w:val="Subtitle"/><w:pPr><w:jc w:val="center"/><w:spacing w:after="60"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:eastAsia="Arial" w:cs="Arial"/><w:color w:val="486581"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/><w:qFormat/><w:pPr><w:spacing w:before="200" w:after="90"/><w:jc w:val="left"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:eastAsia="Arial" w:cs="Arial"/><w:b/><w:color w:val="12344D"/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="BodyText">
    <w:name w:val="Body Text"/><w:pPr><w:jc w:val="both"/><w:spacing w:after="110" w:line="300" w:lineRule="auto"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:eastAsia="Arial" w:cs="Arial"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Caption">
    <w:name w:val="Caption"/><w:pPr><w:jc w:val="center"/><w:spacing w:before="30" w:after="90"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:eastAsia="Arial" w:cs="Arial"/><w:i/><w:color w:val="486581"/><w:sz w:val="18"/><w:szCs w:val="18"/></w:rPr>
  </w:style>
</w:styles>
"""


def settings_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:updateFields w:val="true"/></w:settings>
"""


def web_settings_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:webSettings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:optimizeForBrowser/></w:webSettings>
"""


def theme_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="SAICO Theme">
  <a:themeElements>
    <a:clrScheme name="SAICO">
      <a:dk1><a:srgbClr val="12344D"/></a:dk1>
      <a:lt1><a:srgbClr val="FFFFFF"/></a:lt1>
      <a:dk2><a:srgbClr val="102A43"/></a:dk2>
      <a:lt2><a:srgbClr val="F8FBFD"/></a:lt2>
      <a:accent1><a:srgbClr val="2E86C1"/></a:accent1>
      <a:accent2><a:srgbClr val="D9827B"/></a:accent2>
      <a:accent3><a:srgbClr val="7B8A8B"/></a:accent3>
      <a:accent4><a:srgbClr val="1F618D"/></a:accent4>
      <a:accent5><a:srgbClr val="5DADE2"/></a:accent5>
      <a:accent6><a:srgbClr val="D9EAF7"/></a:accent6>
      <a:hlink><a:srgbClr val="2E86C1"/></a:hlink>
      <a:folHlink><a:srgbClr val="7B8A8B"/></a:folHlink>
    </a:clrScheme>
    <a:fontScheme name="Arial"><a:majorFont><a:latin typeface="Arial"/></a:majorFont><a:minorFont><a:latin typeface="Arial"/></a:minorFont></a:fontScheme>
    <a:fmtScheme name="SAICO Format"/>
  </a:themeElements>
</a:theme>
"""


def app_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>OpenAI Codex</Application><Company>SAICO</Company><SharedDoc>false</SharedDoc><AppVersion>16.0000</AppVersion>
</Properties>
"""


def core_xml() -> str:
    created = dt.datetime.now(dt.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>Cronograma de gestión de vehículos y publicación</dc:title>
  <dc:subject>Planeación del proyecto del 28 de enero de 2026 al 15 de mayo de 2026</dc:subject>
  <dc:creator>OpenAI Codex</dc:creator>
  <dc:description>Documento Word con cronograma extendido, tablas centradas y diagrama de Gantt.</dc:description>
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


def document_rels_xml(image_rels: list[dict[str, str]]) -> str:
    parts = [
        '<Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>',
        '<Relationship Id="rIdSettings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>',
        '<Relationship Id="rIdTheme" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/>',
        '<Relationship Id="rIdWebSettings" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings" Target="webSettings.xml"/>',
    ]
    for rel in image_rels:
        parts.append(
            f'<Relationship Id="{rel["rid"]}" '
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" '
            f'Target="media/{rel["name"]}"/>'
        )
    return (
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
        + "".join(parts)
        + "</Relationships>"
    )


def caption(builder: DocBuilder, text: str) -> None:
    builder.paragraph(text, style="Caption", align="center")


def build_content(builder: DocBuilder, gantt_path: Path) -> None:
    inicio = TASKS[0]["inicio"]
    fin = TASKS[-1]["fin"]

    builder.paragraph("Cronograma extendido de gestión de vehículos y publicación", style="Title")
    builder.paragraph(
        f"Periodo calendarizado del {format_date_long(inicio)} al {format_date_long(fin)}",
        style="Subtitle",
    )
    builder.paragraph(
        "El presente documento organiza de forma más amplia las actividades del proyecto de gestión de vehículos y publicación. "
        "Se distribuyeron las tareas desde el 28 de enero de 2026 hasta el 15 de mayo de 2026, con una secuencia clara de planeación, diseño, construcción, integración, pruebas y cierre. "
        "El contenido fue redactado con ortografía revisada, uso correcto de acentos y formato homogéneo en fuente Arial.",
        style="BodyText",
    )

    builder.heading("1. Datos generales del cronograma", 1)
    builder.table(
        ["Concepto", "Detalle"],
        [
            ["Nombre del proyecto", "Gestión de vehículos y publicación"],
            ["Fecha de inicio", format_date_long(inicio)],
            ["Fecha de cierre", format_date_long(fin)],
            ["Duración total", f"{(fin - inicio).days + 1} días naturales"],
            ["Cobertura", "Planeación, desarrollo, integración, pruebas, documentación y publicación final"],
        ],
    )

    builder.heading("2. Cronograma detallado", 1)
    builder.paragraph(
        "La siguiente tabla resume las actividades principales y su distribución temporal. "
        "Cada registro incluye su fase, fechas previstas, entregable y estado general del avance.",
        style="BodyText",
    )
    builder.table(
        ["Id", "Fase", "Actividad", "Duración hábil", "Inicio", "Fin", "Entregable", "Estado"],
        [
            [
                str(task["id"]),
                task["fase"],
                task["actividad"],
                f"{working_days(task['inicio'], task['fin'])} días",
                format_date(task["inicio"]),
                format_date(task["fin"]),
                task["entregable"],
                task["estado"],
            ]
            for task in TASKS
        ],
    )

    builder.heading("3. Diagrama de Gantt extendido", 1)
    builder.paragraph(
        "El diagrama siguiente amplía la vista del proyecto y muestra la ocupación temporal de cada tarea desde enero hasta mayo. "
        "La lectura visual permite identificar los bloques ya concluidos, las actividades en curso y las pendientes para la liberación final.",
        style="BodyText",
    )
    builder.image(gantt_path, "Diagrama de Gantt extendido", 14.5, 6.8)
    caption(
        builder,
        "Figura 1. Diagrama de Gantt extendido del proyecto de gestión de vehículos y publicación.",
    )

    builder.heading("4. Hitos principales", 1)
    builder.table(
        ["Hito", "Fecha comprometida", "Resultado esperado"],
        [
            ["Cierre de análisis y diseño", "17/02/2026", "Modelo funcional y esquema de datos aprobados"],
            ["Operación completa del módulo vehicular", "01/04/2026", "Solicitudes, checklists y evidencias funcionando"],
            ["Panel de indicadores operativo", "08/04/2026", "Seguimiento de uso, recorridos e incidencias"],
            ["Módulo de publicación integrado", "30/04/2026", "Programación y automatización de difusión listas"],
            ["Liberación final", "15/05/2026", "Solución validada, documentada y publicada"],
        ],
    )

    builder.heading("5. Observaciones de gestión", 1)
    builder.paragraph(
        "Se procuró que la ruta crítica quedara concentrada en la operación del módulo vehicular durante febrero, marzo y la primera semana de abril, debido a que esa parte sostiene las validaciones de negocio, la trazabilidad de recorridos y la captura de evidencias. "
        "Posteriormente, el módulo de publicación se programó en abril para aprovechar los catálogos, las bitácoras y la documentación ya consolidados.",
        style="BodyText",
    )
    builder.paragraph(
        "Si deseas una segunda versión todavía más detallada, también se puede dividir este mismo cronograma por semanas, responsables, dependencias o porcentaje de avance, manteniendo el mismo formato de Word.",
        style="BodyText",
    )


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
    gantt_path = build_gantt_image()
    builder = DocBuilder()
    build_content(builder, gantt_path)
    write_docx(OUTPUT_PATH, builder)
    print(f"Documento generado en: {OUTPUT_PATH}")


if __name__ == "__main__":
    main()
