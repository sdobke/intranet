<?PHP
$sql_videos = "SELECT * FROM ".$_SESSION['prefijo']."videos WHERE item = ".$id." AND tipo = ".$tipo." AND del = 0 ORDER BY id";
$res_videos = fullQuery($sql_videos);
$total_videos = mysqli_num_rows($res_videos);
if($total_videos > 0){
	$rowvid = mysqli_fetch_assoc($res_videos);
	$urlvid = $rowvid['link'];
	$urlvid = explode('video_',$urlvid);
	$urlvid = current($urlvid);
	?>
	<div style="width:600px;margin-right:5px;">
		<div style="height:350px;">
			<div id="osmplayer"></div>
		</div>
	</div>
	<div>
		<?PHP
			$res_videos = fullQuery($sql_videos);
			while($rowvd = mysqli_fetch_array($res_videos)){
				echo '<br /><input type="text" name="videpi'.$rowvd['id'].'" value="'.$rowvd['epigrafe'].'" /> - <input type="checkbox" id="video_'.$rowvd['id'].'" name="video_'.$rowvd['id'].'" /> Borrar';
			}
		?>
	</div>
<?PHP }?>