<div id="slider" class="left">
	<div id="slider_home" class="barousel">
		<?PHP
		$cantidad = config('home_slider');
		$link_actual = explode("/", $_SERVER['PHP_SELF']);
		if (end($link_actual) == 'nota.php') {
			$cantidad--;
		}
		$sql = "SELECT inov.id AS id, inov.id != 'ddd' AS tipo, inov.fecha AS fecha, inov.hora AS hora, inov.titulo AS titulo, inov.texto AS texto 
					FROM ".$_SESSION['prefijo']."novedades AS inov 
					INNER JOIN intranet_link AS il 
						ON inov.id = il.item
					INNER JOIN ".$_SESSION['prefijo']."secciones AS secc
						ON inov.seccion = secc.id
					WHERE 1 AND il.tipo = 7 ".$sql_restric." AND secc.id = 1 AND inov.home = 1 AND inov.del = 0
				GROUP BY inov.id
				ORDER BY orden LIMIT " . $cantidad;
		echo '<br>DEBUG: '.$sql;
		$query = fullQuery($sql);
		$cont_nov_home = ($usadestacada == 1) ? 1 : 2;
		$array_fotos = array();
		$control = 0;
		if (mysqli_num_rows($query)) {
			?>
			<div class="barousel_content">
				<?php
				while ($noticia = mysqli_fetch_array($query)) {
					$erfot = 0;
					$notid = $noticia['id'];
					$text_default = "";
					if ($control == 0) {
						$text_default = 'class="default"';
						$control++;
					}
					$cont_foto_ppal = 0;
					$noti_texto = $noticia['texto'];
					$noti_texto_corto = cortarTexto($noticia['texto'], 120);
					$noti_titulo = $noticia['titulo'];
					$tipo_noti_foto = ($noticia['tipo'] == 1) ? 7 : 23;
					if ($noticia['fecha'] == date("Y-m-d")) {
						$fecha = "Hoy";
					} else {
						$fecha = fechaDet($noticia['fecha']);
					}
					// Datos Imagen Ppal
					$foto_ppal = imagenPpal($notid, $tipo_noti_foto);
					$foto_ppal_link = $foto_ppal['link'];
					if($foto_ppal != 0){
						if(!file_exists($foto_ppal_link)){// Si no existe la destacada, la creamos
							if(fotoCrearPpal($notid,$tipo) == 0){$array_fotos[] = '/cliente/fotos/vacia.jpg';}
						}else{
							$tam_foto   = getimagesize($foto_ppal_link);
							$alto_foto  = $tam_foto[1];
							$ancho_foto = $tam_foto[0];
							if($alto_foto != config('destacadah') || $ancho_foto != config('destacadaw')){
								$erfot = 1;
								fotoCrearPpal($notid,$tipo);
							}
							$array_fotos[] = $foto_ppal['link'];
						}
					}else{
						$array_fotos[] = '/cliente/fotos/vacia.jpg';
					}
					$link = "nota.php?id=" . $notid . "&tipo={$tipo_noti_foto}";
					?>
					<div <?php echo $text_default; ?>>
						<p class="header"><a href="<?php echo $link; ?>"><?php echo txtcod($noti_titulo);?></a></p>
						<?PHP if(config('home_latam') == 1){echo '<p>'.txtcod($noti_texto_corto).'</p>';}?>
					</div>
				<?php } ?>
				</div>
				<?php
		}
		//var_dump($array_fotos);
		if (count($array_fotos) > 0) {
			$control2 = 0;
			?>
			<div class="barousel_image">
				<?php
				foreach ($array_fotos as $key => $value) {
					$foto_ppal = $value;
					$text_default = "";
					if ($control2 == 0) {
						$text_default = 'class="default"';
						$control2++;
					}
					?>
					<img src="<?php echo $value;?>" alt="" <?php echo $text_default ?> style="max-height: 275px; max-width: 630px;"  />
				<?php } ?>
			</div>
		<?php } ?>
		<div class="barousel_nav"></div>
		<!--<div class="mas_novedades"><a href="seccion.php?tipo=7">M&aacute;s Novedades</a></div>-->
	</div>
</div>
<?PHP //include("botonera.php");?>