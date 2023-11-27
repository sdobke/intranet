<?PHP 
require_once("../../cnfg/config.php");
require_once("sechk.php");
require_once("inc/inc_funciones_globales.php");
require_once("inc/func_backend.php");

$error    = '';
$id       = $_GET['id'];
$tipo     = $_GET['tipo'];

$query  = "UPDATE empleos_busqueda_postulantes SET estado = 1 WHERE id = ".$id;
$result = fullQuery($query);

if ($error == ''){
	$msg = 0;
}else{
	$msg = 1;
}
header("Location: listado.php?tipo=".$tipo."&error=".$msg);
?>