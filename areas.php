<?PHP
include "inc/startup.php";
$solo_subareas = 1; // Si no hay empleados en las áreas se muestran solamente subareas. Esto es solamente para que muestre directamente la subárea de un área si es la única
$tipo   = 1;
agrega_acceso($tipo);
$nombre = obtenerNombre($tipo);
$titsec = $nombre;
$cod    = getPost('cod', 1);
$limit  = cantidad('cant_emple'); //Cantidad de resultados por página
$areastit = '';
$link_destino_search = "empleados.php";
$empresa = obtenerDato('nombre', 'empresas', $cod);
?>
<!DOCTYPE html>
<html>

<head>
	<title><?PHP echo $cliente; ?> Intranet | Areas</title>
	<?PHP //include("head_marcas.php"); 
	?>
	<?PHP include("sitio/head.php"); ?>
	<link href="css/jstip.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" language="javascript" src="js/jstip.js"></script>
</head>

<body>
	<div class="flex-wrapper">
		<?PHP include("sitio/header.php"); ?>
		<div class="container">
			<div class="contenido">
				<h1 class="mt-3"><?PHP echo $empresa; ?> | &Aacute;reas</h1>
				<!--
			<div class="col-md-4 col-sm-12 dark menu-marca" id="col-der">
				<?php //include "marca_menu.php"; 
				?>
			</div>
			-->
				<div class="col-md-12 col-sm-12">
					<div class="tituloseccion">
						<h3 class="mb-5">Nuestros Empleados</h3>
					</div>
					<div id="buscador_areas" class="buscador_interno">
						<div class="row align-items-center">
							<div class="col-12 col-sm-4">
								<h4 class="mb-0">Buscador de &Aacute;reas</h4>
							</div>
							<div class="col-12 col-sm-8">
								<form action="#" method="get">
									<div class="row">
										<div class="col-12 col-sm-6">
											<select name="areaemp" id="area" class="form-select" onchange="return sendFormArea('area');">
												<option value="0">Todas las áreas (seleccione un área)...</option>
												<?php
												$sqlpar = "SELECT * FROM " . $_SESSION['prefijo'] . "areas" . " WHERE del = 0 AND parent = 0 ORDER BY nombre";
												$areactiva = 0;
												if (isset($_REQUEST["areaemp"])) {
													$areactiva = $_REQUEST["areaemp"];
												}
												$respar = fullQuery($sqlpar);
												while ($rowpar = mysqli_fetch_array($respar)) {
													$nombredato = txtcod($rowpar['nombre']);
													echo '<option value="' . $rowpar['id'] . '" ' . optSel($rowpar['id'], $areactiva) . ' > ' . $nombredato . '</option>
											';
													echo optChild($_SESSION['prefijo'] . "areas", $rowpar['id'], $areactiva, $rowpar['nombre']);
												}
												?>
											</select>
										</div>
										<div class="col-12 col-sm-6">
											<select name="lugaremp" id="lugar" class="form-select" onchange="return sendFormArea('lugar');">
												<option value="0">Todos los lugares (seleccione un lugar)...</option>
												<?php
												$queryareasel = $innerareasel = '';
												if ($areactiva > 0) {
													$innerareasel = " INNER JOIN " . $_SESSION['prefijo'] . "empleados_areas iea ON iea.empleado = emp.id
													INNER JOIN " . $_SESSION['prefijo'] . "areas area ON area.id = iea.area ";
													$queryareasel = ($areactiva == 0) ? '' : ' AND area.id = ' . $areactiva . ' AND emp.del = 0 AND emp.activo = 1 ';
												}
												$sqlpar = "SELECT el.* FROM " . $_SESSION['prefijo'] . "empleados_lugar el
												INNER JOIN " . $_SESSION['prefijo'] . "empleados emp ON emp.lugar = el.id
												" . $innerareasel . "
												WHERE el.del = 0 
												" . $queryareasel . "
												GROUP BY el.id
												ORDER BY el.nombre";
												//echo $sqlpar;
												$lugaractivo = 0;
												if (isset($_REQUEST["lugaremp"])) {
													$lugaractivo = $_REQUEST["lugaremp"];
												}
												$respar = fullQuery($sqlpar);
												while ($rowpar = mysqli_fetch_array($respar)) {
													$nombredato = txtcod($rowpar['nombre']);
													echo '<option value="' . $rowpar['id'] . '" ' . optSel($rowpar['id'], $lugaractivo) . ' > ' . $nombredato . '</option>
											';
												}
												?>
											</select>
											<?php //echo $sqlpar;
											?>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php //if ( ( isset($_REQUEST["areaemp"]) && !empty($_REQUEST["areaemp"]) && isset($_REQUEST["cod"]) && !empty($_REQUEST["cod"]) || isset($_REQUEST['busqueda']) ) ) { 
					?>
					<div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
						<?php
						include 'empleados_include.php';
						?>
					</div>
					<div>
						<?PHP
						// paginador
						$variables = ""; // variables para el paginador
						if (isset($_GET['cod'])) {
							$variables = "cod=" . $cod . "&areaemp=" . $areactiva;
						}
						if (isset($_REQUEST['busqueda'])) {
							$variables .= "&busqueda=" . $_REQUEST['busqueda'];
						}
						if (isset($_REQUEST['lugaremp'])) {
							$variables .= "&lugaremp=" . $_REQUEST['lugaremp'];
						}
						echo paginador($limit, $contar, $pag, $variables);
						?>
						<div class="clr"></div>
					</div>
					<?php //} 
					?>
				</div>
			</div>
		</div>
		<?PHP include("sitio/footer.php"); ?>
	</div>
	<?PHP include("sitio/js.php"); ?>
	<script>
		function sendFormArea(vari) {
			var area = $("#area").val();
			//var subarea = $("#subarea").val();
			var busqueda = $("#busqueda").val();
			var lugar = $("#lugar").val();
			if (vari == 'area') {
				lugar = 0;
			}
			//if(subarea == 0){areaemp = area;}
			window.location.href = "areas.php?areaemp=" + area + "&cod=<?php echo $cod; ?>" + "&busqueda=" + busqueda + "&lugaremp=" + lugar;
		}
	</script>
</body>

</html>