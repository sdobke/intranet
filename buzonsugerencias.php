<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$tipo = getPost('tipo',14);
$item = getPost('item',0);
agrega_acceso($tipo);
$errno = 0;
include_once("backend/inc/img.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?PHP echo $cliente;?> Intranet | Buz&oacute;n de Sugerencias</title>
<?PHP include ("head.php");?>
<link href="css/secciones.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="middle">
		<div class="middle_inner"><?PHP include("header.php");?>
			<?php include_once 'menu.php'; ?>
			<div class="container">
				<div class="col_ppal left"  >
					<!-- BEGIN BODY -->
					<div class="col_ppal left">
							<div class="hd-seccion">Buz&oacute;n de Sugerencias</div>
							<div class="formularios left mb15">
								Por favor, lee la Pol&iacute;tica del Buz&oacute;n de Sugerencias haciendo <strong><a href="politica_y_procedimiento_buzon_de_sugerencias.pdf">click aqu&iacute;</a></strong>.<br /><br />Hac&eacute; <strong><a href="alta.php?tipo=17">click aqu&iacute;</a></strong> para realizar una sugerencia.
							</div>
				  </div>
					<div class="clr"></div>
					<!-- END BODY -->
					
				</div>
				<?php include("col_der.php"); ?>
			</div>
			<div class="clr"></div>
		</div>
	</div>
	<!--<?PHP include("footer.php");?>-->
</body>
</html>