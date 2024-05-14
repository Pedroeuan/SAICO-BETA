<?php
//include library
include('library/tcpdf.php');


$pdf = new TCPDF('P','mm','A4');
//conexion a la Base de datos
include ('conexion.php');

//remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Definir el tamaño de la fuente
$tamano_fuente = 8;

// Establecer la fuente predeterminada
$pdf->SetFont('helvetica', '', $tamano_fuente);

// Definir el contenido dinámico
$formato = "FOR-PCVE-01/05";
$version = "2";
$persona = "Nombre de la persona";
$fecha = date('d-m-Y'); // Obtener la fecha actual
$destino = "Nombre del destino";
$trabajo = "Nombre del trabajo";

// Definir una función personalizada para el encabezado
function drawHeader($pdf, $formato, $version, $persona, $fecha, $destino, $trabajo, $tamano_fuente) {
    $pdf->Image('logo 1.jpg',175,15,20);
    $pdf->Cell(90, 10, "FORMATO", 1, 0, 'C', false, 0);
    $pdf->Cell(30, 10, "Código", 1, 0, 'C', false, 0);
    $pdf->Cell(40, 10, $formato, 1, 0, 'C', false, 0);
    $pdf->Cell(30, 30, "", 1, 0, 'C', false, 0);
    $pdf->Cell(0, 10, "", 0, 1);
    $pdf->Cell(90, 20, " Manifiesto de Salida y/o Resguardo", 1, 0, 'C', false, 0);
    $pdf->Cell(30, 10, "Versión", 1, 0, 'C', false, 0);
    $pdf->Cell(40, 10, $version, 1, 0, 'C', false, 0);
    $pdf->Ln();
    $pdf->Cell(90, 10, "", 0, 0);
    $pdf->Cell(30, 10, "Página", 1, 0, 'C', false, 0);

    // Obtener el número total de páginas
    $paginas_totales = $pdf->getNumPages();

    // Actualizar el contenido del PDF con el número total de páginas
    $pdf->Cell(40, 10, $pdf->getAliasNumPage() . ' de ' . $paginas_totales, 1, 0, 'C', false, 0);
    $pdf->Ln(11);
    $pdf->Cell(40, 10, "PERSONA", 0, 0, 'C');
    $pdf->Cell(80, 10, $persona, 0, 0);
    $pdf->Cell(30, 10, "FECHA", 0, 0, 'C');
    $pdf->Cell(30, 10, $fecha, 0, 0);
    $pdf->Ln();
    $pdf->Cell(40, 10, "DESTINO", 0, 0, 'C');
    $pdf->Cell(150, 10, $destino, 0, 0);
    $pdf->Ln();
    $pdf->Cell(40, 10, "TRABAJO", 0, 0, 'C');
    $pdf->Cell(150, 10, $trabajo, 0, 0);
    $pdf->Ln();
}



// Agregar primera página
$pdf->AddPage();

// Dibujar el encabezado en la primera página
drawHeader($pdf, $formato, $version, $persona, $fecha, $destino, $trabajo, $tamano_fuente);

 $pdf->SetFillColor(142,169,219);
$pdf->Cell(190,10,"EQUIPOS",1,0,'C',true);
$pdf->Ln();

$sql = "SELECT * FROM equipo_prueba";
$result = $conexion->query($sql);
/// Construir la tabla
if ($result->num_rows > 0) {
    // Determinar el número de columnas
    $num_fields = $result->field_count;

    // Calcular el ancho de cada columna
    $total_width = 190; // Tamaño total deseado en mm
    $column_width = $total_width / $num_fields;

    // Construir la cabecera de la tabla
    $header = array();
    while ($fieldinfo = $result->fetch_field()) {
        $header[] = $fieldinfo->name;
        $pdf->Cell($column_width, 10, $fieldinfo->name, 1, 0, 'C', 1);
    }
    $pdf->Ln(); // Nueva línea después de la cabecera

    // Construir el contenido de la tabla
    while($row = $result->fetch_assoc()) {
        foreach($row as $value) {
            $pdf->Cell($column_width, 10, $value, 1, 0, 'C');
        }
        $pdf->Ln(); // Nueva línea después de cada fila
    }
} else {
    $pdf->Cell(190, 10, 'No se encontraron resultados', 1, 1, 'C');
}

$pdf->Ln(3);
$pdf->SetFillColor(142,169,219);
$pdf->Cell(190,10,"ADICIONAL (accesorio, consumible, y/o herramientas)",1,0,'C',true);
$pdf->Ln();

$sql = "SELECT * FROM adicional_prueba";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    // Definir el ancho de cada columna
    $ancho_columnas = array(20, 20, 150); // Ancho de cada columna en mm

    // Obtener los nombres de las columnas
    $header = array();
    while ($fieldinfo = $result->fetch_field()) {
        $header[] = $fieldinfo->name;
    }

    // Construir la tabla
    if ($result->num_rows > 0) {
        // Construir la cabecera de la tabla
        foreach ($header as $nombre_columna) {
            $pdf->Cell($ancho_columnas[array_search($nombre_columna, $header)], 10, $nombre_columna, 1, 0, 'C', true);
        }
        $pdf->Ln(); // Nueva línea después de la cabecera

        // Construir el contenido de la tabla
        while($row = $result->fetch_assoc()) {
            // Utilizar índice en lugar de foreach para acceder a los valores
            foreach ($header as $nombre_columna) {
                $value = $row[$nombre_columna]; // Obtener el valor del campo basado en el nombre de la columna
                $pdf->Cell($ancho_columnas[array_search($nombre_columna, $header)], 10, $value, 1, 0, 'C');
            }
            $pdf->Ln(); // Nueva línea después de cada fila
        }
    } else {
        $pdf->Cell(190, 10, 'No se encontraron resultados', 1, 1, 'C');
    }
}

$nota = "Nota 1: Los Equipos se entregan en las siguientes condiciones: limpios, en condiciones  operables para su uso y quedan al resguardo del abajo firmante, siendo su responsabilidad de cada uno de los equipos aquí mencionados, excepto de los consumibles. Se deberá mantener en buen estado y que NO sea deteriorado por condiciones ajenas a su fin establecido. En caso de extravío o daño injustificado se tendrá que justificar el percance ocurrido a través de un reporte  dirigido al  PCVE, para determinar  la Reposición  del Equipo/ y/o accesorio.";

$pdf->MultiCell(190, 10, $nota, 1, 'L'); // Añadir la nota en una celda con bordes visibles
$pdf->Ln(5); // Añadir espacio antes de los firmantes
$sql_firmantes = "SELECT * FROM firmantes";
$result_firmantes = $conexion->query($sql_firmantes);

// Obtener los nombres de los firmantes
$primer_firmante = "";
$segundo_firmante = "";

$sql = "SELECT * FROM firmantes";
$result = $conexion->query($sql);
$firmante1 = "";
$firmante2 = "";
if ($result->num_rows >= 2) {
    $row = $result->fetch_assoc();
    $firmante1 = $row['nombre_tecnico'];
    $row = $result->fetch_assoc();
    $firmante2 = $row['nombre_tecnico'];
}

// Calcula la posición y el espacio restante en la página
$remaining_height = $pdf->getPageHeight() - $pdf->GetY();
$signature_height = 20; // Altura de cada firma
$vertical_space = 10; // Espacio vertical entre las firmas
$total_signature_height = ($signature_height * 2) + $vertical_space;

// Si hay suficiente espacio restante en la página, coloca las firmas
if ($remaining_height > $total_signature_height) {
    $pdf->SetY(-$total_signature_height);
} else {
    $pdf->AddPage(); // Agrega una nueva página
}


// Agregar firmantes
$pdf->Cell(95, $signature_height, '______________________________', 0, 0, 'C'); // Firma 1
$pdf->Cell(95, $signature_height, '______________________________', 0, 1, 'C'); // Firma 2
$pdf->Cell(95, 5, $firmante1, 0, 0, 'C'); // Nombre del Firmante 1
$pdf->Cell(95, 5, $firmante2, 0, 1, 'C'); // Nombre del Firmante 2

$pdf->Output();