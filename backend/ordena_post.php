<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
function queryOrdena($variable,$valorhome){
	$debug = 0;
	$valores = explode('&',$variable);
	$query = '';
	$orden = 1;
	foreach($valores as $key=>$value){
		$item = explode('order[]=',$value);
		$item = end($item);
		$id = explode('*',$item);
		$id = current($id);
		$tabla = explode('*',$item);
		$tabla = obtenerDato('nombre','tablas',end($tabla));
		$query = "UPDATE ".$_SESSION['prefijo'].$tabla." SET orden = ".$orden.", home = ".$valorhome." WHERE id = ".$id;
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
$sql_cant = "SELECT SUM(valor) AS suma FROM ".$_SESSION['prefijo']."config WHERE parametro = 'home_slider' OR parametro = 'home_novedades'";
$res_cant = fullQuery($sql_cant);
if(mysqli_num_rows($res_cant) > 0){
	$row_cant = mysqli_fetch_assoc($res_cant);
	$cantidad = $row_cant['suma'];
	$nrotab = explode('&',$enhome);
	$nrotab = current($nrotab);
	$nrotab = explode('*', $nrotab);
	$tabla = obtenerDato('nombre','tablas',end($nrotab));
	$sql = "UPDATE ".$_SESSION['prefijo'].$tabla." SET home = 0 WHERE orden > ".$cantidad;
	$res = fullQuery($sql,'ordena_post.php');
}
?>