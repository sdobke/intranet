<?PHP
include "inc/startup.php";
include "inc/inc_docs.php";
$tipo = 3;
$mostrar_porDefecto_docsDeAreaUser = 1;
include("backend/inc/leer_parametros.php");
$emp_nom = config('nombre');
$nombre = obtenerNombre($tipo);
$titsec = obtenerDato('detalle', 'tablas', $tipo);
$empre = getPost('bus_empresas', 1);
$cad_emp = ($empre > 0) ? " AND empresa = " . $empre : "";
$areaemple = 0;
if ($mostrar_porDefecto_docsDeAreaUser == 1) {
	if (isset($_SESSION['usrfrontend'])) {
		$areaemple = getAreaDocs($_SESSION['usrfrontend']);
	}
}
$area = getPost('bus_areas', $areaemple);
$cad_are = ($area > 0) ? " AND area = " . $area : "";
$tipodoc = getPost('bus_tipos_docs', 0);
$cad_tipodoc = ($tipodoc > 0) ? " AND tipodoc = " . $tipodoc : "";
$vars = "&bus_empresas=" . $empre . "&bus_areas=" . $area . "&bus_tipodoc=" . $tipodoc;

$busext = getPost("bus_ext",'null',0,0);
$cad_ext = "";

if ($busext != 'null') {
	switch ($busext) {
		case 'doc':
			$cad_ext = ' AND (tipoarc = "doc" OR tipoarc = "docx") ';
			break;
		case 'xls':
			$cad_ext = ' AND (tipoarc = "xls" OR tipoarc = "xlsx") ';
			break;
		case 'pdf':
			$cad_ext = " AND tipoarc = 'pdf' ";
			break;
		case 'form':
			$cad_ext = " AND tipoarc = 'form' ";
			break;
	}
}

$otro_query = '';
//$empre = getPost('bus_empresas',0);
$cad_emp = ($empre > 0) ? " AND empresa = " . $empre : "";
$otro_query = $cad_emp . $cad_tipodoc . $cad_are . $cad_ext;

//$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp'] : 0;
//$otro_query .= " AND FIND_IN_SET(" . $restriccion . ",il.part) ";
$query_orden = 'nombre';
$name_empresa = obtenerDato('nombre', 'empresas', $empre);
if (empty($_POST) && empty($_GET)) {
	agrega_acceso($tipo);
}
include "backend/inc/query_busqueda.php";
$limit = cantidad('cant_docus'); //Cantidad de resultados por página
$limit = 12;
include "inc/prepara_paginador.php";
$query4 = $query;
$contdocs = 0;
$result = fullQuery($query);

if (isset($_GET['cl'])) {

	$sqlcl = "UPDATE intranet_docs_emp SET status = 1 WHERE emp = " . $_SESSION['usrfrontend'] . " AND doc = " . $_GET['cl'];

	$rescl = fullQuery($sqlcl);
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>
		<?PHP echo $cliente; ?> Intranet | Documentos Utiles</title>
	<?PHP include("sitio/head.php"); ?>
	<?PHP //include("head_marcas.php"); 
	?>
	<script type="text/javascript" src="js/jstip.js"></script>
	<link href="/css/jstip.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/lightbox.css">
</head>

<body id="docs">
	<div class="flex-wrapper">
		<?PHP include("sitio/header.php"); ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12" id="col-izq">
					<?PHP
					//if ($empre > 0 && $empre < 5) {
					$cod = $empre;
					$empdir = empresa($cod);
					$color_txt = ($cod == 2) ? "555" : "ccc";
					/*} else {
							echo 'Esta p&aacute;gina no es accesible.';
						}*/
					?>
					<h1 class="mb-5 mt-3">
						<?PHP echo txtcod($titsec); ?>
					</h1>
					<div>
						<?PHP include_once("inc/buscador.php"); ?>
						<?PHP
						//echo $query4;
						$resultado = fullQuery($query4);
						$lupa = '<i class="far fa-search" aria-hidden="true"></i>';
						?>
						<div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
							<?php
							$sql_cantemp = "SELECT id FROM intranet_empresas WHERE del = 0";
							$res_cantemp = fullQuery($sql_cantemp);
							$cantemp = mysqli_num_rows($res_cantemp);
							$divconfir = '';
							while ($docs = mysqli_fetch_array($resultado)) {
								$contdocs++;
								$tipoarc = $docs['tipoarc'];
								$id = $docs['id'];
								$nombre = txtcod($docs['nombre']);
								//$peso      = $docs['peso'];
								$peso = '';
								$docurl = $docs['url'];
								//$pesover   = medidaDocs($peso);
								$areaver = obtenerDato('nombre', 'areas', $docs['area']);
								$areaparen = obtenerDato('parent', 'areas', $docs['area']);
								if ($areaparen > 0) {
									$nomparen = obtenerDato('nombre', 'areas', $areaparen);
									$areaver = $nomparen . ' -> ' . $areaver;
								}
								$tipover = obtenerDato('nombre', 'tipos_docs', $docs['tipodoc']);
								//$sectver   = obtenerDato('nombre','sectores',$docs['sector']);
								$link = $docs['link'];
								if ($tipoarc == 'jpg') {
									$target = ' data-lightbox="galeria" ';
								} else {
									$target = ' target="_blank" ';
								}
								$onclick = '';
								if ($docurl == '') {
									if ($tipoarc == 'pdf') {
										$docurl = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $link . '?ver=' . date("Y-m-d");
									}
									if ($tipoarc == 'xlsx' || $tipoarc == 'xls' || $tipoarc == 'doc' || $tipoarc == 'docx') {
										$docurl = 'https://view.officeapps.live.com/op/view.aspx?src=https://' . $_SERVER['SERVER_NAME'] . '/' . $link . '?ver=' . date("Y-m-d");
									}
								}
								if (isset($docurl) && $docurl != '') {
									$nom_emp = '';
									if ($cantemp > 1) {
										$nom_emp = obtenerDato('nombre', 'empresas', $docs['empresa']) . ' - ';
									}
									//$urlink = txtcod($nom_emp.' - '.$areaver.' - '.$sectver);
									$urlink = txtcod($nom_emp . $areaver . ' - ' . $tipover);
									$sqlde = "SELECT count(*) AS leido FROM intranet_docs_emp WHERE doc = " . $id . " AND emp = " . $_SESSION['usrfrontend'] . " AND status = 1";
									$resde = fullQuery($sqlde);

									$fila = $resde->fetch_assoc();
									$leido = $fila['leido'];
									$conflec = 'Lectura confirmada';
									$confirmada = 0;

									if ($leido != "1") {
										$conflec = 'cl=' . $id . '&tipo=' . $tipo . $vars;
										$confirmada = 1;
									}

									$link = 'javascript:void(0)';
									$urlframe = "'" . $docurl . "'";
									$conflec = "'" . $conflec . "'";
									$confirmada = "'" . $confirmada . "'";

									$onclick = 'onClick="abrirFrame(' . $urlframe . ',' . $tipo . ',' . $id . ',' . $conflec . ',' . $confirmada . ')"';
									//$peso = '';
									$target = '';
								}

								$divconfir .= ' 
											<div class="btn-der ocultos" id="oculto_' . $id . '">' . $conflec . '</div>';
								//$confirm = 'conflec' . '_' . $id;
								//$$confirm = $conflec;
							?>
								<div class="col">
									<div class="card h-100">
										<div class="row g-3 cusuario">
											<div class="col-xl-4 col-lg-12">
												<a href="<?PHP echo $link; ?>" <?php echo $onclick; ?><?php echo $target; ?> class="file-icon">
													<i class="bi bi-<?PHP echo arch_img($tipoarc); ?>"></i>
													<!--<img src="img/ic_<?PHP echo arch_img($tipoarc); ?>.gif" alt="" class="user_pic"/>-->
												</a>
											</div>
											<div class="col-xl-8 col-lg-12">
												<div class="user_name">
													<a href="<?PHP echo $link; ?>" <?php echo $onclick; ?><?php echo $target; ?>>
														<span onmouseover="tooltip.show('<?PHP echo $nombre; ?>');" onmouseout="tooltip.hide();">
															<?PHP echo cortarTxt($nombre, 75, '...' . $lupa); ?>
														</span>
													</a>
												</div>
												<div class="card-text">
													<?php if ($peso != '') { ?>
														<strong>Peso:</strong>
														<?PHP echo $pesover; ?>
													<?php	} ?>
													<span onmouseover="tooltip.show('<?PHP echo $urlink; ?>');" onmouseout="tooltip.hide();">
														<?PHP echo cortarTxt($urlink, 100, '...' . $lupa); ?>
													</span>
													<!--<div class="btn-der"><?php //echo $conflec; 
																										?></div>-->
												</div>
											</div>
										</div>
									</div>
								</div>
							<?PHP } ?>
						</div>
					</div>
					<div class="modal fade" id="iframe-documento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-xl modal-fullscreen-xl-down">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Documento</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body" id="iframe-documento-contenidos">
									Cargando...
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
									<div class="btn-der" id="botconf"></div>
									<?php //echo $divconfir; 
									?>
								</div>
							</div>
						</div>
					</div>
					<?PHP
					$variables = "busqueda=" . $busqueda . "&tipo=" . $tipo . $vars; // variables para el paginador
					echo paginador($limit, $contar, $pag, $variables);
					?>
				</div>
				<!--
					<div class="col-md-3 col-sm-12 dark" id="col-der">
						<?php //include("col_der.php"); 
						?>
					</div>
					-->
			</div>
		</div>
		<?PHP include("sitio/footer.php"); ?>
	</div>
	<?PHP include("sitio/js.php"); ?>
	<script type="text/javascript" src="/assets/js/lightbox.js"></script>
	<script>
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		})
	</script>
	<script>
		function sendForm() {
			document.getElementById("buscador").submit();
		}

		function abrirFrame(link, tipo, id, confirma, confirmada) {
			var full = '<iframe id="' + id + '" src="' + link + '<?php echo '&' . date('d-m-y-h-i-s'); ?>"></iframe>';
			$('#iframe-documento-contenidos').html(full);
			if (confirmada == 1) {
				$('#botconf').html('<a href="?' + confirma + '" class="btn btn-info btn-small">Confirmar Lectura</a>');
			} else {
				$('#botconf').html('<button class="btn btn-secondary btn-small disabled">Lectura confirmada</button>');
			}


			$('#iframe-documento').show();
			$('#iframe-documento').modal('show');

			$.ajax({
				type: "POST",
				url: "agregar_acceso.php",
				data: {
					id: id,
					tipo: tipo
				}
			}).done(function(data) {
				$("#subareas").html(data);
				$(".ocultos").hide();
				$("#oculto_" + id).show();
			});
		}
	</script>
</body>

</html>