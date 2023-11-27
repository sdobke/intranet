<?PHP
$cantidad = config('home_slider');

$slider_sql = "SELECT inov.id AS id, inov.id != 'ddd' AS tipo, inov.fecha AS fecha, inov.hora AS hora, inov.titulo AS titulo, inov.texto AS texto, inov.video AS video 
					FROM " . $_SESSION['prefijo'] . "novedades AS inov 
					INNER JOIN intranet_link AS il 
						ON inov.id = il.item
					INNER JOIN " . $_SESSION['prefijo'] . "secciones AS secc
						ON inov.seccion = secc.id
            WHERE 1 AND il.tipo = 7 ".$sql_restric." AND secc.id = 1 AND inov.home = 1 AND inov.del = 0  AND inov.activo = 1
          ";
$slider_go = " GROUP BY inov.id	ORDER BY orden ";
$slider_sql_limit = $slider_sql . $slider_go . " LIMIT " . $cantidad;
$res_slider = fullQuery($slider_sql_limit);
$canttot = mysqli_num_rows($res_slider);
if ($canttot > 0) {
  $row_test = mysqli_fetch_array($res_slider);
  $slider_sql_limit = $slider_sql . " AND inov.video = '' " . $slider_go . " LIMIT " . $cantidad;
}
//echo $slider_sql_limit;
$res_slider = fullQuery($slider_sql_limit);
$cont_nov_home = ($usadestacada == 1) ? 1 : 2;
$array_fotos = array();
$control = $cont = 1;
$canttot = mysqli_num_rows($res_slider);

if ($canttot > 0) {
?>
  <div class="row" id="slider-container">
    <div class="col">
      <div id="slider" class="left">
        <div id="any_id" class="barousel">
          <div class="barousel_content">
            <?php

            while ($noticia = mysqli_fetch_array($res_slider)) {
              $array_fotos = array();
              $not_in .= ',' . $noticia['id'];

              $erfot = 0;
              $notid = $noticia['id'];
              $text_default = "";
              if ($control == 1) {
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
              $link_foto = '/cliente/fotos/vacia.jpg';
              $video = '';
              if ($noticia['video'] != '') {
                $video = preparaVideo($noticia['video']);
              } else {
                $foto_ppal = imagenPpal($notid, $tipo_noti_foto);
                $foto_ppal_link = $foto_ppal['link'];
                if ($foto_ppal != 0) {
                  if (!file_exists($foto_ppal_link)) { // Si no existe la destacada, la creamos
                    if (fotoCrearPpal($notid, $tipo) == 0) {
                      $link_foto = '/cliente/fotos/vacia.jpg';
                    }
                  } else {
                    $tam_foto   = getimagesize($foto_ppal_link);
                    $alto_foto  = $tam_foto[1];
                    $ancho_foto = $tam_foto[0];

                    if ($alto_foto != config('destacadah') || $ancho_foto != config('destacadaw')) {
                      $erfot = 1;
                      fotoCrearPpal($notid, $tipo);
                    }
                    $array_fotos[] = $foto_ppal['link'];
                    $link_foto = $foto_ppal['link'];
                  }
                }
              }
              $link = "nota.php?id=" . $notid . "&tipo={$tipo_noti_foto}";
            ?>

              <div <?php echo $text_default; ?>>
                <p class="header"><a href="<?php echo $link; ?>"><?php echo txtcod($noti_titulo); ?></a></p>
                <?PHP if (config('home_latam') == 1) {
                  echo '<p>' . txtcod($noti_texto_corto) . '</p>';
                } ?>
              </div>


              <?php $cont++; ?>
            <?php } // Fin while 
            ?>
          </div>
          <?php
          $control2 = 1;
          ?>
          <div class="barousel_image">
            <?php
            foreach ($array_fotos as $key => $value) {
              $foto_ppal = $value;
              $text_default = "";
              if ($control2 == 1) {
                $text_default = 'class="default"';
                $control2++;
              }
            ?>
              <img src="<?php echo $value; ?>" alt="" <?php echo $text_default ?> style="max-height: auto; max-width: 100%;" />
            <?php } ?>
          </div>

          <div class="barousel_nav"></div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>