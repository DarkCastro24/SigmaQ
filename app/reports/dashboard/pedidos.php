<?php
require('../../helpers/report.php');
require('../../models/pedidos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReportL('Reporte de pedidos organizados por responsable');
// Se instancia el módelo Pedidos para obtener los datos.
$pedidos = new Pedidos;

// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataResponsables = $pedidos->readResponsables()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    foreach ($dataResponsables as $rowResponsables) {
        // Se establece un color de relleno para mostrar el nombre de la categoría.
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(240, 10, utf8_decode('Responsable: '.$rowResponsables['responsable']), 1, 1, 'C', 1);
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($pedidos->setResponsable($rowResponsables['codigoadmin'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataResponsable = $pedidos->readPedidosResponsable()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(230,231,232);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Times', 'B', 11);
                $pdf->SetTextColor(0);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(40, 10, utf8_decode('Código'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('POS'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('OC'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Registro'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Entrega'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Confirmación'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Times', '', 11);
                //$pdf->SetTextColor(0);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataResponsable as $rowProductos) {
                    $pdf->SetTextColor(0);
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->Cell(40, 10, utf8_decode($rowProductos['codigo']), 1, 0, 'C');
                    $pdf->Cell(40, 10, utf8_decode($rowProductos['pos']), 1, 0, 'C');
                    $pdf->Cell(40, 10, utf8_decode($rowProductos['oc']), 1, 0, 'C');
                    $pdf->Cell(40, 10, $rowProductos['fecharegistro'], 1, 0, 'C');
                    $pdf->Cell(40, 10, $rowProductos['fechaentregada'], 1, 0, 'C');
                    $pdf->Cell(40, 10, $rowProductos['fechaconfirmadaenvio'], 1, 1, 'C');
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