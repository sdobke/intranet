<?PHP
//$query_paginador  = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_listado";
$query_paginador  = $query_todo;

$result_paginador = fullQuery($query_paginador);
$contar_paginador = mysqli_num_rows($result_paginador);
//Cantidad de resultados por página
$limit  = $items;
if ($limit == 0){
	$limitsql = ' ';
	$limit = $contar_paginador;
}else{
	$limitsql = 'ok';
}
// FIN CUENTA DE REGISTROS Y LIMITE
if (isset($_GET['page'])){
	$page = $_GET['page'];//Toma la página
}
if(empty($page)){//Si la página está vacía
	$page = '1';//Setea como página 1
}
$start = ($page-1)*$limit;//setar la página de inicio
$start = round($start,0);//redondea
if ($limitsql == 'ok'){
	$limitsql = ' LIMIT '.$start.', '.$limit;
}	