<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../helpers/email.php');
require_once('../../models/clientes.php');

// Se comprueba si el nombre de la acción a realizar coincide con alguno de los casos, de lo contrario mostrara un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión que se llenaron en el login.
    session_start();
    // Se instancia la clase del modelo correspondiente.
    $cliente = new Cliente;
    // Artributo para almacenar el numero de intentos
    $_SESSION['intentos3'];
    // Se instancia la clase email.
    $email = new Correo;
    // Se instancia atributo para guardar el numero de intentos
    $_SESSION['intentos'] = 0;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se compara la acción a realizar cuando un usuario ha iniciado sesión.
    switch ($_GET['action']) {
            // Caso para cambiar la clave del usuario
        case 'changePass':
            // Obtenemos el form con los inputs para obtener los datos
            $_POST = $cliente->validateForm($_POST);
            if ($_SESSION['mail'] != $_POST['clave1']) {
                if ($cliente->setCorreo($_SESSION['mail'])) {
                    if ($cliente->setClave($_POST['clave1'])) {
                        // Ejecutamos la funcion para actualizar al usuario
                        if ($cliente->updatePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'Clave actualizada correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'El formato de la contraseña es incorrecto';
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
            } else {
                $result['exception'] = 'La clave no puede ser igual al usuario';
            }
            break;
            // Caso para cerrar sesion dentro del sistema
        case 'logOut':
            //Ejecutamos la funcion para cerrar sesion
            if (session_destroy()) {
                $_SESSION['codigoadmin'] = 'null';
                $result['status'] = 1;
                $result['message'] = 'Sesión eliminada correctamente';
            } else {
                // En caso de ocurrir fallar la funcion mostramos el mensaje
                $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
            }
            break;
            // Caso para cerrar sesion dentro del sistema
        case 'logOut2':
            //Ejecutamos la funcion para cerrar sesion
            if (session_destroy()) {
                $_SESSION['codigocliente'] = 'null';
                $result['status'] = 1;
                $result['message'] = 'La sesión ha expirado por inactividad';
            } else {
                // En caso de ocurrir fallar la funcion mostramos el mensaje
                $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
            }
            break;
            // Caso para verificar si el codigo de seguridad ingresado es correcto
        case 'verifyCode':
            $_POST = $cliente->validateForm($_POST);
            // Validmos el formato del mensaje que se enviara en el correo
            if ($email->setCodigo($_POST['codigo'])) {
                // Validamos si el correo ingresado tiene formato correcto
                if ($email->setCorreo($_SESSION['mail'])) {
                    // Ejecutamos la funcion para validar el codigo de seguridad
                    if ($email->validarCodigo('clientes')) {
                        $result['status'] = 1;
                        // Colocamos el mensaje de exito 
                        $result['message'] = 'El código ingresado es correcto';
                    } else {
                        // En caso que el correo no se envie mostramos el error
                        $result['exception'] = 'El código ingresado no es correcto';
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
            } else {
                $result['exception'] = 'Mensaje incorrecto';
            }
            break;
        case 'sendEmail':
            $_POST = $cliente->validateForm($_POST);
            // Generamos el codigo de seguridad 
            $code = rand(999999, 111111);
            // Concatenamos el codigo generado dentro del mensaje a enviar
            $message = "Has solicitado recuperar tu contraseña por medio de correo electrónico, su código de seguridad es: $code";
            // Colocamos el asunto del correo a enviar
            $asunto = "Recuperación de contraseña SigmaQ";
            // Validmos el formato del mensaje que se enviara en el correo
            if ($email->setMensaje($message)) {
                // Validamos si el correo ingresado tiene formato correcto
                if ($email->setCorreo($_POST['correo'])) {
                    if ($email->validarCorreo('clientes')) {
                        // Validamos si el correo ingresado tiene formato correcto
                        if ($email->setAsunto($asunto)) {
                            // Ejecutamos la funcion para enviar el correo electronico
                            if ($email->enviarCorreo()) {
                                $result['status'] = 1;
                                // Colocamos el mensaje de exito 
                                $result['message'] = 'Código enviado correctamente';
                                // Guardamos el correo al que se envio el código
                                $_SESSION['mail'] = $email->getCorreo();
                                // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                $email->actualizarCodigo('clientes', $code);
                            } else {
                                // En caso que el correo no se envie mostramos el error
                                $result['exception'] = $_SESSION['error'];
                            }
                        } else {
                            $result['exception'] = 'Asunto incorrecto';
                        }
                    } else {
                        $result['exception'] = 'El correo ingresado no esta registrado xd';
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
            } else {
                $result['exception'] = 'Mensaje incorrecto';
            }
            break;
            // Caso para el inicio de sesion del usuario
        case 'logIn':
            // Reseteamos el codigo del cliente para evitar errores del sistema
            $_SESSION['codigocliente'] = 'null';
            // Validamos el form donde se encuentran los inputs para poder obtener sus valores
            $_POST = $cliente->validateForm($_POST);
            // Ejecutamos la funcion que verifica si existe el usuario en la base de datos
            if ($cliente->checkUser($_POST['usuario'])) {
                // Ejecutamos la funcion que verifica si la clave es correcta
                if ($cliente->checkState($_POST['usuario']) == 1) {
                    // Creamos una variable de sesion para guardar los intentos del usuario
                    $_SESSION['intentos3'] = $_SESSION['intentos3'] + 1;
                    // Ejecutamos la funcion que verifica si el usuario esta activo
                    if ($cliente->checkPassword($_POST['clave'])) {
                        // Asignamos los valores a las variables de sesion de los datos obtenidos de las consultas
                        $_SESSION['codigocliente'] = $cliente->getId();
                        $_SESSION['usuario'] = $cliente->getUsuario();
                        $_SESSION['empresa'] = $cliente->getEmpresa();
                        $_SESSION['correo'] = $cliente->getCorreo();
                        $_SESSION['clave'] = $_POST['clave'];
                        $_SESSION['intentos'] = 0;
                        $result['status'] = 1;
                        // Mostramos mensaje de bienvenido al usuario
                        $result['message'] = 'Debes autenticar tu identidad para continuar';
                        // En caso exista un error de validacion se mostrara su respectivo mensaje
                    } else {
                        if ($_SESSION['intentos3'] >= 3) {
                            // Ejecutamos la funcion que verifica si la clave es correcta
                            if ($cliente->desactivateClient($_POST['usuario'])) {
                                $result['status'] = 1;
                                // Mostramos mensaje de alerta
                                $result['message'] = 'Limite de intentos alcanzado usuario desactivado';
                                // En caso exista un error de validacion se mostrara su respectivo mensaje
                                $_SESSION['intentos3'] = 0;
                            } else {
                                if (Database::getException()) {
                                    $result['exception'] = Database::getException();
                                } else {
                                    // Mensaje de usuario inactivo
                                    $result['exception'] = 'Error al desactivar usuario';
                                }
                            }
                        } else {
                            if (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                // Mensaje de clave incorrecta
                                $result['exception'] = 'Clave ingresada es incorrecta';
                            }
                        }
                    }
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        // Mensaje de estado inactivo
                        $result['exception'] = 'El usuario se encuentra inactivo';
                    }
                }
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    // Mensaje de usuario incorrecto
                    $result['exception'] = 'Usuario incorrecto';
                }
            }
            break;
            // Caso para verificar si el codigo de seguridad ingresado es correcto
        case 'verifyVerification':
            $_POST = $cliente->validateForm($_POST);
            // Validmos el formato del mensaje que se enviara en el correo
            if ($email->setCodigo($_POST['codigo'])) {
                // Validamos si el correo ingresado tiene formato correcto
                if ($email->setCorreo($_SESSION['correo'])) {
                    // Ejecutamos la funcion para validar el codigo de seguridad
                    if ($email->validarCodigo('clientes')) {
                        // Creamos variable de sesion para corroborar que el usuario autentico su usuario
                        $_SESSION['validador'] = 'Success';
                        // Retornamos el valor de 1 (exito)
                        $result['status'] = 1;
                        // Colocamos el mensaje de exito 
                        $result['message'] = 'El código ingresado es correcto';
                    } else {
                        // En caso que el correo no se envie mostramos el error
                        $result['exception'] = 'El código ingresado no es correcto';
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
            } else {
                $result['exception'] = 'Mensaje incorrecto';
            }
            break;
            // Caso para enviar el codigo de verificacion al correo del usuario
        case 'sendVerification':
            $_POST = $cliente->validateForm($_POST);
            // Generamos el codigo de seguridad 
            $code = rand(999999, 111111);
            // Concatenamos el codigo generado dentro del mensaje a enviar
            $message = "Ingrese el siguiente codigo dentro del formulario para iniciar sesión: $code";
            // Colocamos el asunto del correo a enviar
            $asunto = "Sistema de autenticacion de usuarios de SigmaQ";
            // Validmos el formato del mensaje que se enviara en el correo
            if ($email->setMensaje($message)) {
                // Validamos si el correo ingresado tiene formato correcto
                if ($email->setAsunto($asunto)) {
                    if ($email->setCorreo($_SESSION['correo'])) {
                        // Ejecutamos la funcion para enviar el correo electronico
                        if ($email->enviarCorreo()) {
                            $result['status'] = 1;
                            // Colocamos el mensaje de exito 
                            $result['message'] = 'Ingrese su código de seguridad para continuar';
                            // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                            $email->actualizarCodigo('clientes', $code);
                            // Creamos variable de sesion para validar la plantilla
                            $_SESSION['validacion'] = '';
                        } else {
                            // En caso que el correo no se envie mostramos el error
                            $result['exception'] = $_SESSION['error'];
                        }
                    } else {
                        $result['exception'] = 'Asunto incorrecto';
                    }
                } else {
                    $result['exception'] = 'Asunto incorrecto';
                }
            } else {
                $result['exception'] = 'Mensaje incorrecto';
            }
            break;
            // Caso para cargar los datos todos los datos en la tabla
        case 'readAll':
            // Ejecutamos metodo del modelo y asignamos el valor de su retorno a la variable dataset 
            if ($result['dataset'] = $cliente->readAll()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay usuarios registrados';
                }
            }
            break;
            // Caso verificar si existen usuarios activos en la base de datos
        case 'readIndex':
            // Reseteamos el codigo del administrador para evitar errores del sistema
            $_SESSION['codigocliente'] = 'null';
            // Ejecutamos metodo del modelo y asignamos el valor de su retorno a la variable dataset 
            if ($result['dataset'] = $cliente->readIndex()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay usuarios registrados';
                }
            }
            break;
            // Caso para cerrar sesion dentro del sistema
        case 'logOut2':
            //Ejecutamos la funcion para cerrar sesion
            if (session_destroy()) {
                $_SESSION['codigoadmin'] = 'null';
                $result['status'] = 1;
                $result['message'] = 'La sesión ha expirado por inactividad';
            } else {
                // En caso de ocurrir fallar la funcion mostramos el mensaje
                $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
            }
            break;
        default:
            // En caso de que el caso ingresado no sea ninguno de los anteriores se muestra el siguiente mensaje 
            $result['exception'] = 'Acción no disponible dentro de la sesión';
            break;
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    // En caso que no exista ninguna accion al hacer la peticion se muestra el siguiente mensaje
    print(json_encode('Recurso no disponible'));
}
