<?PHP
include_once("../cnfg/config.php");
include_once("inc/sechk.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
$nombredet = "Empleados: Altas y Bajas";
if(isset($_POST['envio'])){include_once("emplemod_post.php");}
include_once("emplemod_proceso.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="es"><!--<![endif]-->
<head>
<?PHP include("inc/head.php");?>
<script type="text/javascript">
function formSubmit(nomform){
	document.getElementById(nomform).submit();
}
</script>
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
                                    	<?PHP echo ucwords(txtcod($nombredet));?>
                                    </li>
                                </ul>
                                
                                <h1 id="main-heading">
                                	<?PHP echo ucwords(txtcod($nombredet));?>
                                </h1>
                            </div>
                            <div id="main-content">
                            	<?PHP include_once("emplemod_in.php");?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        
		<?PHP include("footer.php");?>
        
    </div>
	<?PHP include("scripts_base.php");?>
    <script src="js/scripts.js"></script>
  
</body>
</html>