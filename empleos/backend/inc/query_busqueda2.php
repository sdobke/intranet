<?PHP
$where_adicional = (isset($otro_query) && $otro_query!= '') ? " AND ".$otro_query : '';
$orden_query     = (isset($query_orden)) ? $query_orden : 'id DESC';
$restriccion = (isset($_SESSION['usrfrontend'])) ? verRestriccion($_SESSION['usrfrontend']): 0;
switch($tipo){
	case 7: // novedades
		$campos = 'inov.id AS id, inov.fecha AS fecha, inov.hora AS hora, inov.titulo AS titulo, inov.texto AS texto';
		break;
	case 9: // galerías
		$campos = 'inov.id AS id, inov.titulo AS titulo, inov.texto AS texto, inov.fecha AS fecha';
		break;
	case 15: // concurso de fotos
		$campos = 'inov.id AS id, inov.titulo AS titulo, inov.texto AS texto, inov.activo AS activo';
		break;
	default:
		$campos = 'inov.id AS id, inov.titulo AS titulo';
		break;
}

$busqueda = '';
if (isset($_GET['busqueda'])){
	$busqueda = $_GET['busqueda'];
}
if (isset($_POST['busqueda'])){
	$busqueda = '%'.$_POST['busqueda'];
}
	$trozos=explode(" ",$busqueda);
	$numero=count($trozos);
	if ($numero>1) {
		$busqueda_todo = explode(" ",$busqueda);
		$busqueda = '';
		foreach($busqueda_todo as $buspartes){
			$busqueda.= '%'.$buspartes;
		}
	}
if (isset($busqueda)){
	// arma las búsquedas según la cantidad de campos
	$buscampos = "inov.".$buscampo1." LIKE '".$busqueda."%' ";
	$matches   = "inov.".$buscampo1;
	if(isset($buscampo2)){
		$buscampos.= " OR "."inov.".$buscampo2." LIKE '".$busqueda."%' ";
		$matches.= ", "."inov.".$buscampo2;
	}
	//Cuento la cantidad de palabras
	$trozos=explode(" ",$busqueda);
	$numero=count($trozos);
	if ($numero==1) {
		//Si tengo una palabra
		
		//$cadbusca="SELECT * FROM empleos_".$nombre." WHERE ".$buscampos.$where_adicional." ORDER BY ".$orden_query;
		
		$cadbusca = "(SELECT ".$campos." 
						FROM empleos_".$nombre." AS inov 
							INNER JOIN empleos_link AS il ON inov.id = il.item 
								WHERE il.tipo = ".$tipo." AND il.part = ".$restriccion." AND (".$buscampos.")".$where_adicional.") 
					UNION
					 (SELECT ".$campos."
					 	FROM empleos_".$nombre." AS inov
							INNER JOIN empleos_link AS il ON inov.id = il.item
								WHERE il.tipo = ".$tipo." AND il.part = 0 AND (".$buscampos.")".$where_adicional.")
					ORDER BY ".$orden_query;
		
		
	}else{
		//Si tengo más de una palabra
		$palabra1 = current($trozos);
		$palabra2 = end($trozos);
		//$cadbusca = "SELECT *, MATCH (".$matches.") AGAINST ('$palabra1' IN BOOLEAN MODE) AS aprox FROM empleos_".$nombre." WHERE MATCH (".$matches.") AGAINST ('$palabra2' IN BOOLEAN MODE) ".$where_adicional." ORDER BY aprox DESC";
		
		$cadbusca = "(SELECT ".$campos.",
						MATCH (".$matches.") AGAINST ('$palabra1' IN BOOLEAN MODE) AS aprox 
						FROM empleos_".$nombre." AS inov
							INNER JOIN empleos_link AS il ON inov.id = il.item
								WHERE il.tipo = ".$tipo." AND il.part = ".$restriccion." AND MATCH (".$matches.") AGAINST ('$palabra2' IN BOOLEAN MODE) 
									AND ".$buscampos.$where_adicional.")
					UNION
					 (SELECT ".$campos.", 
					 	MATCH (".$matches.") AGAINST ('$palabra1' IN BOOLEAN MODE) AS aprox 
						FROM empleos_".$nombre." AS inov 
							INNER JOIN empleos_link AS il ON inov.id = il.item
								WHERE il.tipo = ".$tipo." AND il.part = 0 AND MATCH (".$matches.") AGAINST ('$palabra2' IN BOOLEAN MODE) AND ".$buscampos.$where_adicional.") 
					ORDER BY aprox DESC";
		
  	}
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$cadbusca="SELECT * FROM empleos_".$nombre." WHERE id = $id";
	}
}else{
	$cadbusca = "(SELECT ".$campos."
					FROM empleos_".$nombre." AS inov INNER JOIN empleos_link AS il ON inov.id = il.item 
						WHERE il.tipo = ".$tipo." AND il.part = ".$restriccion.$where_adicional.") 
				UNION
				 (SELECT ".$campos."
				 	FROM empleos_".$nombre." AS inov INNER JOIN empleos_link AS il ON inov.id = il.item
						WHERE il.tipo = ".$tipo." AND il.part = 0".$where_adicional.") 
				ORDER BY ".$orden_query;
	
	//$cadbusca="SELECT * FROM empleos_".$nombre." WHERE 1 ".$where_adicional." ORDER BY ".$orden_query;
	$busqueda = '';
}
?>