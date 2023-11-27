<?PHP
$result = fullQuery($cadbusca);
$contar = mysqli_num_rows($result);
if ($limit == 0){
	$limitsql = ' ';
	$limit = $contar;
}else{
	$limitsql = 'ok';
}		
// FIN CUENTA DE REGISTROS Y LIMITE
if (isset($_GET['pag'])){
	$pag = $_GET['pag'];//Toma la página
}
if(empty($pag)){//Si la página está vacía
	$pag = '1';//Setea como página 1
}
$start = ($pag-1)*$limit;//setar la página de inicio
$start = round($start,0);//redondea
if ($limitsql == 'ok'){
	$limitsql = ' LIMIT '.$start.', '.$limit;
}					
$query=$cadbusca.$limitsql;
?>