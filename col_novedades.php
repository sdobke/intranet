<!--<div class="mod-b-header"><img src="img/titulos/novedades.png" alt="Novedades" width="182" height="38" /></div>-->
<?PHP
$tipo_col = 7;
$nombre_tab = obtenerNombre($tipo_col);
$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp']: 0;
$sql_buscon = " (SELECT isec.id AS id, isec.nombre AS nombre FROM intranet_" . $nombre_tab . " AS inov 
						INNER JOIN intranet_link AS il ON inov.id = il.item 
						INNER JOIN intranet_secciones AS isec ON inov.seccion = isec.id
							WHERE il.tipo = " . $tipo_col . " AND il.part = " . $restriccion . " AND isec.columna = 1
					) 
					UNION 
					(SELECT isec.id AS id, isec.nombre AS nombre FROM intranet_" . $nombre_tab . " AS inov
						INNER JOIN intranet_link AS il ON inov.id = il.item
						INNER JOIN intranet_secciones AS isec ON inov.seccion = isec.id
							WHERE il.tipo = " . $tipo_col . " AND il.part = 0 AND isec.columna = 1
					)
					";
$res_menu = fullQuery($sql_buscon);
$ii=0;
while ($row_menu = mysqli_fetch_array($res_menu)) {
	$tipo_secc_nov = $row_menu['id'];
	$mt = "mt15";
	if($ii==0){
		$mt = "";
		$ii++;
	}
	?>
	<!--<div class="bloque300-st <?php echo $mt; ?> right">-->
		<div class="bloque300 left" style="margin-right: 10px;">
		<div class="hd_accesos nettooffc b t16"><?PHP echo strtoupper($row_menu['nombre']); ?></div>
			<?PHP
			$link_foto_no_disponible = "cliente/img/noDisponible.jpg"; //foto test de maqueta
			$sql_nov_sec = " (SELECT inov.id AS id, inov.fecha AS fecha, inov.titulo AS titulo, inov.texto AS texto FROM intranet_" . $nombre_tab . " AS inov 
							INNER JOIN intranet_link AS il ON inov.id = il.item 
							INNER JOIN intranet_secciones AS isec ON inov.seccion = isec.id
								WHERE il.tipo = " . $tipo_col . " AND il.part = " . $restriccion . "
								AND inov.seccion = " . $tipo_secc_nov . "
								) 
							UNION 
								(SELECT inov.id AS id, inov.fecha AS fecha, inov.titulo AS titulo, inov.texto AS texto FROM intranet_" . $nombre_tab . " AS inov
									INNER JOIN intranet_link AS il ON inov.id = il.item
									INNER JOIN intranet_secciones AS isec ON inov.seccion = isec.id
										WHERE il.tipo = " . $tipo_col . " AND il.part = 0
										AND inov.seccion = " . $tipo_secc_nov . "
								)
							ORDER BY fecha DESC LIMIT 2";
			$res_nov_sec = fullQuery($sql_nov_sec);
			while ($row_nov_sec = mysqli_fetch_array($res_nov_sec)) {
				$texto_nov = cortarTexto($row_nov_sec['texto'], 100);
				?>
				<div class="row_plain brd-b">
			
					<?PHP
					$id_item = $row_nov_sec['id'];
					$fecha_it = $row_nov_sec['fecha'];
					$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $id_item . " AND tipo = " . $tipo_col . " AND ppal = 1";
					$res_fotos = fullQuery($sql_fotos);
					$row_fotos = mysqli_fetch_array($res_fotos);
					$con_fotos = mysqli_num_rows($res_fotos);
					if ($con_fotos == 1) {
						$link_foto = $row_fotos['link'];
						$link_ppal = end(explode("imagen", $link_foto, -1)) . "imagen_ppal.jpg";
						$foto_ppal_clasi = (!file_exists($link_ppal)) ? $link_foto : $link_ppal;
						$id_foto = $row_fotos['id'];
						$epigrafe = $row_fotos['epigrafe'];
						if (file_exists($link_foto)) {
							?>
							<div class="foto50">
								<img src="<?PHP echo $foto_ppal_clasi; ?>" width="50" height="50" align="left" />
							</div>
						<?PHP }else{ ?>
							<div class="foto50">
								<img src="<?php echo $link_foto_no_disponible; ?>" width="50" height="50" align="left" />
							</div>
						<?PHP } ?>
					<?PHP } ?>
					
						<div class="texto-nota-sidebar">
							<span class="tahoma t11"><?PHP echo $fecha_it; ?></span><br />
							<span class="arial t13 b"><a href="nota.php?id=<?PHP echo $row_nov_sec['id']; ?>"><?PHP echo $row_nov_sec['titulo']; ?></a></span><br />
							<?PHP echo $texto_nov; ?>
						</div>
					</div>
			<?PHP } ?>
			<div class="row_vermas ar"><a href="novedades.php?secc=<?PHP echo $row_menu['id']; ?>">Más <?PHP echo ($row_menu['nombre']); ?> &rsaquo;</a></div>
	</div>
<?PHP } ?>		