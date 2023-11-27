<?PHP

$cantidad = config('home_novedades');

$sql = "SELECT inov.id AS id, inov.id != 'ddd' AS tipo, inov.fecha AS fecha, inov.hora AS hora, inov.titulo AS titulo, inov.texto AS texto 

					FROM " . $_SESSION['prefijo'] . "novedades AS inov 

					INNER JOIN intranet_link AS il 

						ON inov.id = il.item

					INNER JOIN " . $_SESSION['prefijo'] . "secciones AS secc

						ON inov.seccion = secc.id

					WHERE il.tipo = 7 ".$sql_restric." AND secc.id = 1 AND inov.home = 1 AND inov.del = 0 AND inov.id NOT IN (".$not_in.") AND inov.activo = 1

				GROUP BY inov.id

				ORDER BY orden LIMIT " . $cantidad;

$result = fullQuery($sql);

$tipofoto = 'sec';

?>

<div id="noticias" class="row row-cols-sm-2 row-cols-md-3 g-4">

	<?PHP

	while ($noticia = mysqli_fetch_array($result)) {

		$not_in.= ','.$noticia['id'];

		$titulo = $noticia['titulo'];

	?>

	  <div class="col">
    <div class="card h-100">
      <?PHP include("seccion_usafotos.php");?>
      <div class="card-body">
		   <p class="card-text"><small class="text-body-secondary"><?PHP if ($noticia['fecha'] == date("Y-m-d")) {
            echo "Hoy";
          } else {
            echo fechaDet($noticia['fecha']);
          } ?></small></p>
        <h5 class="card-title"><a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo; ?>"><?PHP echo txtcod($titulo); ?></a></h5>
		  <?PHP if (isset($subtitulo) && $publicador != '  ') {
							echo '<div class="subtitulo">' . $subtitulo . '</div>';
						}
						?>
		<p class="card-text"><?PHP echo cortarTexto(txtcod($noticia['texto']), 180); ?></p>
		   <a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo; ?>" class="btn btn-primary icon-link icon-link-hover stretched-link">Ver más <i class="bi bi-chevron-right"></i>
</a>
      </div>
    </div>
  </div>

		

	<?PHP } ?>

</div>