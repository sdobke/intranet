<?PHP
$buscados = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : '';
?>
<div class="widget-content form-container">
	<form action="<?PHP echo $_SERVER['PHP_SELF'];?>" method="post" class="form-vertical">
		<fieldset>
			<div class="row-fluid sheepit-form">
				<div class="control-group clearfix">
					<div class="span4">
                        <label class="control-label" for="mes" >
                            <input name="fecha" type="radio" value="mes" <?PHP if($fecha == 'mes'){echo 'checked="checked"';}?> /> Mes y a&ntilde;o
                        </label>
						<div class="controls controls-row">
							<select id="mes" name="mes" class="span4">
                                <option value="01" <?PHP echo mesActual('01');?>>Enero</option>
                                <option value="02" <?PHP echo mesActual('02');?>>Febrero</option>
                                <option value="03" <?PHP echo mesActual('03');?>>Marzo</option>
                                <option value="04" <?PHP echo mesActual('04');?>>Abril</option>
                                <option value="05" <?PHP echo mesActual('05');?>>Mayo</option>
                                <option value="06" <?PHP echo mesActual('06');?>>Junio</option>
                                <option value="07" <?PHP echo mesActual('07');?>>Julio</option>
                                <option value="08" <?PHP echo mesActual('08');?>>Agosto</option>
                                <option value="09" <?PHP echo mesActual('09');?>>Septiembre</option>
                                <option value="10" <?PHP echo mesActual('10');?>>Octubre</option>
                                <option value="11" <?PHP echo mesActual('11');?>>Noviembre</option>
                                <option value="12" <?PHP echo mesActual('12');?>>Diciembre</option>
                            </select>
							<select name="ano" id="ano" class="span3">
								<?PHP
                                $query_fechas = fullQuery("SELECT DISTINCT YEAR(fecha) AS anio FROM intranet_accesos_detalle ORDER BY anio DESC");
                                while($fechanio = mysqli_fetch_array($query_fechas)){
                                    $anio_sel = $fechanio['anio'];
                                    ?>
                                    <option value="<?PHP echo $anio_sel;?>" <?PHP echo anioActual($anio_sel);?>><?PHP echo $anio_sel;?></option>
                                <?PHP } ?>
							</select>
						</div>
					</div>
                    <?PHP // POR ERROR DE LA PLANTILLA SE INCLUDE ESTE DIV PARA FECHA
					echo '<div class="datepicker-inline" style="display:none"></div>';
					// FIN ERROR PLANTILLA?>
					<div class="span6">
                        <label class="control-label" for="mes" >
                            <input name="fecha" type="radio" value="per" <?PHP if($fecha == 'per'){echo 'checked="checked"';}?> /> Per&iacute;odo
                        </label>
                        <?PHP
                        $fecdi    = substr($fechadesde,8,2);
                        $fecme    = substr($fechadesde,5,2);
                        $fecan    = substr($fechadesde,0,4);
                        $fecha_in = $fecdi.'/'.$fecme.'/'.$fecan;
                        $fecdi    = substr($fechahasta,8,2);
                        $fecme    = substr($fechahasta,5,2);
                        $fecan    = substr($fechahasta,0,4);
                        $fecha_ou = $fecdi.'/'.$fecme.'/'.$fecan;
						?>
						<div class="controls controls-row">
							<div style="float:left" class="span4">
                            <label class="control-label" for="dp-cmy">Desde</label>
                            <input type="text" id="dp-cmy" class="datepicker-cmy" name="fecha_desde" value="<?PHP echo $fecha_in;?>">
                            </div>
                            &nbsp;&nbsp;
                            <div style="float:left" class="span4" >
							<label class="control-label" for="dp-cmy2">Hasta</label>
                           	<input type="text" id="dp-cmy2" class="datepicker-cmy" name="fecha_hasta" value="<?PHP echo $fecha_ou;?>">
                            </div>
						</div>
                    </div>
                </div>
			</div>
		</fieldset>
		<div class="form-actions">
	        <input name="posteado" id="posteado" type="hidden" value="1" />
			<button name="search" title="buscar" class="btn btn-primary">Buscar</button>
		</div>
	</form>
                    <div class="control-group clearfix" align="center">
					<a href="<?PHP echo $_SERVER['PHP_SELF'];?>?fecha=tot"><button class="btn btn-info">Ver hist&oacute;rico total</button></a>
				</div>
</div>