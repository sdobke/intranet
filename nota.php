<?PHP

$ean = 'nota.php';

include "inc/startup.php";

include "inc/inc_docs.php";



$tipo = getPost('tipo', 7);

$nombre = obtenerNombre($tipo);

$nombre_titulo = parametro('detalle', $tipo);

$titsec = $nombre;

include("backend/inc/leer_parametros.php");

$texto = 'texto';

$titulado = '';

$ver_nomtit = 1;

$ver_fecha = 1;

$continuacion = 0;

if ($tipo == 14 || $tipo == 7) { // Recomendados o novedades

	$otro_query = " AND inov.activo = 1";

}

if ($tipo != 17) {

	$ver_fecha == 0;

}

$link_destino_search = $nombre . '.php';

//$link_destino_search = 'novedades.php';

$id = getPost('id');

//agrega_intento_acceso_nota($tipo,$id);

$where_adicional = (isset($otro_query)) ? " " . $otro_query : '';

$query = "SELECT * FROM " . $_SESSION['prefijo'] . $nombre . " AS inov 

						INNER JOIN intranet_link AS il 

						ON inov.id = il.item AND il.tipo = ".$tipo."

						WHERE inov.id = " . $id . $where_adicional . " AND inov.del = 0

						".$sql_restric."

						";



//echo $query;

$result = fullQuery($query);

$contar = mysqli_num_rows($result);

if ($contar == 1) {



	agrega_acceso($tipo);

	agrega_acceso_nota($tipo,$id);



	$noticia = mysqli_fetch_array($result);



	if ($tipo == 15 && isset($_SESSION['usrfrontend'])) {

		$sql_yapart  = "SELECT id FROM intranet_participantes WHERE concurso = {$id} AND tipoconcurso = {$tipo} AND usuario = " . $_SESSION['usrfrontend'];

		$res_yapart  = fullQuery($sql_yapart, $ean);

		$con_yapart  = mysqli_num_rows($res_yapart);

		$yaparticipa = ($con_yapart > 0) ? 1 : 0;

	} else {

		$yaparticipa = 0;

	}

	$txinv = '';

	// Secciones interactivas

	$usainvita = 0;

	$link_invita = '';



	if ($tipo == 18 || $tipo == 14 || ($tipo == 15 && $yaparticipa == 0) || $tipo == 11) { // Si son clasificados o recomendados o concurso de fotos

		$usainvita = 1;

		$link_destino = "alta.php?tipo=" . $tipo;

		if (isset($_SESSION['usrfrontend'])) {

			switch ($nombre) {

				case 'clasificados':

					$txinv = '&iquest;Quer&eacute;s publicar un aviso clasificado? Hac&eacute; click <strong>AQU&Iacute;</strong>';

					break;

				case 'recomendados':

					$txinv = '&iquest;Quer&eacute;s publicar una recomendaci&oacute;n? Hac&eacute; click <strong>AQU&Iacute;</strong>';

					break;

				case 'concurfotos':

					$link_destino .= "&item=" . $id;

					$txinv = '&iquest;Quer&eacute;s participar con tu foto? Hac&eacute; click <strong>AQU&Iacute;</strong>';

					//$txinv = '';

					$usafotos = 1;

					break;

			}

			if ($txinv != '') {

				$link_invita .= '<a href="' . $link_destino . '">';

				$link_invita .= $txinv;

				$link_invita .= '</a>';

			}

		} else {

			if ($nombre == 'encuestas') {

				$link_destino = "nota.php?tipo=" . $tipo . "ANDVARid=" . $id;

				$txinv = 'No olvides ingresar con tu usuario para participar de esta encuesta. Hac&eacute; click <strong>AQU&Iacute;</strong>';

			} else {

				$link_invita .= '<a href="' . $link_destino . '">';

				$link_invita .= 'Record&aacute; que ten&eacute;s que ingresar con tu usuario para participar.';

				$link_invita .= '</a>';

			}

		}

	}

	if ($yaparticipa == 1 && isset($_GET['ok']) && $_GET['ok'] == 1 && $tipo == 15) { // Si es un concurso y acaba de subir su foto

		$link_invita = 'Gracias por participar. Tu foto tiene que ser aprobada para ingresar al concurso. Una vez aprobada va a aparecer en el listado.';

		$usainvita = 1;

	}

	// Fin secciones interactivas

	if ($usatexto == 1) {

		$noticia_texto = $noticia[$texto];

		$parsedAll = getContents($noticia_texto, '<oembed url="https://www.youtube.com', '</oembed>');

		foreach ($parsedAll as $ytv) {

			//echo '<br>Nuevo vid en texto: <br><br>codigo: ' . $ytv;

			if ($ytv != '') {

				$nytv = preparaVideo($ytv);

				$noticia_texto = str_replace('<oembed url="https://www.youtube.com' . $ytv . '</oembed>', $nytv, $noticia_texto);

			}

		}

		$parsedAll2 = getContents($noticia_texto, '<oembed url="https://youtu.be', '</oembed>');

		foreach ($parsedAll2 as $ytv2) {

			//echo '<br>Nuevo vid en texto: <br><br>codigo: ' . $ytv;

			if ($ytv2 != '') {

				$nytv = preparaVideo($ytv2);

				$noticia_texto = str_replace('<oembed url="https://youtu.be' . $ytv2 . '</oembed>', $nytv, $noticia_texto);

			}

		}

		$parsedAll3 = getContents($noticia_texto, '<i>https://youtube.com/shorts', '</i>');

		foreach ($parsedAll3 as $ytv3) {

			//echo '<br>Nuevo vid en texto: <br><br>codigo: ' . $ytv;

			if ($ytv3 != '') {

				$nytv = preparaVideo($ytv3);

				$noticia_texto = str_replace('<i>https://youtube.com/shorts' . $ytv3 . '</i>', $nytv, $noticia_texto);

			}

		}

	}

	$idsec = 0;

	$linksec = '';

	if ($tipo == 7) { // Novedades

		if ($noticia['seccion'] > 1) {

			$sql_sec = "SELECT id, nombre AS nom FROM intranet_secciones WHERE id = " . $noticia['seccion'];

			$res_sec = fullQuery($sql_sec, $ean);

			$row_sec = mysqli_fetch_assoc($res_sec);

			$nombre_titulo = $row_sec['nom'];

			$linksec = '&secc=' . $noticia['seccion'];

			$idsec = $row_sec['id'];

			if ($tipo == 7 && $idsec == 10) { // Si son páginas

				$ver_nomtit = 0;

				$ver_fecha = 0;

			}

		}

	}

	$titulo = $noticia[$tipotit];

	if ($tipo == 12) { // Si es Revista

		$titulo = 'Edici&oacute;n Nro. ' . $titulo;

	}

} else {

	$titulo = 'No encontramos lo que est&aacute;s buscando';

}

?>

<!DOCTYPE html>

<html>



<head>

	<title><?PHP echo ucwords($cliente); ?> Intranet | <?PHP echo txtcod($titulo); ?></title>

	<?PHP include("sitio/head.php"); ?>

	<link rel="stylesheet" type="text/css" href="/assets/css/tipr.css">

	<link rel="stylesheet" type="text/css" href="/assets/css/lightbox.css">

</head>



<body class="nota <?php echo $nombre; ?>">


		<?PHP include("sitio/header.php"); ?>
	<div class="container">

		<div class="row justify-content-md-center">

			<!--<div class="col-md-8 col-sm-12" id="col-izq">-->

			<div class="col-md-8 card p-5" id="col-izq">

				<?PHP if ($contar == 1) { ?>

					<?PHP if ($usainvita == 1 && $link_invita != '') { ?>

						<div class="invita"><?php echo $link_invita; ?></div>

					<?PHP } ?>

					<div class="nota-header d-flex justify-content-between">

						<?php if ($ver_nomtit == 1) { ?>

							<h5>

								<span class="seccion">

									<a href="seccion.php?tipo=<?PHP echo $tipo; ?><?PHP echo $linksec; ?>">

										<?PHP echo txtcod($nombre_titulo); ?>

									</a>

									<?PHP if ($tipo == 17) { // Buzón de Sugerencias

										echo ' | ' . fechaDet($noticia['fecha']);

									} ?>

								</span>

							</h5>

						<?php } else { ?>

							<h1 class="mb-5 mt-3"><?PHP echo txtcod($titulo); ?></h1>

						<?php } ?>



						<div class="herramientas">

							<a href="javascript:agrandarTexto();"><img src="img_new/herramientas/opn_tipomas_on.gif" alt="Aumentar tamaño" width="24" height="21" class="mr2" /></a>

							<a href="javascript:achicarTexto();"><img src="img_new/herramientas/opn_tipomenos_on.gif" alt="Reducir tamaño" width="24" height="21" class="mr2" /></a>

						</div>

					</div>

					<?php if ($ver_nomtit == 1) { ?>

						<h1 class="mb-5 mt-3"><?PHP echo txtcod($titulo); ?></h1>

					<?php } ?>

					<?php

					if ($usafecha == 1 && $ver_fecha == 1) { // Si usa fecha y no es buzón de sugerencias

						echo '<div class="fecha">';

						if ($noticia['fecha'] == date("Y-m-d")) {

							echo "Hoy";

						} else {

							echo fechaDet($noticia['fecha']);

						}

						if ($usahora == 1) {

							echo ' | <span class="hora"> ' . substr($noticia['hora'], 0, 5) . '</span>';

						}

						echo '</div>';

						if(isset($noticia['copete']) && $noticia['copete'] != ''){

							echo '<div class="copete">'.txtcod($noticia['copete']).'</div>';

						}

					}

					if ($usafotos == 1 || $usavideos == 1) {

						echo '<div class="row g-0 nota-media-container">';

						//echo '<div class="col-md-8" id="col-izq">';

						echo '<div class="col-12" id="col-izq">';

							include('nota/media.php');

						echo '</div>';

						//echo '<div class="col-md-4" id="col-der">';

						?>

						<?PHP /* if ($usacoment == 1) { ?>

							<h3>Comentar</h3>

							<?php include "nota/comentarios_ver.php"; ?>

							<div class="comentar"><?php include "nota/comentar.php"; ?></div>

						<?PHP } */

						//echo '</div>';

						echo '</div>';

					}

					?>

					<div class="nota-cuerpo">

						<?php

						if ($usatexto == 1 && $noticia_texto != '') {

							echo '<p>' . txtcod($noticia_texto) . '</p>';

							echo '<div style="clear:both"></div>';

						}



						if ($nombre == 'concurfotos') {

							include("inc/concurfotos.php");

						}

						if ($nombre == 'sorteos') {

							include("inc/sorteos.php");

						}

						if ($nombre == 'encuestas') {

							include("inc/encuestas.php");

						}

						?>

					</div>

					<div class="nota-post">

						<?PHP

						if ($usatexto == 1) {

							echo '<p>' . $titulado . '</p>';

						}

						if ($tipo == 14) {

							include_once("inc/inc_recomendados.php");

						}

						if ($usalink == 1) {

							include_once("inc/inc_notadoc.php");

						}

						?>

						<?php if($usagusta == 1){include "nota/me_gusta.php";}?>

						<?PHP if ($usacoment == 1) { ?>

							<div class="comentarios"><?php include "nota/comentarios_ver.php"; ?></div>

							<div class="comentar"><?php include "nota/comentar.php"; ?></div>

						<?PHP } ?>

					</div>

				<?PHP } else {

					echo 'No pudimos encontrar el contenido que estás buscando.';

				} ?>

			</div>

			<!--

			<div class="col-md-3 col-sm-12 dark" id="col-der">

				<?php $restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp'] : 0;?>

				<?php //include("col_der.php"); ?>

			</div>

			-->

		</div> <!-- fin row -->

	</div> <!-- fin container -->

	<?PHP include("sitio/footer.php"); ?>

	<?PHP include("sitio/js.php"); ?>



	<?PHP if ($usafotos == 1 || $tipo == 15) { ?>

		<script type="text/javascript" src="/assets/js/lightbox.js"></script>

		<script>

			lightbox.option({

				'resizeDuration': 200,

				'wrapAround': true

			})

		</script>

	<?PHP } ?>

	<script type="text/javascript" src="/assets/js/tipr.min.js"></script>

	<script>

		$(document).ready(function() {

			$('.tip').tipr({

				'speed': 300,

				'mode': 'top'

			});

		});

	</script>

</body>



</html>