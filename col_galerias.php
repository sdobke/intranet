<?PHP
$tipo_col = 9;
$nombre_tab = obtenerDato('nombre', 'tablas', $tipo_col);
$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp'] : 0;
$sql_buscon = " SELECT inov.id AS id, inov.fecha AS fecha, inov.titulo AS titulo, inov.texto AS texto FROM intranet_" . $nombre_tab . " AS inov 
					INNER JOIN intranet_link AS il ON inov.id = il.item 
						WHERE il.tipo = " . $tipo_col . " AND (il.part = " . $restriccion . " OR il.part = 0) AND del = 0 AND home = 1
				ORDER BY id LIMIT 1";
//echo $sql_buscon;
$res_buscon = fullQuery($sql_buscon);
$con_buscon = mysqli_num_rows($res_buscon);
if ($con_buscon == 1) {
	$row_buscon = mysqli_fetch_array($res_buscon);
	$id_galfot = $row_buscon['id'];
	$titulo = $row_buscon['titulo'];
	$noti_fecha = fechaDet($row_buscon['fecha'], 'corto', 's');
	$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $id_galfot . " AND tipo = " . $tipo_col . " ORDER BY RAND() LIMIT 1";
	$res_fotos = fullQuery($sql_fotos);
	$row_fotos = mysqli_fetch_array($res_fotos);
	$link_foto = $row_fotos['link'];
	$id_foto = $row_fotos['id'];
	$carpeta = explode("imagen", $link_foto, -1);
	$carpeta = end($carpeta);
	$link_foto = $carpeta . "thumb.jpg";
	$epigrafe = $row_fotos['epigrafe'];
	$link_foto_no_disponible = "cliente/img/noDisponible.jpg"; //foto test de maqueta
?>
	<div id="<?php echo $tipo_coldet; ?>" class="seccion">
		<div class="row titulo">
			<h4>Galería de fotos</h4>
		</div>
		<div class="row item">
			<div class="col-4">
				<?PHP if (file_exists($link_foto)) { ?>
					<a href="nota.php?tipo=9&id=<?PHP echo $id_galfot; ?>"><img src="<?PHP echo $link_foto; ?>" width="76px" height="76px" /></a>
				<?PHP } else { ?>
					<img src="<?PHP echo $link_foto_no_disponible; ?>" width="76px" height="76px" /><br />
				<?PHP } ?>
			</div>
			<div class="col-8">
				<?PHP
				echo '<a href="nota.php?tipo=9&id=' . $id_galfot . '">' . txtcod($titulo) . ' | ' . $noti_fecha . '</a>';
				if ($epigrafe != '') {
					echo '<br />' . $epigrafe;
				} ?>
			</div>
			<div class="vermas">
				<a href="/seccion.php?tipo=9">Ver m&aacute;s</a>
			</div>
		</div>
	</div>
<?PHP } ?>