<?PHP
if($usafecha != 0 || $usahora != 0){
	if($usafecha != 0){
		if(!isset($varfecha)){$varfecha = date("Y-m-d");}
		$fecha_def = $varfecha;
		$fecdi    = substr($fecha_def,8,2);
		$fecme    = substr($fecha_def,5,2);
		$fecan    = substr($fecha_def,0,4);
		$fecha_in = $fecdi.'/'.$fecme.'/'.$fecan;
		if($usafecha == 1 || $usafecha == 2){
			$titufecha = 'Fecha';
			if($usafecha == 2){
				$titufecha = 'Vencimiento';
			}
			echo '<div class="control-group">
					<label class="control-label" for="dp-cmy">'.$titufecha.'</label>
					<div class="controls">
						<input type="text" id="dp-cmy" class="span2 datepicker-cmy" name="fecha" value="'.$fecha_in.'">
					</div>
				  </div>';
		}else{
			echo '<input name="fecha" id="fecha" value="'.$fecha_in.'" type="hidden" />';
		}
	}
	if($usahora == 1){
		echo '<div class="control-group">
					<label class="control-label" for="hora">Hora</label>
					<div class="controls">
						<input id="timepicker-basic" type="text" class="span2" name="hora" value="'.substr($varhora,0,5).'">
			</div>
			</div>';
	}
}
?>