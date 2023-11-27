<div class="card card-flip">
  <div class="card-front">
    <div class="olvide">
      <button class="btn btn-danger rotate-btn" data-card="card-1">Olvidé mi Contraseña <i class="fas fa-question-circle"></i></button>
    </div>
  </div>
  <div class="card-back">
    <?php 
    $mailPlaceholder = 'email registrado';
    include("login/form_input_mail.php");
    ?>
  </div>
</div>