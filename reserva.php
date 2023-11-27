<?PHP
setlocale(LC_ALL,"es_AR");
$qf = 'reserva.php';
include "cnfg/config.php";
include "inc/funciones.php";

include_once("reservas_funciones.php");
$modo = 1; // Si el modo es 0 muestra todas las salas juntas, si es 1 muestra un selector y una sola sala.
$tipo = 57;
$item = getPost('item',0);
agrega_acceso($tipo);
$errno = 0;
$usacap = 0; // Si usa capacidad en las salas
$maxhora = 18;
$minhora = 8;
$tipodet = obtenerDato('nombre','tablas',$tipo);
$msg = '';
include("backend/inc/leer_parametros.php");
$nombot = 'Crear ';
include_once("reserva_vars.php");
// echo 'Repetir '.$repetir.'<br>';
include_once("reserva_post.php");
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
function tipoCancela(valor){
	var formu = valor.id;
	var r = confirm("¿Eliminar la serie completa?");
	var nomcpo = formu+'opcion';
	var opcion = document.getElementById(nomcpo).value;
	if (r == true) { // Si elimina la serie
		document.getElementById(nomcpo).value = 'S';
	} else { // Si elimina solamente uno
		document.getElementById(nomcpo).value = 'D';
	}
	document.getElementById(formu).submit();
}
/*
    var x = document.forms.namedItem("myCarForm").innerHTML;
    document.getElementById("demo").innerHTML = x;
*/
/*
function cambiaMes(valor,envia){
	var dest = 'dia';
	if(valor=='mesv'){dest = 'diav';}
	var e = document.getElementById(valor);
	var mes = e.options[e.selectedIndex].value;
	var x = document.getElementById(dest);
	var option = document.createElement("option");
	switch(mes){
		case '04':
		case '06':
		case '09':
		case '11':
			dia = 30;
			break;
		case '01':
		case '03':
		case '05':
		case '07':
		case '08':
		case '10':
		case '12':
			dia = 31;
			break;
		case '02':
			dia = 29;
			break;
	}
	if(dia == 30){
		option.text = "30";
		x.add(option);
	}
	if(dia == 31){
		option.text = "30";
		x.add(option);
		option.text = "31";
		x.add(option);
	}
	if(envia == 1){
		enviarForm();
	}
}
*/
</script>
</head>
<body>
	<div id="middle">
		<div class="middle_inner"><?PHP include("header.php");?>
			<?php include_once 'menu.php'; ?>
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
									case 7:
										$errtx = '<div class="success_box left mb15">La serie se cancel&oacute; con &eacute;xito.</div>';
										break;
								}
								echo $errtx;
							}
							if($msg != ''){
								echo $msg;
							}
							?>
                            <?PHP if(isset($_SESSION['usrfrontend'])){
								$cambhor = '';
								$cambhor = 'onchange="enviarForm()"';
								//$cambmes = "onchange='cambiaMes(&quot;mes&quot;,0)'";
								if(isset($_REQUEST["enviar"]) && $_REQUEST["enviar"] == 'Buscar' && $errno == 0){
									$cambhor = 'onchange="enviarForm()"';
									//$cambmes = "onchange='cambiaMes(&quot;mes&quot;,1)'";
								}
								?>
                                <div class="formularios left mb15">
                                    <?PHP 
									$form_dest = 'reserva';
									include_once("reserva_form.php");?>
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
                                                    <input name="repeti" type="hidden" value="<?PHP echo $repetir;?>" />
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
												<input name="repeti" type="hidden" value="<?PHP echo $repetir;?>" />
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
                                            $cantidad = $cantasis;
                                            $parametro_duracion = $durhora.':'.$durminuto;
                                            echo '<table width="100%" class="reservas">
                                                <tr class="tabtit">
                                                  <td width="50">Horario</td>';
                                                  if($modo == 0){
													  $sql_sla = "SELECT * FROM intranet_reservas_salas ORDER BY nombre";
													  $res_sla = fullQuery($sql_sla,$qf);
													  while($row_sla = mysqli_fetch_array($res_sla)){
														  echo '<td width="110">'.$row_sla['nombre'].'<br />'.$row_sla['capacidad'].' personas</td>';
													  }
												  }else{
													  $sql_sla = "SELECT * FROM intranet_reservas_salas WHERE id = ".$sala;
													  $res_sla = fullQuery($sql_sla,$qf);
													  $row_sla = mysqli_fetch_assoc($res_sla);
													  echo '<td width="110">'.$row_sla['nombre'].'<br />'.$row_sla['capacidad'].' personas</td>';
												  }
                                                echo '</tr>';
												$horain = ($fechasel == date("Y-m-d")) ? date("H")+1 : $minhora;
                                                for ($in = $horain; $in <= $maxhora; $in++) {
                                                    echo '<tr>
                                                            <td>'.$in.':00</td>';
                                                            if($modo == 0){
																$sql_sla2 = "SELECT * FROM intranet_reservas_salas ORDER BY nombre";
																$res_sla2 = fullQuery($sql_sla2,$qf);
																while($row_sla2 = mysqli_fetch_array($res_sla2)){
																	echo Salas00($in.':00:00',$row_sla2['id'],$fecha,$cantidad,$parametro_duracion,$usacap,$repetir,$fecha_hasta);
																}
															}else{
																echo Salas00($in.':00:00',$sala,$fecha,$cantidad,$parametro_duracion,$usacap,$repetir,$fecha_hasta);
															}
                                                    echo '</tr>';
                                                          
                                                    echo '<tr>
                                                            <td>'.$in.':30</td>';
                                                            if($modo == 0){
																$sql_sla3 = "SELECT * FROM intranet_reservas_salas ORDER BY nombre";
																$res_sla3 = fullQuery($sql_sla3,$qf);
																while($row_sla3 = mysqli_fetch_array($res_sla3)){
																	echo Salas30($in.':29:00',$row_sla3['id'],$fecha,$cantidad,$parametro_duracion,$usacap,$repetir,$fecha_hasta);
                                                            	}
															}else{
																echo Salas30($in.':29:00',$sala,$fecha,$cantidad,$parametro_duracion,$usacap,$repetir,$fecha_hasta);
															}
                                                    echo '</tr>';
                                                }
                                            echo '<input type="hidden" name="repeti" id="repeti" value="'. $repetir.'" /></table>';
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