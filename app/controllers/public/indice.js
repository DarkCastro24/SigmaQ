// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ADMINS = '../../app/api/dashboard/usuarios.php?action=readAll';
const API_CLIENTES = '../../app/api/dashboard/clientes.php?action=readAll';
const API_INDICES = '../../app/api/public/indice.php?action=';

// Función manejadora de eventos, para ejecutar justo cuando termine de cardar.
document.addEventListener('DOMContentLoaded', () => {
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_INDICES);
    // Registramos la accion realizada por el cliente dentro del historial de acciones
    updateHistorial (API_HISTORIAL, 'Consulto sus indices de entrega');
})

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_INDICES, 'search-form');
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
    if (document.getElementById('organizacion').checked) {
        headers += `<th>Organización</th>`
    }
    if (document.getElementById('indice').checked) {
        headers += `<th>Índice</th>`
    }
    if (document.getElementById('compromisos').checked) {
        headers += `<th>Compromisos</th>`
    }
    if (document.getElementById('cumplidos').checked) {
        headers += `<th>Cumplidos</th>`
    }
    if (document.getElementById('nocumplidos').checked) {
        headers += `<th>No Cumplidos</th>`
    }
    if (document.getElementById('noconsiderados').checked) {
        headers += `<th>No Considerados</th>`
    }
    if (document.getElementById('incumnoentregados').checked) {
        headers += `<th>% incum no entregados</th>`
    }
    if (document.getElementById('incumporcalidad').checked) {
        headers += `<th>% incum calidad</th>`
    }
    if (document.getElementById('incumporfecha').checked) {
        headers += `<th>% incum fecha</th>`
    }
    if (document.getElementById('incumporcantidad').checked) {
        headers += `<th>% incum cantidad</th>`
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
        if (document.getElementById('organizacion').checked) {
            data += `<td>${row.organizacion}</td>`;
        }
        if (document.getElementById('indice').checked) {
            data += `<td>${row.indice}</td>`
        }
        if (document.getElementById('compromisos').checked) {
            data += `<td>${row.totalcompromiso}</td>`
        }
        if (document.getElementById('cumplidos').checked) {
            data += `<td>${row.cumplidos}</td>`
        }
        if (document.getElementById('nocumplidos').checked) {
            data += `<td>${row.nocumplidos}</td>`
        }
        if (document.getElementById('noconsiderados').checked) {
            data += `<td>${row.noconsiderados}</td>`
        }
        if (document.getElementById('incumnoentregados').checked) {
            data += `<td>${row.incumnoentregados}</td>`
        }
        if (document.getElementById('incumporcalidad').checked) {
            data += `<td>${row.incumporcalidad}</td>`
        }
        if (document.getElementById('incumporfecha').checked) {
            data += `<td>${row.incumporfecha}</td>`
        }
        if (document.getElementById('incumporcantidad').checked) {
            data += `<td>${row.incumporcantidad}</td>`
        }

        data += `</tr>`
        // Agregamos uno al contador por la fila agregada anteriormente al data
        contador = contador + 1;
        //Verificamos si el contador es igual a 5 eso significa que la data contiene 5 filas
        if (contador == 4) {
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

const openCustomDialog = () => {
    $('#modal-form').modal('show');
}
