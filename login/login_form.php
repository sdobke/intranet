<div id="login_head" class="right">
  <form action="<?PHP echo (isset($formloc)) ? $formloc : 'index.php'; ?>" method="post" id="login_usr" name="login_usr">
    <div class="input-group input-group-sm mb-3">
      <input type="text" name="usuario_red" class="form-control" placeholder="Usuario">
    </div>
    <div class="input-group input-group-sm mb-3">
      <input type="password" name="password" id="verpassw" class="form-control" placeholder="Contraseña"> <i class="far fa-eye" id="togglePassword"></i>
    </div>
    <div>
      <button class="btn btn-danger">Ingresar <i class="far fa-caret-square-right"></i></button>
    </div>
  </form>
</div>
<script>
  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#verpassw');

  togglePassword.addEventListener('click', function(e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
  });
</script>