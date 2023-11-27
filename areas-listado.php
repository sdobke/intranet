<?PHP include "cnfg/config.php"; ?>
<?PHP include "inc/funciones.php"; ?>
<?PHP
$cod = $_GET['cod'];
$areaemp = $_GET['areaemp'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" charset="utf-8" />
	<?PHP $cod = $_GET['cod']; ?>
	<link href="css/style-<?PHP echo sitio($cod); ?>.css" rel="stylesheet" type="text/css" />
	<title><?PHP echo $cliente; ?> Intranet | Areas</title>
</head>

<body>
	<div id="contenedor">
		<?PHP include("header.php"); ?>
		<?PHP include "col_izq.php"; ?>
		<div id="ccentro">
			<?PHP include "buscador.php"; ?>
			<div style="width:728px; float:left">

				<?PHP 	// INICIO PAGINADOR
				$queryemp = "SELECT * FROM intranet_empleados WHERE empresa = " . $cod . " AND area = " . $areaemp . " ORDER BY area,apellido,nombre";
				$resultemp = fullQuery($queryemp);
				$contar = mysqli_num_rows($resultemp);
				$limit = cantidad('cant_empar'); //Cantidad de resultados por página
				if ($limit == 0) {
					$limitsql = ' ';
					$limit = $contar;
				} else {
					$limitsql = 'ok';
				}
				// FIN CUENTA DE REGISTROS Y LIMITE
				if (isset($_GET['page'])) {
					$page = $_GET['page']; //Toma la página
				}
				if (empty($page)) { //Si la página está vacía
					$page = '1'; //Setea como página 1
				}
				$start = ($page - 1) * $limit; //setar la página de inicio
				$start = round($start, 0); //redondea
				if ($limitsql == 'ok') {
					$limitsql = ' LIMIT ' . $start . ', ' . $limit;
				}

				$email   = "N/C";
				$queryemp = "SELECT * FROM intranet_empleados WHERE empresa = " . $cod . " AND area = " . $areaemp . " ORDER BY area,apellido,nombre " . $limitsql;
				$resultemp = fullQuery($queryemp);
				while ($empleado = mysqli_fetch_array($resultemp)) {
					$empfot = (file_exists("/cliente/fotos/" . $empleado['id'] . ".jpg")) ? $empleado['id'] : "sinfoto";
				?>
					<div class="caja-result">
						<div class="row-result">
							<div class="row-result-img"><img src="/cliente/fotos/<?PHP echo $empfot; ?>.jpg" align="left" /></div>
							<div class="row-result-txt">
								<span class="nom"><?PHP echo txtcod($empleado['apellido']); ?>, <?PHP echo txtcod($empleado['nombre']); ?></span><br />
								<span class="cargo"><strong><?PHP echo $empleado['cargo']; ?></strong></span><br />
								<?PHP echo area($empleado['area']); ?> | <?PHP echo empresa($empleado['empresa']); ?><br />
								<?PHP
								if ($empleado['email'] != '') { ?>
									E-mail: <a href="mailto:<?PHP echo $empleado['email']; ?>"><?PHP echo $empleado['email']; ?></a>
								<?PHP } else {
									echo "&nbsp;";
								} ?>
								<br />
								<?PHP
								if ($empleado['interno'] != '') { ?>
									Interno: <?PHP echo $empleado['interno']; ?>
								<?PHP } else {
									echo "&nbsp;";
								} ?>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				<?PHP } ?>
				<?PHP //}
				?>
				<?PHP //}
				?>
				<?php // PAGINADOR
				//echo $queryemp." - ".$limitsql;

				if ($limit == 0) {
					$totalpages = 1;
				} else {
					$totalpages = $contar / $limit; //Gets the totalpages
				}
				$totalpages = ceil($totalpages); //rounds them to the bigger number, so if the limit is 10 and there are 11 results it will show 2 paegs instead of 1 :)
				if ($page == 1) //if the page is 1
				{
					$actualpage = '1'; //actial page 1
				} else {
					$actualpage = "[$page]"; //else actualpage is the one we get using the $_GET
				}
				if ($page < $totalpages) //if the page is smaller than totalpages
				{
					$nv = $page + 1; //next page
					$pv = $page - 1; //prev page
					$nextpage = "<a href=?cod=$cod&areaemp=$areaemp&page=$nv>Siguiente ></a>"; //next page link
					$prevpage = "<a href=?cod=$cod&areaemp=$areaemp&page=$pv>&lsaquo; Anterior</a>"; //preg page link
					$firstpage = "<a href=\"?cod=$cod&areaemp=$areaemp&page=1\"><< </a>"; //first page
					$finalpage = "<a href=\"?cod=$cod&areaemp=$areaemp&page=$totalpages\"> >></a>"; //last page
				}
				if ($page == '1') //if the page is 1
				{
					$nv = $page + 1;
					$nextpage = "<a href=?cod=$cod&areaemp=$areaemp&page=$nv> Siguiente &rsaquo; </a>";
					$prevpage = "&lsaquo; Anterior";
					$firstpage = "<< ";
					$finalpage = "<a href=\"?cod=$cod&areaemp=$areaemp&page=$totalpages\"> >></a>";
				} elseif ($page == $totalpages) { //is the page is equal than the totalpages
					$pv = $page - 1;
					$nextpage = " Siguiente &rsaquo;";
					$prevpage = "<a href=?cod=$cod&areaemp=$areaemp&page=$pv>&lsaquo; Anterior </a>";
					$firstpage = "<a href=\"?cod=$cod&areaemp=$areaemp&page=1\"><< </a>";
					$finalpage = " >>";
				}
				if ($totalpages == '1' || $totalpages == '0') { //if totalpages is 1 or 0
					$nextpage = "Siguiente &rsaquo;";
					$prevpage = "&lsaquo; Anterior";
					$firstpage = "<< ";
					$finalpage = " >>";
				}
				// FIN PAGINADOR
				?>
				<div class="paginador" style="margin-bottom: 10px; margin-top: 10px; font-size: 11px;" align="center"><span><?PHP echo $firstpage; ?></span> <span><?PHP echo $prevpage; ?></span> <span><?PHP echo $actualpage; ?></span> <span><?PHP echo $nextpage; ?></span> <span><?PHP echo $finalpage; ?></span></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?PHP include "footer.php"; ?>
</body>

</html>