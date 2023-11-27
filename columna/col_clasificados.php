<?PHP
	$tipo_col = 18;
	$nombre_tab = obtenerNombre($tipo_col);
	$sql_buscon = " SELECT inov.id AS id, inov.activo AS activo, inov.titulo AS titulo, inov.texto AS texto, inov.fecha AS fecha FROM ".$_SESSION['prefijo'].$nombre_tab." AS inov 
					INNER JOIN ".$_SESSION['prefijo']."link AS il ON inov.id = il.item 
					WHERE 1 AND il.tipo = 7 ".$sql_restric." AND inov.activo = 1 AND inov.del = 0 AND fecha >= NOW() ORDER BY fecha LIMIT 2";
	//echo '<!--'.$sql_buscon.'-->';
	$res_buscon = fullQuery($sql_buscon);
	$con_buscon = mysqli_num_rows($res_buscon);
	if($con_buscon > 0){
		echo '<div class="bloque300 left" style="margin-right: 10px;">
	<div class="coltit nettooffc b t16">Clasificados</div>';
			while($row_buscon = mysqli_fetch_array($res_buscon)){
				$id_item     = $row_buscon['id'];
				$titulo      = '<a href="nota.php?tipo='.$tipo_col.'&id='.$id_item.'">'.txtcod($row_buscon['titulo']).'</a>';
				$texto       = cortarTexto($row_buscon['texto'], 300);
				$fecha_clasi = $row_buscon['fecha'];
				
				$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id_item." AND tipo = ".$tipo_col." AND ppal = 1";
				$res_fotos = fullQuery($sql_fotos);
				$row_fotos = mysqli_fetch_array($res_fotos);
				?>
				<div class="row_plain brd-b">
					<div class="galeria_thumb mr10">
						
						<?PHP
						$con_fotos = mysqli_num_rows($res_fotos);
						if($con_fotos == 1){
							$link_foto = $row_fotos['link'];
							$link_ppal = explode("imagen",$link_foto, -1);
							$link_ppal = end($link_ppal)."thumb.jpg";
							$foto_ppal_clasi = (!file_exists($link_ppal)) ? $link_foto : $link_ppal;
							$id_foto   = $row_fotos['id'];
							$epigrafe  = $row_fotos['epigrafe'];
							if(file_exists($link_foto)){?>
								<img src="<?PHP echo $foto_ppal_clasi;?>" width="76" align="left" />
							<?PHP }else{ ?>
								<img src="cliente/img/noDisponible.jpg" width="76" align="left" />
							<?PHP } ?>
						<?PHP } ?>
					</div>
					<div class="texto-nota-sidebar"><span class="tahoma t11">Vence el <?PHP echo fechaDet($fecha_clasi,'corto');?></span><br />
						<span class="arial t13 b"><?PHP echo $titulo; //$texto?></span></div>
				</div>
	
				
			<?PHP } ?>
            	<div class="row_vermas ar"><a href="seccion.php?tipo=18"><?PHP if($con_buscon > 0){ echo 'Ver m&aacute;s y p';}else{echo 'P';}?>ublicar clasificado >></a></div>
</div>
	<?PHP } ?>