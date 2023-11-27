<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
require_once("../login_init.php");

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
						<a href="index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
					<?php include_once '../login.php'; ?>					
				</div>
				<?php include_once '../menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Desarrollo</div>
				<div class="container_inner">
					<div class="col_ppal right">
                       	   <div class="textos">
                                <div class="hd-seccion">Potencial</div>
							<div style="float:left; width:450px">
                          	Es el proceso a trav&eacute;s del cual se identifican fortalezas y oportunidades de mejora en el dominio de las competencias de la Compa&ntilde;&iacute;a en funci&oacute;n de la posici&oacute;n actual del participante.

                            <br /><br />Dirigido a Jefes, Gerentes y Directores, tiene como objetivo fundamental dise&ntilde;ar Planes de Desarrollo Individual (PID) para potenciar el desarrollo profesional.
	                            <br /><br /><strong>&iquest;D&oacute;nde?</strong> A trav&eacute;s de una evaluaci&oacute;n online (Asses).
	                            <br /><br />
	                            <strong>&iquest;C&oacute;mo?</strong> Descarg&aacute; la <a href="docs/instructivo_potencial.pdf">Gu&iacute;a de Usuario</a>.<br /><br />
	                        </div>
	                        <div style="width:160px; float:right">
	                        	<img src="img/logo-potencial.jpg" />
	                        </div>
                          </div>
                          <div style="clear: both"></div>
                          <a href="javascript:history.back(1)">Volver</a>
                       
                         </div>
				<?php include 'col_izq.php'; ?>
			</div>
			<div class="clr"></div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>