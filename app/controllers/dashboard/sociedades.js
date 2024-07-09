// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = '../../app/api/dashboard/clientes.php?action=readAll';
const API_SOCIEDADES = '../../app/api/dashboard/sociedades.php?action=';

// Función para llenar la tabla con los datos de los registros. Se usa en la función readRows()
const fillTableSociedad = dataset => {
    $('#warning-message').empty();
    $('#tbody-rows').empty();
    let content = ''
    if (dataset == [].length) {
        //console.log(dataset)
        content += `<h4>No hay índices registrados</h4>`
        document.getElementById('warning-message').innerHTML = content
    } else {
        //Se agregan los titulos de las columnas
        content += `
            <thead class="thead-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Sociedad</th>
                    <th>Opciones</th>
                </tr>
            </thead>
        `
        // Se obtienen los datos procedentes de la base (dataset)
        dataset.map(row => {
            // Declaramos variables para almacenar los nombres de los iconos y el nombre del metodo
            let toggleEnabledIcon = '';
            let iconToolTip = '';
            let metodo = '';
            // Se verifica el estado de la sociedad
            if (row.estado) {
                //Cuando el registro esté habilitado
                iconToolTip = 'Deshabilitar'
                toggleEnabledIcon = 'block'
                metodo = 'openDeleteDialog';
            } else {
                // Cuando este habilitada
                iconToolTip = 'Habilitar'
                toggleEnabledIcon = 'check_circle_outline'
                metodo = 'openActivateDialog';
            }
            content += `
                <tr>
                    <td>${row.cliente}</th>
                    <td>${row.sociedad}</th>
                    <td>
                        <a href="#" onclick="openUpdateDialog(${row.idsociedad})" class="edit"><i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>
                        <a href="#" onclick="${metodo}(${row.idsociedad})" class="delete"><i class="material-icons" data-toggle="tooltip" title="${iconToolTip}">${toggleEnabledIcon}</i></a>
                    </td>
                </tr>
            `
        })
        //Se agrega el contenido a la tabla mediante su id
        document.getElementById('tbody-rows').innerHTML = content;
    }
}

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
    saveRow(API_INDICES, action, 'save-form-sociedades', 'modal-sociedades');
    // Se manda a llamar la funcion para llenar la tabla con la API de parametro
    readRows(API_INDICES);
}

// Función para abrir el Form al momento de crear un registro
const openCreateDialogSociedades = () => {
    //Se restauran los elementos del form
    document.getElementById('save-form-sociedades').reset();
    //Se abre el form
    $('#modal-sociedades').modal('show');
    //Asignamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Registrar Sociedad'
    // Se llama a la function para llenar los Selects
    fillSelect(API_CLIENTES, 'cliente', null);
}