<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/estadoCuenta.php');

// Se compueba si existe una acción a realizar
if (isset($_GET['action'])) {
    //Se crea o se reanuda la sesión actual
    session_start();
    //Se instancia un objeto de la clase modelo
    $estadoCuenta = new EstadoCuenta;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como usuario para realizar las acciones correspondientes.
    if (isset($_SESSION['codigoadmin'])) {
        // Se evalua la acción a realizar
        // print($_GET['action']);
        switch ($_GET['action']) 
        {
            //Caso para mostrar los registros
            case 'readAll':
                if ($result['dataset'] = $estadoCuenta->SelectEstadoCuenta()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay registros ingresados';
                    }
                }
            break;
            //Caso para eliminar todos los registros de la base de datos
            case 'deleteAll':
                // Ejecutamos funcion para desactivar un usuario
                if ($estadoCuenta->deleteAll()) {
                    $result['status'] = 1;
                    // Mostramos mensaje de exito
                    $result['message'] = 'Datos eliminados correctamente'; 
                    // En caso de que alguna validacion falle se muestra el mensaje con el error 
                } else {
                    $result['exception'] = Database::getException();
                }        
            break;
            //Caso para insertar un registro
            case 'create':
                //Validamos los datos del formulario
                $_POST = $estadoCuenta->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if (isset($_POST['responsable'])) {
                    if ($estadoCuenta->setResponsable($_POST['responsable'])) {
                        if (isset($_POST['sociedad'])) {
                            if ($estadoCuenta->setSociedad($_POST['sociedad'])) {
                                if (isset($_POST['cliente'])) {
                                    if ($estadoCuenta->setCliente($_POST['cliente'])) {
                                        if ($estadoCuenta->setCodigo($_POST['codigo'])) {
                                            if ($estadoCuenta->setFactura($_POST['factura'])) {
                                                if ($estadoCuenta->setAsignacion($_POST['asignacion'])) {
                                                    if ($estadoCuenta->setFechaContable($_POST['fechacontable'])) {
                                                        if ($estadoCuenta->setClase($_POST['clase'])) {
                                                            if ($estadoCuenta->setVencimiento($_POST['vencimiento'])) {
                                                                if (isset($_POST['divisa'])) {
                                                                    if ($estadoCuenta->setDivisa($_POST['divisa'])) {
                                                                        if ($estadoCuenta->setTotal($_POST['total'])) {
                                                                            if ($estadoCuenta->insertEstado()) {
                                                                                $result['status'] = 1;
                                                                                // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                $result['message'] = 'Registro ingresado correctamente';
                                                                            } else {
                                                                                // En caso de ocurrir un error se muestra el mensaje con la excepcion
                                                                                if (Database::getException()) {
                                                                                    $result['exception'] = Database::getException();
                                                                                } else {
                                                                                    $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $result['exception'] = 'Total incorrecto';
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'Divisa incorrecta';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'Escoja una divisa';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Fecha de vencimiento incorrecta';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Clase incorrecta';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Fecha contable incorrecta';
                                                    }
                                                } else {
                                                    $result['exception'] = 'Asignación incorrecta';
                                                }
                                            } else {
                                                $result['exception'] = 'Factura incorrecta';
                                            }
                                        } else {
                                            $result['exception'] = 'Código incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Cliente incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Escoja un cliente';
                                }
                            } else {
                                $result['exception'] = 'Sociedad incorrecta';
                            }
                        } else {
                            $result['exception'] = 'Escoja una sociedad';
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
                $_POST = $estadoCuenta->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($estadoCuenta->setId($_POST['idestado'])) {
                    //Pasamos la información al modelo, mediante los setters
                    if (isset($_POST['responsable'])) {
                        if ($estadoCuenta->setResponsable($_POST['responsable'])) {
                            if (isset($_POST['sociedad'])) {
                                if ($estadoCuenta->setSociedad($_POST['sociedad'])) {
                                    if (isset($_POST['cliente'])) {
                                        if ($estadoCuenta->setCliente($_POST['cliente'])) {
                                            if ($estadoCuenta->setCodigo($_POST['codigo'])) {
                                                if ($estadoCuenta->setFactura($_POST['factura'])) {
                                                    if ($estadoCuenta->setAsignacion($_POST['asignacion'])) {
                                                        if ($estadoCuenta->setFechaContable($_POST['fechacontable'])) {
                                                            if ($estadoCuenta->setClase($_POST['clase'])) {
                                                                if ($estadoCuenta->setVencimiento($_POST['vencimiento'])) {
                                                                    if (isset($_POST['divisa'])) {
                                                                        if ($estadoCuenta->setDivisa($_POST['divisa'])) {
                                                                            if ($estadoCuenta->setTotal($_POST['total'])) {
                                                                                if ($estadoCuenta->updateEstado()) {
                                                                                    $result['status'] = 1;
                                                                                    // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                    $result['message'] = 'Registro actualizado correctamente';
                                                                                } else {
                                                                                    // En caso de corrir un error se muestra el mensaje con la excepcion
                                                                                    if (Database::getException()) {
                                                                                        $result['exception'] = Database::getException();
                                                                                    } else {
                                                                                        $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                $result['exception'] = 'Incompletos por calidad erróneos';
                                                                            }
                                                                        } else {
                                                                            $result['exception'] = 'Divisa incorrecta';
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'Escoja una divisa';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'No considerados incorrectos';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Clase incorrecta';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Fecha contable incorrecta';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Asignación incorrecta';
                                                    }
                                                } else {
                                                    $result['exception'] = 'Factura incorrecta';
                                                }
                                            } else {
                                                $result['exception'] = 'Código incorrecto';
                                            }
                                        } else {
                                            $result['exception'] = 'Cliente incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Escoja un cliente';
                                    }
                                } else {
                                    $result['exception'] = 'Sociedad incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Escoja una sociedad';
                            }
                        } else {
                            $result['exception'] = 'Responsable incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Escoja un responsable';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }

            break;
            //Caso para desactivar un registro
            case 'delete':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $estadoCuenta->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($estadoCuenta->setId($_POST['id'])) {
                    // Cargamos los datos del registro que se desea eliminar
                    if ($data = $estadoCuenta->SelectOneEstadoCuenta()) {
                        // Ejecutamos funcion para desactivar un usuario
                        if ($estadoCuenta->desableEstado()){
                            $result['status'] = 1;
                            // Mostramos mensaje de exito
                            $result['message'] = 'Registro desactivado correctamente';
                            // En caso de que alguna validacion falle se muestra el mensaje con el error 
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Registro inexistente';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
            break;
            //Caso para activar un registro
            case 'activate':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $estadoCuenta->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($estadoCuenta->setId($_POST['id'])) {
                    // Cargamos los datos del registro que se desea eliminar
                    if ($data = $estadoCuenta->SelectOneEstadoCuenta()) {
                        // Ejecutamos funcion para desactivar un usuario
                        if ($estadoCuenta->enableEstado()) {
                            $result['status'] = 1;
                            // Mostramos mensaje de exito
                            $result['message'] = 'Registro activado correctamente';
                            // En caso de que alguna validacion falle se muestra el mensaje con el error 
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Registro inexistente';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
            break;
            //Caso para realizar una busqueda de registros
            case 'search':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $estadoCuenta->validateForm($_POST);
                // Validamos si el input no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion para la busqueda filtrada enviando el contenido del input como parametro
                    if ($result['dataset'] = $estadoCuenta->searchEstado($_POST['search'])) {
                        $result['status'] = 1;
                        // Obtenemos la cantidad de resultados retornados por la consulta
                        $rows = count($result['dataset']);
                        // Verificamos si la cantidad de resultados es mayor a uno asi varia el mensaje a mostrar
                        if ($rows > 1){
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
                if ($estadoCuenta->setId($_POST['id'])) {
                    // Se ejecuta la funcion para leer los datos de un registro
                    if ($result['dataset'] = $estadoCuenta->SelectOneEstadoCuenta()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Registro inexistente'; // Se muestra en caso de no encontrar registro con ese id
                        }
                    }
                } else {
                    $result['exception'] = 'Registro incorrecto';
                }
            break;
            //Caso para obtener la sumatoria de del total general mensual
            case 'totalGeneralMensual':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $estadoCuenta->validateForm($_POST);    
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $estadoCuenta->getTotalMensualCliente($_POST['cliente'])) {
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
