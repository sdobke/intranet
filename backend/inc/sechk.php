<?PHP
//SEGURIDAD: Checkeo si el usuario se logueó
if (!isset($_SESSION['backend'])) {
	echo '<a href="/backend/index.php">Debe ingresar al sistema</a>';
	die();
}elseif($_SESSION['backend'] != md5('Backend4dmn!')){
	die();
}
?>