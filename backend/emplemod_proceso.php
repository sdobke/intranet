<?PHP
// Actualización de datos
$categofiltro = '(0,6,7,8,13,20,22,25,32,33,56,57,58,59,60,62,73,76,77,78,302,305,308,309,315,317)';
$sql_alta = "SELECT rut FROM intranet_empleados WHERE del = 0 AND activo = 1 AND rut IS NOT NULL AND rut != '0' AND rut != ''";
//$sql_alta.= " AND fecha_ing > DATE_SUB(NOW(), INTERVAL -7 day)"; // PARA BUSCAR SOLAMENTE HACE UNA SEMANA
$res_alta = fullQuery($sql_alta);
$emples = '';
$c_alta = 0;
while($row_alta = mysqli_fetch_array($res_alta)){
	if($c_alta > 0){$emples.= ',';}
	$emples.= '"'.$row_alta['rut'];
	$emples.= '"';
	$c_alta++;
}
$sql_alta2 = "SELECT rut FROM empleados WHERE fecha_ret = '3000-01-01' AND rut NOT IN (".$emples.") AND cargo NOT IN (13,15) AND catego NOT IN ".$categofiltro." LIMIT 2000";
$res_alta2 = fullQuery($sql_alta2);
$can_alta2 = mysqli_num_rows($res_alta2);
if($debug == 1){echo '<br><br>Proceso de Altas: '.$can_alta2;}
if($can_alta2 > 0){
	$sql_guardalta = "UPDATE intranet_config SET valor = ".$can_alta2." WHERE parametro = 'altas'";
	$res_guardalta = fullQuery($sql_guardalta);
	$altas2 = '';
	$c_alta2 = 0;
	while($row_alta2 = mysqli_fetch_array($res_alta2)){
		if($c_alta2 > 0){$altas2.= ',';}
		$altas2.= '"'.$row_alta2['rut'];
		$altas2.= '"';
		$c_alta2++;
	}
	$updalt = "UPDATE emp_mods SET datos = '".$altas2."' WHERE id = 1";
	$resalt = fullQuery($updalt);
}
// *******
// BAJAS *
// *******
$sql_baja = "SELECT rut, fecha_ret FROM empleados WHERE fecha_ret <= '".date("Y-m-d")."' AND rut IN (".$emples.") AND cargo NOT IN (13,15) AND catego NOT IN ".$categofiltro." LIMIT 2000";
$res_baja = fullQuery($sql_baja);
$can_baja = mysqli_num_rows($res_baja);
if($debug == 1){echo '<br>Proceso de bajas: '.$can_baja;}
if($can_baja > 0){
	$cantibaj = 0;
	$nrobaja = '';
	while($row_baja = mysqli_fetch_assoc($res_baja)){
		// Para estar seguros que no hubo un alta posterior a la baja (reincorporación) verificamos
		$sql_rev = "SELECT rut FROM empleados WHERE rut = '".$row_baja['rut']."' AND fecha_ret = '3000-01-01'";
		$res_rev = fullQuery($sql_rev);
		$con_rev = mysqli_num_rows($res_rev);
		if($con_rev == 0){ // Si no hubo reincorporación
			if($cantibaj > 0){$nrobaja.= ',';}
			$nrobaja.= '"'.$row_baja['rut'].'"';
			$cantibaj++;
		}
	}
}
$sql_guardbaja = "UPDATE intranet_config SET valor = ".$cantibaj." WHERE parametro = 'bajas'";
$res_guardbaja = fullQuery($sql_guardbaja);
$updbaj = "UPDATE emp_mods SET datos = '".$nrobaja."' WHERE id = 2";
$resbaj = fullQuery($updbaj);
?>