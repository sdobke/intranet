<?php
include "../cnfg/config.php";
include "../inc/funciones.php";
//include "inc/clase_excel.php";
include_once("func_encuestas.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Encuestas</title>
		<?PHP include ("head.php"); ?>
		<style>
			.success_box,
			.alert_box,
			.info_box
			{
				width: 900px !important;
			}
		</style>
		<script type="text/javascript" src="calendarDateInput.js"></script>
		<script type="text/javascript" src="inc/scripts.js"></script>
		<script type="text/javascript">
		function submitformBuscar() {
			document.formbuscar.submit();
		}
		function ocultarMostrar(nombre) {
			var tomarValor = document.getElementById(nombre).style.display;
			if(tomarValor == 'none'){
				document.getElementById(nombre).style.display = 'block';
			}else{
				document.getElementById(nombre).style.display = 'none';
			}
		}
		function mostrarDiv(nombre) {
			document.getElementById('meses').style.display = 'none';
			document.getElementById('periodo').style.display = 'none';
			document.getElementById(nombre).style.display = 'block';
		}
		</script>
	</head>
<?PHP
//Generamos el objeto 
if (isset($_POST['generaexcel']) && $_POST['generaexcel'] == 1) {
	$excel = new Export2ExcelClass;
}
function ultimo_dia($mes,$ano){return strftime("%d", mktime(0, 0, 0, $mes+1, 0, $ano));}

$GLOBALS['selector'] = (isset($_POST['selec_fecha'])) ? $_POST['selec_fecha'] : '';

if (isset($_GET['func'])){
	$func = $_GET['func'];
	if($func == 2){// baja de reclamo
		$reclamo_id = $_GET['id'];
		$query      = "DELETE FROM ".$_SESSION['prefijo']."encuestas_listado WHERE id = ".$reclamo_id;
		$result     = fullQuery($query);
	}
}

$GLOBALS['selector'] = (isset($_POST['selec_fecha'])) ? $_POST['selec_fecha'] : 'mes';
// Si no hubo pedido de fechas muestra el último mes
$fechadesde   = date('Y-m').'-01';
$fechahasta   = date('Y-m-d');

$mes = getPost('mes', date('m'));
$ano = getPost('ano', date('y'));

if (isset($_POST['posteado']) && $_POST['posteado'] == 1){
	if($GLOBALS['selector'] == 'mes'){
		$fechadesde = $ano.'-'.$mes.'-01';
		$fechahasta = $ano.'-'.$mes.'-'.ultimo_dia($mes,$ano);
	}else{
		$fechadesde = $_POST['fecha_desde'];
		$fechahasta = $_POST['fecha_hasta'];
	}
}
if (isset($_GET['desde']) && isset($_GET['hasta'])){
	$fechadesde = $_GET['desde'];
	$fechahasta = $_GET['hasta'];
}
$cadinicio = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_listado ";
$cadbusca = ' WHERE 1 ';
// si recibe POST
$cadbusca.= " AND (DATE(fecha) BETWEEN '".$fechadesde."' AND '".$fechahasta."') "; 
// VER CADA POST POR CADA CAMPO
$cont    = 0;
$query6  = fullQuery("SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE nombre != 'fecha' ORDER BY orden");

$orden = getPost('orden', 'fecha');
$cadresto = " ORDER BY ".$orden;

$items  = getPost('items', 100);

$tipocom = getPost('tipocom', 0);
$buscartxt = getPost('buscartxt', '');

if($tipocom > 0){
	$cadbusca.= " AND tipo_de_comentario = ".$tipocom;
}

$buscatexto = '';

if($buscartxt != ''){
	$buscampo1 = 'nombre';
	$buscampo2 = 'descripcion';
	// arma las búsquedas según la cantidad de campos
	$buscampos = $buscampo1." LIKE '%".$buscartxt."%' ";
	$matches   = $buscampo1;
	if(isset($buscampo2)){
		$buscampos.= " OR ".$buscampo2." LIKE '%".$buscartxt."%' ";
		$matches.= ", ".$buscampo2;
	}
	//Cuento la cantidad de palabras
	$trozos = explode(" ",$buscartxt);
	$numero = count($trozos);
	if ($numero == 1) {
		//Si tengo una palabra
		$buscatexto = " AND (".$buscampos.") ";
	}elseif ($numero > 1){
		//Si tengo más de una palabra
		$palabra1 = current($trozos);
		$palabra2 = end($trozos);
		$cadbusca = "SELECT *, MATCH (".$matches.") AGAINST ('$palabra1' IN BOOLEAN MODE) as aprox FROM ".$_SESSION['prefijo']."encuestas_listado ".$cadbusca." AND MATCH (".$matches.") AGAINST ('$palabra2' IN BOOLEAN MODE) ORDER BY aprox DESC, ".$orden;
		$buscatexto = $cadinicio = $cadresto = '';
  	}
}
$cadbusca.= $buscatexto;
$query_todo = $cadinicio.$cadbusca.$cadresto;
//echo $query_todo;

?>
<body>
	<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="/index.php"><img src="../img_new/logo.png" width="170" height="75" /></a>
					</div>
				<?php include_once '../login.php'; ?>					
				</div>
				<div class="container">
					<div class="hd-minisitio mb5">Encuestas</div>					
					<div class="left w100" >						
						<?PHP include "menu.php";?>
						<div class="contenidos" >
							<div id="contenedor" class="buscador-minisitio mt10">
								<?PHP include("selectores.php");?>
								<div style="clear:both;"></div>
								<div align="center"><a href="javascript:void(0);" class="boton" onclick="ocultarMostrar('ccentro_resul');">Colapsar Resultados</a></div>
							</div>
							<?php include 'resultados_view.php'; ?>
							<?php include 'resultados_campos.php'; ?>
							<div style="clear:both"></div>
						</div>
					</div>
					<div class="left w100 ac brd-t mt10"><img src="../img_new/cierre.png" width="630" height="71" /></div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>