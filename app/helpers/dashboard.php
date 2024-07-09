<?php
//Clase para definir las plantillas de las páginas web del sitio privado
class Dashboard_Page
{
    //Método para imprimir el encabezado y establecer el titulo del documento
    public static function headerTemplate($title, $css)
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
        session_start();
        // Se imprime el código HTML de la cabecera del documento.
        print('
        <!DOCTYPE html>
        <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $title . '</title>
                <link rel="stylesheet" href="../../resources/css/bootstrap.min.css">
                <link rel="stylesheet" href="../../resources/css/' . $css . '.css">
                <link rel="stylesheet" href="../../resources/css/public.css">
                <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">    
                <link rel="shortcut icon" href="../../resources/img/brand/qRoja.png" type="image/x-icon">
            </head>
        <body>
        ');
        // Obtenemos el nombre de la pagina en la cual nos encontramos
        $filename = basename($_SERVER['PHP_SELF']);
        // Validamos si existe la variable de sesion usuario (Inicio sesion en el login)
        if (isset($_SESSION['nombre'])) {
            // Verificamos si el usuario no se encuentra en las siguientes urls
            if ($filename != 'index.php' && $filename != 'register.php') {
                // Verificamos si el usuario autentico su usuario mediante la variable de sesion validador 
                if (isset($_SESSION['nombre'])) {
                    if ($_SESSION['tipo'] == 'Root') {
                        // Imprimimos el codigo dentro del formulario
                        print('
                        <input id="tipoUsuario" name="tipoUsuario" type="hidden" value="' . $_SESSION['tipo'] . '">
                        <nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="navbar--header">
                            <a class="navbar-brand" href="main.php">
                                <img class="nav--logo" src="../../resources/img/brand/logoBlanco.png" alt="">
                            </a>
                            <div class="usuario--contenedor">
                                <img src="../../resources/img/icons/usuario.png" alt="" class="nav--user__icon">
                                <div class="usuario--opciones">
                                    <a onclick="logOut();" class="usuario--contenedor__enlace">Cerrar Sesión</a>
                                </div>
                            </div>
                        </nav>  
                        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" id="navbar--options">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                                MENÚ
                            </button>
                            <div class="collapse navbar-collapse" id="navbarColor02">
                                <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="main.php">Inicio
                                    <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="profile.php">Configuración personal</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="usuarios.php">Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="clientes.php">Clientes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="indice.php">Índice de entrega</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="estado.php">Estados de cuenta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="pedidos.php">Status de pedidos</a>
                                </li>
                                
                            </div>
                        </nav>
                        ');
                    } else {
                        if ($filename != 'usuarios.php' && $filename != 'clientes.php') {
                            // Imprimimos el codigo dentro del formulario
                            print('
                            <input id="tipoUsuario" name="tipoUsuario" type="hidden" value="' . $_SESSION['tipo'] . '">
                            <nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="navbar--header">
                                <a class="navbar-brand" href="main.php">
                                    <img class="nav--logo" src="../../resources/img/brand/logoBlanco.png" alt="">
                                </a>
                                <div class="usuario--contenedor">
                                    <img src="../../resources/img/icons/usuario.png" alt="" class="nav--user__icon">
                                    <div class="usuario--opciones">
                                        <a onclick="logOut();" class="usuario--contenedor__enlace">Cerrar Sesión</a>
                                    </div>
                                </div>
                            </nav>  
                            <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" id="navbar--options">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                    MENÚ
                                </button>
                                <div class="collapse navbar-collapse" id="navbarColor02">
                                    <ul class="navbar-nav mr-auto">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="main.php">Inicio
                                        <span class="sr-only">(current)</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="profile.php">Configuración personal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="indice.php">Índice de entrega</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="estado.php">Estados de cuenta</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="pedidos.php">Status de pedidos</a>
                                    </li>
                                    
                                </div>
                            </nav>
                            ');
                        } else {
                            // Redirigimos al usuario
                            header('location: main.php');
                        } 
                    }
                } else {
                    // Redirigimos al usuario
                    header('location: index.php');
                }
            } else {
                // Redirigimos al usuario
                header('location: main.php');
            }
        } else {
            // Redirigimos al usuario
            header('location: index.php');
        }
    }

    //Método para imprimir el pie y establecer el controlador del documento
    public static function footerTemplate($controller)
    {
        // Imprimimos el codigo dentro del formulario
        print('
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
            <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
            <script type="text/javascript" src="../../app/controllers/paginacion.js"></script>
            <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
            <script type="text/javascript" src="../../resources/js/chart.js"></script>
            <script type="text/javascript" src="../../app/helpers/components.js"></script>
            <script type="text/javascript" src="../../app/controllers/dashboard/account.js"></script>
            <script type="text/javascript" src="../../app/controllers/dashboard/logout.js"></script>
            <script type="text/javascript" src="../../app/controllers/dashboard/' . $controller . '.js"></script> <!-- Direccion del archivo Javascript de la pagina correspondiente -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- LINKS PARA LA LIBRERÍA DE LA TABLA -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <!-- LINK PARA EL LIVE SEARCH -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        </body>
        </html>
        ');
    }
}
