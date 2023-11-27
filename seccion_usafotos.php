<!--<div class="foto">-->

	<?PHP

	include_once("backend/inc/img.php");

	$hayfotoppal = 0;

	$generaconid = 0;

	// imagen principal

	$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $noticia['id'] . " AND tipo = " . $tipo . " LIMIT 1";

	//echo $sql_fotos;

	$res_fotos = fullQuery($sql_fotos);

	$total_fotos = mysqli_num_rows($res_fotos);

	if ($total_fotos > 0) {

		$row_fotos = mysqli_fetch_assoc($res_fotos);

		$foto_ppal = $row_fotos['link'];

		$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $noticia['id'] . " AND tipo = " . $tipo . " AND ppal = 1 limit 1";

		$res_fotos = fullQuery($sql_fotos);

		$cont_foto_ppal = mysqli_num_rows($res_fotos);

		if ($cont_foto_ppal == 1) {

			$row_fotos = mysqli_fetch_assoc($res_fotos);

			$foto_ppal = explode("imagen", $row_fotos['link'], -1);

			if(isset($tipofoto)){

				$foto_ppal = end($foto_ppal) . $tipofoto.".jpg";

			}else{

				$foto_ppal = end($foto_ppal) . "sec.jpg";

			}

			if (file_exists($foto_ppal)) {

				$tamfot = getimagesize($foto_ppal);

				$ancfot = $tamfot[0];

				$altfot = $tamfot[1];

				if ($ancfot >= 85 && $altfot >= 85) {

					$hayfotoppal = 1;

				?>

					<img src="<?php echo $foto_ppal; ?>" class="img-fluid rounded-1" />

		<?PHP } else {

					$generaconid = 1;

				}

			} else {

				$generaconid = 1;

			}

		}

	}

	if ($hayfotoppal == 0) {

		if ($generaconid == 1) {

			generaPpal($tipo, $noticia['id'], 1, $row_fotos['id']);

		} else {

			generaPpal($tipo, $noticia['id']);

		}

		?>

		

	<?PHP } ?>

<!--</div>-->