<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
require_once("../login_init.php");
$tipo = 7;
$tipodet = parametro('detalle',$tipo);
$nombre = obtenerNombre($tipo);

$link_destino_search = 'partuni.php';

$order_by = 'fecha DESC';

include("../backend/inc/leer_parametros.php");
include("../backend/inc/img.php");
$otro_query = " AND inov.seccion = 2";

$nombre = obtenerNombre($tipo);
$nombre_titulo = parametro('detalle',$tipo);
$titsec = $nombre;

// busqueda
$buscampo1 = "titulo";
$buscampo2 = "texto";
include "../backend/inc/query_busqueda2.php";

$limit = 8;
include "backend/inc/prepara_paginador.php";
$usadestacada = 0;
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_empleos.php"); ?>
<link href="/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		<script src="/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("a[rel^='prettyPhoto']").prettyPhoto();
			});
		</script>
		<script type="text/javascript" charset="iso-8859-1">
			$(document).ready(function(){
				$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_rounded',slideshow:3000, autoplay_slideshow:false, show_title:false, social_tools:''})
			})
		</script>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
					<?php include_once '../login.php'; ?>					
				</div>
				<?php include_once '../menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Empleos</div>
				<div class="container_inner">
					<div class="col_ppal right">
						<div class="container mb10 pb15 t28 mt25 nettooffc c999999">Acciones de Branding y atracci&oacute;n de talento</div>
						<?PHP
						$resu = fullQuery($query);
						$cont_nov_home = ($usadestacada == 1) ? 1 : 2;
						while ($noticia = mysqli_fetch_array($resu)) {
							?>
							<div class="formato1 mb15 pb15 brd_b">
								<?php
								$id = $noticia['id'];
								$noti_texto = $noticia['texto'];
								$tipo_noti_foto = 7;

								// Datos Imagen Ppal
								$foto_ppal_arr = imagenPpal($noticia['id'], $tipo_noti_foto, $cont_nov_home);
								$foto_ppal = ($foto_ppal_arr == 0) ? '': '../'.$foto_ppal_arr['link'];
								$foto_ppal = (file_exists($foto_ppal)) ? $foto_ppal : '../cliente/img/noDisponible.jpg';
								$tam_foto = getimagesize($foto_ppal);
								$alto_foto = $tam_foto[1];
								$ancho_foto = $tam_foto[0];

								// ABRE DIV NOVEDAD
								include("nota_foto.php");?>
								
								<?PHP // ------------------------------------------ TEXTO --------------------------------------------------- ?>

								<div class="texto">									
									<div class="left w100">
										<div class="tdest2 left">
											<div class="tdest2-h left">
												<strong>
														<?PHP echo $noticia['titulo']; ?>
													
												</strong>
											</div>
											<div class="tdest2-titular left">
											<?PHP
											if ($noticia['fecha'] == date("Y-m-d")) {
												echo "Hoy";
											} else {
												echo fechaDet($noticia['fecha']);
											}
											?>
											</div>
										</div><div style="clear: both"> </div>
										<span style="float: left"><?PHP echo $noti_texto; ?></span>
									</div>
								</div>
							</div>       


							<?PHP
							$cont_nov_home++;
						}
						?>
						

						<div class="paginador t11 ac">
							<?PHP
							// paginador
							$variables = "busqueda=" . $busqueda; // variables para el paginador
							echo paginador($limit, $contar, $pag, $variables);
							?>
							<div class="clr"></div>
						</div>
						
					</div>
					<?php include 'col_izq.php'; ?>
				</div>
				<div class="clr"></div>
			</div>
		<?PHP include("footer.php");?>
	</body>
</html>