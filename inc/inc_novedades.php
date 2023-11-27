<div id="caja-centro" style="margin:0px 10px">
	<div style="float:left; width:234px; border-bottom:1px solid #c3c3c3; margin-bottom:5px"><img src="../includes/img/hdlatam.gif" width="234" height="37" /></div>
	<?PHP 
	if(!isset($cantidad)){
		$cantidad = cantidad('home_latam');
	}
	if(end(explode("/", $_SERVER['PHP_SELF'])) == 'nota.php'){$cantidad --;}
	$query = fullQuery($sql = "SELECT * FROM intranet_novedades ORDER BY fecha DESC LIMIT ".$cantidad);
	while ($noticia = mysqli_fetch_array($query)) {?>
		<div class="row-latam" style="text-align:justify">
    		<span class="fecha"><?PHP if ($noticia['fecha'] == date("Y-m-d")){echo "Hoy";}else{echo fechaDet($noticia['fecha']);}?></span>
        	<br />
        	<span class="tit"><a href="../includes/nota.php?id=<?PHP echo $noticia['id'];?>&amp;tipo=7"><?PHP echo $noticia['titulo'];?></a></span>
        	<!--<br />-->
        	<?PHP //echo str_replace('&quot', '"',substr($noticia['texto'],0,74)."...");?>
        	<div style="clear:both;"></div>
    </div>
<?PHP }?>
<a href="../includes/novedades.php">Ver más...</a>
</div>
