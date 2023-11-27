<?PHP

include "cnfg/config.php";

include "inc/funciones.php";



$tipo = getPost('tipo', 14);

$item = getPost('item', 0);

agrega_acceso($tipo);

$errno = 0;

include_once("backend/inc/img.php");

include("backend/inc/leer_parametros.php");

$nombot = 'Crear ';

// Definiciones de género y número

$ar = 'la ';

$ar2 = 'a';

$ar3 = 'de la';

$vartitulo = 'T&iacute;tulo';

$vartexto = 'Descripci&oacute;n ';

switch ($tipo) {

	case 10: // Novedades

		$nomitem = 'novedad';

		$ar = 'la ';

		$ar2 = 'a';

		$ar3 = 'de la';

		$vartexto .= $ar3 . ' ' . $nomitem;

		break;

	case 14: // recomendados

		$nomitem = 'recomendaci&oacute;n';

		$vartexto .= $ar3 . ' ' . $nomitem;

		break;

	case 18: // Clasificados

		$nomitem = 'aviso';

		$ar = 'el ';

		$ar2 = 'o';

		$ar3 = 'del';

		$vartexto .= $ar3 . ' ' . $nomitem;

		break;

	case 15: // Concursos

		$nomitem = 'foto';

		$usatexto = 0;

		$nombot = 'Subir ';

		$usafotos = 2; // Para que cargue solamente una foto

		$vartexto .= $ar3 . ' ' . $nomitem;

		break;

	case 17: // Sugerencias

		$nomitem = $vartexto = 'Sugerencia';

		$vartitulo = 'Tema';

		$usatexto = 1;

		$tipotit = 'titulo';

		break;

	case 37: // Info Municipal

		$nomitem = 'Informaci&oacute;n Municipal';

		$vartitulo = 'T&iacute;tulo';

		$tipotit = 'titulo';

		if ((!isset($_SESSION['minisitio_3']) || (isset($_SESSION['minisitio_3']) && $_SESSION['minisitio_3'] != 'admin')) && !isset($_SESSION['id_usr'])) {

			exit;

		}

		break;

	case 22: // Seguridad e Higiene

		$nomitem = 'Seguridad e Higiene';

		$vartitulo = 'T&iacute;tulo';

		$tipotit = 'titulo';

		if ((!isset($_SESSION['minisitio_3']) || (isset($_SESSION['minisitio_3']) && $_SESSION['minisitio_3'] != 'admin')) && !isset($_SESSION['id_usr'])) {

			exit;

		}

		break;

}

?>

<?PHP

if (isset($_POST['post'])) {

	include_once "altapost.php";

}

?>

<!DOCTYPE html>

<html>



<head>

	<title><?PHP echo $cliente; ?> Intranet</title>

	<?PHP if ($tipo == 17) { // Sugerencias 

	?>

		<script type="text/javascript">

			function verCheck() {

				valor = document.formulario.nombre_an.checked;

				if (valor == 1) {

					document.getElementById('con_nom').style.display = 'none';

					/*document.getElementById('nombre').value = '';*/

				} else {

					document.getElementById('con_nom').style.display = 'block';

				}

			}

		</script>

	<?PHP } ?>

	<?PHP if ($usafotos == 1) { // Si usa carga de fotos múltiples

		$_SESSION["foto_nr"] = 0;

		$_SESSION["foto_id"] = 0;

	?>

		<link rel="stylesheet" href="backend/plugins/prettyphoto/css/prettyPhoto.css" media="screen" />

		<link rel="stylesheet" href="backend/bootstrap/css/bootstrap.min.css" media="all" />

		<link rel="stylesheet" href="backend/plugins/plupload/plupload.bootstrap/css/plupload.bootstrap.css" media="screen" />

	<?PHP } ?>

	<?PHP include("sitio/head.php"); ?>

	<?PHP include("old/head_css.php"); ?>

	<?PHP include("old/head_js.php"); ?>

	<link href="/css/nota.css" rel="stylesheet" type="text/css" />

	<link href="/css/secciones.css" rel="stylesheet" type="text/css" />

	<link href="/css/alta-front.css" rel="stylesheet" type="text/css" />

</head>



<body class="alta alta_<?php echo $nomitem; ?>">

	<div class="container">

		<?PHP include("sitio/header.php"); ?>

		<div class="row">

			<div class="col-md-9 col-sm-12" id="col-izq">

				<!-- BEGIN BODY -->

				<div class="col_ppal left">

					<div class="hd-seccion"><?PHP echo txtcod(ucwords($nombredet)); ?> / Nuev<?PHP echo $ar2 . ' ' . txtcod(ucwords($nomitem)); ?></div>

					<?PHP

					if ($errno > 0) {

						switch ($errno) {

							case 1:

								$errtx = '<div class="alert_box left mb15"><strong>ATENCI&Oacute;N: </strong>Se produjo un error al procesar ' . $ar . $nomitem . '. Por favor, volv&eacute; a intentarlo.</div>';

								break;

							case 2:

								if ($tipo == 17) { // Sugerencias

									$errtx = '<div class="info_box left mb15"><strong>Gracias </strong>por tu sugerencia.</div>';

								} else {

									$errtx = '<div class="info_box left mb15">Gracias por tu ' . $nomitem . '. <strong>AVISO: </strong>' . ucwords($ar) . $nomitem . ' va a ser moderad' . $ar2 . ' por el Administrador antes de ser ingresad' . $ar2 . ' al listado.</div>';

								}

								break;

							case 3:

								$errtx = '<div class="success_box left mb15">Tu ' . $nomitem . ' se cre&oacute; con &eacute;xito. En unos instantes aparecer&aacute; en el listado.</div>';

								break;

							case 4:

								$errtx = '<div class="alert_box left mb15">No subiste la foto. Es necesaria para participar.</div>';

								break;

							case 5:

								$errtx = '<div class="alert_box left mb15">La foto tiene que ser jpg.</div>';

								break;

							case 6:

								$errtx = '<div class="alert_box left mb15">Hubo un error al subir la foto.</div>';

								break;

						}

						echo $errtx;

					}

					?>

					<?PHP

					if (isset($_SESSION['usrfrontend'])) {

					?>

						<div class="formularios left mb15">

							<form action="alta.php" method="post" enctype="multipart/form-data" name="formulario">



								<?PHP if ($tipo == 17) { // Sugerencias 

								?>

									<div id="sin_nom">

										<div class="left w100 mb5 c444444 b">An&oacute;nimo</div>

										<div class="left w100 mb15">

											<input type="checkbox" name="nombre_an" value="anonimo" id="nombre_an" onchange="javascript:verCheck()" />

										</div>

									</div>

									<div id="con_nom">

										<div class="left w100 mb5 c444444 b">Nombre</div>

										<div class="left w100 mb15">

											<input type="text" name="nombre" id="textfield" style="width:600px" onchange="javascript:verCheck()" />

										</div>

									</div>

								<?PHP } ?>



								<div class="left w100 mb5 c444444 b"><?PHP echo $vartitulo; ?></div>

								<div class="left w100 mb15">

									<input type="text" name="titulo" id="textfield" style="width:600px" />

								</div>

								<?PHP

								if ($tipo == 14) { // Recomendados

									echo '<div class="left w100 mb5 c444444 b">Tipo de recomendaci&oacute;n</div>';

									echo '<select name="salida" id="select2">';

									$sqlsal = "SELECT * FROM intranet_salidas ORDER BY nombre";

									$ressal = fullQuery($sqlsal);

									while ($rowsal = mysqli_fetch_array($ressal)) {

										echo '<option value="' . $rowsal['id'] . '">' . txtcod($rowsal['nombre']) . '</option>';

									}

									echo '</select>';

								}

								if ($usafecha > 0) {

									$nomfec = '';

									$hoy = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

									$diamax = substr($hoy, 8, 2);

									$mesmax = substr($hoy, 5, 2);

									$anomax = substr($hoy, 0, 4);

									if ($usafecha == 2) { // CALCULA LA FECHA MAXIMA

										$nomfec = ' de Vencimiento';

										$venc_clasif = cantidad('clasivenc');

										$fechamaxima = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + $venc_clasif, date("Y")));

									} elseif ($usafecha == 3) {

										echo '<input name="fecha" type="hidden" value="' . $hoy . '" />';

									}

									if ($tipo == 18) { // Clasificados 

								?>

										<input name="fecha" type="hidden" value="<?PHP echo $fechamaxima; ?>" />

										<?PHP $anomax = substr($fechamaxima, 0, 4); ?>

									<?PHP } ?>

									<?PHP if ($usafecha < 3) { ?><div class="left w100 mb5 c444444"><strong>Fecha<?PHP echo $nomfec; ?></strong></div><?PHP } ?>

									<?PHP if ($usafecha == 2) {

										echo '(fecha m&aacute;xima: ' . fechaDet($fechamaxima, '', 's') . ')';

									} ?>

									<div class="left w100 mb15 inputcortos">

										<select name="dia" id="select">

											<!--<option selected="selected">Día...</option>-->

											<?PHP

											$cont = 1;

											while ($cont <= 31) {

												$sel = ($cont == $diamax) ? 'selected="selected"' : '';

												echo '<option value="' . $cont . '" ' . $sel . '>' . $cont . '</option>';

												$cont++;

											}

											?>

										</select>

										<select name="mes" id="select2">

											<!--<option selected="selected">Mes...</option>-->

											<option value="01" <?PHP echo optSel($mesmax, 1); ?>>Ene</option>

											<option value="02" <?PHP echo optSel($mesmax, 2); ?>>Feb</option>

											<option value="03" <?PHP echo optSel($mesmax, 3); ?>>Mar</option>

											<option value="04" <?PHP echo optSel($mesmax, 4); ?>>Abr</option>

											<option value="05" <?PHP echo optSel($mesmax, 5); ?>>May</option>

											<option value="06" <?PHP echo optSel($mesmax, 6); ?>>Jun</option>

											<option value="07" <?PHP echo optSel($mesmax, 7); ?>>Jul</option>

											<option value="08" <?PHP echo optSel($mesmax, 8); ?>>Ago</option>

											<option value="09" <?PHP echo optSel($mesmax, 9); ?>>Sep</option>

											<option value="10" <?PHP echo optSel($mesmax, 10); ?>>Oct</option>

											<option value="11" <?PHP echo optSel($mesmax, 11); ?>>Nov</option>

											<option value="12" <?PHP echo optSel($mesmax, 12); ?>>Dic</option>

										</select>

										<select name="anio" id="select3">

											<!--<option>Año...</option>-->

											<?PHP

											$anio = date("Y");

											echo '<option value="' . $anio . '" ' . optSel($anio, $anomax) . '>' . $anio . '</option>';

											$aniosig = $anio + 1;

											if ($anomax == $aniosig) {

												echo '<option value="' . $anomax . '" selected="selected">' . $anomax . '</option>';

											}

											?>

										</select>

									</div>



								<?PHP } ?>

								<?PHP if ($usafotos == 1) {

									include_once("backend/inc/agrega_imagenes.php");

								} ?>



								<?PHP if ($usafotos == 2) {  // Foto individual 

								?>

									<div class="left w100 mb5 c444444 b">Por favor, seleccione la fotograf&iacute;a:</div>

									<!--                                            <div class="left w100 mb15">

                                                <input type="file" name="fotoind" id="fileField" />

                                            </div>-->

									<label class="myLabel">

										<input type="file" name="fotoind" id="fileField" />

										<span>Cargar Foto</span>

									</label>

								<?PHP } ?>

								<?PHP if ($usatexto == 1) { ?>

									<br />

									<div class="left w100 mb5 c444444 b"><?PHP echo $vartexto; ?></div>

									<div class="left w100 mb15">

										<textarea name="texto" id="textarea" cols="45" rows="5"></textarea>

									</div>

								<?PHP } ?>

								<?PHP if ($usalink == 1) { ?>

									<div class="left w100 mb5 c444444 b">Por favor, suba el documento:</div>

									<div class="left w100 mb15">

										<input type="file" name="archivo" id="fileField" />

									</div>

								<?PHP } ?>

								<?PHP if ($tipo == 37 || $tipo == 22) { // Información Municipal y Seguridad e Higiene

									echo '<div class="left w100 mb5 c444444 b">Local</div>';

									echo '<select name="locales" id="select2">';

									$sqlsal = "SELECT * FROM intranet_empleados WHERE area = 1002 ORDER BY nombre";

									$ressal = fullQuery($sqlsal);

									while ($rowsal = mysqli_fetch_array($ressal)) {

										echo '<option value="' . $rowsal['id'] . '">' . $rowsal['apellido'] . '</option>';

									}

									echo '</select>';

								}

								?>

								<div class="left w100 brd-t pt10 ar">

									<button type="submit" name="guardaralta"><span class="icon icon68"></span><span class="labeled"><?PHP echo $nombot . ucwords($nomitem); ?></span></button>

									<!--<a href="#" class="button"><span class="icon icon186"></span></a>-->

								</div>

								<input type="hidden" value="<?PHP echo $tipo; ?>" name="tipo" />

								<input type="hidden" value="1" name="post" />

								<input type="hidden" value="<?PHP echo $item; ?>" name="item" />

							</form>

						</div>

					<?PHP } else { ?>

						<div class="formularios left mb15">

							Ten&eacute;s que ingresar con tu usuario para continuar.

						</div>

					<?PHP } ?>

					<?PHP

					if ($usafotos == 1600) { // No se usa pero se guarda.

					?>

						<ul class="empleados brd-b">

							<li>

								<div class="box1"><img src="/cliente/fotos/01.jpg" alt="" width="171" height="171" class="aligncenter" /></div>

								<div class="box3 brd-ts">

									<div class="date"> <a href="#">Eliminar</a></div>

									<div class="clr"></div>

								</div>

							</li>

							<li>

								<div class="box1"><img src="/cliente/fotos/01.jpg" alt="" width="171" height="171" class="aligncenter" /></div>

								<div class="box3 brd-ts">

									<div class="date"> <a href="#">Eliminar</a></div>

									<div class="clr"></div>

								</div>

							</li>

							<li>

								<div class="box1"><img src="/cliente/fotos/01.jpg" alt="" width="171" height="171" class="aligncenter" /></div>

								<div class="box3 brd-ts">

									<div class="date"> <a href="#">Eliminar</a></div>

									<div class="clr"></div>

								</div>

							</li>

						</ul>

					<?PHP } ?>

				</div>

				<div class="clr"></div>

				<!-- END BODY -->



			</div>

			<div class="col-md-3 col-sm-12 dark" id="col-der">

				<?php include("col_der.php"); ?>

			</div>

		</div>

	</div>

	<?PHP include("sitio/footer.php"); ?>

	<?PHP //include("sitio/js.php"); ?>

	<?PHP

	if ($usafotos == 1) { // Si usa fotos

		$esconcurso = ($tipo == 15) ? '_conc' : '';

		echo '

		<script src="backend/plugins/prettyphoto/js/jquery.prettyPhoto.min.js"></script>

		<script src="backend/js/gallerias.js"></script>

		<!-- Freetile -->

		<script src="backend/plugins/freetile/jquery.freetile.min.js"></script>

		<!-- Resize plugin to handle container resizes -->

		<script src="backend/plugins/freetile/jquery.resize.min.js"></script>

		<!-- PLUpload -->

		<script src="backend/plugins/plupload/plupload.full.js"></script>

		<script src="backend/plugins/plupload/plupload.bootstrap/plupload.bootstrap.js"></script>

	    <!-- Demo Scripts -->

	    <script src="backend/assets/js/demo/file_upload_front' . $esconcurso . '.js"></script>

		';

	} ?>

</body>



</html>