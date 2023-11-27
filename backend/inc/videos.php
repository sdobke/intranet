<?PHP
function mostrarVideo($id, $formato){
	switch($formato){
		case 1:
			$ancho = '100';
			$alto  = '75';
			break;
		case 2:
			$ancho = '500';
			$alto  = '200';
			break;
		case 3:
			$ancho = '145';
			$alto  = '95';
			break;
	}
	$sql = "SELECT link FROM ".$_SESSION['prefijo']."videos WHERE id = ".$id;
	$res = fullQuery($sql);
	$row = mysqli_fetch_assoc($res);
	$link = $row['link'];
	echo '
		<iframe width="'.$ancho.'" height="'.$alto.'" src="//www.youtube.com/embed/'.$link.'?rel=0" frameborder="0" allowfullscreen></iframe>
		';
}
?>
<div class="control-group">
<?PHP include_once ("inc/muestra_videos_detalles.php");?>
</div>