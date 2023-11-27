<?php
if ($tipo <> 15) { // Si no es concurso de fotos
	$sql_fotos = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo . " ORDER BY id";
	$res_fotos = fullQuery($sql_fotos);
	$total_fotos = mysqli_num_rows($res_fotos);
} else {
	$total_fotos = 0;
}
if($tipo == 7 && $noticia['video'] != ''){
	echo '<div class="home-video">'.preparaVideo($noticia['video']).'</div>';
}elseif (isset($espopup) && $espopup == 1) {
	include("popup_foto.php");
} else {
	if ($total_fotos > 0) {
		include("nota/foto.php");
	}
}
