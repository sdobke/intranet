<?PHP 
include "cnfg/config.php";
include "inc/funciones.php";
include "inc/inc_docs.php";

$tipo = 34;
include("backend/inc/leer_parametros.php");
$emp_nom = config('nombre');
if(!isset($_POST) && !isset($_GET)){agrega_acceso($tipo);}
$nombre = obtenerNombre($tipo);
$titsec = obtenerDato('detalle','tablas',$tipo);
$empre = getPost('bus_empresas',0);
$cad_emp = ($empre > 0) ? " AND empresa = ".$empre : "";
$area = getPost('bus_areas',0);
$cad_are = ($area > 0) ? " AND area = ".$area : "";
$sec = getPost('bus_sectores',0);
$cad_sec = ($sec > 0) ? " AND sector = ".$sec : "";
$vars = "&bus_empresas=".$empre."&bus_areas=".$area."&bus_sectores=".$sec;
$otro_query = $cad_emp.$cad_sec.$cad_are." AND del = 0";
$query_orden = 'nombre';
include "backend/inc/query_busqueda.php";
$limit  = cantidad('cant_docus');//Cantidad de resultados por página
$limit  = 10;
include "inc/prepara_paginador.php";
$query4 = $query;
//echo $query;
$contdocs = 0;
$result      = fullQuery($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_marcas.php"); ?>
        <script type="text/javascript" src="js/jstip.js"></script>
        <link href="/css/jstip.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
<?PHP include("header.php");?>
				<?php include_once 'menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 mt15 nettooffc c999999"><?PHP echo txtcod($titsec);?></div>
				<div class="container_inner_2">
					<div class="col_ppal left">
						<!-- DINAMIC CONTENT -->
                        <?PHP $navegacion = '<a href="politicas.php?a=1'.$vars.'">Link</a>&nbsp;';?>
						<?php include 'navegacion.php'; ?>
                        
						<div class="cabecera_nota mb20 brd-b">
                        	<?PHP $vertit = (isset($_SESSION['usrfrontend'])) ? txtcod($titsec) : 'Por favor ingres&aacute; con tu usuario y contrase&ntilde;a para visualizar documentos con acceso restringido.';
							?>
							<h3><span class="tup"><a href="#"><?PHP echo $vertit;?></a></h3>
							<h2><?PHP echo txtcod($titsec);?></h2>
						</div>	
						<div class="cuerpo_nota">
                        <?PHP include_once("inc/buscador.php");?>
						
						<?PHP        
							//echo $query4;
							$resultado = fullQuery($query4);
							$lupa = '&nbsp;<img src="img_new/herramientas/lupa.png" />';
							?>
							<div class="formularios left mb15">
                                <div class="cuerpo_nota">
                                        <?php
                                        while ($docs = mysqli_fetch_array($resultado)){
                                            $contdocs++;
                                            $tipoarc   = $docs['tipoarc'];
                                            $id        = $docs['id'];
                                            $nombre    = txtcod($docs['nombre']);
                                            $peso      = $docs['peso'];
                                            $pesover   = medidaDocs($peso);
                                            $areaver   = obtenerDato('nombre','areas',$docs['area']);
											$sectver   = obtenerDato('nombre','sectores',$docs['sector']);
                                            $link      = $docs['link'];
											$nom_emp   = obtenerDato('nombre','empresas',$docs['empresa']);
											$nom_emp = ($nom_emp == 'alsea') ? 'Alsea: &Aacute;reas Corporativas' : ucwords($nom_emp);
											$urlink = txtcod($nom_emp.' - '.$areaver.' - '.$sectver);
                                            ?>
                                            <div style="clear:both;"></div>
                                            <div style="height: 45px">
                                                <div style="float: left; margin-right: 5px">
												<a href="<?PHP echo $link;?>" <?PHP if ($tipoarc == 'jpg'){echo 'rel="lightbox[roadtrip]"';}else{echo 'target="_blank"';}?>>
													<img src="img/ic_<?PHP echo arch_img($tipoarc);?>.gif" alt="" width="35" height="35" class="aligncenter" />
												</a>
											</div>
											<div style="float:left">
												<a href="<?PHP echo $link;?>" <?PHP if ($tipoarc == 'jpg'){echo 'rel="lightbox[roadtrip]"';}else{echo 'target="_blank"';}?>><strong><span onmouseover="tooltip.show('<?PHP echo $nombre;?>');" onmouseout="tooltip.hide();"><?PHP echo cortarTxt($nombre,75, '...' .$lupa); ?></span></strong></a>
												| <strong>Peso:</strong> <?PHP echo $pesover;?>
                                                <br /><span onmouseover="tooltip.show('<?PHP echo $urlink;?>');" onmouseout="tooltip.hide();"><?PHP echo cortarTxt($urlink,100, '...' .$lupa); ?></span><br /><br />
											</div>
										</div>
                                        <?PHP } ?>
                                </div>
                            </div>
						</div>
                        <div class="paginador t11 ac">
							<?PHP
                            $variables = "busqueda=".$busqueda."&tipo=".$tipo.$vars; // variables para el paginador
                            echo paginador($limit, $contar, $pag, $variables);
                            ?>
                        </div>
						
					</div>
                <?php include("col_der.php"); ?>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
		<script>
			function sendForm(){
				document.getElementById("buscador").submit();
			}
		</script>
	</body>
</html>