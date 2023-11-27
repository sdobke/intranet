<?PHP

$hay_ppal = 0;

$fotozoom = 0;

if ($tipo == 7 && $id == 32) {

	$fotozoom = 1;

}

// imagenes

$foto_full = '';

if ($total_fotos > 0) { // hay fotos

	$sql_fotos_trip = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo . " AND ppal = 0 ORDER BY id";

	//echo $sql_fotos_trip;

	$res_fotos_trip = fullQuery($sql_fotos_trip);

	$contador = 0;

	$fotrip = '';

	while ($row_fotos_trip = mysqli_fetch_array($res_fotos_trip)) {

		$fotrip .= '

		<a id="'.$contador.'" href="/' . $row_fotos_trip['link'] . '" data-lightbox="galeria">' . $row_fotos_trip['epigrafe'] . '</a>

		';

		$contador++;

	}



	$foto_link = '/cliente/img/noDisponible.jpg';

	$foto_ppal  = imagenpPal($id, $tipo, 1);

	if (isset($foto_ppal['link'])) {

		if (!file_exists($foto_ppal['link'])) {

			fotoCrearPpal($noticia['id'], $tipo);

			$foto_ppal  = imagenpPal($id, $tipo, 1);

			$foto_link = '/cliente/img/noDisponible.jpg';

		} else {

			$foto_link = $foto_ppal['link'];

			$foto_full = $foto_ppal['full'];

		}

	}



	$hay_ppal = 1;

	$textofot = ($total_fotos == 1) ? 'Click para ampliar' : 'Ver galería';

	if (file_exists($foto_full)) {

		if ($fotozoom == 1) {

			$foto_link = $foto_full;

		}

?>

		<div class="nota-media magnifier">

			<?php echo $fotrip; ?>

			<?php if ($fotozoom == 0) { ?>

				<a href="<?php echo $foto_full; ?>" data-lightbox="galeria">

				<?php } ?>

				<div class="row">

					<div class="col foto-ppal">

						<div class="img-magnifier-container">

							<?php if ($fotozoom == 1) {

								list($fancho, $falto) = getimagesize($foto_full);

								$fotoheight = $falto + 20;

							?>

								<iframe style="height:<?php echo $fotoheight; ?>px; width:100%" src="<?php echo $foto_full; ?>"></iframe>



							<?php } else { ?>

								<img id="fotobase" class="img-fluid" title="<?php echo $textofot; ?>" src="/<?php echo $foto_link; ?>" />

							<?php } ?>

						</div>

					</div>

				</div>

				<div class="row">

					<div class="col">

						<div class="d-flex justify-content-between">

							<?php if ($hay_ppal == 1 && $foto_ppal['epi'] != '') {

								include("nota/epigrafe.php");

							} ?>

							<?php if ($fotozoom == 0) { ?>

								<div class="link-foto btn btn-outline-dark btn-sm more icon-link icon-link-hover"><?php echo $textofot; ?></div>

							<?php } ?>

						</div>

					</div>

				</div>

				<?php if ($fotozoom == 0) { ?>

				</a>

			<?php } ?>

		</div>

<?php }

}

