<?PHP
$spancod = (!isset($spancod)) ? 12 : $spancod;
$basespancod = $spancod;
$query_combos = "SELECT tab.nombre AS tabla, tab.id AS idtab, cbo.campo AS variable, cbo.nombre AS nombre, tab.titulo AS tit, tab.anidable, cbo.multiple AS multi
					FROM " . $_SESSION['prefijo'] . "combos AS cbo
						INNER JOIN " . $_SESSION['prefijo'] . "tablas AS tab ON (cbo.combo = tab.id)
							WHERE cbo.tabla = " . $tipo;
$resul_combos = fullQuery($query_combos);
while ($row_combos = mysqli_fetch_array($resul_combos)) {
	$spancod = $basespancod;
	$var_combo = $row_combos['variable'];
	$tit_combo = ($row_combos['tit'] != '') ? $row_combos['tit'] : 'nombre';
	$titulo_combo = $row_combos['nombre'];
	$cbo_multi = $row_combos['multi'];
	if ($titulo_combo == '') {
		$titulo_combo = ucwords($var_combo);
	}
	$tabla_combo  = $row_combos['tabla'];

	// Multiempresas

	if ($var_combo == 'empresa' && $multiemp == 0) {
		echo '<input type="hidden" name="empresa" value="1" />';
	} else {
		//echo '<br>mult: '.$cbo_multi;
		$selmul = $varmulti = '';
		if ($cbo_multi == 1) {
			$selmul =	' multiple ';
			$varmulti = '[]';
			$spancod .= ' multiselector select_'.$var_combo.' ';
			$titulo_combo.= '<br>'._('(CTRL + click para seleccionar varios)');
		}
		echo '<div class="control-group var_' . $tabla_combo . '">
				<label class="control-label" for="' . $var_combo . '">' . txtcod($titulo_combo) . '</label>
				<div class="controls">';
		// SELECT
		if ($funcion_archivo == 'detalles') {
			// Valor seleccionado
			$valor = 0;
			if($var_combo == 'area' && $tipodet == 'empleados'){
				$sqlar = "SELECT ia.id FROM intranet_areas ia INNER JOIN intranet_empleados_areas iea ON iea.area = ia.id WHERE iea.empleado = ".$id;
				$resar = fullQuery($sqlar);
				if(mysqli_num_rows($resar) > 0){
					$conar = 0;
					while($rowar = mysqli_fetch_array($resar)){
						if($conar > 0){$valor.=',';}
						$valor.=$rowar['id'];
						$conar++;
					}
					$valor = explode(",",$valor);
				}
			}else{
				$sql_select1 = "SELECT *," . $var_combo . " AS val FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE id = " . $id;
				$res_select1 = fullQuery($sql_select1);
				$row_select1 = mysqli_fetch_assoc($res_select1);
				$valor = $row_select1['val'];
				if($cbo_multi == 1){
					$valor = explode(",",$valor);
				}
			}
		} else {
			$valor = 0;
		}

		echo '<select ' . $selmul . ' name="' . $var_combo . $varmulti . '" class="span' . $spancod . '" id="select">';
		$ordencbo = ($var_combo == 'seccion') ? 'id' : $tit_combo;
		$ordencbo = ' ORDER BY ' . $ordencbo;
		/*
		if($var_combo == 'area'){
			$ordencbo = ' ORDER BY CASE WHEN parent = 0 THEN ID ELSE parent END, parent, id';
		}
		*/
		$sql_combo = "SELECT * FROM " . $_SESSION['prefijo'] . $tabla_combo . " WHERE del = 0 " . $ordencbo;

		if ($row_combos['anidable'] == 1) {

			$sqlpar = "SELECT * FROM " . $_SESSION['prefijo'] . $tabla_combo . " WHERE del = 0 AND parent = 0 ORDER BY nombre";

			$respar = fullQuery($sqlpar);
			while ($rowpar = mysqli_fetch_array($respar)) {
				$nombredato = txtcod($rowpar['nombre']);
				echo '<option value="' . $rowpar['id'] . '" ' . optSel($rowpar['id'], $valor) . ' > ' . $nombredato . '</option>
				';
				echo optChild($_SESSION['prefijo'] . $tabla_combo, $rowpar['id'], $valor, $rowpar['nombre']);
			}
		} else {
			//echo $sql_combo;
			$res_combo = fullQuery($sql_combo);
			while ($row_combo = mysqli_fetch_array($res_combo)) {
				$es_activo = ($valor == $row_combo['id']) ? 'selected="selected"' : '';
				$combotexto = txtcod($row_combo[$tit_combo]);
				//echo '<br>Tipo: '.$row_combos['idtab'];
				if ($row_combos['idtab'] == 1 && $row_combo['parent'] > 0) {
					$combotexto = ' > ' . $combotexto;
				}
				echo '<option value="' . $row_combo['id'] . '" ' . $es_activo . ' >' . $combotexto . '</option>';
			}
		}
		echo '</select>
			</div>
		</div>
		';
	}
}
