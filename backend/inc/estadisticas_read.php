<?PHP
function mesActual($mes){
	$activo = (isset($_POST['mes'])) ? $_POST['mes'] : date('m');
	$dev = '';
	if ($mes == $activo){
		$dev = 'selected="selected"';
	}
	return $dev;
}
function anioActual($anio){
	$activo = (isset($_POST['ano'])) ? $_POST['ano'] : date('Y');
	$dev = '';
	if ($anio == $activo){
		$dev = 'selected="selected"';
	}
	return $dev;
}
// Por defecto, el mes actual
$fechadesde = date("Y-m")."-"."01";
$fechahasta = date("Y-m-d");
$fecha = (isset($_POST['fecha'])) ? $_POST['fecha'] : 'mes';
if(isset($_GET['fecha']) && $_GET['fecha'] == 'tot'){$fecha = 'tot';}
 
if (isset($_POST['posteado']) && $_POST['posteado'] == 1){
	if($fecha == 'mes'){
		$mes = $_POST['mes'];
		$ano = $_POST['ano'];
		$fechadesde = $ano.'-'.$mes.'-01';
		$fechahasta = $ano.'-'.$mes.'-31';
	}else{
		$fecdi    = substr($_POST['fecha_desde'],0,2);
		$fecme    = substr($_POST['fecha_desde'],3,2);
		$fecan    = substr($_POST['fecha_desde'],6,4);
		$fechadesde = $fecan.'-'.$fecme.'-'.$fecdi;
		
		$fecdi    = substr($_POST['fecha_hasta'],0,2);
		$fecme    = substr($_POST['fecha_hasta'],3,2);
		$fecan    = substr($_POST['fecha_hasta'],6,4);
		$fechahasta = $fecan.'-'.$fecme.'-'.$fecdi;
		
	}
}
if (isset($_GET['desde']) && isset($_GET['hasta'])){
	$fechadesde = $_GET['desde'];
	$fechahasta = $_GET['hasta'];
}
if($fecha == 'tot'){
	$sql_fti = "SELECT fecha FROM intranet_accesos_detalle ORDER BY fecha LIMIT 1";
	$res_fti = fullQuery($sql_fti);
	$row_fti = mysqli_fetch_assoc($res_fti);
	$fechadesde = $row_fti['fecha'];
	$sql_ftf = "SELECT fecha FROM intranet_accesos_detalle ORDER BY fecha DESC LIMIT 1";
	$res_ftf = fullQuery($sql_ftf);
	$row_ftf = mysqli_fetch_assoc($res_ftf);
	$fechahasta = $row_ftf['fecha'];
}
?>