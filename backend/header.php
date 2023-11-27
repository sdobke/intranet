<?php //print_r($_SESSION);
?>
<header id="header" class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<div class="brand-wrap pull-left">
				<div class="brand-img">
					<img src="img/logo.png" alt="<?PHP echo txtcod(config('nombre')); ?>">
				</div>
			</div>
			<div id="header-right" class="clearfix">
				<div id="nav-toggle" data-toggle="collapse" data-target="#navigation" class="collapsed">
					<i class="icon-caret-down"></i>
				</div>
				<?PHP
				if (config('alertas') == 1 && $_SESSION['backend'] == md5('Backend4dmn!') ) {
					include_once("inc/alertas.php");
				}

				if ( $_SESSION['backend'] == md5('Backend4dmn!') ) {
					$nomusrbk = "Admin";
				} else {
					$nomusrbk = (isset($_SESSION['nombreusr'])) ? $_SESSION['nombreusr'] : '';
				}
				?>
				<div id="header-functions" class="pull-right">
					<div id="user-info" class="clearfix">
						<span class="info">
							Bienvenido/a <span class="name"><?PHP echo $nomusrbk; ?></span>
							<br /><a href="../index.php">Volver a la Intranet</a>
						</span>
					</div>
					<div id="logout-ribbon">
						<a href="index.php?out=1"><i class="icon-off"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>