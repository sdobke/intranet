<?PHP
function defineDoc($doc,$empresa){
	switch ($doc){
		case 1:
			$docu = 'organigrama-'.$empresa.'.pdf';
			break;
		case 2:
			$docu = 'conducta-'.$empresa.'-alsea.pdf';
			break;
		case 3:
			$docu = 'conducta-'.$empresa.'.pdf';
			break;
		case 4:
			$docu = 'tiendas-'.$empresa.'.xls';
			break;
	}
	return $docu;
}
$emp    = $_POST['empresa'];
$empdir = emp_dir($emp);
if ($emp == 1){
	$doc = $_POST['docalsea'];
}
if ($emp == 2){
	$doc = $_POST['docburger'];
}
if ($emp == 3){
	$doc = $_POST['docbucks'];
}
if ($emp == 4){
	$doc = $_POST['docchang'];
}
//echo $emp;
$documento = defineDoc($doc,$empdir);
$direct = "../img-paginas/".$empdir."/";
//chmod($direct, 0777);
$destino = $direct.$documento;
/*if (file_exists($destino)){
	chmod($destino, 0777);
}*/
if (is_uploaded_file($_FILES['archivo']['tmp_name'])){
	move_uploaded_file($_FILES['archivo']['tmp_name'], $destino);
}
?>