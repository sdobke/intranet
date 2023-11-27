<?PHP
$sql_config = "SELECT * FROM ".$_SESSION['prefijo']."config WHERE parametro = 'password'";
$res_config = fullQuery($sql_config);
$row_config = mysqli_fetch_array($res_config);
$password   = $row_config['valor'];
$postpass   = $row_config['id'];
if ($_POST[$postpass] != $password){
	$nuevopass   = $_POST[$postpass];
	$passwordrep = $_POST['passwordrep'];
	$nuevopass   = md5($nuevopass);
	$sql_escribe = "UPDATE ".$_SESSION['prefijo']."config SET valor = '$nuevopass' WHERE id = ".$postpass;
	$res_escribe = fullQuery($sql_escribe);
}
	
$sql_config = "SELECT * FROM ".$_SESSION['prefijo']."config WHERE mostrar = 1 AND parametro != 'password'";
$res_config = fullQuery($sql_config);
while($row_config = mysqli_fetch_array($res_config)){
	$param_id  = $row_config['id'];
	$valor     = utf8_decode($_POST[$param_id]);
	$tipo      = $row_config['tipo'];
	$sql_upd = "UPDATE ".$_SESSION['prefijo']."config SET valor = '$valor' WHERE id = ".$param_id;
	$res_upd = fullQuery($sql_upd);
}