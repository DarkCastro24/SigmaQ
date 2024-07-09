// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    //Ejecutamos la función cuando cargue la pagina
    readProfile();
});

// Función para obtener y mostrar las categorías existentes en la base.
const readProfile = () => {
    // Se realiza solicitud a la API de usuarios enviando como parametro el metodo readProfile para obtener los datos del usuario activo
    fetch(API_USUARIOS + 'readProfile', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se agregan los datos obtenidos a los inputs del formulario
                    document.getElementById('txtNombre').value = response.dataset.nombre;
                    document.getElementById('txtApellido').value = response.dataset.apellido;
                    document.getElementById('txtCorreo').value = response.dataset.correo;
                    document.getElementById('txtDui').value = response.dataset.dui;
                    document.getElementById('txtTelefono').value = response.dataset.telefono;
                    document.getElementById('txtUsuario').value = response.dataset.usuario;
                } else {
                    // Se muestra mensaje de error en caso de no ejecutarse la sentencia
                    sweetAlert(3, 'Error al cargar los datos', null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para obtener y mostrar las categorías existentes en la base.
const modificarDatos = () => {
    // Realizamos una peticion a la API enviando los elementos del form como parametro
    fetch(API_USUARIOS + 'editProfile', {
        method: 'post',
        body: new FormData(document.getElementById('save-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, 'main.php','Acción completada');
                } else {
                    // Se verifica si el token falló (ya sea por tiempo o por uso).
                    if (response.recaptcha) {
                        // Si se completa la accion redirigimos al menu principal
                        sweetAlert(2, response.exception, 'main.php');
                    } else {
                        // Validamos que el mensaje retornado sea el de datos duplicados
                        if (response.exception == 'El registro ingresado esta en uso, no se puede guardar') {
                            // Mostramos su respectiva alerta 
                            sweetAlert(4, response.exception, null,'Usuario no disponible');    
                        } else {
                            // Mostramos mensaje de error del lado del servidor
                            sweetAlert(3, response.exception, null,'Complete el campo solicitado');
                        }
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para obtener y mostrar las categorías existentes en la base.
const actualizarContraseña = () => {
    // Realizamos una peticion a la API enviando los elementos del form como parametro
    fetch(API_USUARIOS + 'changePassword', {
        method: 'post',
        body: new FormData(document.getElementById('password-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, 'main.php','Acción completada');
                } else {
                    // Se verifica si el token falló (ya sea por tiempo o por uso).
                    if (response.recaptcha) {
                        // Si se completa la accion redirigimos al menu principal
                        sweetAlert(2, response.exception, 'main.php');
                    } else {
                        // Mostramos mensaje de error del lado del servidor
                        sweetAlert(3, response.exception, null);
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}
