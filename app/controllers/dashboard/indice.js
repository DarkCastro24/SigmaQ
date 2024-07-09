// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ADMINS = '../../app/api/dashboard/usuarios.php?action=readAll';
const API_CLIENTES = '../../app/api/dashboard/clientes.php?action=readAll';
const API_INDICES = '../../app/api/dashboard/indiceEntrega.php?action=';

// Función manejadora de eventos, para ejecutar justo cuando termine de cardar.
document.addEventListener('DOMContentLoaded', () => {
    // Se manda a llamar funcion para tener la opcion de eliminar todos los registros de la base de datos
    opcionesUsuario();
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_INDICES);
})

// Función para cargar la seccion de botones en base al tipo de usuario que inicio sesion
const opcionesUsuario = () => {
    // Obtenemos el valor del tipo de usuario del panel lateral
    let tipo = document.getElementById("tipoUsuario").value;
    let contenido = '';
    // Comparamos si el usuario es root
    if (tipo == 'Root') {
        // Cargamos el contenido correspondiente a los usuarios root
        contenido += `
            <div class="col-sm-6">
                <a class="btn btn-info btn-md espaciolateral" onclick="openCreateDialog()" role="button" aria-disabled="true">Registrar Índice</button></a>							
            </div>
            <div class="col-sm-4">
                <button class="centrarBoton btn btn-outline-info my-2 my-sm-0">
                    <i class="material-icons" data-toggle="tooltip" title="Limpiar base">report</i></button>
                </button>
            </div>
            `;
    } else {
        // Cargamos el contenido para los usuarios admins
        contenido += `
            <div class="col-sm-4"></div>
            <div class="col-sm-6">
                <a class="btn btn-info btn-md espaciolateral" onclick="openCreateDialog()" role="button" aria-disabled="true">Registrar Índice</button></a>							
            </div>
            `;
    }
    document.getElementById('seccionAgregar').innerHTML = contenido;
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
const fillTable = (dataset) => {
    // Variable para almacenar registros de 5 en 5 del dataset 
    let data = '';
    // Variable para llevar un control de la cantidad de registros agregados
    let contador = 0;
    dataset.map(function (row) {
        // Variables para almacernar los nombres de los iconos del los botones y del estado del usuario en la tabla
        let toggleEnabledIcon = '';
        let iconToolTip = '';
        let metodo = '';
        // Definimos el icono a mostrar en la tabla segun el estado del registro
        if (row.estado) {
            // Cuando el registro esté habilitado
            iconToolTip = 'Deshabilitar'
            toggleEnabledIcon = 'block'
            metodo = 'openDeleteDialog';
        } else {
            // Cuando el registro esté deshabilitado
            iconToolTip = 'Habilitar'
            toggleEnabledIcon = 'check_circle_outline'
            metodo = 'openActivateDialog';
        }
        data += `
                <tr>
                    <td>${row.usuario}</th>
                    <td>${row.organizacion}</th>
                    <td>${row.indice}</th>
                    <td>${row.totalcompromiso}</th>
                    <td>${row.cumplidos}</th>
                    <td>${row.nocumplidos}</th>
                    <td>${row.noconsiderados}</th>
                    <td>${row.incumnoentregados}</th>
                    <td>${row.incumporfecha}</th>
                    <td>${row.incumporcalidad}</th>
                    <td>${row.incumporcantidad}</th>
                    <td>
                        <a href="#" onclick="openUpdateDialog(${row.idindice})" class="edit"><i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                        <a href="#" onclick="${metodo}(${row.idindice})" class="delete"><i class="material-icons" data-toggle="tooltip" title="${iconToolTip}">${toggleEnabledIcon}</i></a>
                    </td>
                    <td>
                        <a href="#" onclick="parameterChart(${row.idindice})"><i class="material-icons" data-toggle="tooltip" title="Generar gráfico">insert_chart</i></a>
                    </td>
                </tr>
            `;
        // Agregamos uno al contador por la fila agregada anteriormente al data
        contador = contador + 1;
        //Verificamos si el contador es igual a 8 eso significa que la data contiene 8 filas
        if (contador == 6) {
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
    // Se verifica si el contenido que se imprimio en la tabla no estaba vacio
    if (content[0] != null) {
        // Se llama la funcion para generar la paginacion segun el numero de registros obtenidos
        generatePagination();
    }

}

// Función para guardar los registros del form
const saveData = () => {
    // Se define atributo que almacenara la accion a realizar
    let action = '';
    // Se comprara el valor del input id 
    if (document.getElementById('idindice').value) {
        action = 'update'; // En caso que exista se actualiza 
    } else {
        action = 'create'; // En caso que no se crea 
    }
    // Ejecutamos la funcion saveRow de components y enviamos como parametro la API la accion a realizar el form para obtener los datos y el modal
    saveRow(API_INDICES, action, 'save-form', 'modal-form');
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_INDICES);
}

//Función para abrir el mensaje de confirmación para deshabilitar un registro
const openDeleteDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmDesactivate(API_INDICES, data);
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_INDICES);
}

// Función para establecer el registro a reactivar y abrir una caja de dialogo de confirmación.
const openActivateDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmActivate(API_INDICES, data);
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_INDICES);
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_INDICES, 'search-form');
});

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('delete-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmClean(API_INDICES);
});

// Función para abrir el Form al momento de crear un registro
const openCreateDialog = () => {
    //Se restauran los elementos del form
    document.getElementById('save-form').reset();
    //Se abre el form
    $('#modal-form').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Registrar índice de entrega'
    // Se llama a la function para llenar los Selects
    fillSelect(API_ADMINS, 'responsable', null);
    fillSelect(API_CLIENTES, 'cliente', null);
}

// Función para preparar el formulario al momento de modificar un registro.
const openUpdateDialog = (id) => {
    // Reseteamos el valor de los campos del modal
    document.getElementById('save-form').reset();
    //Se abre el form
    $('#modal-form').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Registrar índice de entrega'
    // Asignamos el valor del parametro id al campo del id del modal
    document.getElementById('idindice').value = id;
    const data = new FormData();
    data.append('id', id);
    // Hacemos una solicitud enviando como parametro la API y el nombre del case readOne para cargar los datos de un registro
    fetch(API_INDICES + 'readOne', {
        method: 'post',
        body: data
    }).then(request => {
        // Luego se compara si la respuesta de la API fue satisfactoria o no
        if (request.ok) {
            // console.log(request.text())
            return request.json()
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
        // En ocurrir un error se muestra en la consola 
    }).then(response => {
        // En caso de encontrarse registros se imprimen los resultados en los inputs del modal
        if (response.status) {
            // Colocamos el nombre de los inpus y los igualamos al valor de los campos del dataset 
            document.getElementById('idindice').value = response.dataset[0].idindice;
            fillSelect(API_ADMINS, 'responsable', response.dataset[0].codigoadmin);
            fillSelect(API_CLIENTES, 'cliente', response.dataset[0].codigocliente);
            document.getElementById('organizacion').value = response.dataset[0].organizacion
            document.getElementById('indice').value = response.dataset[0].indice;
            document.getElementById('totalcompromiso').value = response.dataset[0].totalcompromiso;
            document.getElementById('cumplidos').value = response.dataset[0].cumplidos;
            document.getElementById('nocumplidos').value = response.dataset[0].nocumplidos;
            document.getElementById('noconsiderados').value = response.dataset[0].noconsiderados;
            document.getElementById('incumnoentregados').value = response.dataset[0].incumnoentregados;
            document.getElementById('incumporcalidad').value = response.dataset[0].incumporcalidad;
            document.getElementById('incumporfecha').value = response.dataset[0].incumporfecha;
            document.getElementById('incumporcantidad').value = response.dataset[0].incumporcantidad;
        } else {
            // En caso de fallar se muestra el mensaje de error 
            sweetAlert(2, response.exception, null);
        }
    }
    ).catch(function (error) {
        console.log(error);
    });
}

//Función para cargar el gráfico de total general mensuales de los clientes
const parameterChart = id => {
    //Vaciamos el contenido del chart
    resetChart('chart-container');
    //Creamos una variable en la cuál crearemos nuestro canvas para el gráfico
    const content = '<canvas id="porcentajeCumplidos"></canvas>';
    //Se agrega el canvas al contenedor de la gráfica
    document.getElementById('chart-container').innerHTML = content;
    //Abrimos el modal
    $('#chart-modal').modal('show');
    // Colocamos el titulo del modal 
    document.getElementById('title-chart').textContent = 'Porcentaje de cumplimiento del índice';
    // Creamos un form data para enviar el id 
    const data = new FormData();
    data.append('id_indice', id);
    //Hacemos la petición a la API usando el id del cliente como parámetro
    fetch(API_INDICES + 'porcentajeCumplimientoIndice', {
        method: 'post',
        body: data
    }).then(request => {
        // Luego se compara si la respuesta de la API fue satisfactoria o no
        if (request.ok) {
            return request.json()
        } else {
            // En ocurrir un error se muestra en la consola 
            console.log(request.status + ' ' + request.statusText);
        }
    }).then(response => {
        //Se evalua que la respuesta sea correcta
        if (response.status) {
            //Se declaran los arreglos para almacenar la información
            let datos = ['No Cumplidos', 'Cumplidos', 'No Considerados'];
            let porcentajes = [response.dataset.nocumplidos, response.dataset.cumplidos, response.dataset.noconsiderados];
            // Se llama a la función que genera y muestra una gráfica de pastel en porcentajes. Se encuentra en el archivo components.js
            pieGraph('porcentajeCumplidos', datos, porcentajes, 'Porcentaje de cumplimiento de los compromisos del índice (%)');
        } else {
            document.getElementById('totalGeneralMensual').remove();
            console.log(response.exception);
        }
    }).catch(function (error) {
        console.log(error);
    });
}