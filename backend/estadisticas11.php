<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
include_once("inc/sechk.php");
$backend = 1;
$emp_nom = config('nombre');
$nombredet = "Estad&iacute;sticas";
$error = new Errores();

function getDia($dato)
{
	$dev = '';
	switch ($dato) {
		case 1:
			$nomdia = 'Domingo';
			break;
		case 2:
			$nomdia = 'Lunes';
			break;
		case 3:
			$nomdia = 'Martes';
			break;
		case 4:
			$nomdia = 'Miercoles';
			break;
		case 5:
			$nomdia = 'Jueves';
			break;
		case 6:
			$nomdia = 'Viernes';
			break;
		case 7:
			$nomdia = 'Sábado';
			break;
	}
	if (isset($nomdia)) {
		$dev = $nomdia;
	}
	return $dev;
}
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
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>Accesos por areas</span>
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
										<br><br>
										<div class="widget-content table-container">
										
											<div style="width:728px; margin:auto; height:auto">
												<h1 align="center">Accesos por areas</h1>
												<?PHP

													$sql = " SELECT intranet_areas.nombre AS areas, intranet_areas.id AS total_accesos";
													$sql .= " FROM intranet_accesos_detalle ";
													$sql .= " inner JOIN intranet_empleados_areas ON intranet_accesos_detalle.empleado = intranet_empleados_areas.empleado ";
													$sql .= " INNER JOIN intranet_areas ON  intranet_empleados_areas.area = intranet_areas.id ";
													$sql .= " WHERE intranet_accesos_detalle.fecha BETWEEN '$fechadesde' AND '$fechahasta' "; 
													$sql .= " GROUP BY intranet_areas.id, intranet_areas.nombre ";
													
													$res = fullQuery($sql);
													$chartData  = '';
													$contador = 0;
													$chartData = " [['Areas', 'Total Accesos', { role: 'style' }],";

													//Pasarla a un fichero para poder consumirla de ambos ficheros de estadisticas
													function generateRandomColor() {
														$red = rand(0, 255);
														$green = rand(0, 255);
														$blue = rand(0, 255);
														
														return "rgb({$red}, {$green}, {$blue})";
													}


													while ($dato = mysqli_fetch_array($res)) {
														$color = generateRandomColor();
														$chartData .= "['{$dato['areas']}', {$dato['total_accesos']}, 'color: $color'],";
												?>
												<?php
													} 
													
													$chartData = rtrim($chartData, ','); 
													$chartData .= "]";
												?>
											</div>
											<div style="clear:both;"></div>
											<div style="width:728px; margin:auto; height:auto">
											<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

												<script type="text/javascript">
													google.charts.load('current', {'packages':['corechart']});
													

													function barHeight(){
														var chartData = <?php echo $chartData; ?>;
														return chartData.length * 35;
													}

													
													function drawVisualization() {
														
														var chartData = <?php echo $chartData; ?>;
														
														if(chartData[1] != undefined) { 
															google.charts.setOnLoadCallback(function() {
																var data = google.visualization.arrayToDataTable(chartData);

																var view = new google.visualization.DataView(data);
																	view.setColumns([
																		0,
																		1,
																		{
																		calc: "stringify",
																		sourceColumn: 1,
																		type: "string",
																		role: "annotation",
																		},
																		2,
																	]);

																	var options = {
																		title: "Total de Accesos por areas",
																		width: 700,
																		height: barHeight(),
																		bar: { groupWidth: "95%" },
																		legend: { position: "none" },
																	};

																	var chart = new google.visualization.BarChart(document.getElementById("rango"));
																	chart.draw(view, options);
																});
															}
														}

														drawVisualization();
														google.charts.setOnLoadCallback(drawVisualization);
												</script>
																				
												<div id="rango" style="width: 900px; height: "></div>

												<script>
													 document.getElementById("rango").style.height = barHeight() +15;
												</script>
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