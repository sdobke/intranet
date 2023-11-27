<?PHP 
include ("../../cnfg/config.php");
include ("sechk.php");
include ("inc/inc_funciones_globales.php");

$tipo      = getPost('tipo');
$nombretab = obtenerNombre($tipo);

$nombredet = parametro('detalle',$tipo);
$usafecha  = parametro('fecha',$tipo); // 1 es fecha manual, 2 es vencimiento manual, 3 es fecha auto, 4 es vencimiento auto
$usatexto  = parametro('texto',$tipo);
$nomtitulo = (parametro('nombre_detalle',$tipo) != '') ? parametro('nombre_detalle',$tipo) : 'Nombre';
$usacanti  = parametro('cantidad',$tipo);


$tipo = getPost('tipo');
$id   = getPost('id');

$sql = "SELECT * FROM empleos_".$nombretab." WHERE id = ".$id;
$res = fullQuery($sql);
$dato = mysqli_fetch_array($res);
?>
<?PHP include "inc/muestra_errores.php";?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | <?PHP echo $nombredet;?></title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
<?PHP if($usafecha == 1 || $usafecha == 2){echo '<script type="text/javascript" src="calendarDateInput.js"></script>
';}?>
<?PHP 
if($usatexto == 1){
echo '
		<script type="text/javascript" src="js/openwysiwyg/scripts/wysiwyg.js"></script>
		<script type="text/javascript" src="js/openwysiwyg/scripts/wysiwyg-settings.js"></script>
		<script type="text/javascript">
';
echo "
			WYSIWYG.attach('texto', mini);
		</script>
";
}?>
<?PHP if($usacanti != ''){
	/*echo '<script type="text/javascript" src="../includes/ocultar-mostrar.js"></script>
';*/
	echo '<link href="css/ocultar-mostrar.css" rel="stylesheet" type="text/css" />';
	
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
}?>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript">
function confirmDelete(delUrl) {
	if (confirm("�Est� seguro que quiere eliminar ese registro?")) {
		document.location = delUrl;
	}
}
</script>
</head>
<body>
<div id="contenedor">
	<div id="header"> </div>
    <?PHP include ("menu.php");?>
	<?PHP include ("inc/buscador.php");?>
	<div class="tit-result">
    	<a href="listado.php?tipo=<?PHP echo $tipo;?>">LISTADO DE <?PHP echo strtoupper($nombredet);?></a> - <a href="alta.php?tipo=<?PHP echo $tipo;?>">ALTA DE <?PHP echo strtoupper($nombredet);?></a>
	</div>

	<div style="width:728px; float:left">
		<?PHP echo $error_msg;?>
        
		<form action="mod.php" method="post" enctype="multipart/form-data" onsubmit="return confirmaSend()" >
        	<input name="id" type="hidden" id="id" value="<?PHP echo $id;?>" />
			    <table>
					<tr>
                		<td>
                        	<input name="tipo" id="tipo" type="hidden" value="<?PHP echo $tipo;?>" />
                        	<?PHP echo $nomtitulo;?>:
                    	</td>
                    	<td><input name="nombre" type="text" id="nombre" size="40" value="<?PHP echo $dato['nombre'];?>" /></td>
	                </tr>
					<?PHP if($usafecha != 0){
							$fecha_def = str_replace("-", "/", $dato['fecha']);
                            if($usafecha == 1 || $usafecha == 2){
                                $titufecha = 'Fecha';
                                if($usafecha == 2){
                                    $titufecha = 'Vencimiento';
                                }
                                ?>
                                <tr>
									<td><?PHP echo $titufecha;?>:</td>
									<td><script type="text/javascript">DateInput('fecha', true, 'YYYY/MM/DD', '<?PHP echo $fecha_def;?>')</script></td>
                                </tr>
                            <?PHP }else{ ?>
                            	<tr style="height:0px"><td colspan="2"><input name="fecha" id="fecha" value="<?PHP echo $dato['fecha'];?>" type="hidden" /></td></tr>
                            <?PHP } ?>
                    <?PHP } ?>
					<?PHP if($usatexto == 1){?>
                        <tr>
                            <td>Texto:</td>
                            <td>
                                <textarea id="texto" name="texto"><?PHP echo $dato['texto'];?></textarea>
                            </td>
                        </tr>
                    <?PHP } ?>
                    <?PHP // COMBOS
                    $query_combos = "SELECT tab.nombre AS variable, tab.detalle AS nombre FROM empleos_combos AS cbo
                                        INNER JOIN empleos_tablas AS tab ON (cbo.combo = tab.id)
                                        WHERE cbo.tabla = ".$tipo;
                    $resul_combos = fullQuery($query_combos);
                    while($row_combos = mysqli_fetch_array($resul_combos)){
                        $titulo_combo = $row_combos['nombre'];
                        $var_combo    = $row_combos['variable'];
                        echo '<tr><td>'.$titulo_combo.':</td><td>';
						// SELECT
						// Valor seleccionado
						$sql_select = "SELECT ".$var_combo." FROM empleos_".$nombretab." WHERE id = ".$id;
						$res_select = fullQuery($sql_select);
						$row_select = mysqli_fetch_array($res_select);
						$valor = $row_select[$var_combo];
						echo '<select name="'.$var_combo.'">';
                        $sql_combo = "SELECT * FROM empleos_".$var_combo." WHERE del = 0";
                        $res_combo = fullQuery($sql_combo);
                        while($row_combo = mysqli_fetch_array($res_combo)){
							$es_activo = ($valor == $row_combo['id']) ? 'selected="selected"' : '';
                            echo '<option value="'.$row_combo['id'].'" '.$es_activo.' >'.$row_combo['nombre'].'</option>';
                        }
                        echo '</select></td></tr>';
                    }
                    ?>
                    <?PHP if ($usacanti != ''){?>
                       	<?PHP
						$sql_canti = "SELECT postu.*, ebp.id AS ebpid
									  FROM empleos_postulantes AS postu 
										INNER JOIN empleos_busqueda_postulantes AS ebp 
											ON ebp.postulante = postu.id
										WHERE ebp.busqueda = ".$id."
										GROUP BY postu.id
										";
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
												<div class="nombre3" align="left" style="width:520px"><?PHP echo $usacanti;?></div>
												<div style="clear:both"></div>
												<?PHP
												while($row_canti = mysqli_fetch_array($res_canti)){
													$id_postu = $row_canti['id'];
													?>
													<div class="dhtmlgoodies_question">
														<div class="nombre<?PHP if($cont % 2 == 0){echo '2';}?>" style="width:300px">
															<?PHP echo $row_canti['nombre'];?>
														</div>
														<div class="nombre<?PHP if($cont % 2 == 0){echo '2';}?>" style="width:100px">
                                                        	<?PHP
															$link_cv = '../docs/cvs/'.$row_canti['cv'];
															if(file_exists($link_cv) && $row_canti['cv'] != ''){
	                                                        	echo '<a href="'.$link_cv.'" target="_blank">Descargar CV</a>';
															}else{
																echo 'CV no cargado';
															}
															?>
															<?PHP //echo '<a href="select.php?id='.$row_canti['ebpid'].'&tipo='.$tipo.'" >Seleccionar</a>';?>
														</div>
                                                        <div class="nombre<?PHP if($cont % 2 == 0){echo '2';}?>" style="width:100px">
                                                        	<a href="ver_formu.php?id=<?PHP echo $id_postu;?>&puesto=<?PHP echo $id;?>">Ver formulario</a>
                                                        </div>
													</div>
													<!--<div class="dhtmlgoodies_answer">
														<div align="left" style="float:left">
															<table class="tabla_answer">
																<tr>
																	<td class="neg">Fecha de nacimiento:</td>
																	<td><?PHP /*echo FechaDet($row_canti['fechanac'],'largo','s');?></td>
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
															if(file_exists($link_cv)){
																echo '<br /><div align="center" style="background-color:#777; color:#DDD;"><strong>Curriculum Vitae</strong></div><br />';
																?>
																<a href="<?PHP echo $link_cv;?>" target="_blank">Descargar CV</a>
															<?PHP }	*/?>
															<br /><br />
														</div>
													</div>-->
                                                <?PHP 
												$cont++;
												} ?>
												<script type="text/javascript">
													initShowHideDivs();
													showHideContent(false,1); // Automatically expand first item
												</script>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						<?PHP }	?>
					<?PHP }?>
				<tr><td colspan="2" align="center"><input name="Editar" type="submit" value="Modificar Datos" /></td></tr>
				<tr><td colspan="2" align="center">
					<span class="del"><strong>
						<a href="#" onclick="javascript:confirmDelete('baja.php?tipo=<?PHP echo $tipo;?>&id=<?PHP echo $id;?>')">Eliminar Registro</a>
					</strong></span>
                </td></tr>
			</table>
		</form>
	</div>
	<div style="clear:both;"></div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>