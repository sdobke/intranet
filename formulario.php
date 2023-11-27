<?PHP

include "inc/startup.php";



$tipo = getPost('tipo', 7);

$tipo = getPost('tipodato', $tipo);

$nombre = $titsec = $nombretab = obtenerNombre($tipo);

$secc = getPost('sec', 1);

$secpag = $secc;

agrega_acceso($tipo);



include("backend/inc/leer_parametros.php");



if (!isset($_SESSION['tipousr'])) {

	$_SESSION['tipousr'] = 0;

}



?>

<!DOCTYPE html>

<html>



<head>

	<title><?PHP echo $cliente; ?> Intranet | <?PHP echo $nombre; ?></title>

	<?PHP include("sitio/head.php"); ?>

	<link rel="stylesheet" type="text/css" href="/assets/css/lightbox.css">

</head>



<body class="seccion seccion-<?php echo $nombre; ?>">

	<div class="flex-wrapper">

		<div class="container">

			<?PHP include("sitio/header.php"); ?>

			<div class="row">

				<div class="col-md-9 col-sm-12" id="col-izq">

					<div class="seccion-header">

						<h1><?PHP echo ucwords($titsec); ?></h1>

					</div>

					<?PHP if ($tipo == 17) { // Sugerencias

					?>

						<div class="row">

							<div class="col">

								<?php include("sugerencias/form.php"); ?>

							</div>

						</div>

					<?php } ?>

				</div>

				<div class="col-md-3 col-sm-12 dark" id="col-der">

					<?php include("col_der.php"); ?>

				</div>

			</div>

		</div>

		<?PHP include("sitio/footer.php"); ?>

	</div>

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

</body>



</html>