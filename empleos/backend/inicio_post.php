<?php
require_once("inc/inc_funciones_globales.php");
include "../../cnfg/config.php";

function nro($valor){
	if ($valor <1 || $valor > 100){
		return false;
	}else{
		return true;
	}
}

function pixels($valor){
	if ($valor <1 || $valor >3000){
		return false;
	}else{
		return true;
	}
}

function cambiaValor($parametro, $valor){
	$error_sql = '';
	$query = "SELECT valor FROM empleos_config WHERE parametro = '".$parametro."'";
	$resul = fullQuery($query) or die($error_sql = mysqli_error());
	$row   = mysqli_fetch_array($resul);
	if ($valor != $row['valor']){
		$query = "UPDATE empleos_config SET valor = '".$valor."' WHERE parametro = '".$parametro."'";
		$result = fullQuery($query) or die($error_sql = mysqli_error()." en query: ".$query);	
	}
	if ($error_sql != ''){
		return $error_sql;
	}
}

//error_reporting(0);

$vencimiento = $_POST['vencimiento'];

if (nro($vencimiento)){
	//echo "ok";
}else{
	$error = 4;
}

if(isset($error)){
	echo '<p class="error"><b>Por favor corrija:</b></p>';
	echo '<span class="error">'.$error.'</span><br />';
}else{
	$error = 0;
	//Comienzan los procesos una vez validado el form
	$error = cambiaValor('vencimiento',$vencimiento);

}
header("Location: index.php?error=$error");
?>