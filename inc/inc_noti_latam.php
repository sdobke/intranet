<div id="caja-centro" style="margin:0px 10px">
	<div style="float:left; width:234px; border-bottom:1px solid #c3c3c3; margin-bottom:5px"><img src="../includes/img/hdlatam.gif" width="234" height="37" /></div>
	<?PHP 
	$cantidad = cantidad('home_latam');
	if(end(explode("/", $_SERVER['PHP_SELF'])) == 'nota.php'){$cantidad --;}
	$query = fullQuery($sql = "SELECT * FROM intranet_noticias ORDER BY fecha DESC LIMIT ".$cantidad) or die(mysqli_error());
	while ($noticia = mysqli_fetch_array($query)) {?>
		<div class="row-latam" align="justify">
    		<span class="fecha"><?PHP if ($noticia['fecha'] == date("Y-m-d")){echo "Hoy";}else{echo fechaDet($noticia['fecha']);}?></span>
        	<br />
        	<span class="tit"><a href="../includes/nota.php?tipo=latam&amp;id=<?PHP echo $noticia['id'];?>"><?PHP echo $noticia['titulo'];?></a></span>
        	<!--<br />-->
        	<?PHP //echo str_replace('&quot', '"',substr($noticia['texto'],0,74)."...");?>
        	<div style="clear:both;"></div>
    </div>
<?PHP }?>
<a href="../includes/novedades.php">Ver m�s...</a>
</div>
