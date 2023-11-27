<?PHP
$query_vars = "SELECT * FROM ".$pref_tab."vars WHERE id = ".$rowcv['id'];
$resul_vars = fullQuery($query_vars);
$row_vars = mysqli_fetch_assoc($resul_vars);
$vars_variable = $row_vars['variable'];
$vars_tipo = $row_vars['tipo'];
$mostrar_label = 1;
if($vars_tipo == 8){// casos especiales
	if($row_vars['id'] == 1){ // Si es el código de usuario
		$mostrar_label = 0;
	}
}
$vars_nombre = ($row_vars['nombre'] == '') ? txtcod(ucwords($vars_variable)) : txtcod($row_vars['nombre']);
if($tipoarchivo == 'detalles'){
	$vars_val = txtcod($noticia[$vars_variable]);
}else{
	$vars_val = '';
	if($vars_tipo == 5){$vars_val = date("Y-m-d");}
	if($vars_tipo == 4){$vars_val = date("H:m");}
}
if($mostrar_label == 1){
	echo'<label class="control-label" for="'.$vars_variable.'">'.$vars_nombre.'</label><div class="controls">';
}
switch($vars_tipo){
	case 1: // Números
		$vars_val = ($vars_val == 0) ? '' : $vars_val;
	case 2: // Texto
		echo '<input '.$taborden.' name="'.$vars_variable.'" type="text" class="span12" value="'.$vars_val.'" />';
		break;
	case 3: // S/N
		echo "<label class='radio'>Si <input ".$taborden." name='".$vars_variable."' type='radio' value='1' ".optSel($vars_val, 1, 1)." /></label>
  <label class='radio'>NO <input ".$taborden." name='".$vars_variable."' type='radio' value='0' ".optSel($vars_val, 0, 1)." /></label>";
		break;
	case 4: // Hora
	echo '<input '.$taborden.' id="timepicker-basic" type="text" class="span2" name="hora" value="'.substr($vars_val,0,5).'">';
		break;
	case 5: // Fecha
		$fecha_def = $vars_val;
		$fecdi    = substr($fecha_def,8,2);
		$fecme    = substr($fecha_def,5,2);
		$fecan    = substr($fecha_def,0,4);
		$fecha_in = $fecdi.'/'.$fecme.'/'.$fecan;
		echo '<input '.$taborden.' type="text" id="dp-cmy'.$vconta.'" class="span3 datepicker-cmy" name="'.$vars_variable.'" value="'.$fecha_in.'">';
		break;
	case 6: // Texto libre
		echo '<textarea '.$taborden.' id="cleditor" name="'.$vars_variable.'">'.$vars_val.'</textarea>';
		break;
	case 8: // Especiales
		if($row_vars['id'] == 1){ // Si es el código de usuario
			echo '<input name="'.$vars_variable.'" type="hidden" class="span12" value="'.$_SESSION['id_usr'].'" />';
		}
		break;
	case 9: // Password
		echo '<input '.$taborden.' name="'.$vars_variable.'" type="password" class="span12" value="'.$vars_val.'" />';
		break;
}
if($mostrar_label == 1){
	echo '</div> <!--cierra controls-->
	';
}
$vconta++;
?>