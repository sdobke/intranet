<?PHP
$query_vars = "SELECT * FROM ".$_SESSION['prefijo']."vars WHERE tabla = ".$tipo." ORDER BY id";
$resul_vars = fullQuery($query_vars);
$vconta = 0;
while($row_vars = mysqli_fetch_array($resul_vars)){
	$vars_variable = $row_vars['variable'];
	$vars_tipo = $row_vars['tipo'];
	$vars_nombre = ($row_vars['nombre'] == '') ? txtcod(ucwords($vars_variable)) : txtcod($row_vars['nombre']);
	if($funcion_archivo == 'detalles'){
		$vars_val = txtcod($noticia[$vars_variable]);
	}else{
		$vars_val = '';
	}
	echo '<div class="control-group var_'.$vars_variable.'">
			<label class="control-label" for="'.$vars_variable.'">'.$vars_nombre.'</label>
			<div class="controls">';
	switch($vars_tipo){
		case 1: // Números
			$vars_val = ($vars_val == 0) ? '' : $vars_val;
		case 2: // Texto
			echo '<input name="'.$vars_variable.'" type="text" class="span12" value="'.$vars_val.'" autocomplete="off" readonly onfocus="this.removeAttribute(\'readonly\');" >';
			break;
		case 3: // S/N
			echo "<label class='radio'>Si <input name='".$vars_variable."' type='radio' value='1' ".optSel($vars_val, 1, 1)." /></label>
				  <label class='radio'>NO <input name='".$vars_variable."' type='radio' value='0' ".optSel($vars_val, 0, 1)." /></label>";
			echo '</label>';
			break;
		case 4: // Password
			echo '<input name="'.$vars_variable.'" class="span12" type="password" value="'.$vars_val.'" />';
			break;
		case 5: // Fecha
			$fecha_def = $vars_val;
			$fecdi    = substr($fecha_def,8,2);
			$fecme    = substr($fecha_def,5,2);
			$fecan    = substr($fecha_def,0,4);
			$fecha_in = $fecdi.'/'.$fecme.'/'.$fecan;
			echo '<input type="text" id="dp-cmy'.$vconta.'" class="span2 datepicker-cmy" name="'.$vars_variable.'" value="'.$fecha_in.'">';
			break;
		case 6: // Texto libre
			echo '<textarea id="cleditor" name="'.$vars_variable.'">'.$vars_val.'</textarea>';
			break;
	}
	echo '</div></div>
	';
	$vconta++;
}
?>