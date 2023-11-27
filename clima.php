<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$tipo = 32;
agrega_acceso($tipo);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?PHP echo $cliente;?> Intranet | Home</title>
<?PHP include ("head.php");?>
</head>
<body onload="show5()">    <?PHP include ("header.php");?>
		<div id="main-wrapper">
			<?PHP include ("menu.php");?>
			<div id="content-wrapper">
                <!--[if lt IE 7]>
                    <table><tr valign="top">
                    	<td>
                <![endif]-->
				<?PHP include ("col_izq.php");?>
                <!--[if lt IE 7]>
	            	</td>
                    <td>
				<![endif]-->
				<div id="right-wrapper">
                    <!--[if lt IE 7]>
                        <table><tr valign="top"><td colspan="2">
                    <![endif]-->
					<?PHP include ("top.php");?>
                    <!--[if lt IE 7]>
                        </td></tr>
                        <tr valign="top"><td>
                    <![endif]-->
					<div id="main-content">
					  <div id="novedades">
						<div id="novedades-header"><img src="img/titulos/clima.gif" alt="Clima" /></div>
                        <div class="bk-base">
                        
                        <?PHP
                          require_once('inc/clima/google_weather_api.php');
						$weather = new weather();
						$weather->location = 'Buenos Aires';
						if (!empty($_GET['loc'])) {
							$weather->location = $_GET['loc'];
						}
						function traduceDia2($dia){
							$orig = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
							$dest = array("Lun","Mar","Mie","Jue","Vie","Sab","Dom");
							$dia  = str_replace($orig, $dest, $dia);
							$orig = array("lun","mar","mie","jue","vie","s�b","dom");
							$dest = array("Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado","Domingo");
							return str_replace($orig, $dest, $dia);
						}
						$weather->get();
						//echo ucwords($weather->location).': ';
						$anoclima = substr($weather->forecast->current_date_time['data'],0,4);
						$mesclima = substr($weather->forecast->current_date_time['data'],5,2);
						$diaclima = substr($weather->forecast->current_date_time['data'],8,2);
						$horaclima  = substr($weather->forecast->current_date_time['data'],11,5);
						$fechaclima = $mesclima."-".$diaclima."-".$anoclima;
						$dia = traduceDia2(date("D",mktime($fechaclima)));
						$detalle_fecha_clima = "al ".$dia." ".date("j",mktime($fechaclima))." a las ".$horaclima;
						$dir_orig = 'http://www.google.com'.end(explode('/',$weather->current->icon['data']));
						$dir_orig = $weather->current->icon['data'];
						$dir_dest = 'includes/clima/iconos/'.end(explode('/',$weather->current->icon['data']));
						if(!file_exists($dir_dest)){
							copy ($dir_orig,$dir_dest);
						}
						if(!file_exists($dir_dest) || $dir_dest == 'includes/clima/iconos/'){
							$dir_dest = 'includes/clima/iconos/mostly_cloudy.gif';
						}
						echo '<h2>Ahora</h2>';
						echo '<table><tr><td>';
						echo '<img src="'.$dir_dest.'" alt="'.$detalle_fecha_clima.'" title="'.$detalle_fecha_clima.'" />';
						echo '</td><td>';
						echo $weather->current->temp_c['data'].' &deg;C ';
						echo " ".utf8_decode($weather->current->condition['data']);
						echo " - ".str_replace('Humedad','Hum',$weather->current->humidity['data']);
						echo '<p>'.$weather->current->wind_condition['data'].'</p>';
						echo '</td></tr></table>';
						// display more days info
						//print_r($weather->nextdays);
						$weather->display();
                            
                     ?>       
                            
                            
                        </div>
					  </div>
					</div>
                    <!--[if lt IE 7]>
	            		</td>
                    	<td>
					<![endif]-->
                    <?PHP include ("col_der.php");?>
                    <!--[if lt IE 7]>
	            		</td>
                    	</tr></table>
					<![endif]-->
				</div>
				<!--[if lt IE 7]>
	            		</td>
                    </tr></table>
				<![endif]-->
			</div>
			<?PHP include "footer.php";?>
		</div>
	</body>
</html>