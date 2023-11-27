<?PHP
$borrado = 0;
foreach ($_POST as $key => $value) { // Levanta todos los POST
	$id_borrar = explode('borrar_',$key);
	$id_borrar = end($id_borrar);
	if(isset($id_borrar) && is_numeric($id_borrar) && $id_borrar > 0){
		$query = "UPDATE ".$_SESSION['prefijo'].$nombretab." SET del = 1 WHERE id = ".$id_borrar;
		
		if($tipodet == 'empleados'){ // Si es empleados
			$query = "UPDATE ".$_SESSION['prefijo'].$nombretab." SET del = 1, activo = 0 WHERE id = ".$id_borrar;
		}
		$resul = fullQuery($query);
		$borrado = 1;
	}
}
if(isset($_GET['opciond']) && $_GET['opciond'] == 'Elim' && isset($_GET['id']) && isset($_GET['tipo'])){
	$id_borrar = $_GET['id'];
	$query = "UPDATE ".$_SESSION['prefijo'].$nombretab." SET del = 1 WHERE id = ".$id_borrar;
		
	if($tipodet == 'empleados'){ // Si es empleados
		$query = "UPDATE ".$_SESSION['prefijo'].$nombretab." SET del = 1, activo = 0 WHERE id = ".$id_borrar;
	}
	$resul = fullQuery($query);
	$borrado = 1;
}
$usafotos  = parametro('fotos',$tipo);
if($usafotos == 1 && $borrado == 1){ //borra las fotos
	include_once("inc/img.php");
	$query = "SELECT link FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id_borrar." AND tipo = ".$tipo;
	$resul = fullQuery($query);
	$conta = mysqli_num_rows($resul);
	if($conta > 0){
		$row = mysqli_fetch_assoc($resul);
		$link_img = $row['link'];
		$carpeta = explode('/imagen',$link_img);
		$carpeta = current($carpeta);
		borrarDestacadas($link_img,0,0,1);
		borraFotos($id_borrar,$tipo);
		//if(file_exists('../'.$carpeta) && is_dir('../'.$carpeta)){rmdir('../'.$carpeta);}
	}
}
?>