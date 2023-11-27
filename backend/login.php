<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es">
<!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- Bootstrap Stylesheet -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" media="screen">
	<!-- Uniform Stylesheet -->
	<link rel="stylesheet" href="plugins/uniform/css/uniform.default.css" media="screen">
	<!-- Plugin Stylsheets first to ease overrides -->
	<!-- End Plugin Stylesheets -->
	<!-- Main Layout Stylesheet -->
	<link rel="stylesheet" href="assets/css/fonts/icomoon/style.css" media="screen">
	<link rel="stylesheet" href="assets/css/custom.css" media="screen">
	<link rel="stylesheet" href="assets/css/login.css" media="screen">
	<link rel="stylesheet" href="plugins/zocial/zocial.css" media="screen">
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
	<title>Backend :: <?PHP echo txtcod(config('nombre')); ?></title>
	<script>
		function getCookie(c_name) {
			var c_value = document.cookie;
			var c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1) {
				c_start = c_value.indexOf(c_name + "=");
			}
			if (c_start == -1) {
				c_value = null;
			} else {
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = c_value.length;
				}
				c_value = unescape(c_value.substring(c_start, c_end));
			}
			return c_value;
		}

		function checkCookie() {
			var username = getCookie("username");
			if (username != null && username != "") {
				document.getElementById('input-username').value = username;
			}
			/*
else 
  {
  username=document.getElementById('input-username').value;
  if (username!=null && username!="")
    {
    setCookie("username",username,365);
    }
  }
  */
		}
	</script>
</head>

<body onload="checkCookie()">
	<div style="height:100px">&nbsp;
		<?PHP if ($error->existe == 1) { ?>
			<div id="login-wrap" style="height:30px; margin-bottom:20px; padding-top:1px;">
				<div align="center" style="padding:15px">
					<?PHP echo $error->mostrar(); ?>
				</div>
			</div>
		<?PHP } ?>
	</div>
	<div id="login-wrap">
		<div id="login-ribbon"><i class="icon-lock"></i></div>
		<div id="login-buttons">
			<div class="btn-wrap">
				<button type="button" class="btn btn-inverse" data-target="#login-form"><i class="icon-key"></i></button>
			</div>
			<!--			<div class="btn-wrap">
				<button type="button" class="btn btn-inverse" data-target="#register-form"><i class="icon-edit"></i></button>
			</div>-->
			<div class="btn-wrap">
				<button type="button" class="btn btn-inverse" data-target="#forget-form"><i class="icon-question-sign"></i></button>
			</div>
		</div>
		<div id="login-inner" class="login-inset">
			<div id="login-circle">
				<section id="login-form" class="login-inner-form" data-angle="0">
					<h1>Ingreso</h1>
					<form class="form-vertical" action="index.php" method="post">
						<div class="control-group-merged">
							<div class="control-group">
								<input type="text" placeholder="Usuario" name="usuario" id="input-username" class="big required">
							</div>
							<div class="control-group">
								<input type="password" placeholder="Password" name="password" id="input-password" class="big required">
							</div>
						</div>
						<div class="control-group">
							<label class="checkbox">
								<input type="checkbox" name="remember" class="uniform"> Recordarme
							</label>
						</div>
						<div class="form-actions">
							<button name="enviado" type="submit" class="btn btn-success btn-block btn-large">Ingresar</button>
						</div>
					</form>
				</section>
				<!--<section id="register-form" class="login-inner-form" data-angle="90">
					<h1>Register</h1>
					<form class="form-vertical" action="dashboard.html">
						<div class="control-group">
							<label class="control-label">Email</label>
							<div class="controls">
								<input type="text" name="Register[email]" class="required email">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Password</label>
							<div class="controls">
								<input type="password" name="Register[password]" class="required">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Fullname</label>
							<div class="controls">
								<input type="text" name="Register[fullname]" class="required">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Payment Method</label>
							<div class="controls">
								<select class="required" name="Register[payment]">
									<option>PayPal</option>
									<option>Credit Card</option>
								</select>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-danger btn-block btn-large">Register</button>
						</div>
					</form>
				</section>-->
				<section id="forget-form" class="login-inner-form" data-angle="180">
					<h1>Olvid&eacute; mi password</h1>
					<form class="form-vertical" action="dashboard.php" method="post">
						<div class="control-group">
							<div class="controls">
								<input type="text" name="Reset[email]" class="big required email" placeholder="Ingrese su e-mail...">
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-danger btn-block btn-large">Enviar</button>
						</div>
					</form>
				</section>
			</div>
		</div>
		<!-- <div id="login-social" class="login-inset">
	    	<button class="zocial facebook">Connect with Facebook</button>
	    	<button class="zocial twitter">Connect with Twitter</button>
	    </div> -->
	</div>


	<!-- Core Scripts -->
	<script src="assets/js/libs/jquery-1.8.3.min.js"></script>
	<script src="assets/js/libs/jquery.placeholder.min.js"></script>

	<!-- Login Script -->
	<script src="assets/js/login.js"></script>
	<!-- Validation -->
	<script src="plugins/validate/jquery.validate.min.js"></script>
	<!-- Uniform Script -->
	<script src="plugins/uniform/jquery.uniform.min.js"></script>
</body>

</html>