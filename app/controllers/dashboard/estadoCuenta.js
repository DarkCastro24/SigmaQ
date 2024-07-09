// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ESTADO = '../../app/api/dashboard/estadoCuenta.php?action=';
const API_ADMINS = '../../app/api/dashboard/usuarios.php?action=readAll';
const API_CLIENTES = '../../app/api/dashboard/clientes.php?action=readAll';
const API_SOCIEDADES = '../../app/api/dashboard/sociedades.php?action=readAll';
const API_DIVISAS = '../../app/api/dashboard/divisas.php?action=readAll';

// Método manejador de eventos que se ejecutara cuando cargue la pagina
document.addEventListener('DOMContentLoaded', function () {
    // Se manda a llamar funcion para tener la opcion de eliminar todos los registros de la base de datos
    opcionesUsuario();
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_ESTADO);
});

// Función para cargar la seccion de botones en base al tipo de usuario que inicio sesion
const opcionesUsuario = () => {
    // Obtenemos el valor del tipo de usuario del panel lateral
    let tipo = document.getElementById("tipoUsuario").value;
    let contenido = '';
    // Comparamos si el usuario es root 
    if (tipo == 'Root') {
        // Cargamos el contenido correspondiente a los usuarios root
        contenido += `
            <a class="dropdown-item" href="#">Borrar registros</a>
            <div class="container">
            <button class="centrarBoton2  btn btn-outline-info">
                <i class="material-icons" data-toggle="tooltip" title="Limpiar base">report</i></button>
            </button>
            </div>`;
    } 
    // Agregamos el codigo al contenedor HTML
    document.getElementById('seccionAgregar').innerHTML = contenido;
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('delete-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmClean(API_ESTADO);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
const fillTable = (dataset) => {
    // Variable para almacenar registros de 5 en 5 del dataset 
    let data = '';
    // Variable para llevar un control de la cantidad de registros agregados
    let contador = 0;
    // Obtenemos los datos de la consulta realizada en la base (dataset)
    dataset.map(function (row) {
        // Variables para almacernar los nombres de los iconos del los botones y del estado del usuario en la tabla
        let toggleEnabledIcon = '';
        let iconToolTip = '';
        let metodo = '';
        let estado = '';
        // Se verifica el estado del estado de cuenta
        if (row.estado) {
            // Cuando el registro esté habilitado
            iconToolTip = 'Deshabilitar'
            toggleEnabledIcon = 'block';
            metodo = 'openDeleteDialog';
            estado = 'visibility';
        } else {
            // Cuando el registro esté deshabilitado
            iconToolTip = 'Habilitar'
            toggleEnabledIcon = 'check_circle_outline'
            metodo = 'openActivateDialog';
            estado = 'visibility_off';
        }
        data += `
            <tr>
                <td>${row.responsable}</td>
                <td>${row.sociedad}</td>
                <td>${row.usuario}</td>
                <td>${row.codigo}</td>
                <td>${row.factura}</td>
                <td>${row.asignacion}</td>
                <td>${row.fechacontable}</td>
                <td>${row.clase}</td>
                <td>${row.vencimiento}</td>
                <td>${row.diasrestantes}</td>
                <td>${row.divisa}</td>
                <td>${row.totalgeneral}</td>     
                <td><a href="#"><i class="material-icons" data-toggle="tooltip"">${estado}</i></a></th> 
                <td>
                    <a href="#" onclick="openUpdateDialog(${row.idestadocuenta})" class="edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                    <a href="#" onclick="${metodo}(${row.idestadocuenta})" class="delete"><i class="material-icons" data-toggle="tooltip" title="${iconToolTip}">${toggleEnabledIcon}</i></a>
                </td>
                <td>
                    <a href="../../app/reports/dashboard/estadoCuentaPorCliente.php?id=${row.codigoadmin}" target="_blank"><i class="material-icons" data-toggle="tooltip" title="Generar reporte de estado de cuenta por este cliente">assignment_ind</i></a>
                    <a href="#" onclick="parameterChart(${row.cliente})"><i class="material-icons" data-toggle="tooltip" title="Generar gráfico de % de cumplimiento de un índice">insert_chart</i></a>
                </td>
            </tr>
        `;
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

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_ESTADO, 'search-form');
});


// Función para abrir el Form al momento de crear un registro
const openCreateDialog = () => {
    //Se restauran los elementos del form
    document.getElementById('save-form').reset();
    //Se abre el form
    $('#staticBackdrop').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Ingresar estado de cuenta'
    // Se llama a la function para llenar los Selects
    fillSelect(API_ADMINS, 'responsable', null);
    fillSelect(API_CLIENTES, 'cliente', null);
    fillSelect(API_SOCIEDADES, 'sociedad', null);
    fillSelect(API_DIVISAS, 'divisa', null);
}

// Función para preparar el formulario al momento de modificar un registro.
const openUpdateDialog = (id) => {
    // Reseteamos el valor de los campos del modal
    document.getElementById('save-form').reset();
    // Asignamos el valor del parametro id al campo del id del modal
    document.getElementById('idestado').value = id;
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Actualizar estado de cuenta';
    const data = new FormData();
    data.append('id', id);
    // Hacemos una solicitud enviando como parametro la API y el nombre del case readOne para cargar los datos de un registro
    fetch(API_ESTADO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Luego se compara si la respuesta de la API fue satisfactoria o no
        if (request.ok) {
            // En caso que la respuesta de la API sea satisfactoria se ejecuta el siguiente codigo
            request.json().then(function (response) {
                // En caso de encontrarse registros se imprimen los resultados en los inputs del modal
                if (response.status) {
                    // Colocamos el nombre de los inputs y los igualamos al valor de los campos del dataset 
                    document.getElementById('idestado').value = response.dataset[0].idestadocuenta;
                    fillSelect(API_ADMINS, 'responsable', response.dataset[0].codigoadmin);
                    fillSelect(API_CLIENTES, 'cliente', response.dataset[0].codigocliente);
                    fillSelect(API_SOCIEDADES, 'sociedad', response.dataset[0].idsociedad);
                    fillSelect(API_DIVISAS, 'divisa', response.dataset[0].iddivisa);
                    // document.getElementById('responsable').value = response.dataset.responsable;
                    // document.getElementById('sociedad').value = response.dataset.sociedad;
                    // document.getElementById('usuario').value = response.dataset.usuario;
                    document.getElementById('codigo').value = response.dataset[0].codigo;
                    document.getElementById('factura').value = response.dataset[0].factura;
                    document.getElementById('asignacion').value = response.dataset[0].asignacion;
                    document.getElementById('fechacontable').value = response.dataset[0].fechacontable;
                    document.getElementById('clase').value = response.dataset[0].clase;
                    document.getElementById('vencimiento').value = response.dataset[0].vencimiento;
                    // document.getElementById('diasrestantes').value = response.dataset.diasrestantes;
                    // document.getElementById('divisa').value = response.dataset.divisa;
                    document.getElementById('total').value = response.dataset[0].totalgeneral;
                } else {
                    // En caso de fallar se muestra el mensaje de error 
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
        // En ocurrir un error se muestra en la consola 
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para definir si el metodo a ejecutar es guardar o actualizar.
const saveData = () => {
    // Se define atributo que almacenara la accion a realizar
    let action = '';
    // Se comprara el valor del input id 
    if (document.getElementById('idestado').value) {
        // En caso que exista se actualiza
        action = 'update';
    } else {
        // En caso que no se crea 
        action = 'create';
    }
    // Ejecutamos la funcion saveRow de components y enviamos como parametro la API la accion a realizar el form para obtener los datos y el modal
    // console.log(action);
    saveRow(API_ESTADO, action, 'save-form', 'staticBackdrop');
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_ESTADO);
}

// Función para desactivar el registro
const openDeleteDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmDesactivate(API_ESTADO, data);
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_ESTADO);
}

// Función para establecer el registro a reactivar y abrir una caja de dialogo de confirmación.
const openActivateDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmActivate(API_ESTADO, data);
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_ESTADO);
}

//Función para cargar el gráfico de total general mensuales de los clientes
const parameterChart = id => {
    //Vaciamos el contenido del chart
    resetChart('chart-container');
    //Creamos una variable en la cuál crearemos nuestro canvas para el gráfico
    let content = '<canvas id="totalGeneralMensual"></canvas>';
    //Se agrega el canvas al contenedor de la gráfica
    document.getElementById('chart-container').innerHTML = content;
    //Abrimos el modal
    $('#chart-modal').modal('show');
    // Colocamos el titulo del modal 
    document.getElementById('title-chart').textContent = 'Sumatoria de Total General Mensual';
    // Creamos un form data para enviar el id 
    const data = new FormData();
    data.append('cliente', id);
    //Hacemos la petición a la API usando el id del cliente como parámetro
    fetch(API_ESTADO + 'totalGeneralMensual', {
        method: 'post',
        body: data
    }).then(request => {
        // Luego se compara si la respuesta de la API fue satisfactoria o no
        if (request.ok) {
            return request.json();
        } else {
            // En ocurrir un error se muestra en la consola 
            console.log(request.status + ' ' + request.statusText);
        }
    }).then(response => {
        //Se evalua que la respuesta sea correcta
        if (response.status) {
            //Se declaran los arreglos para almacenar la información
            let meses = [];
            let totalGeneral = [];
            //Se recorren los datos que retorno la API
            response.dataset.map(row => {
                //Se almacenan los valores en los arreglos
                meses.push(months[row.mes - 1]);
                totalGeneral.push(row.total_mensual);
            })
            // Se llama a la función que genera y muestra una gráfica de pastel en porcentajes. Se encuentra en el archivo components.js
            lineGraph('totalGeneralMensual', meses, totalGeneral, 'Total general mensual', 'Sumatorial de total general mensual en USD($)');
        } else {
            document.getElementById('totalGeneralMensual').remove();
            console.log(response.exception);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('report-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Abrimos el reporte en una pestaña nueva
    window.open('../../app/reports/dashboard/estadoCuenta.php');
});
