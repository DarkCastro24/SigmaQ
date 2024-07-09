<?php
include('../../app/helpers/dashboard.php');
Dashboard_Page::headerTemplate('Sitio administradores', 'dashboard');
?>
<div id="fondo" class="container-fluid">
	<!-- Seccion de cabecera con imagen de fondo-->
	<div class="container">
		<h1 class="letraBlancaEspacio">Bienvenido al sistema de administradores</h1>
		<h3 class="letraBlancaIndex">SigmaQ</h3>
	</div>
</div> <!-- Cierra seccion de cabecera -->
<div id="contenido" class="container-fluid fondoBlanco">
	<!-- Seccion incluye todo el contenido del cuerpo la pagina -->

	<div id="estadisticas" class="container-fluid">
		<!-- Seccion de estadisticas -->
		<div class="container-fluid">
			<h3 class="centrar">Acciones realizadas por los clientes del sistema</h3>

			<form method="post" id="search-form">
				<div class="row">
					<div class="col-9 bajar">
						<!-- Campo de busqueda filtrada -->
						<input autocomplete="off" id="search" name="search" class="searchButtons form-control mr-sm-2 fondoInput" type="search" placeholder="Buscar por usuario, dispositivo, empresa o acción" aria-label="search">
					</div>
					<div class="col-3">
						<!-- Boton para busqueda filtrada -->
						<button class="centrarBoton btn btn-outline-info my-2 my-sm-0" type="submit">
							<i class="material-icons">search</i></button>
						</button>
					</div>
				</div>
			</form>
			<div class="table-responsive">
				<table class="table borde">
					<thead class="thead-dark">
						<tr>
							<th scope="col">Usuario</th>
							<th scope="col">Empresa</th>
							<th scope="col">Acción realizada</th>
							<th scope="col">Fecha y hora</th>
							<th scope="col">Sistema operativo</th>
							<th scope="col">Dispositivo</th>
						</tr>
					</thead>
					<tbody id="tbody-rows">
					</tbody>
				</table>
				<div id="seccionPaginacion" class="clearfix">
				<!-- Seccion controladores tabla -->
				</div> <!-- Cierra controladores de tabla -->
			</div>
		</div>
	</div> <!-- Cierra secion estadisticas -->
</div> <!-- Cierra la seccion de contenido -->
<?php
Dashboard_Page::footerTemplate('main');
?>