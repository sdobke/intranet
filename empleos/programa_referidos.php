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
                                <div class="hd-seccion">Programa de Referidos</div>
                          	A trav&eacute;s del <strong>Programa de Referidos</strong>, alentamos y recompensamos tu colaboraci&oacute;n en el proceso de selecci&oacute;n cuando refer&iacut;s amigos o conocidos como candidatos potenciales para la Compa&ntilde;&iacute;.
                            <br /><br />
                            <strong>&iquest;C&oacute;mo participo?</strong>
                            <br /><br />
                            <table>
                            <tr><td>1.</td><td>Conoc&eacute; las oportunidades activas que tenemos para tus amigos</td></tr>
                            <tr><td>2.</td><td>Hac&eacute; clic en el t&iacute;tulo de la b&uacute;squeda que sea de tu inter&eacute;s.</td></tr>
                            <tr><td>3.</td><td>Eleg&iacute; en la opci&oacute;n "Referir a un amigo".</td></tr>
                            <tr><td>4.</td><td>Adjunt&aacute; y env&iacute;anos su CV.</td></tr>
                            <tr><td valign="top">5.</td><td>Si no encontr&aacute;s una vacante acorde al perfil de tu referido pero te interesa hacernos llegar su perfil, hac&eacute; clic en el &iacute;cono "Envianos su CV" para que podamos considerarlo en b&uacute;squedas futuras.</td></tr>
                            </table>
                            <br /><br />
                            <div align="center">
                                <table>
                                    <tr>
                                        <td><div class="bot_emp"><a href="postulaciones_referidos.php">B&Uacute;SQUEDAS ACTIVAS</a></div></td>
                                        <td>
                                            <div class="bot_emp">
                                                <a href="mailto:empleos@alsea.com.ar?subject=Programa de Referidos&body=No te olvides de adjuntar el CV de tu referido" onclick="agregaClick()">ENVIANOS SU CV</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
							</div>
							<br /><br /><strong>&iquest;Cu&aacute;l es la recompensa?</strong>
                            <br /><br />
                            Si tu referido ingresa a la compa&ntilde;&iacute;a y permanece por un plazo m&iacute;nimo de 3 meses recibir&aacute;s una gratificaci&oacute;n por tu compromiso.
                            <br /><br /><strong>&iquest;Qu&eacute; necesito saber?</strong>
                            <br /><br />
                            Para conocer a quienes podr&aacute;s referir, cuales son los plazos de vigencia de la referencia y los requisitos necesarios para la correcta participaci&oacute;n en el proceso, ingres&aacute; a:
                            <br /><br />
                            <ul>
                                <li><a href="docs/Politica_de_Referidos.pdf" target="_blank">Pol&iacute;tica del Programa de Referidos</a></li>
                                <li><a href="docs/Programa_de_Referidos_Preguntas_Frecuentes.pdf" target="_blank">Gu&iacute;a de Preguntas Frecuentes</a></li>
                            </ul>
							<br /><br />
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