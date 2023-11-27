<?PHP
$result = fullQuery($cadbusca, 'prepara_paginador.php');
$contar = mysqli_num_rows($result);
if ($limit == 0){
	$limitsql = ' ';
	$limit = $contar;
}else{
	$limitsql = 'ok';
}
$total_de_pags = ceil($contar / $limit);
// FIN CUENTA DE REGISTROS Y LIMITE
$pag = getPost('pag');
if($pag > 0){
	$_SESSION['pagi'] = $pag;
}else{//Si la página está vacía
	if(isset($_SESSION['pagi']) && $_SESSION['pagi'] > 0){
		$pag = $_SESSION['pagi'];
	}else{
		$pag = '1';//Setea como página 1
	}
	if($pag == 'ultima'){
		$pag = $total_de_pags;
	}
}
$start = ($pag-1)*$limit;//setar la página de inicio
$start = round($start,0);//redondea
if ($limitsql == 'ok'){
	$limitsql = ' LIMIT '.$start.', '.$limit;
}					
$query=$cadbusca.$limitsql;
?>