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
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>Ranking de p&aacute;ginas accedidas</span>
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
												<!--<div style="float:left; width:330px">
													<h3>Ranking de p&aacute;ginas accedidas<br />por empresas</h3>
													<?PHP /*
													$sql = "SELECT
                                                                    SUM(intranet_accesos_detalle.accesos) AS accesos, intranet_empleados.empresa, intranet_accesos_detalle.fecha AS fecha, intranet_empresas.*
                                                                FROM
                                                                    intranet_accesos_detalle
                                                                    INNER JOIN intranet_empleados
                                                                        ON (intranet_empleados.id = intranet_accesos_detalle.empleado)
                                                                    INNER JOIN intranet_empresas
                                                                        ON (intranet_empleados.empresa = intranet_empresas.id)
                                                                        WHERE 1 ";
													if ($fecha != 'tot') {
														$sql .= " AND (DATE(intranet_accesos_detalle.fecha) BETWEEN '$fechadesde' AND '$fechahasta')";
													}
													$sql .= "
                                                                        GROUP BY intranet_empresas.id
                                                                        ORDER BY accesos DESC LIMIT 10";
													$res = fullQuery($sql);
													while ($dato = mysqli_fetch_array($res)) {
														$accesos = (isset($dato['accesos']) && $dato['accesos'] > 0) ? $dato['accesos'] : 'Ninguno';
														*/
													?>
														<div style="border:solid #CCC 1px; width:200px; height:40px; margin-bottom:10px; padding:5px">
															<div>
																<div>
																	<span class="tit">
																		<?PHP //echo empresa($dato['empresa']); ?>
																	</span>
																	<br />
																	<span class="nom">
																		Accesos: <?PHP //echo $accesos; ?>
																	</span>
																</div>
															</div>
														</div>
													<?PHP // }?>
												</div> -->
												<?PHP /*<div style="float:left; width:220px">
                                                            <h3>Ranking de p&aacute;ginas accedidas<br />por &aacute;reas</h3>
                                                            <?PHP
                                                            $sql = "SELECT
                                                                        iemp.id AS empresa, iarea.nombre AS nomarea
                                                                        , intranet_tablas.detalle AS seccion
                                                                        , COUNT(*) AS accesos
                                                                    FROM
                                                                        intranet_empleados AS emp
                                                                        INNER JOIN intranet_accesos_detalle AS iadet
                                                                            ON (emp.id = iadet.empleado)
                                                                        INNER JOIN intranet_areas AS iarea 
                                                                            ON (emp.area = iarea.id)
                                                                        INNER JOIN intranet_empleados AS iemp
                                                                            ON (emp.empresa = iemp.id)
                                                                        INNER JOIN intranet_tablas 
                                                                            ON (intranet_tablas.id = iadet.seccion)
                                                                            WHERE 1 ";
                                                                        if($fecha != 'tot'){
                                                                            $sql.= " AND (DATE(iadet.fecha) BETWEEN '$fechadesde' AND '$fechahasta')";
                                                                        }
                                                                        $sql.= "
                                                                            GROUP BY empresa,nomarea,seccion
                                                                            ORDER BY accesos DESC
                                                                            ";
                                                            $res = fullQuery($sql);
                                                            while($dato = mysqli_fetch_array($res)){
                                                                $accesos = (isset($dato['accesos']) && $dato['accesos'] > 0) ? $dato['accesos'] : 'Ninguno';
                                                            ?>
                                                            <div style="border:solid #CCC 1px; width:200px; height:40px; margin-bottom:10px; padding:5px">
                                                                <div>
                                                                    <div>
                                                                        <span class="tit">
                                                                            <?PHP echo empresa($dato['empresa']).': '.$dato['nomarea'];?>
                                                                        </span>
                                                                        <br />
                                                                        <span class="nom">
                                                                            <?PHP echo $dato['seccion'];?>: <?PHP echo $dato['accesos'];?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?PHP }?>
                                                        </div>
														<?PHP */ ?>
												<div style="float:left; width:100%">
													<h3>Secci&oacute;n m&aacute;s accedida</h3><br />
													<?PHP
													$sql = "SELECT SUM(iad.accesos) AS accesos, it.detalle, iad.fecha AS fecha, iad.seccion
                          FROM intranet_accesos_detalle AS iad
                        	INNER JOIN intranet_tablas AS it ON (it.id = iad.seccion)
                          WHERE 1 ";
													if ($fecha != 'tot') {
														$sql .= " AND (DATE(iad.fecha) BETWEEN '$fechadesde' AND '$fechahasta')";
													}
													$sql .= "
                          GROUP BY it.id
                          ORDER BY accesos DESC";
													$res = fullQuery($sql);
													while ($dato = mysqli_fetch_array($res)) {
														$accesos = (isset($dato['accesos'])) ? $dato['accesos'] : 'Ninguno';
													?>
														<div style="border:solid #CCC 1px; width:200px; height:40px; margin-bottom:10px; padding:5px">
															<div>
																<div>
																	<span class="tit">
																		<?PHP echo txtcod($dato['detalle']); ?>
																	</span>
																	<br />
																	<span class="nom">
																		Ingresos: <?PHP echo $accesos; ?>
																	</span>
																</div>
															</div>
														</div>
													<?PHP } ?>
												</div>
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