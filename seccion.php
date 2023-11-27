<?PHP

include "inc/startup.php";



$ea = $_SERVER[ 'PHP_SELF' ];

$tipo = getPost( 'tipo', 7 );

$tipo = getPost( 'tipodato', $tipo );

$nombre = $titsec = $nombretab = obtenerNombre( $tipo );

$secc = getPost( 'sec', 1 );

$secpag = $secc;

agrega_acceso( $tipo );



include( "backend/inc/leer_parametros.php" );

$usainvita = 0;

if ( $tipo == 18 || $tipo == 14 || $tipo == 15 ) { // Si son clasificados o recomendados o concurso de fotos o novedades para tiendas y locales

	$usainvita = 1;

	$link_destino = "alta.php?tipo=" . $tipo;

	switch ( $tipo ) {

		case 18: // Clasificados

			$texto = ( isset( $_SESSION[ 'usrfrontend' ] ) ) ? '&iquest;Quer&eacute;s publicar un aviso clasificado? Hac&eacute; click ac&aacute;.' : '&iquest;Quer&eacute;s publicar un aviso clasificado? No olvides ingresar con tu usuario.';

			break;

		case 14: // Recomendados

			$texto = ( isset( $_SESSION[ 'usrfrontend' ] ) ) ? '&iquest;Quer&eacute;s publicar una recomendaci&oacute;n? Hac&eacute; click ac&aacute;.' : '&iquest;Quer&eacute;s publicar una recomendaci&oacuten? No olvides ingresar con tu usuario.';

			break;

		case 15: // Concursos de fotos

			$texto = ( isset( $_SESSION[ 'usrfrontend' ] ) ) ? '' : 'Por favor ingres&aacute; con tu usuario para ver Concursos de Fotos';

			break;

	}

	$link_invita = '';

	if ( isset( $_SESSION[ 'usrfrontend' ] ) ) {

		$link_invita .= '<a href="' . $link_destino . '">';

	} else {

		$popup = "MM_openBrWindow('login_popup.php?formloc=" . $link_destino . "', 'loginpop', 'scrollbars=yes,width=550,height=140')";

		//$link_invita.= '<a href="javascript:;" onclick="' . $popup . '">';

	}

	$link_invita .= $texto;

	$link_invita .= '</a>';

}

//if(($usauser == 1 & isset($_SESSION['tipousr']) && $_SESSION['tipousr'] == 1) || $usact == 0){

// busqueda

$buscampos = parametro( 'campos_busqueda', $tipo );

if ( $buscampos == '' ) {

	$buscampos = parametro( 'titulo', $tipo );

}

$otro_query = parametro( 'otroquery', $tipo );

$query_orden = parametro( 'orden', $tipo );

$mostrarlink = 1;

if ( !isset( $_SESSION[ 'tipousr' ] ) ) {

	$_SESSION[ 'tipousr' ] = 0;

}

if ( isset( $_GET[ 'sec' ] ) ) {

	$titsec = obtenerDato( 'nombre', 'secciones', $secc );

	if ( $titsec == 'comunidad' ) {

		$otro_query .= " inov.seccion = 4 OR inov.seccion = 6 ";

		$titulo_sec = 'Comunidad';

	} else {

		if ( $secc == 1 ) {

			$secc = '1 OR seccion = 9';

		}

		$otro_query .= " AND (inov.seccion = " . $secc . " ) ";

	}

} elseif ( $tipo == 7 && !isset( $_GET[ 'secc' ] ) && getPost( 'busqueda' ) != 0 ) { // Novedades

	$otro_query .= " AND (seccion = " . $secc . " ) ";

}

if ( $tipo == 7 ) {

	$otro_query .= ' AND inov.activo = 1 ';

}

if ( $tipo == 19 ) { // Sorteos

	$otro_query .= " AND fecha >= DATE(NOW()) ";

}

if ( $tipo == 15 ) { // Concurso de fotos

	$titsec = 'Concurso de Fotos';

}

if ( $tipo == 12 ) {

	$titsec = 'Revista';

}

if ( $tipo == 22 ) { // Seguridad e higiene

	$otro_query .= " AND usuario = " . $_SESSION[ 'usrfrontend' ];

	$mostrarlink = 0;

}

if ( $tipo == 37 ) { // Información Municipal

	$otro_query .= " AND usuario = " . $_SESSION[ 'usrfrontend' ];

}



include "backend/inc/query_busqueda.php";

$limit = 12;

include "inc/prepara_paginador.php";

//echo '<!--Query: '.$query.'-->';

//echo $query;

$result = fullQuery( $query, $ea );

$cntqry = mysqli_num_rows( $result );

$doclink = '';

$es_seccion = $haydatos = 0;



if ( $busqueda != '' ) {

	$secnom = 'Resultados de la b&uacute;squeda';

}

?>

<!DOCTYPE html>

<html>



<head>

	<title>
		<?PHP echo $cliente; ?> Intranet |
		<?PHP echo $nombre; ?>
	</title>

	<?PHP include("sitio/head.php"); ?>

	<link rel="stylesheet" type="text/css" href="/assets/css/lightbox.css">

</head>



<body class="seccion seccion-<?php echo $nombre; ?>">

	<div class="flex-wrapper">


		<?PHP include("sitio/header.php"); ?>
		
		<div class="container">

			<?php
			if ( $tipo == 37 || $tipo == 22 ) {
				include_once 'minisitios_menu.php';
			}
			?>

			
			<div class="row g-5">

				<div class="col-md-9 col-sm-12" id="col-izq">

					<div class="seccion-header">

						<h1 class="mb-5 mt-3">
							<?PHP echo ucwords($titsec); ?>
						</h1>

					</div>
					
					
					<div class="row row-cols-sm-2 row-cols-md-3 g-4" id="noticias">
						
						

					<?PHP if ($usainvita == 1) { ?>

					<div class="invita">
						<?php echo $link_invita; ?>
					</div>

					<?PHP } ?>

					<?php

					if ( $cntqry == 0 && ( isset( $_SESSION[ 'usrfrontend' ] ) ) ) {

						if ( $tipo == 19 ) { // Sorteos

							echo 'En este momento no hay sorteos activos.';

						}

					}

					if ( !isset( $_SESSION[ 'usrfrontend' ] ) && $tipo == 19 ) {

						echo 'Por favor ingres&aacute; con tu usuario para ver los sorteos.';

					}
					if ( $tipo != 12 && !( $tipo == 15 && !isset( $_SESSION[ 'usrfrontend' ] ) ) ) { // Si no es BK Revista o si el tipo es concurso de fotos pero no está logueado
						while ( $noticia = mysqli_fetch_array( $result ) ) {
							
							$titulo = $noticia[ $tipotit ];
							if ( $usalink == 1 ) {

								$doclink = 'No se encontr&oacute; documento.';

								$sql_doc = "SELECT * FROM intranet_docs WHERE tabla = " . $tipo . " AND sector = " . $noticia[ 'id' ];

								$res_doc = fullQuery( $sql_doc );

								$con_doc = mysqli_num_rows( $res_doc );

								if ( $con_doc > 0 ) {

									$doc = mysqli_fetch_assoc( $res_doc );

									if ( file_exists( $doc[ 'link' ] ) ) {

										$nombre_doc = $doc[ 'nombre' ];

										$doclink = 'Descargar documento:</strong> <a href="../' . $doc[ 'link' ] . '" target="_blank">' . $nombre_doc . '</a>';

									}

								}

							}
							$tipofoto = 'wide';

							?>
						
						<div class="col">
    <div class="card h-100">
		<?PHP if ($tipo == 7 && $noticia['video'] != '') {
												echo '<div class="home-video">' . preparaVideo($noticia['video']) . '</div>';
											} else {
												include("seccion_usafotos.php");
											}
											?>
      <div class="card-body">
		  
								

							<?PHP

							if ( $usauser == 1 ) {

								$publicador = ( $noticia[ 'usuario' ] == 0 ) ? 'administrador' : txtcod( obtenerDato( "nombre,apellido", "empleados", $noticia[ 'usuario' ] ) );

								$subtitulo = '<p class="card-text"><small class="text-body-secondary">Publicado por ' . $publicador .'</small></p>';

							}

							if ( isset( $subtitulo ) && $publicador != '  ' ) {

								echo '<div class="tdest2-h left">' . $subtitulo . '</div>';

							}

							if ( $usafecha > 0 ) {
								?>

							<p class="card-text"><small class="text-body-secondary">
								<?PHP
								if ( $usafecha == 2 ) {
									echo 'Vence ';
								}
								echo( $noticia[ 'fecha' ] == date( "Y-m-d" ) ) ? "Hoy" : '<!--el-->  ' . fechaDet( $noticia[ 'fecha' ] );
								?>
								</small></p>

							<?PHP } ?>
		  <h3>

										<?PHP if ($mostrarlink == 1) { ?>

										<a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo; ?>">

											<?PHP } ?>

											<?PHP echo txtcod($titulo); ?>

											<?PHP if ($mostrarlink == 1) { ?>

										</a>

										<?PHP } ?>

									</h3>	

							<?PHP if ($tipo == 14) { // Recomendados 

									?>

							<div class="tdest2-titular right">
								<?PHP echo (txtcod($noticia['salida'])); ?>
							</div>

							<?PHP } ?>


									<?PHP if ($tipodet == 'encuestas') {

												$doclink = txtcod($noticia['pregunta']);

											} ?>

		  <p class="card-text"><?PHP echo $doclink; ?></p>

								<?PHP if ($tipo == 14) { // Recomendados

											if ($noticia['votos'] > 0) {

												$cantvot = $noticia['votos'];

												$vtp = ($cantvot > 1) ? 's' : '';

												$vtx = 'Promedio: ' . $noticia['prom'] . ' (' . $cantvot . ' voto' . $vtp . ')';

											} else {

												$cantvot = 0;

												$vtx = 'Sin votos';

											}

											echo '<div class="txvot">' . $vtx . '</div>';

										} ?>

								<?PHP if ($usatexto == 1) {

											echo '<p class="card-text">' . cortarTexto(txtcod($noticia['texto']), 150) . '</p>';

										} ?>
		  <a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo; ?>" class="btn btn-primary icon-link icon-link-hover stretched-link">Ver más <i class="bi bi-chevron-right"></i></a>



      </div>
    </div>
  </div>
						


					<?PHP } ?>
										</div>

					<?PHP if ($cntqry > 0) { ?>



						<?PHP

						// paginador

						$variables = "tipo=" . $tipo . "&busqueda=" . $busqueda; // variables para el paginador

						if ( $tipo == 7 ) { // si es novedad

							$variables .= '&secc=' . $secpag;

						}

						echo paginador( $limit, $contar, $pag, $variables );

						?>


					<?PHP } ?>

					<?PHP } // Fin if tipo no es BK Revista 

					?>

					<?PHP if ($tipo == 12) {

						include("seccion_bkr.php");

					} // Si es BK Revista 

					?>

					<?PHP if ($tipo == 17) { // Sugerencias

					?>


							<?php include("sugerencias/form.php"); ?>


					<?php } ?>
					


				</div>
				
				
				
				<div class="col-md-3 col-sm-12 dark" id="col-der">

					<?php include("col_der.php"); ?>

				</div>

			</div>

		</div>

		<?PHP include("sitio/footer.php"); ?>

	</div>

	<?PHP include("sitio/js.php"); ?>

	<?PHP if ($usafotos == 1 || $tipo == 15) { ?>

	<script type="text/javascript" src="/assets/js/lightbox.js"></script>

	<script>
		lightbox.option( {

			'resizeDuration': 200,

			'wrapAround': true

		} )
	</script>

	<?PHP } ?>

</body>



</html>