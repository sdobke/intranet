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
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Formacion</div>
				<div class="container_inner">
					<div class="col_ppal right">
                       	   <div class="textos">
                                <div class="hd-seccion">Plan Anual de Formaci&oacute;n</div>
                            <p>Te compartimos las iniciativas de capacitaci&oacute;n que desarrollamos para acompa&ntilde;ar tu crecimiento profesional. 
                            <br /><br />Nuestra gesti&oacute;n se sustenta a trav&eacute;s del siguiente <strong>Modelo de Aprendizaje</strong>:
                            <br /><br /><img src="docs/formacion.jpg" /><br /><br />
                            Si est&aacute;s interesado/a en participar en un taller o curso que es necesario para el desarrollo de tus tareas actuales, descarg&aacute; el Formulario de Capacitaciones Externas haciendo <a href="docs/Formulario_de_Solicitud_de_Capacitacion_Externa.doc">clic aqu&iacute;</a>.<br /><br />
                            Para sugerencias de Talleres o Cursos, envianos tus comentarios a: <a href="mailto:formacionydesarrollo@alsea.com.ar">formacionydesarrollo@alsea.com.ar</a><br /><br />
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