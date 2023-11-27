<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

include_once("reservas_funciones.php");
$tipo = 57;
$item = getPost('item',0);
agrega_acceso($tipo);
$errno = 0;
$usacap = 0; // Si usa capacidad en las salas
$maxhora = 18;
$minhora = 8;
$tipodet = obtenerDato('nombre','tablas',$tipo);
include("backend/inc/leer_parametros.php");
$nombot = 'Crear ';
$opcion = isset($_POST['opcion']) ? $_POST['opcion'] : '';
$cantasis = ($usacap == 1) ? getPost("cantidad") : 1;
$motivo = (isset($_REQUEST['motivo'])) ? str_replace('"','&quot;',$_REQUEST['motivo']) : '';
if(isset($_POST['fechamov'])){
	$diaselect = substr($_POST['fechamov'],8,2);
	$messelect = substr($_POST['fechamov'],5,2);
	$anioselect = substr($_POST['fechamov'],0,4);
}else{
	$diaselect = (isset($_POST["dia"])) ? $_POST["dia"] : date('d');
	$messelect = (isset($_POST["mes"])) ? $_POST["mes"] : date('m');
	$anioselect = (isset($_POST["anio"])) ? $_POST["anio"] : date('Y');
}
$messelect = (strlen($messelect) == 1) ? '0'.$messelect : $messelect;
$diaselect = (strlen($diaselect) == 1) ? '0'.$diaselect : $diaselect;
$fechasel = $anioselect.'-'.$messelect.'-'.$diaselect;
if($fechasel == date("Y-m-d") && date("H")+1 >= $maxhora){
	$fechasel = diaSig($fechasel);
}
$diaselect = substr($fechasel,8,2);
$messelect = substr($fechasel,5,2);
$anioselect = substr($fechasel,0,4);
if(!isset($_SESSION['usrfrontend'])){
	$errno = 4;
}
if ( isset($_REQUEST["enviar"]) && $_REQUEST["enviar"] == 'Buscar' && $errno == 0) {
	if ($cantasis == 0) {
		$errno = 1;
	}
	if ($fechasel < date("Y-m-d")){
		$errno = 2;
	}
	if ($_POST["durhora"] == '0' && $_POST["durminuto"] == '00'){
		$errno = 6;
	}
}
// Fecha límite repeticiones
$diavselect = (isset($_POST["diav"])) ? $_POST["diav"] : date('d')+1;
$mesvselect = (isset($_POST["mesv"])) ? $_POST["mesv"] : date('m');
$aniovselect = (isset($_POST["aniov"])) ? $_POST["aniov"] : date('Y');
$fecha_hasta = $aniovselect.'-'.$mesvselect.'-'.$diavselect;
if(isset($_REQUEST['fecha_hasta'])){$fecha_hasta = $_REQUEST['fecha_hasta'];}
$diamax = substr($fecha_hasta,8,2);
$mesmax = substr($fecha_hasta,5,2);
$aniomax = substr($fecha_hasta,0,4);
$repetir = 0;
if(isset($_POST['repeti']) && $_POST['repeti'] > 0){
	$repetir = $_POST['repeti'];
}
if($opcion == 'R' && $errno == 0 && isset($_SESSION['usrfrontend'])){
	
	$repetir = $_REQUEST['repeticiones'];
	$sqlr = '0';
	$resid = $sqlr = nuevoID($tipodet);
	if($repetir > 0){
		switch ($repetir){
			case 1:
				$frec = "+1 day";
				break;
			case 2:
				$frec = "+7 day";
				break;
			case 3:
				$frec = "+15 day";
				break;
			case 4:
				$frec = "+1 month";
				break;
			case 5:
				$frec = "+2 month";
				break;
			case 6:
				$frec = "+3 month";
				break;
			case 7:
				$frec = "+4 month";
				break;
			case 8:
				$frec = "+6 month";
				break;
			case 9:
				$frec = "+12 month";
				break;			
		}
		$c_inicio = $c_mes = strtotime($fechasel);
		$c_fin    = strtotime($fecha_hasta);
	
		while($c_mes < $c_fin){
			$qfec2    = ", '".date("Y-m-d",$c_mes)."'";	
			$sql_inserta = "INSERT INTO intranet_reservas (FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES ('".$qfec2."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."'".$sqlr.")";
	$sql_res = fullQuery($sql_inserta);
			$c_mes = strtotime($frec, $c_mes);
			$id++;
		}		
	}else{
		$sql_inserta = "INSERT INTO intranet_reservas (ID,FECHA,HINI,HFIN,SALA,EMPLEADO,MOTIVO,REPETIDOR) VALUES (".$resid.",'".$_POST['fecha']."','".$_POST['horaini']."','".$_POST['horaf']."','".$_POST['sala']."','".$_SESSION['usrfrontend']."', '".$_POST['motivo']."',0)";
		$sql_res = fullQuery($sql_inserta);
		$errno = 3;	
	}
}
if($opcion == 'D' && isset($_SESSION['usrfrontend']) ){
	$borrar = getPost("borrar");
	if($borrar > 0){
		$sql_borra = "DELETE FROM intranet_reservas WHERE id = ".$borrar." AND empleado = ".$_SESSION['usrfrontend'];
		$res_borra = fullQuery($sql_borra);
		$errno = 5;
	}
}
// Definiciones de género y número
$ar = 'la ';
$ar2 = 'a';
$ar3 = 'de la';
$vartitulo = 'T&iacute;tulo';
$vartexto = 'Descripci&oacute;n ';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?PHP echo $cliente;?> Intranet | Reserva de salas</title>
<?PHP include ("head.php");?>
<link href="/css/secciones.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/css/tipr.css">
<link rel="stylesheet" type="text/css" href="/css/reservas.css">
<script type="text/javascript">
function enviarForm(){
	document.getElementById("salas").submit();
}
function cambiaRepeticiones(valor){
	if(valor == 0){
		document.getElementById("fecha_finrep").style.display = "none";
		enviarForm();
	}else{
		document.getElementById("fecha_finrep").style.display = "block";
		document.getElementById('repeti').value = valor;
		enviarForm();
	}
}
</script>
</head>
<body>
	<div id="middle">
		<div class="middle_inner">
			<?PHP include("header.php");?>
            <div class="container">
				<div class="col_ppal left"  >
					<!-- BEGIN BODY -->
					<div class="col_ppal left">
							<div class="hd-seccion"><?PHP echo utf8_encode(ucwords('Reserva de Salas'));?></div>
							<?PHP
							if($errno > 0){
								switch ($errno){
									case 1:
										$errtx = '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>Debe ingresar la cantidad de asistentes para realizar la reserva.</div>';
										break;
									case 2:
										$errtx = '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>La fecha de la reserva tiene que ser a futuro.</div>';
										break;
									case 3:
										$errtx = '<div class="success_box left mb15">La reserva se realiz&oacute; con &eacute;xito.</div>';
										break;
									case 4:
										$errtx = '<div class="info_box left mb15"><strong>ATENCI&Oacute;N: </strong>Para realizar una reserva ten&eacute;s que ingresar con tu usuario.</div>';
										break;
									case 5:
										$errtx = '<div class="success_box left mb15">La reserva se cancel&oacute; con &eacute;xito.</div>';
										break;
									case 6:
										$errtx = '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>No se seleccion&oacute; duraci&oacute;n para la reuni&oacute;n.</div>';
										break;
								}
								echo $errtx;
							}
							?>
                            <?PHP if(isset($_SESSION['usrfrontend'])){ ?>
                                <div class="formularios left mb15">
                                    <div class="left w100 mb5 c444444"><strong>Fecha</strong></div>
                                    <form name="salas" id="salas" action="reserva.php" method="post">
                                        <div class="left w100 mb15 inputcortos">
                                            <select name="dia" id="select">
                                                <?PHP
                                                $cont = 1;
                                                while($cont <= 31){
                                                    if ($cont == $diaselect) $sel = ' selected="selected"'; else $sel = ' ';
                                                        echo '<option value="'.$cont.'" '.$sel.'>'.$cont.'</option>';
                                                    $cont++;
                                                }
                                                ?>
                                            </select>
                                            <select name="mes" id="select2">
                                                <option value="01" <?PHP echo optSel('01', $messelect);?>>Ene</option>
                                                <option value="02" <?PHP echo optSel('02', $messelect);?>>Feb</option>
                                                <option value="03" <?PHP echo optSel('03', $messelect);?>>Mar</option>
                                                <option value="04" <?PHP echo optSel('04', $messelect);?>>Abr</option>
                                                <option value="05" <?PHP echo optSel('05', $messelect);?>>May</option>
                                                <option value="06" <?PHP echo optSel('06', $messelect);?>>Jun</option>
                                                <option value="07" <?PHP echo optSel('07', $messelect);?>>Jul</option>
                                                <option value="08" <?PHP echo optSel('08', $messelect);?>>Ago</option>
                                                <option value="09" <?PHP echo optSel('09', $messelect);?>>Sep</option>
                                                <option value="10" <?PHP echo optSel('10', $messelect);?>>Oct</option>
                                                <option value="11" <?PHP echo optSel('11', $messelect);?>>Nov</option>
                                                <option value="12" <?PHP echo optSel('12', $messelect);?>>Dic</option>
                                            </select>
                                            <select name="anio" id="select3">
                                                <?PHP
                                                echo '<option value="'.$anioselect.'" '.optSel($anioselect,$anomax).'>'.$anioselect.'</option>';
                                                $aniosig = $anioselect + 1;
                                                if($anomax == $aniosig){
                                                    echo '<option value="'.$anomax.'" selected="selected">'.$anomax.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>          
                                        <?PHP if($usacap == 1){?>
                                            <div class="left w100 mb5 c444444"><strong>Cantidad de asistentes</strong></div>
                                            <div class="left w100 mb15">
                                                <input type="text" name="cantidad" id="textfield" value="<?php echo $cantasis;?>" style="width:40px" />
                                            </div>
                                        <?PHP } ?>
                                        <div class="left w100 mb5 c444444"><strong>Motivo</strong></div>
                                        <div class="left w100 mb15">
                                        <input name="motivo" id="motivo" type="text" value="<?PHP echo $motivo;?>" />
                                        </div>
                                        <div class="left w100 mb5 c444444"><strong>Duraci&oacute;n</strong></div>
                                        <div class="left w100 mb15 inputcortos">
                                            <?PHP
                                            $durhora = (isset($_POST["durhora"])) ? $_POST["durhora"] : '0';
                                            $durminuto = (isset($_POST["durminuto"])) ? $_POST["durminuto"] : '0';
											$cambhor = '';
											if(isset($_REQUEST["enviar"]) && $_REQUEST["enviar"] == 'Buscar' && $errno == 0){
												$cambhor = 'onchange="enviarForm()"';
											}
                                            ?>
                                            <select name="durhora" id="select4" <?PHP echo $cambhor;?>>
                                                <?PHP
                                                $canthoras = 0;
                                                while($canthoras < 10){
                                                    ?>
                                                    <option value="<?PHP echo $canthoras;?>" <?PHP if ($canthoras == $durhora) echo ' selected="selected"';?>><?PHP echo $canthoras;?></option>													
													<?PHP $canthoras++;?>
                                                <?PHP } ?>
                                            </select> Horas
                                            <select name="durminuto" id="select5" <?PHP echo $cambhor;?>>
                                                <option value="00" <?PHP if ('00' == $durminuto) echo ' selected="selected"';?>>00</option>
                                                <option value="30" <?PHP if ('30' == $durminuto) echo ' selected="selected"';?>>30</option>
                                            </select> Minutos
                                        </div>
                                        <?PHP if (isset($_REQUEST["enviar"]) && $_REQUEST["enviar"] == 'Buscar' && $errno == 0) {?>
                                            <div class="left w100 mb5 c444444"><strong>Repeticiones</strong></div>
                                            <div class="left w100 mb15 inputcortos">
                                                <select name="repeticiones" id="repeticiones" class="txtfield" style="width:150px" onchange="javascript:cambiaRepeticiones(this.value);" >
                                                    <option value="0" <?PHP echo optSel(0,$repetir);?>>Sin repeticiones</option>
                                                    <option value="1" <?PHP echo optSel(1,$repetir);?>>Diario</option>
                                                    <option value="2" <?PHP echo optSel(2,$repetir);?>>Semanal</option>
                                                    <option value="3" <?PHP echo optSel(3,$repetir);?>>Quincenal</option>
                                                    <option value="4" <?PHP echo optSel(4,$repetir);?>>Mensual</option>
                                                    <option value="5" <?PHP echo optSel(5,$repetir);?>>Bimestral</option>
                                                    <option value="6" <?PHP echo optSel(6,$repetir);?>>Trimestral</option>
                                                    <option value="7" <?PHP echo optSel(7,$repetir);?>>Cuatrimestral</option>
                                                    <option value="8" <?PHP echo optSel(8,$repetir);?>>Semestral</option>
                                                    <option value="9" <?PHP echo optSel(9,$repetir);?>>Anual</option>
                                                </select>
                                            </div>
                                            <?PHP $repfec = ($repetir > 0) ? 'block' : 'none';?>
                                            <div style="display:<?PHP echo $repfec;?>" id="fecha_finrep">
                                                <div class="left w100 mb5 c444444"><strong>Repetir hasta</strong></div>
                                                <div class="left w100 mb15 inputcortos">
                                                    <select name="diav" id="select" <?PHP echo $cambhor;?>>
                                                        <?PHP
                                                        $cont = 1;
                                                        while($cont <= 31){
                                                            if ($cont == $diaselect) $sel = ' selected="selected"'; else $sel = ' ';
                                                                echo '<option value="'.$cont.'" '.$sel.'>'.$cont.'</option>';
                                                            $cont++;
                                                        }
                                                        ?>
                                                    </select>
                                                    <select name="mesv" id="select2" <?PHP echo $cambhor;?>>
                                                    <option value="01" <?PHP echo optSel('01', $messelect);?>>Ene</option>
                                                    <option value="02" <?PHP echo optSel('02', $messelect);?>>Feb</option>
                                                    <option value="03" <?PHP echo optSel('03', $messelect);?>>Mar</option>
                                                    <option value="04" <?PHP echo optSel('04', $messelect);?>>Abr</option>
                                                    <option value="05" <?PHP echo optSel('05', $messelect);?>>May</option>
                                                    <option value="06" <?PHP echo optSel('06', $messelect);?>>Jun</option>
                                                    <option value="07" <?PHP echo optSel('07', $messelect);?>>Jul</option>
                                                    <option value="08" <?PHP echo optSel('08', $messelect);?>>Ago</option>
                                                    <option value="09" <?PHP echo optSel('09', $messelect);?>>Sep</option>
                                                    <option value="10" <?PHP echo optSel('10', $messelect);?>>Oct</option>
                                                    <option value="11" <?PHP echo optSel('11', $messelect);?>>Nov</option>
                                                    <option value="12" <?PHP echo optSel('12', $messelect);?>>Dic</option>
                                                    </select>
                                                    <select name="aniov" id="select3" <?PHP echo $cambhor;?>>
                                                        <?PHP
                                                        echo '<option value="'.$anioselect.'" '.optSel($anioselect,$anomax).'>'.$anioselect.'</option>';
                                                        $aniosig = $anioselect + 1;
                                                        if($anomax == $aniosig){
                                                            echo '<option value="'.$anomax.'" selected="selected">'.$anomax.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                         <?PHP } ?>
                                        <input type="hidden" name="enviar" value="Buscar" />
                                        <input type="hidden" name="repeti" id="repeti" value="<?PHP echo $repetir;?>" />
                                        <button type="enviar" name="enviar" value="Buscar"><span class="icon icon198"></span><span class="labeled">Buscar</span></button>
                                    </form>
                                    <br /><br />
									<?php 
                                    if (isset($_REQUEST["enviar"]) && $_REQUEST["enviar"] == 'Buscar' && $errno == 0) {
										$fechant = diaAnt($fechasel);
										$fechsig = diaSig($fechasel);
										$horasig = date("H")+1;
										?>
										<?PHP if($fechant > date("Y-m-d") || ($fechant == date("Y-m-d") && $horasig <= $maxhora ) ){?>
											<div class="left">
                                            	<form name="ante" method="post">
                                                	<input name="fechamov" type="hidden" value="<?PHP echo $fechant;?>" />
                                                    <input name="cantidad" type="hidden" value="<?PHP echo $cantasis;?>" />
                                                    <input name="durhora" type="hidden" value="<?PHP echo $durhora;?>" />
                                                    <input name="durminuto" type="hidden" value="<?PHP echo $durminuto;?>" />
                                                    <input name="motivo" type="hidden" value="<?PHP echo $motivo;?>" />
                                                    <input name="fecha_hasta" type="hidden" value="<?PHP echo $fecha_hasta;?>" />
                                                    <input name="repetir" type="hidden" value="<?PHP echo $repetir;?>" />
                                                    <button type="enviar" name="enviar" value="Buscar">
                                                    	<span class="icon icon8"></span><span class="labeled">D&iacute;a anterior</span>
													</button>
												</form>
                                            </div>
										<?PHP } ?>
										<div class="right">
                                            <form name="ante" method="post">
                                               	<input name="fechamov" type="hidden" value="<?PHP echo $fechsig;?>" />
												<input name="cantidad" type="hidden" value="<?PHP echo $cantasis;?>" />
												<input name="durhora" type="hidden" value="<?PHP echo $durhora;?>" />
												<input name="durminuto" type="hidden" value="<?PHP echo $durminuto;?>" />
                                                <input name="motivo" type="hidden" value="<?PHP echo $motivo;?>" />
												<input name="fecha_hasta" type="hidden" value="<?PHP echo $fecha_hasta;?>" />
												<input name="repetir" type="hidden" value="<?PHP echo $repetir;?>" />
												<button type="enviar" name="enviar" value="Buscar">
                                                	<span class="icon icon9"></span><span class="labeled">D&iacute;a siguiente</span>
												</button>
											</form>
										</div>
                                        <?PHP echo '<div class="right" style="margin-right:90px"><strong>'.ucwords(nomDia($fechasel)).' '.fechadet($fechasel,'largo').'</strong></div>';?>
										<br /><br /><br />
                                        Presione el bot&oacute;n "Reservar" en el horario que quiere iniciar la reuni&oacute;n.<br /><br />
										<?PHP
                                        if ($cantasis >= 1) {
                                            $fecha = $anioselect.'-'.$messelect.'-'.$diaselect;
                                            $cantidad = $cantasis;
                                            $parametro_duracion = $durhora.':'.$durminuto;
                                            echo '<table width="100%" class="reservas">
                                                <tr class="tabtit">
                                                  <td width="50">Horario</td>';
                                                  $sql_sla = "SELECT * FROM intranet_reservas_salas ORDER BY nombre";
                                                  $res_sla = fullQuery($sql_sla);
                                                  while($row_sla = mysqli_fetch_array($res_sla)){
                                                      echo '<td width="110">'.$row_sla['nombre'].'<br />'.$row_sla['capacidad'].' personas</td>';
                                                  }
                                                echo '</tr>';
												$horain = ($fechasel == date("Y-m-d")) ? date("H")+1 : $minhora;
                                                for ($in = $horain; $in <= $maxhora; $in++) {
                                                    echo '<tr>
                                                            <td>'.$in.':00</td>';
                                                            $sql_sla2 = "SELECT * FROM intranet_reservas_salas ORDER BY nombre";
                                                            $res_sla2 = fullQuery($sql_sla2);
                                                            while($row_sla2 = mysqli_fetch_array($res_sla2)){
                                                                echo Salas00($in.':00:00',$row_sla2['id'],$fecha,$cantidad,$parametro_duracion,$usacap,$repetir,$fecha_hasta);
                                                            }
                                                    echo '</tr>';
                                                          
                                                    echo '<tr>
                                                            <td>'.$in.':30</td>';
                                                            $sql_sla3 = "SELECT * FROM intranet_reservas_salas ORDER BY nombre";
                                                            $res_sla3 = fullQuery($sql_sla3);
                                                            while($row_sla3 = mysqli_fetch_array($res_sla3)){
                                                                echo Salas30($in.':29:00',$row_sla3['id'],$fecha,$cantidad,$parametro_duracion,$usacap,$repetir,$fecha_hasta);
                                                            }
                                                    echo '</tr>';
                                                }
                                            echo '</table>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?PHP }?>
						</div>
					<div class="clr"></div>
					<!-- END BODY -->
					
				</div>
				<?php include("col_der.php"); ?>
			</div>
			<div class="clr"></div>
		</div>
	</div>
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="/js/tipr.min.js"></script>
<script>
$(document).ready(function() {
     $('.tip').tipr();
});
</script>
</body>
</html>