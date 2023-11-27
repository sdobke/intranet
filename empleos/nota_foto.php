<?PHP
$hay_ppal = 0;
$hayfotoppal = 0;
// imagenes
$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY id";
$res_fotos = fullQuery($sql_fotos);
$total_fotos = mysqli_num_rows($res_fotos);
if ($total_fotos > 0) {
	
	// Preparamos la galería
	
	echo '<script type="text/javascript">
	';
		echo '$("a[rel^=';
		echo "'prettyPhoto'";
		echo ']").prettyPhoto();
		';
		$sql_fotos_trip = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY id";
		//echo $sql_fotos_trip;
		$res_fotos_trip = fullQuery($sql_fotos_trip);
		$fotos_nota = 'fotos_'.$id.' = [';
		$descr_nota = 'api_desc_'.$id.' = [';
		$contador = 0;
		while ($row_fotos_trip = mysqli_fetch_array($res_fotos_trip)){
			$vacia_tamanio = '';
			if($contador > 0){
				$fotos_nota.= ',';
				$descr_nota.= ',';
			}
			$fotos_nota.= "'/".$row_fotos_trip['link']."'";
			$descr_nota.= "''";
			$contador++;
		}
		$fotos_nota.= '];
		';
		$descr_nota.= '];
		';
		echo $fotos_nota;
		echo $descr_nota;
	echo '</script>
	';
	$hay_ppal = 0;
	$hayfotoppal = 0;
	$row_fotos = mysqli_fetch_array($res_fotos);
	$foto_ppal = $row_fotos['link'];
	$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $noticia['id'] . " AND tipo = " . $tipo . " AND ppal = 1";
	$res_fotos = fullQuery($sql_fotos, $ea);
	$cont_foto_ppal = mysqli_num_rows($res_fotos);
	if ($cont_foto_ppal == 1) {
		$row_fotos = mysqli_fetch_array($res_fotos);
		$foto_ppal = explode("imagen", $row_fotos['link'], -1);
		$foto_ppal = '../'.end($foto_ppal) . "sec.jpg";
		if (file_exists($foto_ppal)) {
			$tamfot = getimagesize($foto_ppal);
			$ancfot = $tamfot[0];
			$altfot = $tamfot[1];
			if($ancfot == 85 && $altfot == 85){
				$hayfotoppal = 1;
				?>
				<div id="cuerpo_nota_foto" style="width:85px" class="mb5 mr5">
					<div class="left">
						<?PHP
						$hay_ppal = 1;
						if($total_fotos > 1){
							echo '<img title="Ver Galeria" src="'.$foto_ppal.'" width="85" height="85" align="left" class="mr15 brd_foto" onclick="javascript:$.prettyPhoto.open(fotos_'.$id.', api_desc_'.$id.', api_desc_'.$id.');" '.$vacia_tamanio.' />';
						}else{
							echo '<img alt="Ver Galeria" title="Ver Galeria" src="/'.$foto_link.'" width="85" height="85" align="left" class="mr15 brd_foto" />';
						}
						if($total_fotos > 1){echo '<div class="notagal" style="clear:both; width:75px; text-align:center"><a href="javascript:void(0)" onclick="javascript:$.prettyPhoto.open(fotos_'.$id.', api_desc_'.$id.', api_desc_'.$id.');">Ver galer&iacute;a</a></div>';}
						?>
					</div>
				</div>
			<?PHP } else {
				$generaconid = 1;
			}
		} else {
			$generaconid = 1;
		}
	}
}
if($hayfotoppal == 0){
	if($generaconid == 1){
		generaPpal($tipo, $id, 1, $row_fotos['id'],'../');
	}else{
		generaPpal($tipo, $id);
	}
	echo '<img src="/cliente/img/noDisponible.jpg" width="85" height="85" align="left" class="mr15 brd_foto" />';
}
?>