<?PHP
$error_msg = '';
if(isset($_GET['error'])){
	$error = $_GET['error'];
	switch ($error){
		case 1:
			$mensaje = 'Hubo un problema con una query de base de datos.';
			break;
		default:
			$mensaje = 'La informaci&oacute;n fue agregada.';
			break;
	}
	if ($error > 0){echo '<p class="error"><b>Por favor corrija el siguiente error:</b></p>';}
	$error_msg = '<span class="error">'.$mensaje.'</span><br /><br />';
}
?>