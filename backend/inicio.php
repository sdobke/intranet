<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es">
<!--<![endif]-->

<head>
	<script>
		function setCookie(c_name, value, exdays) {
			var exdate = new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
			document.cookie = c_name + "=" + c_value;
		}

		function guardarCookie(valor) {
			username = valor;
			if (username != null && username != "") {
				setCookie("username", username, 365);
			}
		}
	</script>
	<?PHP include("inc/head.php"); ?>
	<?PHP
	if (isset($_POST['remember'])) {
		$valorcookie = "guardarCookie('" . $_POST['usuario'] . "')";
		$bodyload = 'onload="javascript:' . $valorcookie . '"';
	} else {
		$bodyload = '';
	}
	?>
</head>

<body data-show-sidebar-toggle-button="true" data-fixed-sidebar="false" <?PHP echo $bodyload; ?>>
	<?PHP
	if (isset($_POST['inicio_enviado'])) {
		include('inc/inicio_post.php');
	}
	?>
	<div id="customizer">
		<div id="showButton"><i class="icon-cogs"></i></div>
		<div id="layoutMode">
			<label class="checkbox"><input type="checkbox" class="uniform" name="layout-mode" value="boxed"> En Caja</label>
		</div>
	</div>
	<!--<div id="style-changer"><a href="../simple/index.html"></a></div>-->
	<div id="wrapper">
		<?PHP include_once("header.php"); ?>

		<div id="content-wrap">
			<div id="content">
				<div id="content-outer">
					<div id="content-inner">
						<?PHP include_once("menu.php"); ?>
						<div id="sidebar-separator"></div>

						<section id="main" class="clearfix">
							<div id="main-header" class="page-header">
								<ul class="breadcrumb">
									<li>
										<i class="icon-home"></i>Backend
										<span class="divider">&raquo;</span>
									</li>
									<li>
										<a href="index.php">Backend</a>
									</li>
								</ul>

								<h1 id="main-heading">
									Backend <span>Configuraci&oacute;n del sistema.</span>
								</h1>
							</div>
							<div id="main-content">
								<div class="row-fluid">
									<div class="span12 widget">
										<div class="widget-header">
											<span class="title"><i class="icos-cog-3"></i> Configuraci&oacute;n del backend</span>
										</div>
										<div class="widget-content form-container">
											<form class="form-horizontal" method="post" action="index.php" id="inicio">
												<?PHP
												$sql_configs = "SELECT * FROM " . $_SESSION['prefijo'] . "config WHERE mostrar = '1' ORDER BY id";
												$res_configs = fullQuery($sql_configs);
												while ($row_configs = mysqli_fetch_array($res_configs)) {
													$parametro = $row_configs['parametro'];
													$param_id  = $row_configs['id'];
													$valor     = txtcod($row_configs['valor']);
													$detalle   = txtcod($row_configs['detalle']);
													$tipo = ($parametro == 'password') ? 'password' : 'text';

													echo '<div class="control-group">
                                                    <label class="control-label" for="' . $param_id . '">' . $detalle . '</label>';
													echo '<div class="controls">';

													if ($row_configs['tipo'] == 5) {
														$actno = ($valor == 0) ? 'checked="checked"' : '';
														$actsi = ($valor == 1) ? 'checked="checked"' : '';
														echo 'NO <input class="uniform" name="' . $param_id . '" id="' . $param_id . '" type="radio" value="0" ' . $actno . ' />';
														echo 'SI <input class="uniform" name="' . $param_id . '" id="' . $param_id . '" type="radio" value="1" ' . $actsi . ' />';
													} else {
														if ($parametro == 'password') {
															$passval = $valor;
														}
														echo '<input name="' . $param_id . '" type="' . $tipo . '" class="span12" id="' . $param_id . '" value="' . $valor . '" />';
													}
													if ($parametro == 'password') {
														echo '</div>
														</div>';
														echo '<div class="control-group">
															<label class="control-label" for="passwordrep">Vuelva a ingresar la contrase&ntilde;a para modificarla</label>';
														echo '<div class="controls">';
														echo '<input name="passwordrep" type="password" class="span12" id="passwordrep" value="' . $passval . '" />';
													}
													echo '</div>
													</div>';
												}
												?>
												<div class="form-actions">
													<input type="hidden" id="inicio_enviado" name="inicio_enviado" value="enviado" />
													<button type="submit" class="btn btn-primary">Guardar Modificaciones</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>

		<?PHP include("footer.php"); ?>

	</div>
	<?PHP include("scripts_base.php"); ?>

	<!-- Plugin Scripts -->

	<!-- Flot -->
	<!--[if lt IE 9]>
    <script src="assets/js/libs/excanvas.min.js"></script>
    <![endif]-->
	<script src="plugins/flot/jquery.flot.min.js"></script>
	<script src="plugins/flot/plugins/jquery.flot.tooltip.min.js"></script>
	<script src="plugins/flot/plugins/jquery.flot.pie.min.js"></script>
	<script src="plugins/flot/plugins/jquery.flot.resize.min.js"></script>
	<!-- iButton -->
	<script src="plugins/ibutton/jquery.ibutton.min.js"></script>

	<!-- DataTables -->
	<script src="plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/TableTools/js/TableTools.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.js"></script>

	<!-- Validation -->
	<script src="plugins/validate/jquery.validate.min.js"></script>
	<script src="js/validar_inicio.js"></script>

</body>

</html>