<?PHP
$buscados = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : '';
?>
<div class="widget-content form-container">
	<form action="listado.php?tipo=<?PHP echo $tipo; ?>" method="post" class="form-vertical">
		<fieldset>
			<div class="control-group">
				<?php $cantispan = 0;?>
				<div class="controls controls-row">
					<input name="busqueda" type="text" class="span3" id="busqueda" value="<?PHP echo $buscados; ?>" placeholder="Buscar" />
					<?php $cantispan++;?>
					<?PHP // COMBOS
					$query_combos = "SELECT tab.nombre AS tabla, cbo.campo AS variable, cbo.nombre AS nombre, tab.titulo AS tit 
										FROM " . $_SESSION['prefijo'] . "combos AS cbo
										INNER JOIN " . $_SESSION['prefijo'] . "tablas AS tab ON (cbo.combo = tab.id)
										WHERE cbo.tabla = " . $tipo;
					$resul_combos = fullQuery($query_combos);
					$conta_combos = mysqli_num_rows($resul_combos);
					if ($conta_combos > 0) {
						while ($row_combos = mysqli_fetch_array($resul_combos)) {
							$var_combo = $row_combos['variable'];
							$titulo_combo = ($row_combos['nombre'] != '') ? $row_combos['nombre'] : ucwords($var_combo);
							$tabla_combo  = $row_combos['tabla'];
							$tit_cbo = $row_combos['tit'];
							// Valor seleccionado
							$$var_combo   = (isset($_POST['bus_' . $tabla_combo])) ? $_POST['bus_' . $tabla_combo] : 0;
							// SELECT
							echo '<select class="span3 '.$row_combos['tabla'].'" name="bus_' . $tabla_combo . '">';
							echo '<option value="0">Todos: ' . txtcod($titulo_combo) . '</option>';
							$tabla_where = '';
							if ($row_combos['tabla'] == 'areas') {
								$tabla_where = ' AND parent = 0';
							}
							$sql_combo = "SELECT * FROM " . $_SESSION['prefijo'] . $tabla_combo . " WHERE del = 0 " . $tabla_where . " ORDER BY " . $tit_cbo;
							$res_combo = fullQuery($sql_combo);
							$con_combo = mysqli_num_rows($res_combo);
							if ($con_combo > 0) {
								while ($row_combo = mysqli_fetch_array($res_combo)) {
									$es_activo = ($$var_combo == $row_combo['id']) ? 'selected="selected"' : '';
									echo '<option value="' . $row_combo['id'] . '" ' . $es_activo . ' >' . txtcod($row_combo['nombre']) . '</option>';
								}
							}
							echo '</select>';
							if($row_combos['tabla'] == 'areas'){
								echo '<div class="span3" id="subareas"></div>';
								$cantispan++;
								if($cantispan % 4 == 0){
									echo '</div><div class="controls controls-row">';
								}
							}
							$cantispan++;
							if($cantispan % 4 == 0){
								echo '</div><div class="controls controls-row">';
							}
						}
					}
					if ($tipodet == 'empleados') { // Activos e inactivos
						echo '<select class="span3" name="bus_empactivos">';
						$es_activo1 = ($emacti == 0) ? 'selected="selected"' : '';
						$es_activo2 = ($emacti == 1) ? 'selected="selected"' : '';
						$es_activo3 = ($emacti == 2) ? 'selected="selected"' : '';
						echo '<option value="0" ' . $es_activo1 . '>Todos: act. e inact.</option>';
						echo '<option value="1" ' . $es_activo2 . '>Activos</option>';
						echo '<option value="2" ' . $es_activo3 . '>Inactivos</option>';
						echo '</select>';
					}
					?>
				</div>
			</div>
		</fieldset>
		<div class="form-actions">
			<button name="search" title="buscar" class="btn btn-primary">Buscar</button>
		</div>
	</form>
</div>