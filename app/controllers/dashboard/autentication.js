// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';
// Atributo para almacenar la acción a realizar
var accion = 0;

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    enviarCorreo();
});

// Método para enviar el correo electronico al presionar boton.
function enviarCorreo() {
    // Realizamos peticion a la API enviando el nombre del caso el tipo de metodo y el formulario
    fetch(API_USUARIOS + 'sendVerification', {
        method: 'post',
        body: new FormData(document.getElementById('email-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Mostramos mensaje de exito
                    sweetAlert(4, response.message, null,'Revise su correo');
                } else {
                    // Enviamos mensaje de error
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

// Método para validar si el codigo es correcto
function verificarCodigo() {
    // Validamos si el campo de correo esta vacio
    if (document.getElementById("codigo").value == '') {
        // Enviamos el mensaje de validación
        sweetAlert(4, 'Ingrese el código de verificación', null);
    } else {
        // Realizamos peticion a la API enviando el nombre del caso el tipo de metodo y el formulario
        fetch(API_USUARIOS + 'verifyVerification', {
            method: 'post',
            body: new FormData(document.getElementById('email-form'))
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        // Mostramos mensaje de exito
                        sweetAlert(1, response.message,'main.php','Acceso concedido');
                    } else {
                        // Validamos el numero de intentos al verificar el codigo
                        if (accion == 3) {
                            // Si el usuario se equivoca mas de 3 veces en el codigo redirigira al index
                            sweetAlert(4, 'Has fallado 3 veces el código seras redirigido al login', 'index.php','Límite de intentos');          
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