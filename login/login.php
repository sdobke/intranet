<?php
include("login/form_olvide_post.php");
$mostrar_olvide = 1;
if (isset($_GET['codigo'])) {
  $incluir = 'form_restore';
  $mostrar_olvide = 0;
} else {
  $incluir = 'login_form';
}

if(isset($resultado_login) && $resultado_login == 3){ // Forzar reinicio de contraseña
  $incluir = 'login_forzar';
  $mostrar_olvide = 0;
}

if (isset($_POST['save_new_pass']) && isset($_POST['verificador']) && $_POST['verificador'] != '') {
  include("form_restore_save.php");
}
?>
<div id="home-login">
  <div id="login-logo" class="loginCentrado">
    <img src="/cliente/img/logo-login.png" />
  </div>
  <div id="login" class="face front loginCentrado"><?php include('login/'.$incluir . ".php"); ?></div>
  <?php
  if (isset($msg_error) && $msg_error != "") {
    echo '<div class="login_err">' . $msg_error . '</div>';
  }
  if (isset($msg_ok) && $msg_ok != "") {
    echo '<div class="login_ok">' . $msg_ok . '</div>';
  }
  if($mostrar_olvide == 1){include("login/form_olvide.php");}
  ?>

  <div id="mailAnim" class="loginCentrado">
    <h5>Enviando mail, por favor espere...</h5>
    <img src="assets/img/mail-send.gif" />
  </div>
</div>
<div id="by" class="firma-light"><a href="https://intranet.com.ar" target="_blank">Intranet</a> by <a href="https://fuxion.ar" target="_blank"><img src="https://fuxion.ar/internos/iso.png" />&nbsp;Fuxion</a></div>