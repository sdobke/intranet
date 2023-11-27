<?PHP

$tipo_col = 9;

$nombre_tab = obtenerDato('nombre', 'tablas', $tipo_col);

$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp'] : 0;

$sql_buscon = " SELECT inov.id AS id, inov.fecha AS fecha, inov.titulo AS titulo, inov.texto AS texto FROM intranet_" . $nombre_tab . " AS inov 

					INNER JOIN intranet_link AS il ON inov.id = il.item 

						WHERE il.tipo = " . $tipo_col . " " . $sql_restric . " AND del = 0 AND home = 1

				ORDER BY id LIMIT 1";

//echo $sql_buscon;

$res_buscon = fullQuery($sql_buscon);

$con_buscon = mysqli_num_rows($res_buscon);

if ($con_buscon == 1) {

	$row_buscon = mysqli_fetch_array($res_buscon);

	$id_galfot = $row_buscon['id'];

	$titulo = $row_buscon['titulo'];

	$noti_fecha = fechaDet($row_buscon['fecha'], 'corto', 's');

	$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $id_galfot . " AND tipo = " . $tipo_col . " ORDER BY RAND() LIMIT 1";

	//echo $sql_fotos;

	$res_fotos = fullQuery($sql_fotos);

	if (mysqli_num_rows($res_fotos) > 0) {

		$row_fotos = mysqli_fetch_array($res_fotos);

		$link_foto = $row_fotos['link'];

		$id_foto = $row_fotos['id'];

		$carpeta = explode("imagen", $link_foto, -1);

		$carpeta = end($carpeta);

		$link_foto = $carpeta . "thumb.jpg";

		$epigrafe = $row_fotos['epigrafe'];

		$link_foto_no_disponible = "cliente/img/noDisponible.jpg"; //foto test de maqueta

?>

		<div id="<?php echo $tipo_coldet; ?>" class="card">

			<div class="card-body sidebar_gallery">
					<h4>Galerías de fotos</h4>
				<div class="datos card-text">


					<?PHP if (file_exists($link_foto)) { ?>

						<a href="nota.php?tipo=9&id=<?PHP echo $id_galfot; ?>"><img src="<?PHP echo $link_foto; ?>"  /></a>

					<?PHP } else { ?>

						<img src="<?PHP echo $link_foto_no_disponible; ?>"  /><br />

					<?PHP } ?>




						<?PHP
						echo '<small class="text-body-secondary">'.$noti_fecha .'</small><br>';
						echo '<a class="title" href="nota.php?tipo=9&id=' . $id_galfot . '">' . txtcod($titulo) . '</a><br>';

						?>


					<?php
					if ($epigrafe != '') {
						 $epigrafe;
					} ?>				</div>

							<a class="btn btn-outline-dark btn-sm more icon-link icon-link-hover" href="seccion.php?tipo=<?PHP echo $tipo_col; ?>">Ver m&aacute;s galerías <i class="bi bi-chevron-right"></i></a>

				</div>

			</div>

		<?PHP } ?>

	<?PHP } ?>