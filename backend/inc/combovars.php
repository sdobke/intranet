<?PHP
$debugcv = 0;
$itemsxfila = 3;
$ixf = $itemsxfila;
$span = 12/$ixf;
$sqlcv = "(SELECT id, nombre, 'cbo' AS tipodato, orden, activo FROM combos WHERE tabla = ".$tipo.") UNION (SELECT id, nombre, 'var' AS tipodato, orden, activo FROM vars WHERE tabla = ".$tipo.") ORDER BY orden";
$rescv = fullQuery($sqlcv);
$concv = mysqli_num_rows($rescv);
if($concv > 0){
	$vconta = 0; // para diferentes variables de fechas (el calendario debe tener distinto id)
	$cixf = 0;
	while($rowcv = mysqli_fetch_array($rescv)){
		$taborden = '';
		$continuar = 1;
		if($tipoarchivo == 'alta' && $tipo == 2 && $rowcv['tipodato'] == 'var'){
			switch($rowcv['id']){
				case 9: // Fecha
				case 32: // Hora
					$continuar = 0;
					break;
			}
		}
		if($continuar == 1){
			if($debugcv == 1){echo '<br>Campo: '.txtcod($rowcv['nombre']);}
			$displaygo = 1;
			$display = '';
			if($rowcv['activo'] == 1){
				$taborden = ' tabindex="'.$rowcv['orden'].'" ';
			}
			//$abrir = 0;
			$resu = $cixf % $ixf;
			if($debugcv == 1){$display.='<br>Cixf: '.$cifx.' % ixf: '.$ixf.' = '.$resu;}
			if($cixf % $ixf == 0){
				if($debugcv == 1){$display.='<br>Abre';}
				$abrir = 1;
				$display.='
				<div class="row-fluid"><div class="control-group">';
			}
			$display.='<div class="span'.$span.'">
			';
			if($rowcv['tipodato'] == 'cbo'){ // si es combos
				include("combovars/combo.php");
			}else{ // si es vars
				include("combovars/vars.php");
			}
			$display.='</div> <!--cierra span-->';
	
			if($cixf % $ixf == 2){
				$display.='</div></div> <!--cierra control-groupy row-fluid-->
				';// control-groupy row-fluid
			}
			if($displaygo == 1){$cixf++;}
			if($displaygo == 1){echo $display;}
		} // Fin si continuar == 1
	}
	if ($tipodet == "jugadores"){
		$displaygo = 1;
		include_once ("inc/photo.php");
	}
	if($abrir == 1){
		if($debugcv == 1){echo '<br>Cierre | Cixf: '.$cifx.' % ixf: '.$ixf.' = '.$resu;}
		echo '</div></div> <!--cierra control-groupy row-fluid-->
		';// control-groupy row-fluid
	}
}
?>