<?PHP
$hay_ppal = 0;
// imagenes
$foto_full = '';
if ($total_fotos > 0) { // hay fotos
	$sql_fotos_trip = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo . " ORDER BY id";
	//echo $sql_fotos_trip;
	$res_fotos_trip = fullQuery($sql_fotos_trip);
	$contador = 0;
	while ($row_fotos_trip = mysqli_fetch_array($res_fotos_trip)) {
		$foto_link = '/cliente/img/noDisponible.jpg';
		$foto_ppal  = imagenpPal($id, $tipo, 1);
		if (isset($foto_ppal['link'])) {
			if (!file_exists($foto_ppal['link'])) {
				fotoCrearPpal($noticia['id'], $tipo);
				$foto_ppal  = imagenpPal($id, $tipo, 1);
				$foto_link = '/cliente/img/noDisponible.jpg';
			} else {
				$foto_link = $foto_ppal['link'];
				$foto_full = $row_fotos_trip['link'];
			}
		}
	}
	$hay_ppal = 1;
	$textofot = ($total_fotos == 1) ? 'Click para ampliar' : 'Ver galería';
	if(file_exists($foto_full)){
?>
	<div class="nota-media">
		<a href="<?php echo $foto_full; ?>" data-lightbox="galeria">
			<div class="row">
				<div class="col foto-ppal">
					<img class="img-fluid" title="<?php echo $textofot; ?>" src="/<?php echo $foto_link; ?>" />
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="d-flex justify-content-between">
						<?php if ($hay_ppal == 1 && $foto_ppal['epi'] != '') {
							include("nota/epigrafe.php");
						} ?>
						<div class="link-foto"><?php echo $textofot; ?></div>
					</div>
				</div>
			</div>
		</a>
	</div>
<?php }
}
