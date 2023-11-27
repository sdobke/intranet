<div id="contenedor2"  class=" mt10" style="overflow: auto;">
	<div id="ccentro_resul">
		<!--............................................ RESULTADOS ..............................................-->     
		<table cellspacing="0" class="tablesorter" id="resultados">
								
				<thead>
					<?PHP
					$error = 'ok';
					$cant_campos = 0;
					$query4 = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE 1 ORDER BY orden";
					$resul4 = fullQuery($query4);

					while ($campos = mysqli_fetch_array($resul4)) {
						$camporden = codTx($campos['nombre']);
						echo "<th class='header'  align='center' >";
						echo "<a href ='?orden=" . $camporden;
						if ($camporden == $orden) {
							echo " DESC";
						}
						//agrego el filtro de fecha
						//echo "&desde=" . $fechadesde . "&hasta=" . $fechahasta;
						echo "&items=" . $items;
						echo "&tipocom=" . $tipocom;
						//echo "&buscartxt=" . $buscartxt;
						//fin filtro de fecha
						echo "'>";
						echo ucwords($camporden);
						echo "</a>";
						echo "</th>";
						$cant_campos++;
					}
					?>
					<th class='header' >
						<strong>BORRAR</strong>
					</th>
				
			</thead>
			<tbody>
				<?PHP
				// PREPARACIÓN DEL PAGINADOR
				include("inc/prepara_paginador.php");
				// FIN PREPARACIÓN DEL PAGINADOR
				//$query5  = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_listado ORDER BY ".$orden." DESC".$limitsql; // Recorre todos los reclamos
				$query5 = $query_todo . $limitsql;
				$result5 = fullQuery($query5);
				//while($reclamos = mysqli_fetch_array($query)){
				//echo $query5;
				$contar = mysqli_num_rows($result5);
				if ($contar > 0) {
					$contr = 0;
					while ($reclamos = mysqli_fetch_array($result5)) {
						$contr++;
						?>
						<tr>
							<?PHP
							$query_campos = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE 1 ORDER BY orden"; // Recorre todos los campos
							$resul_campos = fullQuery($query_campos);
							$contd = 0;
							while ($campos = mysqli_fetch_array($resul_campos)) {
								$contd++;
								$contenido = $campos['nombre'];
								$campo_opc = $campos['opciones'];
								$campo_tipo = $campos['tipo'];
								$campo_id = $campos['id'];
								$valor_orig = $reclamos[$campo_id];
								//$estado_act = $reclamos['estado'];

								if ($contenido == 'email' || $contenido == 'e-mail') {
									$valor = '<a href="mailto:' . $valor_orig . ' ">' . $valor_orig;
								} else {
									$valor = $valor_orig;
								}
								if ($valor_orig != '') {
									if ($campo_opc == 1) { // Si es un campo con multiples opciones
										if ($campo_tipo == 6) {
											if ($valor_orig == 1) {
												$valor = 'si';
											} else {
												$valor = 'no';
											}
										} elseif ($valor_orig == 0) {
											$valor = 'Indistinto';
											$valor = 'No';
										} else {
											$query_opc_res = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE id = $valor_orig";
											$resul_opc_res = fullQuery($query_opc_res);
											$opc_res = mysqli_fetch_array($resul_opc_res);
											$valor = $opc_res['opcion']; // y la trae desde las opciones de ese campo
										}
									}
								}
								if ($contr % 2 == 0) {
									$clasetd = ($contd % 2 == 0) ? '1' : '2';
								} else {
									$clasetd = ($contd % 2 == 0) ? '3' : '4';
								}
								?>
								<td class="td<?PHP echo $clasetd; ?>">
									<?PHP if ($campo_id == 0) { // si el campo es Estado?>
										<form id="cambiaestados_<?PHP echo $reclamos['id']; ?>" name="cambiaestados_<?PHP echo $reclamos['id']; ?>">
											<select id="estado_cambio_<?PHP echo $reclamos['id']; ?>" name="estado_cambio_<?PHP echo $reclamos['id']; ?>" onchange="cambiarEstado(<?PHP echo $reclamos['id']; ?>,this.value,<?PHP echo $page; ?>,<?PHP echo $estado; ?>,'<?PHP echo $orden; ?>','<?PHP echo $fechadesde; ?>','<?PHP echo $fechahasta; ?>','<?PHP echo $items; ?>')">
												<option value="0"<?PHP echo optSel($estado_act, 0); ?>>Activo</option>
												<option value="1"<?PHP echo optSel($estado_act, 1); ?>>Esperando Rta.</option>
												<option value="2"<?PHP echo optSel($estado_act, 2); ?>>Cerrado</option>
											</select>
										</form>
										<?PHP
									} elseif ($campo_tipo == 1) { // Si es tipo FECHA
										echo FechaDet($valor, 'corto');
									} elseif ($campo_tipo == 5) { // Si es la descripción
										echo '<form><textarea rows="3" cols="25" readonly="readonly">' . $valor . '</textarea></form>';
									} else {
										echo $valor;
									}
									?>
								</td>
							<?PHP } ?> 
							<td>
								<a class="button" href="javascript:confirmDelete('?func=2&id=<?PHP echo $reclamos['id']; ?>',3)">
									<span class="icon icon186"></span>
								</a>
								<!--
								<a href="javascript:confirmDelete('?func=2&id=<?PHP echo $reclamos['id']; ?>',3)">
									<img src="img/borrar.png" alt="borrar" width="80" height="27" border="0" /></a>-->
							</td> 
						</tr>
					<?PHP } ?>
				<?PHP } else { ?>
					<tr>
						<td colspan="<?PHP echo $cant_campos; ?>" align="left">
							No hay resultados que coindidan con la b&uacute;squeda solicitada.
						</td>
					</tr>
				<?PHP } ?>
			</tbody>  
		</table>

		<?PHP
		// Preparado de archivo excel
		if (isset($_POST['generaexcel']) && $_POST['generaexcel'] == 1) {
			$matriz_hdr = array();
			$queryexc = "SELECT nombre FROM ".$_SESSION['prefijo']."encuestas_campos WHERE activo = 1 AND id NOT IN (6,44,45) ORDER BY orden";
			$resulexc = fullQuery($queryexc);
			while ($campos = mysqli_fetch_array($resulexc)) {
				$camporden = $campos['nombre'];
				$matriz_hdr[] = $campos['nombre'];
			}
			$Matriz = array();
			$Matriz[] = $matriz_hdr;
			$query6 = $query_todo;
			$result6 = fullQuery($query6);
			$contar = mysqli_num_rows($result6);
			if ($contar > 0) {
				while ($reclamos = mysqli_fetch_array($result6)) {
					$matriz_datos = array();
					$query_campos = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE activo = 1 AND id NOT IN (6,44,45) ORDER BY orden"; // Recorre todos los campos
					$resul_campos = fullQuery($query_campos);
					while ($campos = mysqli_fetch_array($resul_campos)) {
						$contenido = $campos['nombre'];
						$valor_orig = $reclamos[$contenido];
						$campo_opc = $campos['opciones'];
						$campo_tipo = $campos['tipo'];
						$campo_id = $campos['id'];
						//$estado_act = $reclamos['estado'];
						if ($contenido == 'email' || $contenido == 'e-mail') {
							$valor = '<a href="mailto:' . $valor_orig . ' ">' . $valor_orig;
						} else {
							$valor = $valor_orig;
						}
						if ($valor_orig != '') {
							if ($campo_opc == 1) { // Si es un campo con multiples opciones
								if ($campo_tipo == 6) {
									if ($valor_orig == 1) {
										$valor = 'si';
									} else {
										$valor = 'no';
									}
								} elseif ($valor_orig == 0) {
									$valor = 'Indistinto';
									$valor = 'No';
								} else {
									$query_opc_res = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE id = $valor_orig";
									$resul_opc_res = fullQuery($query_opc_res);
									$opc_res = mysqli_fetch_array($resul_opc_res);
									$valor = $opc_res['opcion']; // y la trae desde las opciones de ese campo
								}
							}
						}
						// Definiendo valores para excel
						if ($contenido == 'email' || $contenido == 'e-mail') {
							$datomatriz = $valor_orig;
						} else {
							$datomatriz = $valor;
						}
						if ($contenido == 'estado') {
							switch ($estado_act) {
								case 0:
									$datomatriz = 'Activo';
									break;
								case 1:
									$datomatriz = 'Esperando Respuesta';
									break;
								case 2:
									$datomatriz = 'Cerrado';
									break;
							}
						}
						$matriz_datos[] = $datomatriz;
					}
					$Matriz[] = $matriz_datos;
				}
			}
		}

		// PAGINADOR
		//Convertimos la matriz a Excel: 
		if (isset($_POST['generaexcel']) && $_POST['generaexcel'] == 1) {
			if(isset($Matriz))
				$excel->WriteMatriz($Matriz);
	
			$excel->Archivo("reporte.xls");
		}
		include_once("paginador.php");
		// FIN PAGINADOR
		?>
	</div>
	<div style="clear:both;"></div>
	<!-- ....................................... RESULTADOS Y GRAFICOS DE CAMPOS ..................................... -->
</div>