<?PHP
$hacer_login = 0;

$Testuserlogin = rememberMe();
//echo 'test: '.$Testuserlogin;
if ($Testuserlogin == 0) {
	unset($_SESSION['usrfrontend']);
}

if (isset($_SESSION['usrfrontend']) || isset($_POST['usuario_red']) || (isset($es_home) && $es_home == 1)) {
	if (!isset($_SESSION['id_usr'])) {
		$_SESSION['id_usr'] = 0;
	}
	$modo_test = 0;
	$resultado_login = 0;
	$link_destino_query = (isset($link_destino)) ? $link_destino : $_SERVER['PHP_SELF'];
	$queryget = '';
	if (isset($_GET)) {
		$contget  = 1;
		foreach ($_GET as $key => $value) {
			if ($key != "login") {  // ignora este valor
				$queryget .= ($contget == 1) ? '?' : '&';
				$queryget .= $key . "=" . $value;
			}
			$contget++;
		}
	} else {
		$queryget = '';
	}
	$headerloc = $link_destino_query . $queryget;
	$formloc   = $_SERVER['PHP_SELF'] . $queryget;
	$msg_error = '';
	//echo '<pre>';print_r($_REQUEST);echo '</pre>';
	if ($modo_test == 1) {
		echo '<br />Ingresando datos';
	}
	if (isset($_POST['usuario_red'])) {
		if ($modo_test == 1) {
			echo '<br />Usuario cargado';
		}
		$usuario = $_POST['usuario_red'];
		if ($_POST['password']) {
			if ($modo_test == 1) {
				echo '<br />Password cargada';
			}
			if ($modo_test == 1 && $usuario == 'test' && hash('sha512', $_POST['password']) == '098f6bcd4621d373cade4e832627b4f6') {
				//$resultado_login = 3;
				$_SESSION['tipousr'] = 1;
				$_SESSION['usrfrontend'] = 868;
				$_SESSION['nombreusr'] = 'Test Oficinas';
				$_SESSION['empresa'] = 1;
			} else {
				if ($modo_test == 1) {
					echo '<br />Usa LDAP';
				}
				if ($usa_ldap == 1) { // si usa LDAP
					$ldapsrv  = '172.31.1.16:389';
					$ldaprdn  = $usuario . '@red.local'; // ldap rdn or dn
					$ldappass = $_POST['password'];  // associated password
					$ldapconn = ldap_connect($ldapsrv);
					$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
					if ($ldapbind) {
						$resultado_login = 1; // ingresó
					} else {
						$resultado_login = 2; // no ingresó
					}
				} else { // Si no usa LDAP
					if ($modo_test == 1 || config("ldap") == 0) {
						echo '<br />No usa LDAP';
					}
					// Buscamos en la tabla de usuarios
					$password = hash('sha512', $_POST['password']);
					//echo $password;
					$query = "SELECT id,ulting FROM " . $_SESSION['prefijo'] . "empleados
								WHERE password = '$password' AND usuario = '$usuario' AND activo > 0 AND del = 0";
					$result = fullQuery($query);
					$row_res = mysqli_fetch_assoc($result);
					if (mysqli_num_rows($result) > 0) {
						if ($modo_test == 1) {
							echo '<br />Usuario Encontrado';
						}
						$resultado_login = 1;
						if ($row_res['ulting'] == '0000-00-00') {
							$resultado_login = 3; // Reset obligatorio de contraseña
						}
					} else { // Si no está en usuarios buscamos en locales
						if ($modo_test == 1) {
							echo '<br />No se encontró usuario';
						}
						$resultado_login = 2;
					}
				}
			}
		} else {
			if ($modo_test == 1) {
				echo '<br />No se ingresó contraseña.';
			}
			$msg_error = 'No ingres&oacute; contrase&ntilde;a.';
		}
	} else {
		if (isset($es_backend) && $es_backend == 1 && !isset($_SESSION['usrfrontend'])) {
			if ($modo_test == 1) {
				echo '<br />Usuario o contrase&ntilde;a incorrectos';
			}
			$msg_error = 'Usuario o contrase&ntilde;a incorrectos.';
		}
		if ($modo_test == 1) {
			echo '<br />No se ingresó usuario';
		}
	}
	if ($resultado_login == 1) {
		if ($modo_test == 1) {
			echo '<br />Ingreso OK.';
		}
		//echo 'in';
		$query = "SELECT * FROM " . $_SESSION['prefijo'] . "empleados AS emp
					WHERE usuario = '" . $usuario . "' AND activo > 0 AND del = 0 ORDER BY id DESC LIMIT 1";
		$result = fullQuery($query);
		$contar_usuarios = mysqli_num_rows($result);
		if ($contar_usuarios == 0) { // Si no encontró el usuario entre los empleados
			if ($usa_ldap == 1) {
				$query_loc = "SELECT empresa, cod, interior AS inte FROM " . $_SESSION['prefijo'] . "loctie WHERE usuario = '" . $usuario . "' AND activo > 0 AND del = 0";
			}
			$res_loc = fullQuery($query_loc);
			$con_loc = mysqli_num_rows($res_loc);
			if ($con_loc == 1) {
				$row_loc = mysqli_fetch_assoc($res_loc);
				$_SESSION['usrfrontend'] = $row_loc['cod'];
				$_SESSION['nombreusr'] = $usuario;
				$_SESSION['empresa'] = $emp = $row_loc['empresa'];
				//$_SESSION['tipoemp'] = $_SESSION['tipousr'] = $row_usr['area'];
			} else {
				$resultado_login = 2;
			}
		} else { // Si el usuario está en la tabla empleados

			$row_usr = mysqli_fetch_array($result);
			$id_usuario = $row_usr['id'];
			onLogin($id_usuario);
			//$accesos = $row_usr['accesos']+1;
			//$area = ($row_usr['area'] != '') ? $row_usr['area'] : 0;
			$area = 0;

			$emp  = ($row_usr['empresa'] != '') ? $row_usr['empresa'] : 0;
			$_SESSION['usrfrontend'] = $id_usuario;
			$_SESSION['nombreusr'] = $row_usr['nombre'] . " " . $row_usr['apellido'];
			$fechahora = date('Y-m-d');
			$sql_ulting = "UPDATE " . $_SESSION['prefijo'] . "empleados SET ulting = '" . $fechahora . "' WHERE id = $id_usuario";
			//echo $sql_ulting;
			$res_ulting = fullQuery($sql_ulting);
			$_SESSION['empresa'] = $emp;
			//$_SESSION['tipoemp'] = $_SESSION['tipousr'] = $area;

			$sql_restric = '';


			$sqlar = "SELECT ia.id FROM intranet_areas ia 
			INNER JOIN intranet_empleados_areas iea ON iea.area = ia.id
			INNER JOIN intranet_empleados ie ON ie.id = iea.empleado
			WHERE ie.id = " . $id_usuario;
			$resar = fullQuery($sqlar);
			$empareas = '';
			if (mysqli_num_rows($resar) > 0) {
				$sql_restric = " AND ( ";
				$cont_restr = 1;
				while ($rowar = mysqli_fetch_array($resar)) {
					if ($cont_restr > 1) {
						$sql_restric .= ' OR ';
					}
					$sql_restric .= " FIND_IN_SET(" . $rowar['id'] . ",il.part) ";
					$cont_restr++;
				}
				$sql_restric .= " ) ";
			} else {
				$sql_restric .= " AND FIND_IN_SET(9999,il.part) ";
			}
		}
	}
	if ($resultado_login == 2) {
		$msg_error = 'La contrase&ntilde;a es incorrecta o el usuario est&aacute; inactivo.';
		//$msg_error.= ' Si no pod&eacute;s ingresar, hac&eacute; <a href="login/error.php?usr='.$usuario.'"><strong>click ac&aacute;</strong></a>.';
	}
	if ($resultado_login == 3) {
		$hacer_login = 1;
	}
	$user_emp = (isset($_SESSION['empresa'])) ? $_SESSION['empresa'] : 4;
	if (isset($_SESSION['mini_ger']) && ($_SESSION['mini_ger'] > 0)) {
		$_SESSION['usr_mini'] = 'user';
	}
} else { // No hay ningún tipo de login
	$hacer_login = 1;
}


$login = 0;
$linkPopup = '';
$userlogin = rememberMe();

if ($userlogin > 0) {
	if (!isset($_SESSION['usrfrontend'])) {
		$sql_li = "SELECT * FROM " . $_SESSION['prefijo'] . "empleados AS emp
					WHERE id = " . $userlogin . " AND activo = 1 AND del = 0";
		$res_li = fullQuery($sql_li);
		$con_li = mysqli_num_rows($res_li);
		if ($con_li == 1) {
			$row_usr = mysqli_fetch_array($res_li);
			$id_usuario = $row_usr['id'];
			//$accesos = $row_usr['accesos']+1;
			$area = ($row_usr['area'] != '') ? $row_usr['area'] : 0;
			$emp  = ($row_usr['empresa'] != '') ? $row_usr['empresa'] : 0;
			$_SESSION['usrfrontend'] = $id_usuario;
			$_SESSION['nombreusr'] = $row_usr['nombre'] . " " . $row_usr['apellido'];
			$fechahora = date('Y-m-d');
			$sql_ulting = "UPDATE " . $_SESSION['prefijo'] . "empleados SET ulting = '" . $fechahora . "' WHERE id = $id_usuario";
			//echo $sql_ulting;
			$res_ulting = fullQuery($sql_ulting);
			$_SESSION['empresa'] = $emp;
			$_SESSION['tipoemp'] = $_SESSION['tipousr'] = $row_usr['area'];
			$empareas = explode(',', $_SESSION['tipoemp']);
			$sql_restric = '';
			$sql_restric = " AND ( ";
			$cont_restr = 1;
			foreach ($empareas as $emparea) {
				if ($cont_restr > 1) {
					$sql_restric .= ' OR ';
				}
				$sql_restric .= " FIND_IN_SET(" . $emparea . ",il.part) ";
				$cont_restr++;
			}
			$sql_restric .= " ) ";
		}
	}
}
