<?php
require('../../helpers/report.php');
require('../../models/clientes.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de clientes agrupado por estado');
// Se instancia el módelo Cliente para obtener los datos.
$categoria = new Cliente;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataCategorias = $categoria->readEstado()) {
    // Se recorren los registros fila por fila.
    foreach ($dataCategorias as $rowCategoria) {
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el estado del cliente.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Creamos una variable para almacenar el encabezado de la celda
        $tipo = '';
        $state = '';
        // Compramamos el valor del estado de cliente
        if ($rowCategoria['estado'] == 'Activo') {
            // Si el estado es true es un cliente activo
            $tipo = 'Usuarios activos';
            $state = 1;
        } else {
            // Si no esta inactivo
            $tipo = 'Usuarios inactivos';   
            $state = 2;                                                                                                            
        }
        // Se imprime una celda con el nombre del estado.
        $pdf->Cell(0, 10, utf8_decode($tipo), 1, 1, 'C', 1);
        // Se establece el estado para obtener sus clientes, de lo contrario se imprime un mensaje de error.
        if ($categoria->setEstado($state)) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $categoria->readUsuariosEstado()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(230,231,232);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(20, 10, utf8_decode('Código'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Empresa'), 1, 0, 'C', 1);
                $pdf->Cell(40, 10, utf8_decode('Usuario'), 1, 0, 'C', 1);
                $pdf->Cell(36, 10, utf8_decode('Teléfono'), 1, 0, 'C', 1);
                $pdf->Cell(50, 10, utf8_decode('Correo'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los clientes.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros fila por fila.
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los clientes.
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(20, 10, utf8_decode($rowProducto['codigocliente']), 1, 0);
                    $pdf->Cell(40, 10, utf8_decode($rowProducto['empresa']), 1, 0);
                    $pdf->Cell(40, 10, utf8_decode($rowProducto['usuario']), 1, 0);
                    $pdf->Cell(36, 10, utf8_decode($rowProducto['telefono']), 1, 0);
                    $pdf->Cell(50, 10, utf8_decode($rowProducto['correo']), 1, 1);
                }
                // Comparamos el valor del tipo de usuario
                if ($rowCategoria['estado'] == 'Activo') {
                    // Comparamos si existe uno o mas registros dentro del estado
                    if ($rowCategoria['cantidad'] == 1) {
                        // En caso de existir un solo registro se coloca el nombre del estado en singular
                        $tipo = 'activo';
                    } else {
                        // En caso de existir mas de un registro se coloca el nombre del estado en plural
                        $tipo = 'activos';
                    } 
                } else {
                    // Comparamos si existe uno o mas registros dentro del estado
                    if ($rowCategoria['cantidad'] == 1) {
                        // En caso de existir un solo registro se coloca el nombre del estado en singular
                        $tipo = 'inactivo';
                    } else {
                        // En caso de existir mas de un registro se coloca el nombre del estado en plural
                        $tipo = 'inactivos';
                    }                    
                }
                // Se agrega un salto de línea para mostrar el contenido principal del documento.
                $pdf->Ln(2);
                $pdf->Cell(20);
                $pdf->SetFont('Arial', 'B', 10);
                // Imprimimos el pie de la celda colocando la cantidad de usuarios que existen por tipo
                $pdf->Cell(283, 10, 'Existen ' .$rowCategoria['cantidad'].' usuarios '.$tipo, 0, 1, 'C');
                $pdf->Ln(2);
            } else {
                // Colocamos el color del texto a mostrar
                $pdf->SetTextColor(9,9,9);
                // Imprimimos el mensaje que no se encontraron clientes en ese estado
                $pdf->Cell(0, 10, utf8_decode('No hay clientes con este estado'), 1, 1);
            }
        } else {
            // Colocamos el color del texto a mostrar
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Estado incorrecto o inexistente'), 1, 1);
        }
    }
} else {
    // Colocamos el color del texto a mostrar
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay clientes para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>