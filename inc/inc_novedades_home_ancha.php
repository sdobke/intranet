<div id="caja-centro-larga" style="margin:0px 10px">
	<div style="float:left; width:470px; border-bottom:1px solid #c3c3c3; margin-bottom:5px"><img src="../includes/img/hdlatam.gif" width="234" height="37" /></div>
	<?PHP 
	$cantidad = cantidad('home_latam');
	if(end(explode("/", $_SERVER['PHP_SELF'])) == 'nota.php'){$cantidad --;}
	$query = fullQuery($sql = "SELECT * FROM intranet_novedades ORDER BY fecha DESC LIMIT ".$cantidad) or die(mysqli_error());
	while ($noticia = mysqli_fetch_array($query)) {?>
		<div class="row-latam-home" align="justify">
    		<span class="fecha"><?PHP if ($noticia['fecha'] == date("Y-m-d")){echo "Hoy";}else{echo fechaDet($noticia['fecha']);}?></span>
        	<br />
            <span class="tit-home"><a href="../includes/nota.php?id=<?PHP echo $noticia['id'];?>"><?PHP echo $noticia['titulo'];?></a></span>
        	<br /><br />
        		<?PHP 
				// imagen principal
				$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$noticia['id']." order by id";
				$res_fotos = fullQuery($sql_fotos);
				$total_fotos = mysqli_num_rows($res_fotos);
				if ($total_fotos > 0){
					$row_fotos = mysqli_fetch_array($res_fotos);
					$foto_ppal = $row_fotos['link'];
					$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$noticia['id']." AND ppal = 1";
					$res_fotos = fullQuery($sql_fotos);
					$cont_foto_ppal = mysqli_num_rows($res_fotos);
					if ($cont_foto_ppal = 1){
						$row_fotos = mysqli_fetch_array($res_fotos);
						$foto_ppal = end(explode("imagen",$row_fotos['link'], -1))."imagen_ppal.jpg";
					}
					?>
                    <div class="row-latam-img">
						<img src="<?PHP echo $foto_ppal;?>"/>
                	</div>
				<?PHP
				}?>
                
        	<?PHP 
			echo cortarTexto($noticia['texto'], 250);
			//echo str_replace('&quot', '"',substr($noticia['texto'],0,74)."...");?>
        	<div style="clear:both;"></div>
    </div>
<?PHP }?>
<a href="../includes/novedades.php">Ver m�s...</a>
</div>
