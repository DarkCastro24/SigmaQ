// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ADMINS = '../../app/api/dashboard/usuarios.php?action=readAll';
const API_CLIENTES = '../../app/api/dashboard/clientes.php?action=readAll';
const API_PEDIDOS = '../../app/api/public/statusPedidos.php?action=';

// Función manejadora de eventos, para ejecutar justo cuando termine de cardar.
document.addEventListener('DOMContentLoaded', () => {
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    getResponsableInfo(API_PEDIDOS);
})

// Funcion para obtener los datos del responsable de cada empresa.
const getResponsableInfo = api => {
    /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo get 
    para obtener el resultado de la API*/
    fetch(api + 'readResponsableInfo', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            // console.log(request.text())
            return request.json()
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).then(function (response) {
        let data = [];
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (response.status) {
            // Se obtiene el valor del dataset para asignarlo al atributo data
            data = response.dataset;
        } else {
            sweetAlert(response.exception);
        }
        // Se envían los datos a la función del controlador para que llene la tabla en la vista.
        setResponsableInfo(data);
    }).catch(function (error) {
        console.log(error);
    });
}

const setResponsableInfo = dataset => {
    if (dataset == [].length) {
        sweetAlert(4, 'No se encontró la información del responsable');
    } else {
        console.log(dataset[0])
        document.getElementById('responsable-name').innerHTML = `${dataset[0].nombre} ${dataset[0].apellido}`
        document.getElementById('responsable-telefono').innerHTML = `T: (503) ${dataset[0].telefono}`
        document.getElementById('responsable-correo').innerHTML = `${dataset[0].correo}`
    }
}