<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
function queryOrdena($variable,$valorhome){
	$debug = 0;
	$valores = explode('&',$variable);
	$query = '';
	$orden = 1;
	foreach($valores as $key=>$value){
		$item = end(explode('order[]=',$value));
		$id = current(explode('*',$item));
		$tabla = explode('*',$item);
		$tabla = obtenerDato('nombre','tablas',end($tabla));
		$query = "UPDATE ".$tabla." SET ordencol = ".$orden.", col = ".$valorhome." WHERE id = ".$id;
		if($debug == 1){
			$sql_debug = "INSERT INTO debug (texto) VALUES ('
			".$query."')";
			$res_debug = fullQuery($sql_debug);
		}
		$resul = fullQuery($query);
		$orden++;
	}
}
$enhome = $_POST['sort1'];
queryOrdena($enhome,1);
$nohome = $_POST['sort2'];
queryOrdena($nohome,0);
?>