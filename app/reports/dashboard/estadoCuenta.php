<?php
require('../../helpers/report.php');
require('../../models/estadoCuenta.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReportL('Reporte de estados de cuenta agrupado por cliente');
// Se instancia el módelo EstadoCuenta para obtener los datos.
$estado = new EstadoCuenta;

// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataResponsables = $estado->readResponsables()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    foreach ($dataResponsables as $rowResponsables) {
        // Se establece un color de relleno para mostrar el nombre de la categoría.
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(0, 10, utf8_decode('Cliente: '.$rowResponsables['responsable']), 1, 1, 'C', 1);
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($estado->setResponsable($rowResponsables['codigoadmin'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataResponsable = $estado->selectEstadoReporte()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(230,231,232);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Times', 'B', 11);
                $pdf->SetTextColor(0);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(20, 10, utf8_decode('Código'), 1, 0, 'C', 1);
                $pdf->Cell(20, 10, utf8_decode('Factura'), 1, 0, 'C', 1);
                $pdf->Cell(30, 10, utf8_decode('Asignación'), 1, 0, 'C', 1);
                $pdf->Cell(20, 10, utf8_decode('Clase'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Fecha Contable'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Vencimiento'), 1, 0, 'C', 1);
                $pdf->Cell(30, 10, utf8_decode('Días restantes'), 1, 0, 'C', 1);
                $pdf->Cell(20, 10, utf8_decode('Divisa'), 1, 0, 'C', 1);
                $pdf->Cell(29, 10, utf8_decode('Total'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Times', '', 11);
                //$pdf->SetTextColor(0);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataResponsable as $rowProductos) {
                    $pdf->SetTextColor(0);
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->Cell(20, 10, utf8_decode($rowProductos['codigo']), 1, 0, 'C');
                    $pdf->Cell(20, 10, utf8_decode($rowProductos['factura']), 1, 0, 'C');
                    $pdf->Cell(30, 10, utf8_decode($rowProductos['asignacion']), 1, 0, 'C');
                    $pdf->Cell(20, 10, utf8_decode($rowProductos['clase']), 1, 0, 'C');
                    $pdf->Cell(40, 10, $rowProductos['fechacontable'], 1, 0, 'C');
                    $pdf->Cell(40, 10, $rowProductos['vencimiento'], 1, 0, 'C');
                    $pdf->Cell(30, 10, $rowProductos['diasrestantes'], 1, 0, 'C');
                    $pdf->Cell(20, 10, $rowProductos['divisa'], 1, 0, 'C');
                    $pdf->Cell(29, 10, utf8_decode($rowProductos['totalgeneral']), 1, 1, 'C');
                }
                $pdf->Ln(5);
            } else {
                $pdf->Cell(0, 10, utf8_decode('No hay productos para esta categoría'), 1, 1);
            }
        } else {
            $pdf->Cell(0, 10, utf8_decode('Categoría incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->Cell(0, 10, utf8_decode('No hay categorías para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>