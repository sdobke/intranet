<?PHP 
$qarch = 'reservas_funciones.php';
function diaSig($fecha){
	$dev = date('Y-m-d', strtotime($fecha .' +1 day'));
	$dia = strtolower(date("D", strtotime($dev)));
	switch ($dia){
		case 'sat':
			$dev = date('Y-m-d', strtotime($dev .' +2 day'));
			break;
		case 'sun':
			$dev = date('Y-m-d', strtotime($dev .' +1 day'));
			break;
	}
	return $dev;
}
function diaAnt($fecha){
	$dev = date('Y-m-d', strtotime($fecha .' -1 day'));
	$dia = strtolower(date("D", strtotime($dev)));
	switch ($dia){
		case 'sat':
			$dev = date('Y-m-d', strtotime($dev .' -1 day'));
			break;
		case 'sun':
			$dev = date('Y-m-d', strtotime($dev .' -2 day'));
			break;
	}
	return $dev;
}
function nomDia($fecha){
	$dia = strtolower(date("D", strtotime($fecha)));
	switch ($dia){
		case 'sun':
			$dev = 'domingo';
			break;
		case 'mon':
			$dev = 'lunes';
			break;
		case 'tue':
			$dev = 'martes';
			break;
		case 'wed':
			$dev = 'mi&eacute;rcoles';
			break;
		case 'thu':
			$dev = 'jueves';
			break;
		case 'fri':
			$dev = 'viernes';
			break;
		case 'sat':
			$dev = 's&aacute;bado';
			break;
	}
	return $dev;
}

function Salas1 ($hora,$horafin,$sala,$fecha){
	
	$tmp = '';
	$sql_horario = "SELECT horario, horarioh FROM intranet_reservas_horarios WHERE horario >= '".$hora."' AND horarioh <= '".$horafin."' order by 1";
	$res_horario = fullQuery($sql_horario,$qarch);
	while($row_horario = mysqli_fetch_array($res_horario)){
		$sql_salasocu = "SELECT id FROM intranet_reservas WHERE hini <= '".$row_horario[0]."' and hfin >= '".$row_horario[1]."' and sala=".$sala." and fecha= '".$fecha."'";
		$res_salasocu = fullQuery($sql_salasocu,$qarch);
		$row_salasocu = mysqli_fetch_array($res_salasocu);
		// echo $sql_salasocu.'<br>';
		$tmp = $tmp.$row_salasocu['id'];
	}
	return $tmp;
}

function Salas00 ($hora,$sala,$fecha,$cantidad,$duracion,$usacap,$repetir,$fecha_hasta){
	
	/* echo 'Hora '.$hora.'<br>';
	echo 'Sala '.$sala.'<br>';
	echo 'Fecha '.$fecha.'<br>';
	echo 'Cantidad '.$cantidad.'<br>';
	echo 'Duracion '.$duracion.'<br>';
	echo 'Usacap '.$usacap.'<br>'; */
	
	$sql_salas = "SELECT id,empleado,motivo FROM intranet_reservas WHERE hini <= '".$hora."' and hfin >= '".$hora."' and sala=".$sala." and fecha= '".$fecha."'";
	
	// echo $sql_salas.'<br>';
	
	$res_salas = fullQuery($sql_salas,$qarch);
	$row_salas = mysqli_fetch_array($res_salas);
	$tmp = '';	
	if (!empty($row_salas['id'])){ // Si está reservada
		$resemple = obtenerDato('nombre,apellido','empleados',$row_salas['empleado']);
		$resinter = obtenerDato('interno','empleados',$row_salas['empleado']);
		if($resinter != ''){
			$resemple.= ' (interno: '.$resinter.')';
		}
		if($row_salas['motivo'] != ''){
			$resemple.= '<br />Motivo: '.$row_salas['motivo'];
		}
		if($row_salas['empleado'] == $_SESSION['usrfrontend']){ // Si la reserva la realizó el mismo usuario
			$tmp = '<td><div class="tip" data-tip="Reservada por '.$resemple.'">
						<form method="post" name="'.str_replace(array(":","-"),'',$hora.$sala.$fecha).'">
							<input type="hidden" name="borrar" value="'.$row_salas['id'].'" />
							<input type="hidden" name="opcion" value="D" />
							<input type="hidden" name="repetir" value="'.$repetir.'" />
							<input type="hidden" name="fecha_hasta" value="'.$fecha_hasta.'" />
							<button type="submit" name="submit" value="Cancelar Reserva"><span class="icon icon35"></span><span class="labeled">Cancelar</span></button>
						</form>
					</div></td>';
		}else{
			$tmp = '<td><div class="tip" data-tip="Reservada por '.$resemple.'"><img src="img_new/alert_box.png" /></div></td>';
		}
	}else{
		$sql_capsalas = "SELECT capacidad FROM intranet_reservas_salas WHERE id=".$sala;
		$res_capsalas = fullQuery($sql_capsalas,$qarch);
		$row_capsalas = mysqli_fetch_array($res_capsalas);
		$capac_sala = ($usacap == 1) ? $row_capsalas['capacidad'] : 9999;
		if ($capac_sala >= $cantidad) {
			$cant_duracion = explode(':',$duracion);
			$hora_duracion = explode(':',$hora);
			$minutos = str_pad(($hora_duracion[1] + $cant_duracion[1]),2,0,STR_PAD_RIGHT);
	
			if ($hora_duracion[1] == '00')
				$dif = 1;
			else
				$dif = '';
			if ($minutos == '00' or $minutos == '59') {
				$minutosduracion = '59';
				$horaduracion = ($hora_duracion[0]+$cant_duracion[0]-$dif);
			}
			
			if ($minutos == '29' or $minutos == '30' or $minutos == '31') {
				$minutosduracion = '29';
				$horaduracion = ($hora_duracion[0]+$cant_duracion[0]);
			}
			$dura_fin = $horaduracion.':'.str_pad($minutosduracion,2,0,STR_PAD_RIGHT).':00';
			$valorfin = Salas1 ($hora,$dura_fin,$sala,$fecha);
			
			if (empty($valorfin) and substr(str_pad($dura_fin,8,"0",STR_PAD_LEFT),0,2) < 19) {
				$motivo = (isset($_REQUEST['motivo'])) ? $_REQUEST['motivo'] : '';
				$tmp = '<td>
							<form name="'.str_replace(array(":","-"),'',$hora.$dura_fin.$fecha).'" method="post">
								<input type="hidden" name="fecha" value="'.$fecha.'" />
								<input type="hidden" name="horaini" value="'.$hora.'" />
								<input type="hidden" name="horaf" value="'.$dura_fin.'" />
								<input type="hidden" name="sala" value="'.$sala.'" />
								<input type="hidden" name="opcion" value="R" />
								<input type="hidden" name="motivo" value="'.$motivo.'" />
								<input type="hidden" name="repetir" value="'.$repetir.'" />
								<input type="hidden" name="fecha_hasta" value="'.$fecha_hasta.'" />
								<button type="submit" name="submit" value="Reservar"><span class="icon icon33"></span><span class="labeled">Reservar</span></button>
							</form>
						</td>';
				
				//<td><a href="reserva.php?Fecha='.$fecha.'&Horaini='.$hora.'&Horaf='.$dura_fin.'&Sala='.$sala.'&Opcion=R&motivo='.$motivo.'">Reservar</a></td>';
			}else{
				$tmp = '<td><div class="tip" data-tip="No es suficiente el tiempo disponible."><img src="img_new/info_box.png" /></div></td>';
			}
		}else{
			$tmp = '<td><div class="tip" data-tip="La capacidad de la sala es insuficiente."><img src="img_new/info_box.png" /></div></td>';
		}
	}
	return $tmp;
}
function Salas30 ($hora,$sala,$fecha,$cantidad,$duracion,$usacap,$repetir,$fecha_hasta){
	
	/*  echo 'Hora '.$hora.'<br>';
	echo 'Sala '.$sala.'<br>';
	echo 'Fecha '.$fecha.'<br>';
	echo 'Cantidad '.$cantidad.'<br>';
	echo 'Duracion '.$duracion.'<br>';
	echo 'Usacap '.$usacap.'<br>'; */
	
	$sql_salas = "SELECT id,empleado,motivo FROM intranet_reservas WHERE hini <= '".$hora."' and hfin > '".$hora."' and sala=".$sala." and fecha= '".$fecha."'";
	
	// echo $sql_salas.'<br>';
	
	$res_salas = fullQuery($sql_salas,$qarch);
	$row_salas = mysqli_fetch_array($res_salas);
	$tmp = '';	
	if (!empty($row_salas['id'])){ // Si está reservada
		$resemple = obtenerDato('nombre,apellido','empleados',$row_salas['empleado']);
		$resinter = obtenerDato('interno','empleados',$row_salas['empleado']);
		if($resinter != ''){
			$resemple.= ' (interno: '.$resinter.')';
		}
		if($row_salas['motivo'] != ''){
			$resemple.= '<br />Motivo: '.$row_salas['motivo'];
		}
		if($row_salas['empleado'] == $_SESSION['usrfrontend']){ // Si la reserva la realizó el mismo usuario
			$tmp = '<td><div class="tip" data-tip="Reservada por '.$resemple.'">
						<form method="post" name="'.str_replace(array(":","-"),'',$hora.$sala.$fecha).'">
							<input type="hidden" name="borrar" value="'.$row_salas['id'].'" />
							<input type="hidden" name="opcion" value="D" />
							<button type="submit" name="submit" value="Cancelar Reserva"><span class="icon icon35"></span><span class="labeled">Cancelar</span></button>
						</form>
					</div></td>';
		}else{
			$tmp = '<td><div class="tip" data-tip="Reservada por '.$resemple.'"><img src="img_new/alert_box.png" /></div></td>';
		}
	}else{
		$sql_capsalas = "SELECT capacidad FROM intranet_reservas_salas WHERE id=".$sala;
		$res_capsalas = fullQuery($sql_capsalas,$qarch);
		$row_capsalas = mysqli_fetch_array($res_capsalas);
		$capac_sala = ($usacap == 1) ? $row_capsalas['capacidad'] : 9999;
		if ($capac_sala >= $cantidad) {
			$cant_duracion = explode(':',$duracion);
			$hora_duracion = explode(':',$hora);
			$minutos = str_pad(($hora_duracion[1] + $cant_duracion[1]),2,0,STR_PAD_RIGHT);
			
				if ($hora_duracion[1] == '00')
				$dif = 1;
			else
				$dif = '';
			if ($minutos == '00' or $minutos == '59') {
				$minutosduracion = '59';
				$horaduracion = ($hora_duracion[0]+$cant_duracion[0]-$dif);
			}
			
			if ($minutos == '29' or $minutos == '30' or $minutos == '31') {
				$minutosduracion = '29';
				$horaduracion = ($hora_duracion[0]+$cant_duracion[0]);
			}
			
			$dura_fin = $horaduracion.':'.str_pad($minutosduracion,2,0,STR_PAD_RIGHT).':00';
			$valorfin = Salas1($hora,$dura_fin,$sala,$fecha);
			
			if (empty($valorfin) and substr(str_pad($dura_fin,2,"0",STR_PAD_LEFT),0,2) < 19) {
				$motivo = (isset($_REQUEST['motivo'])) ? $_REQUEST['motivo'] : '';
				$tmp = '<td>
							<form name="'.str_replace(array(":","-"),'',$hora.$dura_fin.$fecha).'" method="post">
								<input type="hidden" name="fecha" value="'.$fecha.'" />
								<input type="hidden" name="horaini" value="'.$hora.'" />
								<input type="hidden" name="horaf" value="'.$dura_fin.'" />
								<input type="hidden" name="sala" value="'.$sala.'" />
								<input type="hidden" name="opcion" value="R" />
								<input type="hidden" name="motivo" value="'.$motivo.'" />
								<input type="hidden" name="repetir" value="'.$repetir.'" />
								<input type="hidden" name="fecha_hasta" value="'.$fecha_hasta.'" />
								<button type="submit" name="submit" value="Reservar"><span class="icon icon33"></span><span class="labeled">Reservar</span></button>
							</form>
						</td>';
				
				//<td><a href="reserva.php?Fecha='.$fecha.'&Horaini='.$hora.'&Horaf='.$dura_fin.'&Sala='.$sala.'&Opcion=R&motivo='.$motivo.'">Reservar</a></td>';
			}else{
				$tmp = '<td><div class="tip" data-tip="No es suficiente el tiempo disponible."><img src="img_new/info_box.png" /></div></td>';
			}
		}else{
			$tmp = '<td><div class="tip" data-tip="La capacidad de la sala es insuficiente."><img src="img_new/info_box.png" /></div></td>';
		}
	}
	return $tmp;
}
function LOVSalas ($numsala, $cantidad, $idsala){
	$tmp = '<form action="#" method="post">
				<select name="sala" id="sala" onchange="return sendFormanio(this.value);">
					<option value="0" >Seleccione una opci&oacute;n...</option>';
					
						$sql_a = "SELECT  id,concat(nombre,' - (Capacidad Max: ',capacidad,' personas)'), capacidad
						FROM intranet_reservas_salas";
						
						if (!empty($numsala))
							$sql_a = $sql_a.' where id not in('.$numsala.')';
						
						if (!empty($numsala) and $cantidad>=1)
							$sql_a = $sql_a.' and capacidad >= '.$cantidad; 
						
						if (empty($numsala) and $cantidad>=1)
							$sql_a = $sql_a.' where capacidad >= '.$cantidad; 
							
						$sql_a = $sql_a.' order by capacidad, nombre';
						$res_a = fullQuery($sql_a,$qarch);
                        while($row_a = mysqli_fetch_array($res_a)){
							$selected = "";
							if($bka = $row_a['id'] == $idsala)
							$selected = 'selected="selected"';
							
						$tmp = $tmp.'<option '.$selected.' value="'. $row_a[0].'">'. $row_a[1].'</option>';
						
						}
			$tmp = $tmp. '</select>
			</form>';
	return $tmp;
}
function LOVCantidad ($numsala, $cantidad){
	$sql_a = "SELECT  id,concat(nombre,' - (Capacidad Max: ',capacidad,' personas)'), capacidad
				FROM intranet_reservas_salas";
	if (!empty($numsala))
		$sql_a = $sql_a.' WHERE id NOT IN ('.$numsala.')';
	if (!empty($numsala) and $cantidad>=1)
		$sql_a = $sql_a.' AND capacidad >= '.$cantidad; 
	if (empty($numsala) and $cantidad>=1)
		$sql_a = $sql_a.' WHERE capacidad >= '.$cantidad; 
						
	$res_a = fullQuery($sql_a,$qarch);
	$tmp = mysqli_num_rows($res_a);
	
	return $tmp;
}
function Salasugerida ($numsala, $cantidad){
	$sql_a = "SELECT  id,concat(nombre,' - (Capacidad Max: ',capacidad,' personas)'), capacidad
				FROM intranet_reservas_salas";
						
	if (!empty($numsala))
		$sql_a = $sql_a.' WHERE id NOT in('.$numsala.')';
					
	if (!empty($numsala) and $cantidad>=1)
		$sql_a = $sql_a.' AND capacidad >= '.$cantidad; 
						
	if (empty($numsala) and $cantidad>=1)
		$sql_a = $sql_a.' WHERE capacidad >= '.$cantidad; 
						
	$res_a = fullQuery($sql_a,$qarch);
	$row_a = mysqli_fetch_array($res_a);
	$tmp = $row_a[0];
	
	return $tmp;
}
?>