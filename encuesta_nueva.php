<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

include_once("encuestas/func_encuestas.php");
$tipo = 20;
agrega_acceso($tipo);
$nombre = obtenerNombre($tipo);
$titsec = $nombre;
// busqueda
$buscampo1 = "titulo";
$buscampo2 = "texto";
$queryemp = '';
//$otro_query = "(empresa = 4 OR empresa = $user_emp) AND (oficinas = 3 OR oficinas = $_SESSION['tipousr'] ".$queryemp.")";
$order_by = " fecha DESC";
$usafecha = 0;
$tit_fecha = ($usafecha == 1) ? 'fecha-' : '';
if (isset($_POST['func']) && $_POST['func'] == '1') {
	$error = 'ok';
	$querycampos = "SELECT * FROM intranet_encuestas_campos ORDER BY orden";
	$resulcampos = fullQuery($querycampos);
	$idenc = nuevoID('encuestas_listado');
	$query = "INSERT INTO intranet_encuestas_listado (id,fecha, empleado, ";
	$query2 = ") VALUES (".$idenc.", '" . date("Y-m-d") . "', ".$_SESSION['usrfrontend'].", ";
	$cont = 0;
	while ($campos = mysqli_fetch_array($resulcampos)) {
		if ($cont > 0) {
			$query.= ", ";
			$query2.= ", ";
		}
		$cont++;
		$campid = $campos['id'];
		$nombre_campo = '`'.$campid.'`';
		$query.= $nombre_campo;
		$valor_del_campo = txtcod($_POST['var_'.$campid]);
		//$debug .= $nombre_del_campo;
		if ($valor_del_campo == '') {
			$query2.= "''";
		} else {
			if ($campos['tipo'] == 4 || $campos['tipo'] == 5) { // si es texto
				if ($campos['opciones'] == 1) {
					$query2.= $valor_del_campo;
				} else {
					$query2.= "'" . $valor_del_campo . "'";
				}
			} elseif ($campos['tipo'] == 1) { // si es fecha
				$query2.= "'" . $valor_del_campo . "'";
			} else { // si no es ni texto ni fecha
				$query2.= $valor_del_campo;
			}
		}
	}
	$query2.= ")";
	$query_completo = $query . $query2;
	//echo $query_completo;
	$result = fullQuery($query_completo);
	//echo "<br /><br />".$debug;
}
// Fin de procesamiento de alta 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head.php"); ?>
		<link href="css/minisitios.css" rel="stylesheet" type="text/css" />
		<link href="css/campos.css" rel="stylesheet" type="text/css" />
		<link href="css/secciones.css" rel="stylesheet"  type="text/css"  media="screen">
		<script type="text/javascript" src="encuestas/inc/scripts.js"></script>
		<script type="text/javascript" src="encuestas/calendarDateInput.js"></script>
		<script type="text/javascript">
			function submitformCrear() {
				document.formcrear.submit();
			}
		</script>
        <style>
			.corta{width:585px;}
		</style>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner"><?PHP include("header.php");?>
				<?php include_once 'menu.php'; ?>
				<div class="container_inner_2">
					<div class="col_ppal left">
						<div class="hd-seccion">Encuesta</div>
                        <?PHP
						$sql_dat = "SELECT titulo AS t FROM intranet_encuesta_datos WHERE id = 1";
						$res_dat = fullQuery($sql_dat);
						$row_dat = mysqli_fetch_assoc($res_dat);
						//echo '<h1>'.ucwords(txtcod($row_dat['t'])).'</h1><br /><br />';
						//echo '<h1>Eleg&iacute; los nombres de las siguientes salas</h1><br /><br />';
							
						if (!isset($_SESSION['usrfrontend'])) {
							echo '<div class="alert_box left mb15">';
							echo 'Para realizar la encuesta ingresa: usuario y contrase&ntilde;a.';
							echo '</div>';
						}else{
							$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp']: 0;
							$sql_res = "SELECT id FROM intranet_link WHERE tipo = 52 AND item = 1 AND (part = 0 OR part = ".$restriccion.")";
							$res_res = fullQuery($sql_res);
							$con_res = mysqli_num_rows($res_res);
							if($con_res > 0){ // Está habilitado
								$sql_v = "SELECT id FROM intranet_encuestas_listado WHERE empleado = ".$_SESSION['usrfrontend'];
								$res_v = fullQuery($sql_v);
								$con_v = mysqli_num_rows($res_v);
								if($con_v == 0){ // Si no participó
									?>
									<div style="margin-top: 10px;">
										<?PHP if (isset($error)) { ?>
											<?php if ($error != 'ok') { ?>
												<div class="alert_box left mb15 corta">
													<?php echo $error; ?>
												</div>
											<?php }else if ($error == 'ok') { ?>
												<div class="success_box left mb15 corta">
													Datos ingresados correctamente.
												</div>
											<?php } ?>
										<?php } ?>	
										<div style="clear: both;"></div>
									</div>
									<div class="contenidos"><?PHP echo txtcod($titulo);?>				
										<div class="buscador-minisitio mt10" style="max-width: 600px;">
											<form action="?" method="post" name="formcrear" id="formcrear">
												<table width="908" cellpadding="5" cellspacing="1" bgcolor="#F6F5F5" style="color:#333">
													<tbody>
														<?PHP
														$query = "SELECT * FROM intranet_encuestas_campos ORDER BY orden";
														$result = fullQuery($query);
														$conta = 1;
														while ($campo = mysqli_fetch_array($result)) {
															$conta++;
															$nombre = 'var_'.$campo['id'];
															$tipo = $campo['tipo'];
															$campo_id = $campo['id'];
															$campo_nom = txtcod($campo['nombre']);
															?>
															<tr <?PHP
															if ($conta % 2 == 0) {
																echo 'bgcolor="#e6e6e6"';
															}
															?>><td width="200"><?PHP echo $campo_nom;?></td>
																<td>
																	<?PHP
																	switch ($tipo) {
																		case 1:
																			?>							
																			<script>DateInput('<?PHP echo $nombre; ?>', true, 'YYYY/MM/DD')</script>
																			<?PHP
																			break;
																		case 2:
																		case 3:
																			?>
																			<input name="<?PHP echo $nombre; ?>" type="text" onChange="validaNro(this.value);" size="50"/>
																			<?PHP
																			break;
																		case 4:
																			if ($campo['opciones'] == 1) {
																				$query_opc = "SELECT * FROM intranet_encuestas_opciones WHERE campo = $campo_id ORDER BY orden";
																				$resul_opc = fullQuery($query_opc);
																				?>
																				<select name="<?PHP echo $nombre; ?>">
																					<option value="0">-indistinto-</option>
																					<?PHP
																					while ($opcion = mysqli_fetch_array($resul_opc)) {
																						echo '<option value="' . $opcion['id'] . '">' . txtcod($opcion['opcion']) . '</option>';
																					}
																					?>
																				</select>
																			<?PHP } else { ?>
																				<input name="<?PHP echo $nombre; ?>" type="text" size="50"/>
																				<?PHP
																			}
																			break;
																		case 5:
																			echo '<textarea name="' . $nombre . '" cols="40" rows="5" style="max-width: 330px;"></textarea>';
																			break;
																		case 6:
																			?>
																			SI <input type="radio" name="<?PHP echo $nombre; ?>" id="<?PHP echo $nombre; ?>" value="1" />
																			NO <input type="radio" name="<?PHP echo $nombre; ?>" id="<?PHP echo $nombre; ?>" value="0" checked="checked" />
																			<?PHP
																			break;
																			echo "</td></tr>";
																	}
																	?>
														<?PHP } ?>
														<tr>
															<td colspan="2">
																<input name="func" type="hidden" value="1" />
																<div align="center">
																	<!--<a href="javascript: submitformCrear()"><img src="img/crear.png" alt="Crear" border="0" /></a>-->
																
																	<a href="javascript:void(0);" class="button" id="grabarTipo" onclick="submitformCrear();">
																		<span class="icon icon67"></span>
																		<span class="label">Grabar</span>
																	</a>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</form>
										</div>
										<div style="clear:both"></div>
									</div>
								<?php }else{?>
									<div class="alert_box left mb15 corta">
										Ya participaste de esta encuesta.
									</div>
								<?php } ?>
							<?php }else{ echo 'No hay encuestas activas actualmente.';}?>
                        <?php } ?>
						
					</div>
					<?php include("col_der.php"); ?>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>