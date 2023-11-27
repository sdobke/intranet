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
                            <div class="container mb10 pb15 t30 mt15 nettooffc c999999">Programa de Postulaciones Internas</div>
								En Alsea impulsamos tu desarrollo profesional  y te brindamos un espacio donde pod&eacute;s conocer las oportunidades laborales que tenemos activas.
                                <br /><br />A trav&eacute;s de "Postulate", nuestro Programa de Postulaciones Internas, podr&aacute;s enterarte de las b&uacute;squedas activas y aplicar a aquellas que sean de tu inter&eacute;s de acuerdo con los requisitos de la posici&oacute;n a cubrir.
                                <br /><br />
                                <img src="docs/tabpostint.jpg" />
                                <br /><br />
                                &iquest;Est&aacute;s listo para conocer tus nuevos desaf&iacute;os profesionales? <strong><a href="postulaciones_internas.php">HAC&Eacute; CLIC AQU&Iacute;</a></strong>
                                <br /><br /><strong>&iquest;D&oacute;nde puedo obtener mayor informaci&oacute;n?</strong>
                                <br /><br />
                              <ul>
                                <li><a href="docs/Politica_Postulaciones_internas.pdf" target="_blank">Pol&iacute;tica de Postulaciones Internas</a></li>
                                <li><a href="docs/Postulaciones_Internas_Preguntas_Frecuentes.pdf" target="_blank">Gu&iacute;a de Usuario</a></li>
                              </ul>
                            <br />Para mayor informaci&oacute;n: <a href="mailto:empleos@alsea.com.ar">empleos@alsea.com.ar</a><br /><br />
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