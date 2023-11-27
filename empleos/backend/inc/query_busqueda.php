<?PHP
$where_combos = '';
foreach ($_POST as $key => $value) {
	$nom_post = end(explode('bus_',$key));
    $query_bus = "SELECT * FROM empleos_tablas WHERE nombre = '".$nom_post."'";
	$resul_bus = fullQuery($query_bus);
	$conta_bus = mysqli_num_rows($resul_bus);
	if($conta_bus > 0){
		if($value > 0){
			$where_combos = " AND ".$nom_post." = ".$value;
		}
	}
}

$where_adicional = (isset($otro_query) && $otro_query != '') ? " AND ".$otro_query : '';
$orderby = (isset($order_by)) ? $order_by : 'id DESC';
if (isset($_GET['busqueda'])){
	$busqueda = $_GET['busqueda'];
}
if (isset($_POST['busqueda'])){
	$busqueda = $_POST['busqueda'];
}
if (isset($busqueda)){
	// arma las búsquedas según la cantidad de campos
	$buscampos = $buscampo1." LIKE '%".$busqueda."%' ";
	$matches   = $buscampo1;
	if(isset($buscampo2)){
		$buscampos.= "OR ".$buscampo2." LIKE '%".$busqueda."%' ";
		$matches.= ", ".$buscampo2;
	}
	//Cuento la cantidad de palabras
	$trozos=explode(" ",$busqueda);
	$numero=count($trozos);
	if ($numero==1) {
		//Si tengo una palabra
		$cadbusca="SELECT * FROM empleos_".$nombretab." WHERE (".$buscampos.") AND del = 0 ".$where_adicional.$where_combos." ORDER BY ".$orderby;
	}elseif ($numero>1){
		//Si tengo más de una palabra
		$palabra1 = current($trozos);
		$palabra2 = end($trozos);
		$cadbusca = "SELECT *, MATCH (".$matches.") AGAINST ('$palabra1' IN BOOLEAN MODE) as aprox FROM empleos_".$nombretab." WHERE MATCH (".$matches.") AGAINST ('$palabra2' IN BOOLEAN MODE) ".$where_adicional.$where_combos." AND del = 0 ORDER BY aprox DESC";
  	}
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$cadbusca="SELECT * FROM empleos_".$nombretab." WHERE id = $id AND del = 0".$where_combos;
	}
}else{
	$cadbusca="SELECT * FROM empleos_".$nombretab." WHERE 1 ".$where_adicional.$where_combos." AND del = 0 ORDER BY ".$orderby;
	$busqueda = '';
}
?>