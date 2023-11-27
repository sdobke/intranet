<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
include_once("inc/sechk.php");
$backend = 1;
if (isset($cadmu) && $cadmu > 0) {
	$tipo = $row_admu['tabla'];
}
$tipo   = (isset($tipo)) ? $tipo : getPost('tipo');
$emp_nom = config('nombre');

$error = new Errores();
if ($tipo == 0) {
	$tipo = config('tabla_defecto');
}
if (!isset($_SESSION['sestipo']) || (isset($_SESSION['sestipo']) && $_SESSION['sestipo'] != $tipo)) {
	$_SESSION['sestipo'] = $tipo;
	$_SESSION['pagi'] = 1;
}
include("inc/leer_parametros.php");
if ($tipodet == 'novedades' && isset($backend)) { // Si es novedad
	if (isset($_SESSION['secc'])) {
		$secc = $_SESSION['secc'];
		if (getPost('secc', 0) != $secc) {
			$_SESSION['secc'] = getPost('secc', 0);
			$_SESSION['pagi'] = 1;
		}
	} else {
		$secc = getPost('secc', 0);
		$_SESSION['secc'] = $secc;
		$_SESSION['pagi'] = 1;
	}
	$secc = $_SESSION['secc'];
}
$otro_query = '';
if ($tipodet == 'empleados') {
	$emacti = getPost("bus_empactivos");
	if ($emacti == 1) {
		$otro_query .= ' AND activo = 1 ';
	}
	if ($emacti == 2) {
		$otro_query .= ' AND activo = 0 ';
	}
}
$id = getPost('id');
if (empty($id))
	$id = 0;
if ($anidable == 1) {
	$otro_query .= " AND parent = 0 ";
}
/*
if($id > 0 && isset($_GET['opciond']) && $_GET['opciond'] == 'Elim'){
	include("inc/baja.php");
}
*/
include("inc/bajamulti.php");
$order_by = (parametro("orden_backend", $tipo) != '') ? parametro("orden_backend", $tipo) : $order_by;
include_once("inc/query_busqueda.php");
//$query = $cadbusca;
//$limit = 0;//Cantidad de resultados por página
$limit = (parametro("cantidad", $tipo) != '') ? parametro("cantidad", $tipo) : 20;
include_once("inc/prepara_paginador.php");
//echo $query;
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
	<script type="text/javascript">
		function formSubmit() {
			document.getElementById("formulario").submit();
		}
	</script>
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
										<?PHP echo ucwords(txtcod($nombredet)); ?>: listado
									</li>
								</ul>

								<h1 id="main-heading">
									<?PHP echo ucwords(txtcod($nombredet)); ?> <span>Listado Avanzado</span>
								</h1>
							</div>
							<div id="main-content">
								<div class="row-fluid">
									<div class="span12 widget">
										<div class="widget-header">
											<span class="title"><i class="icos-looking-glass"></i> Buscar</span>
										</div>
										<?PHP
										include("inc/buscador.php"); ?>
									</div>
								</div>
								<?PHP if ($contar > 0) { ?>
									<div class="row-fluid">
										<div class="widget">
											<div class="widget-header">
												<span class="title"><?PHP echo ucwords(txtcod($nombredet)); ?></span>
											</div>
											<div class="widget-content table-container">
												<form method="POST" action="listado.php" id="formulario">
													<input name="tipo" type="hidden" value="<?PHP echo $tipo; ?>" />
													<table class="table table-striped table-checkable">
														<thead>
															<tr>
																<th class="checkbox-column">
																	<input type="checkbox" class="uniform">
																</th>
																<?PHP
																$tipotit_ver = ucwords($tipotit);
																if ($tipodet == "empleados") {
																	$tipotit_ver = "Apellido y Nombre";
																}
																if ($usafotos == 1) {
																	include_once("inc/img.php");
																	echo '<th>Imagen</th>';
																}
																?>
																<th><?PHP echo $tipotit_ver; ?></th>
																<?PHP if ($usacolor == 1) {
																	echo '<th>Color</th>';
																} ?>
																<?PHP if ($usafecha > 0) {
																	$texto_fecha = ($usafecha == 2) ? 'Vencimiento' : 'Fecha';
																	echo '<th>' . $texto_fecha . '</th>';
																} ?>
																<?PHP if ($usahora == 1) {
																	echo '<th>Hora</th>';
																} ?>
																<?PHP if ($tipodet == "sugerencias") {
																	echo '<th>Tema</th>';
																} ?>
																<?PHP if ($tipodet == 'clasificados' || $tipodet == 'recomendados') {
																	echo '<th>Activo</th>';
																} ?>
															</tr>
														</thead>
														<tbody>
															<?PHP
															$contnot = 0;
															$result   = fullQuery($query);
															$contador = mysqli_num_rows($result);
															if ($contador > 0) {
																$notiarr = array();
																while ($noti = mysqli_fetch_array($result)) {
																	$notiid = $noti['id'];
																	$notiarr[][] = $noti;
																	if ($anidable == 1) {
																		$childarr = array();
																		$newnoti = ChildList($_SESSION['prefijo'] . $nombretab, $notiid);
																		$notiarr[] = $newnoti;
																	}
																}
																/*
																echo '<br><pre>';
																print_r($notiarr);
																echo '</pre>';
																*/
																$notiarr = deepValues($notiarr);

																if (!empty($notiarr)) {
																	foreach ($notiarr as $noticia) {
																		$novid = $item = $noticia['id'];
																		if (isset($noticia['nomparent'])) {
																			//$noticia['nombre'] = $noticia['nomparent'] . ' -> ' . $noticia['nombre'];
																		}
																		$contnot++;
															?>
																		<tr>
																			<td class="checkbox-column">
																				<input type="checkbox" class="uniform" name="borrar_<?PHP echo $novid; ?>">
																				<input name="id" type="hidden" id="id" value="<?PHP echo $novid; ?>" />
																			</td>
																			<?PHP
																			if ($usafotos == 1) { // imagen principal
																				echo '<td><a href="detalles.php?tipo=' . $tipo . '&id=' . $novid . '">';
																				include("inc/muestra_imagen_ppal.php");
																				echo '</a></td>';
																			}
																			$estilo_titulo = ($usacolor == 1) ? 'style="font-weight:bold; color:#' . $noticia['color'] . '"' : '';
																			if (is_numeric($tipotit)) { // Si se guardó un nro en vez de título, es el nro de tabla. Se busca el nombre de ese item
																				$tab_cso = obtenerNombre($tipotit);
																				$campo_singular = ($tipodet == "sectores") ? substr($tab_cso, 0, -2) : substr($tab_cso, 0, -1); // Si es proveedores, saca 2 letras (es)
																				$sql_cso = "SELECT * FROM " . $prefijo . $tab_cso . " WHERE id = " . $noticia[$campo_singular];
																				$titulo = $sql_cso;
																				$res_cso = fullQuery($sql_cso);
																				$row_cso = mysqli_fetch_array($res_cso);
																				$titulo  = $row_cso['nombre'];
																				if (is_numeric($titulo)) {
																					$tabla_nom = obtenerNombre($titulo);
																					$id_nom = $row_cso['id'];
																					$sql_nom = "SELECT nombre FROM " . $prefijo . $tabla_nom . " WHERE id = " . $id_nom;
																					$titulo = $sql_nom;
																					$res_nom = fullQuery($sql_nom);
																					$row_nom = mysqli_fetch_array($res_nom);
																					//$titulo = $row_nom['nombre'];
																				}
																			} else {
																				$titulo = ($tipotit != '') ? $noticia[$tipotit] : '';
																			}
																			if ($tipodet == "empleados") { // Empleados
																				$titulo = ucwords(strtolower($noticia['apellido'] . ', ' . $titulo));
																			}
																			?>
																			<td><a href="detalles.php?tipo=<?PHP echo $tipo; ?>&id=<?PHP echo $novid; ?>"><?PHP echo txtcod($titulo); ?></a></td>
																			<?PHP if ($usacolor == 1) {
																				echo '<td><div style="float:left; margin-right:5px; width:20px; height:20px; background-color:#' . $noticia['color'] . '">&nbsp;</div></td>';
																			}
																			if ($usafecha > 0) {
																				echo '<td>' . FechaDet($noticia['fecha'], 'largo', 's') . '</td>';
																			}
																			if ($usahora == 1) {
																				echo '<td>' . substr($noticia['hora'], 0, 5) . '</td>';
																			}
																			if ($tipodet == "sugerencias") {
																				echo '<td>' . cortarTexto(txtcod(obtenerDato('nombre','sugerencias_temas',$noticia['tema'])), 105, $puntos = "...") . '</td>';
																			}
																			if ($tipodet == 'clasificados' || $tipodet == 'recomendados') {
																				$tdcolor = '';
																				if ($noticia['activo'] == 0) {
																					$tdcolor = ' class="inactivo"';
																				}
																			?>
																				<td <?PHP echo $tdcolor; ?>><?PHP echo ($noticia['activo'] == 1) ? 'activo' : '<a href="detalles.php?tipo=' . $tipo . '&id=' . $novid . '"><div class="inactivos">inactivo</div></a>'; ?></td>
																			<?PHP } ?>
																		</tr>
																<?PHP } //Cierra While
																} // if not empty
																?>
															<?PHP } //Cierra If
															?>
														</tbody>
													</table>
												</form>
											</div>
											<div class="toolbar btn-toolbar">
												<div class="btn-group">
													<span class="btn" onclick="formSubmit()"><i class="icol-bin-closed"></i> Borrar Seleccionados</span>
												</div>
											</div>
										</div>
									</div>
									<?PHP
									$variables = "busqueda=" . $busqueda . "&tipo=" . $tipo; // variables para el paginador
									if ($tipodet == 'novedades')
										$variables .= "&secc=" . $secc;
									echo '<div align="center">' . paginador($limit, $contar, $pag, $variables) . '</div>';
									?>
								<?PHP } //Cierra If
								?>
							</div>
							<?php
							if($tipodet == 'empleados'){
								$sql_empac = "SELECT * FROM intranet_empleados WHERE activo = 1 AND del = 0 ORDER BY ulting";
								$res_empac = fullQuery($sql_empac);
								if(mysqli_num_rows($res_empac) > 0){
									echo '<div style="padding:50px"><table class="table table-striped">
									<thead><tr><th>Apellido</th><th>Nombre</th><th>Ultimo Ingreso</th></tr></thead>';
									while($row_empac = mysqli_fetch_array($res_empac)){
										$ulting = ($row_empac['ulting'] == '0000-00-00' || $row_empac['ulting'] == '') ? 'Nunca' : fechaDet($row_empac['ulting'],'corto');
										echo '<tr><td>'.txtcod($row_empac['apellido']).'</td><td>'.txtcod($row_empac['nombre']).'</td><td>'.$ulting.'</td></tr>';
									}
									echo '</table></div>';
								}
							}
							?>
						</section>
					</div>
				</div>
			</div>
		</div>

		<?PHP include("footer.php"); ?>

	</div>
	<?PHP include("scripts_base.php"); ?>
	<script src="js/scripts.js"></script>
	<script>
		$(document).ready(function() {
			$("select.areas").change(function() {
				var areaSelected = $(".areas option:selected").val();
				$.ajax({
					type: "POST",
					url: "listado_sub.php",
					data: {
						area: areaSelected
					}
				}).done(function(data) {
					$("#subareas").html(data);
				});
			});
		});
	</script>
</body>

</html>