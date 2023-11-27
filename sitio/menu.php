<?php

if ( isset( $minisitio ) ) {

	$pre_url = '../';

} else {

	$pre_url = '';

}



function MenuSec( $id, $nom, $pre_url, $sql_restric, $cargaemp = 0 )

{

	$ret = '';

	$mostrar = 0;

	$sql = "SELECT nombre, activable, restriccion FROM intranet_tablas WHERE id = " . $id;

	$res = fullQuery( $sql );

	$con = mysqli_num_rows( $res );

	if ( $con == 1 ) {

		$row = mysqli_fetch_assoc( $res );

		$sql2 = "SELECT tab.* FROM intranet_" . $row[ 'nombre' ] . " AS tab ";

		if ( $row[ 'restriccion' ] == 1 ) {

			$sql2 .= " INNER JOIN intranet_link AS lnk ON lnk.tipo = " . $id;

		}

		$sql2 .= " WHERE del = 0";



		//$menulnkpart = (isset($_SESSION['usrfrontend'])) ? " OR lnk.part = " . $_SESSION['usrfrontend'] : '';

		if ( $row[ 'restriccion' ] == 1 ) {

			$sql2 .= " " . $sql_restric . " ";

		}

		if ( $row[ 'activable' ] == 1 ) {

			$sql2 .= " AND activo = 1";

		}



		$res2 = fullQuery( $sql2 );

		$con2 = mysqli_num_rows( $res2 );

		if ( $con2 > 0 ) {

			$mostrar = 1;

		}

	}

	if ( $mostrar == 1 || $cargaemp == 1 ) { // Cargaemp son las secciones que carga el empleado como Clasificados o Recomendados

		$ret = '<li><a href="' . $pre_url . 'seccion.php?tipo=' . $id . '">' . $nom . '</a></li>';

		//$ret = '<li><a href="' . $pre_url . urlFriendly($nom). '.html">' . $nom . '</a></li>';

	}

	return $ret;

}

//$area_emple = (isset($_SESSION['usrfrontend'])) ? obtenerDato('area', 'empleados', $_SESSION['usrfrontend']) : 0;

$showMenu = true;

if ( $showMenu ) {

	?>

	<ul class="navbar-nav me-auto mb-2 mb-md-0">
		<li class="nav-item">
			<a class="nav-link" aria-current="page" href="<?php echo $pre_url; ?>index.php">Inicio</a>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Institucional</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="nota.php?id=28&tipo=7">Historia</a>
				</li>
				<li><a class="dropdown-item" href="<?php echo $pre_url; ?>mision.php">Misi&oacute;n, Visi&oacute;n y Valores</a>
				</li>
				<li><a class="dropdown-item" href="nota.php?id=32&tipo=7">Organigrama</a>
				</li>
				<li><a class="dropdown-item" href="nota.php?id=33&tipo=7">Organigrama</a>
				</li>

			</ul>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo $pre_url; ?>areas.php">Nuestra Gente</a>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">RSE</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="seccion.php?tipo=7&sec=15">Alianzas</a>
				</li>

			</ul>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/docs.php">Documentos</a>
		</li>

	</ul>
<a id="btn_buscador" class="btn btn-outline-light collapsed" role="button"  data-bs-toggle="collapse" data-bs-toggle="button" href="#buscador_container" aria-expanded="false" aria-controls="buscador_container"><i class="bi bi-search"></i></a>
	<?php include_once 'sitio/login.php';?>


	<!--<ul>-->



		<?PHP // Si es admin de minisitio

			if (isset($_SESSION['usrfrontend']) && $showMenu) {

				$sql_mini = "SELECT im.* FROM intranet_minisitios AS im 

									INNER JOIN intranet_minisitios_usuarios AS imu ON imu.minisitio = im.id

									WHERE imu.usuario = " . $_SESSION['usrfrontend'] . " GROUP BY im.id";

				//echo $sql_mini;

				$res_mini = fullQuery($sql_mini);

				$cont_mini = mysqli_num_rows($res_mini);

				$cont_mini = 0;

				if ($cont_mini > 0) {

					echo '<li><a class="dropdown-arrow" href="javascript:void(0)">Minisitios</a><ul class="sub-menus">';

				}

				while ($row_mini = mysqli_fetch_array($res_mini)) {

					$link_mini = $row_mini['link'];

					$id_minisitio = $row_mini['id'];

					$_SESSION['minisitio_' . $id_minisitio] = 'admin';

					echo '<li><a href="/' . $pre_url . $link_mini . '">' . txtcod($row_mini['nombre']) . '</a></li>';

					$_SESSION['empleos'] = $_SESSION['nombreusr'];

				}

				if ($cont_mini > 0) {

					echo '</ul></li>';

				}

			}

			?>

		

	<!--</ul>-->

	<?	PHP
		?>



	<?php } ?>