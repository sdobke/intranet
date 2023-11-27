<?PHP
$_SESSION['usr_mini'] = '';

if( (isset($_GET['gerlog']) && $_GET['gerlog'] == 1)){
	$inger = 1;
	$txtit = 'Ingreso para Gerentes';
}else{
	$inger = 0;
	$txtit = 'Minisitios';
}
if(isset($_POST['password'])){
	$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
	$password = $_POST['password'];
	$sql_usr = "SELECT * FROM intranet_locales_usr AS usr
					INNER JOIN intranet_locales_emp AS emp ON emp.legajo = usr.legajo
					WHERE usuario = '".$usuario."' AND password = '".$password."' AND emp.empresa = 2";
	$res_usr = fullQuery($sql_usr);
	$con_usr = mysqli_num_rows($res_usr);
	if($con_usr > 0){
		$row_usr = mysqli_fetch_array($res_usr);
		$_SESSION['mini_ger'] = $row_usr['legajo'];
		$_SESSION['mini_emp'] = $row_usr['empresa'];
	}else{
		$mensaje = 'No coinciden el usuario y la contraseña.';
	}
}
if(isset($_SESSION['mini_ger']) && $_SESSION['mini_ger'] > 0){ // Login de usuario gerente
	$usrger = 1;
	$inger = 1;
}else{
	$usrger = 0;
}
if (isset($_SESSION['tipousr']) && $_SESSION['tipousr'] >= 2) {
	$_SESSION['usr_mini'] = 'user';
	$_SESSION['mini_emp'] = $_SESSION['empresa'];
}
?>