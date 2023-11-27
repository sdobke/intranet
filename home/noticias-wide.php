<?PHP

$cantidad = config('home_wide');

$sql = "SELECT inov.id AS id, inov.copete AS copete, inov.fecha AS fecha, inov.hora AS hora, inov.titulo AS titulo, inov.texto AS texto, inov.video AS video 

					FROM " . $_SESSION['prefijo'] . "novedades AS inov 

					INNER JOIN intranet_link AS il 

						ON inov.id = il.item

					INNER JOIN " . $_SESSION['prefijo'] . "secciones AS secc

						ON inov.seccion = secc.id

					WHERE il.tipo = 7 " . $sql_restric . " AND inov.home = 1 AND inov.del = 0 AND inov.id NOT IN (" . $not_in . ")  AND inov.activo = 1

				GROUP BY inov.id

				ORDER BY orden LIMIT " . $cantidad;

        //echo '<div style="display:none" id="testing">'.$sql.'</div>';

        //echo $sql;

$result = fullQuery($sql);

$tipofoto = 'dest';

?>

<div id="noticias-wide">
	<div class="row g-3">
		<div class="col">

  <?PHP

  while ($noticia = mysqli_fetch_array($result)) {

    $not_in .= ',' . $noticia['id'];

    $titulo = $noticia['titulo'];

  ?>

	
	
	
	
	<div class="card mb-3">
  <div class="row g-0">
    <div class="col-md-6">
      
		<?PHP if ($noticia['video'] != '') {

            echo '<div class="home-video">' . preparaVideo($noticia['video']) . '</div>';

          } else {

            include("seccion_usafotos.php");

          }

          ?>
    </div>
    <div class="col-md-6">
      <div class="card-body">
		  <p class="card-text"><small class="text-body-secondary"><?PHP if ($noticia['fecha'] == date("Y-m-d")) {

            echo "Hoy";

          } else {

            echo fechaDet($noticia['fecha']);

          } ?></small></p>
        <h3 class="card-title"> <a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo; ?>"><?PHP echo txtcod($titulo); ?></a></h3>
		<p class="card-text"><?PHP echo cortarTexto(txtcod($noticia['copete']), 150); ?></p>
		  <a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo; ?>" class="btn btn-primary icon-link icon-link-hover stretched-link">Ver más <i class="bi bi-chevron-right"></i>
</a>
      </div>
    </div>
  </div>
</div>

	
  <?PHP } ?>
</div>
		</div>
</div>