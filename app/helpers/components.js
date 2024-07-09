/*
*   Función para obtener todos los registros disponibles en las tablas de la base de datos
*
*   Parámetros: api (ruta del servidor para obtener los datos).
*
*   Retorno: ninguno.
*/

//Arreglo para obtener los meses por su nombre, con el número del mes como posición del arreglo
const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Séptiembre', 'Octubre', 'Noviembre', 'Diciembre'];

const readRows = (api) => { 
    /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo get 
    para obtener el resultado de la API*/
    fetch(api + 'readAll', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                let data = [];
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se obtiene el valor del dataset para asignarlo al atributo data
                    data = response.dataset;
                } else {
                    sweetAlert(4, response.exception, null);
                }
                // Se resetean los valores de los arreglos de la paginacion
                resetPagination();
                // Se envían los datos a la función del controlador para que llene la tabla en la vista.
                fillTable(data);
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

/*
*   Función para crear o actualizar un registro en los mantenimientos de tablas (operación create y update).
*
*   Parámetros: api (ruta del servidor para enviar los datos), form (identificador del formulario) y modal (identificador de la caja de dialogo).
*
*   Retorno: ninguno.
*/
const saveRow = (api, action, form, modal) => { 
    /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
    para acceder a los campos desde la API*/
    fetch(api + action, {
        method: 'post',
        body: new FormData(document.getElementById(form))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            // console.log(request.text());
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se cierra la caja de dialogo (modal) del formulario.
                    $(`#${modal}`).modal('hide');
                    // Resetamos los vectores que contienen los registros de la tabla
                    resetPagination();
                    // Se cargan nuevamente las filas en la tabla de la vista después de agregar o modificar un registro.
                    readRows(api);
                    // Mostramos alerta con mensaje de exito
                    sweetAlert(1, response.message, null,'Acción completada');
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

/*
*   Función para crear o actualizar un registro en los mantenimientos de tablas (operación create y update).
*
*   Parámetros: api (ruta del servidor para enviar los datos), form (identificador del formulario) ,modal (identificador de la caja de dialogo) 
*   y url (direccion del reporte).
*
*   Retorno: ninguno.
*/
const paramReport = (api, action, form, modal, url) => { 
    /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
    para acceder a los campos desde la API*/
    fetch(api + action, {
        method: 'post',
        body: new FormData(document.getElementById(form))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se cierra la caja de dialogo (modal) del formulario.
                    $(`#${modal}`).modal('hide');
                    // Abrimos el reporte en una pestaña nueva
                    window.open(url);
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}


/*
*   Función para obtener los resultados de una búsqueda en los mantenimientos de tablas (operación search).
*
*   Parámetros: api (ruta del servidor para obtener los datos) y form (identificador del formulario de búsqueda).
*
*   Retorno: ninguno.
*/
const searchRows = (api, form) => { 
    /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
    para acceder a los campos desde la API*/
    fetch(api + 'search', {
        method: 'post',
        body: new FormData(document.getElementById(form))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se resetean los valores de los arreglos de la paginacion
                    resetPagination();
                    // Borramos el contenido de la tabla
                    deleteTable();
                    // Se envían los datos a la función del controlador para que llene la tabla en la vista.
                    fillTable(response.dataset);
                    // Mostramos alerta con mensaje de exito
                    sweetAlert(1, response.message, null,'Busqueda exitosa');
                } else {
                    sweetAlert(4, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

/*
*   Función para eliminar un registro seleccionado en los mantenimientos de tablas (operación delete). Requiere el archivo sweetalert.min.js para funcionar.
*
*   Parámetros: api (ruta del servidor para enviar los datos) y data (objeto con los datos del registro a eliminar).
*
*   Retorno: ninguno.
*/
const confirmDelete = (api, data) => { 
    // Se manda a llamar la funcion de la libreria sweet alert y se envian los parametros para generar la caja de dialogo
    swal({
        title: 'Advertencia',
        text: '¿Desea eliminar el registro?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.
        if (value) {
            /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
            para acceder a los campos desde la API*/
            fetch(api + 'delete', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Resetamos los vectores que contienen los registros de la tabla
                            resetPagination();
                            // Se cargan nuevamente las filas en la tabla de la vista después de borrar un registro.
                            readRows(api);
                            // Se muestra una alerta con el mensaje de exito
                            sweetAlert(1, response.message, null);
                        } else {
                            // Se muestra una alerta con el mensaje de error
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });
}

/*
*   Función para eliminar todos los registros de una tabla (limpiar base de datos)
*
*   Parámetros: api (ruta del servidor para enviar los datos) y data (objeto con los datos del registro a eliminar).
*
*   Retorno: ninguno.
*/
const confirmClean = (api) => { 
    // Se manda a llamar la funcion de la libreria sweet alert y se envian los parametros para generar la caja de dialogo
    swal({
        title: 'Advertencia',
        text: '¿Desea eliminar todos los registros de la tabla?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        if (value == true) {
            swal({
                title: 'Advertencia',
                text: '¿Confirma la acción a realizar?',
                icon: 'info',
                buttons: ['No', 'Sí'],
                closeOnClickOutside: false,
                closeOnEsc: false
            }).then(function (value) {
                if (value) {
                    /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
                    para acceder a los campos desde la API*/
                    fetch(api + 'deleteAll', {
                        method: 'post'
                    }).then(function (request) {
                        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                        if (request.ok) {
                            request.json().then(function (response) {
                                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                                if (response.status) {
                                    // Borramos el contenido de la tabla 
                                    deleteTable();
                                    // Se muestra una alerta con el mensaje de exito
                                    sweetAlert(1, response.message, null);
                                } else {
                                    sweetAlert(2, response.exception, null);
                                }
                            });
                        } else {
                            console.log(request.status + ' ' + request.statusText);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            });  
        } 
    });
}

// Se verifica si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.


/*
*   Función para eliminar un registro seleccionado en los mantenimientos de tablas (operación delete). Requiere el archivo sweetalert.min.js para funcionar.
*
*   Parámetros: api (ruta del servidor para enviar los datos) y data (objeto con los datos del registro a eliminar).
*
*   Retorno: ninguno.
*/
const confirmDesactivate = (api, data) => { 
    // Se manda a llamar la funcion de la libreria sweet alert y se envian los parametros para generar la caja de dialogo
    swal({
        title: 'Advertencia',
        text: '¿Desea desactivar el registro?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.
        if (value) {
            /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
            para acceder a los campos desde la API*/
            fetch(api + 'delete', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Resetamos los vectores que contienen los registros de la tabla
                            resetPagination();
                            // Se cargan nuevamente las filas en la tabla de la vista después de borrar un registro.
                            readRows(api);
                            // Se muestra una alerta con el mensaje de exito
                            sweetAlert(1, response.message, null);
                        } else {
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });
}

/*
*   Función para eliminar un registro seleccionado en los mantenimientos de tablas (operación delete). Requiere el archivo sweetalert.min.js para funcionar.
*
*   Parámetros: api (ruta del servidor para enviar los datos) y data (objeto con los datos del registro a eliminar).
*
*   Retorno: ninguno.
*/
const confirmActivate = (api, data) => { 
    // Se manda a llamar la funcion de la libreria sweet alert y se envian los parametros para generar la caja de dialogo
    swal({
        title: 'Advertencia',
        text: '¿Desea activar el usuario?',
        icon: 'info',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.
        if (value) {
            /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
            para acceder a los campos desde la API*/
            fetch(api + 'activate', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Resetamos los vectores que contienen los registros de la tabla
                            resetPagination();
                            // Se cargan nuevamente las filas en la tabla de la vista después de borrar un registro.
                            readRows(api);
                            // Se muestra una alerta con el mensaje de exito
                            sweetAlert(1, response.message, null);
                        } else {
                            // Se muestra una alerta con el mensaje de error
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });
}

/*
*   Función para eliminar un registro seleccionado en los mantenimientos de tablas (operación delete). Requiere el archivo sweetalert.min.js para funcionar.
*
*   Parámetros: api (ruta del servidor para enviar los datos) y data (objeto con los datos del registro a eliminar).
*
*   Retorno: ninguno.
*/
const onlyConfirmDelete = (api, data) => {  
    // Se manda a llamar la funcion de la libreria sweet alert y se envian los parametros para generar la caja de dialogo
    swal({
        title: 'Advertencia',
        text: '¿Desea eliminar el registro?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.
        if (value) {
            /* Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el metodo post 
            para acceder a los campos desde la API*/
            fetch(api + 'delete', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Se cargan nuevamente las filas en la tabla de la vista después de borrar un registro.
                            sweetAlert(1, response.message, null);
                        } else {
                            // Se muestra una alerta con el mensaje de error
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });
}

/*
*   Función para manejar los mensajes de notificación al usuario. Requiere el archivo sweetalert.min.js para funcionar.
*
*   Parámetros: type (tipo de mensaje), text (texto a mostrar) y url (ubicación a direccionar al cerrar el mensaje).
*
*   Retorno: ninguno.
*/
const sweetAlert = (type, text, url , title) => {  
    // Se compara el tipo de mensaje a mostrar.
    if (title != null) {
        switch (type) {
            case 1:
                icon = 'success';
                break;
            case 2:
                icon = 'error';
                break;
            case 3:
                icon = 'warning';
                break;
            case 4:
                icon = 'info';
                break;
        }    
    } else {
        switch (type) {
            case 1:
                title = 'Éxito';
                icon = 'success';
                break;
            case 2:
                title = 'Error';
                icon = 'error';
                break;
            case 3:
                title = 'Advertencia';
                icon = 'warning';
                break;
            case 4:
                title = 'Aviso';
                icon = 'info';
                break;
        }
    }
    // Si existe una ruta definida, se muestra el mensaje y se direcciona a dicha ubicación, de lo contrario solo se muestra el mensaje.
    if (url) {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: 'Aceptar',
            closeOnClickOutside: false,
            closeOnEsc: false
        }).then(function () {
            location.href = url
        });
    } else {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: 'Aceptar',
            closeOnClickOutside: false,
            closeOnEsc: false
        });
    }
}

/*
*   Función para cargar las opciones en un select de formulario.
*
*   Parámetros: endpoint (ruta específica del servidor para obtener los datos), select (identificador del select en el formulario) y selected (valor seleccionado).
*
*   Retorno: ninguno.
*/
const fillSelect = (endpoint, select, selected) => {   
    fetch(endpoint, {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                let content = '';
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Si no existe un valor para seleccionar, se muestra una opción para indicarlo.
                    if (!selected) {
                        content += '<option disabled selected>Seleccione una opción</option>';
                    }
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se obtiene el dato del primer campo de la sentencia SQL (valor para cada opción).
                        value = Object.values(row)[0];
                        // Se obtiene el dato del segundo campo de la sentencia SQL (texto para cada opción).
                        text = Object.values(row)[1];
                        // Se verifica si el valor de la API es diferente al valor seleccionado para enlistar una opción, de lo contrario se establece la opción como seleccionada.
                        if (value != selected) {
                            content += `<option value="${value}">${text}</option>`;
                        } else {
                            content += `<option value="${value}" selected>${text}</option>`;
                        }
                    });
                } else {
                    content += '<option>No hay opciones disponibles</option>';
                }
                // Se agregan las opciones a la etiqueta select mediante su id.
                document.getElementById(select).innerHTML = content;
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

/*
*   Función para generar una gráfica de barras verticales. Requiere el archivo chart.js para funcionar.
*
*   Parámetros: canvas (identificador de la etiqueta canvas), xAxis (datos para el eje X), yAxis (datos para el eje Y), legend (etiqueta para los datos) y title (título de la gráfica).
*
*   Retorno: ninguno.
*/
const barGraph = (canvas, xAxis, yAxis, legend, title) => { 
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = ["#000000", "#CE0E2D","#58595B","#E6E7E8","#FFFFFF"];
    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar la gráfica con los datos recibidos.
    const chart = new Chart(context, {
        // Indicamos el tipo de reporte que generaremos
        type: 'bar',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                // Agregamos el arreglo con los datos para llenar el grafico
                data: yAxis,
                // Asignamos el color del borde del grafico
                borderColor: '#000000',
                borderWidth: 1,
                // Colocamos el arreglo con los codigos de colores
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            legend: {
                display: false
            },
            // Colocamos el titulo al grafico
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }]
            }
        }
    });
}

/*
*   Función para generar una gráfica de pastel. Requiere el archivo chart.js para funcionar.
*
*   Parámetros: canvas (identificador de la etiqueta canvas), legends (valores para las etiquetas), values (valores de los datos) y title (título de la gráfica).
*
*   Retorno: ninguno.
*/
const pieGraph = (canvas, legends, values, title) => { 
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = ["#000000", "#CE0E2D","#58595B","#E6E7E8","#FFFFFF"]
    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar la gráfica con los datos recibidos.
    const chart = new Chart(context, {
        // Indicamos el tipo de reporte que generaremos
        type: 'pie',
        data: {
            labels: legends,
            datasets: [{
                // Agregamos el arreglo con los datos para llenar el grafico
                data: values,
                // Colocamos el arreglo con los codigos de colores
                backgroundColor: colors,
                // Asignamos el color del borde del grafico
                borderColor: '#000000',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: title
            }
        }
    });
}

/*
*   Función para generar una gráfica de doughnut. Requiere el archivo chart.js para funcionar.
*
*   Parámetros: canvas (identificador de la etiqueta canvas), legends (valores para las etiquetas), values (valores de los datos) y title (título de la gráfica).
*
*   Retorno: ninguno.
*/
const doughnutGraph = (canvas, legends, values, title) => { 
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = ["#000000", "#CE0E2D","#58595B","#E6E7E8","#FFFFFF"];
    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar la gráfica con los datos recibidos.
    const chart = new Chart(context, {
        // Indicamos el tipo de reporte que generaremos
        type: 'doughnut',
        data: {
            labels: legends,
            datasets: [{
                // Agregamos el arreglo con los datos para llenar el grafico
                data: values,
                // Colocamos el arreglo con los codigos de colores
                backgroundColor: colors,
                // Asignamos el color del borde del grafico
                borderColor: '#000000',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: title
            }
        }
    });
}


/*
*   Función para generar una gráfica de area polar. Requiere el archivo chart.js para funcionar.
*
*   Parámetros: canvas (identificador de la etiqueta canvas), legends (valores para las etiquetas), values (valores de los datos) y title (título de la gráfica).
*
*   Retorno: ninguno.
*/
const polarGraph = (canvas, legends, values, title) => { 
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = ["#000000", "#CE0E2D","#58595B","#E6E7E8","#FFFFFF"];
    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar la gráfica con los datos recibidos.
    const chart = new Chart(context, {
        // Indicamos el tipo de reporte que generaremos
        type: 'polarArea',
        data: {
            labels: legends,
            datasets: [{
                // Agregamos el arreglo con los datos para llenar el grafico
                data: values,
                // Colocamos el arreglo con los codigos de colores
                backgroundColor: colors,
                // Asignamos el color del borde del grafico
                borderColor: '#000000',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: title
            }
        }
    });
}

/*
*   Función para eliminar el contenido del contenedor de las graficas 
*
*   Parámetros: Nombre del contenedor del grafico.
*
*   Retorno: ninguno.
*/
const resetChart = (container) => {
    // Se agrega el codigo HTML en el contenedor de la grafica.
    document.getElementById(container).innerHTML = '';
}

/*
*   Función para generar una gráfica de lineas verticales. Requiere el archivo chart.js para funcionar.
*
*   Parámetros: canvas (identificador de la etiqueta canvas), xAxis (datos para el eje X), yAxis (datos para el eje Y), legend (etiqueta para los datos) y title (título de la gráfica).
*
*   Retorno: ninguno.
*/
const lineGraph = (canvas, xAxis, yAxis, legend, title) => { 
    // Se define el canva donde se dibujará el gráfico
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar la gráfica con los datos recibidos.
    const chart = new Chart(context, {
        type: 'line',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                fill: false,
                tension: 0.1,
                borderColor: 'rgb(206,14,45)',
                backgroundColor: 'rgb(206,14,45)',
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
                display: false
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }]
            }
        },
    })
}

// Funcion para guardar las accciones realizadas por los usuarios del sistema automaticamente en la base de datos
const updateHistorial = (api, accion) => { 
    // Creamos un form data para enviar el id 
    const data = new FormData();
    data.append('accion', accion);
    // Se realiza una peticion a la API enviando como parametro el form que contiene los datos, el nombre del caso y el body con la accion realizada
    fetch(api + 'create', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    console.log(response.message);
                } else {
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