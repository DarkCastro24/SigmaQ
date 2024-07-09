// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ADMINS = '../../app/api/dashboard/usuarios.php?action=readAll';
const API_CLIENTES = '../../app/api/dashboard/clientes.php?action=readAll';
const API_PEDIDOS = '../../app/api/dashboard/pedidos.php?action=';

// Función manejadora de eventos, para ejecutar justo cuando termine de cardar.
document.addEventListener('DOMContentLoaded', () => {
    // Se manda a llamar funcion para tener la opcion de eliminar todos los registros de la base de datos
    opcionesUsuario();
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_PEDIDOS);
})

// Función para cargar la seccion de botones en base al tipo de usuario que inicio sesion
const opcionesUsuario = () => {
    let tipo = document.getElementById("tipoUsuario").value;
    let contenido = '';
    if (tipo == 'Root') {
        contenido += `
        <form method="post" id="delete-form"><br>
        <a class="dropdown-item" href="#">Borrar registros</a>
            <div class="container">
                <div class="col-sm-3">
                    <button id="limpiar-tabla" class="centrarBoton btn btn-outline-info">
                        <i class="material-icons" data-toggle="tooltip" title="Limpiar base">report</i></button>
                    </button>
                </div>
            </div>
        </form>
            `;
    } 
    document.getElementById('seccionAgregar').innerHTML = contenido;

    //Agregando los controladores de evento para los botones del mantenimiento
    if (tipo == 'Root') {
        // Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
        document.getElementById('limpiar-tabla').addEventListener('click', function (event) {
            // Evitamos que la pagina se refresque 
            event.preventDefault();
            // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
            confirmClean(API_PEDIDOS);
        });
        document.getElementById('mejoresClientes').addEventListener('click', event => {
            // Evitamos que la pagina se refresque 
            event.preventDefault();
            drawChart(2)
        })
        document.getElementById('enviosMensuales').addEventListener('click', event => {
            // Evitamos que la pagina se refresque 
            event.preventDefault();
            drawChart(1)
        })
    }
}

// Función para abrir el Form al momento de crear un registro
const openCreateDialog = () => {
    //Se restauran los elementos del form
    document.getElementById('save-form').reset();
    //Se abre el form
    $('#modal-form').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Registrar Pedido'
    //Asignamos la fecha actual al campo del formulario
    document.getElementById('fecharegistro').value = new Date().toDateInputValue();
    //Asignamos la fecha mínima para las fechas
    document.getElementById('fechaentrega').setAttribute('min', new Date().toDateInputValue())
    document.getElementById('fechaconfirmadaenvio').setAttribute('min', new Date().toDateInputValue())
    // Se llama a la function para llenar los Selects
    fillSelect(API_ADMINS, 'responsable', null);
    fillSelect(API_CLIENTES, 'cliente', null);
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
const fillTable = (dataset) => {
    // Variable para almacenar registros de 5 en 5 del dataset 
    let data = '';
    // Variable para llevar un control de la cantidad de registros agregados
    let contador = 0;
    // Obtenemos los valores del retorno de la consulta de la base de datos (dataset)
    dataset.map(function (row) {
        // Declaracion de variables para almacenar los nombres de iconos y metodo
        let toggleEnabledIcon = '';
        let iconToolTip = '';
        let metodo = '';
        let estado = '';
        // Se verifica si el estado es activo o inactivo
        if (row.estado) {
            //Cuando el registro esté habilitado
            iconToolTip = 'Deshabilitar'
            toggleEnabledIcon = 'block'
            metodo = 'openDeleteDialog';
            estado = 'visibility';
        } else {
            // Cuando este deshabilitado
            iconToolTip = 'Habilitar'
            toggleEnabledIcon = 'check_circle_outline'
            metodo = 'openActivateDialog';
            estado = 'visibility_off';
        }
        data += `
            <tr>
                <td>${row.usuario}</th>
                <td>${row.pos}</th>
                <td>${row.oc}</th>
                <td>${row.cantidadsolicitada}</th>
                <td>${row.codigo}</th>
                <td>${row.cantidadenviada}</th>
                <td>${row.fecharegistro}</th>
                <td>${row.fechaentregada}</th>
                <td>${row.fechaconfirmadaenvio}</th>
                <td><a href="#"><i class="material-icons" data-toggle="tooltip"">${estado}</i></a></th>
                <td>
                    <a href="../../app/reports/dashboard/PedidosPorCliente.php?id=${row.cliente}" target="_blank"><i class="material-icons" data-toggle="tooltip" title="Generar reporte de estatus de pedido por este cliente">assignment_ind</i></a>
                </td>
                <td>
                    <a href="#" onclick="openUpdateDialog(${row.idpedido})" <i class="edit"><i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#" onclick="${metodo}(${row.idpedido})" class="delete"><i class="material-icons" data-toggle="tooltip" title="${iconToolTip}">${toggleEnabledIcon}</i></a>
                </td>
            </tr>
        `;
        // Agregamos uno al contador por la fila agregada anteriormente al data
        contador = contador + 1;
        //Verificamos si el contador es igual a 5 eso significa que la data contiene 5 filas
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

//Función para obtener la fecha actual
Date.prototype.toDateInputValue = (function () {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0, 10);
});

// Función manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_PEDIDOS, 'search-form');
});

//Función para abrir el mensaje de confirmación para deshabilitar un registro
const openDeleteDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmDesactivate(API_PEDIDOS, data);
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_PEDIDOS);
}

// Función para establecer el registro a reactivar y abrir una caja de dialogo de confirmación.
const openActivateDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmActivate(API_PEDIDOS, data);
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_PEDIDOS);
}

// Función para guardar los registros del form
const saveData = () => {
    // Se define atributo que almacenara la accion a realizar
    let action = '';
    // Se comprara el valor del input id 
    if (document.getElementById('idpedido').value) {
        action = 'update'; // En caso que exista se actualiza 
    } else {
        action = 'create'; // En caso que no se crea 
    }
    // Ejecutamos la funcion saveRow de components y enviamos como parametro la API la accion a realizar el form para obtener los datos y el modal
    saveRow(API_PEDIDOS, action, 'save-form', 'modal-form');
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_PEDIDOS);
}

// Función para preparar el formulario al momento de modificar un registro.
const openUpdateDialog = (id) => {
    // Reseteamos el valor de los campos del modal
    document.getElementById('save-form').reset();
    //Se abre el form
    $('#modal-form').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Modificar pedido'
    // Asignamos el valor del parametro id al campo del id del modal
    document.getElementById('idpedido').value = id;
    //Se establece ReadOnly el campo del codigo
    document.getElementById("codigo").readOnly = true;
    //Se establece ReadOnly el campo del cliente
    document.getElementById("cliente").setAttribute('disabled', false)

    const data = new FormData();
    data.append('id', id);
    // Hacemos una solicitud enviando como parametro la API y el nombre del case readOne para cargar los datos de un registro
    fetch(API_PEDIDOS + 'readOne', {
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
            document.getElementById('idpedido').value = response.dataset[0].idpedido;
            fillSelect(API_ADMINS, 'responsable', response.dataset[0].codigoadmin);
            fillSelect(API_CLIENTES, 'cliente', response.dataset[0].codigocliente);
            document.getElementById('oc').value = response.dataset[0].oc
            document.getElementById('pos').value = response.dataset[0].pos;
            document.getElementById('codigo').value = response.dataset[0].codigo;
            document.getElementById('cantidadsolicitada').value = response.dataset[0].cantidadsolicitada;
            document.getElementById('descripcion').value = response.dataset[0].descripcion;
            document.getElementById('cantidadenviada').value = response.dataset[0].cantidadenviada;
            document.getElementById('fechaentrega').value = response.dataset[0].fechaentregada;
            document.getElementById('fechaconfirmadaenvio').value = response.dataset[0].fechaconfirmadaenvio;
            document.getElementById('comentarios').value = response.dataset[0].comentarios;
            document.getElementById('fecharegistro').value = response.dataset[0].fecharegistro;
        } else {
            // En caso de fallar se muestra el mensaje de error 
            sweetAlert(2, response.exception, null);
        }
    }
    ).catch(function (error) {
        console.log(error);
    });
}

//Función para cargar el gráfico de los productos enviados mensuales o los mejores clientes
const drawChart = type => {
    //Vaciamos el contenido del chart
    resetChart('chart-container');
    //Creamos una variable en la cuál crearemos nuestro canvas para el gráfico
    const content = '<canvas id="orders-chart"></canvas>';
    //Se agrega el canvas al contenedor de la gráfica
    document.getElementById('chart-container').innerHTML = content;
    //Abrimos el modal
    $('#chart-modal').modal('show');
    // Colocamos el titulo del modal 
    const titulo = type === 1 ? 'Cantidad de productos enviados por mes' : 'Top 5 Usuarios que han realizado más pedidos';
    document.getElementById('title-chart').textContent = titulo;
    //Definimos que acción se realizará en la API
    const action = type === 1 ? 'cantidadEnviadaMensual' : 'clientesTop'
    //Realizamos la petición a la API
    fetch(API_PEDIDOS + action)
        .then(request => {
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
                let cualitativas = [];
                let cuantitativas = [];
                //Se llama a la función que genera y muestra el gráfico dependiendo de la acción ejecutada
                if (type === 1) {
                    //Se recorren los datos que retorno la API
                    response.dataset.map(row => {
                        //Se almacenan los valores en los arreglos
                        cualitativas.push(months[row.mes - 1]);
                        cuantitativas.push(row.cantidadmensual);
                    })
                    lineGraph('orders-chart', cualitativas, cuantitativas, 'Cantidad enviada en el mes', 'Total de productos enviados en los últimos 12 meses');
                } else {
                    //Se recorren los datos que retorno la API
                    response.dataset.map(row => {
                        //Se almacenan los valores en los arreglos
                        cualitativas.push(row.usuario);
                        cuantitativas.push(row.pedidos);
                    })
                    barGraph('orders-chart', cualitativas, cuantitativas, 'Cantidad de pedidos realizados', 'Top 5 clientes que han realizado más pedidos');
                }
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
    window.open('../../app/reports/dashboard/pedidos.php');
});
