<?PHP



use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\SMTP;

use PHPMailer\PHPMailer\Exception;



//Load Composer's autoloader

require 'vendor/autoload.php';



//Create an instance; passing `true` enables exceptions

$mail = new PHPMailer(true);



include "cnfg/config.php";

include "inc/funciones.php";

include "inc/security.php";

$es_home = 1;

require_once("login/init.php");



if (isset($_SESSION['usrfrontend']) && $_SESSION['usrfrontend'] > 0) {



	if (isset($_GET['redirected']) && isset($_GET['reqFile'])) {

		$addGet = '';

		if (isset($_GET['tipo']) && isset($_GET['id'])) {

			$addGet = '?tipo=' . $_GET['tipo'] . '&id=' . $_GET['id'];

		}

		$newLoc = "https://" . $server . "/" . $_GET['reqFile'] . $addGet;

		//echo $newLoc;

		header("Location: " . $newLoc);

		die();

	}



	$login = 1;

	$tipo = 26;

	//agrega_acceso($tipo);

	$tipo = 7;

	$link_destino_search = 'areas.php';

	$usadestacada = 0;

	$usauser   = 1;

	$usact     = 1;

	$usainvita = 1;



	// Seleccionono una novedad para mostrar en el popup

	$SQL = "SELECT inov.id AS id FROM " . $_SESSION['prefijo'] . "novedades AS inov

				INNER JOIN " . $_SESSION['prefijo'] . "link AS il ON inov.id = il.item

				WHERE 1 AND inov.seccion = 8 AND il.tipo = " . $tipo . " " . $sql_restric . " AND inov.del = 0

				ORDER BY inov.fecha DESC

				LIMIT 1";

	$RES = Fullquery($SQL);

	if (mysqli_num_rows($RES)) {

		$ROW = mysqli_fetch_array($RES);

		$linkPopup = "'home/nota_popup.php?id=" . $ROW['id'] . "&tipo=" . $tipo . "'";

		$linkPopup = 'onload="Abrir_ventana(' . $linkPopup . ')"';

	}

}

?>

<!DOCTYPE html>

<html>



<head>

	<title><?PHP echo $cliente; ?> Intranet | Home</title>

	<?PHP include("sitio/head.php"); ?>

	<!--<link href="/assets/css/sheetslider.min.css" rel="stylesheet" type="text/css" />-->

	<!--<link href="/assets/css/jquery.barousel.css" rel="stylesheet" type="text/css" />-->

	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

	<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css" />

	<?php if ($login <> 1) { ?>

		<link href="/assets/css/home_login.css" rel="stylesheet" type="text/css" />

	<?php } ?>

</head>

<?php

$csshome = '';

if ($login != 1) {

	$csshome = "homebody";

} ?>



<body <?php echo $linkPopup; ?> class="home <?php echo $csshome; ?>">

	<?php if ($login == 1) {

	?>

		<?php $not_in = '0'; ?>

		<div class="flex-wrapper">


				<?PHP include("sitio/header.php"); ?>

				<?php /*

				<div class="row">

					<div class="col"><?PHP include_once('home/slider.php'); ?></div>

				</div>

				*/ ?>
				<div class="container">

				<div class="row g-5">

					<div class="col-lg-9" id="col-izq">

						<?PHP include_once('home/slider_slick.php'); ?>

						<?php include_once('home/noticias-wide.php'); ?>

						<?php include_once('home/noticias.php'); ?>

					</div>

					<div class="col-lg-3" id="col-der">

						<?PHP include("col_der.php"); ?>

					</div>

				</div>

			</div>
			<?php //include("home/accesos_directos.php"); 

			?>

			<?PHP include("sitio/footer.php"); ?>

		<?php } else { // Si no hay login

		//if (isset($_GET['codigo'])) {include("index_restore.php");}

		//if (isset($hacer_login) && $hacer_login == 1) {

		include("login/login.php");

		//}

	}

		?>

		</div>

		<?PHP include_once("sitio/js.php"); ?>

		<script language="JavaScript" type="text/javascript">

			function Abrir_ventana(pagina) {

				var opciones = "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=400,top=85,left=140";

				window.open(pagina, "", opciones);

			}

		</script>

		<script>

			$(document).ready(function() {

				$("#mailsend").click(function() {

					$(".card").hide();

					$("#mailAnim").show();

				});

				$("#guarda_pass").submit(function(e) {

					e.preventDefault();

					if ($("#pass1").val() != $("#pass2").val()) {

						$("#restore_error").html("Las contraseñas no coinciden. Por favor intente nuevamente.");

						$("#restore_error").slideDown();

					} else {

						$("#guarda_pass")[0].submit();

					}

				});

			});

		</script>

		<?php if ($login == 1) { ?>

			<!--<script src="/assets/js/sheetslider.min.js"></script>-->

			<!--<script src="/assets/js/jquery.barousel.js"></script>

			<script type="text/javascript">

				$(document).ready(function() {

					$('#any_id').barousel();

				});

			</script>-->

			<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

			<script>

				$(document).ready(function() {

					$('.slickslider').slick({

						accessibility: true,

						adaptiveHeight: true,

						autoplay: true,

						autoplaySpeed: 3000,

						arrows: true,

						dots: true



					});

				});

			</script>

		<?php } ?>

</body>



</html>