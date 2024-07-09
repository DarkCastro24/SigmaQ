<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/historial.php');

// Se comprueba si el nombre de la acción a realizar coincide con alguno de los casos, de lo contrario mostrara un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión que se llenaron en el login.
    session_start();
    // Se instancia la clase del modelo correspondiente.
    $historial = new Historial;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se compara la acción a realizar cuando un usuario ha iniciado sesión.
    switch ($_GET['action']) 
    {
        case 'readAll':  // Caso para cargar los datos todos los datos en la tabla
            // Ejecutamos metodo del modelo y asignamos el valor de su retorno a la variable dataset 
            if ($result['dataset'] = $historial->readAll()) { 
                $result['status'] = 1;
            } 
            else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay clientes registrados';  
                }
            }
        break;
        // Caso para insertar un registro
        case 'create':
            // Pasamos la información al modelo, mediante los setters
            if ($historial->insertHistorial($_POST['accion'])) {
                $result['status'] = 1;
                // Se muestra un mensaje de exito en caso de registrarse correctamente
                $result['message'] = 'Sisisisisi';
            } else {
                if (Database::getException()) {
                        $result['exception'] = Database::getException();
                } else {
                        $result['exception'] = 'Ocurrió un problema al insertar el registro';
                }
            } 
        break;
        case 'search':  // Caso para realizar la busqueda filtrada 
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $historial->validateForm($_POST);
            // Validamos si el input no esta vacio
            if ($_POST['search'] != '') {
                // Ejecutamos la funcion para la busqueda filtrada enviando el contenido del input como parametro
                if ($result['dataset'] = $historial->searchHistorial($_POST['search'])) {
                    $result['status'] = 1;
                    // Obtenemos la cantidad de resultados retornados por la consulta
                    $rows = count($result['dataset']);
                    // Verificamos si la cantidad de resultados es mayor a uno asi varia el mensaje a mostrar
                    if ($rows > 1) {
                        // Mostramos un mensaje con la cantidad de coincidencias encontradas
                        $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                    } else {
                        // Mostramos un mensaje donde solo hubo una sola coincidencia
                        $result['message'] = 'Solo existe una coincidencia';
                    }
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        // En caso de no encontrar registros se muestra el siguiente mensaje
                        $result['exception'] = 'No hay coincidencias'; 
                    }
                }
            } else {
                $result['exception'] = 'Ingrese un valor para buscar';
            }
        break;
        default:
            // En caso de que el caso ingresado no sea ninguno de los anteriores se muestra el siguiente mensaje 
            $result['exception'] = 'Acción no disponible dentro de la sesión';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    // En caso que no exista ninguna accion al hacer la peticion se muestra el siguiente mensaje
    print(json_encode('Recurso no disponible'));
}