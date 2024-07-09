// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';

// Método manejador de eventos que se ejecutara cuando cargue la pagina
document.addEventListener('DOMContentLoaded', function () {
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_USUARIOS);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
const fillTable = (dataset) => {
    // Variable para almacenar registros de 5 en 5 del dataset 
    let data = '';
    // Variable para llevar un control de la cantidad de registros agregados
    let contador = 0;
    // Variables para almacernar los nombres de los iconos del los botones y del estado del usuario en la tabla
    let iconToolTip = '';
    let iconMetod = '';
    let iconType = '';
    let iconTypeTooltip = '';
    // Recorremos el contenido del arreglo
    dataset.map(function (row) {
        // Definimos el icono a mostrar en la tabla segun el estado del registro
        if (row.estado == 1) {
            // Si el estado del usuario es activo se muestran los siguiente icono
            icon = 'lock_open'
            // Se asigna el nombre del metodo para deshabilitar el registro
            metodo = 'openDeleteDialog';
            // Tooltip para indicar la accion que realiza el boton
            iconToolTip = 'Deshabilitar';
            // Se asigna el siguiente icono al boton
            iconMetod = 'block';
        } else {
            // Si el estado del usuario es activo se muestran los siguiente icono
            icon = 'lock';
            // Se asigna el nombre del metodo para activar el registro
            metodo = 'openActivateDialog';
            // Tooltip para indicar la accion que realiza el boton
            iconToolTip = 'Habilitar';
            // Se asigna el siguiente icono al boton
            iconMetod = 'check_circle_outline';
        }
        // Verificamos el tipo de usuario
        if (row.tipo == 1) {
            iconType = 'folder_special';
            iconTypeTooltip = 'Root';
        } else {
            iconType = 'folder_shared';
            iconTypeTooltip = 'Admin';
        }
        // Definimos la estructura de las filas con los campos del dataset 
        data += `
            <tr>
                <td>${row.codigoadmin}</td>
                <td>${row.nombre}</td>
                <td>${row.apellido}</td>
                <td>${row.dui}</td>
                <td>${row.correo}</td>
                <td>${row.telefono}</td>
                <td>${row.usuario}</td>
                <td><i class="material-icons">${icon}</i></td>
                <td><i class="material-icons" data-toggle="tooltip" title="${iconTypeTooltip}">${iconType}</i></a></td>
                <td>
                    <a href="#" onclick="openUpdateDialog(${row.codigoadmin})" class="edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                    <a href="#" onclick="${metodo}(${row.codigoadmin})" class="delete"><i class="material-icons" data-toggle="tooltip" title="${iconToolTip}">${iconMetod}</i></a>
                </td>
                <td>
                    <a href="#" onclick="parameterChart(${row.codigoadmin})"><i class="material-icons" data-toggle="tooltip" title="Generar gráfico de acciones más realizadas por un usuario">insert_chart</i></a>
                    <a href="../../app/reports/dashboard/accionesUsuario.php?id=${row.codigoadmin}" target="_blank"><i class="material-icons" data-toggle="tooltip" title="Generar reporte de acciones realizadas por usuario">assignment_ind</i></a>
                </td>
                <td>
                    <a href="#" onclick="parameterReportModal(${row.codigoadmin})"><i class="material-icons" data-toggle="tooltip" title="Generar reporte de acciones por fecha">collections_bookmark</i></a>
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

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Se ejecuta la funcion search rows de components y se envia como parametro la api y el form que contiene el input buscar
    searchRows(API_USUARIOS, 'search-form');
});

// Método manejador de eventos que se ejecuta cuando quiere cargar el grafico
document.getElementById('chart-form').addEventListener('submit', function (event) {
    // Evitamos que la pagina se refresque 
    event.preventDefault();
    // Colocamos el titulo del modal 
    document.getElementById('title-chart').textContent = 'Top 5 de usuarios con más acciones';
    // Se llama la funcion que muestra el gráfico en el modal.
    graficaAcciones();
    // Mandamos a llamar el modal desde JS
    var myModal = new bootstrap.Modal(document.getElementById('chart-modal'));
    myModal.show();
});

//Método manejador de eventos que se ejecuta cuando quiere cargar el gráfico
document.getElementById('pedidosCompletadosForm').addEventListener('submit', event => {
    //Evitamos que la página se recargue
    event.preventDefault();
    // Colocamos el titulo del modal 
    document.getElementById('title-chart').textContent = 'Top 5 de usuarios con más pedidos completados';
    //Se llama a la función que muestra el gráfico en el modal
    graficarPedidosCompletados();
    //Mostramos el modal
    $('#chart-modal').modal('show');
})

// Función para mostrar los 5 usuarios que han realizado mas acciones en el sistema.
const graficarPedidosCompletados = () => {
    // Cerramos el formulario de opciones
    $('#modal-form').modal('hide');
    // Reseteamos el contenido del chart
    resetChart('chart-container');
    // Creamos un atributo para guardar el codigo HTML para generar el grafico
    let content = '<canvas id="chart2"></canvas>';
    // Se agrega el codigo HTML en el contenedor de la grafica.
    document.getElementById('chart-container').innerHTML = content;
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_USUARIOS + 'topPedidosCompletados')
        .then(request => {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
            if (request.ok) {
                return request.json()
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        }).then(response => {
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
            if (response.status) {
                // Se declaran los arreglos para guardar los datos por gráficar.
                let usuarios = [];
                let cantidad = [];
                // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                response.dataset.map(function (row) {
                    // Se asignan los datos a los arreglos.
                    usuarios.push(row.usuario);
                    cantidad.push(row.pedidos);
                });
                // Se llama a la función que genera y muestra una gráfica de barras. Se encuentra en el archivo components.js
                barGraph('chart2', usuarios, cantidad, 'Cantidad de acciones realizadas', '');
            } else {
                document.getElementById('chart2').remove();
                console.log(response.exception);
            }
        }).catch(function (error) {
            console.log(error);
        });
}

// Función para cargar el grafico parametrizado.
const parameterChart = (id) => {
    // Reseteamos el contenido del chart
    resetChart('chart-container');
    // Creamos un atributo para guardar el codigo HTML para generar el grafico
    let content = '<canvas id="chart1"></canvas>';
    // Se agrega el codigo HTML en el contenedor de la grafica.
    document.getElementById('chart-container').innerHTML = content;
    // Mandamos a llamar el modal desde JS
    var myModal = new bootstrap.Modal(document.getElementById('chart-modal'));
    myModal.show();
    // Colocamos el titulo del modal 
    document.getElementById('title-chart').textContent = 'Top 5 de acciones más realizadas por un usuario';
    // Creamos un form data para enviar el id 
    const data = new FormData();
    data.append('id', id);
    // Hacemos una solicitud enviando como parametro la API y el nombre del case readOne para cargar los datos de un registro
    fetch(API_USUARIOS + 'graficaParam', {
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
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
        if (response.status) {
            // Se declaran los arreglos para guardar los datos por gráficar.
            let categorias = [];
            let cantidad = [];
            // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
            response.dataset.map(function (row) {
                // Se asignan los datos a los arreglos.
                categorias.push(row.accion);
                cantidad.push(row.cantidad);
            });
            // Se llama a la función que genera y muestra una gráfica de pastel en porcentajes. Se encuentra en el archivo components.js
            pieGraph('chart1', categorias, cantidad, 'Cantidad de acciones realizadas');
        } else {
            document.getElementById('chart1').remove();
            console.log(response.exception);
        }
    }
    ).catch(function (error) {
        console.log(error);
    });
}

// Función para cargar el reporte parametrizado.
const parameterReportModal = (id) => {
    // Mandamos a llamar el modal desde JS
    var myModal = new bootstrap.Modal(document.getElementById('report-modal'));
    // Hacemos visible el modal
    myModal.show();
    // Limpiamos el contenido del modal 
    document.getElementById('parameter-form').reset();
    // Guardamos el valor del id en un input
    document.getElementById('idReport').value = id;
}

// Función para cargar el reporte parametrizado.
const parameterReport = () => {
    // Obtenemos el valor de los inputs tipo date del formulario
    let fechaInicial = document.getElementById("fechaInicial").value;
    let fechaFinal = document.getElementById("fechaFinal").value;
    // Verificamos si el usuario ha seleccionado el rango de fechas
    if (fechaInicial == '' || fechaFinal == '') {
        // Mostramos alerta con mensaje de validacion
        sweetAlert(3, 'Seleccione el rango de fechas', null, 'Complete los campos');
    } else {
        // Validamos si la fecha inicial no es mayor a la fecha final
        if (fechaInicial > fechaFinal) {
            // Mostramos alerta con mensaje de validacion
            sweetAlert(3, 'La fecha inicial es mayor a la fecha final', null);
        } else {
            // Obtenemos el valor del input que contiene el ID del registro seleccionado
            let id = document.getElementById('idReport').value;
            // Declaramos un atributo para guardar la url a cargar
            let url = '';
            // Definimos la url del reporte agregando el id al final de la direccion
            url += `../../app/reports/dashboard/accionesUsuarioParam.php?id=${id}`;
            // Ejecutamos la funcion parameterReport para guardar los parametros para el reporte
            paramReport(API_USUARIOS, 'param-report', 'parameter-form', 'report-modal', url);
        }
    }
}

// Función para mostrar los 5 usuarios que han realizado mas acciones en el sistema.
function graficaAcciones() {
    // Cerramos el formulario de opciones
    $('#modal-form').modal('hide');
    // Reseteamos el contenido del chart
    resetChart('chart-container');
    // Creamos un atributo para guardar el codigo HTML para generar el grafico
    let content = '<canvas id="chart2"></canvas>';
    // Se agrega el codigo HTML en el contenedor de la grafica.
    document.getElementById('chart-container').innerHTML = content;
    // Realizamos peticion a la API enviando el nombre del caso y metodo get debido a que la funcion de la API retorna datos
    fetch(API_USUARIOS + 'graficaUsuarios', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se declara variable para guardar el numero de registros que han sido ingresados en el arreglo
                let contador = 0;
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas de la gráfica.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos por gráficar.
                    let categorias = [];
                    let cantidad = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se asignan los datos a los arreglos.
                        categorias.push(row.usuario);
                        cantidad.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra una gráfica de barras. Se encuentra en el archivo components.js
                    barGraph('chart2', categorias, cantidad, 'Cantidad de acciones realizadas', '');
                } else {
                    document.getElementById('chart2').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
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
    window.open('../../app/reports/dashboard/usuarios.php');
});

// Funcion para abrir el modal
const openCustomDialog = () => {
    $('#modal-form').modal('show');
}

// Función para preparar el formulario al momento de modificar un registro.
const openUpdateDialog = (id) => {
    //Mandamos a llamar la funcion para colocar el titulo al formulario
    modalTitle(id);
    // Asignamos el valor del parametro id al campo del id del modal
    document.getElementById('txtIdx').value = id;
    const data = new FormData();
    data.append('id', id);
    // Hacemos una solicitud enviando como parametro la API y el nombre del case readOne para cargar los datos de un registro
    fetch(API_USUARIOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Luego se compara si la respuesta de la API fue satisfactoria o no
        if (request.ok) {
            // En caso que la respuesta de la API sea satisfactoria se ejecuta el siguiente codigo
            request.json().then(function (response) {
                // En caso de encontrarse registros se imprimen los resultados en los inputs del modal
                if (response.status) {
                    // Colocamos el nombre de los inpus y los igualamos al valor de los campos del dataset 
                    document.getElementById('txtId').value = response.dataset.codigoadmin;
                    document.getElementById('txtNombre').value = response.dataset.nombre;
                    document.getElementById('txtApellido').value = response.dataset.apellido;
                    document.getElementById('txtDui').value = response.dataset.dui;
                    document.getElementById('txtCorreo').value = response.dataset.correo;
                    document.getElementById('txtTelefono').value = response.dataset.telefono;
                    document.getElementById('txtDireccion').value = response.dataset.direccion;
                    document.getElementById('txtUsuario').value = response.dataset.usuario;
                    document.getElementById("tipo").selectedIndex = response.dataset.tipo;
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
    // Validmos que los campos no esten vacios del lado del cliente
    if (document.getElementById("txtId").value != '') {
        if (document.getElementById("txtNombre").value != '') {
            if (document.getElementById("txtTelefono").value != '') {
                if (document.getElementById("txtCorreo").value != '') {
                    if (document.getElementById("txtUsuario").value != '') {
                        if (document.getElementById("txtApellido").value != '') {
                            if (document.getElementById("txtDui").value != '') {
                                // Se define atributo que almacenara la accion a realizar
                                let action = '';
                                // Se comprara el valor del input id 
                                if (document.getElementById('txtIdx').value) {
                                    // En caso que exista se actualiza 
                                    action = 'update';
                                } else {
                                    // En caso que no se crea 
                                    action = 'create';
                                }
                                // Ejecutamos la funcion saveRow de components y enviamos como parametro la API la accion a realizar el form para obtener los datos y el modal
                                saveRow(API_USUARIOS, action, 'save-form', 'staticBackdrop');
                            } else {
                                sweetAlert(2, 'No puedes dejar el campo de dui vacio', null),'Complete el campo solicitado';
                            }
                        } else {
                            sweetAlert(2, 'No puedes dejar el campo de apellido vacio', null),'Complete el campo solicitado';
                        }
                    } else {
                        sweetAlert(2, 'No puedes dejar el campo de usuario vacio', null),'Complete el campo solicitado';
                    }
                } else {
                    sweetAlert(2, 'No puedes dejar el campo de correo vacio', null),'Complete el campo solicitado';
                }
            } else {
                sweetAlert(2, 'No puedes dejar el campo de teléfono vacio', null),'Complete el campo solicitado';
            }
        } else {
            sweetAlert(2, 'No puedes dejar el campo de nombre vacio', null),'Complete el campo solicitado';
        }
    } else {
        sweetAlert(2, 'No puedes dejar el campo de código vacio', null),'Complete el campo solicitado';
    }

    
}

// Funcion para ocultar el input del id del registro y para cambiar el titulo del modal depende de la accion a realizar.
const modalTitle = (id) => {
    // Cerramos el modal de acciones
    $('#modal-form').modal('hide');
    // Reseteamos el valor de los campos del modal
    document.getElementById('save-form').reset();
    // Ocultamos el input que contiene el ID del registro
    document.getElementById('txtIdx').style.display = 'none';
    // Atributo para almacenar el titulo del modal
    let titulo = '';
    // Atributo para almacenar el input de clave
    let clave = '';
    // Atributo para almacenar el input confirmar clave
    let confirmar = '';
    // Compramos si el contenido el input esta vacio
    if (id == 0) {
        // En caso que no exista valor se registra
        titulo = 'Registrar usuario';
        clave = `<label  id="lblClave">Contraseña*</label>
        <input id="txtClave" name="txtClave" type="password" maxlength="35" aria-describedby="passwordHelpBlock" class="form-control" placeholder="Clave$123" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
        <div id="emailHelp" class="form-text">Tu contraseña debe tener al menos 8 caracteres.</div>`;
        confirmar = `<label id="lblConfirmarClave">Confirmar clave*</label>
        <input id="txtClave2" name="txtClave2" type="password" maxlength="35" aria-describedby="passwordHelpBlock" class="form-control" placeholder="Clave$123" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
        <div id="emailHelp" class="form-text">Debes confirmar que tu contraseña sea correcta.</div>`;
    }
    else {
        // En caso que exista se actualiza 
        titulo = 'Actualizar usuario';
        clave = `<label  id="lblClave">Contraseña</label>
        <input id="txtClave" name="txtClave" type="password" maxlength="35" aria-describedby="passwordHelpBlock" class="form-control" placeholder="" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo opcional">
        <div id="emailHelp" class="form-text">Tu contraseña debe tener al menos 8 caracteres.</div>`;
        confirmar = `<label id="lblConfirmarClave">Confirmar clave</label>
        <input id="txtClave2" name="txtClave2" type="password" maxlength="35" aria-describedby="passwordHelpBlock" class="form-control" placeholder="" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo opcional">
        <div id="emailHelp" class="form-text">Debes confirmar que tu contraseña sea correcta.</div>`;
    }
    // Colocamos el titulo al elemento con el id modal-title
    document.getElementById('boxClave').innerHTML = clave;
    document.getElementById('boxConfirmar').innerHTML = confirmar;
    document.getElementById('modal-title').textContent = titulo;
}

// Función para establecer el registro a eliminar y abrir una caja de dialogo de confirmación.
const openDeleteDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmDesactivate(API_USUARIOS, data);
}

// Función para establecer el registro a reactivar y abrir una caja de dialogo de confirmación.
const openActivateDialog = (id) => {
    const data = new FormData();
    // Asignamos el valor de la data que se enviara a la API
    data.append('id', id);
    // Ejecutamos la funcion confirm delete de components y enviamos como parametro la API y la data con id del registro a eliminar
    confirmActivate(API_USUARIOS, data);
}