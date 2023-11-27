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
	$display.='<label class="control-label" for="'.$vars_variable.'">'.$vars_nombre.'</label><div class="controls">';
}
$displaygo = 1;
switch($vars_tipo){
	case 1: // Números
		$vars_val = ($vars_val == 0) ? '' : $vars_val;
	case 2: // Texto
		$display.='<input '.$taborden.' id="'.$vars_variable.'" name="'.$vars_variable.'" type="text" class="span12" value="'.$vars_val.'" />';
		break;
	case 3: // S/N
		$display.="<label class='radio'>Si <input ".$taborden." name='".$vars_variable."' type='radio' value='1' ".optSel($vars_val, 1, 1)." /></label>
  <label class='radio'>NO <input ".$taborden." name='".$vars_variable."' type='radio' value='0' ".optSel($vars_val, 0, 1)." /></label>";
		break;
	case 4: // Hora
		$typ = 'text';
		switch($row_vars['id']){
			case 32: // Hora de fichaje
				date("H:i");
				$typ = 'hidden';
				$displaygo = 0;
				break;
		}
		$display.='<input '.$taborden.' id="timepicker-basic" type="'.$typ.'" class="span12" name="hora" value="'.substr($vars_val,0,5).'">';
		break;
	case 5: // Fecha
		$fecha_def = $vars_val;
		$fecdi    = substr($fecha_def,8,2);
		$fecme    = substr($fecha_def,5,2);
		$fecan    = substr($fecha_def,0,4);
		$fecha_in = $fecdi.'/'.$fecme.'/'.$fecan;
		$typ = 'text';
		switch($row_vars['id']){
			case 9: // Fecha de fichaje
				date("Y-m-d");
				$typ = 'hidden';
				$displaygo = 0;
				break;
		}
		$display.='<input '.$taborden.' type="'.$typ.'" id="dp-cmy'.$vconta.'" class="span12 datepicker-cmy" name="'.$vars_variable.'" value="'.$fecha_in.'">';
		break;
	case 6: // Texto libre
		$display.='<textarea class="span12" '.$taborden.' id="cleditor" name="'.$vars_variable.'">'.$vars_val.'</textarea>';
		break;
	case 7: // Fotos
		$displaygo = 0;
		break;
	case 8: // Especiales
		switch($row_vars['id']){
			case 1: // Si es el código de usuario
				$valor_val = $_SESSION['id_usr'];
				$displaygo = 0;
				break;
		}
		echo '<input name="'.$vars_variable.'" type="hidden" id="'.$vars_variable.'" class="span12" value="'.$valor_val.'" />';
		$displaygo = 0;
		break;
	case 9: // Password
		$display.='<input '.$taborden.' name="'.$vars_variable.'" id="'.$vars_variable.'" type="password" class="span12" value="'.$vars_val.'" />';
		break;
}
if($mostrar_label == 1){
	$display.='</div> <!--cierra controls-->
	';
}
$vconta++;
?>