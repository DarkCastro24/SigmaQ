// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENT = '../../app/api/public/clientes.php?action=';
const API_HISTORIAL = '../../app/api/dashboard/historial.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Petición para verificar si existen usuarios.
    fetch(API_CLIENT + 'readIndex', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            return request.json()
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).then(function (response) {
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción
        if (response.status) {
            // Mostramos mensaje de autenticacion
            sweetAlert(4, 'Debe autenticarse para acceder al sistema');
        } else {
            // Se verifica si ocurrió un problema en la base de datos, de lo contrario se continua normalmente.
            if (response.error) {
                sweetAlert(2, response.exception, null);
            } else {
                sweetAlert(3, response.exception, null);
            }
        }
    }).catch(function (error) {
        console.log(error);
    });
});

// Metodo para cargar todos los datos de la categoria seleccionada al presionar el boton
const iniciarSesion = () => {
    if (document.getElementById("usuario").value == '') {
        // Mostramos mensaje de validacion al usuario
        sweetAlert(3, 'Debe ingresar su usuario para continuar', null, 'Complete todos los campos');
    } else {
        // Validamos si el usuario ingreso su contraseña
        if (document.getElementById("clave").value == '') {
            // Mostramos mensaje de validacion al usuario
            sweetAlert(3, 'Debe ingresar la contraseña para continuar', null, 'Complete todos los campos');
        } else {
            // Realizamos una peticion a la API enviando el formulario session para obtener los datos en caso Login
            fetch(API_CLIENT + 'logIn', {
                method: 'post',
                body: new FormData(document.getElementById('session-form'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            if (response.message == 'Limite de intentos alcanzado usuario desactivado') {
                                // Mostramos mensaje de exito
                                sweetAlert(4, response.message, null,'Usuario desactivado');   
                            } else {
                                // Registramos la accion realizada por el cliente dentro del historial de acciones
                                updateHistorial (API_HISTORIAL, 'Ingreso sus credenciales');
                                // Mostramos mensaje de exito
                                sweetAlert(4, response.message, 'autentication.php','Contraseña correcta');
                            }
                        } else {
                            sweetAlert(3, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
}