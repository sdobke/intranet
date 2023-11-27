<?PHP 
$tipo   = 12;
$nombre = obtenerNombre($tipo);
$error    = '';
$fecha    = $_POST['fecha'];
$edicion  = (isset($_POST['edicion'])) ? $_POST['edicion'] : 0;

// Tapita
$carpeta  = "../".config('carp_edimp')."/";
/*
if (is_uploaded_file($_FILES['pdf']['tmp_name'])){
	$url_link   = $carpeta."bk_".$id.".pdf";
	move_uploaded_file($_FILES['pdf']['tmp_name'], $url_link);
}
*/
if(is_uploaded_file($_FILES['imagenbk']['tmp_name'])){
	$url_link  = $carpeta."imagen_".$id.".jpg";
	$fotow  = cantidad('fotow');
	$fotoh  = cantidad('fotoh'); // ancho y alto límite de las fotos
	subirImagenSinDB('imagenbk', $url_link, $fotow, $fotoh);
}
$url_link   = $carpeta.config('edimp')."_".$id.".pdf";
move_uploaded_file($_FILES['pdf']['tmp_name'], $url_link);
/* $query  = "INSERT INTO intranet_".$nombre." (id, fecha, edicion) VALUES ($id, '$fecha', $edicion) ";
//echo $query;
$result = fullQuery($query);*/
if ($error == ''){
	$msg = 0;
}else{
	$msg = 1;
}?>
<?PHP //header("Location: ".$nombre.".php?error=".$msg);?>