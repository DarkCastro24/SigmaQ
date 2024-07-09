<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/indiceEntrega.php');

// Se compueba si existe una acción a realizar
if (isset($_GET['action'])) {
    //Se crea o se reanuda la sesión actual
    session_start();
    //Se instancia un objeto de la clase modelo
    $indice = new Indice;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como usuario para realizar las acciones correspondientes.
    if (isset($_SESSION['codigoadmin'])) { 
        // Se evalua la acción a realizar
        switch($_GET['action']) 
        {
            //Caso para mostrar los el índice de entrega
            case 'readAll':
                if($result['dataset'] = $indice->selectIndice()) {
                    $result['status'] = 1;
                } else {
                    if(Database::getException()) {
                        $result['exception'] = Database::getException(); 
                    } else {
                        $result['exception'] = 'No hay índices registradas';
                    }
                }
            break;
            //Caso para insertar un registro
            case 'create':
                //Validamos los datos del formulario
                $_POST = $indice->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if(isset($_POST['responsable'])) {
                    if($indice->setResponsable($_POST['responsable'])) {
                        if(isset($_POST['cliente'])) {
                            if($indice->setCliente($_POST['cliente'])) {
                                if($indice->setOrganizacion($_POST['organizacion'])) {
                                    if($indice->setIndice($_POST['indice'])) {
                                        if($indice->setTotalCompromiso($_POST['totalcompromiso'])) {
                                            if($indice->setCumplidos($_POST['cumplidos'])) {
                                                if($indice->setNoCumplidos($_POST['nocumplidos'])) {
                                                    if($indice->setNoConsiderados($_POST['noconsiderados'])) {
                                                        if($indice->setIncumNoEntregados($_POST['incumnoentregados'])) {
                                                            if($indice->setIncumPorCalidad($_POST['incumporcalidad'])) {
                                                                if($indice->setIncumPorFecha($_POST['incumporfecha'])) {
                                                                    if($indice->setIncumPorCantidad($_POST['incumporcantidad'])) {
                                                                        if($indice->insertIndice()) {
                                                                            $result['status'] = 1;
                                                                            // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                            $result['message'] = 'Índice registrado correctamente';
                                                                        } else {
                                                                            if(Database::getException()) {
                                                                                $result['exception'] = Database::getException();
                                                                            } else {
                                                                                $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'Incumpletos por cantidad erróneos';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'Incompletos por fecha erróneos';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Incompletos por calidad erróneos';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Incompletos no entregados erróneos';
                                                        }
                                                    } else{
                                                        $result['exception'] = 'No considerados incorrectos';
                                                    }
                                                } else{
                                                    $result['exception'] = 'No cumplidos incorrectos';
                                                }
                                            } else {
                                                $result['exception'] = 'Cumplidos incorrectos';
                                            }
                                        } else {
                                            $result['exception'] = 'Compromisos totales incorrectos';
                                        }
                                    } else {
                                        $result['exception'] = 'Índice incorrecto';    
                                    }
                                } else {
                                    $result['exception'] = 'Organización incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Cliente incorrecto';    
                            }
                        } else {
                            $result['exception'] = 'Escoja un cliente';
                        }
                    } else {
                        $result['exception'] = 'Responsable incorrecto';
                    }
                } else {
                    $result['exception'] = 'Escoja un responsable';
                }
            break;
            //Caso para modificar un registro
            case 'update':
                //Validamos los datos del formulario
                $_POST = $indice->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if($indice->setIdIndice($_POST['idindice'])) {
                    if(isset($_POST['responsable'])) {
                        if($indice->setResponsable($_POST['responsable'])) {
                            if(isset($_POST['cliente'])) {
                                if($indice->setCliente($_POST['cliente'])) {
                                    if($indice->setOrganizacion($_POST['organizacion'])) {
                                        if($indice->setIndice($_POST['indice'])) {
                                            if($indice->setTotalCompromiso($_POST['totalcompromiso'])) {
                                                if($indice->setCumplidos($_POST['cumplidos'])) {
                                                    if($indice->setNoCumplidos($_POST['nocumplidos'])) {
                                                        if($indice->setNoConsiderados($_POST['noconsiderados'])) {
                                                            if($indice->setIncumNoEntregados($_POST['incumnoentregados'])) {
                                                                if($indice->setIncumPorCalidad($_POST['incumporcalidad'])) {
                                                                    if($indice->setIncumPorFecha($_POST['incumporfecha'])) {
                                                                        if($indice->setIncumPorCantidad($_POST['incumporcantidad'])) {
                                                                            if($indice->updateIndice()) {
                                                                                $result['status'] = 1;
                                                                                // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                $result['message'] = 'Índice modificado correctamente';
                                                                            } else {
                                                                                if(Database::getException()){
                                                                                    $result['exception'] = Database::getException();
                                                                                } else {
                                                                                    $result['exception'] = 'Ocurrió un problema al modificar el registro';
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $result['exception'] = 'Incumpletos por cantidad erróneos';
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'Incompletos por fecha erróneos';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'Incompletos por calidad erróneos';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Incompletos no entregados erróneos';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'No considerados incorrectos';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'No cumplidos incorrectos';
                                                    }
                                                } else {
                                                    $result['exception'] = 'Cumplidos incorrectos';
                                                }
                                            } else {
                                                $result['exception'] = 'Compromisos totales incorrectos';
                                            }
                                        } else {
                                            $result['exception'] = 'Índice incorrecto';    
                                        }
                                    } else {
                                        $result['exception'] = 'Organización incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'Cliente incorrecto';    
                                }
                            } else {
                                $result['exception'] = 'Escoja un cliente';
                            }
                        } else {
                            $result['exception'] = 'Responsable incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Escoja un responsable';
                    }
                } else {
                    $result['exception'] = 'ID del índice incorrecto';
                }
            break;
            //Caso para desactivar un registro
            case 'delete':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $indice->validateForm($_POST);      
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($indice->setIdIndice($_POST['id'])) {
                    // Cargamos los datos del registro que se desea eliminar
                    if ($data = $indice->readOneIndice()) {
                        // Ejecutamos funcion para desactivar un usuario
                        if ($indice->desableIndice()) {
                            $result['status'] = 1;
                            // Mostramos mensaje de exito
                            $result['message'] = 'Índice desactivado correctamente'; 
                        // En caso de que alguna validacion falle se muestra el mensaje con el error 
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Índice inexistente';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
            break;
            //Caso para eliminar todos los registros de la base de datos
            case 'deleteAll':
                // Ejecutamos funcion para desactivar un usuario
                if ($indice->deleteAll()) {
                    $result['status'] = 1;
                    // Mostramos mensaje de exito
                    $result['message'] = 'Datos eliminados correctamente'; 
                    // En caso de que alguna validacion falle se muestra el mensaje con el error 
                } else {
                    $result['exception'] = Database::getException();
                }        
            break;
            //Caso para activar un registro
            case 'activate':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $indice->validateForm($_POST);      
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($indice->setIdIndice($_POST['id'])) {
                    // Cargamos los datos del registro que se desea eliminar
                    if ($data = $indice->readOneIndice()) {
                        // Ejecutamos funcion para desactivar un usuario
                        if ($indice->enableIndice()) {
                            $result['status'] = 1;
                            // Mostramos mensaje de exito
                            $result['message'] = 'Índice activado correctamente'; 
                        // En caso de que alguna validacion falle se muestra el mensaje con el error 
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Índice inexistente';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
            break;
            //Caso para realizar una busqueda de registros
            case 'search': 
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $indice->validateForm($_POST);
                // Validamos si el input no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion para la busqueda filtrada enviando el contenido del input como parametro
                    if ($result['dataset'] = $indice->searchRows($_POST['search'])) {
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
            // Caso para leer los datos de un solo registro parametrizado mediante el identificador
            case 'readOne': 
                if ($indice->setIdIndice($_POST['id'])) {
                    // Se ejecuta la funcion para leer los datos de un registro
                    if ($result['dataset'] = $indice->readOneIndice()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Índice inexistente'; // Se muestra en caso de no encontrar registro con ese id
                        }
                    }
                } else {
                    $result['exception'] = 'Índice incorrecto';
                }
            break;
            //Caso para obtener el porcentaje de cumplimiento de un indice
            case 'porcentajeCumplimientoIndice':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $indice->validateForm($_POST);    
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $indice->porcentajeCumplimientoIndice($_POST['id_indice'])) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos disponibles';
                    }
                }
            break;
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
?>