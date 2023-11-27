<?PHP
if(isset($_POST['anonima'])){
	$nombre = $email = 'anonimo';
}else{
	$nombre = $_POST['nombre'];
	$email = $_POST['email'];
}
$comentario = $_POST['comentario'];
$idcom = nuevoID('sugerencias_web');
$sqlc = "INSERT INTO intranet_sugerencias_web (id,nombre,email,texto) VALUES (".$idcom.",'".$nombre."','".$email."','".$comentario."')";
$resc = fullQuery($sqlc);
$respuesta = 'Gracias por tu comentario. Este nos ayuda a mejorar la intranet.';
?>