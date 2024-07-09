<?php
include('../../app/helpers/dashboard.php');
Dashboard_Page::headerTemplate('Mantenimiento de pedidos', 'dashboard');
?>
<!-- Seccion de contenido -->
<div id="contenido" class="container-fluid fondo">
	<!-- Seccion de titulo de pagina -->
	<div class="container-fluid"><br><br>
		<h5 class="tituloMto">Gestión de pedidos</h5>
		<img src="../../resources/img/utilities/division.png" class="separador" alt="">
	</div><br> <!-- Cierra seccion de titulo de pagina -->
	<!-- Seccion de busqueda filtrada -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 col-lg-6 col-xl-8">
				<form method="post" id="search-form">
					<div class="row">
						<div class="col-sm-8 col-lg-8">
							<!-- Campo de busqueda filtrada -->
							<input id="search" name="search" class="searchButtons form-control mr-sm-2" type="search" placeholder="Buscar por responsable, cliente u organización" aria-label="search">
						</div>
						<div class="col-sm-4 col-lg-4">
							<!-- Boton para busqueda filtrada -->
							<button class="centrarBoton btn btn-outline-info my-2 my-sm-0" type="submit">
								<i class="material-icons">search</i></button>
							</button>
						</div>
					</div><br>
				</form>
			</div>
			<div class="col-sm-6 col-lg-3 col-xl-2">
				<a class="btn btn-info btn-md espaciolateral" onclick="openCreateDialog()" role="button" aria-disabled="true">Ingresar pedido</button></a>
			</div>
			<div class="col-sm-6 col-lg-3 col-xl-2">
				<div class="dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Opciones adicionales
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="#">Reportes</a>
						<form method="post" id="report-form">
							<div class="container">
								<button class="centrarBoton btn btn-outline-info" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar reporte de pedidos organizados por responsable">
									<i class="material-icons">assignment_ind</i>
								</button><br><br>
							</div>
						</form>
						<a class="dropdown-item" href="#">Gráficos</a>
						<div class="col-sm-3">
							<button id="enviosMensuales" class="centrarBoton btn btn-outline-info">
								<i class="material-icons" data-toggle="tooltip" title="Gráfico de productos enviados por mes">assignment_turned_in</i></button>
							</button><br><br>
							<button id="mejoresClientes" class="centrarBoton btn btn-outline-info">
								<i class="material-icons" data-toggle="tooltip" title="Gráfico de top 5 clientes con más pedidos realizados">workspace_premium</i></button>
							</button>
						</div>
						<!-- Boton para ingresar nuevos registros -->
						<div id="seccionAgregar" class="row">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Cierra seccion de busqueda filtrada -->
		<div class="table-responsive">
			<table class="table borde">
				<!-- Cabecera de la tabla -->
				<thead class="thead-dark">
					<tr>
						<th>Cliente</th>
						<th>Pos</th>
						<th>OC</th>
						<th>Solicitada</th>
						<th>Código</th>
						<th>Enviada</th>
						<th>Fecha registrado</th>
						<th>Fecha de entrega</th>
						<th>Fecha de confirmación</th>
						<th>Estado</th>
						<th>Reporte</th>
						<th>Opciones</th>
					</tr>
				</thead>
				<!-- Contenido de la tabla -->
				<tbody id="tbody-rows">
				</tbody>
			</table>
			<div id="seccionPaginacion" class="clearfix">
			</div> <!-- Cierra controladores de tabla -->
		</div>
	</div>
	<!-- Modal  -->
	<div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 id="modal-title" name="modal-title" class="modal-title">Modal title</h5>
				</div>
				<div class="modal-body">
					<form method="post" id="save-form" enctype="multipart/form-data">
						<div class="row">
							<!-- Campo invicible del ID -->
							<input class="d-none" type="number" id="idpedido" name="idpedido">
							<div class="col-6 form-group">
								<label>Responsable*</label>
								<select id="responsable" name="responsable" class="form-control">
								</select>
							</div>
							<div class="col-6 form-group">
								<label>Cliente*</label>
								<select id="cliente" name="cliente" class="form-control">
								</select>
								<label class="font-italic text-danger">No podrá modificar el valor de este campo</label>
							</div>
							<div class="col-6 form-group">
								<label>Código*</label>
								<input autocomplete="off" id="codigo" name="codigo" type="number" min="1" class="form-control" required>
								<label class="font-italic text-danger">No podrá modificar el valor de este campo</label>
							</div>
							<div class="col-6 form-group">
								<label>Fecha registrado*</label>
								<input id="fecharegistro" name="fecharegistro" type="date" class="form-control" readonly>
							</div>
							<div class="col-6 form-group">
								<label>Pos*</label>
								<input id="pos" name="pos" type="number" class="form-control" required>
							</div>
							<div class="col-6 form-group">
								<label>Oc*</label>
								<input id="oc" name="oc" type="number" class="form-control" min="1" required>
							</div>
							<div class="col-6 form-group">
								<label>Cantidad solicitada*</label>
								<input id="cantidadsolicitada" name="cantidadsolicitada" type="number" class="form-control" required>
								<div id="emailHelp" class="form-text">Cantidad de unidades solicitadas dentro del pedido.</div>
							</div>
							<div class="col-6 form-group">
								<label>Cantidad enviada*</label>
								<input id="cantidadenviada" name="cantidadenviada" type="number" min="0" class="form-control" required>
								<div id="emailHelp" class="form-text">Cantidad de unidades enviadas dentro del pedido.</div>
							</div>
							<div class="col-6 form-group">
								<label>Fecha de entrega*</label>
								<input id="fechaentrega" name="fechaentrega" type="date" min="0" class="form-control" required>
							</div>
							<div class="col-6 form-group">
								<label>Fecha confirmada de envío*</label>
								<input id="fechaconfirmadaenvio" name="fechaconfirmadaenvio" type="date" min="0" class="form-control" required>
							</div>
							<div class="form-group col-6">
								<label>Descripción</label>
								<textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
							</div>
							<div class="col-6 form-group">
								<label>Comentarios</label>
								<textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
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
					<h5 id="modal-title" name="modal-title" class="modal-title">Personalización de la tabla</h5>
				</div>
				<div class="modal-body">
					<form method="post" id="save-form" enctype="multipart/form-data">
						<div class="row">
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="responsable" id="responsable" checked>
									<label class="form-check-label" for="organizacion">Responsable</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="sociedad" id="sociedad" checked>
									<label class="form-check-label" for="indice">Sociedad</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="usuario" id="usuario" checked>
									<label class="form-check-label" for="compromisos">Usuario</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="codigo" id="codigo" checked>
									<label class="form-check-label" for="cumplidos">Código</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="factura" id="factura" checked>
									<label class="form-check-label" for="nocumplidos">Factura</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="asignacion" id="asignacion" checked>
									<label class="form-check-label" for="noconsiderados">Asignación</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="fechacontable" id="fechacontable" checked>
									<label class="form-check-label" for="incumnoentregados">Fecha contable</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="clase" id="clase" checked>
									<label class="form-check-label" for="incumporcalidad">Clase</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="vencimiento" id="vencimiento" checked>
									<label class="form-check-label" for="incumporfecha">Vencimiento</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="diasrestantes" id="diasrestantes" checked>
									<label class="form-check-label" for="incumporcantidad">Días restantes</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="divisa" id="divisa" checked>
									<label class="form-check-label" for="incumporcantidad">Divisa</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="totalgeneral" id="totalgeneral" checked>
									<label class="form-check-label" for="incumporcantidad">Total general</label>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button onclick="readRows('../../app/api/public/estadoCuenta.php?action=')" type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal chart-modal -->
	<div class="modal fade" id="chart-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="title-chart"></h5>
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
</div>
<!-- Cierra seccion de contenido -->
<?php
Dashboard_Page::footerTemplate('pedidos');
?>