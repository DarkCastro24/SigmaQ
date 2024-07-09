<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/clientes.php');

// Se comprueba si el nombre de la acción a realizar coincide con alguno de los casos, de lo contrario mostrara un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión que se llenaron en el login.
    session_start();
    // Se instancia la clase del modelo correspondiente.
    $cliente = new Cliente;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se compara la acción a realizar cuando un usuario ha iniciado sesión
    switch ($_GET['action']) {
        case 'readAll':  // Caso para cargar los datos todos los datos en la tabla
            // Ejecutamos metodo del modelo y asignamos el valor de su retorno a la variable dataset 
            if ($result['dataset'] = $cliente->readAll()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay clientes registrados';
                }
            }
            break;
        case 'search':  // Caso para realizar la busqueda filtrada 
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Validamos si el input no esta vacio
            if ($_POST['search'] != '') {
                // Ejecutamos la funcion para la busqueda filtrada enviando el contenido del input como parametro
                if ($result['dataset'] = $cliente->searchRows($_POST['search'])) {
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
        case 'create':  // Caso para crear un registro
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Obtenemos el valor de los input mediante los metodos set del modelo 
            if ($cliente->validateNull($_POST['txtId'])) {
                if ($cliente->setId($_POST['txtId'])) {
                    if ($cliente->validateNull($_POST['txtEmpresa'])) {
                        if ($cliente->setEmpresa($_POST['txtEmpresa'])) {
                            if ($cliente->validateNull($_POST['txtTelefono'])) {
                                if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                    if ($cliente->validateNull($_POST['txtUsuario'])) {
                                        if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                            if ($cliente->validateNull($_POST['txtCorreo'])) {
                                                if ($cliente->setCorreo($_POST['txtCorreo'])) {
                                                    // Validamos que la clave coincida con la confirmacion de clave                      
                                                    if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                                        if ($cliente->validateNull($_POST['txtClave'])) {
                                                            if ($cliente->setClave($_POST['txtClave'])) {
                                                                // Se ejecuta la funcion para ingresar el registro
                                                                if ($cliente->createRow()) {
                                                                    $result['status'] = 1;
                                                                    // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                    $result['message'] = 'Cliente registrado correctamente';
                                                                } else {
                                                                    $result['exception'] = Database::getException();;
                                                                }
                                                            } else {
                                                                $result['exception'] = $cliente->getPasswordError();
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Ingrese la clave del cliente';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Claves nuevas diferentes';
                                                    }
                                                } else {
                                                    $result['exception'] = 'El correo tiene formato incorrecto';
                                                }
                                            } else {
                                                $result['exception'] = 'Ingrese el correo del cliente';
                                            }
                                        } else {
                                            $result['exception'] = 'El usuario tiene formato incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Ingrese el usuario del cliente';
                                    }
                                } else {
                                    $result['exception'] = 'El telefono posee formato incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Ingrese el numero de telefono';
                            }
                        } else {
                            $result['exception'] = 'El nombre de la empresa contiene caracteres erróneos';
                        }
                    } else {
                        $result['exception'] = 'Ingrese el nombre de la empresa';
                    }
                } else {
                    $result['exception'] = 'El codigo debe ser numerico';
                }
            } else {
                $result['exception'] = 'Ingrese el codigo del usuario';
            }
            break;
        case 'readOne': // Caso para leer los datos de un solo registro parametrizado mediante el identificador
            if ($cliente->setId($_POST['id'])) {
                // Se ejecuta la funcion para leer los datos de un registro
                if ($result['dataset'] = $cliente->readRow()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Usuario inexistente'; // Se muestra en caso de no encontrar registro con ese id
                    }
                }
            } else {
                $result['exception'] = 'Usuario incorrecto';
            }
            break;
            // Caso para leer los datos de un solo registro parametrizado mediante el identificador
        case 'readProfile':
            if ($cliente->setId($_SESSION['codigocliente'])) {
                // Se ejecuta la funcion para leer los datos de un registro
                if ($result['dataset'] = $cliente->readRow()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Usuario inexistente'; // Se muestra en caso de no encontrar registro con ese id
                    }
                }
            } else {
                $result['exception'] = 'Usuario incorrecto';
            }
            break;
            // Caso para editar los datos de un usuario que ha iniciado sesion
        case 'editProfile':
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Obtenemos el valor de los input mediante los metodos set del modelo 
            if ($cliente->setTelefono($_POST['txtTelefono'])) {
                if ($cliente->setCorreo($_POST['txtCorreo'])) {
                    // Ejecutamos el metodo para editar perfil enviando el codigo como parametro    
                    if ($cliente->editProfile($_SESSION['codigocliente'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Perfil actualizado correctamente'; // En caso de exito mostramos el mensaje
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'El correo ingresado no es valido';
                }
            } else {
                $result['exception'] = 'El teléfono ingresado no es valido';
            }
            break;
        case 'changePassword': // Caso para cambiar la contraseña del usuario que ha iniciado sesion
            if ($cliente->setId($_SESSION['codigocliente'])) {
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores{
                $_POST = $cliente->validateForm($_POST);
                // Validamos que ninguno de los inputs esten vacios 
                if ($_POST['txtClaveActual'] != '' && $_POST['txtClaveConfirmar'] != '' && $_POST['txtClaveNueva'] != '') {
                    // Validamos que la contraseña actual sea correcta
                    if ($cliente->checkPassword($_POST['txtClaveActual'])) {
                        if ($_POST['txtClaveActual'] != $_POST['txtClaveConfirmar']) {
                            // Validamos que la clave nueva y la confirmacion de clave coincida
                            if ($_POST['txtClaveNueva'] == $_POST['txtClaveConfirmar']) {
                                if ($_POST['txtClaveNueva'] != $_SESSION['usuario']) {
                                    // Obtenemos el valor del input mediante la funcion del modelo 
                                    if ($cliente->setClave($_POST['txtClaveConfirmar'])) {
                                        // Ejecutamos la funcion del modelo cambiar clave enviando la variable de sesion como parametro
                                        if ($cliente->changePassword($_SESSION['codigocliente'])) {
                                            $result['status'] = 1; 
                                            // En caso de exito mostramos el siguiente mensaje
                                            $result['message'] = 'Clave actualizada correctamente'; 
                                        } else {
                                            $result['exception'] = Database::getException();
                                        }
                                    } else {
                                        $result['exception'] = $cliente->getPasswordError();
                                    }
                                } else {
                                    $result['exception'] = 'La clave no puede ser igual a su usuario';
                                }
                                // Mostramos errores segun la validacion que no sea correcta 
                            } else {
                                $result['exception'] = 'Las nuevas claves no coinciden';
                            }
                            // Mostramos errores segun la validacion que no sea correcta 
                        } else {
                            $result['exception'] = 'La nueva clave no puede ser igual a la anterior';
                        }
                    } else {
                        $result['exception'] = 'La clave actual es incorrecta';
                    }
                } else {
                    $result['exception'] = 'Complete todos los campos solicitados';
                }
            } else {
                $result['exception'] = 'Error al asignar codigo admin';
            }
            break;
            // Caso para actualizar los datos de un registro
        case 'update':
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Obtenemos el valor de los input mediante los metodos set del modelo 
            if ($cliente->setCodigo($_POST['txtIdx'])) {
                if ($cliente->validateNull($_POST['txtId'])) {
                    if ($cliente->setId($_POST['txtId'])) {
                        if ($cliente->validateNull($_POST['txtEmpresa'])) {
                            if ($cliente->setEmpresa($_POST['txtEmpresa'])) {
                                if ($cliente->validateNull($_POST['txtTelefono'])) {
                                    if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                        if ($cliente->validateNull($_POST['txtUsuario'])) {
                                            if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                                if ($cliente->validateNull($_POST['txtCorreo'])) {
                                                    if ($cliente->setCorreo($_POST['txtCorreo'])) {
                                                        // Verificamos si el usuario ingreso o no la clave asi obtenemos el valor del input o no
                                                        if ($cliente->validateNull($_POST['txtClave'])) {
                                                            // Validamos que la clave coincida con la confirmacion de clave                      
                                                            if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                                                if ($cliente->setClave($_POST['txtClave'])) {
                                                                    // Se ejecuta la funcion para ingresar el registro
                                                                    if ($cliente->updateRow()) {
                                                                        $result['status'] = 1;
                                                                        // Se muestra un mensaje de exito en caso de modificarse correctamente
                                                                        $result['message'] = 'Cliente y clave modificados correctamente';
                                                                    } else {
                                                                        $result['exception'] = Database::getException();;
                                                                    }
                                                                } else {
                                                                    $result['exception'] = $cliente->getPasswordError();
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Claves nuevas diferentes';
                                                            }
                                                        } else {
                                                            // Se ejecuta la funcion para actualizar el registro (Sin cambiar clave)
                                                            if ($cliente->updateRow()) {
                                                                $result['status'] = 1;
                                                                // Se muestra mensaje de exito
                                                                $result['message'] = 'Cliente modificado correctamente';
                                                                // En caso que exista algun error con alguna validacion se mostrara el mensaje de error
                                                            } else {
                                                                $result['exception'] = Database::getException();;
                                                            }
                                                        }
                                                    } else {
                                                        $result['exception'] = 'El correo tiene formato incorrecto';
                                                    }
                                                } else {
                                                    $result['exception'] = 'Ingrese el correo del cliente';
                                                }
                                            } else {
                                                $result['exception'] = 'El usuario tiene formato incorrecto';
                                            }
                                        } else {
                                            $result['exception'] = 'Ingrese el usuario del cliente';
                                        }
                                    } else {
                                        $result['exception'] = 'El telefono posee formato incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Ingrese el numero de telefono';
                                }
                            } else {
                                $result['exception'] = 'El nombre de la empresa contiene caracteres erróneos';
                            }
                        } else {
                            $result['exception'] = 'Ingrese el nombre de la empresa';
                        }
                    } else {
                        $result['exception'] = 'El codigo debe ser numerico';
                    }
                } else {
                    $result['exception'] = 'Ingrese el codigo del usuario';
                }
            } else {
                $result['exception'] = 'El codigo debe ser numerico';
            }
            break;
        case 'delete': // Caso para eliminar un registro 
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Obtenemos el valor de los input mediante los metodos set del modelo 
            if ($cliente->setId($_POST['id'])) {
                // Cargamos los datos del registro que se desea eliminar
                if ($data = $cliente->readRow()) {
                    // Ejecutamos funcion para desactivar un usuario
                    if ($cliente->desactivateUser()) {
                        $result['status'] = 1;
                        // Mostramos mensaje de exito
                        $result['message'] = 'Cliente desactivado correctamente';
                        // En caso de que alguna validacion falle se muestra el mensaje con el error 
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
            } else {
                $result['exception'] = 'Codigo incorrecto';
            }
            break;
        case 'activate': // Caso para eliminar un registro 
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Obtenemos el valor de los input mediante los metodos set del modelo 
            if ($cliente->setId($_POST['id'])) {
                // Cargamos los datos del registro que se desea eliminar
                if ($data = $cliente->readRow()) {
                    // Ejecutamos funcion para activar un usuario
                    if ($cliente->activateUser()) {
                        $result['status'] = 1;
                        // Mostramos mensaje de exito
                        $result['message'] = 'Usuario activado correctamente';
                        // En caso de que alguna validacion falle se muestra el mensaje con el error 
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
            } else {
                $result['exception'] = 'Codigo incorrecto';
            }
            break;
            // Caso para cargar los parametros en variables de sesion para el reporte parametrizado
        case 'param-report':
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Validamos si los inputs no estan vacios
            if ($_POST['fechaInicial'] != '' || $_POST['fechaFinal'] != '') {
                // Validamos si el formato de las fechas ingresas es correcto
                if (!$cliente->setFechaInicial($_POST['fechaInicial'])) {
                    if (!$cliente->setFechaFinal($_POST['fechaFinal'])) {
                        // Validamos si la fecha inicial no es mayor a la fecha final
                        if ($_POST['fechaInicial'] < $_POST['fechaFinal']) {
                            // Asignamos el valor de los parametros a las variables de sesion
                            $_SESSION['fechaInicio'] = $_POST['fechaInicial'];
                            $_SESSION['fechaFin'] = $_POST['fechaFinal'];
                            $result['status'] = 1;
                        } else {
                            $result['exception'] = 'Fecha inicial mayor a la fecha menor';
                        }
                    } else {
                        $result['exception'] = 'La fecha inicial no cumple el formato correcto';
                    }
                } else {
                    $result['exception'] = 'La fecha final no cumple el formato correcto';
                }
            } else {
                $result['exception'] = 'Seleccione el rango de fechas';
            }
            break;
            // Caso para cargar los datos de la grafica general de clientes con mas acciones realizadas
        case 'graficaClientes':
            // Ejecutamos la funcion para cargar los datos de la base
            if ($result['dataset'] = $cliente->graficaClientes()) {
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
            // Caso para cargar los datos de la grafica parametrizada de la cantidad de acciones realizadas por cada usuario
        case 'graficaParam':
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Ejecutamos la funcion para cargar los datos de la base
            if ($result['dataset'] = $cliente->graficaParam($_POST['id'])) {
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
        //Función para obtener los pedidos semanales de un cliente
        case 'pedidosSemanales':
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            //Validamos el ID del cliente
            if($cliente->setId($_POST['id'])) {
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $cliente->pedidosSemanales()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay datos disponibles';
                    }
                }
            } else {
                $result['exception'] = 'El ID del cliente es incorrecto';
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
