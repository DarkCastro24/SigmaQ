<?php
require('../../helpers/report.php');
require('../../models/usuarios.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de usuarios agrupado por tipo');
// Se instancia el módelo Usuario para obtener los datos.
$categoria = new Usuario;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataCategorias = $categoria->readTipo()) {
    // Se recorren los registros fila por fila.
    foreach ($dataCategorias as $rowCategoria) {
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Creamos una variable para almacenar el encabezado de la celda
        $tipo = '';
        // Compramamos el valor del tipo de usuario
        if ($rowCategoria['tipo'] == 1) {
            // Si el usuario es uno es usuario root
            $tipo = 'Root';
        } else {
            // Si el usuario es un numero diferente de uno es admin
            $tipo = 'Administradores';
        }
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(0, 10, utf8_decode($tipo), 1, 1, 'C', 1);
        // Se establece el tipo para obtener los usuarios pertenezcan a el, de lo contrario se imprime un mensaje de error.
        if ($categoria->setTipo($rowCategoria['tipo'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $categoria->readUsuariosTipo()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(230,231,232);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(20, 10, utf8_decode('Código'), 1, 0, 'C', 1);
                $pdf->Cell(35, 10, utf8_decode('Nombre'), 1, 0, 'C', 1);
                $pdf->Cell(35, 10, utf8_decode('Apellido'), 1, 0, 'C', 1);
                $pdf->Cell(41, 10, utf8_decode('Usuario'), 1, 0, 'C', 1);
                $pdf->Cell(30, 10, utf8_decode('Teléfono'), 1, 0, 'C', 1);
                $pdf->Cell(25, 10, utf8_decode('Estado'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros fila por fila.
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los usuarios.
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(20, 10, utf8_decode($rowProducto['codigoadmin']), 1, 0);
                    $pdf->Cell(35, 10, utf8_decode($rowProducto['nombre']), 1, 0);
                    $pdf->Cell(35, 10, utf8_decode($rowProducto['apellido']), 1, 0);
                    $pdf->Cell(41, 10, utf8_decode($rowProducto['usuario']), 1, 0);
                    $pdf->Cell(30, 10, utf8_decode($rowProducto['telefono']), 1, 0);
                    // Se compara el valor del estado para determinar si el usuario esta activo o inactivo
                    if ($rowProducto['estado'] == '1') {
                        // Usuario activo
                        $pdf->Cell(25, 10, 'Activo', 1, 1);
                    } else {
                        // Usuario inactivo
                        $pdf->Cell(25, 10, 'Inactivo', 1, 1);
                    }   
                }
                // Comparamos el valor del tipo de usuario
                if ($rowCategoria['tipo'] == 1) {
                    // Si el tipo es 1 los usuarios son root
                    $tipo = 'root';
                } else {
                    // Comparamos si existe uno o mas registros dentro del tipo de usuario
                    if ($rowCategoria['cantidad'] == 1) {
                        // En caso de existir un solo registro se coloca el nombre del tipo de usuario en singular
                        $tipo = 'admin';
                    } else {
                        // En caso de existir mas de un registro se coloca el nombre del tipo de usuario en plural
                        $tipo = 'admins';
                    }                    
                }
                // Se agrega un espacio para separar el texto de la tabla.
                $pdf->Ln(2);
                $pdf->Cell(20);
                $pdf->SetFont('Arial', 'B', 10);
                // Imprimimos el pie de la celda colocando la cantidad de usuarios que existen por tipo
                $pdf->Cell(283, 10, 'Existen ' .$rowCategoria['cantidad'].' usuarios '.$tipo, 0, 1, 'C');
                $pdf->Ln(2);
            } else {
                // Colocamos el color del texto a mostrar
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('No hay usuarios en este tipo'), 1, 1);
            }
        } else {
            // Colocamos el color del texto a mostrar
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Categoría incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    // Colocamos el color del texto a mostrar
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay categorías para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>