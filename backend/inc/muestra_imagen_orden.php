<?PHP
$confoto = 0;
$sql_usr_foto = (isset($usuario_foto)) ? " AND usuario = ".$usuario_foto : "";
$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$item." AND tipo = ".$tipo_ord.$sql_usr_foto." ORDER BY id";
$res_fotos = fullQuery($sql_fotos);
$total_fotos = mysqli_num_rows($res_fotos);
if ($total_fotos > 0){
	$row_fotos = mysqli_fetch_array($res_fotos);
	$foto_ppal = $row_fotos['link'];
	$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$item." AND tipo = ".$tipo_ord.$sql_usr_foto." AND ppal = 1 LIMIT 1";
	$res_fotos = fullQuery($sql_fotos);
	$cont_foto_ppal = mysqli_num_rows($res_fotos);
	if ($cont_foto_ppal == 1){
		$row_fotos = mysqli_fetch_array($res_fotos);
		$foto_ppal = explode("imagen",$row_fotos['link'], -1);
		//$foto_ppal = "../".end($foto_ppal)."img_".$row_fotos['id']."_thumb.jpg";
		$foto_ppal = "../".end($foto_ppal)."thumb.jpg";
		if(!file_exists($foto_ppal)){
			$hayppal = fotoPpal($row_fotos['id'], $row_fotos['link'], 'thumb', 3, config('thumb'), config('thumb')); // Thumbnail
			$confoto = ($hayppal == 0) ? 0 : 1;
		}else{
			$confoto = 1;			
		}
	}else{
		generaPpal($tipo, $item);
		$confoto = 1;
	}
}else{
	//echo '<br>no hay fotos';
	$confoto = 0;
}
if($confoto == 0){
	echo '<div class="row-result-img">';
	echo '<img src="img/sinfoto.jpg" width="30" height="30" />';
	echo '</div>';
}else{
	echo '<div class="row-result-img">';
	echo '<img src="'.$foto_ppal.'" width="30" height="30" />';
	echo '</div>';
}
?>