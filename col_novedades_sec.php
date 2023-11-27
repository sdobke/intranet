<!--<div class="mod-b-header"><img src="img/titulos/novedades.png" alt="Novedades" width="182" height="38" /></div>-->
<?PHP
$nombre_sec = obtenerDato('nombre', 'secciones', $colsec);
$tipo_secc_nov = $colsec;
$tipo_col = 7;
$col_usafotos  = 1;

$sql_nov_sec = "SELECT inov.id AS id, inov.fecha AS fecha, inov.titulo AS titulo, inov.texto AS texto FROM " . $_SESSION['prefijo'] . "novedades AS inov
							INNER JOIN intranet_link AS il ON inov.id = il.item
							INNER JOIN intranet_secciones AS isec ON inov.seccion = isec.id
								WHERE il.tipo = 7 AND FIND_IN_SET(" . $restriccion . ",il.part)
								AND inov.seccion = " . $tipo_secc_nov . " AND inov.del = 0
								GROUP BY inov.id
								ORDER BY fecha DESC LIMIT 2 ";
$col_res_rest = fullQuery($sql_nov_sec);
$col_con_rest = mysqli_num_rows($col_res_rest);

if ($col_con_rest > 0) {
?>
	<div id="secc_<?php echo $colsec; ?>" class="seccion">
		<div class="row item">
			<div class="titulo">
				<h5><?PHP echo $nombre_sec; ?></h5>
			</div>
			<?php
			while ($col_row_rest = mysqli_fetch_array($col_res_rest)) {
			?>
				<div class="datos row">
				<?PHP
					$col2 = 'col';
					$col_limtx = $col_orlimtx;
					$col_base_id     = $col_row_rest['id'];
					$col_base_tit      = '<a href="nota.php?tipo=' . $tipo_col . '&id=' . $col_base_id . '">' . txtcod($col_row_rest['titulo']) . '</a>';
					if ($col_usafecha > 0) {
						$col_base_fec = $col_row_rest['fecha'];
						$col_base_fectx = ($col_usafecha == 2) ? 'Vence el ' : '';
					}
					if ($col_usafotos == 1) {
						$col_sql_fotos = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $col_base_id . " AND tipo = " . $tipo_col . " AND ppal = 1";
						$col_res_fotos = fullQuery($col_sql_fotos);
						$col_row_fotos = mysqli_fetch_array($col_res_fotos);
						$col_clasefoto = 0;
						$col_con_fotos = mysqli_num_rows($col_res_fotos);
						if ($col_con_fotos == 1) {
							$col2 .= '-8';
					?>
							<div class="col-4">
								<?PHP
								$col_clasefoto = 1;
								$col_link_foto = $col_row_fotos['link'];
								$col_link_ppal = explode("imagen", $col_link_foto, -1);
								$col_link_ppal = end($col_link_ppal) . "thumb.jpg";
								$col_foto_ppal_clasi = (!file_exists($col_link_ppal)) ? $col_link_foto : $col_link_ppal;
								$col_id_foto   = $col_row_fotos['id'];
								$col_epigrafe  = $col_row_fotos['epigrafe'];
								if (file_exists($col_link_foto)) { ?>
									<img src="<?PHP echo $col_foto_ppal_clasi; ?>" />
								<?PHP } else { ?>
									<img src="cliente/img/noDisponible.jpg" />
								<?PHP } ?>
							</div>
						<?PHP } // Fin si hay fotos 
						?>
					<?PHP } // Fin Usa Fotos	
					?>
					<div class="<?php echo $col2; ?>">
						<?PHP if ($col_usafecha > 0) { ?>
							<span class="fecha"><?PHP echo $col_base_fectx . fechaDet($col_base_fec, 'corto'); ?></span><br />
						<?PHP } ?>
						<div class="tituloitem"><?PHP echo $col_base_tit; ?></div>
						<?PHP if ($col_usatexto == 1) {
							$col_limtx = $col_limtx - (strlen($col_base_tit));
							echo '<p class="texto">' . cortarTexto(txtcod($col_row_rest['texto']), $col_limtx) . '</p>';
						} ?>
					</div>
				</div>
			<?PHP } // fin While
			?>
			<div class="vermas">
				<div class="col">
					<a href="seccion.php?tipo=<?PHP echo $tipo_col; ?>&sec=<?php echo $tipo_secc_nov;?>">
						Ver m&aacute;s >>
					</a>
				</div>
			</div>
		</div>
	</div>
<?PHP } ?>