<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$tipo    = 4;
$tipodet = obtenerDato('nombre','tablas',$tipo);
agrega_acceso($tipo);
$nombretab = obtenerNombre($tipo);
$titsec = $nombretab;
$tipotit = parametro('titulo',$tipo);
$limit  = cantidad('cant_emple');//Cantidad de resultados por página
include("backend/inc/leer_parametros.php");
$busqueda = getPost('busqueda',0,0,0);
$link_destino_search = "empleados.php";
?>
<!DOCTYPE html>
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
				<div class="container mb10 pb15 brd-bs t30 mt10 pt5 nettooffc c999999">Empleados</div>
				<div class="container_inner_2">
					<div class="col_ppal left">
						<?php include 'navegacion.php'; ?>						
						<div class="cabecera_nota mb20 brd-b">
							<h3><span class="tup"><a href="#">Empleados</a></span> | Resultados de la b&uacute;squeda</h3>
						</div>	
						<div class="cabecera_nota pb10 mt10">
							<h3><span class="tup b">Nuestros Empleados</span></h3>
						</div>
						<!--<div class="cuerpo_nota mb15 pb15 brd-b"></div>-->
						
						<?php if($busqueda != ''){ ?>
							<div class="cuerpo_nota">
								<ul class="empleados">
									<?php
									include 'empleados_include_emp.php';
									?>
								</ul>
							</div>
						<?php }else{echo 'Faltan el nombre o apellido a buscar.';}?>
					
				</div>
				<?php include("col_der.php"); ?>
			</div>
			<div class="clr"></div>
		</div>
		</div>
		<?PHP include("footer.php");?>
		<script>
			function sendFormArea(area){
				window.location.href = "areas.php?areaemp="+area+"&cod=<?php echo $cod; ?>"
			}
		</script>
	</body>
</html>