<div id="ccentro">
	<div style="font-size:16px; color:#999; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif">Filtros</div>
	<?PHP //echo "<br /><br />".$cadbusca.$cadresto."<br /><br />";?>
	<form id="formbuscar" name="formbuscar" method="post" action="resultados.php">
		<table bgcolor="#f6f6f6" style="color:#333" cellpadding="3">
			<tbody>
<!--				<tr>
					<td>Buscar en nombre y comentario</td>
  	  				<td>
  						<input type="text" name="buscartxt" value="<?PHP echo $buscartxt;?>" />
					</td>
				</tr>-->
				<tr>
					
                    <td><!--
						<input name="selec_fecha" type="radio" value="mes" <?PHP if($GLOBALS['selector'] == 'mes'){echo 'checked="checked"';}?> onclick="javascript:mostrarDiv('meses')" />Mes <br />
						<input name="selec_fecha" type="radio" value="per" <?PHP if($GLOBALS['selector'] == 'per'){echo 'checked="checked"';}?> onclick="javascript:mostrarDiv('periodo')"/> Per&iacute;odo-->
					Per&iacute;odo
                    </td>
					<td>
						<!--<div id="meses" style="display:<?PHP echo ($GLOBALS['selector'] == 'mes') ? 'block' : 'none';?>">
							<select id="mes" name="mes" onchange="cambiarRadio()">
								<option value="01" <?PHP echo optSel($mes, 1);?>>Enero</option>
								<option value="02" <?PHP echo optSel($mes, 2);?>>Febrero</option>
								<option value="03" <?PHP echo optSel($mes, 3);?>>Marzo</option>
								<option value="04" <?PHP echo optSel($mes, 4);?>>Abril</option>
								<option value="05" <?PHP echo optSel($mes, 5);?>>Mayo</option>
								<option value="06" <?PHP echo optSel($mes, 6);?>>Junio</option>
								<option value="07" <?PHP echo optSel($mes, 7);?>>Julio</option>
								<option value="08" <?PHP echo optSel($mes, 8);?>>Agosto</option>
								<option value="09" <?PHP echo optSel($mes, 9);?>>Septiembre</option>
								<option value="10" <?PHP echo optSel($mes, 10);?>>Octubre</option>
								<option value="11" <?PHP echo optSel($mes, 11);?>>Noviembre</option>
								<option value="12" <?PHP echo optSel($mes, 12);?>>Diciembre</option>
							</select>
							<select name="ano" id="ano">
								<?PHP $query_fechas = fullQuery("SELECT DISTINCT YEAR(fecha) AS anio FROM ".$_SESSION['prefijo']."encuestas_listado ORDER BY anio");
								while($fechanio = mysqli_fetch_array($query_fechas)){
									$seleccionado = ($fechanio['anio'] == date("Y")) ? 'selected="selected"' : '';
									?>
        		                	<option value="<?PHP echo $fechanio['anio'];?>" <?PHP echo $seleccionado;?>><?PHP echo $fechanio['anio'];?></option>
								<?PHP } ?>
          					</select>
						</div>
						<div id="periodo" style="display:<?PHP echo ($GLOBALS['selector'] == 'per') ? 'block' : 'none';?>">-->
							<?PHP
							$fechainicio = str_replace("-","/",$fechadesde);
							$fechafin    = str_replace("-","/",$fechahasta);
							?>
							<table>
                           		<tr>
                               		<td>Desde <script>DateInput('fecha_desde', true, 'YYYY/MM/DD', '<?PHP echo $fechainicio;?>')</script></td>
                           		</tr>
                           		<tr>
                               		<td>Hasta <script>DateInput('fecha_hasta', true, 'YYYY/MM/DD', '<?PHP echo $fechafin;?>')</script></td>
                           		</tr>
							</table>
						<!--</div>-->
					</td>
				</tr>
				<?PHP
				$conta = 1;
				$query3  = fullQuery("SELECT id, nombre FROM ".$_SESSION['prefijo']."encuestas_campos WHERE nombre != 'fecha' AND opciones = 1 ORDER BY orden");
	            while($campos = mysqli_fetch_array($query3)){?>
				<?PHP } ?>
				<!--<tr bgcolor="#e6e6e6">
					<td>Tipo de comentario</td>
					<td>
						<select name="tipocom" id="tipocom">
							<option value="0" <?PHP echo optSel($tipocom, 0);?>>Todos</option>
							<?PHP
                            $sql_tipocom  = fullQuery("SELECT id,opcion FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = 9 ORDER BY orden");
							while($rowcom = mysqli_fetch_array($sql_tipocom)){
								echo '<option value="'.$rowcom['id'].'" '.optSel($tipocom, $rowcom['id']).'>'.$rowcom['opcion'].'</option>
								';
							}
							?>
						</select>
					</td>
				</tr>-->
            	<tr>
					<td>Items x P&aacute;g.</td>
					<td><input name="items" type="text" id="items" value="<?PHP echo $items;?>" size="10" /></td>
				</tr>
				<!--<tr bgcolor="#e6e6e6">
					<td>Preparar archivo Excel</td>
					<td>
						<input name="generaexcel" type="checkbox" value="1" />
					</td>
				</tr>-->
                <?PHP if( isset($_POST['generaexcel']) && $_POST['generaexcel'] == 1 && file_exists('reporte.xls') ){?>
                <tr>
					<td>Descargar archivo Excel</td>
					<td>
						<a href="reporte.xls"><img src="img/excel.gif" /></a>
					</td>
				</tr>
                <?PHP } ?>
				<tr>
					<td colspan="2" align="center">
						<a href="javascript:void(0);" class="button" onclick="return submitformBuscar();">
							<span class="icon icon198"></span>
							<span class="label">Buscar</span>
						</a>
						
						<!--<a href="javascript: submitformBuscar()"><img src="img/buscar.png" alt="Crear" border="0" /></a>-->
					</td>
				</tr>
			</tbody>	
		</table>
		<input value="1" type="hidden" name="posteado" />
	</form>
</div>