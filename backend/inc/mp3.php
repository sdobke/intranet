<?PHP if($usamp3 == 1){
	function mostrarMp3($id,$tipo){
		$link = '../mp3/'.$tipo.'/'.$id.'/audio.mp3';
		echo $link;
	}
}
?>
<div class="control-group">
<?PHP include_once ("inc/muestra_mp3_detalles.php");?>
</div>
<div class="control-group">
	<?PHP include_once ("inc/agrega_mp3.php");?>
</div>