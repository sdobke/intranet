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
$emp_nom = config('nombre');
$tipoarchivo = 'detalles';
$error = new Errores();
$tipo  = getPost('tipo', config('tabla_defecto'));
if (!isset($_SESSION['sestipo']) || (isset($_SESSION['sestipo']) && $_SESSION['sestipo'] != $tipo)) {
	$_SESSION['sestipo'] = $tipo;
	$_SESSION['pagi'] = 1;
}
include("inc/leer_parametros.php");
if ($usalink == 1) {
	include("../inc/inc_docs.php");
}

$id = getPost('id', 'ultimo', $tipo);

$sql = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE id = " . $id;
$res = fullQuery($sql);
$con = mysqli_num_rows($res);
if ($con == 1) {
	$noticia = mysqli_fetch_assoc($res);
}

$_SESSION["foto_id"] = $id;
$_SESSION["foto_tipo"] = $tipo;
$_SESSION["foto_nr"] = 0;
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es">
<!--<![endif]-->

<head>
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
			$resultado .= 'Opcion ' . $nro . ': <input type="text" class="span3" name="opcion' . $nro . '" id="opcion' . $nro . '" onkeyup="agregaCampos(' . $nro . ')" /> ';
			$resultado .= '<input name="color' . $nro . '" type="text" class="minicolors" id="color" value="#70a5ff" />';
			//$resultado .= 'Imagen: <input type="file" name="imagen'.$nro.'" id="imagen'.$nro.'" class="txtfield" />';
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
	<?PHP
	if ($usafotos == 1 || $tipodet == "revista" || $tipodet == "empleados" || $tipodet == "concurfotos") { // Si usa fotos o es Impresa o empleados
		include_once("inc/img.php");
	?>
		<link rel="stylesheet" href="plugins/prettyphoto/css/prettyPhoto.css" media="screen">
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" media="all">
		<link rel="stylesheet" href="plugins/plupload/plupload.bootstrap/css/plupload.bootstrap.css" media="screen">
	<?PHP } ?>
	<?PHP
	if (isset($_POST['guardar']) || isset($_POST['notificar'])) {
		include("inc/detalles_post.php");
	}
	$sql = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE id = " . $id;
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if ($con == 1) {
		$noticia = mysqli_fetch_assoc($res);
	}
	// Comentarios
	if (isset($_GET['val']) && isset($_GET['com'])) {
		$comval = $_GET['val'];
		$comid = $_GET['com'];
		$sql_com = "UPDATE " . $_SESSION['prefijo'] . "comentarios SET activo = " . $comval . " WHERE id = " . $comid;
		$res_com = fullQuery($sql_com);
	}
	// Fin comentarios
	?>
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
										<?PHP echo ucwords(txtcod($nombredet)); ?> <span>edici&oacute;n: </span>
										<?PHP
										if (isset($_POST['guardar'])) {
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
									</li>
								</ul>
								<h1 id="main-heading">
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>edici&oacute;n</span>
								</h1>
							</div>
							<div id="main-content">
								<div class="row-fluid">
									<div class="span12 widget">
										<div class="widget-header">
											<span class="title"><i class="icos-address-book"></i> <?PHP echo ucwords(txtcod($nombredet)); ?></span>
											<?php 
											if ($usagusta == 1) {
												echo '<div class="barra-derecha">';
												$sqlgus = "SELECT mg.id, emp.nombre, emp.apellido FROM " . $_SESSION['prefijo'] . "me_gusta mg 
												LEFT JOIN " . $_SESSION['prefijo'] . "empleados emp ON emp.id = mg.usuario_id
												WHERE mg.tipo = " . $tipo . " AND mg.item = " . $id;
												$resgus = fullQuery($sqlgus);
												$cangus = mysqli_num_rows($resgus);
												if ($cangus > 0) {
													$gusplural = ($cangus == 1) ? '' : 's';
													echo 'Le gusta a ' . $cangus . ' usuario' . $gusplural . ' <a href="#megustan">Ver</a> <i class="icon-thumbs-up"></i>';
												} else {
													echo 'Ningún usuario hizo click en "me gusta" para este contenido. <i class="icon-thumbs-up"></i>';
												}
												echo '</div>';
											}
											?>
										</div>
										<div class="widget-content form-container">
											<?PHP
											include("inc/detalles_variables.php");
											if (isset($_POST['notificar'])) { // Envío de mail
												$titulonota = $vartitulo;
												include("inc/notificar.php");
											}
											if ($con == 1) {
												$formpost = 'detalles';
											?>
												<form class="form-horizontal" method="post" action="<?PHP echo $formpost; ?>.php" id="detalles" enctype="multipart/form-data">
													<?PHP // POR ERROR DE LA PLANTILLA SE INCLUDE ESTE DIV
													echo '<div class="datepicker-inline" style="display:none"></div>';
													// FIN ERROR PLANTILLA

													if ($tipotit != '' && !is_numeric($tipotit)) {
														echo '<div class="control-group">
														';

														if ($tipodet == "areas" && $multiemp == 1) { //areas
															echo '<table><tr><td style="width:650px">';
														}

														echo '<label class="control-label" for="' . $tipotit . '">' . ucwords(txtcod($tipotit)) . ': </label>';
														echo '<div class="controls">';
														echo "<input name='" . $tipotit . "' type='" . $tipo . "' class='span12' id='" . $tipotit . "' value='" . txtcod($vartitulo) . "' />";
														echo '</div>';
														echo '</td>';
														if ($tipodet == "areas" && $multiemp == 1) { //areas
															$sqlemp = "SELECT * FROM " . $_SESSION['prefijo'] . "empresas ORDER BY nombre";
															$resemp = fullQuery($sqlemp);
															while ($rowemp = mysqli_fetch_array($resemp)) {
																echo '<td>';
																$sql_actemp = "SELECT * FROM " . $_SESSION['prefijo'] . "areas_emp WHERE area = " . $id . " AND empresa = " . $rowemp['id'];
																$res_actemp = fullQuery($sql_actemp);
																$con_actemp = mysqli_num_rows($res_actemp);
																$activemp = ($con_actemp == 1) ? 'checked="checked"' : '';
																echo '&nbsp;&nbsp;' . $rowemp['nombre'] . '<input name="emp' . $rowemp['id'] . '" id="cp1" type="checkbox" class="uniform" ' . $activemp . '" />';
																echo '</td>';
															}
														}
														echo '</tr></table>';
														echo '</div>';
														if ($anidable == 1) {
													?>
															<label class="control-label">Padre: </label>
															<div class="controls">
																<select name="parent">
																	<option value="0" <?php echo optSel('0', $noticia['parent']); ?>>Ninguno</option>
																	<?php
																	//$sqlpar = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE del = 0 AND id <> " . $id. " ORDER BY CASE WHEN parent = 0 THEN ID ELSE parent END, parent, id";
																	$sqlpar = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE del = 0 AND id <> " . $id . " AND parent = 0 ORDER BY id";

																	$respar = fullQuery($sqlpar);
																	while ($rowpar = mysqli_fetch_array($respar)) {
																		$nombredato = txtcod($rowpar['nombre']);
																		echo '<option value="' . $rowpar['id'] . '" ' . optSel($rowpar['id'], $noticia['parent']) . ' > ' . $nombredato . '</option>
																		';
																		echo optChild($_SESSION['prefijo'] . $nombretab, $rowpar['id'], $noticia['parent'], $rowpar['nombre'], 0);
																	}
																	?>
																</select>
															</div>
														<?php
														}
													}
													if ($usacolor == 1) {
														echo '<div class="control-group">
                                  <label class="control-label" for="color">Color: </label>';
														echo '<div class="controls">';
														echo '<input name="color" id="cp1" type="text" class="minicolors" value="#' . $varcolor . '">';
														echo '</div>
                                </div>';
													}
													// VARIABLES
													include("inc/variables.php");


													if ($tipodet == "empleados") { // Empleados
														//include("inc/emp_minisitios.php");
														include("inc/emp_admin.php");
														//include("inc/emp_locales.php");
														echo '<div class="control-group">
														<label class="control-label" for="ulting">Ultimo Ingreso: </label>';
														echo '<div class="controls">';
														$ulting = ($noticia['ulting'] != '0000-00-00') ? fechaDet($noticia['ulting']) : 'Nunca';
														echo $ulting;
														echo '</div></div>';
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
													// CONCURSOS
													if ($tipodet == 'concursos') {
														include("inc/concursos.php");
													}

													// IMPRESAS

													if ($tipodet == "revista") {
														include("inc/impresas.php");
													}

													// BUZON DE SUGERENCIAS
													if ($tipodet == 'sugerencias') {
														include("inc/sugerencias.php");
													}
													// IMPRESAS
													if ($tipodet == 'revistas' || $tipodet == 'edimp') {
														include("inc/impresas.php");
													}
													// FOTOS
													if ($usafotos == 1 || $tipodet == "empleados") {
														include_once("inc/muestra_fotos_detalles.php");
														echo '<div class="control-group">';
														include_once("inc/agrega_imagenes.php");
														echo '</div>';
													}
													// ------
													// VIDEOS
													// ------
													if ($usavideos == 1) {
														include("inc/videos.php");
														/*
                              echo '<div class="control-group">';
															include_once ("inc/agrega_videos.php");
														echo '</div>';
														*/
													}

													// -------------
													// UPLOADS
													// -------------
													if ($usalink == 1 && ($tipodet == "docs" || $tipodet == "politicas")) {
														include("inc/verdocs.php");
														include("inc/upload.php");
													}
													// -----------
													// COMENTARIOS
													// -----------
													if ($usacoment == 1) {
														include("inc/comentarios.php");
													}
													// -------------
													// RESTRICCIONES
													// -------------
													if ($usarest == 1) {
														include("inc/restricciones.php");
													}
													if ($activable == 1) {
														$activok = ($noticia['activo'] == 1) ? 'checked="checked"' : '';
														?>
														<div class="control-group">
															<label class="control-label" for="activo">Activo: </label>
															<div class="controls">
																<input name="activo" id="cp1" type="checkbox" class="uniform" <?php echo $activok; ?> />
															</div>
														</div>
													<?php
													}
													// ENCUESTAS
													if ($tipodet == 'encuestas') {
														include("inc/encuestas.php");
													}
													// Concursos de fotos
													if ($tipodet == 'concurfotos') {
														include("inc/concursos.php");
													}
													$tiene_alerta = 0;
													if ($usalerta == 1) {
														$tiene_alerta = 1;
														$alertactual = 1;
														$alhor = date("H");
														$alfec = date("Y-m-d");
														//$alhor = 23;
														$alhor++;
														if (date("i") >= 58) {
															$alhor++;
														}
														if ($alhor == 24) {
															$alhor = 0;
														}
														$almin = '00';
														$sql_alh = "SELECT * FROM intranet_alertas WHERE tipo = " . $tipo . " AND item = " . $id . " ORDER BY id DESC LIMIT 1";
														//echo $sql_alh;
														$res_alh = fullQuery($sql_alh);
														$can_alh = mysqli_num_rows($res_alh);
														if ($can_alh > 0) {
															$alertactual = 2;
															$row_alh = mysqli_fetch_assoc($res_alh);
															$alfec = $row_alh['fecha'];
															$alhor = substr($row_alh['hora'], 0, 2);
															if ($alhor == date("H")) {
																$alhor++;
																if (date("i") >= 58) {
																	$alhor++;
																}
																if ($alhor == 24) {
																	$alhor = 0;
																}
															}
														}
														//$alfec = fechaDet($alfec,'barras');
													?>
														<div class="control-group">
															<label class="control-label" for="enviar">Alerta </label>
															<div class="controls">
																<?php
																$alchk1 = ($alertactual == 1) ? 'checked="checked"' : '';
																$alchk2 = ($alertactual == 2) ? 'checked="checked"' : '';
																?>
																Inmediata <input type="radio" <?php echo $alchk1; ?> name="tipoalerta" value="1" onclick="tipoAlerta(1)" />
																Futura <input type="radio" <?php echo $alchk2; ?> name="tipoalerta" value="2" onclick="tipoAlerta(2)" />
															</div>
															<br><br>
															<?php
															$alerdis = 'block';
															if ($alertactual == 1) {
																$alerdis = 'none';
															}
															?>
															<div class="controls" id="alertaprg" style="display:<?php echo $alerdis; ?>">
																Fecha
																<input type="date" id="alertafecha" name="alertafecha" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $alfec; ?>">
																<br>Hora <input id="alertahora" type="number" class="span2" name="alertahora" min="0" max="23" maxlength="2" value="<?php echo $alhor; ?>">
																<!-- Min <input id="alertamin" type="number" class="span2" name="alertamin" min="0" max="59" maxlength="2" value="<?php //echo $almin; 
																																																																									?>"> -->
															</div>
															<?php
															$alerdis = 'block';
															if ($alertactual == 2) {
																$alerdis = 'none';
															}
															?>
															<div class="controls" id="alertaya" style="display:<?php echo $alerdis; ?>">
																<a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">Enviar Alerta</a>
															</div>
														</div>
														<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																<h3 id="myModalLabel">Envío de notificación</h3>
															</div>
															<div class="modal-body">
																<?php
																$unaotra = ' ';
																$sql_alh2 = "SELECT * FROM intranet_alertas WHERE tipo = " . $tipo . " AND item = " . $id . " AND enviada = 1";
																$res_alh2 = fullQuery($sql_alh2);
																$can_alh2 = mysqli_num_rows($res_alh2);
																if ($can_alh2 > 0) {
																	echo '<p>Ya se enviaron ' . $can_alh2 . ' notificaciones de este ítem.</p>';
																	$unaotra = ' nueva';
																} ?>
																<p>¿Desea enviar una<?php echo $unaotra; ?> notificación?</p>
															</div>
															<div class="modal-footer">
																<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cerrar</button>
																<button type="submit" name="notificar" class="btn btn-primary">Enviar Notificación</button>
															</div>
														</div>
														<?php
														$notifsho = 'none';
														if(isset($_GET['testing'])){
															$notifsho = 'block';
														}
														?>
														<div style="display:<?php echo $notifsho;?>; text-align:center" id="testing">
														<a class="btn btn-large btn-success" target="_blank"  href="./push_notifications/push.php?idnota=<?php echo $id;?>&tabnom=<?php echo $nombretab;?>&tipo=<?php echo $tipo;?>">Enviar Notificación Push</a>
														<!--<iframe id="notifications" src="./push_notifications/push.php?idnota=<?php echo $id;?>&tabnom=<?php echo $nombretab;?>&tipo=<?php echo $tipo;?>" width="100%" height="600"></iframe>-->
															<?php //include("push_notifications/send.php"); ?>
														</div>
													<?php } ?>

													<?php //print_r($_POST);
													?>
													<div class="form-actions" style="padding-left:10px">
														<input type="hidden" id="enviado" name="enviado" value="enviado" />
														<input type="hidden" id="tipo" name="tipo" value="<?PHP echo $tipo; ?>" />
														<input type="hidden" id="id" name="id" value="<?PHP echo $id; ?>" />
														<input name="agregar_fotos" type="hidden" id="agregar_fotos" value="0" />
														<input name="agregar_videos" type="hidden" id="agregar_videos" value="0" />
														<input name="habilita_envio" type="hidden" id="habilita_envio" value="si" />
														<input name="generada" type="hidden" id="generada" value="0" />
														<?PHP // ELIMINAR
														$link_bot_elim = ($borrable > 0) ? "javascript:mostrar('eliminar'); ocultar('bot_elim')" : "javascript:confirmDelete('listado.php?opciond=Elim&amp;tipo=" . $tipo . "&amp;id=" . $noticia['id'] . "')";
														?>
														<div class="btn btn-large btn-danger" onclick="<?PHP echo $link_bot_elim; ?>">Eliminar</div>
														<?PHP if ($borrable > 0) { ?>
															<div id="eliminar" style="display:none; float:left; margin-left:10px; margin-right:10px">
																<div style="float:left">
																	Existen notas asignadas a esta secci&oacute;n. Por favor seleccione una secci&oacute;n a la que se asignar&aacute;n las notas.
																	<select id="nuevoelim" name="nuevoelim">
																		<?PHP
																		$sql_elimine = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE del = 0 AND id != " . $id;
																		$res_elimine = fullQuery($sql_elimine);
																		while ($row_elim = mysqli_fetch_array($res_elimine)) {
																			echo '<option value="' . $row_elim['id'] . '" >' . $row_elim['nombre'] . '</option>';
																		}
																		?>
																	</select>
																</div>
																<div style="float:left; margin-left:10px;">
																	<span style="float:left">
																		<button class="btn btn-large btn-danger" onclick="javascript:confirmDeleteDependencias('listado.php?opciond=Elim&tipo=<?PHP echo $tipo; ?>&id=<?PHP echo $noticia['id']; ?>')">Eliminar</button>
																	</span>
																</div>
															</div>
														<?PHP } ?>
														<button id="guardarmods" type="submit" name="guardar" class="btn btn-large btn-primary">Guardar Modificaciones</button>
													</div>
												</form>
												<?PHP
												// SORTEOS
												if ($tipodet == 'sorteos') {
													include("inc/sorteos.php");
												}
												include("stats/detalles_notas.php");
												if ($usagusta == 1) {include("stats/detalles_notas_megusta.php");}

												if ($tipodet == 'docs') {include("stats/detalles_docs_lecturas.php");}
												?>
											<?PHP } else {
												echo 'La sesi&oacute;n expir&oacute; o intent&oacute; seleccionar una noticia con identificador incorrecto.';
											}
											?>
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
	<?PHP if ($tipodet == "revista") { // Impresa 
	?>
		<!-- Bootstrap FileInput -->
		<script src="custom-plugins/bootstrap-fileinput.min.js"></script>
		<script src="js/additional-methods.js"></script>
	<?PHP } ?>
	<?PHP if ($usafotos == 1 || $tipodet == "revista" || $tipodet == "empleados" || $tipodet == "concurfotos") { // Si usa fotos o es Impresa o empleados
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
	<?PHP if ($usacolor == 1 || $tipodet == "encuestas") { // Si usa color o es encuestas
		echo '	<!-- Colorpicker -->
	<script src="plugins/minicolors/jquery.miniColors.min.js"></script>
		';
	} ?>
	<?PHP if ($usatexto == 1) { ?>
		<script src="inc/texto/build/ckeditor.js"></script>
		<script src="inc/texto/ckfinder/ckfinder.js"></script>
		<script>
			ClassicEditor
				.create(document.querySelector('.editor'), {
					licenseKey: ''
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
		<script src="assets/jui/js/i18n/jquery.ui.datepicker-es.js"></script>
		<script src="assets/jui/timepicker/jquery-ui-timepicker.min.js"></script>
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
					//var almin = $("#alertamin").val();
					var almin = '00';
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
	<?PHP } ?>
	<?PHP if ($tipodet == "concurfotos") { ?>
		<!-- DataTables -->
		<script src="plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="plugins/datatables/TableTools/js/TableTools.min.js"></script>
		<script src="plugins/datatables/FixedColumns/FixedColumns.min.js"></script>
		<script src="plugins/datatables/dataTables.bootstrap.js"></script>
		<script src="plugins/datatables/jquery.dataTables.columnFilter.js"></script>

		<!-- Demo Scripts -->
		<script src="assets/js/demo/tablas.js"></script>
	<?PHP } ?>
	<?php if ($usavideos == 1) : ?>
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<!-- Include the core media player JavaScript. -->
		<script type="text/javascript" src="/js/osmplayer/bin/osmplayer.compressed.js"></script>
		<!-- Include the DarkHive ThemeRoller jQuery UI theme. -->
		<link rel="stylesheet" href="/js/osmplayer/jquery-ui/dark-hive/jquery-ui.css">
		<!-- Include the Default template CSS and JavaScript. -->
		<link rel="stylesheet" href="/js/osmplayer/templates/default/css/osmplayer_default.css">
		<script type="text/javascript" src="/js/osmplayer/templates/default/osmplayer.default.js"></script>
		<?PHP if (isset($urlvid)) { ?>
			<script type="text/javascript">
				$(function() {
					$("#osmplayer").osmplayer({
						playlist: '../<?php echo $urlvid . $id; ?>.xml',
						width: '100%',
						height: '350px'
					});
				});
			</script>
		<?PHP } ?>
	<?php endif; ?>
	<script>
		function envioAlertas(cant) {
			alert("Ya se enviaron " + cant + " alertas de este item.");
		}
	</script>
</body>

</html>