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
	<link rel="stylesheet" href="plugins/zebradp/css/mooncake/zebra_datepicker.css" media="screen">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" media="all">
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
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>Secciones por meses</span>
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
											<?PHP
											$sql2 = "SELECT sum(acc.accesos) AS accesos, sec.nombre AS nombre, sec.id AS secid
													FROM intranet_accesos_detalle AS acc
													INNER JOIN intranet_tablas AS sec ON acc.seccion = sec.id 
													INNER JOIN intranet_empleados AS emp ON emp.id = acc.empleado
													INNER JOIN intranet_areas AS are ON emp.area = are.id
												WHERE 1 ";
											//if($fecha != 'tot'){
											$sql2 .= " AND (DATE(acc.fecha) BETWEEN '" . $fechadesde . "' AND '" . $fechahasta . "')";
											//	}
											$sql2 .= " GROUP BY acc.seccion
													ORDER BY accesos DESC "; //LIMIT 1,1000";
											$res2 = fullQuery($sql2);
											$all_secc = array();
											while ($dato2 = mysqli_fetch_array($res2)) { // MUESTRA DATOS DE CADA SECCION
												$seccid = $dato2['secid'];
												$all_secc[$seccid] = 0;
											}

											// Preparación de gráfica
											$perhasta = substr($fechahasta, 0, 4) . substr($fechahasta, 5, 2);
											$perdesde = substr($fechadesde, 0, 4) . substr($fechadesde, 5, 2);

											$sql_graf = "SELECT PERIOD_DIFF('" . $perhasta . "','" . $perdesde . "') AS meses";
											$res_graf = fullQuery($sql_graf);
											$row_graf = mysqli_fetch_array($res_graf);
											$meses = $row_graf['meses'];
											//if($meses > 0){ // Si hay más de 1 mes de período
											if ($fecha == 'mes') { // Si es un mes solamente
												$res3 = fullQuery($sql2);
												include_once("inc/export_estadisticas.php");
											?>
											
											
											
												<script type="text/javascript">
													google.load("visualization", "1", {
														packages: ["corechart"]
													});
													google.setOnLoadCallback(drawChart);

													function drawChart() {
														let mes = document.getElementById('mes');
														let mesOpcion = mes.options[mes.selectedIndex].text;

														let ano = document.getElementById('ano');
														let anoOpcion = ano.options[ano.selectedIndex].text;

														var data = google.visualization.arrayToDataTable([
															['Sección', 'Accesos'],
															<?PHP
															$mosdat = '';
															while ($dato3 = mysqli_fetch_array($res3)) { // MUESTRA DATOS DE CADA SECCION
																if ($dato3['accesos'] > 0) {
																	$mosdat .= "['" . $dato3['nombre'] . "', " . $dato3['accesos'] . "],";
																}
															}
															$mosdat = substr($mosdat, 0, -1);
															echo $mosdat;
															?>
														]);

														var options = {
															width: 1200,
															height: 800,
															bar: {
																groupWidth: "95%"
															},
															legend: {
																position: "none"
															},
														};
														var view = new google.visualization.DataView(data);
														var chart = new google.visualization.ColumnChart(document.getElementById("grafico"));
														chart.draw(view, options);
														console.log("paso 1");

														verificarExistenciaImagen('seccion_meses',mesOpcion, anoOpcion)
													}
												</script>
												<div style="overflow: hidden; width:1130px; margin:5px" id="marco_grafico">
													<!--[if !IE]><!-->
													<div id="grafico" style="height:650px; position:relative; top:-110px; left:-110px; width:1300px"></div>
													


													<!--<![endif]-->
													<!--[if IE]>
                            	<div id="secmesesofi" style="height:650px; position:relative; top:0px; left:-10px; width:950px"></div>
                            <![endif]-->
												</div>
												<button id="downloadToDeviceButton" data-location="seccion_meses" class="btn btn-primary btn-small">Descargar al Dispositivo</button>
											<?PHP
											} else { // período
												$conso_inicio = $conso_mes = strtotime($fechadesde);
												$conso_fin    = strtotime($fechahasta);
												$cantimeses = 0;
												$chart = '';
												while ($conso_mes < $conso_fin) { // RECORRE CADA MES
													$sql2_graf = "
								 SELECT SUM(acc.accesos) AS accesos, sec.nombre AS nombre, sec.id AS secid
									FROM intranet_accesos_detalle AS acc
									INNER JOIN intranet_tablas AS sec ON acc.seccion = sec.id 
									INNER JOIN intranet_empleados AS emp ON emp.id = acc.empleado
									INNER JOIN intranet_areas AS are ON emp.area = are.id
									WHERE 1 ";
													//if($fecha != 'tot'){
													$sql2_graf .= " AND (DATE(acc.fecha) BETWEEN '" . date('Y-m', $conso_mes) . "-01' AND '" . date('Y-m', $conso_mes) . "-31')";
													//}
													$sql2_graf .= "
									AND emp.oficinas = 1
									GROUP BY acc.seccion
									ORDER BY accesos DESC
									"; //LIMIT 1,1000";
													$res2_graf = fullQuery($sql2_graf);
													$chart .= "data.setValue(" . $cantimeses . ", 0, '" . FechaDet(date('Y-m-d', $conso_mes), $formato = 'm-y') . "');
							";
													foreach ($all_secc as $id_secc => $valor_secc) {
														$all_secc[$id_secc] = 0;
													}
													while ($dato2_graf = mysqli_fetch_array($res2_graf)) {
														$cantid_accesos = $dato2_graf['accesos'];
														foreach ($all_secc as $id_secc => $valor_secc) {
															if ($id_secc == $dato2_graf['secid']) {
																$all_secc[$id_secc] = $cantid_accesos;
															}
														}
													}
													$conso_mes = strtotime("+1 month", $conso_mes);
													$contar_secc = 1;
													foreach ($all_secc as $id_secc => $valor_secc) {
														$chart .= "data.setValue(" . $cantimeses . ", " . $contar_secc . ", " . $valor_secc . ");
								";
														$contar_secc++;
													}
													$cantimeses++;
												}
											?>
												<script type="text/javascript">
													google.load('visualization', '1', {
														packages: ['corechart']
													});
												</script>
												<script type="text/javascript">
													function drawVisualization() {
														// Create and populate the data table.
														var data = new google.visualization.DataTable();
														data.addColumn('string', 'Mes');
														<?PHP
														foreach ($all_secc as $id_secc => $valor_secc) {
															echo "data.addColumn('number', '" . txtcod(obtenerDato('detalle', 'tablas', $id_secc)) . "');
								";
														}
														?>
														data.addRows(<?PHP echo $cantimeses; ?>);
														<?PHP echo $chart; ?>
														// Create and draw the visualization.
														new google.visualization.LineChart(document.getElementById('secmesesofi')).
														draw(data, {
															width: 1200,
															height: 800
														});
													}
													google.setOnLoadCallback(drawVisualization);
												</script>
												<div style="overflow: hidden; width:1130px" id="marco_grafico">
													<!--[if !IE]><!-->
													<div id="secmesesofi" style="height:650px; position:relative; top:-110px; left:-110px; width:1300px"></div>
													<!--<![endif]-->
													<!--[if IE]>
                            	<div id="secmesesofi" style="height:650px; position:relative; top:0px; left:-10px; width:950px"></div>
                            <![endif]-->
												</div>
											<?PHP
											}/*else{
						echo 'Seleccione un per&iacute;odo mayor a un mes para ver la gr&aacute;fica.';
					}*/
											// Fin preparación de gráfica
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
	<script src="assets/jui/timepicker/jquery-ui-timepicker.min.js"></script>
	<script src="assets/jui/js/i18n/jquery.ui.datepicker-es.js"></script>
	<!-- Demo Scripts -->
	<script src="assets/js/demo/ui_comps.js"></script>
	<script src="js/scripts.js"></script>
	<script src="plugins/zebradp/zebra_datepicker.min.js"></script>
</body>

</html>