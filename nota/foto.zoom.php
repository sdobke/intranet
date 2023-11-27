<?PHP
$fotozoom = 0;
if ($tipo == 7 && $id == 32) {
	$fotozoom = 1;
}
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
	if (file_exists($foto_full)) {
?>
		<div class="nota-media">
			<?php
			if ($fotozoom == 1) {
				echo '<div id="zoomC" style="background: url(\'' . $foto_full . '\');"></div>';
			} else {
			?>
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
								<?php echo '<div class="link-foto">' . $textofot . '</div>'; ?>
							</div>
						</div>
					</div>
				</a>
			<?php } ?>
		</div>
	<?php }
}

if ($fotozoom == 1) {
	?>
	<script>
		// CREDITS : https://www.cssscript.com/image-zoom-pan-hover-detail-view/
		var addZoom = (target) => {
			// (A) GET CONTAINER + IMAGE SOURCE
			let container = document.getElementById(target),
				imgsrc = container.currentStyle || window.getComputedStyle(container, false);
			imgsrc = imgsrc.backgroundImage.slice(4, -1).replace(/"/g, "");

			// (B) LOAD IMAGE + ATTACH ZOOM
			let img = new Image();
			img.src = imgsrc;
			img.onload = () => {
				// (B1) CALCULATE ZOOM RATIO
				let ratio = img.naturalHeight / img.naturalWidth,
					percentage = ratio * 100 + "%";

				// (B2) ATTACH ZOOM ON MOUSE MOVE
				container.onmousemove = (e) => {
					let rect = e.target.getBoundingClientRect(),
						xPos = e.clientX - rect.left,
						yPos = e.clientY - rect.top,
						xPercent = xPos / (container.clientWidth / 100) + "%",
						yPercent = yPos / ((container.clientWidth * ratio) / 100) + "%";

					Object.assign(container.style, {
						backgroundPosition: xPercent + " " + yPercent,
						backgroundSize: img.naturalWidth + "px"
					});
				};

				// (B3) RESET ZOOM ON MOUSE LEAVE
				container.onmouseleave = (e) => {
					Object.assign(container.style, {
						backgroundPosition: "center",
						backgroundSize: "cover"
					});
				};
			}
		};

		// (C) ATTACH FOLLOW ZOOM
		window.onload = () => {
			addZoom("zoomC");
		};
	</script>
<?php }
