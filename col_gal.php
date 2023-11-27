<?PHP
	$tipo_col = 9;
	$nombre_tab = obtenerNombre($tipo_col);
	$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp']: 0;
	$sql_buscon = " SELECT inov.id AS id, inov.fecha AS fecha, inov.titulo AS titulo, inov.texto AS texto FROM intranet_" . $nombre_tab . " AS inov 
					INNER JOIN intranet_link AS il ON inov.id = il.item 
						WHERE il.tipo = " . $tipo_col . " AND (il.part = " . $restriccion . " OR il.part = 0) AND del = 0 AND home = 1
				ORDER BY id LIMIT 1";
	//echo '<!--'.$sql_buscon.'-->';
	$res_buscon = fullQuery($sql_buscon);
	$con_buscon = mysqli_num_rows($res_buscon);
	if($con_buscon > 0){
		echo '<div class="bloque300 left" style="margin-right: 10px;">
	<div class="hd_accesos nettooffc b t16">Clasificados</div>';
			while($row_buscon = mysqli_fetch_array($res_buscon)){
				$id_item     = $row_buscon['id'];
				$titulo      = '<a href="nota.php?tipo='.$tipo_col.'&id='.$id_item.'">'.$row_buscon['titulo'].'</a>';
				$texto       = cortarTexto($row_buscon['texto'], 300);
				$fecha_clasi = $row_buscon['fecha'];
				
				$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id_item." AND tipo = ".$tipo_col." AND ppal = 1";
				$res_fotos = fullQuery($sql_fotos);
				$row_fotos = mysqli_fetch_array($res_fotos);
			?>
				<div class="row_plain brd-b">
					<div class="foto50">
						
						<?PHP
						$con_fotos = mysqli_num_rows($res_fotos);
						if($con_fotos == 1){
							$link_foto = $row_fotos['link'];
							$link_ppal = explode("imagen",$link_foto, -1);
							$link_ppal = end($link_ppal)."imagen_ppal.jpg";
							$foto_ppal_clasi = (!file_exists($link_ppal)) ? $link_foto : $link_ppal;
							$id_foto   = $row_fotos['id'];
							$epigrafe  = $row_fotos['epigrafe'];
							if(file_exists($link_foto)){?>
								<img src="<?PHP echo $foto_ppal_clasi;?>" width="50" height="50" align="left" />
							<?PHP }else{ ?>
								<img src="cliente/img/noDisponible.jpg" width="50" height="50" align="left" />
							<?PHP } ?>
						<?PHP } ?>
					</div>
					<div class="texto-nota-sidebar"><span class="tahoma t11"><?PHP echo fechaDet($fecha_clasi,'corto');?></span><br />
						<span class="arial t13 b"><?PHP echo $titulo; //$texto?></span></div>
				</div>
	
				
			<?PHP } ?>
            	<div class="row_vermas ar"><a href="seccion.php?tipo=18"><?PHP if($con_buscon > 0){ echo 'Ver m&aacute;s y p';}else{echo 'P';}?>ublicar clasificado >></a></div>
</div>
	<?PHP } ?>