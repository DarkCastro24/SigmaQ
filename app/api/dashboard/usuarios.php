<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../helpers/email.php');
require_once('../../models/usuarios.php');

// Se comprueba si el nombre de la acción a realizar coincide con alguno de los casos, de lo contrario mostrara un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión que se llenaron en el login.
    session_start();
    // Se instancia la clase del modelo correspondiente.
    $cliente = new Usuario;
    // Se instancia la clase email.
    $email = new Correo;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Si existe el codigo del administrador las acciones disponibles seran diferentes 
    if (isset($_SESSION['codigoadmin'])) {
        // Se compara la acción a realizar cuando un usuario ha iniciado sesión.
        switch ($_GET['action']) {
                // Caso para cambiar la clave del usuario
            case 'changePass':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $cliente->validateForm($_POST);
                if ($_SESSION['correo2'] != $_POST['clave1']) {
                    if ($cliente->setCorreo($_SESSION['correo2'])) {
                        if ($cliente->setClave($_POST['clave1'])) {
                            // Ejecutamos la funcion para actualizar al usuario
                            if ($cliente->updatePassword()) {
                                $result['status'] = 1;
                                $result['message'] = 'Clave actualizada correctamente';
                            } else {
                                // En caso fallar la obtencion del error se muestra el error
                                $result['exception'] = Database::getException();
                            }
                        } else {
                            // En caso fallar la obtencion del error se muestra el error
                            $result['exception'] = 'El formato de la contraseña es incorrecto';
                        }
                    } else {
                        // En caso fallar la obtencion del error se muestra el error
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    // En caso fallar la obtencion del error se muestra el error
                    $result['exception'] = 'La clave no puede ser igual al usuario';
                }
                break;
                // Caso para verificar si el codigo de seguridad ingresado es correcto
            case 'verifyCode':
                $_POST = $cliente->validateForm($_POST);
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setCodigo($_POST['codigo'])) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['correo2'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo02('administradores')) {
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
                        if ($email->validarCorreo('administradores')) {
                            // Validamos si el correo ingresado tiene formato correcto
                            if ($email->setAsunto($asunto)) {
                                // Ejecutamos la funcion para enviar el correo electronico
                                if ($email->enviarCorreo()) {
                                    $result['status'] = 1;
                                    // Colocamos el mensaje de exito 
                                    $result['message'] = 'Código enviado correctamente';
                                    // Ejecutamos funcion para obtener el usuario del correo ingresado
                                    $cliente->obtenerUsuario($_POST['correo']);
                                    $_SESSION['correo2'] = $_POST['correo'];
                                    // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                    $email->actualizarCodigo2('administradores', $code);
                                } else {
                                    // En caso que el correo no se envie mostramos el error
                                    $result['exception'] = $_SESSION['error'];
                                }
                            } else {
                                // En caso fallar la obtencion del error se muestra el error
                                $result['exception'] = 'Asunto incorrecto';
                            }
                        } else {
                            // En caso fallar la obtencion del error se muestra el error
                            $result['exception'] = 'El correo ingresado no esta registrado';
                        }
                    } else {
                        // En caso fallar la obtencion del error se muestra el error
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    // En caso fallar la obtencion del error se muestra el error
                    $result['exception'] = 'Mensaje incorrecto';
                }
                break;
                // Caso para cerrar sesion dentro del sistema
            case 'logOut':
                //Ejecutamos la funcion para cerrar sesion
                if (session_destroy()) {
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
                    $result['status'] = 1;
                    $result['message'] = 'La sesión ha expirado por inactividad';
                } else {
                    // En caso de ocurrir fallar la funcion mostramos el mensaje
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
                // Caso para leer el perfil del cliente que ha iniciado sesion mediante el codigo de administrador
            case 'readProfile':
                // Se ejecuta la funcion para obtener los datos del perfil
                if ($result['dataset'] = $cliente->readProfile($_SESSION['codigoadmin'])) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        // En caso fallar la obtencion del error se muestra el error
                        $result['exception'] = 'Usuario inexistente';
                    }
                }
                break;
                // Caso verificar si existen usuarios activos en la base de datos  
            case 'readIndex':
                // Ejecutamos metodo del modelo y asignamos el valor de su retorno a la variable dataset 
                if ($result['dataset'] = $cliente->readIndex()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        // En caso fallar la obtencion del error se muestra el error
                        $result['exception'] = 'No hay usuarios registrados';
                    }
                }
                break;
            case 'register':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($cliente->setId($_POST['txtId'])) {
                    if ($cliente->setTipo(1)) {
                        if ($cliente->setNombre($_POST['txtNombre'])) {
                            if ($cliente->setApellido($_POST['txtApellido'])) {
                                if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                    if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                        if ($cliente->setDui($_POST['txtDui'])) {
                                            if ($cliente->setCorreo($_POST['txtCorreo'])) {
                                                // Validamos que la clave coincida con la confirmacion de clave                      
                                                if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                                    if ($cliente->setClave($_POST['txtClave'])) {
                                                        if ($cliente->setDireccion($_POST['txtDireccion'])) {
                                                            if ($_POST['txtClave'] != $_POST['txtUsuario']) {
                                                                // Se ejecuta la funcion para ingresar el registro
                                                                if ($cliente->createRow()) {
                                                                    $result['status'] = 1;
                                                                    // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                    $result['message'] = 'Usuario registrado correctamente';
                                                                    // Se muestran los mensajes de error segun la validacion que falle 
                                                                } else {
                                                                    $result['exception'] = Database::getException();;
                                                                }
                                                            } else {
                                                                $result['exception'] = 'La clave no puede ser igual a su usuario';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Direccion incorrecta';
                                                        }
                                                    } else {
                                                        $result['exception'] = $cliente->getPasswordError();
                                                    }
                                                } else {
                                                    $result['exception'] = 'Claves nuevas diferentes';
                                                }
                                            } else {
                                                $result['exception'] = 'Correo incorrecto';
                                            }
                                        } else {
                                            $result['exception'] = 'Dui incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Usuario incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Telefono incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Apellido incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Nombre incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Tipo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
                break;
                // Caso para editar los datos de un usuario que ha iniciado sesion 
            case 'editProfile':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($cliente->setNombre($_POST['txtNombre'])) {
                    if ($cliente->setApellido($_POST['txtApellido'])) {
                        if ($cliente->setCorreo($_POST['txtCorreo'])) {
                            if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                    // Ejecutamos el metodo para editar perfil enviando el codigo como parametro    
                                    if ($cliente->editProfile($_SESSION['codigoadmin'])) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Perfil actualizado correctamente'; // En caso de exito mostramos el mensaje
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }
                                    // En caso de ocurrir algun error con la obtencion de datos se mostraran los siguentes mensajes 
                                } else {
                                    $result['exception'] = 'Usuario incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Teléfono incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Correo incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Apellido incorrecto';
                    }
                } else {
                    $result['exception'] = 'Nombre incorrecto';
                }
                break;
                // Caso para cambiar la contraseña del usuario que ha iniciado sesion
            case 'changePassword':
                if ($cliente->setId($_SESSION['codigoadmin'])) {
                    // Validamos el form donde se encuentran los inputs para poder obtener sus valores{
                    $_POST = $cliente->validateForm($_POST);
                    // Validamos que ninguno de los inputs esten vacios 
                    if ($_POST['txtClaveActual'] != '' || $_POST['txtClaveConfirmar'] != '' || $_POST['txtClaveNueva'] != '') {
                        // Validamos que la contraseña actual sea correcta
                        if ($cliente->checkPassword($_POST['txtClaveActual'])) {
                            if ($_POST['txtClaveNueva'] != $_SESSION['usuario']) {
                                if ($_POST['txtClaveActual'] != $_POST['txtClaveConfirmar']) {
                                    // Validamos que la clave nueva y la confirmacion de clave coincida
                                    if ($_POST['txtClaveNueva'] == $_POST['txtClaveConfirmar']) {
                                        // Obtenemos el valor del input mediante la funcion del modelo 
                                        if ($cliente->setClave($_POST['txtClaveConfirmar'])) {
                                            // Ejecutamos la funcion del modelo cambiar clave enviando la variable de sesion como parametro
                                            if ($cliente->changePassword($_SESSION['codigoadmin'])) {
                                                $result['status'] = 1; // Colocamos status 1 porque muestra el icono de exito en el mensaje de alerta
                                                $result['message'] = 'Clave actualizada correctamente'; // En caso de exito mostramos el siguiente mensaje
                                            } else {
                                                $result['exception'] = Database::getException();
                                            }
                                        } else {
                                            $result['exception'] = $cliente->getPasswordError();
                                        }
                                        // Mostramos errores segun la validacion que no sea correcta 
                                    } else {
                                        $result['exception'] = 'Las nuevas claves no coinciden';
                                    }
                                } else {
                                    $result['exception'] = 'La nueva clave no puede ser igual a la anterior';
                                }
                                // Mostramos errores segun la validacion que no sea correcta 
                            } else {
                                $result['exception'] = 'La clave no puede ser igual a su usuario';
                            }
                        } else {
                            $result['exception'] = 'La clave actual es incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Ingresa todas las contraseñas';
                    }
                } else {
                    $result['exception'] = 'Error al asignar codigo admin';
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
                        $result['exception'] = 'No hay usuarios registrados';  // Mostramos mensaje de error 
                    }
                }
                break;
                // Caso para realizar la busqueda filtrada
            case 'search':
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
                if (isset($_POST['tipo'])) {
                    if ($cliente->setTipo($_POST['tipo'])) {
                        // Verificamos si el contenido de los inputs no es nulo        
                        if ($cliente->validateNull($_POST['txtId'])) {
                            // Obtenemos el valor de los input mediante los metodos set del modelo             
                            if ($cliente->setId($_POST['txtId'])) {
                                if ($cliente->validateNull($_POST['txtNombre'])) {
                                    if ($cliente->setNombre($_POST['txtNombre'])) {
                                        if ($cliente->validateNull($_POST['txtApellido'])) {
                                            if ($cliente->setApellido($_POST['txtApellido'])) {
                                                if ($cliente->validateNull($_POST['txtTelefono'])) {
                                                    if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                                        if ($cliente->validateNull($_POST['txtUsuario'])) {
                                                            if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                                                if ($cliente->validateNull($_POST['txtDui'])) {
                                                                    if ($cliente->setDui($_POST['txtDui'])) {
                                                                        if ($cliente->validateNull($_POST['txtCorreo'])) {
                                                                            if ($cliente->setCorreo($_POST['txtCorreo'])) {
                                                                                // Validamos que la clave coincida con la confirmacion de clave                      
                                                                                if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                                                                    if ($cliente->validateNull($_POST['txtClave'])) {
                                                                                        if ($cliente->setClave($_POST['txtClave'])) {
                                                                                            if ($cliente->validateNull($_POST['txtDireccion'])) {
                                                                                                if ($cliente->setDireccion($_POST['txtDireccion'])) {
                                                                                                    // Se ejecuta la funcion para ingresar el registro
                                                                                                    if ($cliente->createRow()) {
                                                                                                        $result['status'] = 1;
                                                                                                        // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                                                        $result['message'] = 'Usuario registrado correctamente';
                                                                                                        // Se muestran los mensajes de error segun la validacion que falle 
                                                                                                    } else {
                                                                                                        $result['exception'] = Database::getException();;
                                                                                                    }
                                                                                                } else {
                                                                                                    $result['exception'] = 'La direccion supera el limite de caracteres del campo';
                                                                                                }
                                                                                            } else {
                                                                                                $result['exception'] = 'Ingrese la direccion del usuario';
                                                                                            }
                                                                                        } else {
                                                                                            $result['exception'] = $cliente->getPasswordError();
                                                                                        }
                                                                                    } else {
                                                                                        $result['exception'] = 'Ingrese la clave del usuario';
                                                                                    }
                                                                                } else {
                                                                                    $result['exception'] = 'Claves nuevas diferentes';
                                                                                }
                                                                            } else {
                                                                                $result['exception'] = 'El correo tiene formato incorrecto';
                                                                            }
                                                                        } else {
                                                                            $result['exception'] = 'Ingrese la direccion del usuario';
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'El dui posee formato incorrecto';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'Ingrese el dui del usuario';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'El usuario tiene formato incorrecto';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Ingrese el alias del usuario';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'El telefono posee formato incorrecto';
                                                    }
                                                } else {
                                                    $result['exception'] = 'Ingrese el telefono del usuario';
                                                }
                                            } else {
                                                $result['exception'] = 'El apellido contiene caracteres erróneos';
                                            }
                                        } else {
                                            $result['exception'] = 'Ingrese el apellido del usuario';
                                        }
                                    } else {
                                        $result['exception'] = 'El nombre contiene caracteres erróneos';
                                    }
                                } else {
                                    $result['exception'] = 'Ingrese el nombre del usuario';
                                }
                            } else {
                                $result['exception'] = 'El codigo debe ser numerico';
                            }
                        } else {
                            $result['exception'] = 'Ingrese el codigo del usuario';
                        }
                    } else {
                        $result['exception'] = 'Escoja un tipo de usuario';
                    }
                } else {
                    $result['exception'] = 'Escoja un tipo de usuario';
                }
                break;
                // Caso para leer los datos de un solo registro parametrizado mediante el identificador
            case 'readOne':
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
            case 'update': // Caso para actualizar los datos de un registro
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if (isset($_POST['tipo'])) {
                    if ($cliente->setTipo($_POST['tipo'])) {
                        if ($cliente->setCodigo($_POST['txtIdx'])) {
                            if ($cliente->validateNull($_POST['txtId'])) {
                                if ($cliente->setId($_POST['txtId'])) {
                                    if ($cliente->validateNull($_POST['txtNombre'])) {
                                        if ($cliente->setNombre($_POST['txtNombre'])) {
                                            if ($cliente->validateNull($_POST['txtApellido'])) {
                                                if ($cliente->setApellido($_POST['txtApellido'])) {
                                                    if ($cliente->validateNull($_POST['txtTelefono'])) {
                                                        if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                                            if ($cliente->validateNull($_POST['txtUsuario'])) {
                                                                if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                                                    if ($cliente->validateNull($_POST['txtDui'])) {
                                                                        if ($cliente->setDui($_POST['txtDui'])) {
                                                                            if ($cliente->validateNull($_POST['txtCorreo'])) {
                                                                                if ($cliente->setCorreo($_POST['txtCorreo'])) {
                                                                                    if ($cliente->validateNull($_POST['txtDireccion'])) {
                                                                                        if ($cliente->setDireccion($_POST['txtDireccion'])) {
                                                                                            if ($cliente->validateNull($_POST['txtClave'])) {
                                                                                                if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                                                                                    if ($cliente->setClave($_POST['txtClave'])) {
                                                                                                        // Se ejecuta la funcion para actualizar el registro
                                                                                                        if ($cliente->updateRow()) {
                                                                                                            $result['status'] = 1;
                                                                                                            // Se muestra mensaje de exito
                                                                                                            $result['message'] = 'Usuario modificado correctamente';
                                                                                                            // En caso que exista algun error con alguna validacion se mostrara el mensaje de error
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
                                                                                                    $result['message'] = 'Usuario modificado correctamente';
                                                                                                    // En caso que exista algun error con alguna validacion se mostrara el mensaje de error
                                                                                                } else {
                                                                                                    $result['exception'] = Database::getException();;
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            $result['exception'] = 'La direccion supera el limite de caracteres del campo';
                                                                                        }
                                                                                    } else {
                                                                                        $result['exception'] = 'Ingrese la direccion del usuario';
                                                                                    }
                                                                                } else {
                                                                                    $result['exception'] = 'El correo tiene formato incorrecto';
                                                                                }
                                                                            } else {
                                                                                $result['exception'] = 'Ingrese la direccion del usuario';
                                                                            }
                                                                        } else {
                                                                            $result['exception'] = 'El dui posee formato incorrecto';
                                                                        }
                                                                    } else {
                                                                        $result['exception'] = 'Ingrese el dui del usuario';
                                                                    }
                                                                } else {
                                                                    $result['exception'] = 'El usuario tiene formato incorrecto';
                                                                }
                                                            } else {
                                                                $result['exception'] = 'Ingrese el alias del usuario';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'El telefono posee formato incorrecto';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Ingrese el telefono del usuario';
                                                    }
                                                } else {
                                                    $result['exception'] = 'El apellido contiene caracteres erróneos';
                                                }
                                            } else {
                                                $result['exception'] = 'Ingrese el apellido del usuario';
                                            }
                                        } else {
                                            $result['exception'] = 'El nombre contiene caracteres erróneos';
                                        }
                                    } else {
                                        $result['exception'] = 'Ingrese el nombre del usuario';
                                    }
                                } else {
                                    $result['exception'] = 'El codigo debe ser numerico';
                                }
                            } else {
                                $result['exception'] = 'Ingrese el codigo del usuario';
                            }
                        } else {
                            $result['exception'] = 'Codigo incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Escoja un tipo de usuario';
                    }
                } else {
                    $result['exception'] = 'Escoja un tipo de usuario';
                }
                break;
            case 'delete': // Caso para eliminar un registro 
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Se valida que el id del usuario que inicio sesion no sea el mismo que se desea eliminar 
                if ($_POST['id'] != $_SESSION['codigoadmin']) {
                    // Obtenemos el valor de los input mediante los metodos set del modelo 
                    if ($cliente->setId($_POST['id'])) {
                        // Cargamos los datos del registro que se desea eliminar
                        if ($data = $cliente->readRow()) {
                            // Ejecutamos funcion para desactivar un usuario
                            if ($cliente->desactivateUser()) {
                                $result['status'] = 1;
                                // Mostramos mensaje de exito
                                $result['message'] = 'Usuario desactivado correctamente';
                                // En caso de que alguna validacion falle se muestra el mensaje con el error 
                            } else {
                                $result['exception'] = Database::getException();
                            }
                        } else {
                            $result['exception'] = 'El usuario es inexistente';
                        }
                    } else {
                        $result['exception'] = 'Codigo del administrador incorrecto';
                    }
                } else {
                    $result['exception'] = 'No se puede desactivar a si mismo';
                }
                break;
            case 'activate': // Caso para eliminar un registro 
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Se valida que el id del usuario que inicio sesion no sea el mismo que se desea eliminar 
                if ($_POST['id'] != $_SESSION['codigoadmin']) {
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
                } else {
                    $result['exception'] = 'No se puede eliminar a si mismo';
                }
                break;
                // Caso para cargar los datos de la grafica general de usuarios usuarios con mas acciones realizadas
            case 'graficaUsuarios':
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $cliente->graficaUsuarios()) {
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
            //Caso para cargar los gráficos de los usuarios con más pedidos completados
            case 'topPedidosCompletados':
                // Ejecutamos la funcion para cargar los datos de la base
                if ($result['dataset'] = $cliente->topPedidosCompletados()) {
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
                // Caso para el inicio de sesion del usuario
            case 'logIn':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Ejecutamos la funcion que verifica si existe el usuario en la base de datos
                if ($cliente->checkUser($_POST['usuario'])) {
                    // Ejecutamos la funcion que verifica el usuario se encuentra activo
                    if ($cliente->checkState($_POST['usuario']) == 1) {
                        // Ejecutamos la funcion que verifica si la clave es correcta
                        if ($cliente->checkPassword($_POST['clave'])) {
                            // Asignamos los valores a las variables de sesion de los datos obtenidos de las consultas
                            $_SESSION['codigoadmin'] = $cliente->getId();
                            $_SESSION['usuario'] = $cliente->getUsuario();
                            $_SESSION['nombre'] = $cliente->getNombre();
                            $_SESSION['apellido'] = $cliente->getApellido();
                            $_SESSION['correo2'] = $cliente->getCorreo();
                            // Verificamos si el tipo de usuario no es root (= 1)
                            if ($cliente->getTipo() != 1) {
                                $_SESSION['tipo'] = 'Admin';
                            } else {
                                $_SESSION['tipo'] = 'Root';
                            }
                            $_SESSION['intentos'] = 0;
                            $result['status'] = 1;
                            // Mostramos mensaje de bienvenido al usuario
                            $result['message'] = 'Debes autenticar tu identidad para continuar';
                            // En caso exista un error de validacion se mostrara su respectivo mensaje
                        } else {
                            // Creamos una variable de sesion para guardar los intentos del usuario
                            $_SESSION['intentos'] = $_SESSION['intentos'] + 1;
                            if ($_SESSION['intentos'] >= 3) {
                                // Ejecutamos la funcion que verifica si la clave es correcta
                                if ($cliente->desactivateAdmin($_POST['usuario'])) {
                                    $result['status'] = 2;
                                    // Mostramos mensaje de alerta
                                    $result['message'] = 'Limite de intentos alcanzado';
                                    // En caso exista un error de validacion se mostrara su respectivo mensaje
                                    $_SESSION['intentos'] = 0;
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
                                    $result['exception'] = 'La clave ingresada es incorrecta';
                                }
                            }
                        }
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            // Mensaje de usuario inactivo
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
                    if ($email->setCorreo($_SESSION['correo2'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo02('administradores')) {
                            // Creamos variable de sesion para corroborar que el usuario autentico su usuario
                            $_SESSION['validador2'] = 'Success';
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
                        if ($email->setCorreo($_SESSION['correo2'])) {
                            // Ejecutamos la funcion para enviar el correo electronico
                            if ($email->enviarCorreo()) {
                                $result['status'] = 1;
                                // Colocamos el mensaje de exito 
                                $result['message'] = 'Ingrese su código de seguridad para continuar';
                                // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                $email->actualizarCodigo2('administradores', $code);
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
            default:
                // En caso de que el caso ingresado no sea ninguno de los anteriores se muestra el siguiente mensaje 
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el cliente no ha iniciado sesión.
        switch ($_GET['action']) {
                // Caso para verificar si el codigo de seguridad ingresado es correcto
            case 'verifyCode':
                $_POST = $usuario->validateForm($_POST);
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setCodigo($_POST['codigo'])) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['correo2'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo('usuarios')) {
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
                $_POST = $usuario->validateForm($_POST);
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
                        if ($email->validarCorreo('administradores')) {
                            // Validamos si el correo ingresado tiene formato correcto
                            if ($email->setAsunto($asunto)) {
                                // Ejecutamos la funcion para enviar el correo electronico
                                if ($email->enviarCorreo()) {
                                    $result['status'] = 1;
                                    // Colocamos el mensaje de exito 
                                    $result['message'] = 'Código enviado correctamente';
                                    // Guardamos el correo al que se envio el código
                                    $_SESSION['correo2'] = $email->getCorreo();
                                    // Ejecutamos funcion para obtener el usuario del correo ingresado
                                    $usuario->obtenerUsuario($_SESSION['correo2']);
                                    // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                    $email->actualizarCodigo('administradores', $code);
                                } else {
                                    // En caso que el correo no se envie mostramos el error
                                    $result['exception'] = $_SESSION['error'];
                                }
                            } else {
                                $result['exception'] = 'Asunto incorrecto';
                            }
                        } else {
                            $result['exception'] = 'El correo ingresado no esta registrado';
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
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Ejecutamos la funcion que verifica si existe el usuario en la base de datos
                if ($cliente->checkUser($_POST['usuario'])) {
                    // Ejecutamos la funcion que verifica el usuario se encuentra activo
                    if ($cliente->checkState($_POST['usuario']) == 1) {
                        // Ejecutamos la funcion que verifica si la clave es correcta
                        if ($cliente->checkPassword($_POST['clave'])) {
                            // Asignamos los valores a las variables de sesion de los datos obtenidos de las consultas
                            $_SESSION['codigoadmin'] = $cliente->getId();
                            $_SESSION['usuario'] = $cliente->getUsuario();
                            $_SESSION['nombre'] = $cliente->getNombre();
                            $_SESSION['apellido'] = $cliente->getApellido();
                            $_SESSION['correo2'] = $cliente->getCorreo();
                            $_SESSION['clave'] = $_POST['clave'];
                            $_SESSION['intentos'] = 0;
                            if ($cliente->getTipo() != 1) {
                                $_SESSION['tipo'] = 'Admin';
                            } else {
                                $_SESSION['tipo'] = 'Root';
                            }
                            $result['status'] = 1;
                            // Mostramos mensaje de bienvenido al usuario
                            $result['message'] = 'Debes autenticar tu identidad para continuar';
                            // En caso exista un error de validacion se mostrara su respectivo mensaje
                        } else {
                            // Creamos una variable de sesion para guardar los intentos del usuario
                            $_SESSION['intentos'] = $_SESSION['intentos'] + 1;
                            if ($_SESSION['intentos'] >= 3) {
                                // Ejecutamos la funcion que verifica si la clave es correcta
                                if ($cliente->desactivateAdmin($_POST['usuario'])) {
                                    $result['status'] = 2;
                                    // Mostramos mensaje de alerta
                                    $result['message'] = 'Limite de intentos alcanzado usuario desactivado';
                                    // En caso exista un error de validacion se mostrara su respectivo mensaje
                                    $_SESSION['intentos'] = 0;
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
                                    $result['exception'] = 'La clave ingresada es incorrecta';
                                }
                            }
                        }
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            // Mensaje de usuario inactivo
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
            case 'readIndex':  // Caso verificar si existen usuarios activos en la base de datos
                // Reseteamos el codigo del administrador para evitar errores del sistema
                $_SESSION['codigoadmin'] = 'null';
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
            case 'readAll':  // Caso para cargar los datos todos los datos en la tabla
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
            case 'register':
                // Validamos el form donde se encuentran los inputs para poder obtener sus valores
                $_POST = $cliente->validateForm($_POST);
                // Obtenemos el valor de los input mediante los metodos set del modelo 
                if ($cliente->setId($_POST['txtId'])) {
                    if ($cliente->setTipo(1)) {
                        if ($cliente->setNombre($_POST['txtNombre'])) {
                            if ($cliente->setApellido($_POST['txtApellido'])) {
                                if ($cliente->setTelefono($_POST['txtTelefono'])) {
                                    if ($cliente->setUsuario($_POST['txtUsuario'])) {
                                        if ($cliente->setDui($_POST['txtDui'])) {
                                            if ($cliente->setCorreo($_POST['txtCorreo'])) {
                                                // Validamos que la clave coincida con la confirmacion de clave                      
                                                if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                                    if ($cliente->setClave($_POST['txtClave'])) {
                                                        if ($cliente->setDireccion($_POST['txtDireccion'])) {
                                                            if ($_POST['txtClave'] != $_POST['txtUsuario']) {
                                                                // Se ejecuta la funcion para ingresar el registro
                                                                if ($cliente->createRow()) {
                                                                    $result['status'] = 1;
                                                                    // Se muestra un mensaje de exito en caso de registrarse correctamente
                                                                    $result['message'] = 'Usuario registrado correctamente';
                                                                    // Se muestran los mensajes de error segun la validacion que falle 
                                                                } else {
                                                                    $result['exception'] = Database::getException();
                                                                }
                                                            } else {
                                                                $result['exception'] = 'La clave no puede ser igual a su usuario';
                                                            }
                                                        } else {
                                                            $result['exception'] = 'Direccion incorrecta';
                                                        }
                                                    } else {
                                                        $result['exception'] = $cliente->getPasswordError();
                                                    }
                                                } else {
                                                    $result['exception'] = 'Claves nuevas diferentes';
                                                }
                                            } else {
                                                $result['exception'] = 'Correo incorrecto';
                                            }
                                        } else {
                                            $result['exception'] = 'Dui incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Usuario incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Telefono incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Apellido incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Nombre incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Tipo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Codigo incorrecto';
                }
                break;
                // Caso para cerrar sesion dentro del sistema
            case 'logOut2':
                //Ejecutamos la funcion para cerrar sesion
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'La sesión ha expirado por inactividad';
                } else {
                    // En caso de ocurrir fallar la funcion mostramos el mensaje
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            default:
                // En caso de que el caso ingresado no sea ninguno de los anteriores se muestra el siguiente mensaje 
                $result['exception'] = 'Acción no disponible fuera de la sesión';
                break;
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    // En caso que no exista ninguna accion al hacer la peticion se muestra el siguiente mensaje
    print(json_encode('Recurso no disponible'));
}
