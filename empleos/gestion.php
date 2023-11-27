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
                            	<span class="titulos">Gesti&oacute;n del Desempe&ntilde;o</span>
                            	<br /><br />
                           		<span class="subtitulos">&iquest;Qu&eacute; es?</span>
                            	<br /><br />
                            	Es un proceso sistem&aacute;tico que nos permite definir los objetivos a cumplir a lo largo del a&ntilde;o, darle seguimiento y medir su grado de cumplimiento manteniendo reuniones de feedback con jefes directos.
                                <br /><br />
                            	<span class="subtitulos">&iquest;Para qu&eacute; sirve?</span>
                            	<br /><br />
                           	  	A trav&eacute;s de la herramienta podemos evaluar el desempe&ntilde;o planificado  vs. el desempe&ntilde;o obtenido y elaborar planes de acci&oacute;n que nos permitan  trabajar aspectos a mejorar, potenciar  competencias adquiridas y continuar desarroll&aacute;ndonos profesionalmente.
                              <p><br />
                                <span class="subtitulos">&iquest;Cu&aacute;les son las etapas que componen el proceso?</span><br /><br />
                                
                                <div align="center"><img src="img/grafico.jpg" width="650" height="162" /></div>
                            <br />
                            <ul>
                              <li>Planificaci&oacute;n: el colaborador y jefe inmediato  revisan y definen las expectativas que se tienen del colaborador durante el  a&ntilde;o.</li><br />
                              <li>Revisi&oacute;n  a mitad de a&ntilde;o: Revisi&oacute;n cualitativa del desempe&ntilde;o durante los primeros  6 meses del proceso.</li>
                              <li>Evaluaci&oacute;n: en esta etapa se califica el  desempe&ntilde;o del colaborador logrado durante el a&ntilde;o con base a las metas esperadas  en sus indicadores y las escalas de referencia de cada uno.</li><br />
                              <li>Firma: colaborador y jefe inmediato firman  electr&oacute;nicamente el formulario de desempe&ntilde;o ALSEA quedando ratificado que se  llev&oacute; a cabo el proceso de desempe&ntilde;o del a&ntilde;o. </li><br />
                              <li>Completado: estatus que indica que el proceso de  desempe&ntilde;o ha concluido.</li>
                            </ul>
                            <br />
                            <table width="100%" style="text-align:left">
                            	<tr>
                                	<td width="110"><img src="img/objetivos2011.jpg" /></td>
                                    <td width="10"></td>
                                    <td width="90"><a href="docs/Instructivo para Colaboradores.ppt">Gu&iacute;a de revisi&oacute;n de objetivos mitad de a&ntilde;o</a></td>
                                    <td width="10"></td>
                                    <td width="110"><img src="img/objetivos2011.jpg" /></td>
                                    <td width="10"></td>
                                <td width="70"><a href="docs/Guia Rapida.pdf">Descarg&aacute; la<br />gu&iacute;a r&aacute;pida<br />de Usuario</a></td>
                                    <td width="10"></td>
                                    <td width="110"><img src="img/objetivos2011.jpg" /></td>
                                    <td><a href="docs/Taller_Fijacion_de_Objetivos_2011.PPT">Descarg&aacute; el contenido del Taller de Fijaci&oacute;n de Objetivos</a></td>
								</tr>
                            </table>
                            <div style="clear:both"></div>
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