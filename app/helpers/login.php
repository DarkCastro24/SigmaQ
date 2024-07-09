<?php
//Clase para definir las plantillas de las páginas web del login
class Login_Page
{
    //Método para imprimir el encabezado y establecer el titulo del login del dashboard
    public static function headerTemplateDashboard($title) 
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
        session_start();
        // Se imprime el código HTML de la cabecera del documento.
            print('
            <!DOCTYPE html>
            <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>'.$title.'</title>
                    <link rel="stylesheet" href="../../resources/css/bootstrap.min.css">
                    <link rel="stylesheet" href="../../resources/css/login.css">
                    <link rel="shortcut icon" href="../../resources/img/brand/qRoja.png" type="image/x-icon">
                </head>
            <body>
            ');
    }

    //Método para imprimir el encabezado y establecer el titulo del login del dashboard
    public static function headerTemplatePublic($title) 
    {
        print('
            <!DOCTYPE html>
            <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>'.$title.'</title>
                    <link rel="stylesheet" href="../../resources/css/bootstrap.min.css">
                    <link rel="stylesheet" href="../../resources/css/login.css">
                    <link rel="shortcut icon" href="../../resources/img/brand/qRoja.png" type="image/x-icon">
                </head>
            <body>
        ');  
    }

    //Método para imprimir el pie y establecer el controlador del documento
    public static function footerTemplate($controller) 
    {
        print('
                <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                <script type="text/javascript" src="../../app/helpers/components.js"></script>
                <script type="text/javascript" src="../../app/controllers/'.$controller.'"></script>
            </body>
        </html>
        ');
    }
}
