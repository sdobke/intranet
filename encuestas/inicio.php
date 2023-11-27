<?PHP include "../cnfg/config.php";?>
<?PHP include "../backend/inc/sechk.php";?>
<?PHP include "../inc/funciones.php";?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Encuestas</title>
<?PHP include ("head.php"); ?>
		<style>
			.success_box,
			.alert_box,
			.info_box
			{
				width: 900px !important;
			}
		</style>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="/index.php"><img src="../img_new/logo.png" width="170" height="75" /></a>
					</div>
				<?php 
				include_once '../login.php';
				?>
				</div>
				<div class="container">
					<div class="hd-minisitio mb5">Encuestas</div>					
					<div class="left w100" >						
						<?PHP include "menu.php";?>
						<div class="contenidos" >
							<div class="buscador-minisitio mt10">Por favor seleccione una opci&oacute;n del men&uacute;.</div>
							<div style="clear:both"></div>
						</div>
					</div>
					<div class="left w100 ac brd-t mt10"><img src="../img_new/cierre.png" width="630" height="71" /></div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>