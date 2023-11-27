<?PHP
$query_combos = "SELECT tab.nombre AS tabla, cbo.campo AS variable, cbo.nombre AS nombre, tab.titulo AS tit, cbo.manual AS man
		FROM ".$pref_tab."combos AS cbo
INNER JOIN ".$pref_tab."tablas AS tab ON (cbo.combo = tab.id)
	WHERE cbo.id = ".$rowcv['id'];
//echo $query_combos;
$resul_combos = fullQuery($query_combos);
$row_combos = mysqli_fetch_assoc($resul_combos);
$idsel = $row_combos['man'];// Si utiliza selectores por código
$var_combo = $row_combos['variable'];
$tit_combo = ($row_combos['tit'] != '') ? $row_combos['tit'] : 'nombre';
$titulo_combo = $row_combos['nombre'];
if($titulo_combo == ''){$titulo_combo = ucwords($var_combo);}
$tabla_combo  = $row_combos['tabla'];
echo '<label class="control-label" for="'.$var_combo.'">'.txtcod($titulo_combo).'</label>
		<div class="controls">';
// SELECT
if($tipoarchivo == 'detalles'){
	// Valor seleccionado
	$sql_select1 = "SELECT ".$var_combo." AS val FROM ".$_SESSION['prefijo'].$nombretab." WHERE id = ".$id;
	$res_select1 = fullQuery($sql_select1);
	$row_select1 = mysqli_fetch_assoc($res_select1);
	$valor = $row_select1['val'];
}else{
	$valor = 0;
	if($tipo == 2){ // Si es jugadores
		switch($var_combo){
			case 'tipotorneo':
				$valor = 3;
				break;
			case 'tipodoc':
				$valor = 1;
				break;
		}
	}
}
$cambiarid = '';
$spancod = 12;
$cbotab = $taborden;
if($idsel == 1){
	echo '<input '.$taborden.' class="span2" id="id_'.$var_combo.'" name="id_'.$var_combo.'" type="text" value="'.$valor.'" onChange="cambiaSelect(this.value, this.id)" />';
	$cambiarid = 'onChange="cambiaInput(this.value, this.id)"';
	$spancod = 10;
	$cbotab = '';
}
echo '<select name="'.$var_combo.'" class="span'.$spancod.'" id="'.$var_combo.'" '.$cambiarid.$cbotab.'>';
$sql_combo = "SELECT * FROM ".$pref_tab.$tabla_combo." WHERE del = 0 ORDER BY ".$tit_combo;
//echo $sql_combo;
$res_combo = fullQuery($sql_combo);
while($row_combo = mysqli_fetch_array($res_combo)){
	$codid = ($idsel == 1) ? 'codigo' : 'id';
	$es_activo = ($valor == $row_combo[$codid]) ? 'selected="selected"' : '';
	echo '<option value="'.$row_combo[$codid].'" '.$es_activo.' >'.txtcod($row_combo[$tit_combo]).'</option>';
}
echo '</select>
	</div>';