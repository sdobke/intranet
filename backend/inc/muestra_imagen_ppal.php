<?PHP
$sin_img = 1;
if(isset($tipodet) && $tipodet == "revista"){ //Si es edición Impresa
	$link_tapa = "../datos/".config('carp_edimp')."/".config('edimp')."_".$item.".jpg";
	echo '<img src="'.$link_tapa.'" />';
}else{
	$sql_usr_foto = (isset($usuario_foto)) ? " AND usuario = ".$usuario_foto : "";
	$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$item." AND tipo = ".$tipo.$sql_usr_foto." ORDER BY id";
	$res_fotos = fullQuery($sql_fotos);
	$total_fotos = mysqli_num_rows($res_fotos);
	if ($total_fotos > 0){
		$row_fotos = mysqli_fetch_array($res_fotos);
		$foto_ppal = $row_fotos['link'];
		$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$item." AND tipo = ".$tipo.$sql_usr_foto." AND ppal = 1";
		$res_fotos = fullQuery($sql_fotos);
		$cont_foto_ppal = mysqli_num_rows($res_fotos);
		if ($cont_foto_ppal == 1){ // Si tiene foto Principal
			$row_fotos = mysqli_fetch_array($res_fotos);
			$foto_ppal1 = explode("imagen",$row_fotos['link'], -1);
			$foto_ppal2 = "../".end($foto_ppal1);
			$foto_ppal = $foto_ppal2."thumb.jpg";
			if(file_exists($foto_ppal)){
				$sin_img = 0;
			}else{
				$hayppal = fotoPpal($row_fotos['id'], $row_fotos['link'], 'thumb', 3, config('thumb'), config('thumb')); // Thumbnail
				$sin_img = ($hayppal == 0) ? 0 : 1;
			}
		}else{ // Si tiene ppal seleccionada
			generaPpal($tipo, $item,1,0,'../');
			$sin_img = 1;
		}
	}else{
		$sin_img = 1;
	}
	if(isset($foto_ppal) && !file_exists($foto_ppal)){
		$sin_img = 1;
	}
	if($sin_img == 1){
		echo '<div>';
		echo '<img src="img/sinfoto.jpg" />';
		echo '</div>';
	}else{
		echo '<div><img src="'.$foto_ppal.'" width="100" height="100" /></div>';
	}
}
?>