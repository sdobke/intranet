<?PHP
$debug = 1;
if($debug == 1){echo '<br>Definicion de zona horaria por defecto';}
date_default_timezone_set('America/Buenos_Aires');
if($debug == 1){echo '<br>Definicion de limite de memoria';}
//ini_set('memory_limit','1000M');
function rmdirtree($dirname) {
   if (is_dir($dirname)) {
       $result=array();
       if (substr($dirname,-1)!='/') {$dirname.='/';}
       $handle = opendir($dirname);
       while (false !== ($file = readdir($handle))) {
           if ($file!='.' && $file!= '..') {
               $path = $dirname.$file;
               if (is_dir($path)) {
                   $result=array_merge($result,rmdirtree($path));
               }else{
                   unlink($path);
                   $result[].=$path;
               }
           }
       }
       closedir($handle);
       //rmdir($dirname);
       $result[].=$dirname;
       return $result;
   }else{
       return false;
   }
}
function cantidad($parametro){
	$query = fullQuery("SELECT valor FROM ".$_SESSION['prefijo']."config where parametro = '$parametro'") or die(mysqli_error());
	$row = mysqli_fetch_array($query);
	$dif = $row['valor'];
	return $dif;
}
$dirname = "../temp";
if($debug == 1){echo '<br>Borrado '.$dirname;}
rmdirtree($dirname);
$dirname = "tempimg";
if($debug == 1){echo '<br>Borrado '.$dirname;}
rmdirtree($dirname);
$dirname = "tempvid";
if($debug == 1){echo '<br>Borrado '.$dirname;}
rmdirtree($dirname);
//include_once "../cnfg/config.php";
$modo_local = 0;
$db_user = "root";
$db_pass = "";
$db_srvr = "localhost";
if($modo_local == 0){
	$db_user = "web";
	$db_pass = "w3b4ls34";
}
$db_base = "alsea";
if($debug == 1){echo '<br>Conexion a DB';}
$conexion = @mysqli_connect($db_srvr, $db_user, $db_pass);
mysqli_select_db($db_base , $conexion)or die ("Error de base de datos: ".mysqli_error());
$_SESSION['prefijo'] = 'intranet_';
include_once "../inc/funciones.php";
//include "sorteos_aviso.php";
if($debug == 1){echo '<br>Aniversarios y Cumpleanios';}
if($modo_local == 0){include_once "cron/tarjeta_aniversario.php";}
//include_once "cron/procesar_empleados.php";
$dirname = "../temp";
// Desactiva sorteos vencidos
if($debug == 1){echo '<br>desactiva sorteos vencidos';}
$sqlsv = "UPDATE intranet_sorteos SET activo = 0 WHERE fecha <= '".date("Y-m-d")."'";
$ressv = fullQuery($sqlsv);
if($debug == 1){echo '<br>proceso de empleados';}
include("emplemod_proceso.php");
//include "../obras/cron_diario.php";
?>