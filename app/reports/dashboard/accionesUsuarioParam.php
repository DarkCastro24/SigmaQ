<?php
require('../../helpers/report.php');
require('../../models/usuarios.php');

// Creamos un atributo para almacenar el numero de registros
$numero = 0;
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de acciones realizadas por un usuario');
// Se instancia el módelo Usuario para obtener los datos.
$categoria = new Usuario;

// Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
if ($categoria->setId($_GET['id'])) {
    // Se verifica si existen registros usuarios para mostrar, de lo contrario se imprime un mensaje.
    if ($dataCategorias = $categoria->readUsuarios()) {
        // Se recorren los registros fila por fila.
        foreach ($dataCategorias as $rowCategoria) {
            // Creamos un atributo para almacenar el numero de registros
            $cantidad = 0;
            // Se establece un color de relleno para mostrar el encabezado de la tabla.
            $pdf->SetFillColor(0,0,0);
            // Se establece la fuente para el encabezado de la tabla.
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->SetTextColor(255);
            // Se imprime una celda con el nombre de usuario.
            $pdf->Cell(0, 10, utf8_decode('Rango de fechas seleccionado'), 1, 1, 'C', 1);
            // Se establece un color de relleno para los encabezados.
            $pdf->SetFillColor(230,231,232);
            // Se establece la fuente para los encabezados.
            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->SetTextColor(9,9,9);
            // Se imprimen las celdas con los encabezados.
            $pdf->Cell(93, 10, utf8_decode('Fecha inicial'), 1, 0, 'C', 1);
            $pdf->Cell(93, 10, utf8_decode('Fecha final'), 1, 1, 'C', 1);            
            // Se establece la fuente para los datos de las acciones.
            $pdf->SetFont('Helvetica', '', 11);
            // Se van sumando el numero de registros para mostrarse en la tabla
            $pdf->SetTextColor(9,9,9);
            // Se imprimen las celdas con las fechas seleccionadas por el usuario.
            $pdf->Cell(93, 10, utf8_decode($_SESSION['fechaInicio']), 1, 0);
            $pdf->Cell(93, 10, utf8_decode($_SESSION['fechaFin']), 1, 1);
            // Se agrega un salto de línea para mostrar el contenido principal del documento.
            $pdf->Ln(5);
            // Se establece un color de relleno para mostrar el nombre del usuario.
            $pdf->SetFillColor(0,0,0);
            // Se establece la fuente para el usuario.
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->SetTextColor(255);
            // Se imprime una celda con el nombre de usuario.
            $pdf->Cell(0, 10, utf8_decode('Usuario: '.$rowCategoria['usuario']), 1, 1, 'C', 1);
            // Se establece el codigo de usuario para obtener sus acciones, de lo contrario se imprime un mensaje de error.
            if ($categoria->setId($rowCategoria['codigoadmin'])) {
                // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
                if ($dataProductos = $categoria->readAccionesParam($_SESSION['fechaInicio'],$_SESSION['fechaFin'])) {
                    // Se establece un color de relleno para los encabezados.
                    $pdf->SetFillColor(230,231,232);
                    // Se establece la fuente para los encabezados.
                    $pdf->SetFont('Helvetica', 'B', 11);
                    $pdf->SetTextColor(9,9,9);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->Cell(8, 10, utf8_decode('#'), 1, 0, 'C', 1);
                    $pdf->Cell(110, 10, utf8_decode('Acción realizada'), 1, 0, 'C', 1);
                    $pdf->Cell(68, 10, utf8_decode('Fecha de realización'), 1, 1, 'C', 1);
                    // Se establece la fuente para los datos de las acciones.
                    $pdf->SetFont('Helvetica', '', 11);
                    // Se recorren los registros fila por fila.
                    foreach ($dataProductos as $rowProducto) {
                        // Se van sumando el numero de registros para mostrarse en la tabla
                        $numero = $numero + 1;
                        $pdf->SetTextColor(9,9,9);
                        // Se imprimen las celdas con las acciones realizadas por el usuario.
                        $pdf->Cell(8, 10, ($numero), 1, 0);
                        $pdf->Cell(110, 10, utf8_decode($rowProducto['accion']), 1, 0);
                        $pdf->Cell(68, 10, utf8_decode($rowProducto['hora']), 1, 1);
                    }
                    // Se agrega un salto de línea para mostrar el contenido principal del documento.
                    $pdf->Ln(2);
                    $pdf->Cell(20);
                    $pdf->SetFont('Arial', 'B', 10);
                    // Imprimimos el numero total de acciones 
                    $pdf->Cell(280, 10, utf8_decode('Número total de acciones: ') .$numero, 0, 1, 'C');
                } else {
                    // Colocamos el color del texto a mostrar
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(0, 10, utf8_decode('El usuario no realizo ninguna acción en el rango de fechas seleccionado'), 1, 1);
                }
            } else {
                // Colocamos el color del texto a mostrar
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('Usuario incorrecto o inexistente'), 1, 1);
            }
        }
    } else {
        // Colocamos el color del texto a mostrar
        $pdf->SetTextColor(9,9,9);
        $pdf->Cell(0, 10, utf8_decode('No hay categorías para mostrar'), 1, 1);
    }
} else {
    // Redirigimos al formulario principal
    header('location: ../../../views/dashboard/usuarios.php');
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>