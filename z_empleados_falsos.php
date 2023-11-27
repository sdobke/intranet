<?PHP
include "cnfg/config.php";
include "inc/funciones.php";


$listadop = array("Jefatura de area", "Ventas", "Ventas", "Ventas", "Compras", "Compras", "Asistente", "Asistente", "Programacion", "Responsable de planeamiento", "Comercial", "Pago a proveedores", "Operaciones", "Finanzas", "Marketing", "Contabilidad", "finanzas", "Analista de RRHH", "Recepcionista");
$listausr = array("Demo", "Usuario", "Pruebas", "Testing", "Tester", "User");
$entrada = array("Luis", "Santiago", "Fernando", "Paula", "Mariana", "Isabel", "Pablo", "Luis", "Jorge", "Fernanda", "Alejandra", "Cecilia", "Roberto");

$sql = "DELETE FROM intranet_empleados where del = 1 OR fechanac='0000-00-00' OR fechaing = '0000-00-00'";
$res = fullQuery($sql);
$sql = "SELECT * FROM intranet_empleados where del = 0";
$res = fullQuery($sql);
$cont = 0;
while($row = mysqli_fetch_array($res)){
	$cont++;
	shuffle($entrada);
	$nombre = $entrada[0];
	shuffle($listausr);
	$apellido = $listausr[0];
	shuffle($listadop);	
	$puesto = $listadop[0];
	$usuario = strtolower(substr($nombre,0,1).$apellido).rand(0,99);
	$password = 'fsdf';
	$extra = '';
	if($cont == 1){
		$usuario = 'aatester';
		$password = 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff';
		$extra = ',activo=1,verificador="" ';
	}	
	$nuecum = date("Y-m-d",strtotime("-93days",strtotime($row['fechanac'])));
	echo '<br>Cumple: '.$nuecum;
	$nueani = date("Y-m-d",strtotime("-112days",strtotime($row['fechanac'])));
	//echo '<br />'.$row['fechanac'].' -> '.$nuecum;
	$sql2 = "UPDATE intranet_empleados set legajo = ".$cont.", nombre = '".$nombre."', email = '".$usuario."@demointra.com', apellido = '".$apellido."', interno = '555-5555', usuario = '".$usuario."', password='".$password."', fechanac = '".$nuecum."', fechaing = '".$nueani."', puesto = '".$puesto."'".$extra." WHERE id = ".$row['id'];
	echo '<br />'.$sql2;
	$res2 = fullQuery($sql2);
}
?>