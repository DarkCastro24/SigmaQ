// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';
// Atributo para almacenar la acción a realizar
var accion = 0;

// Método para enviar el correo electronico al presionar boton.
function enviarCorreo() {
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    if (accion == 0) {
        // Validamos si el campo de correo esta vacio
        if (document.getElementById("correo").value == '') {
            // Enviamos el mensaje de validacion
            sweetAlert(4, 'Ingrese el correo electrónico', null);
        } else {
            // Realizamos peticion a la API enviando el nombre del caso el tipo de metodo y el formulario
            fetch(API_USUARIOS + 'sendEmail', {
                method: 'post',
                body: new FormData(document.getElementById('email-form'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Mostramos mensaje de exito
                            sweetAlert(1, response.message, null,'Revise su correo');
                            // Habilitamos el campo para ingresar el codigo
                            document.getElementById('codigo').disabled = false;
                            // Deshabilitamos el campo para ingresar el correo
                            document.getElementById('correo').disabled = true;
                            // Cambiamos el texto del boton
                            document.getElementById('texto').innerHTML = 'VERIFICAR CÓDIGO';
                            // Colocamos uno a la accion para identificar que accion se debe realizar
                            accion = 1;
                        } else {
                            sweetAlert(4, response.exception, null,'Ha ocurrido un error');
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    } else {
        // Validamos si el campo de correo esta vacio
        if (document.getElementById("codigo").value == '') {
            // Enviamos el mensaje de validación
            sweetAlert(4, 'Ingrese el código de verificación', null);
        } else {
            // Realizamos peticion a la API enviando el nombre del caso el tipo de metodo y el formulario
            fetch(API_USUARIOS + 'verifyCode', {
                method: 'post',
                body: new FormData(document.getElementById('email-form'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Mostramos mensaje de exito
                            sweetAlert(1, response.message, 'password.php','Acceso concedido');
                        } else {
                            // Validamos el numero de intentos al verificar el codigo
                            if (accion == 4) {
                                // Si el usuario se equivoca mas de 3 veces en el codigo redirigira al index
                                sweetAlert(4, 'Has fallado 3 veces el código, serás redirigido al login', 'index.php','Límite de intentos');
                            } else {
                                sweetAlert(4, response.exception, null);
                                // Reutilizamos el atributo para llevar la cuenta de los intentos
                                accion++;
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
    }
}