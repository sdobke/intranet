<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?PHP echo $cliente;?> Intranet | Home</title>
<?PHP include ("head.php");?>
</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<?PHP include("header.php");?>
				<div class="container">
					<div class="col_ppal left">
						<!-- BEGIN BODY -->
						Hubo un error en el sistema. Por favor disculpe las molestias ocasionadas. Ya estamos trabajando en la soluci&oacute;n del mismo.
						<!-- END BODY -->
					</div>
					<?php include("col_der.php"); ?>
					<?PHP include("accesos_directos.php");?>
					
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<!--<?PHP include("footer.php");?>-->
	</body>
</html>