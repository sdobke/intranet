<div id="ccentro"  class="buscador-minisitio mt10">
	<form action="campos.php" method="post" id="formod" name="formod">
		<table width="930" border="0" cellspacing="0" cellpadding="0">
			<tr bgcolor="#e6e6e6">
				<td align="left" valign="top">
					<table width="930" cellpadding="5" cellspacing="1" id="autor">
						<thead align="center">
							<tr>
								<th>CAMPO</th>
								<th>TIPO</th>
								<th>OPCIONES</th>
								<th>ORDEN</th>
								<th>ELIMINAR CAMPO</th>
							</tr>
						</thead>
						<tbody>
							<!--NOMBRE DEL CAMPO-->
							<?PHP
							$cont_campos = 0;
							$cont_orden_cp = 1;
							$cont_mayor_cp = 1;
							$query_campos = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE encuesta = ".$_SESSION['actiform']." ORDER BY orden";
							$result_campos = fullQuery($query_campos);
							$cont_mayor_cp = mysqli_num_rows($result_campos);
							while ($campo = mysqli_fetch_array($result_campos)) {
								$campo_id = $campo['id'];
								$campo_nombre = codTx($campo['nombre']);
								$campo_tipo = $campo['tipo'];
								$campo_orden = $campo['orden'];
								$codigo_campo = "nombre_" . $campo_id;
								?>
								<tr <?PHP if ($cont_campos % 2 == 0) {
									echo 'bgcolor="#f8f8f8"';
								} ?>>
									<td><input name="<?PHP echo $codigo_campo; ?>" id="<?PHP echo $codigo_campo; ?>" type="text" maxlength="250" size="20" value="<?PHP echo $campo_nombre; ?>" onchange="javascript:cambiado(1,<?PHP echo $campo_id; ?>,0)" />
									</td>
									<!--TIPO DE CAMPO-->
									<td><?PHP if ($campo_tipo == 2) { /*?>
											<input name="tipo_<?PHP echo $campo_id; ?>" type="radio" value="2" checked="checked" onchange="javascript:cambiado(2,<?PHP echo $campo_id; ?>,0)"/>
											N&uacute;mero menor a 100<br />
											<input name="tipo_<?PHP echo $campo_id; ?>" type="radio" value="3" onchange="javascript:cambiado(2,<?PHP echo $campo_id; ?>,0)"/>
											N&uacute;mero mayor a 100
										<?PHP */
										} else {
											$query_tipos = "SELECT detalle FROM ".$_SESSION['prefijo']."encuestas_tipos WHERE id = ".$campo_tipo;
											$result_tipos = fullQuery($query_tipos);
											while ($tipo = mysqli_fetch_array($result_tipos)) {
												$tipo_det = $tipo['detalle'];
											}
											echo $tipo_det;
										}
										?>
									</td>
									<!-- OPCIONES DE CAMPO -->
									<td align="center">
										<?PHP if ($campo_tipo == 4) { ?>
											<table>
												<?PHP
												$cont_orden = 1;
												$query_opc_lst = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE encuesta = ".$_SESSION['actiform']." ORDER BY orden";
												$resul_opc_lst = fullQuery($query_opc_lst);
												$query_opc_lst = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = ".$campo_id." ORDER BY orden";
												$resul_opc_lst = fullQuery($query_opc_lst);
												$cont_mayor = mysqli_num_rows($resul_opc_lst);
												while ($opc_lst = mysqli_fetch_array($resul_opc_lst)) { // recorre cada resultado posible
													$opc_id = $opc_lst['id'];
													$opc_opcion = codTx($opc_lst['opcion']);
													$opc_campo = $opc_lst['campo'];
													$opc_orden = $opc_lst['orden'];
													?>
													<tr>
														<td><input name="c<?PHP echo $campo_id; ?>op<?PHP echo $opc_id; ?>" id="c<?PHP echo $campo_id; ?>op<?PHP echo $opc_id; ?>" type="text" value="<?PHP echo $opc_opcion; ?>" size="38" maxlength="250" onchange="javascript:cambiado(3,<?PHP echo $campo_id; ?>,<?PHP echo $opc_id; ?>)"/>
														</td>
														<td>
															<a class="button" href="javascript:confirmDelete('?func=4&amp;id=<?PHP echo $opc_id ?>&amp;campo_id=<?PHP echo $campo_id ?>',2)">
																<span class="icon icon186"></span>
															</a>
														</td>
														<td><table>
															<tr>
																<td>
																	<?PHP if ($cont_orden > 1) { ?>
																		<a href="?func=5&amp;id=<?PHP echo $opc_id; ?>&amp;orden=<?PHP echo $opc_orden; ?>&amp;campo=<?PHP echo $campo_id; ?>" class="button"><span class="icon icon7"></span></a>
																	<?PHP } ?>
																</td>
															</tr>
															<tr>
																<td>
																	<?PHP if ($cont_orden < $cont_mayor) { ?>
																		<a href="?func=6&amp;id=<?PHP echo $opc_id; ?>&amp;orden=<?PHP echo $opc_orden; ?>&amp;campo=<?PHP echo $campo_id; ?>" class="button"><span class="icon icon10"></span></a>
																	<?PHP } ?>
																</td>
															</tr>
														</table></td>
													</tr>
													<?PHP $cont_orden++;?>
												<?PHP }?>
											</table>
											<table>
												<tr>
													<td>
														<a href="javascript:void(0);" class="button" onclick="javascript:ModMostrar(<?PHP echo $campo_id; ?>,0)" name="modbot_<?PHP echo $campo_id; ?>_0" id="modbot_<?PHP echo $campo_id; ?>_0">
															<span class="icon icon3"></span>
															<span class="label">Agregar Opcion</span>
														</a>
														<br /><br />
														<?PHP
														$mod_cont = 0;
														while ($mod_cont <= 20) {
															$mod_cont++;
															?>
															<div id="ModDiv<?PHP echo $campo_id.'_'.$mod_cont; ?>" style="display:none">
																<table>
																	<tr>
																		<td width="0"></td>
																		<td><input name="newopcion_<?PHP echo $campo_id.'_'.$mod_cont; ?>" id="newopcion_<?PHP echo $campo_id.'_'.$mod_cont; ?>" type="text" size="30" maxlength="100" style="float:left;"/>
																			&nbsp;
																			<a href="javascript:void(0);" class="button" name="modbot_<?PHP echo $campo_id.'_'.$mod_cont; ?>" id="modbot_<?PHP echo $campo_id.'_'.$mod_cont; ?>" onclick="javascript:ModMostrar(<?PHP echo $campo_id.','.$mod_cont; ?>)">
																				<span class="icon icon67"></span>
																				<span class="label">Agregar</span>
																			</a>
																			&nbsp;
																			<a class="button" href="javascript:void(0);"  onclick="javascript:ModOcultar(<?PHP echo $campo_id.','.$mod_cont; ?>)">
																				<span class="icon icon186"></span>
																			</a>
																		</td>
																	</tr>
																</table>
															</div>
														<?PHP } ?>
													</td>
												</tr>
											</table>
										<?PHP } ?>
									</td>
									<td align="center"><table>
										<tr>
											<td><?PHP if ($cont_orden_cp > 1) { ?>
													<a href="?func=7&amp;id=<?PHP echo $campo_id; ?>&amp;orden=<?PHP echo $campo_orden; ?>" class="button"><span class="icon icon10"></span></a>
												<?PHP } ?>
											</td>
										</tr>
										<tr>
											<td><?PHP if ($cont_orden_cp < $cont_mayor_cp) { ?>
												<a href="?func=8&amp;id=<?PHP echo $campo_id; ?>&amp;orden=<?PHP echo $campo_orden; ?>" class="button"><span class="icon icon7"></span></a>
												<?PHP } ?>
											</td>
										</tr>
									</table></td>
									<!--ELIMINAR-->
									<td align="center">
                                            <a class="button" href="javascript:confirmDelete('?func=3&amp;id=<?PHP echo $campo_id; ?>',1)">
                                                <span class="icon icon186"></span>
                                            </a>
											<?PHP $cont_campos++;?>
										<?PHP $cont_orden_cp++;?>
									</td>
								</tr>
							</tbody>
						<?PHP }?>
						<tr bgcolor="#366" height="50">
							<td colspan="5" align="center">
								<input name="mod_nombres" id="mod_nombres" type="hidden" value="0"/>
								<input name="mod_tipos" id="mod_tipos" type="hidden" value="0"/>
								<input name="mod_opciones" id="mod_opciones" type="hidden" value="0"/>
								<input name="mod_opciones_new" id="mod_opciones_new" type="hidden" value="0"/>
								<input name="func" type="hidden" value="2"/>
								
								<a href="javascript:void(0);" class="button" id="grabarTipo" onclick="submitformMod();">
									<span class="icon icon67"></span>
									<span class="label">Modificar Todo</span>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
        </table>
	</form>
</div>