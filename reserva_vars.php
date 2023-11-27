<?PHP
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
if(strlen($diaselect) == 1){$diaselect = '0'.$diaselect;}
$fechasel = $fecha = $anioselect.'-'.$messelect.'-'.$diaselect;
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
$diavselect = (isset($_POST["diav"])) ? $_POST["diav"] : date('d');
$mesvselect = (isset($_POST["mesv"])) ? $_POST["mesv"] : date('m');
$aniovselect = (isset($_POST["aniov"])) ? $_POST["aniov"] : date('Y');
if(isset($_REQUEST['fecha_hasta'])){
	$fecha_hasta = $_REQUEST['fecha_hasta'];
	$diavselect = substr($fecha_hasta,8,2);
	$mesvselect = substr($fecha_hasta,5,2);
	$aniomax = $aniovselect = substr($fecha_hasta,0,4);
}
$fecha_hasta = $aniovselect.'-'.$mesvselect.'-'.$diavselect;
$repetir = (isset($_POST['repeti']) && $_POST['repeti'] > 0) ? $repetir = $_POST['repeti'] : 0;
if(strtotime($fecha_hasta) < strtotime($fecha)){
	$fecha_hasta = $fecha;
}
$diavselect = substr($fecha_hasta,8,2);
$mesvselect = substr($fecha_hasta,5,2);
$aniovselect = substr($fecha_hasta,0,4);
$sala = (isset($_REQUEST['sala'])) ? $_REQUEST['sala'] : 0;
?>