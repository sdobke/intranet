<?PHP
include "cnfg/config.php";
include "inc/funciones.php";
include "inc/inc_docs.php";
$mensaje = '';
$confirma = '';
include("inc/check_login.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?PHP echo $cliente;?> Intranet | Minisitios</title>
<?PHP if($inger == 1 && $usrger == 1){
	echo '<meta http-equiv="refresh" content="0; url=evaluaciones/index.php" />';
}
?>
<?PHP include ("head.php"); ?>
<link href="/css/minisitios.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="middle">
		<div class="middle_inner">
			<div id="header" class="mb10">
				<div id="logo" >
					<a href="/index.php"><img src="../img_new/logo.png" /></a>
				</div>
				<?php if($inger == 0 || ($inger == 1 && isset($_SESSION['tipousr']))){include_once('login.php');}?>
			</div>
			<?PHP
			$showMenu = true;
			$showMenuMS = false;
			?>
			<?php include_once ('minisitios_menu.php');?>
			<div class="container">
				<div class="hd-minisitio mb5"><?PHP echo $txtit;?></div>
                <?PHP if($inger != 1 && $usrger == 0 && !isset($_SESSION['tipousr'])){echo 'Ingres&aacute; con tu usuario para continuar.';}?>
				<div class="left w100" >						
					<?PHP 
					/* METODO ANTERIOR
					if($inger == 1 && $usrger == 0){
						if(isset($_SESSION['tipousr'])){
							echo '<div align="center">Para ingresar como Gerente de local cierre su usuario actual.</div>';
						}else{
							include("minisitios_login.php");
						}
					}*/
					// METODO NUEVO
					if($inger == 1 && $usrger == 0){
						include("minisitios_login.php");
					}
					// FIN METODO NUEVO
					?>
				</div>
				<div class="left w100 ac brd-t mt10"><img src="../img_new/cierre.png" width="630" height="71" /></div>
			</div>
			<div class="clr"></div>
		</div>
	</div>
</body>
</html>