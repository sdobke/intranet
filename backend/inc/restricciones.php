<?PHP
$tf = 'backend/inc/restricciones.php';
$tabla_combo = 'areas';
echo '<div class="control-group">
		<label class="control-label" for="Restricciones">Visibilidad: </label>';
echo '<div class="controls">';
$check_si = '';
$todos = 1;
$areas_act = 0;

$sql_part = "SELECT part FROM " . $_SESSION['prefijo'] . "link WHERE item = " . $id . " AND tipo = " . $tipo;
$res_part = fullQuery($sql_part);
$con_part = mysqli_num_rows($res_part);
if ($con_part == 1) {
	$row_part = mysqli_fetch_assoc($res_part);
	$areas_act = $row_part['part'];
}
$areas_act = explode(",", $areas_act);
/*
if($tipoarchivo == 'detalles'){
	// Busca si está la selección de Todos activa
	$query_si = "SELECT * FROM ".$_SESSION['prefijo']."link WHERE item = ".$id." AND tipo = ".$tipo;
	$resul_si = fullQuery($query_si,$tf);
	$conta_si = mysqli_num_rows($resul_si);
}
if($conta_si == 0){
	$check_si = 'checked="checked"';
}
*/

$sql_combo = "SELECT * FROM " . $_SESSION['prefijo'] . $tabla_combo . " WHERE parent = 0 AND del = 0 ORDER BY nombre";
$res_combo = fullQuery($sql_combo);
if (mysqli_num_rows($res_combo) > 0) {
	$muestra_checks = '';
	while ($row_combo = mysqli_fetch_array($res_combo)) {
		$res_area = $row_combo['id'];
		if (in_array($res_area, $areas_act)) {
			$es_activo = 'checked="checked"';
		} else {
			$todos = 0;
			$es_activo = '';
		}
		// Area Parent
		$muestra_checks .= '<br /><input type="checkbox" onclick="childrenAct(' . $res_area . ')" id="valor_' . $res_area . '" name="valor_' . $res_area . '" ' . $es_activo . ' /> ' . txtcod($row_combo['nombre']) . '
		';
		// Sub áreas
		$muestra_checks.= childBox($tabla_combo, $res_area, $areas_act);
		
		
	}
}
if ($todos == 1) {
	$check_si = 'checked="checked"';
}
//echo '<input type="checkbox" id="valor_0" name="valor_0" value="1" '.$check_si.' /> ';
echo '<input id="todos" name="todos" type="checkbox" ' . $check_si . '/> Todos<br />
';
echo $muestra_checks;
echo '</div>
</div>
';
