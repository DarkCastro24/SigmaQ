<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/sociedades.php');

// Se compueba si existe una acción a realizar
if (isset($_GET['action'])) {
    //Se crea o se reanuda la sesión actual
    session_start();
    //Se instancia un objeto de la clase modelo
    $sociedades = new sociedades;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como usuario para realizar las acciones correspondientes.
    if (isset($_SESSION['codigoadmin'])) {
        // Se evalua la acción a realizar
        switch ($_GET['action']) 
        {
            //Caso para mostrar los el índice de entrega
            case 'readAll':
                if ($result['dataset'] = $sociedades->SelectSociedades()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay sociedades registradas';
                    }
                }
            break;
            //Caso para insertar un registro
            case 'create':
                //Validamos los datos del formulario
                $_POST = $sociedades->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if (isset($_POST['cliente'])) {
                    if ($sociedades->setCliente($_POST['cliente'])) {
                        if ($sociedades->setSociedad($_POST['sociedad'])) {
                            if ($sociedades->insertSociedad()) {
                                $result['status'] = 1;
                                // Se muestra un mensaje de exito en caso de registrarse correctamente
                                $result['message'] = 'Sociedad registrada correctamente';
                            } else {
                                if (Database::getException()) {
                                    $result['exception'] = Database::getException();
                                } else {
                                    $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                }
                            }
                        } else {
                            $result['exception'] = 'Sociedad incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Cliente incorrecto';
                    }
                } else {
                    $result['exception'] = 'Escoja un cliente';
                }
            break;
            //Caso para modificar un registro
            case 'update':
                //Validamos los datos del formulario
                $_POST = $sociedades->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if (isset($_POST['cliente'])) {
                    if ($sociedades->setCliente($_POST['cliente'])) {
                        if ($sociedades->setSociedad($_POST['sociedad'])) {
                            if ($sociedades->updateSociedad()){
                                $result['status'] = 1;
                                // Se muestra un mensaje de exito en caso de registrarse correctamente
                                $result['message'] = 'Sociedad actualizada correctamente';
                            } else {
                                if (Database::getException()){
                                    $result['exception'] = Database::getException();
                                } else {
                                    $result['exception'] = 'Ocurrió un problema al actualizar el registro';
                                }
                            }
                        } else {
                            $result['exception'] = 'Sociedad incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Cliente incorrecto';
                    }
                } else {
                    $result['exception'] = 'Escoja un cliente';
                }
            break;
            //Caso para desactivar un registro
            case 'delete': 
                if($sociedades->setIdSociedad($_POST['idsociedad'])) {
                    if($data = $sociedades->SelectOneSociedades()) {
                        if($sociedades->deleteSociedad()) {
                            $result['status'] = 1;
                            $result['message'] = 'Sociedad eliminada correctamente';
                        } else {
                            $result['message'] = Database::getException(); 
                        }
                    } else {
                        $result['message'] = 'Sociedad inexistente';
                    }
                } else {
                    $result['message'] = 'Sociedad incorrecta';
                }
            break;
            // Caso para leer los datos de un solo registro parametrizado mediante el identificador
            case 'readOne':
                if ($sociedades->setIdSociedad($_POST['idsociedad'])) {
                    // Se ejecuta la funcion para leer los datos de un registro
                    if ($result['dataset'] = $sociedades->SelectOneSociedades()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Sociedad inexistente'; // Se muestra en caso de no encontrar registro con ese id
                        }
                    }
                } else {
                    $result['exception'] = 'Sociedad incorrecta';
                }
            default:
                $result['exception'] = 'Acción no reconocida';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
}
