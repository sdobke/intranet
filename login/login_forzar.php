<?php
if (isset($_POST['usuario_red'])) {
?>
  <div class="olvide">
    <p>Por su seguridad, solicitamos que modifique su contraseña.</p>
    <form class="form" action="/" method="post">
      <div class="input-group input-group-sm mb-3">
        <input type="text" name="email_forzar" class="form-control" placeholder="Ingrese su email">
        <input type="hidden" name="usuario" value="<?php echo $_POST['usuario_red']; ?>" />
        <button id="mailsend" class="btn btn-danger">Enviar <i class="fas fa-key"></i></button>
      </div>
    </form>
  </div>
<?php }
