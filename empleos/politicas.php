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
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Empleos</div>
				<div class="container_inner">
					<div class="col_ppal right">
						 <div class="textos">
                            <p class="titulos">Pol&iacute;ticas Asociadas</p>
                                <p><br />
                                  <strong><a href="docs/Politica_de_Referidos.doc" target="_blank">Pol&iacute;tica del Programa de Referidos</a></strong></p>
                                <p>&nbsp;</p>
                                <p><strong><a href="docs/Politica_Postulaciones_internas.doc" target="_blank">Pol&iacute;tica del Postulaciones Internas</a></strong></p>
                                <p>&nbsp;</p>
                                <p><strong><a href="docs/Guia_Rapida.pdf" target="_blank">Gu&iacute;a R&aacute;pida</a></strong></p>
                                <p>&nbsp;</p>
                                <p><strong><a href="docs/Postulaciones_Internas_Preguntas_Frecuentes.doc" target="_blank">Programa de Postulaciones Internas - Preguntas Frecuentes</a></strong></p>
                                <p>&nbsp;</p>
                                <p><strong><a href="docs/Programa_de_Referidos_Preguntas_Frecuentes.doc" target="_blank">Programa de Referidos - Preguntas Frecuentes</a></strong></p>
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