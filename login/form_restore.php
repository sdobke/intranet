<?php
if (isset($_GET['codigo'])) {
  $codigo = dbVarClean($_GET['codigo']);
  $user_lost = $_SESSION['conexion']->prepare('SELECT id,email FROM intranet_empleados WHERE verificador = ? AND del = 0 AND activo = 1');
  $user_lost->bind_param('s', $codigo);
  $user_lost->execute();
  $l_res = $user_lost->get_result();
  $l_can = $l_res->num_rows;
  if ($l_can == 1) {
    $l_row = $l_res->fetch_assoc();
    $idveri = $l_row['id'];
?>
    <div id="login_head" class="right">
      <form action="/index.html" method="post" id="guarda_pass" name="save_pass">
        <div class="input-group input-group-sm mb-3">
          <input type="password" id="pass1" name="password" class="form-control" placeholder="Contraseña">
          <input type="password" id="pass2" name="password2" class="form-control" placeholder="Repita Contraseña">
          <input type="hidden" name="verificador" value="<?php echo $codigo;?>" />
          <input type="hidden" name="save_new_pass" value="1"/>
          <button class="btn btn-danger">Guardar <i class="far fa-caret-square-right"></i></button>
        </div>
      </form>
    </div>
    <div id="restore_error"></div>
<?php

  } else {
    $msg_error = "Código Incorrecto.";
  }
}