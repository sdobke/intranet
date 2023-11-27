<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="es"><!--<![endif]-->
<head>
<?PHP
include_once("../cnfg/config.php");
include_once("inc/sechk.php");
include_once("../inc/funciones.php");
include_once("inc/func_backend.php");
include_once("../clases/clase_error.php");
$emp_nom = config('nombre');

$error = new Errores();
?>
<?PHP include("inc/head.php");?>
</head>
<body data-show-sidebar-toggle-button="true" data-fixed-sidebar="false">
    <div id="customizer">
        <div id="showButton"><i class="icon-cogs"></i></div>
        <div id="layoutMode">
            <label class="checkbox"><input type="checkbox" class="uniform" name="layout-mode" value="boxed"> En Caja</label>
        </div>
    </div>
	<!--<div id="style-changer"><a href="../simple/index.html"></a></div>-->
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
                                    	Minisitios: Acceso
                                    </li>
                                </ul>
                                
                                <h1 id="main-heading">
                                	Minisitios <span>accesos</span>
                                </h1>
                            </div>
                            <div id="main-content">
                            	<div class="row-fluid">
                                    <div class="span12 widget">
                                        <div class="widget-header">
                                            <span class="title">
                                                <i class="icol-table"></i> Minisitios
                                            </span>
                                        </div>
                                        <div class="widget-content table-container">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Minisitio</th>
                                                        <th>Acceso</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?PHP
													$sql_mini = "SELECT * FROM ".$_SESSION['prefijo']."minisitios WHERE del = 0 ORDER BY nombre";
													$res_mini = fullQuery($sql_mini);
													while($row_mini = mysqli_fetch_array($res_mini)){
														?>
                                                    	<tr>
                                                            <td><?PHP echo txtcod($row_mini['nombre']);?></td>
                                                            <td><a href="../<?PHP echo $row_mini['link'];?>">Acceder</a></td>
														</tr>
													<?PHP }?>
                                                </tbody>
                                            </table>
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
    <script src="assets/js/demo/ui_comps.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>