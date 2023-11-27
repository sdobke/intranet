<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
$salida = 0;
if(isset($_GET['out']) && $_GET['out'] == 1){
	session_destroy();
	$salida = 1;
}
$emp_nom = config('nombre');
$error = new Errores();
if (isset($_POST['password'])) {
	$password = mysqli_real_escape_string($_SESSION['conexion'],hash('sha512',$_POST['password']));
	if ($password == NULL) {
		$error->Guardar(1);
	}elseif(isset($_POST['usuario'])){
		$usuario = mysqli_real_escape_string($_SESSION['conexion'],$_POST['usuario']);
		$query = "SELECT valor FROM ".$_SESSION['prefijo']."config WHERE valor = '".$password."'";
		$resul = fullQuery($query);
		if(mysqli_num_rows($resul) == 1 && $usuario == 'admin'){
			$row = mysqli_fetch_assoc($resul);
			//$_SESSION['id_usr'] = 1;
			$_SESSION['backend'] = md5('Backend4dmn!');
		}else{
			$error->Guardar(1);
		}
	}
}
$cadmu = 0;
if(isset($_SESSION['usrfrontend'])){
	$sadmu = "SELECT * FROM intranet_usr_adm WHERE usuario = ".$_SESSION['usrfrontend']." AND del = 0";
	//echo $sadmu;
	$radmu = fullQuery($sadmu);
	$cadmu = mysqli_num_rows($radmu);
	if($cadmu > 0){
		$row_admu = mysqli_fetch_assoc($radmu);
		$_SESSION['backend'] = md5('Backend4dmn!');
	}
}
if($salida == 0){
	if( isset($_SESSION['backend']) && $_SESSION['backend'] == md5('Backend4dmn!') || $cadmu > 0){
		if($cadmu > 0){
			$_SESSION['backend'] = md5('Backend4dmn!');
			if($cadmu == 1 && $row_admu['tabla'] == 52){
				require_once("encuestas/inicio.php");
			}else{
				require_once("empty.php");
			}
		}else{
			$_SESSION["login"] = 'admin';
			include_once("inicio.php");
		}
	}else{
		include_once("login.php");
	}
}else{
	include_once("inc/salida.php");
}
?>