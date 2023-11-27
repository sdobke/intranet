<?PHP 
require_once("../../cnfg/config.php");
require_once("sechk.php");
require_once("inc/inc_funciones_globales.php");
require_once("inc/func_backend.php");

$tipo   = getPost('tipo');
$nombretab = obtenerNombre($tipo);
$nombredet = parametro('detalle',$tipo);

$usafecha  = parametro('fecha',$tipo); // 1 es fecha manual, 2 es vencimiento manual, 3 es fecha auto, 4 es vencimiento auto
$usatexto  = parametro('texto',$tipo);
$nomtitulo = parametro('nombre_detalle',$tipo);

$error    = '';
$id       = nuevoID($nombretab);

$nombre   = reemplazo_comillas($_POST['nombre']);
if($usatexto == 1){
	$qtexto1 = ', texto';
	$qtexto2 = ", '".$_POST['texto']."'";
}
if($usafecha != 0){
	$qfecha1 = ', fecha';
	$qfecha2 = ', "'.$_POST['fecha'].'"';
}

// Combos
$qcombos1 = '';
$qcombos2 = '';

$query_combos = "SELECT tab.nombre AS variable FROM empleos_combos AS cbo
					INNER JOIN empleos_tablas AS tab ON (cbo.combo = tab.id)
					WHERE cbo.tabla = ".$tipo;
$resul_combos = fullQuery($query_combos);
while($row_combos = mysqli_fetch_array($resul_combos)){
	$var_combo    = $row_combos['variable'];
	$qcombos1.= ', '.$var_combo;
	$qcombos2.= ', '.$_POST[$var_combo];
}

$query  = "INSERT INTO empleos_".$nombretab." (id, nombre".$qtexto1.$qfecha1.$qcombos1.") VALUES (".$id.", '".$nombre."'".$qtexto2.$qfecha2.$qcombos2.") ";
if($result = fullQuery($query)){
	if($tipo == 1 || $tipo == 12){ // si son búsquedas se guardan estadísticas
		$sql = "INSERT INTO empleos_stats_busq (busqueda, tipo, estado, postulados, fecha) VALUES (".$id.", ".$tipo.", ".$_POST['estado'].",0, '".date("Y-m-d")."')";
		$res = fullQuery($sql);
	}
}
//echo $query;

if ($error == ''){
	$msg = 0;
}else{
	$msg = 1;
}
header("Location: listado.php?tipo=".$tipo."&error=".$msg);
?>