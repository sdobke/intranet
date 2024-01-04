<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
include_once("inc/sechk.php");
include_once("inc/libreriasJs.php");
$backend = 1;
$emp_nom = config('nombre');
$nombredet = "Estad&iacute;sticas";
$error = new Errores();
$titleDia = "Accesos por dia";
$titleMes = "Accesos por dia del mes";
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
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>Accesos por d&iacute;a (s&oacute;lo usuarios que loguearon)</span>
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
											<?PHP
											$sql = "SELECT SUM(accesos) AS cant, DAYOFWEEK(fecha) AS dia FROM intranet_accesos_detalle 
											WHERE 1 ";
											if ($fecha != 'tot') {
												$sql .= " AND (DATE(fecha) BETWEEN '$fechadesde' AND '$fechahasta')";
											}
											$sql .= "
											AND empleado > 0 GROUP BY DAYOFWEEK(fecha) ORDER BY DAYOFWEEK(fecha)";
											//echo $sql;
											$chart = '';
											$contador = 0;
											$res = fullQuery($sql);
											while ($dato = mysqli_fetch_array($res)) {
												$nomdia = getDia($dato['dia']);
											?>
												<div class="estad_grp">
													<div style="height:30px;" class="tit">
														<?PHP echo $nomdia; ?>: <span class="nom"><?PHP echo $dato['cant']; ?></span>
													</div>
												</div>
												<?PHP
												$chart .= 'data.addRow(["' . substr($nomdia, 0, 3) . '", ' . $dato['cant'] . ']);';
												$chartPdf[] = ["dia" => substr($nomdia, 0, 3), "cant" => $dato['cant']]; 
												$contador++;
												?>
											<?PHP } 
												include_once("inc/export_estadisticas.php");
											?>
											<div style="width:728px; margin:auto; height:auto">
												<script type="text/javascript">
													google.load('visualization', '1', {
														packages: ['corechart']
													});
												</script>
												<script type="text/javascript">
													function drawVisualization() {

														let mes = document.getElementById('mes');
														let mesOpcion = mes.options[mes.selectedIndex].text;
														let title = '<?php echo $titleDia ?>';
														let ano = document.getElementById('ano');
														let anoOpcion = ano.options[ano.selectedIndex].text;
														let dataChart = '<?PHP echo json_encode($chartPdf); ?>';
														console.log(dataChart);
														// Create and populate the data table.
														var data = new google.visualization.DataTable();
														data.addColumn('string', 'Días');
														data.addColumn('number', 'Visitas');
														<?PHP echo $chart; ?>
														// Create and draw the visualization.
														new google.visualization.LineChart(document.getElementById('semanas')).
														draw(data, {
															width: 700,
															height: 300
														});
														verificarExistenciaImagen('dia_semanas', mesOpcion, anoOpcion, 'semanas')
											
														var xhttp = new XMLHttpRequest();
														xhttp.onreadystatechange = function() {
															if (this.readyState == 4 && this.status == 200) {
																console.log(this.responseText);
															}
														};
														console.log(dataChart);
														xhttp.open("POST", "inc/create_pdf.php", true);
														xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
														var postData = "mesOpcion=" + encodeURIComponent(mesOpcion)
																		+ "&anoOpcion=" + encodeURIComponent(anoOpcion) 
																		+ "&title=" + encodeURIComponent(title) 
																		+ "&location=" + encodeURIComponent('dia_semanas')
																		+ "&chartData=" + encodeURIComponent(dataChart);

														xhttp.send(postData);
													}
													google.setOnLoadCallback(drawVisualization);
												</script>
												<div id="semanas"></div>
												<button id="downloadToDeviceButton" data-location="dia_semanas" class="btn btn-primary btn-small">Descargar PDF</button>
											</div>
											<div style="width:728px; margin:auto; height:auto">
												<h1 align="center">Accesos por d&iacute;a del mes</h1>
												<?PHP
												$sql = "SELECT SUM(accesos) AS cant, DAYOFMONTH(fecha) AS dia FROM intranet_accesos_detalle 
														WHERE 1 ";
												if ($fecha != 'tot') {
													$sql .= " AND (DATE(fecha) BETWEEN '$fechadesde' AND '$fechahasta')";
												}
												$sql .= " AND empleado > 0 GROUP BY DAYOFMONTH(fecha) ORDER BY DAYOFMONTH(fecha)";
												//echo $sql;
												$res = fullQuery($sql);
												$chart = '';
												$contador = 0;
												while ($dato = mysqli_fetch_array($res)) {
												?>
													<div class="estad_grp" style="width:70px;">
														<div style="height:30px;" class="tit"><?PHP echo $dato['dia']; ?>: <span class="nom"><?PHP echo $dato['cant']; ?></span></div>
													</div>
													<?PHP
													$chart .= 'data.addRow(["' . $dato['dia'] . '", ' . $dato['cant'] . ']);';	
													$chartPdf2[] = ["dia" => $dato['dia'] , "cant" => $dato['cant']]; 
													$contador++;
													?>
												<?PHP } ?>
											</div>
											<div style="clear:both;"></div>
											<div style="width:728px; margin:auto; height:auto">
												<script type="text/javascript">
													google.load('visualization', '1', {
														packages: ['corechart']
													});
												</script>
												<script type="text/javascript">
													function drawVisualization() {

														let mes = document.getElementById('mes');
														let mesOpcion = mes.options[mes.selectedIndex].text;
														let title = '<?php  echo $titleMes ?>';
														let ano = document.getElementById('ano');
														let anoOpcion = ano.options[ano.selectedIndex].text;
														let dataChart = '<?PHP echo json_encode($chartPdf2); ?>';
														// Create and populate the data table.
														var data = new google.visualization.DataTable();
														data.addColumn('string', 'Días');
														data.addColumn('number', 'Visitas');
														<?PHP echo $chart; ?>
														// Create and draw the visualization.
														new google.visualization.LineChart(document.getElementById('meses')).
														draw(data, {
															width: 700,
															height: 300
														});
														verificarExistenciaImagen('dia_mes', mesOpcion, anoOpcion, 'meses')
														var xhttp = new XMLHttpRequest();
														xhttp.onreadystatechange = function() {
															if (this.readyState == 4 && this.status == 200) {
																console.log(this.responseText);
															}
														};
														console.log(dataChart);
														xhttp.open("POST", "inc/create_pdf.php", true);
														xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
														var postData = "mesOpcion=" + encodeURIComponent(mesOpcion)
																		+ "&anoOpcion=" + encodeURIComponent(anoOpcion) 
																		+ "&title=" + encodeURIComponent(title) 
																		+ "&location=" + encodeURIComponent('dia_mes')
																		+ "&chartData=" + encodeURIComponent(dataChart);

														xhttp.send(postData);
													}
													google.setOnLoadCallback(drawVisualization);
												</script>
												<div id="meses"></div>
												<button id="downloadToDeviceButtonDos" data-location="dia_mes" class="btn btn-primary btn-small">Descargar PDF</button>
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