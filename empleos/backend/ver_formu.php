<?PHP 
include ("../../cnfg/config.php");
include ("sechk.php");
include ("inc/inc_funciones_globales.php");

$id     = getPost('id');
$puesto = getPost('puesto');

$nom_puesto = obtenerDato('nombre','busquedas',$puesto,'empleos');
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo</title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
<?PHP
function nivelaIdioma($valor){
	switch($valor){
		case 1:
			$devol = 'B&aacute;sico';
			break;
		case 2:
			$devol = 'Intermedio';
			break;
		case 3:
			$devol = 'Avanzado';
			break;
		case 4:
			$devol = 'Biling&uuml;e';
			break;
		default:
			$devol = 'Sin ingresar';
			break;																	
		}
	return $devol;
}
?>

<script type="text/javascript" src="js/scripts.js"></script>
</head>
<body>
<div id="contenedor">
	<div id="header"> </div>
    <?PHP include ("menu.php");?>

	<div style="width:728px; float:left">
        <br /><a href="detalles.php?tipo=1&id=<?PHP echo $puesto;?>">Volver</a>
		    <table>
                       	<?PHP
						$sql_canti = "SELECT * FROM empleos_postulantes WHERE id = ".$id;
						$res_canti = fullQuery($sql_canti);
						$con_canti = mysqli_num_rows($res_canti);
						if($con_canti > 0){
							$cont = 1;
							?>
							<tr>
								<td colspan="2" align="center">
                                   	<table>
                                       	<tr>
                                           	<td>
												<br /><h2><?PHP echo 'Puesto: '.$nom_puesto;?></h2><br />
												<div style="clear:both"></div>
												<?PHP
												while($row_canti = mysqli_fetch_array($res_canti)){
													$id_postu = $row_canti['id'];
													?>
													<div class="dhtmlgoodies_question">
														<h3>
															<?PHP echo 'Nombre: '.$row_canti['nombre'];?>
														</h3>
													</div>
													<div class="dhtmlgoodies_answer">
														<div align="left" style="float:left">
															<table class="tabla_answer">
																<tr>
																	<td class="neg">Fecha de nacimiento:</td>
																	<td><?PHP echo FechaDet($row_canti['fechanac'],'largo','s');?></td>
                                                                </tr>
																<tr>
																	<td class="neg">Domicilio:</td>
																	<td><?PHP echo $row_canti['direccion'];?></td>
                                                                </tr>
																<tr>
																	<td class="neg">Tel&eacute;fono:</td>
																	<td><?PHP echo $row_canti['telefono'];?></td>
                                                                </tr>
																<tr>
																	<td class="neg">E-mail:</td>
																	<td><?PHP echo $row_canti['email'];?></td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!--TRABAJOS-->
                                                                                                                        
                                                            <?PHP
															$sql_empl = "SELECT * FROM empleos_trabajos WHERE postulante = ".$id_postu;
															$res_empl = fullQuery($sql_empl);
															$can_empl = mysqli_num_rows($res_empl);
															if($can_empl > 0){
																$cont_empl = 1;
																echo '<br /><div align="center" style="background-color:#777; color:#DDD;"><strong>EXPERIENCIA LABORAL</strong></div><br />';
																while($row_empl = mysqli_fetch_array($res_empl)){
																	if($cont_empl > 1){
																		echo '----------------------------------------------------<br />';
																	}
																	?>
                                                                    <table class="tabla_answer">
                                                                        <tr>
                                                                            <td class="neg">Empresa:</td>
                                                                            <td><?PHP echo $row_empl['empresa'];?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Posici&oacute;n:</td>
                                                                            <td><?PHP echo $row_empl['posicion'];?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Per&iacute;odo:</td>
                                                                            <?PHP $fecha_fin = ($row_empl['fechaegr'] == '1111-11-11') ? 'al presente' : FechaDet($row_empl['fechaegr'],'corto','s');?>
                                                                            <td><?PHP echo FechaDet($row_empl['fechaing'],'corto','s');?> - <?PHP echo $fecha_fin;?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Descripci&oacute;n:</td>
                                                                            <td><?PHP echo $row_empl['descripcion'];?></td>
                                                                        </tr>
                                                                    </table>
																	<?PHP $cont_empl++;?>
																<?PHP }	?>
															<?PHP }	?>
                                                            
                                                            <!--ESTUDIOS-->
                                                            
															<?PHP
															$sql_empl = "SELECT * FROM empleos_educacion WHERE postulante = ".$id_postu;
															$res_empl = fullQuery($sql_empl);
															$can_empl = mysqli_num_rows($res_empl);
															if($can_empl > 0){
																$cont_empl = 1;
																echo '<br /><div align="center" style="background-color:#777; color:#DDD;"><strong>ESTUDIOS</strong></div><br />';
																while($row_empl = mysqli_fetch_array($res_empl)){
																	if($cont_empl > 1){
																		echo '----------------------------------------------------<br />';
																	}
																	?>
                                                                    <table class="tabla_answer">
                                                                        <tr>
                                                                            <td class="neg">Nivel:</td>
                                                                            <td><?PHP echo $row_empl['nivel'];?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Carrera:</td>
                                                                            <td>
																				<?PHP 
																				echo obtenerDato('nombre','carrera',$row_empl['carrera']);
																				if($row_empl['otra'] != ''){
																					echo '<br />'.$row_empl['otra'];
																				}
																				?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Estado:</td>
                                                                            <td><?PHP echo $row_empl['estado'];?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Materias aprobadas:</td>
                                                                            <td><?PHP echo $row_empl['aprobadas'];?> de <?PHP echo $row_empl['materias'];?></td>
                                                                        </tr>
                                                                    </table>
																	<?PHP $cont_empl++;?>
																<?PHP }	?>
															<?PHP }	?>
                                                            
                                                            <!--IDIOMAS-->
                                                            
															<?PHP
															$sql_empl = "SELECT * FROM empleos_idiomas WHERE postulante = ".$id_postu;
															$res_empl = fullQuery($sql_empl);
															$can_empl = mysqli_num_rows($res_empl);
															if($can_empl > 0){
																$cont_empl = 1;
																echo '<br /><div align="center" style="background-color:#777; color:#DDD;"><strong>IDIOMAS</strong></div><br />';
																while($row_empl = mysqli_fetch_array($res_empl)){
																	if($cont_empl > 1){
																		echo '----------------------------------------------------<br />';
																	}
																	?>
                                                                    <table class="tabla_answer">
                                                                        <tr>
                                                                            <td class="neg">Idioma:</td>
                                                                            <td><?PHP echo $row_empl['idioma'];?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Oral:</td>
                                                                            <td><?PHP echo nivelaIdioma($row_empl['oral']);?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Escrito:</td>
                                                                            <td><?PHP echo nivelaIdioma($row_empl['escrito']);?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Lectura:</td>
                                                                            <td><?PHP echo nivelaIdioma($row_empl['lectura']);?></td>
                                                                        </tr>
                                                                    </table>
																	<?PHP $cont_empl++;?>
																<?PHP }	?>
															<?PHP }	?>
                                                            
                                                           <!--CONOCIMIENTOS-->
                                                            
															<?PHP
															$sql_empl = "SELECT * FROM empleos_conocimientos WHERE postulante = ".$id_postu;
															$res_empl = fullQuery($sql_empl);
															$can_empl = mysqli_num_rows($res_empl);
															if($can_empl > 0){
																$cont_empl = 1;
																echo '<br /><div align="center" style="background-color:#777; color:#DDD;"><strong>CONOCIMIENTOS</strong></div><br />';
																while($row_empl = mysqli_fetch_array($res_empl)){
																	if($cont_empl > 1){
																		echo '----------------------------------------------------<br />';
																	}
																	?>
                                                                    <table class="tabla_answer">
                                                                        <tr>
                                                                            <td class="neg">Conocimiento:</td>
                                                                            <td><?PHP echo $row_empl['nombre'];?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="neg">Descripci&oacute;n:</td>
																			<td><?PHP echo $row_empl['descr'];?></td>
                                                                        </tr>
                                                                    </table>
																	<?PHP $cont_empl++;?>
																<?PHP }	?>
															<?PHP }	?>
                                                            
                                                           <!--CV-->
                                                            
															<?PHP
															$link_cv = '../docs/cvs/'.$row_canti['cv'];
															if(file_exists($link_cv) && $row_canti['cv'] != ''){
																echo '<br /><div align="center" style="background-color:#777; color:#DDD;"><strong>Curriculum Vitae</strong></div><br />';
																?>
																<a href="<?PHP echo $link_cv;?>" target="_blank">Descargar CV</a>
															<?PHP }else{echo 'No ingres&oacute; CV';}?>
															<br /><br />
														</div>
													</div>
                                                <?PHP 
												$cont++;
												} ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						<?PHP }	?>
			</table>
	</div>
	<div style="clear:both;"></div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>