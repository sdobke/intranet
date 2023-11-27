<?php
error_reporting (E_ALL ^ E_NOTICE);
$proveedor = cantidad('clima');
$proveedor = 1;

if($proveedor == 1){
	require_once('clima/google_weather_api.php');
}
if($proveedor == 2){
	//require_once('clima/pxweather/pxweather.class.php');
}

function traduceDia($dia){
	$orig = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
	$dest = array("Lun","Mar","Mie","Jue","Vie","Sab","Dom");
	return str_replace($orig, $dest, $dia);
}

if($proveedor == 1){
	$weather = new weather();
	$weather->location = 'Buenos Aires';
	if (!empty($_GET['loc'])) {
		$weather->location = $_GET['loc'];
	}
	$weather->get();
	//echo ucwords($weather->location).': ';
	$anoclima = substr($weather->forecast->current_date_time['data'],0,4);
	$mesclima = substr($weather->forecast->current_date_time['data'],5,2);
	$diaclima = substr($weather->forecast->current_date_time['data'],8,2);
	$horaclima  = substr($weather->forecast->current_date_time['data'],11,5);
	$fechaclima = $mesclima."-".$diaclima."-".$anoclima;
	$dia = traduceDia(date("D",mktime($fechaclima)));
	$detalle_fecha_clima = "al ".$dia." ".date("j",mktime($fechaclima))." a las ".$horaclima;
	$dir_orig = 'http://www.google.com'.$weather->current->icon['data'];
	$dir_dest = 'includes/clima/iconos/'.end(explode('/',$weather->current->icon['data']));
	/*
	if(!file_exists($dir_dest)){
		copy ($dir_orig,$dir_dest);
	}
	*/
	if(!file_exists($dir_dest) || $dir_dest == 'includes/clima/iconos/'){
		$dir_dest = 'includes/clima/iconos/mostly_cloudy.gif';
	}
}

if($proveedor == 2){
	echo '<a href="http://weather.com" target="_blank"><img src="includes/clima/iconos/TWClogo_31px.png" alt="weather.com" title="weather.com" border="0" style="border:none" /></a>&nbsp;';
}

if($proveedor == 2){
	require_once('clima/weather/index.php');
}


echo '<img src="'.$dir_dest.'" alt="'.$detalle_fecha_clima.'" title="'.$detalle_fecha_clima.'" width="20"/>';
echo ' <strong>El Clima:</strong> ';
echo $weather->current->temp_c['data'].' &deg;C ';
echo " ".utf8_decode($weather->current->condition['data']);
echo " - ".str_replace('Humedad','Hum',$weather->current->humidity['data']);
echo ' | <a href="clima.php">Pron&oacute;stico extendido</a> ';

?>