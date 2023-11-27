<?php
if ( isset($_POST['verificador']) && isset($_POST['password']) && isset($_POST['password2']) && $_POST['password'] == $_POST['password2'] ) {
  $codigo = dbVarClean($_POST['verificador']);
  $user_lost = $_SESSION['conexion']->prepare('SELECT id,email FROM intranet_empleados WHERE verificador = ? AND del = 0 AND activo = 1');
  $user_lost->bind_param('s', $codigo);
  $user_lost->execute();
  $l_res = $user_lost->get_result();
  $l_can = $l_res->num_rows;
  if ($l_can == 1) {
    $l_row = $l_res->fetch_assoc();
    $idveri = $l_row['id'];
    $newpass = hash('sha512', $_POST['password']);
    $sql_upd = "UPDATE intranet_empleados SET password = '".$newpass."', verificador = '', ulting = '".date("Y-m-d")."' WHERE id = ".$idveri;
    $res_upd = fullQuery($sql_upd);
    $msg_ok = "Nueva contraseña guardada. Por favor ingrese.";
  } else {
    $msg_error = "Código Incorrecto.";
  }
}else{
  $msg_error="Hubo un error al intentar guardar la nueva contraseña.";
  guardaLog('Error guardando password verificador = '.$_POST['verificador']);
}
