<?PHP

include "cnfg/config.php";

include "inc/funciones.php";



$tipo = 6;

agrega_acceso($tipo);

$cod = GetPost('cod', 1);

?>

<!DOCTYPE html>

<html>



<head>

	<title><?PHP echo $cliente; ?> Intranet | Home</title>

	<?PHP include("head_marcas.php"); ?>

	<?PHP include("sitio/head.php"); ?>

</head>



<body>

	<div class="flex-wrapper">
			<?PHP include("sitio/header.php"); ?>

		<div class="container">


			<div class="row">

				<?PHP

				$name_empresa = "";

				$sqlem = "SELECT * FROM intranet_empresas WHERE id = " . $cod;

				$resem = fullQuery($sqlem);

				$rowem = mysqli_fetch_assoc($resem);

				$sqlmv = "SELECT mvv.* FROM intranet_mvv AS mvv INNER JOIN intranet_mvv_tipos AS imt ON mvv.tipomvv = imt.id WHERE mvv.empresa = " . $cod." AND mvv.del = 0";

				$resmv = fullQuery($sqlmv);

				$color_txt = ($cod == 2) ? "555" : "ccc";

				$name_empresa = strtoupper(txtcod($rowem['detalle']));

				//$textotit = txtcod($rowem['mvv']);

				$textotit = 'Misi&oacute;n, Visi&oacute;n y Valores';

				$include_menu = $rowem['nombre'];

				?>

				<div class="col-md-12 col-sm-12" id="col-der">

					<div class="">

						<h1 class="mt-3 mb-5"><?PHP echo $textotit; ?></h1>

					</div>

					<?PHP

					while ($rowmv = mysqli_fetch_array($resmv)) {

					?>

						<div class="cabecera_nota">

							<h3><span class="titulo"><?PHP echo txtcod($rowmv['titulo']); ?></span></h3>

						</div>

						<div class="texto">

							<p><?PHP echo txtcod($rowmv['texto']); ?></p>

						</div>

					<?PHP } ?>

				</div>

			</div>

		</div>

		<?PHP include("sitio/footer.php"); ?>

	</div>

	<?PHP include("sitio/js.php"); ?>

</body>



</html>