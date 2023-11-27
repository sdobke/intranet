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
<script type="text/javascript" src="../../includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../includes/ckeditor/navegadores.js"></script>
';
}?>
<?PHP if($usacanti != ''){
	
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
								<p>
                                    <textarea id="texto" name="texto"><?PHP echo $dato['texto'];?></textarea>
                                    <script type="text/javascript">
                                        //<![CDATA[
                            
                                            // This call can be placed at any point after the
                                            // <textarea>, or inside a <head><script> in a
                                            // window.onload event handler.
                            
                                            // Replace the <textarea id="editor"> with an CKEditor
                                            // instance, using default configurations.
                                            CKEDITOR.replace( 'texto' );
                            
                                        //]]>
                                    </script>
                                </p>
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
								<td colspan="2">
                                   	<table>
                                       	<tr>
                                           	<td>
                                            	<table>
                                                	<tr>
                                                    	<td width="520" bgcolor="#124F81" style="color:#EEE; padding-left:10px; font-weight:bold" colspan="3">
                                                    		<?PHP echo $usacanti;?>
                                                        </td>
                                                    </tr>
                                                    <?PHP
                                                    while($row_canti = mysqli_fetch_array($res_canti)){
                                                        $id_postu = $row_canti['id'];
                                                        ?>
                                                        <tr>
                                                        	<td width="300" bgcolor="#<?PHP if($cont % 2 == 0){echo 'E8F7FF';}else{echo 'D3E8FE';}?>" style="padding-left:10px">
																<?PHP echo $row_canti['nombre'];?>
                                                           	</td>
															<td width="100" bgcolor="#<?PHP if($cont % 2 == 0){echo 'E8F7FF';}else{echo 'D3E8FE';}?>" style="padding-left:10px">
																<?PHP
																$link_cv = '../docs/cvs/'.$row_canti['cv'];
																if(file_exists($link_cv) && $row_canti['cv'] != ''){
																	echo '<a href="'.$link_cv.'" target="_blank">Descargar CV</a>';
																}else{
																	echo 'CV no cargado';
																}
																?>
                                                            </td>
															<td width="100" bgcolor="#<?PHP if($cont % 2 == 0){echo 'E8F7FF';}else{echo 'D3E8FE';}?>" style="padding-left:10px">
																<a href="ver_formu.php?id=<?PHP echo $id_postu;?>&puesto=<?PHP echo $id;?>">Ver formulario</a>
                                                            </td>
                                                        </tr>
                                                    <?PHP 
                                                    $cont++;
                                                    } ?>
                                                </table>
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