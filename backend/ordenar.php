<?PHP
include_once("../cnfg/config.php");
include_once("inc/sechk.php");
include_once("../inc/funciones.php");
include_once("inc/func_backend.php");
include_once("inc/img.php");
include_once("../clases/clase_error.php");
$emp_nom = config('nombre');

$error = new Errores();
$tipo  = getPost('tipo',config('tabla_defecto'));
if(!isset($_SESSION['sestipo']) || (isset($_SESSION['sestipo']) && $_SESSION['sestipo'] != $tipo) ){
	$_SESSION['sestipo'] = $tipo;
	$_SESSION['pagi'] = 1;
}
include("inc/leer_parametros.php");
$ord = getPost('ord');
$order_def = 'id DESC';
if($ord == 1){
	$variable = "home";
	$order_by = "orden";
	$titulo_orden = "Home";
}
if($ord == 2){
	$variable = "col";
	$order_by = "ordencol";
	$titulo_orden = "Columna";
}
function selectorTablas($on_off,$variable,$order_by,$titulo,$tabla,$cantidad=0){
	$tablas = "SELECT * FROM ".$_SESSION['prefijo']."tablas WHERE id = ".$tabla;
	$result = fullQuery($tablas);
	// $campo_orden = '';
	$pos = strpos($order_by, "DESC");
	// if ($pos !== false) {
	// 	$campo_orden = ', '.substr($order_by, 0, -5);	
	// }
	$contar_tab = 1;
	$query = '';
	while($rowtab = mysqli_fetch_array($result)){
		$estado = ($rowtab['usaestado'] == 1) ? 'AND estado = 2' : '';
		$usafec = ($rowtab['fecha'] == 1) ? ', fecha' : '';
		$tipo = $rowtab['id'];
		if($contar_tab > 1){$query.= ' UNION ';}
		$query.= 'SELECT '.$tipo.' AS tipo, id '. $campo_orden.', '.$variable.', '.$titulo.', del '.$usafec.' FROM '.$_SESSION['prefijo'].$rowtab['nombre'].' WHERE 1 AND del = 0 '.$estado.' AND '.$variable.' = '.$on_off;
		$contar_tab++;
	}
	$cant = ($cantidad == 0) ? '' : ' LIMIT '.$cantidad;
	$query.= ' ORDER BY '. $order_by . $cant ;
	return $query;
}
$sql_cant = "SELECT SUM(valor) AS suma FROM ".$_SESSION['prefijo']."config WHERE parametro = 'novedades_slider' OR parametro = 'novedades_home' OR parametro = 'novedades_mas' OR parametro = 'home_latam'";
$res_cant = fullQuery($sql_cant);
$row_cant = mysqli_fetch_assoc($res_cant);
$cantidad = $row_cant['suma']*3;
$cantidadon = $row_cant['suma'];
if($tipodet == 'feriados'){$cantidadon = 1000;}
$query_on  = selectorTablas(1,$variable,$order_by,$tipotit,$tipo,$cantidadon);
$query_off = selectorTablas(0,$variable,$order_def,$tipotit,$tipo,$cantidad);
//echo '<!--'.$query_on.'-->';
//echo $query_off;
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="es"><!--<![endif]-->
<head>
<?PHP include("inc/head.php");?>
<link rel="stylesheet" href="assets/css/ordenar.css" media="screen">
</head>
<body data-show-sidebar-toggle-button="true" data-fixed-sidebar="false">
    <div id="customizer">
        <div id="showButton"><i class="icon-cogs"></i></div>
        <div id="layoutMode">
            <label class="checkbox"><input type="checkbox" class="uniform" name="layout-mode" value="boxed"> En Caja</label>
        </div>
    </div>
    <div id="wrapper">
        <?PHP include_once("header.php");?>
        
        <div id="content-wrap">
        	<div id="content">
            	<div id="content-outer">
                	<div id="content-inner">
                    	<?PHP include_once("menu.php");?>
                        <div id="sidebar-separator"></div>
                        
                        <section id="main" class="clearfix">
                        	<div id="main-header" class="page-header">
                            	<ul class="breadcrumb">
                                	<li>
                                    	<i class="icon-home"></i>Backend
                                        <span class="divider">&raquo;</span>
                                    </li>
                                    <li>
                                    	<?PHP echo ucwords(txtcod($nombredet));?>: Ordenar
                                    </li>
                                </ul>
                                
                                <h1 id="main-heading">
                                	<?PHP echo ucwords(txtcod($nombredet));?> <span>ordenar</span>
                                </h1>
                            </div>
                            <div id="main-content">
                            	<div class="row-fluid">
                                	<div class="span12 widget">
                                        <div class="widget-header">
                                            <span class="title"><i class="icos-address-book"></i> <?PHP echo ucwords(txtcod($nombredet));?></span>
                                        </div>
                                        <div class="widget-content form-container"">
	                                        <div class="ordentot">
                                                <div class="ordentit">Items en <?PHP echo $titulo_orden;?></div>
                                                <div class="ordencol">
                                                    <?PHP 
                                                    $contnot = 0;
                                                    $result   = fullQuery($query_on);
                                                    $contador = mysqli_num_rows($result);
                                                    ?>
                                                    <ul id="sortable1" class="connectedSortable">
                                                        <?PHP
                                                        while($noticia = mysqli_fetch_array($result)){
                                                            $contnot++;
                                                            $item_id   = $noticia['id'];
                                                            $item_tipo = $noticia['tipo'];
                                                            ?>
                                                            <li id="order_<?PHP echo $item_id;?>*<?PHP echo $item_tipo;?>">
                                                                <?PHP  
                                                                if($usafotos == 1){// imagen principal
                                                                    $item = $item_id;
                                                                    $tipo_ord = $noticia['tipo'];
                                                                    include ("inc/muestra_imagen_orden.php");
                                                                }
                                                                ?>
                                                                <div class="row-result-ord">
                                                                    <span class="nom">
                                                                        <?PHP echo cortarTexto(txtcod($noticia[$tipotit]), 30);?>
                                                                    </span>
                                                                    <br />
                                                                    <?PHP 
                                                                    if($usafecha >= 1 && $usafecha <= 2){
                                                                        $texto_fecha = ($usafecha == 2) ? 'Vencimiento' : 'Fecha';
                                                                        echo $texto_fecha.': '.FechaDet($noticia['fecha'],'largo','s');
                                                                     }
                                                                     ?>
                                                                </div>
                                                            </li>
                                                        <?PHP }?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="ordentot">
                                                <div class="ordentit">Items NO en <?PHP echo $titulo_orden;?></div>
                                                <div class="ordencol">
                                                    <?PHP 
                                                    $contnot = 0;
                                                    $result   = fullQuery($query_off);
                                                    $contador = mysqli_num_rows($result);
                                                    ?>
                                                    <ul id="sortable2" class="connectedSortable">
                                                        <?PHP
                                                        while($noticia = mysqli_fetch_array($result)){
                                                            $contnot++;
                                                            $item_id   = $noticia['id'];
                                                            $item_tipo = $noticia['tipo'];
                                                            ?>
                                                            <li id="order_<?PHP echo $item_id;?>*<?PHP echo $item_tipo;?>">
                                                                <?PHP  
                                                                if($usafotos == 1){// imagen principal
                                                                    $item = $item_id;
                                                                    $tipo_ord = $noticia['tipo'];
                                                                    include("inc/muestra_imagen_orden.php");
                                                                }
                                                                ?>
                                                                <div class="row-result-ord">
                                                                    <span class="nom">
                                                                        <?PHP echo cortarTexto(txtcod($noticia[$tipotit]), 30);?>
                                                                    </span>
                                                                    <br />
                                                                    <?PHP 
                                                                    if($usafecha >= 1 && $usafecha <= 2){
                                                                        $texto_fecha = ($usafecha == 2) ? 'Vencimiento' : 'Fecha';
                                                                        echo $texto_fecha.': '.FechaDet($noticia['fecha'],'largo','s');
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        <?PHP }?>
                                                    </ul>
                                                </div>
                                            </div>
											<div style="clear:both;"></div>
                                        </div>
    	                            </div>
                                </div>                                
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        
		<?PHP include("footer.php");?>
        
    </div>
	<?PHP include("scripts_base.php");?>
    
   	<!-- Demo Scripts -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
    <script type="text/javascript">
		$(function() 
		{
			$("#sortable1, #sortable2").sortable(
			{
				connectWith: '.connectedSortable',
				update : function () 
				{ 
					$.ajax(
					{
						type: "POST",
						url: "ordena_post<?PHP if($ord == 2){echo '_col';}?>.php",
						data: 
						{
							sort1:$("#sortable1").sortable('serialize'),
							sort2:$("#sortable2").sortable('serialize')
						},
						success: function(html)
						{
							//$('.success').fadeIn(500);
							//$('.success').fadeOut(500);
						}
					});
				} 
			}).disableSelection();
		});
	</script>
</body>
</html>