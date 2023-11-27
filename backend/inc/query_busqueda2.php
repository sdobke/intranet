<?PHP
$ea = 'Query_busqueda2.php';
$where_adicional = (isset($otro_query) && $otro_query!= '') ? $otro_query : '';
$where_adicional.= ' AND inov.del = 0 ';
$orden_query  = (isset($order_by) && $order_by != '') ? $order_by : 'id DESC';
$restriccion = (isset($_SESSION['tipoemp']) || (isset($backend) && $backend != 1)) ? $_SESSION['tipoemp'] : 0;
$inner_join = '';
$where_restriccion = '';
$inner_restriccion = '';
//echo '<br>Id: '.$_SESSION['id_usr'];
if( 
	( !isset($backend) || ( isset($backend) && $backend != 1) ) 
		&& 
		( isset($usarest) && $usarest == 1 
			&& 
			( !isset($_SESSION['id_usr'] ) || 
				( isset($_SESSION['id_usr']) && $_SESSION['id_usr'] <> 1 ) 
			) 
		)
	){
	$where_restriccion = " AND (il.tipo = ".$tipo.") AND FIND_IN_SET(" . $restriccion . ",il.part) ";
	$inner_restriccion = "INNER JOIN ".$_SESSION['prefijo']."link AS il ON inov.id = il.item";
}
//echo '<br>Inner: '.$inner_restriccion;
$where_combos = '';
foreach ($_POST as $key => $value) { // Levanta todos los POST
	$nom_post = explode('bus_',$key);
	$nom_post = end($nom_post);
	//echo '<br />post: '.$key.' valor: '.$value;
    $query_bus = "SELECT * FROM ".$_SESSION['prefijo']."tablas WHERE nombre = '".$nom_post."'"; // Busca en las tablas si existe una que se llame como la variable post
	//echo '<br>'.$query_bus;
	$resul_bus = fullQuery($query_bus);
	$conta_bus = mysqli_num_rows($resul_bus);
	if($conta_bus > 0){ // Si existe la tabla
		if($value > 0){ // Si el valor es mayor a cero
			$row_bus = mysqli_fetch_array($resul_bus);
			$tab_bus = $row_bus['id'];
			$sql_busca_nom = "SELECT campo FROM ".$_SESSION['prefijo']."combos WHERE combo = ".$tab_bus; // Busca el nombre del campo en la tabla combos con el ID de tabla
			//echo '<br>'.$sql_busca_nom;
			$res_busca_nom = fullQuery($sql_busca_nom);
			$row_busca_nom = mysqli_fetch_array($res_busca_nom);
			$nom_post_combo = $row_busca_nom['campo'];
			$where_combos.= " AND ".$nom_post_combo." = ".$value;
		}
	}
}
if($tipodet == 'novedades' && isset($backend) && $secc > 0){
	$where_adicional.= " AND inov.seccion = ".$secc;
}
$where_adicional.= $where_combos;
if(isset($backend)){ // Solo si es el backend
	//$where_adicional.= ' AND inov.del = 0 ';
	//if($tipodet == 'novedades'){$orden_query = ' orden, '.$orden_query;}
}else{ // Solo si no es el backend
	if($activable == 1){
		$where_adicional.= ' AND activo = 1 ';
	}
	if($usafecha == 2){
		$where_adicional.= ' AND fecha >= DATE(NOW()) ';
	}
}
$bfec = ($usafecha > 0) ? ', inov.fecha AS fecha' : '';
$btex = ($usatexto == 1) ? ', inov.texto AS texto' : '';
$btit = (!is_numeric($tipotit)) ? ', inov.'.$tipotit.' AS '.$tipotit : '';
$bhor = ($usahora  == 1) ? ', inov.hora AS hora' : '';
$bcol = ($usacolor == 1) ? ', inov.color AS color' : '';
$bact = ($activable == 1) ? ', inov.activo AS activo' : '';
$campos = 'inov.id AS id'.$bfec.$btex.$btit.$bhor.$bcol.$bact;
switch($tipo){
	case 34: // Políticas
		$campos.= ' , inov.tipoarc AS tipoarc, inov.area AS area, inov.empresa AS empresa, inov.link AS link, inov.peso AS peso, inov.tipodoc AS tipodoc';
		//$inner_join = 'INNER JOIN emp_cargos AS c ON c.cod = inov.cargo';
		break;
	case 3: // Documentos Útiles
		$campos.= ' , inov.tipoarc AS tipoarc, inov.area AS area, inov.empresa AS empresa, inov.link AS link, inov.peso AS peso, inov.tipodoc AS tipodoc, inov.url AS url';
		break;
	case 7: // novedades
		$campos.= ' ,inov.video AS video';
		$inner_join = 'INNER JOIN '.$_SESSION["prefijo"].'secciones AS secc ON inov.seccion = secc.id';
		break;
	case 15: // concurso de fotos
		$campos.= ' ,inov.votactiva AS votactiva';
		break;
	case 18: // Clasificados
		$campos.= ' , inov.usuario AS usuario';
		break;
	case 14: // Recomendados
		$campos.= ' , inov.usuario AS usuario, inov.votos AS votos, inov.promedio AS prom, sal.nombre AS salida';
		$inner_join = 'INNER JOIN '.$_SESSION["prefijo"].'salidas AS sal ON inov.salida = sal.id';
		break;
	case 17: // Buzón de sugerencias
		$campos.= ' , inov.sugerencia AS sugerencia, inov.tema AS tema';
		break;
	case 11: // Encuestas
		$campos.= ' , inov.pregunta AS pregunta';
		break;
	case 4: // Empleados
		$campos.= ' , inov.apellido AS apellido, inov.area AS area, inov.empresa AS empresa, inov.interno AS interno, inov.fechanac AS fechanac, inov.email AS email ';
		//$inner_join = 'INNER JOIN emp_cargos AS c ON c.cod = inov.cargo';
		break;
}

$busqueda = '';
if (isset($_GET['busqueda'])){
	$busqueda = $_GET['busqueda'];
}
if (isset($_POST['busqueda'])){
	$busqueda = $_POST['busqueda'];
}
	$trozos=explode(" ",$busqueda);
	$numero=count($trozos);
	if ($numero>1) {
		$busqueda_todo = explode(" ",$busqueda);
		$busqueda = '%';
		foreach($busqueda_todo as $buspartes){
			$busqueda.= $buspartes.'%';
		}
	}
if (isset($busqueda) && $busqueda != ''){
	$_SESSION['pagi'] = 0;
	$sql_buscampos = '';
	$matches = '';
	// arma las búsquedas según la cantidad de campos
	$bus_campos = explode(',',$buscampos);
	$num_campos = count($bus_campos);
	$contar_campos = 1;
	foreach($bus_campos as $campo){
		if($contar_campos > 1){
			$sql_buscampos.= ' OR ';
		}
		$sql_buscampos.= "inov.".$campo." LIKE '%".$busqueda."%' ";
		if($contar_campos > 1){
			$matches.= ', ';
		}
		$matches.= "inov.".$campo;
		$contar_campos++;
	}
	
	//Cuento la cantidad de palabras
	if ($numero==1) {
		//Si tengo una palabra
		
		$cadbusca = "SELECT ".$campos." 
						FROM ".$_SESSION['prefijo'].$nombretab." AS inov 
							".$inner_restriccion." 
							".$inner_join."
								WHERE 1 ".$where_restriccion." AND (".$sql_buscampos.")".$where_adicional."
					GROUP BY inov.id ORDER BY ".$orden_query;
		
		
	}else{
		//Si tengo más de una palabra
		$palabra1 = current($trozos);
		$palabra2 = end($trozos);
		
		$cadbusca = "SELECT ".$campos.",
						MATCH (".$matches.") AGAINST ('".$busqueda."' IN BOOLEAN MODE) AS aprox 
						FROM ".$_SESSION['prefijo'].$nombretab." AS inov
							".$inner_restriccion." 
							".$inner_join."
								WHERE 1 ".$where_restriccion." AND MATCH (".$matches.") AGAINST ('".$busqueda."' IN BOOLEAN MODE) 
									AND ".$sql_buscampos.$where_adicional."
					GROUP BY inov.id ORDER BY aprox DESC";
		
  	}
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$cadbusca="SELECT * FROM ".$_SESSION['prefijo'].$nombretab." WHERE id = $id";
	}
}else{
	$cadbusca = "SELECT ".$campos."
					FROM ".$_SESSION['prefijo'].$nombretab." AS inov 
					".$inner_restriccion." 
					".$inner_join."
						WHERE 1 ". $where_restriccion.$where_adicional."
				GROUP BY inov.id ORDER BY ".$orden_query;
	$busqueda = '';
}
?>