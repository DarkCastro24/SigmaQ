<?php
include('../../app/helpers/dashboard.php');
Dashboard_Page::headerTemplate('Mantenimiento de clientes', 'dashboard');
?>
<!-- Seccion de contenido -->
<div id="contenido" class="container-fluid fondo">
	<!-- Seccion de titulo de pagina -->
	<div class="container-fluid espacioSuperior">
		<h5 class="tituloMto">Gestión de cliente</h5>
		<img src="../../resources/img/utilities/division.png" class="separador" alt="">
		<!-- Cierra seccion de titulo de pagina -->
	</div>
	<!-- Seccion de busqueda filtrada -->
	<div class="container-fluid">
		<form method="post" id="search-form">
			<div class="row">
				<div class="col-sm-7 col-md-4">
					<!-- Campo de busqueda filtrada -->
					<input id="search" name="search" class="searchButtons form-control mr-sm-2" type="search" placeholder="Buscar por código de cliente" aria-label="search">
				</div>
				<div class="col-sm-5 col-md-5">
					<button class="centrarBoton btn btn-outline-info my-2 my-sm-0" type="submit">
						<i class="material-icons">search</i></button>
					</button>
				</div>
				<div class="col-sm-12 col-md-3">
					<a onclick=openCustomDialog() type='button' class='btn btn-primary' id='conf_tabla_estado'>
						Opciones adicionales
					</a>
				</div>
			</div>
		</form>
	</div>
	<!-- Seccion de tabla -->
	<div class="container-fluid ">
		<div class="table-responsive">
			<table class="table borde">
				<!-- Cabecera de la tabla -->
				<thead class="thead-dark">
					<tr>
						<th>Código</th>
						<th>Empresa</th>
						<th>Teléfono</th>
						<th>Correo</th>
						<th>Usuario</th>
						<th>Estado</th>
						<th>Opciones</th>
						<th>Extras</th>
						<th>Reporte</th>
					</tr>
				</thead>
				<!-- Contenido de la tabla -->
				<tbody id="tbody-rows">
				</tbody>
			</table>
			<div id="seccionPaginacion" class="clearfix">
			<!-- Seccion controladores tabla -->
		</div> 
		</div>
		<!-- Cierra controladores de tabla -->
		<!-- Cierra seccion de tabla -->
	</div>
	<!-- Modal chart-modal -->
	<div class="modal fade" id="chart-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="title-chart">Gráfica de los cinco clientes con más acciones realizadas</h5>
				</div>
				<div class="modal-body">
					<!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
					<div id="chart-container" class="containter-fluid">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal report-modal -->
	<div class="modal fade" id="report-modal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="title-chart">Seleccione un rango de fechas para generar el reporte</h5>
				</div>
				<div class="modal-body">
					<!-- Formulario para enviar parametros -->
					<form method="post" id="parameter-form" enctype="multipart/form-data">
						<div class="d-none"><input type="number" id="idReport" name="idReport" /></div>
						<div class="row">
							<div class="col-6 form-group">
								<label>Fecha inicial*</label>
								<div class="form-group">
									<input id="fechaInicial" name="fechaInicial" type="date" class="form-control">
								</div>
							</div>
							<div class="col-6 form-group">
								<label>Fecha final*</label>
								<div class="form-group">
									<input id="fechaFinal" name="fechaFinal" type="date" class="form-control">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button onclick="parameterReport()" type="button" class="btn btn-primary">Generar reporte</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal  -->
	<div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="modal-title" name="modal-title" class="modal-title">Opciones adicionales</h4>
				</div>
				<div class="modal-body">
					<div class="row centrarContenido">
						<div class="col-12 opcionesEspacio">
							<font SIZE=3 COLOR="black">Opción de ingresar nuevos clientes a la base de datos del sistema.</font>
						</div>
						<div class="col-12">
							<!-- Boton para ingresar nuevos registros -->
							<a href="#" onclick="modalTitle(0)" class="btn btn-info btn-md " role="button" aria-disabled="true" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Registrar cliente</button></a>
						</div>
					</div>
					<div class="row centrarContenido">
						<div class="col-12 opcionesEspacio">
							<font SIZE=3 COLOR="black">Generar un gráfico de los clientes que han realizado más acciones dentro del sistema.</font>
						</div>
						<div class="col-12">
							<form method="post" id="chart-form">
								<!-- Boton para busqueda filtrada -->
								<button class="centrarBoton2 btn btn-outline-info my-2 my-sm-0" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar gráfico de clientes con más acciones">
									<i class="material-icons">insert_chart</i></button>
								</button>
							</form>
						</div>
					</div>
					<div class="row centrarContenido">
						<div class="col-12 opcionesEspacio">
							<font SIZE=3 COLOR="black">Generar un reporte de todos los clientes registrados agrupados por estado.</font>
						</div>
						<div class="col-12">
							<form method="post" id="report-form">
								<!-- Boton para busqueda filtrada -->
								<button class="centrarBoton2 btn btn-outline-info my-2 my-sm-0" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar reporte de clientes agrupado por estado">
									<i class="material-icons">assignment_ind</i></button>
								</button>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar formulario</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal  -->
	<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 id="modal-title" name="modal-title" class="modal-title" id="staticBackdropLabel">Registrar clientes</h5>
				</div>
				<div class="modal-body">
					<form method="post" id="save-form" name="save-form" enctype="multipart/form-data">
						<input type="number" id="txtIdx" name="txtIdx" />
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label>Código*</label>
									<input autocomplete="off" id="txtId" name="txtId" type="number" min="1" max="999999" class="form-control" placeholder="000001" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Identificador del cliente, campo unico.</div>
								</div>
								<div class="form-group">
									<label>Usuario*</label>
									<input autocomplete="off" id="txtUsuario" name="txtUsuario" maxlength="35" type="text" class="form-control" placeholder="User01" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Su nombre de usuario debe ser unico.</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label>Empresa*</label>
									<input autocomplete="off" id="txtEmpresa" name="txtEmpresa" maxlength="40" type="text" class="form-control" placeholder="SigmaQ" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Ingrese el nombre de la empresa del cliente.</div>
								</div>
								<div class="form-group">
									<label>Teléfono*</label>
									<input autocomplete="off" id="txtTelefono" name="txtTelefono" maxlength="9" type="text" class="form-control" placeholder="0000-0000" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Debes separar con un guión luego del cuarto dígito.</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label>Correo*</label>
									<input autocomplete="off" id="txtCorreo" name="txtCorreo" type="email" maxlength="60" class="form-control" placeholder="correo@example.com" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Debes ingresar un correo valido.</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div id="boxClave" class="form-group">
								</div>
							</div>
							<div class="col-6">
								<div id="boxConfirmar" class="form-group">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button onclick="saveData()" type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<?php
	Dashboard_Page::footerTemplate('clientes');
	?>