// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ESTADO = '../../app/api/public/estadoCuenta.php?action=';

// Función manejadora de eventos, para ejecutar justo cuando termine de cardar.
document.addEventListener('DOMContentLoaded', () => {
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_ESTADO);
    // Registramos la accion realizada por el cliente dentro del historial de acciones
    updateHistorial (API_HISTORIAL, 'Consulto su estado de cuentas');
})

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_ESTADO, 'search-form');
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
const fillTable = (dataset) => {
    //Se oculta el modal de la personalización
    $('#modal-form').modal('hide');

    //Se crea la fila de los headers de la tabla
    let headers = `<tr>`;

    //Se agregan los headers si está seleccionado en la tabla
    if (document.getElementById('responsable').checked) {
        headers += `<th>Responsable</th>`
    }
    if (document.getElementById('sociedad').checked) {
        headers += `<th>Sociedad</th>`
    }
    if (document.getElementById('usuario').checked) {
        headers += `<th>Usuario</th>`
    }
    if (document.getElementById('codigo').checked) {
        headers += `<th>Código</th>`
    }
    if (document.getElementById('factura').checked) {
        headers += `<th>Factura</th>`
    }
    if (document.getElementById('asignacion').checked) {
        headers += `<th>Asignacion</th>`
    }
    if (document.getElementById('fechacontable').checked) {
        headers += `<th>Fecha contable</th>`
    }
    if (document.getElementById('clase').checked) {
        headers += `<th>Clase</th>`
    }
    if (document.getElementById('vencimiento').checked) {
        headers += `<th>Vencimiento</th>`
    }
    if (document.getElementById('diasrestantes').checked) {
        headers += `<th>Días restantes</th>`
    }
    if (document.getElementById('divisa').checked) {
        headers += `<th>Divisa</th>`
    }
    if (document.getElementById('totalgeneral').checked) {
        headers += `<th>Total general</th>`
    }

    //Se agrega el cierre de la cabecera de la tabla
    headers += `</tr>`;

    // Variable para almacenar registros de 5 en 5 del dataset 
    let data = '';
    // Variable para llevar un control de la cantidad de registros agregados
    let contador = 0;
    //Se abre la etiqueta del cuerpo de la tabla
    dataset.map(function (row) {
        // Definimos la estructura de las filas con los campos del dataset 
        data += `<tr>`;

        if (document.getElementById('responsable').checked) {
            data += `<td>${row.responsable}</td>`;
        }
        if (document.getElementById('sociedad').checked) {
            data += `<td>${row.sociedad}</td>`
        }
        if (document.getElementById('usuario').checked) {
            data += `<td>${row.usuario}</td>`
        }
        if (document.getElementById('codigo').checked) {
            data += `<td>${row.codigo}</td>`
        }
        if (document.getElementById('factura').checked) {
            data += `<td>${row.factura}</td>`
        }
        if (document.getElementById('asignacion').checked) {
            data += `<td>${row.asignacion}</td>`
        }
        if (document.getElementById('fechacontable').checked) {
            data += `<td>${row.fechacontable}</td>`
        }
        if (document.getElementById('clase').checked) {
            data += `<td>${row.clase}</td>`
        }
        if (document.getElementById('vencimiento').checked) {
            data += `<td>${row.vencimiento}</td>`
        }
        if (document.getElementById('diasrestantes').checked) {
            data += `<td>${row.diasrestantes}</td>`
        }
        if (document.getElementById('divisa').checked) {
            data += `<td>${row.divisa}</td>`
        }
        if (document.getElementById('totalgeneral').checked) {
            data += `<td>${row.totalgeneral}</td>`
        }

        data += `</tr>`
        // Agregamos uno al contador por la fila agregada anteriormente al data
        contador = contador + 1;
        //Verificamos si el contador es igual a 5 eso significa que la data contiene 5 filas
        if (contador == 9) {
            // Reseteamos el contador a 0
            contador = 0;
            // Agregamos el contenido de data al arreglo que contiene los datos content[]
            content.push(data);
            // Vaciamos el contenido de data para volverlo a llenar
            data = '';
            // Agregamos una posicion dentro del arreglo debido a que se agrego un nuevo elemento
            posiciones = posiciones + 1;
        }
    });
    //Se cierra la etiqueta del cuerpo de la tabla
    // Verificamos si el ultimo retorno de datos no esta vacio en caso de estarlo no se agrega a la paginacion
    if (data != '') {
        // Agregamos el contenido el contenido al arreglo en caso de no estar vacio
        content.push(data);
    }
    else {
        // Se resta una posicion ya que no se agrego el contenido final por estar vacio
        posiciones = posiciones - 1;
    }
    // Se llama la funcion fillPagination que carga los datos del arreglo en la tabla 
    fillPagination(content[0]);
    // Se llama la funcion para generar la paginacion segun el numero de registros obtenidos
    generatePagination();
    //Se agrega el contenido a la tabla mediante su id
    document.getElementById('theaders').innerHTML = headers;
    document.getElementById('tbody-rows').innerHTML = data;
    console.log(headers)
}

// Funcion para abrir el modal
const openCustomDialog = () => {
    $('#modal-form').modal('show');
}


