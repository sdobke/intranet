<div id="ccentro"  class="buscador-minisitio mt10">
	<div id="caja-centro" style="width:908px; padding:10px;">
		<form action="campos.php" method="post" name="formcrear" id="formcrear">
			<table width="910" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" style="color:#333">
				<tbody>
					<tr>
						<td>
							<table width="900" cellpadding="5" cellspacing="1">
								<tr>
									<td width="100">Nombre del campo:</td>
									<td width="350">
										<input name="nombre" type="text" size="30" maxlength="100" style="float:left;"/>
									</td>
									<td width="100" >Tipo:</td>
									<td>
										<select name="tipo" onchange="javascript:ocultarMostrar(this)">
											<?PHP
											$query_tipos_alta = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_tipos";
											$result_tipos_alta = fullQuery($query_tipos_alta);
											while ($tipo_alta = mysqli_fetch_array($result_tipos_alta)) {
												?>
												<option value="<?PHP echo $tipo_alta['id']; ?>"><?PHP echo $tipo_alta['detalle']; ?></option>
<?PHP } ?>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div id="EmpDiv1" style="display:none;">
								<table>
									<tr><td colspan="2"><strong>OPCIONES</strong></td></tr>
									<tr>
										<td width="100">Opci&oacute;n 1: </td>
										<td>
											<input name="opcion1" id="opcion1" type="text" size="30" maxlength="100" style="float:left;"/>
											<a class="button" href="javascript:void();" onclick="javascript:Mostrar(1)"><span class="icon icon67"></span>
																				<span class="label">Agregar m&aacute;s opciones</span></a></td>
									</tr>
								</table>
							</div>						
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">
							<?PHP
							$cont_alta = 1;
							while ($cont_alta <= 20) {
								$cont_alta++;
								?>
								<div id="EmpDiv<?PHP echo $cont_alta; ?>" style="display:none">
									<table>
										<tr>
											<td width="100">
												Opci&oacute;n <?PHP echo $cont_alta; ?>:											</td>
											<td>
												<input name="opcion<?PHP echo $cont_alta; ?>" id="opcion<?PHP echo $cont_alta; ?>" type="text" size="30" maxlength="100" style="float:left;"/>
												&nbsp;
                                                <a class="button" href="javascript:void();" onclick="javascript:Mostrar(<?PHP echo $cont_alta; ?>)"><span class="icon icon67"></span>
																				<span class="label">Agregar m&aacute;s opciones</span></a>
												<a class="button" href="javascript:void();" onclick="javascript:Ocultar(<?PHP echo $cont_alta; ?>)"><span class="icon icon186"></span>
																				<span class="label">eliminar esta opcion</span></a>
											</td>
										</tr>
									</table>
								</div>
<?PHP } ?>						</td>
					</tr>
					<tr>
						<td height="30" colspan="2">
							<div align="center">
							  <!--Orden: <input name="orden" type="text" size="3" maxlength="3" />-->
								<input name="func" type="hidden" value="1"/>
								<a href="javascript:void(0);" class="button" id="grabarTipo" onclick="submitformCrear();">
									<span class="icon icon67"></span>
									<span class="label">Grabar</span>
								</a>

<!--<a href="javascript: submitformCrear()"><img src="img/crear2.png" alt="Crear" border="0" /></a>-->
							</div>                        
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>