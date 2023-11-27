<?PHP
//SEGURIDAD: Checkeo si el usuario se logueó

if( ( isset($_SESSION['minisitio_2']) && $_SESSION['minisitio_2'] == 'admin' ) || (isset($_SESSION["login"]) && $_SESSION["login"] == 'admin' ) ){
	
}else{
	echo 'Para ingresar, h&aacute;galo desde <a href="../index.php">Intranet</a> con su usuario.';
	die();
}
?>