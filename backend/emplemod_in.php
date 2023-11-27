<?PHP
$contador_tipo = 0;
?>
<form method="POST" action="emplemod.php" id="emplemod">
	<input name="envio" type="hidden" value="1" />
	<div class="row-fluid">
		<div class="widget">
			<div class="widget-content table-container">
				<?PHP
                while($contador_tipo <= 1){ // 0 es altas y 1 es bajas
                    $sqlfunc = ($contador_tipo == 0) ? '=' : '<';
					$sqlacti = ($contador_tipo == 0) ? 'NOT' : '';
					$tituform = ($contador_tipo == 0) ? 'Altas' : 'Bajas';
					$sqlfech = ($contador_tipo == 0) ? 'Ingreso' : 'Egreso';
					$campo_comp = ($contador_tipo == 0) ? 'a' : 'b';
					$campo_id = ($contador_tipo == 0) ? '1' : '2';
					$empletab = ($contador_tipo == 0) ? 'empleados' : 'intranet_empleados';
					$campoadic = ($contador_tipo == 0) ? ', emp.fecha_ing AS ingreso' : ', emple.fecha_ret AS retiro';
					$tabjoin   = ($contador_tipo == 0) ? '' : ' INNER JOIN empleados AS emple ON emple.rut = emp.rut';
					$whereadic = ($contador_tipo == 0) ? " " : ' AND emp.activo = 1 AND emp.del = 0 AND fecha_ret <= "'.date("Y-m-d").'"';
					/*
					$res_alta = "SELECT  emp.*
					FROM    empleados AS emp
					WHERE   1
						AND emp.fecha_ret ".$sqlfunc." '3000-01-01'
						AND ".$sqlacti." EXISTS
							(
							SELECT  1
							FROM    intranet_empleados intr
							WHERE   intr.rut = emp.rut ".$sqladic."
							)
							LIMIT 20";
					*/
					$ids_incluidas = obtenerDato('datos','mods',$campo_id,'emp_');
					if($ids_incluidas != ''){
						$res_alta = "SELECT emp.id, emp.apellido, emp.nombre, emp.rut ".$campoadic." FROM ".$empletab." AS emp 
										".$tabjoin."
										WHERE emp.RUT IN (".$ids_incluidas.") 
										".$whereadic." ";
										if($contador_tipo == 0){$res_alta.= " ORDER BY emp.fecha_ing DESC ";}
						$result = fullQuery($res_alta, 'emplemod_in.php');
						$contar = mysqli_num_rows($result);
						
						//echo '<br>cantidad: '.$contar.'<br>';
						
						if($contar > 0){
							?>
							<div class="widget-header"><span class="title"><?PHP echo $tituform;?></span></div>
							<table class="table table-striped table-checkable">
								<thead>
									<tr>
										<th class="checkbox-column">
												<i class="icol-accept"></i>
											</th>
											<th>Nombre</th>
											<th>RUT</th>
											<th>Fecha <?PHP echo $sqlfech;?></th>
									</tr>
								</thead>
								<tbody>
										<?PHP
										while($persona=mysqli_fetch_array($result)){
											$perid = $persona['rut'];
											$fechavar = ($contador_tipo == 0) ? fechaDet($persona['ingreso'],'corto') : fechaDet($persona['retiro'],'corto');
											?>
											<tr>
												<td class="checkbox-column">
													<input type="radio" class="uniform" name="dato<?PHP echo $campo_comp;?>_<?PHP echo $perid;?>" value="1">
												</td>
												<td width="400"><?PHP echo $persona['apellido'].', '.$persona['nombre'];?></td>
												<td width="400"><?PHP echo $persona['rut'];?></td>
												<td width="200"><?PHP echo $fechavar;?></td>
											</tr>
										<?PHP } //Cierra While?>
								</tbody>
							</table>
						<?PHP } ?>
                    <?PHP } ?>
        			<?PHP $contador_tipo++;?>
				<?PHP } ?>
			</div>
			<div class="toolbar btn-toolbar">
				<div class="btn-group">
					<span class="btn" onclick="formSubmit('emplemod')"><i class="icol-accept"></i> Confirmar Seleccionados</span>
				</div>
			</div>
		</div>
	</div>
</form>