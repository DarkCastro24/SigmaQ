<?php
require('../../helpers/database.php');
require('../../helpers/validator.php');
require('../../../libraries/fpdf182/fpdf.php');

/**
*   Clase para definir las plantillas de los reportes del sitio privado. Para más información http://www.fpdf.org/
*/
class Report extends FPDF
{
    // Propiedad para guardar el título del reporte.
    private $title = null;
    // Propiedad para guardar el numero de paginas del reporte
    private $pagina = null;

    /*
    *   Método para iniciar el reporte con el encabezado del documento.
    *
    *   Parámetros: $title (título del reporte).
    *
    *   Retorno: ninguno.
    */
    public function startReport($title)
    {
        // Se establece la zona horaria a utilizar durante la ejecución del reporte.
        ini_set('date.timezone', 'America/El_Salvador');
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en los reportes.
        session_start();
        // Se verifica si un administrador ha iniciado sesión para generar el documento, de lo contrario se direcciona a main.php
        if (isset($_SESSION['nombre'])) {
            // Se asigna el título del documento a la propiedad de la clase.
            $this->title = $title;
            // Se establece el título del documento (true = utf-8).
            $this->SetTitle($title, true);
            // Se establecen los margenes del documento (izquierdo, superior y derecho).
            $this->setMargins(15, 15, 15);
            // Se añade una nueva página al documento (orientación vertical y formato carta) y se llama al método Header()
            $this->AddPage('p', 'letter');
            // Se define un alias para el número total de páginas que se muestra en el pie del documento.
            $this->AliasNbPages();
            // Se obtiene el numero de paginas para mostrar o no la cabecera
            $this->pagina = $this->pagina + 1;
        } else {
            header('location: ../../../views/dashboard/index.php');
        }
    }

    public function startReportL($title)
    {
        // Se establece la zona horaria a utilizar durante la ejecución del reporte.
        ini_set('date.timezone', 'America/El_Salvador');
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en los reportes.
        session_start();
        // Se verifica si un administrador ha iniciado sesión para generar el documento, de lo contrario se direcciona a main.php
        if (isset($_SESSION['nombre'])) {
            // Se asigna el título del documento a la propiedad de la clase.
            $this->title = $title;
            // Se establece el título del documento (true = utf-8).
            $this->SetTitle($title, true);
            // Se establecen los margenes del documento (izquierdo, superior y derecho).
            $this->setMargins(15, 15, 15);
            // Se añade una nueva página al documento (orientación vertical y formato carta) y se llama al método Header()
            $this->AddPageL('l', 'letter');
            // Se define un alias para el número total de páginas que se muestra en el pie del documento.
            $this->AliasNbPages();
            // Se obtiene el numero de paginas para mostrar o no la cabecera
            $this->pagina = $this->pagina + 1;
        } else {
            header('location: ../../../views/dashboard/index.php');
        }
    }

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del encabezado de los reportes.
    *   Se llama automáticamente en el método AddPage()
    */
    public function Header()
    {
        if ($this->pagina != 1) {
            // Se establece el logo.
            $this->Image('../../../resources/img/brand/logoRojo.png', 15, 15, 38);
            // Se ubica el título.
            $this->Cell(20);
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(180, 12, utf8_decode($this->title), 0, 1, 'C');
            // Se ubica la fecha y hora del servidor.
            $this->Cell(20);
            $this->SetFont('Arial', '', 12);
            $this->Cell(180, 10, utf8_decode('Fecha y hora de generación: ') .date('d-m-Y H:i:s'), 0, 1, 'C');
            // Se setea el usuario de la sesión en el reporte.
            $this->Cell(20);
            $this->SetFont('Arial', '', 12);
            $this->Cell(180, 10, ('Usuario que solicita el reporte: '. $_SESSION['usuario']), 0, 1, 'C');
            // Se agrega un salto de línea para mostrar el contenido principal del documento.
            $this->Ln(10);
        }       
    }

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del encabezado de los reportes horizontales.
    *   Se llama automáticamente en el método AddPageL()
    */
    public function HeaderL()
    {
        if ($this->pagina != 1) {
            // Se establece el logo.
            $this->Image('../../../resources/img/brand/logoRojo.png', 30, 22, 38);
            // Se ubica el título.
            $this->Cell(20);
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(225, 12, utf8_decode($this->title), 0, 1, 'C');
            // Se ubica la fecha y hora del servidor.
            $this->Cell(20);
            $this->SetFont('Arial', '', 12);
            $this->Cell(225, 10, utf8_decode('Fecha y hora de generación: ') .date('d-m-Y H:i:s'), 0, 1, 'C');
            // Se setea el usuario de la sesión en el reporte.
            $this->Cell(20);
            $this->SetFont('Arial', '', 12);
            $this->Cell(225, 10, ('Usuario que solicita el reporte: '. $_SESSION['usuario']), 0, 1, 'C');
            // Se agrega un salto de línea para mostrar el contenido principal del documento.
            $this->Ln(10);
        }       
    }

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del pie de los reportes.
    *   Se llama automáticamente en el método Output()
    */
    public function Footer()
    {
        // Se establece la posición para el número de página (a 15 milimetros del final).
        $this->SetY(-15);
        // Se establece la fuente para el número de página.
        $this->SetFont('Arial', 'I', 8);
        // Se imprime una celda con el número de página.
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}
?>