<?php
include('../../app/helpers/public.php');
Public_Page::headerTemplate('SigmaQ - Estado de cuenta');
?>
<div class='my-4'></div>

<!-- Botón para el modal de personalización de la tabla -->
<div class='container-fluid'>
	<div class='row'>
		<div class="col-sm-12 col-md-8">
			<form method="post" id="search-form">
				<div class="row">
					<div class="col-9 bajar">
						<!-- Campo de busqueda filtrada -->
						<input autocomplete="off" id="search" name="search" class="searchButtons form-control mr-sm-2 " type="search" placeholder="Buscar por responsable, sociedad u código" aria-label="search">
					</div>
					<div class="col-3">
						<!-- Boton para busqueda filtrada -->
						<button class="centrarBoton btn btn-outline-info my-2 my-sm-0" type="submit">
							<i class="material-icons">search</i></button>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class='col-sm-12 col-md-4'>
			<a onclick=openCustomDialog() type='button' class='btn btn-primary' id='conf_tabla_estado'>
				Configurar tabla
			</a>
		</div>
	</div><br>
</div>

<!-- Tabla -->
<!-- Seccion de tabla de registros -->
<div class="container-fluid table-responsive espacioSuperior">
	<table class="table borde">
		<!-- Cabecera de la tabla -->
		<thead id="theaders" class="thead-dark">
		</thead>
		<!-- Contenido de la tabla -->
		<tbody id="tbody-rows">
		</tbody>
	</table>
	<div id="seccionPaginacion" class="clearfix">
		<!-- Seccion controladores tabla -->
	</div> <!-- Cierra controladores de tabla -->
	<!-- Cierra seccion de tabla -->

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
									<input class="form-check-input" type="checkbox" id="responsable" checked>
									<label class="form-check-label" for="responsable">Responsable</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="sociedad" checked>
									<label class="form-check-label" for="sociedad">Sociedad</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="usuario" checked>
									<label class="form-check-label" for="usuario">Usuario</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="codigo" checked>
									<label class="form-check-label" for="codigo">Código</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="factura" checked>
									<label class="form-check-label" for="factura">Factura</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="asignacion" checked>
									<label class="form-check-label" for="asignacion">Asignacion</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="fechacontable" checked>
									<label class="form-check-label" for="fechacontable">Fecha contable</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="clase" checked>
									<label class="form-check-label" for="clase">Clase</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="vencimiento" checked>
									<label class="form-check-label" for="vencimiento">Vencimiento</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="diasrestantes" checked>
									<label class="form-check-label" for="diasrestantes">Días restantes</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="divisa" checked>
									<label class="form-check-label" for="divisa">Divisa</label>
								</div>
							</div>
							<div class="col-6 form-group">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="totalgeneral" checked>
									<label class="form-check-label" for="totalgeneral">Total general</label>
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

	<?php
	Public_Page::footerTemplate('estadoCuenta');
	?>