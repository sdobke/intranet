<?PHP
$mostrar_existentes_solamente = 0;
$buscados = (isset($_REQUEST['busqueda'])) ? $_REQUEST['busqueda'] : '';
$_SESSION['prefijo'] = 'intranet_';
$inner_extra = '';
?>
<div class="">
	<form action="?" method="get" class="buscador_interno" id="buscador_datos">
		<fieldset>
			<div class="row fields">
				<div class="col-12 col-md-4 col-lg-4">
					<input name="busqueda" type="text" id="busqueda" value="<?PHP echo $buscados; ?>" placeholder="Buscar" class="form-control" />
				</div>
				<?PHP // COMBOS
				$query_combos = "SELECT tab.nombre AS tabla, cbo.campo AS variable, cbo.nombre AS nombre, tab.titulo AS tit, cbo.buscador_formato AS formato
                    FROM " . $_SESSION['prefijo'] . "combos AS cbo
                    INNER JOIN " . $_SESSION['prefijo'] . "tablas AS tab ON (cbo.combo = tab.id)
                    WHERE cbo.tabla = " . $tipo;
				if ($multiemp == 0) {
					$query_combos .= " AND tab.nombre != 'empresas' ";
					$empre = $cod;
				}
				$resul_combos = fullQuery($query_combos);
				$conta_combos = mysqli_num_rows($resul_combos);
				if ($conta_combos > 0) {
					$cont_cbos_deb = 0;
					$debug_cbos = array();
					while ($row_combos = mysqli_fetch_array($resul_combos)) {
						$where_extra = '';
						$var_combo = $row_combos['variable'];
						$titulo_combo = ($row_combos['nombre'] != '') ? $row_combos['nombre'] : ucwords($var_combo);
						$tabla_combo  = $row_combos['tabla'];
						$tit_cbo = $row_combos['tit'];
						// Valor seleccionado
						if(!isset($$var_combo)){
							$$var_combo   = (isset($_POST['bus_' . $tabla_combo])) ? $_POST['bus_' . $tabla_combo] : 0;
							$$var_combo   = (isset($_GET['bus_' . $tabla_combo])) ? $_GET['bus_' . $tabla_combo] : $$var_combo;
						}
						if ($row_combos['formato'] == 0) { // SELECT
							echo '<div class="col-12 col-xs-6 col-md-3 col-lg-3">';
							// SELECT
							echo '<select class="form-select" id="formu_'.$tabla_combo.'" name="bus_' . $tabla_combo . '" onChange="this.form.submit()">';
							echo '<option value="0">Todos: ' . txtcod($titulo_combo) . '</option>';
							$where_rest = $inner_rest = $where_crop = '';
							if ($tipo == 34 || $tipo == 3) { // Si son políticas o documentos útiles
								$where_rest = $sql_restric;
								if ($tabla_combo == 'areas') {
									$where_extra .= ' AND parent = 0 ';
								}
								if ($mostrar_existentes_solamente == 1) {
									$inner_extra = " INNER JOIN " . $_SESSION['prefijo'] . $nombretab . " AS inov ON inov." . $var_combo . "=tabla.id  ";
									$where_extra .= ' AND inov.del = 0 ';
								}
								//$inner_rest.= " INNER JOIN " . $_SESSION['prefijo'] . "link AS il ON inov.id = il.item ";
								if (isset($empre) && $tabla_combo != 'empresas' && $empre > 0) {
									//$where_crop .= ' AND inov.empresa = ' . $empre . ' ';
								}
								if (isset($_REQUEST['bus_areas']) && $tabla_combo != 'areas' && $tabla_combo != 'empresas' && $_POST['bus_areas'] > 0) {
									$where_crop .= ' AND inov.area = ' . $_REQUEST['bus_areas'] . ' ';
								}
								if (isset($_REQUEST['bus_sectores']) && $tabla_combo != 'sectores' && $tabla_combo != 'areas' && $tabla_combo != 'empresas' && $_POST['bus_sectores'] > 0) {
									$where_crop .= ' AND inov.sector = ' . $_REQUEST['bus_sectores'] . ' ';
								}
							}
							//$sql_combo = "SELECT tabla.* FROM " . $_SESSION['prefijo'] . $tabla_combo . " AS tabla " . $inner_rest . " WHERE 1 AND inov.del = 0 AND tabla.del = 0 " . $where_rest . $where_crop . " GROUP BY tabla.id ORDER BY tabla." . $tit_cbo;
							$sql_combo = "SELECT tabla.* FROM " . $_SESSION['prefijo'] . $tabla_combo . " AS tabla " . $inner_extra . " WHERE 1 AND tabla.del = 0 " . $where_crop . $where_extra . " GROUP BY tabla.id ORDER BY tabla." . $tit_cbo;
							$sql_child = "SELECT tabla.* FROM " . $_SESSION['prefijo'] . $tabla_combo . " AS tabla " . $inner_extra . " WHERE 1 AND tabla.del = 0 " . $where_crop .  " |	GROUP BY tabla.id ORDER BY tabla." . $tit_cbo;
							//echo $sql_combo;
							$debug_cbos[$cont_cbos_deb] = $sql_combo;
							$res_combo = fullQuery($sql_combo);
							$con_combo = mysqli_num_rows($res_combo);
							if ($con_combo > 0) {
								$debug = '';
								while ($row_combo = mysqli_fetch_array($res_combo)) {
									$debug.= '<br><br>Area: '.$area;
									$debug.= '<br>'.$var_combo.': '.$$var_combo.' valor: '.$row_combo['id'];
									$nomdato = txtcod(ucwords($row_combo['nombre']));
									$nomdato = str_replace(" Y ", " y ", $nomdato);
									$es_activo = ($$var_combo == $row_combo['id']) ? 'selected="selected"' : '';
									if ($tabla_combo == 'areas') {
										echo '<option value="' . $row_combo['id'] . '" ' . $es_activo . ' >' . $nomdato . '</option>';
										echo optChild($_SESSION['prefijo'] . "areas", $row_combo['id'], $$var_combo, $nomdato, 0);
									} else {
										echo '<option value="' . $row_combo['id'] . '" ' . $es_activo . ' >' . $nomdato . '</option>';
									}
								}
							}
							echo '</select>';
							$cont_cbos_deb++;
							echo '</div>';
						}else{ // Botones
							echo '<div class="col-12 col-xs-6 col-md-3 col-lg-3">';
							$sql_combo = "SELECT tabla.* FROM " . $_SESSION['prefijo'] . $tabla_combo . " AS tabla " . $inner_extra . " WHERE 1 AND tabla.del = 0 " . $where_crop . $where_extra . " GROUP BY tabla.id ORDER BY tabla." . $tit_cbo;
							$sql_child = "SELECT tabla.* FROM " . $_SESSION['prefijo'] . $tabla_combo . " AS tabla " . $inner_extra . " WHERE 1 AND tabla.del = 0 " . $where_crop .  " |	GROUP BY tabla.id ORDER BY tabla." . $tit_cbo;
							//echo $sql_combo;
							$debug_cbos[$cont_cbos_deb] = $sql_combo;
							$res_combo = fullQuery($sql_combo);
							$con_combo = mysqli_num_rows($res_combo);
							if ($con_combo > 0) {
								while ($row_combo = mysqli_fetch_array($res_combo)) {
									//echo '<div class="col-6 col-xs-4 col-sm-3 col-lg-2">';
									$nomdato = txtcod(ucwords($row_combo['nombre']));
									$nomdato = str_replace(" Y ", " y ", $nomdato);
									//echo '<br>Post: '.$$var_combo.' ID: '.$row_combo['id'].'<br>';
									$es_activo = ($$var_combo == $row_combo['id']) ? 1 : 0;
									$botclass = ($es_activo == 1) ? 'success' : 'info';
									//echo '<option value="' . $row_combo['id'] . '" ' . $es_activo . ' >' . $nomdato . '</option>';
									if($es_activo == 1){
										echo '<input type="hidden" name="bus_' . $tabla_combo . '" value="'.$row_combo['id'].'">';
									}
									//echo '<button type="submit" name="bus_'.$tabla_combo.'" value="' . $row_combo['id'] . '" class="mr5 btn btn-small btn-'.$botclass.'">' . $nomdato . '</button>';
									echo '<a href="?'.$vars.'&bus_'.$tabla_combo.'=' . $row_combo['id'] . '" class="mr5 btn btn-small btn-'.$botclass.'">' . $nomdato . '</a>';
									//echo '</div>';
								}
							}
							echo '</div>';
						}
					}
				}
				?>
				<div class="col-12 col-xs-6 col-md-2 col-lg-2">
					<input type="submit" name="search" value="Buscar" class="btn  btn-primary  btn-small" />
				</div>
					<?php
						$fileTypes = array(
							'xls' => 'Excel',
							'doc' => 'Word',
							'pdf' => 'PDF',
							'form' => 'Form'
						);
					?>
					<div class="col-12 col-xs-6 col-md-3 col-lg-3 mt-1">
						<select class="form-select" id="bus_ext" name="bus_ext" onChange="this.form.submit()">
						
						<?php 
							echo '<option value="">Seleccionar tipo de archivo</option>';
							foreach ($fileTypes as $extension => $tipo) {
								echo '<option value="' . $extension . '">' . $tipo . '</option>';
							}
						?>
						</select>
					</div>
			</div>
		</fieldset>
	</form>
</div>
<?PHP
$mdebug = 0;
if ($mdebug == 1) {
	foreach ($debug_cbos as $key => $val) {
		echo '<br><br>' . $val;
	}
}
//echo $debug;
?>