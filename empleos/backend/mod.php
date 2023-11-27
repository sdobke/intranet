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
$id       = getPost('id');

$nombre   = reemplazo_comillas($_POST['nombre']);
if($usatexto == 1){
	$qtexto = ", texto = '".$_POST['texto']."'";
}
if($usafecha != 0){
	$qfecha = ', fecha = "'.$_POST['fecha'].'"';
}

// Combos
$qcombos = '';

$query_combos = "SELECT tab.nombre AS variable FROM empleos_combos AS cbo
					INNER JOIN empleos_tablas AS tab ON (cbo.combo = tab.id)
					WHERE cbo.tabla = ".$tipo;
$resul_combos = fullQuery($query_combos);
while($row_combos = mysqli_fetch_array($resul_combos)){
	$var_combo    = $row_combos['variable'];
	$qcombos.= ', '.$var_combo.' = '.$_POST[$var_combo];
}

$query  = "UPDATE empleos_".$nombretab." SET nombre = '$nombre' ".$qtexto.$qfecha.$qcombos." WHERE id = ".$id;
if($result = fullQuery($query)){
	if($tipo == 1 || $tipo == 12){ // si son búsquedas se guardan estadísticas
		$sql = "UPDATE empleos_stats_busq SET estado = ".$_POST['estado']." WHERE busqueda = ".$id;
		$res = fullQuery($sql);
	}

}

if ($error == ''){
	$msg = 0;
}else{
	$msg = 1;
}
header("Location: detalles.php?tipo=".$tipo."&id=".$id."&error=".$msg);
?>