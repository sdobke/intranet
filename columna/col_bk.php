<?PHP
$tipo_col = 12;
$nombre_tab = obtenerNombre($tipo_col);
$sql_bk = "SELECT * FROM intranet_" . $nombre_tab . " ORDER BY edicion DESC LIMIT 1";
$res_bk = fullQuery($sql_bk);
$con_bk = mysqli_num_rows($res_bk);
if ($con_bk == 1) {
	$row_bk = mysqli_fetch_array($res_bk);
	$id_bk = $row_bk['id'];
	$fecha = $row_bk['fecha'];
	$edicion = $row_bk['edicion'];
	$link_foto = "bk/imagen_" . $id_bk . ".jpg";
	$link_foto_no_disponible = "cliente/img/noDisponible.jpg"; //foto test de maqueta
	?>
	<div class="bloque300-st mt15 right">
		<div class="hd_accesos nettooffc b t14">BK Noticias</div>
		<div class="row_accesos">
			<div class="galeria_thumb mr10">
				<?PHP if (file_exists($link_foto)) { ?>
					<a href="bk/bk_<?PHP echo $id_bk; ?>.pdf" target="_blank">
						<img src="<?PHP echo $link_foto; ?>" width="100PX" height="76px" />
					</a>
				<?PHP }else{ ?>
					<img src="<?PHP echo $link_foto_no_disponible; ?>"   width="100px" height="76px" /><br />
				<?PHP } ?>
			</div>
			<a href="bk/bk_<?PHP echo $id_bk; ?>.pdf" target="_blank">
				Edici&oacute;n Nro. <strong><?PHP echo $edicion; ?></strong><br />del <?PHP echo fechaDet($fecha); ?>
			</a>
			<br />
			<br />
			<a href="bk.php">Ver anteriores >></a>
		</div>
	</div>
<?PHP } ?>