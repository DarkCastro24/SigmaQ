<?php
include('../../app/helpers/dashboard.php');
Dashboard_Page::headerTemplate('Mantenimiento de usuarios', 'dashboard');
?>
<!-- Seccion de contenido -->
<div id="contenido" class="container-fluid fondo">
	<!-- Seccion de titulo de pagina -->
	<div class="container-fluid espacioSuperior">
		<h5 class="tituloMto">Gestión de usuarios</h5>
		<img src="../../resources/img/utilities/division.png" class="separador" alt="">
		<!-- Cierra seccion de titulo de pagina -->
	</div>
	<!-- Seccion de busqueda filtrada -->
	<div class="container-fluid">
		<form method="post" id="search-form">
			<div class="row">
				<div class="col-sm-7 col-md-4">
					<!-- Campo de busqueda filtrada -->
					<input id="search" name="search" class="searchButtons form-control mr-sm-2" type="search" placeholder="Buscar por código de administrador" aria-label="search">
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
	<div class="table-responsive">
		<table class="table borde">
			<!-- Cabecera de la tabla -->
			<thead class="thead-dark">
				<tr>
					<th>Código</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>DUI</th>
					<th>Correo</th>
					<th>Teléfono</th>
					<th>Usuario</th>
					<th>Estado</th>
					<th>Tipo</th>
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
		</div> <!-- Cierra controladores de tabla -->
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
									<input autocomplete="off" id="fechaInicial" name="fechaInicial" type="date" class="form-control">
								</div>
							</div>
							<div class="col-6 form-group">
								<label>Fecha final*</label>
								<div class="form-group">
									<input autocomplete="off" id="fechaFinal" name="fechaFinal" type="date" class="form-control">
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
	<!-- Modal -->
	<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 id="modal-title" name="modal-title" class="modal-title" id="staticBackdropLabel">Modal title</h5>
				</div>
				<div class="modal-body">
					<form method="post" id="save-form" enctype="multipart/form-data">
						<input type="number" id="txtIdx" name="txtIdx" />
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label>Código*</label>
									<input id="txtId" name="txtId" type="number" min="1" max="999999" class="form-control" placeholder="1" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Identificador del usuario, campo unico.</div>
								</div>
								<div class="form-group">
									<label>Nombre*</label>
									<input autocomplete="off" id="txtNombre" name="txtNombre" maxlength="40" type="text" class="form-control" placeholder="Roberto" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
								</div>
								<div class="form-group">
									<label>Teléfono*</label>
									<input autocomplete="off" id="txtTelefono" name="txtTelefono" maxlength="9" type="text" class="form-control" placeholder="0000-0000" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Debes separar con un guión luego del cuarto dígito.</div>
								</div>
								<div class="form-group">
									<label>Correo*</label>
									<input autocomplete="off" id="txtCorreo" name="txtCorreo" type="email" maxlength="60" class="form-control" placeholder="correo@example.com" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Debes ingresar un correo valido.</div>
								</div>
								<div id="boxClave" class="form-group">
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label>Usuario*</label>
									<input autocomplete="off" id="txtUsuario" name="txtUsuario" maxlength="35" type="text" class="form-control" placeholder="User01" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Su nombre de usuario debe ser unico.</div>
								</div>
								<div class="form-group">
									<label>Apellido*</label>
									<input autocomplete="off" id="txtApellido" name="txtApellido" maxlength="40" type="text" class="form-control" placeholder="Sanchez" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
								</div>
								<div class="form-group">
									<label>DUI*</label>
									<input autocomplete="off" id="txtDui" name="txtDui" type="text" maxlength="10" class="form-control" placeholder="01234567-8" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
									<div id="emailHelp" class="form-text">Debes ingresar un guión luego del octavo carácter.</div>
								</div>
								<div class="form-group">
									<label>Tipo usuario*</label>
									<select id="tipo" name="tipo" class="form-control">
										<option selected>Seleccione un tipo de usuario</option>
										<option value="1">Root</option>
										<option value="2">Admin</option>
									</select>
									<div id="emailHelp" class="form-text">Según su tipo el usuario tendra diferentes privilegios.</div>
								</div>
								<div id="boxConfirmar" class="form-group">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Dirección*</label>
									<input autocomplete="off" id="txtDireccion" name="txtDireccion" type="text" maxlength="150" class="form-control" placeholder="Avenida Aguilares 218 San Salvador CP, 1101" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
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
							<font SIZE=3 COLOR="black">Opción de ingresar nuevos usuarios a la base de datos del sistema.</font>
						</div>
						<div class="col-12">
							<!-- Boton para ingresar nuevos registros -->
							<a href="#" onclick="modalTitle(0)" class="btn btn-info btn-md " role="button" aria-disabled="true" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Registrar usuario</button></a>
						</div>
					</div>
					<div class="row centrarContenido">
						<div class="col-12 opcionesEspacio">
							<font SIZE=3 COLOR="black">Generar un gráfico de los usuarios que han realizado más acciones dentro del sistema.</font>
						</div>
						<div class="col-12">
							<form method="post" id="chart-form">
								<!-- Boton para busqueda filtrada -->
								<button class="centrarBoton2 btn btn-outline-info my-2 my-sm-0" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar gráfico de usuarios con más acciones">
									<i class="material-icons">insert_chart</i>
								</button>
							</form>
						</div>
					</div>
					<div class="row centrarContenido">
						<div class="col-12 opcionesEspacio">
							<font SIZE=3 COLOR="black">Generar un gráfico del top 5 de los usuarios que han completado más pedidos.</font>
						</div>
						<div class="col-12">
							<form method="post" id="pedidosCompletadosForm">
								<!-- Boton para busqueda filtrada -->
								<button class="centrarBoton2 btn btn-outline-info my-2 my-sm-0" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar gráfico de usuarios con más pedidos">
									<i class="material-icons">fact_check</i>
								</button>
							</form>
						</div>
					</div>
					<div class="row centrarContenido">
						<div class="col-12 opcionesEspacio">
							<font SIZE=3 COLOR="black">Generar un reporte de todos los usuarios registrados agrupados por tipo.</font>
						</div>
						<div class="col-12">
							<form method="post" id="report-form">
								<!-- Boton para busqueda filtrada -->
								<button class="centrarBoton2 btn btn-outline-info my-2 my-sm-0" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar reporte de tipos de usuario">
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
	<?php
	Dashboard_Page::footerTemplate('usuarios');
	?>