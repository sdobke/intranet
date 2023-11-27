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
                                <div class="hd-seccion">Gesti&oacute;n del Desempe&ntilde;o</div>
                          	Es un proceso sistem&aacute;tico que nos permite definir los objetivos a cumplir a lo largo del a&ntilde;o,  y clarificar nuestro dominio de las Competencias para contar con un seguimiento integral y medir el grado de cumplimiento manteniendo reuniones de feedback con jefes directos.
                            <br /><br />
                            <strong>&iquest;Cu&aacute;les son las etapas que componen el proceso?</strong>
                            <br /><br />
                            <img src="docs/desempeno.jpg" />
                            <br /><br />
                            <strong>&iquest;D&oacute;nde?</strong> Herramienta de Gesti&oacute;n del Talento -  <a href="http://mla.alsea.net/WEBEvalDesempeno/login.html" target="_blank">http://mla.alsea.net/WEBEvalDesempeno/login.html</a>
                            <br /><br /><strong>&iquest;C&oacute;mo?</strong> Guia de Usuario <br /><br />
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