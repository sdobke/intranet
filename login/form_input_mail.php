<?php
if(!isset($mailPlaceholder)){
  $mailPlaceholder = 'email';
}
?>
<div class="olvide">
  <form class="form" action="/" method="post">
    <div class="input-group input-group-sm mb-3">
      <input type="text" name="email_olvido" class="form-control" placeholder="<?php echo $mailPlaceholder;?>">
      <button id="mailsend" class="btn btn-danger">Enviar <i class="fas fa-key"></i></button>
    </div>
  </form>
</div>