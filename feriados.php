<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$tipo = 31;
agrega_acceso($tipo);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head.php"); ?>
		<link href="css/campos.css" rel="stylesheet" type="text/css" />
		<link href="css/minisitios.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner"><?PHP include("header.php");?>
				<?php include_once 'menu.php'; ?>
				<div class="container_inner_2">
					<div class="col_ppal left">
						<div class="hd-seccion">Feriados</div>
                        
						<div class="left w100 pb5  brd-b">
							<div class="left t20 c999999 mt10">FERIADOS INAMOVIBLES 2012</div>
						</div>
						<div class="left w100">
							<table cellspacing="0" class="tablesorter" id="table">
								<thead>
									<tr>
										<td class="header" width="145px">Fecha</td>
										<td class="header" width="90px">D&iacute;a</td>
										<td class="header">Conmemoraci&oacute;n</td>
									</tr>
								</thead>
								<tbody>
									
									<?PHP
									$sql_feriados = "SELECT * FROM intranet_feriados WHERE tipo = 1 ORDER BY orden";
									$res_feriados = fullQuery($sql_feriados);
									$cuenta_filas = 0;
									$cuenta_anota = 1;
									while ($row_feriados = mysqli_fetch_array($res_feriados)) {
										$fer_orden = $row_feriados['orden'];
										?>
										<tr <?PHP if ($cuenta_filas % 2 == 0) {
										echo 'bgcolor="#c4e8ff"';
									} ?>>
											<td height="25">
												<?PHP
												echo $row_feriados['fecha'];
												if ($row_feriados['anotacion'] != '') {
													if ($row_feriados['anotacion'] > '0' && $row_feriados['anotacion'] < '100') {
														$fer_orden_existente = $row_feriados['anotacion'];
														echo ' (*' . $anota[$fer_orden_existente] . ')';
													} else {
														echo ' (*' . $cuenta_anota . ')';
														$anota[$fer_orden] = $cuenta_anota;
														$cuenta_anota++;
													}
												}
												?>
											</td>
											<td><?PHP echo $row_feriados['dia']; ?></td>
											<td>
												<?PHP
												if ($row_feriados['link'] != '') {
													echo '<a href="' . $row_feriados['link'] . '" target="_blank">';
												}
												echo $row_feriados['texto'];
												if ($row_feriados['link'] != '') {
													echo '</a>';
												}
												?>
											</td>
										</tr>
										<?PHP
										$cuenta_filas++;
									}
									?>
								</tbody>
							</table>
						</div>
						
						<?PHP
						$sql_anotaciones = "SELECT link, anotacion FROM intranet_feriados WHERE tipo = 1 AND anotacion != '' ORDER BY orden";
						$res_anotaciones = fullQuery($sql_anotaciones);
						$cuenta_anota = 1;
						while ($row_anotaciones = mysqli_fetch_array($res_anotaciones)) {
							if (!is_numeric($row_anotaciones['anotacion'])) {
								if ($row_feriados['link'] != '') {
									echo '<a href="' . $row_feriados['link'] . '" target="_blank">';
								}
								echo '(*' . $cuenta_anota . '): ' . $row_anotaciones['anotacion'];
								if ($row_feriados['link'] != '') {
									echo '</a>';
								}
								echo '<br /><br />';
								$cuenta_anota++;
							}
						}
						?>
						<div class="left w100 pb5 mt30 brd-b">
							<div class="left t20 c999999 mt10">FERIADOS TRASLADABLES 2012</div>
						</div>
						<div class="left w100">
							<table cellspacing="0" class="tablesorter" id="table">
								<thead>
									<tr>
										<td class="header" width="145px">Fecha</td>
										<td class="header" width="90px">D&iacute;a</td>
										<td class="header">Conmemoraci&oacute;n</td>
									</tr>
								</thead>
								<tbody>
							<?PHP
							$sql_feriados = "SELECT * FROM intranet_feriados WHERE tipo = 2 ORDER BY orden";
							$res_feriados = fullQuery($sql_feriados);
							$cuenta_filas = 0;
							$cuenta_anota = 1;
							while ($row_feriados = mysqli_fetch_array($res_feriados)) {
								$fer_orden = $row_feriados['orden'];
								?>
	                            <tr <?PHP if ($cuenta_filas % 2 == 0) {
									echo 'bgcolor="#c4e8ff"';
								} ?>>
									<td height="25">
										<?PHP
										echo $row_feriados['fecha'];
										if ($row_feriados['anotacion'] != '') {
											if ($row_feriados['anotacion'] > '0' && $row_feriados['anotacion'] < '100') {
												$fer_orden_existente = $row_feriados['anotacion'];
												echo (isset($anota[$fer_orden_existente])) ? ' (*' . $anota[$fer_orden_existente] . ')' : "";
											} else {
												echo (isset($anota[$fer_orden_existente])) ? ' (*' . $cuenta_anota . ')' : "";
												$anota[$fer_orden] = $cuenta_anota;
												$cuenta_anota++;
											}
										}
										?>
									</td>
									<td><?PHP echo $row_feriados['dia']; ?></td>
									<td>
										<?PHP
										if ($row_feriados['link'] != '') {
											echo '<a href="' . $row_feriados['link'] . '" target="_blank">';
										}
										echo $row_feriados['texto'];
										if ($row_feriados['link'] != '') {
											echo '</a>';
										}
										?>
									</td>
	                            </tr>
								<?PHP
								$cuenta_filas++;
							}
							?>
                           </tbody>
							</table>
						</div>
						<?PHP
						$sql_anotaciones = "SELECT link, anotacion FROM intranet_feriados WHERE tipo = 2 AND anotacion != '' ORDER BY orden";
						$res_anotaciones = fullQuery($sql_anotaciones);
						$cuenta_anota = 1;
						while ($row_anotaciones = mysqli_fetch_array($res_anotaciones)) {
							if (!is_numeric($row_anotaciones['anotacion'])) {
								if ($row_feriados['link'] != '') {
									echo '<a href="' . $row_feriados['link'] . '" target="_blank">';
								}
								echo '(*' . $cuenta_anota . '): ' . $row_anotaciones['anotacion'];
								if ($row_feriados['link'] != '') {
									echo '</a>';
								}
								echo '<br />';
								$cuenta_anota++;
							}
						}
						?>
						<div class="left w100 pb5 mt30 brd-b">
							<div class="left t20 c999999 mt10">DIAS NO LABORABLES INAMOVIBLES 2012</div>
						</div>
						<div class="left w100">
							<table cellspacing="0" class="tablesorter" id="table">
								<thead>
									<tr>
										<td class="header" width="145px">Fecha</td>
										<td class="header" width="90px">D&iacute;a</td>
										<td class="header">Conmemoraci&oacute;n</td>
									</tr>
								</thead>
								<tbody>
							<?PHP
							$sql_feriados = "SELECT * FROM intranet_feriados WHERE tipo = 3 ORDER BY orden";
							$res_feriados = fullQuery($sql_feriados);
							$cuenta_filas = 0;
							$cuenta_anota = 1;
							while ($row_feriados = mysqli_fetch_array($res_feriados)) {
								$fer_orden = $row_feriados['orden'];
								?>
	                            <tr <?PHP if ($cuenta_filas % 2 == 0) {
										echo 'bgcolor="#c4e8ff"';
									} ?>>
									<td height="25">
										<?PHP
										echo $row_feriados['fecha'];
										if ($row_feriados['anotacion'] != '') {
											if ($row_feriados['anotacion'] > '0' && $row_feriados['anotacion'] < '100') {
												$fer_orden_existente = $row_feriados['anotacion'];
												echo ' (*' . $anota[$fer_orden_existente] . ')';
											} else {
												echo ' (*' . $cuenta_anota . ')';
												$anota[$fer_orden] = $cuenta_anota;
												$cuenta_anota++;
											}
										}
										?>
									</td>
									<td><?PHP echo $row_feriados['dia']; ?></td>
									<td>
										<?PHP
										if ($row_feriados['link'] != '') {
											echo '<a href="' . $row_feriados['link'] . '" target="_blank">';
										}
										echo $row_feriados['texto'];
										if ($row_feriados['link'] != '') {
											echo '</a>';
										}
										?>
									</td>
	                            </tr>
							<?PHP
							$cuenta_filas++;
						}
						?>
                             </tbody>
							</table>
						</div>			
						<?PHP
						$sql_anotaciones = "SELECT link, anotacion FROM intranet_feriados WHERE tipo = 3 AND anotacion != '' ORDER BY orden";
						$res_anotaciones = fullQuery($sql_anotaciones);
						$cuenta_anota = 1;
						while ($row_anotaciones = mysqli_fetch_array($res_anotaciones)) {
							if (!is_numeric($row_anotaciones['anotacion'])) {
								if ($row_feriados['link'] != '') {
									echo '<a href="' . $row_feriados['link'] . '" target="_blank">';
								}
								echo '(*' . $cuenta_anota . '): ' . $row_anotaciones['anotacion'];
								if ($row_feriados['link'] != '') {
									echo '</a>';
								}
								echo '<br /><br />';
								$cuenta_anota++;
							}
						}
						?>
						<div class="clr"></div>
						
						<div class="left w100 ac brd-t mt10"><img src="img_new/cierre.png" width="630" height="71" /></div>
					</div>
					<?php include("col_der.php"); ?>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>