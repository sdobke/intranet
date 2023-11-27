<?PHP
/*
$agregar = (isset($agregar) && $agregar == 0) ? 0 : 1;
$max_img = (isset($max_img)) ? $max_img : 21;
echo '<p>';
echo agregaImg(1,1,$agregar);
$cont_img = 2;
while ($cont_img < $max_img){
	echo agregaImg($cont_img);
	$cont_img++;
}
echo '</p>';
*/
$txupl = '';
if($usafotos == 1){
	$txupl.= 'fotos';
}
if($usavideos == 1){
	if($usafotos == 1){
		$txupl.= ' y ';
	}
	$txupl.= 'videos';
}
?>
<div class="row-fluid">
	<div class="span12 widget">
		<div class="widget-header">
			<span class="title"><i class="icon-upload"></i> Agregar <?PHP echo $txupl;?></span>
		</div>
		<div id="plupload-demo" class="widget-content no-padding no-border"></div>
	</div>
</div>