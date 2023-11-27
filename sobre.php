<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$tipo = 56;
agrega_acceso($tipo);
$id = getPost('cod');
$sql = "SELECT * FROM intranet_empresas WHERE id = " . $id;
$res = fullQuery($sql);
$row = mysqli_fetch_assoc($res);
$nomemp = txtcod($row['detalle']);
$codemp = txtcod($row['nombre']);
$textoemp = txtcod($row['texto']);
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
		<div class="container">
			<?PHP include("sitio/header.php"); ?>
			<div class="row">
				<div class="col-md-12 col-sm-12" id="col-der">
					<div class="cabecera_nota pb10 mt10">
						<h3>Historia</h3>
					</div>
					<p>
						<?PHP echo $textoemp; ?>
					</p>
				</div>
			</div>
		</div>
	</div>
	<?PHP include("sitio/footer.php"); ?>
	</div>
	<?PHP include("sitio/js.php"); ?>
</body>

</html>