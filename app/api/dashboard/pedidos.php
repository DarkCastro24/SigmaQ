<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/pedidos.php');

// Se compueba si existe una acción a realizar
if (isset($_GET['action'])) {
    //Se crea o se reanuda la sesión actual
    session_start();
    //Se instancia un objeto de la clase modelo
    $pedido = new Pedidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como usuario para realizar las acciones correspondientes.
    if (isset($_SESSION['codigoadmin'])) { 
        // Se evalua la acción a realizar
        switch($_GET['action']) 
        {
            //Caso para insertar un pedidos
            case 'create':
                //Validamos los datos del formulario
                $_POST = $pedido->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if(isset($_POST['responsable'])) {
                    if($pedido->setResponsable($_POST['responsable'])) {
                        if(isset($_POST['cliente'])) {
                            if($pedido->setCliente($_POST['cliente'])) {
                                if($pedido->setPos($_POST['pos'])) {
                                    if($pedido->setOc($_POST['oc'])) {
                                        if($pedido->setCantidadSolicitada($_POST['cantidadsolicitada'])) {
                                            if($pedido->setDescripcion($_POST['descripcion'])) {
                                                if($pedido->setCantidadEnviada($_POST['cantidadenviada'])) {
                                                    if($_POST['fechaentrega'] != '') {
                                                        if($pedido->setFechaEntrega($_POST['fechaentrega'])) {
                                                            if($_POST['fechaconfirmadaenvio'] != '') {
                                                                if($pedido->setFechaConfirmadaEnvio($_POST['fechaconfirmadaenvio'])) {
                                                                    if($pedido->setCodigo($_POST['codigo'])) {
                                                                        if($pedido->checkCode()) {
                                                                            $result['exception'] = 'El código no se puede repetir';
                                                                        } else {
                                                                            if($_POST['comentarios'] != '') {
                                                                                if($pedido->setComentarios($_POST['comentarios'])) {
                                                                                    if($pedido->insertPedido()) {
                                                                                        $result['status'] = 1;
                                                                                        // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                        $result['message'] = 'Pedido registrado correctamente';
                                                                                    } else {
                                                                                        if(Database::getException()){
                                                                                            $result['exception'] = Database::getException();
                                                                                        } else {
                                                                                            $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    $result['exception'] = 'Comentario incorrecto';
                                                                                }
                                                                            } else {         
                                                                                if($pedido->insertPedido()) {
                                                                                    $result['status'] = 1;
                                                                                    // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                    $result['message'] = 'Pedido registrado correctamente';
                                                                                } else {
                                                                                    if(Database::getException()) {
                                                                                        $result['exception'] = Database::getException();
                                                                                    } else {
                                                                                        $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'Código incorrecto';    
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'Fecha confirmada de envío incorrecta';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Seleccione una fecha confirmada de envío';     
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Fecha de entrega incorrecta';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Seleccione una fecha de entrega';     
                                                    }
                                                } else {
                                                    $result['exception'] = 'Cantidad enviada incorrecta';    
                                                }
                                            } else {
                                                $result['exception'] = 'Descripción incorrecta';
                                            }
                                        } else {
                                            $result['exception'] = 'Cantidad solicitada incorrecta';
                                        }
                                    } else {
                                        $result['exception'] = 'Oc incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Pos incorrecto';
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
            //Caso para modificar un pedidos
            case 'update':
                //Validamos los datos del formulario
                $_POST = $pedido->validateForm($_POST);
                //Pasamos la información al modelo, mediante los setters
                if($pedido->setIdPedido($_POST['idpedido'])) {
                    if(isset($_POST['responsable'])) {
                        if($pedido->setResponsable($_POST['responsable'])) {
                            //if(isset($_POST['cliente'])) {
                                //if($pedido->setCliente($_POST['cliente'])) {
                                    if($pedido->setPos($_POST['pos'])) {
                                        if($pedido->setOc($_POST['oc'])) {
                                            if($pedido->setCantidadSolicitada($_POST['cantidadsolicitada'])) {
                                                if($pedido->setDescripcion($_POST['descripcion'])) {
                                                    if($pedido->setCantidadEnviada($_POST['cantidadenviada'])) {
                                                        if($_POST['fechaentrega'] != '') {
                                                            if($pedido->setFechaEntrega($_POST['fechaentrega'])) {
                                                                if($_POST['fechaconfirmadaenvio'] != '') {
                                                                    if($pedido->setFechaConfirmadaEnvio($_POST['fechaconfirmadaenvio'])) {
                                                                        //if($pedido->setCodigo($_POST['codigo'])) {
                                                                            //if($pedido->checkCode()) {
                                                                               // $result['exception'] = 'El código no se puede repetir';
                                                                            //} else {
                                                                                if($_POST['comentarios'] != '') {
                                                                                    if($pedido->setComentarios($_POST['comentarios'])) {
                                                                                        if($pedido->updatePedido()){
                                                                                            $result['status'] = 1;
                                                                                            // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                            $result['message'] = 'Pedido modificado correctamente';
                                                                                        } else {
                                                                                            if(Database::getException()){
                                                                                                $result['exception'] = Database::getException();
                                                                                            } else {
                                                                                                $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $result['exception'] = 'Comentario incorrecto';
                                                                                    }
                                                                                } else {
                                                                                    if($pedido->updatePedido()) {
                                                                                        $result['status'] = 1;
                                                                                        // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                        $result['message'] = 'Pedido modificado correctamente';
                                                                                    } else {
                                                                                        if(Database::getException()){
                                                                                            $result['exception'] = Database::getException();
                                                                                        } else {
                                                                                            $result['exception'] = 'Ocurrió un problema al insertar el registro';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            //}
                                                                        //} else {
                                                                        //    $result['exception'] = 'Código incorrecto';    
                                                                        //}
                                                                    } else {
                                                                        $result['exception'] = 'Fecha confirmada de envío incorrecta';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'Seleccione una fecha confirmada de envío';     
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Fecha de entrega incorrecta';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Seleccione una fecha de entrega';     
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Cantidad enviada incorrecta';    
                                                    }
                                                } else {
                                                    $result['exception'] = 'Descripción incorrecta';
                                                }
                                            } else {
                                                $result['exception'] = 'Cantidad solicitada incorrecta';
                                            }
                                        } else {
                                            $result['exception'] = 'Oc incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Pos incorrecto';
                                    }
                                //} else {
                                    //$result['exception'] = 'Cliente incorrecto';    
                                //}
                            //} else {
                                //$result['exception'] = 'Escoja un cliente';
                            //}
                        } else {
                            $result['exception'] = 'Responsable incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Escoja un responsable';
                    }
                } else {
                    $result['exception'] = 'ID del pedido incorrecto';
                }
            break;
            //Caso para mostrar los pedidos
            case 'readAll':
                if($result['dataset'] = $pedido->readPedidos()) {
                    $result['status'] = 1;
                } else {
                    if(Database::getException()) {
                        $result['exception'] = Database::getException(); 
                    } else {
                        $result['exception'] = 'No hay pedidos registradas';
                    }
                }
            break;
            //Caso para realizar una busqueda de registros
            case 'search': 
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $pedido->validateForm($_POST);
                // Validamos si el input no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion para la busqueda filtrada enviando el contenido del input como parametro
                    if ($result['dataset'] = $pedido->searchRows($_POST['search'])) {
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
            //Caso para desactivar un registro
            case 'delete':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $pedido->validateForm($_POST);      
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($pedido->setIdPedido($_POST['id'])) {
                    // Cargamos los datos del registro que se desea eliminar
                    if ($data = $pedido->readOnePedido()) {
                        // Ejecutamos funcion para desactivar un usuario
                        if ($pedido->desablePedido()) {
                            $result['status'] = 1;
                            // Mostramos mensaje de exito
                            $result['message'] = 'Pedido desactivado correctamente'; 
                        // En caso de que alguna validacion falle se muestra el mensaje con el error 
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Pedido inexistente';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
            break;
            //Caso para eliminar todos los registros de la base de datos
            case 'deleteAll':
                // Ejecutamos funcion para desactivar un usuario
                if ($pedido->deleteAll()) {
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
                $_POST = $pedido->validateForm($_POST);      
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($pedido->setIdPedido($_POST['id'])) {
                    // Cargamos los datos del registro que se desea eliminar
                    if ($data = $pedido->readOnePedido()) {
                        // Ejecutamos funcion para desactivar un usuario
                        if ($pedido->enablePedido()) {
                            $result['status'] = 1;
                            // Mostramos mensaje de exito
                            $result['message'] = 'Pedido activado correctamente'; 
                        // En caso de que alguna validacion falle se muestra el mensaje con el error 
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Pedido inexistente';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
            break;
            // Caso para leer los datos de un solo registro parametrizado mediante el identificador
            case 'readOne': 
                if ($pedido->setIdPedido($_POST['id'])) {
                    // Se ejecuta la funcion para leer los datos de un registro
                    if ($result['dataset'] = $pedido->readOnePedido()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Pedido inexistente'; // Se muestra en caso de no encontrar registro con ese id
                        }
                    }
                } else {
                    $result['exception'] = 'Pedido incorrecto';
                }
            break;
            //Caso para obtener la cantidad de productos enviados por mes
            case 'cantidadEnviadaMensual':
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $pedido->cantidadEnviadaMensual()) {
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
            //Caso para obtener los 5 usuarios que han realizado más pedidos
            case 'clientesTop':
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $pedido->clientesTop()) {
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