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
                       	  <div class="textos" style="padding-right:10px">
                              <p>En Alsea  implementamos programas de desarrollo que te permitir&aacute;n sentirte  acompa&ntilde;ado, guiado y alentado en cada etapa de tu crecimiento personal y  profesional.<br /><br />
A lo largo del  2011 llevamos adelante:                              
                            <p>
                            <br />
                            <ul>
                              <li>Evaluaciones de Potencial y Assessment Center para Promociones Gerenciales.</li>
                              <li>Programas de Coaching para tu desarrollo profesional.</li>
                              <li>An&aacute;lisis y detecci&oacute;n de perfiles potenciales por direcci&oacute;n (Pool de talentos).</li>
                            </ul>
                            <br />
                            <div style="clear:both"></div>
                            </p>
                            <p></p>
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