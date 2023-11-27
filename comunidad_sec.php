<?php
include "cnfg/config.php";
include "inc/funciones.php";

$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp']: 0;
$link_foto_no_disponible = "cliente/img/noDisponible.jpg"; //foto test de maqueta
$tipo = 10;
agrega_acceso($tipo);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<link href="css/minisitios.css" rel="stylesheet" type="text/css" />
		<?PHP include ("head_marcas.php"); ?>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner"><?PHP include("header.php");?>
				<?php include_once 'menu.php'; ?>
				<div class="container">
					<div class="hd-minisitio mb15">Comunidad</div>
					<!-- BODY -->
					<?php
					/**
					 * ex col_izq.php
					 */
					echo '<div style="height:300px; float:left">';
					$colsec = 4; // Nacimientos
					include("col_novedades_sec.php");
					echo '</div>';
					echo '<div style="height:300px; float:left">';
					$colsec = 7; // Casamientos
					include("col_novedades_sec.php");
					echo '</div>';
					echo '<div style="height:300px; float:left">';
					$colsec = 6; // Graduaciones
					include("col_novedades_sec.php");
					echo '</div>';
					?>
					<!-- END BODY -->
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>