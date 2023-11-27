<?PHP
function codTx($tx){
	$dev = $tx;
	//$dev = utf8_encode($tx);
	return $dev;
}
function decTx($tx){
	$dev = $tx;
	//$dev = utf8_decode($tx);
	return $dev;
}
// Función para desplazar campos y opciones
function moverCampo($tabla, $funcion){
	$id_orden_1 = $_GET['id'];
	$orden_1    = $_GET['orden'];
	if(isset($_GET['campo'])){$campo=$_GET['campo'];}
	// PRIMERO SE VE QUE NO SEA UN REFRESH
	$querycheck = "SELECT orden FROM ".$tabla." WHERE id = $id_orden_1";
	$resulcheck = fullQuery($querycheck);
	$rowcheck   = mysqli_fetch_array($resulcheck);
	if ($rowcheck['orden'] == $orden_1){
		if($funcion < 7){ // Si son opciones
			$caso_opciones = "AND campo = ".$campo;
		}else{
			$caso_opciones = "";
		}
		// BUSCAR EL VALOR DE ORDEN DE LA OPCION SIGUIENTE
		if($funcion == 5 || $funcion == 7){ // si son de subir
			$query_mover = "SELECT id, orden FROM ".$tabla." WHERE orden < $orden_1 ".$caso_opciones." ORDER BY orden DESC LIMIT 1";
		}else{ // si son de bajar
			$query_mover = "SELECT id, orden FROM ".$tabla." WHERE orden > $orden_1 ".$caso_opciones." ORDER BY orden LIMIT 1";
		}
		$resul_mover 	 = fullQuery($query_mover);
		$row_mover       = mysqli_fetch_array($resul_mover);
		$orden_2         = $row_mover['orden'];
		$id_orden_2      = $row_mover['id'];
		
		// INTERCAMBIAR AMBOS VALORES
		$query_mover = "UPDATE ".$tabla." SET orden = $orden_2 WHERE id = $id_orden_1";
		$resul_mover = fullQuery($query_mover);
		$query_mover = "UPDATE ".$tabla." SET orden = $orden_1 WHERE id = $id_orden_2";
		$resul_mover = fullQuery($query_mover);
	}
}
?>