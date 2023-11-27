<?PHP
if(isset($_GET['nuevodato']) && $_GET['nuevodato'] != 0 && $_GET['nuevodato'] != ''){
	$nuevodato = $_GET['nuevodato'];
	$nombredet = parametro('detalle',$tipo);
	$tabladep  = obtenerNombre(parametro('tabla_dependencia',$tipo));
	$campodep  = parametro('campo_dependencia',$tipo);
	$sql_new = "UPDATE ".$_SESSION['prefijo'].$tabladep." SET ".$campodep." = ".$nuevodato." WHERE ".$campodep." = ".$id;
	$res_new = fullQuery($sql_new);
}
$query = "UPDATE ".$_SESSION['prefijo'].$nombretab." SET del = 1 WHERE id = ".$id;
if($tipodet == 'empleados'){ // Si es empleados
	$query = "UPDATE ".$_SESSION['prefijo'].$nombretab." SET del = 1, activo = 0 WHERE id = ".$id;
}
$resul = fullQuery($query);
//borra las fotos
$usafotos  = parametro('fotos',$tipo);
if($usafotos == 1){
	include_once("inc/img.php");
	$query = "SELECT link FROM ".$_SESSION['prefijo']."fotos as fotos WHERE item = ".$id." AND tipo = ".$tipo;
	$resul = fullQuery($query);
	$conta = mysqli_num_rows($resul);
	if($conta > 0){
		$row = mysqli_fetch_assoc($resul);
		$link_img = $row['link'];
		$carpeta = explode('/imagen',$link_img);
		$carpeta = current($carpeta);
		borrarDestacadas($link_img,0,0,1);
		borraFotos($id,$tipo);
		rmdir('../'.$carpeta);
	}
}
if($tipodet == 'encuestas'){ //Encuestas
	$sql_fot = "SELECT fotos.link AS link, fotos.id AS fotoid FROM ".$_SESSION['prefijo']."fotos as fotos
					INNER JOIN ".$_SESSION['prefijo']."encuestas_opc AS opc ON opc.id = fotos.item
					INNER JOIN ".$_SESSION['prefijo']."encuestas AS enc ON enc.id = opc.encuesta
					WHERE fotos.tipo = ".$tipo." AND encuesta = ".$id;
	$res_fot = fullQuery($sql_fot);
	while($row_fot = mysqli_fetch_array($res_fot)){
		$id_item = $row_fot['fotoid'];
		unlink("../".$row_fot['link']);
		unlink("../".str_replace('imagen', 'thumb', $row_fot['link']));
		$sql_borra = "DELETE FROM ".$_SESSION['prefijo']."fotos WHERE id = ".$id_item;
		$res_borra = fullQuery($sql_borra);
	}
	$sql_borra = "DELETE FROM ".$_SESSION['prefijo']."encuestas_votos WHERE encuesta = ".$id;
	$res_borra = fullQuery($sql_borra);
	$sql_borra = "DELETE FROM ".$_SESSION['prefijo']."encuestas_opc WHERE encuesta = ".$id;
	$res_borra = fullQuery($sql_borra);
}
?>