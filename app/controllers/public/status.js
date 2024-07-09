// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_PEDIDOS = '../../app/api/public/statusPedidos.php?action=';

// Función manejadora de eventos, para ejecutar justo cuando termine de cardar.
document.addEventListener('DOMContentLoaded', () => {
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_PEDIDOS);
    // Registramos la accion realizada por el cliente dentro del historial de acciones
    updateHistorial (API_HISTORIAL, 'Consulto su estatus de pedidos');
})

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_PEDIDOS, 'search-form');
});


// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
const fillTable = (dataset) =>{ 
    //Se oculta el modal de la personalización
    $('#modal-form').modal('hide');
    //Se crea la fila de los headers de la tabla
    let headers = `<tr>`;
    //Se agregan los headers si está seleccionado en la tabla
    if(document.getElementById('responsable').checked) {
        headers += `<th>Responsable</th>`
    }
    if(document.getElementById('pos').checked) {
        headers += `<th>Pos</th>`
    }
    if(document.getElementById('oc').checked) {
        headers += `<th>OC</th>`
    }
    if(document.getElementById('solicitada').checked) {
        headers += `<th>Solicitada</th>`
    }
    if(document.getElementById('codigo').checked) {
        headers += `<th>Código</th>`
    }
    if(document.getElementById('enviada').checked) {
        headers += `<th>Enviada</th>`
    }
    if(document.getElementById('fecharegistrado').checked) {
        headers += `<th>Fecha registrado</th>`
    }
    if(document.getElementById('fechaentrega').checked) {
        headers += `<th>Fecha de entrega</th>`
    }
    if(document.getElementById('fechaconfirmacion').checked) {
        headers += `<th>Fecha de confirmación</th>`
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
        data+= `<tr>`;
        if(document.getElementById('responsable').checked) {
            data += `<td>${row.nombre_responsable}</td>`
        }
        if(document.getElementById('pos').checked) {
            data += `<td>${row.pos}</td>`
        }
        if(document.getElementById('oc').checked) {
            data += `<td>${row.oc}</td>`
        }
        if(document.getElementById('solicitada').checked) {
            data += ` <td>${row.cantidadsolicitada}</td>`
        }
        if(document.getElementById('codigo').checked) {
            data += `<td>${row.codigo}</td>`
        }
        if(document.getElementById('enviada').checked) {
            data += `<td>${row.cantidadenviada}</td>`
        }
        if(document.getElementById('fecharegistrado').checked) {
            data += `<td>${row.fecharegistro}</td>`
        }
        if(document.getElementById('fechaentrega').checked) {
            data += `<td>${row.fechaentregada}</td>`
        }
        if(document.getElementById('fechaconfirmacion').checked) {
            data += `<td>${row.fechaconfirmadaenvio}</td>`
        }    
        //Se cierra la etiqueta de la fila
        data+=`</tr>`
        // Agregamos uno al contador por la fila agregada anteriormente al data
        contador = contador + 1;
        //Verificamos si el contador es igual a 5 eso significa que la data contiene 5 filas
        if (contador == 5) {
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
    else{
        // Se resta una posicion ya que no se agrego el contenido final por estar vacio
        posiciones = posiciones -1;
    }
    // Se llama la funcion fillPagination que carga los datos del arreglo en la tabla 
    fillPagination(content[0]);
    // Se llama la funcion para generar la paginacion segun el numero de registros obtenidos
    generatePagination();
    //Se agrega el contenido a la tabla mediante su id
    document.getElementById('theaders').innerHTML = headers;
    document.getElementById('tbody-rows').innerHTML = data;
}

// Función para preparar el formulario al momento de modificar un registro.
const openView = (id) => {  
    // Reseteamos el valor de los campos del modal
    document.getElementById('save-form').reset();
    //Se abre el form
    $('#modal-form').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Ver más'
    // Asignamos el valor del parametro id al campo del id del modal
    document.getElementById('idpedido').value = id;
    //Se establece ReadOnly el campo del codigo
    document.getElementById("codigo").readOnly = true;
    //Se establece ReadOnly el campo del cliente
    document.getElementById("cliente").setAttribute('disabled',false)
    const data = new FormData();
    data.append('id', id);
}

const openCustomDialog = () => {
    $('#modal-form').modal('show');
}