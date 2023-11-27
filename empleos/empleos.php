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
							Como profesionales de Empleos tenemos la misi&oacute;n de atraer, reclutar y seleccionar personas talentosas a fin  de garantizar un servicio de excelencia para nuestros clientes.
							<br /><br />Con este  objetivo dise&ntilde;amos el proceso de reclutamiento y selecci&oacute;n y continuamos  trabajando para enriquecer tu experiencia en la compa&ntilde;&iacute;a.<br /><br />
							Porque creemos que la identificaci&oacute;n del  talento es un trabajo de todos, hemos pensado &nbsp;dos programas que te permitir&aacute;n sentirte parte  desde diferentes perspectivas.
							<br /><br />
							<span class="titulos">Aqu&iacute; podr&aacute;s conocer:</span>
							<br /><br />
                            <ul>
								<li><a href="programa_postulaciones_internas.php">Programa de Postulaciones Internas</a></li>
								<li><a href="programa_referidos.php">Programa de Referidos</a></li>
                                <li><a href="novedades.php">Participaci&oacute;n en Universidades - Acciones de Branding</a></li>
                            </ul>
                            
                            ​Para sugerencias de Talleres o Cursos, envianos tus comentarios a: <a href="mailto:formacionydesarrollo@alsea.com.ar">formacionydesarrollo@alsea.com.ar</a><br /><br />
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