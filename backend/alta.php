<?PHP

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
include_once("../cnfg/config.php");
include_once("inc/sechk.php");
include_once("../inc/funciones.php");
include_once("inc/func_backend.php");
include_once("../clases/clase_error.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es">
<!--<![endif]-->

<head>
	<?PHP
	$error = '';
	$tipoarchivo = 'alta';
	$emp_nom = config('nombre');

	//$error = new Errores();
	$tipo  = getPost('tipo', config('tabla_defecto'));
	if (!isset($_SESSION['sestipo']) || (isset($_SESSION['sestipo']) && $_SESSION['sestipo'] != $tipo)) {
		$_SESSION['sestipo'] = $tipo;
		$_SESSION['pagi'] = 1;
	}
	include("inc/leer_parametros.php");
	if ($usalink == 1) {
		include("../inc/inc_docs.php");
	}
	$id = getPost('id');
	if ($usafotos == 1 || $tipodet == "empleados" || $tipodet == "revista") { // Si usa fotos o es Impresa
		include_once("inc/img.php");
		echo '<link rel="stylesheet" href="plugins/prettyphoto/css/prettyPhoto.css" media="screen">';
		echo '<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" media="all">';
		echo '<link rel="stylesheet" href="plugins/plupload/plupload.bootstrap/css/plupload.bootstrap.css" media="screen">';
		$_SESSION["foto_nr"] = 0;
		$_SESSION["foto_id"] = 0;
	}
	if (isset($_POST['guardaralta'])) {
		include("inc/alta_post.php");
	}
	?>
	<?PHP include("inc/head.php"); ?>
	<?PHP
	if ($usacolor == 1 || $tipodet == "encuestas") { // Si usa color o es encuesta
		echo '<!-- Colorpicker -->
	<link rel="stylesheet" href="plugins/minicolors/jquery.miniColors.css" media="screen">
	';
	}
	if ($tipodet == "encuestas") { // Si es encuesta
		function agregaOpcion($nro, $vis = 0)
		{
			if ($vis == 1) {
				$ver = "block";
			} else {
				$ver = "none";
			}
			$resultado  = '<div id="DivCont' . $nro . '" style="display:' . $ver . '">';
			$resultado .= '<table><tr><td>';
			$resultado .= 'Opcion ' . $nro . ': <input type="text" name="opcion' . $nro . '" id="opcion' . $nro . '" onkeyup="agregaCampos(' . $nro . ')"/><br />';
			$resultado .= '</td><td>';
			$resultado .= '<input name="color' . $nro . '" type="text" class="minicolors" id="color" value="#70A5FF"/>';
			$resultado .= '</td>';
			//$resultado .= '<td>Imagen: <input type="file" name="imagen'.$nro.'" id="imagen'.$nro.'" class="txtfield" /></td>';
			$resultado .= '</tr></table>';
			$resultado .= '</div>
		';
			return $resultado;
		}
	?>
		<script type="text/javascript">
			function agregaCampos(nro) {
				var nroDiv = nro + 1;
				document.getElementById('DivCont' + nroDiv).style.display = 'block';
			}
		</script>
	<?PHP } ?>
	<?PHP include("inc/alta_variables.php");
	if (isset($_POST['notificar'])) { // Envío de mail
		$titulonota = txtdeco(leePost($tipotit));
		include("inc/notificar.php");
	} ?>
</head>

<body data-show-sidebar-toggle-button="true" data-fixed-sidebar="false">
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
										<?PHP echo ucwords(txtcod($nombredet)); ?>: Alta
									</li>
								</ul>

								<h1 id="main-heading">
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>alta </span>
									<?PHP
									if (isset($_POST['guardaralta'])) {
										if ($error == '') {
											echo '<span style="color:#38B34E; font-weight:bold; font-size:16px"> Datos guardados</span>';
											if (isset($msg_ok)) {
												echo '<span style="color:#38B34E; font-weight:bold; font-size:16px"> ' . $msg_ok . '</span>';
											}
										} else {
											echo '<span style="color:#D92225; font-weight:bold; font-size:16px"> ' . $error . '</span>';
										}
									}
									?>
								</h1>
							</div>
							<div id="main-content">
								<div class="row-fluid">
									<div class="span12 widget">
										<div class="widget-header">
											<span class="title"><i class="icos-address-book"></i> <?PHP echo ucwords(txtcod($nombredet)); ?></span>
										</div>
										<div class="widget-content form-container">
											<?PHP $formpost = 'alta'; ?>
											<form class="form-horizontal" method="post" action="<?PHP echo $formpost; ?>.php" id="detalles" enctype="multipart/form-data">
												<?PHP // POR ERROR DE LA PLANTILLA SE INCLUDE ESTE DIV PARA FECHA
												echo '<div class="datepicker-inline" style="display:none"></div>';
												// FIN ERROR PLANTILLA
												if ($tipotit != '' && !is_numeric($tipotit)) {
													echo '<div class="control-group">
                              <label class="control-label" for="' . $tipotit . '">' . ucwords(txtcod($tipotit)) . ': </label>';
													echo '<div class="controls">';
													echo '<input name="' . $tipotit . '" type="' . $tipo . '" class="span12" id="' . $tipotit . '" value="' . txtcod($vartitulo) . '" />';
													echo '</div>
                              </div>';
												}
												if ($tipodet == "areas" && $multiemp == 1) { //areas
													echo '</td>';
													$sqlemp = "SELECT * FROM " . $_SESSION['prefijo'] . "empresas ORDER BY nombre";
													$resemp = fullQuery($sqlemp, 'detalles.php');
													while ($rowemp = mysqli_fetch_array($resemp)) {
														echo '<td>';
														$sql_actemp = "SELECT * FROM " . $_SESSION['prefijo'] . "areas_emp WHERE area = " . $id . " AND empresa = " . $rowemp['id'];
														$res_actemp = fullQuery($sql_actemp, 'detalles.php');
														$con_actemp = mysqli_num_rows($res_actemp);
														$activemp = ($con_actemp == 1) ? 'checked="checked"' : '';
														echo '&nbsp;&nbsp;' . $rowemp['nombre'] . '<input name="emp' . $rowemp['id'] . '" id="cp1" type="checkbox" class="uniform" ' . $activemp . '" />';
														echo '</td>';
													}
													echo '</tr></table>';
												}
												if ($usacolor == 1) {
													echo '<label class="control-label" for="color">Color: </label>';
													echo '<div class="controls">';
													echo '<input name="color" id="cp1" type="text" class="minicolors" value="#' . $varcolor . '">';
													echo '</div>
													</div>';
												}
												if ($anidable == 1) {
												?>
													<label class="control-label">Padre: </label>
													<div class="controls">
														<select name="parent">
															<option value="0">Ninguno</option>
															<?php
															$sqlpar = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE del = 0 AND id <> " . $id . " AND parent = 0 ORDER BY id";

															$respar = fullQuery($sqlpar);
															while ($rowpar = mysqli_fetch_array($respar)) {
																$nombredato = txtcod($rowpar['nombre']);
																echo '<option value="' . $rowpar['id'] . '" ' . optSel($rowpar['id'], $noticia['parent']) . ' > ' . txtcod($nombredato) . '</option>
																';
																echo optChild($_SESSION['prefijo'] . $nombretab, $rowpar['id'], $noticia['parent'], $rowpar['nombre'], 0);
															}
															?>
														</select>
														<br>
													</div>
												<?php
												}
												// VARIABLES

												include("inc/variables.php");

												if ($tipodet == "empleados") { // Empleados
													//include("inc/emp_minisitios.php");
													include("inc/emp_admin.php");
													//include("inc/emp_tipouser.php");
												}

												// ------
												// COMBOS
												// ------

												include("inc/combos.php");

												// FECHA Y HORA

												include("inc/fechahora.php");

												// TEXTO

												include("inc/usatexto.php");

												// ENCUESTAS

												if ($tipodet == "encuestas") {
													include("inc/encuestas.php");
												}

												// CONCURSOS

												if ($tipodet == "concurfotos") {
													include("inc/concursos.php");
												}

												// IMPRESAS

												if ($tipodet == "revista") {
													include("inc/impresas.php");
												}

												// SORTEOS

												if ($tipodet == "sorteos") {
													include("inc/sorteos.php");
												}

												// FOTOS

												if ($usafotos == 1 || $tipodet == "empleados") {
													echo '<div class="control-group">';
													include_once("inc/agrega_imagenes.php");
													echo '</div>';
												}

												// ------
												// VIDEOS
												// ------
												/*                                                    
                                                if ($usavideos == 1){
                                                    echo '<div class="control-group">';
														include_once ("inc/agrega_videos.php");
													echo '</div>';
												}
												*/

												// -------------
												// UPLOADS
												// -------------
												if ($usalink == 1 && ($tipodet == "docs" || $tipodet == "politicas")) {
													include("inc/upload.php");
												}

												if ($usarest == 1) {
													include("inc/restricciones.php");
												}

												if ($activable == 1) {
													echo '<div class="control-group">
                                  <label class="control-label" for="activo">Activo: </label>';
													echo '<div class="controls">';
													echo '<input name="activo" id="cp1" type="checkbox" checked class="uniform" />';
													echo '</div>
													</div>';
												}

												$tiene_alerta = 0;
												if ($usalerta == 1) {
													$tiene_alerta = 1;
													$alhor = date("H");
													//$alhor = 23;
													$alhor++;
													if (date("i") >= 58) {
														$alhor++;
													}
													if ($alhor == 24) {
														$alhor = 0;
													}
													$almin = '00';
												?>
													<div class="control-group">
														<label class="control-label" for="enviar">Alerta </label>
														<div class="controls">
															Inmediata <input type="radio" checked="checked" name="tipoalerta" value="1" onclick="tipoAlerta(1)" />
															Futura <input type="radio" name="tipoalerta" value="2" onclick="tipoAlerta(2)" />
														</div>
														<br><br>
														<div class="controls" id="alertaprg" style="display:none">
															Fecha
															<input type="date" id="alertafecha" name="alertafecha" min="<?php echo date("Y-m-d"); ?>">
															<br>Hora <input id="alertahora" type="number" class="span2" name="alertahora" min="0" max="23" maxlength="2" value="<?php echo $alhor; ?>">
															<!--Min <input id="alertamin" type="number" class="span2" name="alertamin" min="0" max="59" maxlength="2" value="<?php //echo $almin; 
																																																																								?>">-->
														</div>

														<div class="controls" id="alertaya">
															Enviar notificación <input type="checkbox" name="notificar" value="1" />
														</div>

													</div>
												<?php } ?>

												<div class="form-actions" style="padding-left:10px">
													<input type="hidden" id="enviado" name="enviado" value="enviado" />
													<input type="hidden" id="tipo" name="tipo" value="<?PHP echo $tipo; ?>" />
													<input name="agregar_fotos" type="hidden" id="agregar_fotos" value="0" />
													<input name="agregar_videos" type="hidden" id="agregar_videos" value="0" />
													<input name="habilita_envio" type="hidden" id="habilita_envio" value="si" />
													<button id="guardarmods" type="submit" name="guardaralta" class="btn btn-large btn-primary">Guardar</button>
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
	<!-- Validation -->
	<script src="plugins/validate/jquery.validate.min.js"></script>
	<!--<script src="js/validar_detalles.js"></script>-->

	<?PHP if ($tipodet == "revista") { // Si es Impresa
	?>
		<!-- Bootstrap FileInput -->
		<script src="custom-plugins/bootstrap-fileinput.min.js"></script>
		<script src="js/additional-methods.js"></script>
		<script src="js/validar_impresa.js"></script>
	<?PHP } ?>
	<?PHP if ($usafotos == 1 || $tipodet == "revista" || $tipodet == "empleados") { // Si es impresa
		echo '<script src="plugins/prettyphoto/js/jquery.prettyPhoto.min.js"></script>
				<script src="js/gallerias.js"></script>
			<!-- Freetile -->
				<script src="plugins/freetile/jquery.freetile.min.js"></script>
				<!-- Resize plugin to handle container resizes -->
				<script src="plugins/freetile/jquery.resize.min.js"></script>
				<!-- PLUpload -->
    <script src="plugins/plupload/plupload.full.js"></script>
	<script src="plugins/plupload/plupload.bootstrap/plupload.bootstrap.js"></script>
    <!-- Demo Scripts -->
    <script src="assets/js/demo/file_upload.js"></script>
		';
	} ?>
	<?PHP if ($usacolor == 1 || $tipodet == "encuestas") { // Si es encuesta
		echo '	<!-- Colorpicker -->
	<script src="plugins/minicolors/jquery.miniColors.min.js"></script>
		';
	} ?>
	<?PHP if ($usatexto == 1) { ?>
		<script src="inc/texto/build/ckeditor.js"></script>

		<script>
			ClassicEditor
				.create(document.querySelector('.editor'), {
					licenseKey: '',
				})
				.then(editor => {
					window.editor = editor;
				})
				.catch(error => {
					console.error('Oops, something went wrong!');
					console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
					console.warn('Build id: bh7xbvw2wsqp-mkpeakfhvny5');
					console.error(error);
				});
		</script>
	<?PHP } ?>
	<?PHP if ($usafecha > 0 || $tipodet = 'empleados') { ?>
		<script src="assets/jui/timepicker/jquery-ui-timepicker.min.js"></script>
		<script src="assets/jui/js/i18n/jquery.ui.datepicker-es.js"></script>
	<?PHP } ?>
	<!-- Demo Scripts -->
	<script src="assets/js/demo/ui_comps.js"></script>
	<script src="js/scripts.js"></script>
	<?PHP if ($usarest == 1) { ?>
		<script>
			$(document).ready(function() {
				$("#todos").click(function() {
					var chks = $("input:checkbox[name^='valor_']");
					chks.attr("checked", $(this).is(":checked"))
				})
				$("input[name^='valor_']").click(function() {
					var todos = $("input:checkbox[name^='valor_']")
					var activos = $("input:checked[name^='valor_']")
					$("#todos").attr("checked", todos.length == activos.length)
				})
				<?php if ($tiene_alerta == 1) { ?>
					$("#alertafecha").blur(function() {
						checkAlerta();
					})
				<?php } ?>
			})

			function checkAlerta() {
				//console.log("test");
				var alfec = $("#alertafecha").val();
				//alert(alfec.length);
				if (alfec.length > 10) {
					$("#alertafecha").val('');
					//$("#alertafecha").focus();
				} else {
					var hoy = new Date();
					var alhor = $("#alertahora").val();
					var almin = $("#alertamin").val();
					var fechalert = new Date(alfec + "T" + alhor + ":" + almin);

					if (fechalert.getTime() > hoy.getTime()) {
						//alert("es mayor a hoy");
						$("#guardarmods").show();
					} else {
						$("#alertafecha").val('');
						//$("#alertafecha").focus();
						$("#guardarmods").hide();
					}
				}
			}

			function tipoAlerta(tipo) {
				if (tipo == 1) {
					//console.log('1');
					$("#alertaprg").hide();
					$("#alertaya").show();
					$("#guardarmods").show();
				} else {
					//console.log('2');
					$("#alertaprg").show();
					$("#alertaya").hide();
					checkAlerta();
				}
			}

			function childrenAct(valor) {
				if ($("#valor_" + valor).is(":checked")) {
					$('.child_of_' + valor).prop('checked', true);
				} else {
					$('.child_of_' + valor).prop('checked', false);
				}
			}

			function changeParent(valor) {
				var todos = $(".child_of_" + valor + ":checkbox")
				var activos = $(".child_of_" + valor + ":checkbox:checked");
				$("#valor_" + valor).attr("checked", todos.length == activos.length)
			}
		</script>
		<script>
			function checkEnter(e) {
				e = e || event;
				var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
				return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
			}
			document.querySelector('form').onkeypress = checkEnter;
		</script>
	<?PHP } ?>
</body>

</html>