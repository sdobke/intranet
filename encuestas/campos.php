<?PHP
include "../cnfg/config.php";
include "../backend/inc/sechk.php";
include "../inc/funciones.php";
include_once("func_encuestas.php");
function aplanaTexto($texto){
	$original  = array(" ","á","é","í","ó","ú","ñ","ü","-");
	$reemplazo = array("_","a","e","i","o","u","n","u","_");
	$devuelve  = str_replace($original,$reemplazo,strtolower($texto));
	return $devuelve;
}
$_SESSION['prefijo'] = 'intranet_';
global $error;
$error = 'ok';

// SI SE EJECUTO UNA FUNCION DE ABM
if (isset($_GET['func']) || isset($_POST['func'])){
	if (isset($_GET['func'])){$func = $_GET['func'];}else{$func = $_POST['func'];}
	include_once("func_abm.php");
}
?>
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
		<script type="text/javascript" src="inc/scripts.js"></script>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="/index.php"><img src="../img_new/logo.png" width="170" height="75" /></a>
					</div>
				<?php include_once '../login.php'; ?>					
				</div>
				<div class="container">
					<div class="hd-minisitio mb5">Encuestas</div>					
					<div class="left w100" >						
						<?PHP include "menu.php";?>
						<div class="contenidos" >
							<?PHP //include "menu_campos.php";
							$error = 'ok';
							include 'form_alta.php';
							include 'form_listado_campos.php';
							?>
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