// Constante para establecer la ruta y parámetros de comunicación con la API.
const API = '../../app/api/public/clientes.php?action=';
const API_HISTORIAL = '../../app/api/dashboard/historial.php?action=';

// Función para mostrar un mensaje de confirmación al momento de cerrar sesión.
const logOut = () => {
    swal({
        title: 'Advertencia',
        text: '¿Está seguro de cerrar la sesión?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de cerrar sesión, de lo contrario se muestra un mensaje.
        if (value) {
            // Peticion a la base de datos con el parametro de la API de usuarios y el caso logout
            fetch(API + 'logOut', {
                method: 'get'
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // En caso de exito muestra mensaje de exito y redirige al login 
                            sweetAlert(1, response.message, 'index.php', 'Acción completada');
                            // Registramos la accion realizada por el cliente dentro del historial de acciones
                            updateHistorial (API_HISTORIAL, 'Cerro sesión');
                        } else {
                            sweetAlert(2, response.exception, null, 'Ha ocurrido un error');
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        } else {
            // En caso que el usuario seleccione la opcion no 
            sweetAlert(4, 'Puede continuar con la sesión', null);
        }
    });
}
