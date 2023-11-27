<?php

$linkbus = 'buscador.php';

$busholder = 'Ingrese Nombre o Apellido...';

$link_destino_search = 'areas.php';

/*

switch ($_SERVER['PHP_SELF']) {

	case '/seccion.php':

	case '/nota.php':

		if ($tipodet = 'novedades') {

			$busholder = 'Buscar en novedades...';

			$link_destino_search = 'seccion.php';

		}

		break;

}

*/

$busval = $styleb = '';

//print_r($_REQUEST);

if ( isset( $_REQUEST[ "busqueda" ] ) && $_REQUEST[ "busqueda" ] != '' ) {

	$busval = $_REQUEST[ "busqueda" ];

	$styleb = 'style="display:block"';

}

$tipodato = 4;

if ( isset( $_REQUEST[ 'tipodato' ] ) ) {

	$tipodato = $_REQUEST[ 'tipodato' ];



	switch ( $tipodato ) {

		case 3:

			$link_destino_search = 'docs.php';

			break;

		case 4:

		case 1:

			$link_destino_search = 'areas.php';

			break;

		case 7:

		case 9:

		case 14:

		case 20:

			$link_destino_search = 'seccion.php';

			break;

	}

}

//echo '<br>Tipodato: '.$tipodato;

?>
<div class="collapse" id="buscador_container">
	<div class="card card-body">
			<form class="row" role="search" action="<?PHP echo $link_destino_search; ?>" id="searchform" method="GET">
				<div class="col-7">
					<input class="form-control" type="search" name="busqueda" id="busqueda" placeholder="<?PHP echo $busholder; ?>" value="<?php echo $busval; ?>" aria-label="Search">
				</div>
				<div class="col-3">
					<select class="form-select" name="tipodato" id="tipodato">
						<?php
						$sql_tda = "SELECT id, detalle, buscador_texto FROM intranet_tablas WHERE buscador_texto != '' AND menu = 1";
						$res_tda = fullQuery( $sql_tda );
						while ( $row_tda = mysqli_fetch_array( $res_tda ) ) {
							?>
						<option value="<?php echo $row_tda['id']; ?>" <?php echo optSel($tipodato, $row_tda[ 'id']); ?>>
							<?php echo txtcod($row_tda['detalle']); ?>
						</option>
						<?php } ?>
					</select>
				</div>
				<div class="col-2">
					<button class="btn btn-outline-light col-12" type="submit">Buscar</button>
				</div>
			</form>
		</div>
</div>