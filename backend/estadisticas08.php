<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
include_once("inc/sechk.php");
$backend = 1;
$emp_nom = config('nombre');
$nombredet = "Estad&iacute;sticas";
$error = new Errores();
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es"><!--<![endif]-->

<head>
	<?PHP include("inc/head.php"); ?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		function submitformBuscar() {
			document.formbuscar.submit();
		}
	</script>
</head>
<?PHP include("inc/estadisticas_read.php"); ?>
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
										<?PHP echo ucwords(txtcod($nombredet)); ?>
									</li>
								</ul>

								<h1 id="main-heading">
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>Comentarios</span>
								</h1>
							</div>
							<div id="main-content">
								<div class="row-fluid">
									<div class="span12 widget">
										<div class="widget-header">
											<span class="title"><i class="icos-looking-glass"></i> Buscar</span>
										</div>
										<?PHP include("inc/estadisticas_buscador.php"); ?>
									</div>
								</div>
								<div class="row-fluid">
									<div class="widget">
										<div class="widget-header">
											<span class="title"><?PHP echo ucwords(txtcod($nombredet)); ?></span>
										</div>
										<div class="widget-content table-container">
											<div style="width:728px; margin:auto; height:auto;">
												<div style="float:left; width:220px">
													<h3>Comentarios: por secci&oacute;n</h3>
													<?PHP
													$sql2 = " AND (DATE(img.fecha) BETWEEN '" . $fechadesde . "' AND '" . $fechahasta . "')";
													$sql = "SELECT COUNT(img.id) AS canti, tipo.detalle FROM intranet_comentarios AS img
                            INNER JOIN intranet_tablas AS tipo ON tipo.id = img.tipo
														WHERE 1
														".$sql2."
                            GROUP BY img.tipo";
													$res = fullQuery($sql);
													//echo '<br>'.$sql;
													while ($dato = mysqli_fetch_array($res)) {
													?>
														<div style="border:solid #CCC 1px; width:200px; height:40px; margin-bottom:10px; padding:5px">
															<div>
																<div>
																	<span class="tit">
																		<?PHP echo txtcod($dato['detalle']); ?>
																	</span>
																	<br />
																	<span class="nom">
																		Accesos: <?PHP echo $dato['canti']; ?>
																	</span>
																</div>
															</div>
														</div>
													<?PHP } ?>
												</div>
												<div style="float:left; width:220px">
													<h3>Ranking de Novedades</h3>
													<?PHP
													$sql = "SELECT COUNT(nov.id) AS canti, nov.titulo FROM intranet_comentarios AS img
                          INNER JOIN intranet_novedades AS nov ON nov.id = img.item
                          WHERE 1 ".$sql2." AND img.tipo = 7
                          GROUP BY img.item ORDER BY canti DESC";
													$res = fullQuery($sql);
													//echo '<br>'.$sql;
													while ($dato = mysqli_fetch_array($res)) {
														$accesos = $dato['canti'];
													?>
														<div style="border:solid #CCC 1px; width:200px; height:40px; margin-bottom:10px; padding:5px">
															<div>
																<div>
																	<span class="tit">
																		<?PHP echo txtcod($dato['titulo']) . ': ' . $accesos; ?>
																	</span>
																</div>
															</div>
														</div>
													<?PHP } ?>
												</div>
												<!--<div style="float:left; width:220px">
													<h3>Ranking de comentarios en </h3>
													<?PHP
													$sql = "SELECT COUNT(nov.id) AS canti, nov.titulo FROM intranet_comentarios AS img
															INNER JOIN intranet_beneficios AS nov ON nov.id = img.item
															WHERE img.tipo = 9
															GROUP BY img.item ORDER BY canti DESC";
													$res = fullQuery($sql);
													while ($dato = mysqli_fetch_array($res)) {
														$accesos = $dato['canti'];
													?>
														<div style="border:solid #CCC 1px; width:200px; height:40px; margin-bottom:10px; padding:5px">
															<div>
																<div>
																	<span class="tit">
																		<?PHP echo txtcod($dato['titulo']) . ': ' . $accesos; ?>
																	</span>
																</div>
															</div>
														</div>
													<?PHP } ?>
												</div>-->
												<div style="clear:both;"></div>
											</div>
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
	<script src="assets/jui/timepicker/jquery-ui-timepicker.min.js"></script>
	<script src="assets/jui/js/i18n/jquery.ui.datepicker-es.js"></script>
	<script src="assets/js/demo/ui_comps.js"></script>
	<script src="js/scripts.js"></script>

</body>

</html>