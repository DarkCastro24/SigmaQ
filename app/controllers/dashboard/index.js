// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENT = '../../app/api/dashboard/usuarios.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Petición para verificar si existen usuarios.
    fetch(API_CLIENT + 'readIndex', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción
                if (response.status) {
                    sweetAlert(4, 'Debe autenticarse para ingresar', null);
                } else {
                    // Se verifica si ocurrió un problema en la base de datos, de lo contrario se continua normalmente.
                    if (response.error) {
                        sweetAlert(2, response.exception, null);
                    } else {
                        sweetAlert(3, response.exception, 'register.php');
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
});

// Metodo para cargar todos los datos de la categoria seleccionada al presionar el boton
const iniciarSesion = () => {  
    // Validamos que el campo de usuario no este vacio
    if(document.getElementById("usuario").value == ''){
        sweetAlert(3, 'Debe ingresar su usuario para continuar', null,'Complete todos los campos');
    } else {
        // Validamos que el campo de clave no este vacio
        if(document.getElementById("clave").value == ''){
            sweetAlert(3, 'Debe ingresar su contraseña para continuar', null,'Complete todos los campos');
        } else {
            // Realizamos peticion a la API de clientes con el caso login y method post para dar acceso al valor de los campos del form
            fetch(API_CLIENT + 'logIn', {
                method: 'post',
                body: new FormData(document.getElementById('session-form'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // En caso de iniciar sesion correctamente mostrar mensaje y redirigir al menu
                            sweetAlert(4, response.message, 'autentication.php','Contraseña correcta');
                        } else {
                            if (response.message != 'Limite de intentos alcanzado') {
                                sweetAlert(3, response.exception, null);
                            } else {
                                sweetAlert(4, response.exception, null,'Usuario desactivado');
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