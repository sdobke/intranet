<?PHP
$where_adicional = (isset($otro_query) && $otro_query!= '') ? " AND ".$otro_query : '';
$orden_query     = (isset($query_orden)) ? $query_orden : 'id DESC';
$restriccion = (isset($_SESSION['usrfrontend'])) ? verRestriccion($_SESSION['usrfrontend']): 0;

$moscampos = ',inov.*';

$msql = "SELECT fecha,hora,titulo,texto FROM intranet_tablas WHERE id = ".$tipo;
$mres = fullQuery($msql);
$mrow = mysqli_fetch_array($mres);
if($mrow['titulo'] == 1){
	$moscampos.= ',inov.'.$mrow['titulo'];
}
if($mrow['texto'] == 1){
	$moscampos.= ',inov.texto';
}
if($mrow['fecha'] == 1){
	$moscampos.= ',inov.fecha';
}
if($mrow['hora'] == 1){
	$moscampos.= ',inov.hora';
}

if (isset($_GET['busqueda'])){
	$busqueda = $_GET['busqueda'];
}
if (isset($_POST['busqueda'])){
	$busqueda = $_POST['busqueda'];
}
if (isset($busqueda)){
	// arma las búsquedas según la cantidad de campos
	$buscampos = " AND inov.".$buscampo1." LIKE '%".$busqueda."%' ";
	$matches   = $buscampo1;
	if(isset($buscampo2)){
		$buscampos.= " OR "."inov.".$buscampo2." LIKE '%".$busqueda."%' ";
		$matches.= ", ".$buscampo2;
	}
	//Cuento la cantidad de palabras
	$trozos=explode(" ",$busqueda);
	$numero=count($trozos);
	if ($numero==1) {
		//Si tengo una palabra
		
		//$cadbusca="SELECT * FROM intranet_".$nombre." WHERE ".$buscampos.$where_adicional." ORDER BY ".$orden_query;
		
		$cadbusca = "SELECT inov.id, inov.id = 'ddd' AS tipo ".$moscampos."
						FROM intranet_".$nombre." AS inov 
							INNER JOIN intranet_link AS il 
								ON inov.id = il.item 
							WHERE il.tipo = ".$tipo." AND FIND_IN_SET(" . $restriccion . ",il.part) ".$buscampos.$where_adicional."
					 
						ORDER BY ".$orden_query;
		
		
	}elseif ($numero>1){
		//Si tengo más de una palabra
		$palabra1 = current($trozos);
		$palabra2 = end($trozos);
		$cadbusca = "SELECT *, MATCH (".$matches.") AGAINST ('$palabra1' IN BOOLEAN MODE) as aprox FROM intranet_".$nombre." WHERE MATCH (".$matches.") AGAINST ('$palabra2' IN BOOLEAN MODE) ".$where_adicional." ORDER BY aprox DESC";
  	}
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$cadbusca="SELECT * FROM intranet_".$nombre." WHERE id = $id";
	}
}else{
	$cadbusca = "SELECT inov.id, inov.id = 'ddd' AS tipo ".$moscampos."
					FROM intranet_".$nombre." AS inov 
						INNER JOIN intranet_link AS il 
							ON inov.id = il.item 
								WHERE il.tipo = ".$tipo." AND FIND_IN_SET(" . $restriccion . ",il.part) ".$where_adicional."
				ORDER BY ".$orden_query;
	
	//$cadbusca="SELECT * FROM intranet_".$nombre." WHERE 1 ".$where_adicional." ORDER BY ".$orden_query;
	$busqueda = '';
}
?>