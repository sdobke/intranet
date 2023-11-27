<?PHP

$pre_url = '';

$carpeta_url = explode("/", $_SERVER['PHP_SELF']);



$showMenu = true;

//require_once($pre_url . "login_init.php");



?>

<!-- DESLOGUEADO -->

<?PHP

$contraste = (isset($_GET['cod']) && $_GET['cod'] == 2) ? '_osc' : '';

if (!isset($_SESSION['usrfrontend'])) {

?>

	<div id="login_head" class="right">

		<form action="<?PHP echo (isset($formloc)) ? $formloc : 'index.php'; ?>" method="post" id="login_usr" name="login_usr">

			<div class="input-group input-group-sm mb-3">

				<input type="text" name="usuario_red" class="form-control" placeholder="Usuario">

				<input type="text" name="password" class="form-control" placeholder="Contraseña">

				<button class="btn btn-danger">Ingresar <i class="far fa-caret-square-right"></i></button>

			</div>

		</form>

	</div>

<?PHP } else { ?>



	<!-- LOGUEADO -->
<div class="dropdown">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle dropdown-menu-right" data-bs-toggle="dropdown" data-bs-theme="dark" aria-expanded="false">
            
			 
			  
			  <?PHP
	//FOTO USUARIO
					$fotousuario = '/cliente/img/none.png';
					if (isset($_SESSION['usrfrontend']) && file_exists($pre_url . 'cliente/fotos/' . $_SESSION['usrfrontend'] . '.jpg')) {
						$fotousuario = $pre_url . 'cliente/fotos/' . $_SESSION['usrfrontend'] . '.jpg';
					} ?>
			  <img src="<?PHP echo $fotousuario; ?>" alt="mdo" width="40" height="40" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small" style="">
			    <li><strong class="dropdown-item">
					<?PHP //NOMBRE USUARIO
	echo (isset($_SESSION['nombreusr'])) ? txtcod($_SESSION['nombreusr']) : ''; ?>
					</strong></li>
			  
			  <?PHP
					$sql_usra = "SELECT * FROM intranet_usr_adm WHERE usuario = " . $_SESSION['usrfrontend'] . " AND del = 0";
					$res_usra = fullQuery($sql_usra);
					$con_usra = mysqli_num_rows($res_usra);
					if ($con_usra > 0) {
						echo "<li><a class='dropdown-item' href='" . $pre_url . "backend/index.php'>Administrar secciones</a><br />";
					}
					?>
			              <li><hr class="dropdown-divider"></li>

			  <li><a class="dropdown-item" href="<?php echo $pre_url; ?>out.php"><i class="bi bi-box-arrow-right"></i>
 Salir</a></li>
			  
			  
			  
           
            
          </ul>
        </div>


	

<?PHP }

//echo '<pre>';print_r($_SESSION);echo '</pre>';

?>