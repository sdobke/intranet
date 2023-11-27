<?PHP
$tipo_col   = 14;
$nombre_tab = obtenerNombre($tipo_col);
$sql_buscon_rec = "SELECT * FROM ".$_SESSION['prefijo']."fotos AS fot INNER JOIN ".$_SESSION['prefijo']."recomendados AS rec ON (fot.item = rec.id)
				WHERE fot.tipo = 14 AND activo = 1 ORDER BY RAND() LIMIT 2";
$res_buscon_rec = fullQuery($sql_buscon_rec);
$con_buscon_rec = mysqli_num_rows($res_buscon_rec);
if($con_buscon_rec > 0){
	$row_buscon_rec = mysqli_fetch_array($res_buscon_rec);
	$id_concurso = $row_buscon_rec['id'];
	$titulo      = $row_buscon_rec['titulo'];
	
	$sql_fotos_rec = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id_concurso." AND tipo = ".$tipo_col." ORDER BY RAND() LIMIT 2";
	$res_fotos_rec = fullQuery($sql_fotos_rec);
	$row_fotos_rec = mysqli_fetch_array($res_fotos_rec);
	$link_foto_rec = $row_fotos_rec['link'];
	$id_foto   = $row_fotos_rec['id'];
	$epigrafe  = $row_fotos_rec['epigrafe'];
	$carpeta   = explode("imagen",$link_foto_rec, -1);
	$carpeta   = end($carpeta);
	$link_foto_rec = $carpeta."imagen_ppal.jpg";
	?>
	<div class="bloque300 left" style="margin-right: 10px;">
		<div class="hd_accesos nettooffc b t16">Recomendados</div>
		<div class="row_plain brd-b">
			<div class="galeria_thumb mr10">
				<?PHP
				if(file_exists($link_foto_rec)){?>
					<a href="recomendados.php"><img src="<?PHP echo $link_foto_rec;?>" width="63px" /></a>
				<?PHP }else{ ?>
					<img src="cliente/img/noDisponible.jpg" width="100px"  height="44px" />
				<?PHP } ?>
			</div>
			<div class="galeria_desc"><span class="t15 b"><a href="nota.php?tipo=<?PHP echo $tipo_col;?>&id=<?PHP echo $id_concurso;?>"><?PHP echo $titulo;?><?PHP //echo $epigrafe;?></a></span>
			</div>
		</div>
		<div class="row_vermas ar"><a href="seccion.php?tipo=<?PHP echo $tipo_col;?>">Ver todas las recomendaciones y publicar una &rsaquo;</a></div>
	</div>	
<?PHP }?>