<div class="control-group">
	<label class="control-label" for="archivo">Documento Actual</label>
	<?PHP
	$sqlvd = "SELECT * FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE del = 0 AND id = " . $id;
	$resvd = fullQuery($sqlvd);
	$convd = mysqli_num_rows($resvd);
	if ($convd == 1) {
	?>
		<div class="controls">
			<?PHP
			$rowvd = mysqli_fetch_assoc($resvd);
			$tipoarc   = $rowvd['tipoarc'];
			$nomarc    = txtcod($rowvd['nombre']);
			$peso      = $rowvd['peso'];
			$pesover   = medidaDocs($peso);
			if (isset($rowvd['url']) && $rowvd['url'] != '') {
				$linkarc   = $rowvd['url'];
			} else {
				$linkarc   = '../'.$rowvd['link'];
			}
			$nom_emp   = obtenerDato('nombre', 'empresas', $rowvd['empresa']);
			?>
			<a href="<?PHP echo $linkarc; ?>" <?PHP if ($tipoarc == 'jpg') {
																						echo 'rel="lightbox[roadtrip]"';
																					} else {
																						echo 'target="_blank"';
																					} ?>>
				<img src="../img/ic_<?PHP echo arch_img($tipoarc); ?>.gif" alt="" width="35" height="35" class="aligncenter" />
				<?PHP echo $nomarc; ?>
			</a>
		</div>
	<?PHP } ?>
</div>