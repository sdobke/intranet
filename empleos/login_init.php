<?PHP
$ea = 'login_init.php';
$usa_ldap = cantidad('ldap');
$resultado_login = 0;

$link_destino_query = (isset($link_destino)) ? $link_destino : $_SERVER['PHP_SELF'];

$queryget = '';
if(isset($_GET)){
	$contget  = 1;
	foreach ($_GET as $key => $value) {
		if ($key != "login") {  // ignora este valor
			$queryget .= ($contget == 1) ? '?' : '&';
			$queryget .= $key."=".$value;
		}
		$contget++;
	}
}else{
	$queryget = '';
}
$headerloc = $link_destino_query.$queryget;
$formloc   = $_SERVER['PHP_SELF'].$queryget;

$msg_error = '';
if(isset($_POST['usuario_red'])){
	
	$usuario = 	$_POST['usuario_red'];
	if ($_POST['password']) {
		if($usa_ldap == 1){ // si usa LDAP
			$ldapsrv  = '172.31.1.16:389';
			$ldaprdn  = $usuario.'@alsea-netodp.local'; // ldap rdn or dn
			$ldappass = $_POST['password'];  // associated password
			$ldapconn = ldap_connect($ldapsrv);
			$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
			if ($ldapbind) {
				$resultado_login = 1;
			} else {
				$resultado_login = 2;
			}
		}else{ // si no usa ldap
			$password = md5(mysqli_real_escape_string($_POST['password']));
			$query = "SELECT * FROM intranet_empleados WHERE password = '$password' AND usuario = '$usuario'";
			$result = fullQuery($query) or die(mysqli_error());
			if(mysqli_num_rows($result) == 0){
				$resultado_login = 2;
			}else{
				$resultado_login = 1;
			}
			echo 'no LDAP';
		}
	}else{
		$msg_error = 'No ingres&oacute; contrase&ntilde;a.';
	}
}else{
	if(isset($es_backend) && $es_backend == 1 && !isset($_SESSION['usrfrontend'])){
		$msg_error = 'Usuario o contrase&ntilde;a incorrectos.';
	}
}

if($resultado_login == 1){
	//echo 'in';
	$query = "SELECT * FROM intranet_empleados WHERE usuario = '$usuario'";
	//echo $query;
	$result = fullQuery($query);
	$contar_usuarios = mysqli_num_rows($result);
	if($contar_usuarios == 0){
		$resultado_login = 2;
	}else{
		$row_usr = mysqli_fetch_array($result);
		$id_usuario = $row_usr['id'];
		//$accesos = $row_usr['accesos']+1;
		$area = ($row_usr['area'] != '') ? $row_usr['area'] : 0;
		$emp  = ($row_usr['empresa'] != '') ? $row_usr['empresa'] : 0;
		$_SESSION['usrfrontend'] = $id_usuario;
		$_SESSION['nombreusr'] = $row_usr['nombre']." ".$row_usr['apellido'];
		$_SESSION['tipousr'] = $row_usr['area'];
		$fechahora = date('Y-m-d');
		$sql_ulting = "UPDATE intranet_empleados SET ulting = '$fechahora' WHERE id = $id_usuario";
		//echo $sql_ulting;
		$res_ulting = fullQuery($sql_ulting);
		$_SESSION['empresa'] = $emp;
	}
}
if($resultado_login == 2){
	$msg_error = 'Contrase&ntilde;a incorrecta. Si la misma es correcta pero no pod&eacute;s ingresar, hac&eacute; <a href="login_error.php?usr='.$usuario.'"><strong>click ac&aacute;</strong></a>.';
}
?>