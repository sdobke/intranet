<?PHP
error_reporting(E_ALL);
// Funciones
function procesar_dato($cod, $desc, $tabla, $emp=0, $cencos=0){
	$nomcod = ($tabla == $_SESSION['prefijo'].'locales_loc') ? 'codigo' : 'cod';
	$sql = "SELECT ".$nomcod." FROM ".$tabla." WHERE ".$nomcod." = ".$cod;
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if($con == 0){
		$emp1 = ($emp == 0) ? '' : ', empresa';
		$emp2 = ($emp == 0) ? '' : ','.$emp;
		$cenc = ($cencos == 0) ? '' : ', cencos';
		$cen2 = ($cencos == 0) ? '' : ', '.$cencos;
		$sql_nue = "INSERT INTO ".$tabla." (".$nomcod.", nombre".$emp1.$cenc.") VALUES (".$cod.", '".$desc."'".$emp2.$cen2.")";
		$res_nue = fullQuery($sql_nue);
	}
}
// Proceso diario
$hora = date("H:i:s");
echo '<br>--------------------------------------<br>'.$hora.' | Procesando Tablas y empleados';
$sql = "SELECT * FROM empleados WHERE fecha_ret = '3000-01-01'";
$res = fullQuery($sql);
while($row = mysqli_fetch_array($res)){
	$sql2 = "SELECT id FROM intranet_empleados WHERE rut = '".$row['rut']."'";
	$res2 = fullQuery($sql2);
	$con2 = mysqli_num_rows($res2);
	if($con2 == 0){ // Si el empleado no existe
	
		$nombre = $row['nombre'];
		// Proceso de corrección de nombres
		$nuenom = (substr($nombre,0,1) == ' ') ? substr($nombre,1,100) : $nombre;
		$pos = strpos($nuenom, '  ');
		if ($pos === false) {
			// Si no encuentra el doble espacio no hace nada
		}else{
			$nuenom = preg_replace('/\s\s+/', ' ', $nuenom);
		}
		//$nuenom = (substr($nuenom, 0, -1) == ' ') ? substr($nuenom, 0, -1) : $nuenom;
		$nombre = $nuenom;
		$apellido = $row['apellido'];
		$fecha_nac = $row['fecha_nac'];
		$fecha_ing = $row['fecha_ing'];
		$cargo = $row['d_cargo'];
		$rut = $row['rut'];
		
		$sql3 = "INSERT INTO intranet_empleados (rut, nombre, apellido, cargo, fechanac, fechaing, activo) 
											VALUES ('{$rut}', '{$nombre}', '{$apellido}', '{$cargo}', '{$fecha_nac}', '{$fecha_ing}', 0)";
		$res3 = fullQuery($sql3);
	}
}
?>