<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$sql = "SELECT * FROM intranet_empleados_old";
$res = fullQuery($sql);
$contador = 0;
while($row = mysqli_fetch_array($res)){
	
	$fechanac = $row['fechanac'];
	$fechaing = $row['fechaing'];
	$id = $row['id'];
	$apellido = $row['apellido'];
	
	$sql2 = "SELECT id FROM intranet_empleados WHERE id = ".$id." AND apellido = '".$apellido."'";
	$res2 = fullQuery($sql2);
	$con2 = mysqli_num_rows($res2);
	if($con2 > 0){
		$contador++;
		$sql3 = "UPDATE intranet_empleados SET fechanac = '".$fechanac."', fechaing = '".$fechaing."' WHERE id = ".$id." AND apellido = '".$apellido."'";
		$res3 = fullQuery($sql3);
	}
}
echo $contador;
?>