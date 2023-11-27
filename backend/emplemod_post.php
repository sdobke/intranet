<?PHP 
$cantalt = config('altas');
$cantbaj = config('bajas');
$nep = 'backend/emplemod_post';
foreach ($_POST as $key => $value) { // Levanta todos los POST
	if($value == 1){
		$tipdat = substr($key,4,1);
		$idemp = substr($key,6);
		if($tipdat == 'a'){ // alta
			$sql_de = "SELECT rut, fecha_nac, fecha_ing, fecha_ret, sexo, nombre, apellido FROM empleados WHERE rut = '".$idemp."'";
			//echo '<br>'.$sql_de;
			$res_de = fullQuery($sql_de, $nep);
			$row_de = mysqli_fetch_assoc($res_de);
			$nombre = $row_de['nombre'];
			// Proceso de corrección de nombres
			$nuenom = (substr($nombre,0,1) == ' ') ? substr($nombre,1,100) : $nombre;
			$pos = strpos($nuenom, '  ');
			if ($pos === false) {
				// Si no encuentra el doble espacio no hace nada
			}else{
				$nuenom = preg_replace('/\s\s+/', ' ', $nuenom);
			}
			$empidm = nuevoID("empleados");
			$sql_in = "INSERT INTO ".$_SESSION['prefijo']."empleados (id, rut, nombre, apellido, fechanac, fechaing, activo) VALUES (".$empidm.",'".$row_de['rut']."', '".$nuenom."', '".$row_de['apellido']."', '".$row_de['fecha_nac']."', '".$row_de['fecha_ing']."', 1)";
			//echo '<br>'.$sql_in;
			$res_in = fullQuery($sql_in,$nep);
			
			
			/* --> Borrar después del 1/3/15
			$cantalt--;
			$sql_bm = "SELECT datos FROM emp_mods WHERE tipo = 'alta'";
			$res_bm = fullQuery($sql_bm);
			$row_bm = mysqli_fetch_assoc($res_bm);
			$datosm = $row_bm['datos'];
			$modifi = str_replace('"'.$row_de['rut'].'"','',$datosm);
			$modifi = str_replace(',,','',$modifi);
			$sql_bn = "UPDATE emp_mods SET datos = '".$modifi."' WHERE tipo = 'alta'";
			//echo '<br>'.$sql_bn;
			$res_bm = fullQuery($sql_bn);
			*/
		}
		if($tipdat == 'b'){ // baja
			$rut_em = $idemp;
			$sql_dl = "UPDATE ".$_SESSION['prefijo']."empleados SET activo = 0, del = 1 WHERE rut = '".$rut_em."'";
			echo '<br>'.$sql_dl;
			$res_dl = fullQuery($sql_dl, $nep);
			$cantbaj--;
			
			/* --> Borrar después del 1/3/15
			$sql_bm = "SELECT datos FROM emp_mods WHERE tipo = 'baja'";
			$res_bm = fullQuery($sql_bm);
			$row_bm = mysqli_fetch_assoc($res_bm);
			$datosm = $row_bm['datos'];
			$modifi = str_replace('"'.$rut_em.'"','',$datosm);
			$modifi = str_replace(',,','',$modifi);
			$modifi = str_replace('""','","',$modifi);
			$sql_bn = "UPDATE emp_mods SET datos = '".$modifi."' WHERE tipo = 'baja'";
			$sql_bn = str_replace(",' WHERE","' WHERE",$sql_bn);
			//echo '<br>'.$sql_bn;
			$res_bm = fullQuery($sql_bn);
			*/
		}
	}
}
/* --> Borrar después del 1/3/15
$sql_upd1 = "UPDATE ".$_SESSION['prefijo']."config SET valor = ".$cantalt." WHERE parametro = 'altas'";
$res_upd1 = fullQuery($sql_upd1);
$sql_upd2 = "UPDATE ".$_SESSION['prefijo']."config SET valor = ".$cantbaj." WHERE parametro = 'bajas'";
$res_upd2 = fullQuery($sql_upd2);
*/
?>