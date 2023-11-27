<?PHP
if($opcion == 'R' && $errno == 0 && isset($_SESSION['usrfrontend'])){
	$repetir = $_POST['repeti'];
	$sqlr = '0';
	$resid = $sqlr = nuevoID($tipodet);
	if($repetir > 0){ // Si hay repeticiones
		switch ($repetir){
			case 1:
				$frec = "+1 day";
				$frec1 = 1;
				$frec2 = 1;
				break;
			case 2:
				$frec = "+7 day";
				$frec1 = 7;
				$frec2 = 7;
				break;
			case 3:
				$frec = "+14 day";
				$frec1 = 14;
				$frec2 = 14;
				break;
			case 4:
				$frec = "+1 month";
				$frec1 = 1;
				$frec2 = 28;
				break;
			case 5:
				$frec = "+2 month";
				$frec1 = 2;
				$frec2 = 56;
				// $frec2 = 63;
				break;
			case 6:
				$frec = "+3 month";
				$frec1 = 3;
				$frec2 = 84;
				// $frec2 = 91;
				break;
			case 7:
				$frec = "+4 month";
				$frec1 = 4;
				$frec2 = 112;
				// $frec2 = 119;
				break;
			case 8:
				$frec = "+6 month";
				$frec1 = 6;
				$frec2 = 168;
				// $frec2 = 175;
				break;
			case 9:
				$frec = "+12 month";
				$frec1 = 12;
				$frec2 = 336;
				// $frec2 = 364;
				break;			
		}
		$fecha_inicio = $_POST['fecha'];
		$sql_inserta = "INSERT INTO intranet_reservas (ID,FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES (".$resid.",'".$fecha_inicio."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."',".$resid.")";  
		$sql_res = fullQuery($sql_inserta,$qf);
		$msg.= '<div class="success_box left mb15">Se confirm&oacute; la reserva para la fecha: '.substr($fecha_inicio,8,2).'/'.substr($fecha_inicio,5,2).'/'.substr($fecha_inicio,0,4).'.</div>';
		//$errno = 3;
		if($repetir >= 2){ // Si la repetición no es diaria
			$f_proxima = fechaInserta($fecha_inicio,$repetir,$frec2,$fecha_hasta);
			$salaestado = Salascontrola($_POST['horaini'],$_POST['horaf'],$_POST['sala'],$f_proxima);
			if (!empty($salaestado)) {
				$estado = ' - OCUPADA (NO INSERTO RESERVA)';
				$nestado = 1;
				$msg.= '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>No se confirm&oacute; la reserva para la fecha: '.substr($f_proxima,8,2).'/'.substr($f_proxima,5,2).'/'.substr($f_proxima,0,4).' porque exist&iacute;a una reserva previa.</div>';
			}else{
				$nestado = 0;
				$estado = ' - DESOCUPADA (INSERTO RESERVA)';
			}
			if ($nestado == 0) {
				$sql_inserta = "INSERT INTO intranet_reservas (FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES ('".$f_proxima."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."',".$resid.")"; 
				$sql_res = fullQuery($sql_inserta,$qf);
				$msg.= '<div class="success_box left mb15">Se confirm&oacute; la reserva para la fecha: '.substr($f_proxima,8,2).'/'.substr($f_proxima,5,2).'/'.substr($f_proxima,0,4).'.</div>';
				//$errno = 3;
			}
			$f_icompa = explode('-',$f_proxima);
			$f_hcompa = explode('-',$fecha_hasta);	
			$f_proxima_final = $fecha_inicio.' - DESOCUPADA (INSERTO RESERVA)<br>'.$f_proxima.$estado;
			while(mktime(0, 0, 0, $f_hcompa[1], $f_hcompa[2], $f_hcompa[0]) >= mktime(0, 0, 0, $f_icompa[1], $f_icompa[2], $f_icompa[0])) {
				$f_proxima1 = fechaInserta(date('Y-m-d',mktime(0, 0, 0, $f_icompa[1], $f_icompa[2], $f_icompa[0])),$repetir,$frec2,$fecha_hasta);
				$f_icompa = explode('-',$f_proxima1);
				$salaestado = Salascontrola($_POST['horaini'],$_POST['horaf'],$_POST['sala'],$f_proxima1);
				if (!empty($salaestado)) {
					$estado = ' - OCUPADA (NO INSERTO RESERVA)';
					$nestado = 1;
					$msg.= '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>No se confirm&oacute; la reserva para la fecha: '.substr($f_proxima1,8,2).'/'.substr($f_proxima1,5,2).'/'.substr($f_proxima1,0,4).' porque exist&iacute;a una reserva previa.</div>';				}else{
					$nestado = 0;
					//$estado = ' - DESOCUPADA (INSERTO RESERVA)';
				}
				if (mktime(0, 0, 0, $f_hcompa[1], $f_hcompa[2], $f_hcompa[0]) >= mktime(0, 0, 0, $f_icompa[1], $f_icompa[2], $f_icompa[0])) {
					$f_proxima_final = $f_proxima_final.'<br>'.$f_proxima1.$estado;
					if ($nestado == 0) {
						$sql_inserta = "INSERT INTO intranet_reservas (FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES ('".$f_proxima1."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."',".$resid.")"; 
						$sql_res = fullQuery($sql_inserta,$qf);
						$msg.= '<div class="success_box left mb15">Se confirm&oacute; la reserva para la fecha: '.substr($f_proxima1,8,2).'/'.substr($f_proxima1,5,2).'/'.substr($f_proxima1,0,4).'.</div>';
						//$errno = 3;
					}
				}
			}
			//echo $f_proxima_final;
		}else{ // Si la repetición es diaria
			$f_icompa2 = explode('-',$_POST['fecha']);
			$f_fcompa2 = explode('-',$_POST['fecha_hasta']);
			$cantdias = (mktime(0, 0, 0, $f_fcompa2[1], $f_fcompa2[2], $f_fcompa2[0]) - mktime(0, 0, 0, $f_icompa2[1], $f_icompa2[2], $f_icompa2[0]))/86400;
			for ($i = 1; $i <= $cantdias; $i++) {
				$fechapr = date('Y-m-d',mktime(0, 0, 0, $f_icompa2[1], $f_icompa2[2] + $i, $f_icompa2[0]));
				$salaestado = Salascontrola($_POST['horaini'],$_POST['horaf'],$_POST['sala'],$fechapr);
				if (!empty($salaestado)) {
					$msg.= '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>No se confirm&oacute; la reserva para la fecha: '.substr($fechapr,8,2).'/'.substr($fechapr,5,2).'/'.substr($fechapr,0,4).' porque exist&iacute;a una reserva previa.</div>';
					$estado = ' - OCUPADA (NO INSERTO RESERVA)';
					$nestado = 1;
				}else{
					$nestado = 0;
					//$estado = ' - DESOCUPADA (INSERTO RESERVA)';
				}
				if (strtolower(date("D", strtotime($fechapr))) != 'sat' and strtolower(date("D", strtotime($fechapr))) != 'sun') {
					if ($nestado == 0) {
						$sql_inserta = "INSERT INTO intranet_reservas (FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES ('".$fechapr."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."',".$resid.")"; 
						$sql_res = fullQuery($sql_inserta,$qf);
						$msg.= '<div class="success_box left mb15">Se confirm&oacute; la reserva para la fecha: '.substr($fechapr,8,2).'/'.substr($fechapr,5,2).'/'.substr($fechapr,0,4).'.</div>';
						//$errno = 3;
					}
				}
			}
		}
	}else{ // Si es única
		$sql_inserta = "INSERT INTO intranet_reservas (ID,FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES (".$resid.",'".$_POST['fecha']."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."',0)";  
		$sql_res = fullQuery($sql_inserta,$qf);
		$msg.= '<div class="success_box left mb15">fSe confirm&oacute; la reserva para la fecha: '.substr($_POST['fecha'],8,2).'/'.substr($_POST['fecha'],5,2).'/'.substr($_POST['fecha'],0,4).'.</div>';
		//$errno = 3;
		$nestado = 0;
	}
}
if($opcion == 'D' && isset($_SESSION['usrfrontend']) ){
	$borrar = getPost("borrar");
	if($borrar > 0){
		$sql_borra = "DELETE FROM intranet_reservas WHERE id = ".$borrar." AND empleado = ".$_SESSION['usrfrontend'];
		$res_borra = fullQuery($sql_borra,$qf);
		$errno = 5;
	}
}
if($opcion == 'S' && isset($_SESSION['usrfrontend']) ){
	$borrar = getPost("borrar");
	if($borrar > 0){
		$sql_borra = "DELETE FROM intranet_reservas WHERE repetidor = ".$_POST['idserie']." AND empleado = ".$_SESSION['usrfrontend']." AND fecha >= '".$_POST['fechainicio']."'";
		$res_borra = fullQuery($sql_borra,$qf);
		$errno = 7;
	}
}
?>