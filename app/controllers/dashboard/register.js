// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENT = '../../app/api/dashboard/usuarios.php?action=';

// Método manejador de eventos que se ejecuta cuando se envía el formulario de registrar cliente.
const registrarUsuario = () => {
    // Se realiza solicitud a la API de usuarios enviando como parametro el metodo register en caso de primer uso del sistema
    fetch(API_CLIENT + 'register', {
        method: 'post',
        body: new FormData(document.getElementById('register-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, 'index.php', 'Datos registrados correctamente');
                } else {
                    // Se verifica si el token falló (ya sea por tiempo o por uso).
                    if (response.recaptcha) {
                        sweetAlert(2, response.exception, 'index.php');
                    } else {
                        sweetAlert(2, response.exception, null);
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
