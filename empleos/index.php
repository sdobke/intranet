<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
require_once("../login_init.php");
$tipo   = 36;
agrega_acceso($tipo);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_empleos.php"); ?>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="../index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
					<?php include_once '../login.php';?>					
				</div>
				<?php include_once '../menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999">Gesti&oacute;n de Talento</div>
				<div class="container_inner">
					<div class="col_ppal right">
						<div class="textos">
                              Bienvenidos/as
						</div>
                        <a href="javascript:history.back(1)">Volver</a>
					</div>
					<?php include 'col_izq.php'; ?>
			</div>
			<div class="clr"></div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>