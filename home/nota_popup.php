<?PHP
include "cnfg/config.php";
include "inc/funciones.php";
include "inc/inc_docs.php";

$tipo = 7;
$nombre = obtenerNombre($tipo);
$nombre_titulo = $nombre;
$titsec = $nombre;
$usafotos = 1;
$usavideos = 1;
$usafecha = 1;
$usahora = 1;
$usatexto = 1;
$texto = 'texto';
$titulado = '';
$continuacion = 0;
$usavotos = 0;
$usadocs = 0;
$espopup = 1;
$id = isset($_GET['id']) ? $_GET['id'] : 1;
$where_adicional = (isset($otro_query)) ? " AND " . $otro_query : '';
$query = "SELECT * FROM intranet_" . $nombre . " WHERE id = " . $id . $where_adicional;
$result = fullQuery($query);
$noticia = mysqli_fetch_array($result);
if ($usatexto == 1) {
	$noticia_texto = $noticia[$texto];
}
agrega_acceso($tipo);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<link type="text/css" rel="stylesheet" href="css/lightbox.css" media="screen" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <?PHP include_once("head_css.php");?>
		<style>
		/*
		 * Ajusto el ancho para el popup
		 */
		#middle{width: 650px;}
		.middle_inner{width: 620px;}
		.footer_inner{width: 628px;}
		</style>
	</head>
	<body>
		<div id="middle">
			<div class="middle_innerpop">
				<div id="headerpop" class="mb10">
					<div id="logo" >
						<a href="index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
				</div>
				<div class="containerpop">
					<div class="col_ppalpop left">					
						<?PHP 
						if($noticia['titulo'] != '' && $noticia_texto != ''){echo '<div class="cabecera_notapop pb20 brd-b">';}
						if($noticia['titulo'] != ''){echo '<div class="poptit">'.$noticia['titulo'].'</div>';}
						if($usafotos == 1 || $usavideos == 1){include('nota_media.php');}
						if($noticia_texto != ''){
							echo '<div style="clear:both"></div><div class="poptxt">';
								echo $noticia_texto;
							echo '</div>';
						}
						if($noticia['titulo'] != '' && $noticia_texto != ''){echo '</div>';}
						?>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>