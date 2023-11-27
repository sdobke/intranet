<?PHP
$hay_ppal = 0;
// imagenes
if ($total_fotos > 0){ // hay fotos
	$vacia_tamanio = '  ';
	echo '<div class="mb5">';
		echo '<div class="left">';
			$sql_fotos_trip = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY ppal DESC LIMIT 1";
			//echo $sql_fotos_trip;
			$res_fotos_trip = fullQuery($sql_fotos_trip);
			$row_fotos_trip = mysqli_fetch_assoc($res_fotos_trip);
			$foto_ppal  = imagenpPal($id,$tipo,2);
			if(!file_exists($foto_ppal['link'])){
				fotoCrearPpal($noticia['id'],$tipo);
				$foto_link = '/cliente/img/noDisponible.jpg';
				$vacia_tamanio = ' width="300" height="200" ';
			}else{
				$foto_ppal  = imagenpPal($id,$tipo,2);
				$foto_link = $foto_ppal['link'];
			}
			//if (isset($foto_ppal['link']) && file_exists($foto_ppal['link'])) {
			$hay_ppal = 1;
			echo '<img src="'.$foto_link.'" '.$vacia_tamanio.' />';
			if($hay_ppal == 1 && $foto_ppal['epi'] != ''){
				include("nota_epigrafe.php");
			}
			//}
		echo '</div>';
	echo '</div>';
}
?>