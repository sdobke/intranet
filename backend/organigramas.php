<?PHP
include_once("../cnfg/config.php");
include_once("inc/sechk.php");
include_once("../inc/funciones.php");
include_once("inc/func_backend.php");
include_once("../clases/clase_error.php");
$emp_nom = config('nombre');

$error = new Errores();
$tipo  = getPost('tipo',config('tabla_defecto'));
$tipodet = obtenerDato('nombre','tablas',$tipo);
if(!isset($_SESSION['sestipo']) || (isset($_SESSION['sestipo']) && $_SESSION['sestipo'] != $tipo) ){
	$_SESSION['sestipo'] = $tipo;
	$_SESSION['pagi'] = 1;
}
include("inc/leer_parametros.php");
$nombredet = 'Documentos Espec&iacute;ficos';
if(isset($_POST['guardar'])){
	include_once("inc/organipost.php");
}?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="es"><!--<![endif]-->
<script type="text/javascript">
function ocultarMostrar(obj) {
	no = obj.options[obj.selectedIndex].value;
	count = obj.options.length;
	for(i=1;i<count;i++)
	document.getElementById('EmpDiv'+i).style.display = 'none';
	if(no>0)
	document.getElementById('EmpDiv'+no).style.display = 'block';
}
</script>
<head>
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
                                    	<?PHP echo ucwords(txtcod($nombredet));?>: alta
                                    </li>
                                </ul>
                                
                                <h1 id="main-heading">
                                	<?PHP echo ucwords(txtcod($nombredet));?> <span>alta</span>
                                </h1>
                            </div>
                            <div id="main-content">
                            	<div class="row-fluid">
                                	<div class="span12 widget">
                                        <div class="widget-header">
                                            <span class="title"><i class="icos-address-book"></i> <?PHP echo ucwords(txtcod($nombredet));?></span>
                                        </div>
                                        <div class="widget-content form-container">
                                          <form action="organigramas.php" method="post" enctype="multipart/form-data" name="doc" class="form-horizontal">
						                    
						                    <div class="control-group">
												<label class="control-label" for="archivo">Documento: </label>
												<div class="controls">
													<input type="file" id="archivo" name="archivo" data-provide="fileinput">
												</div>
											</div>
						                    <div class="control-group">
												<label class="control-label" for="input01">Empresa:</label>
												<div class="controls">
													<select id="input01" class="span12" name="empresa" onchange="javascript:ocultarMostrar(this)">
														<?PHP
														$sql_empre = "SELECT * FROM ".$_SESSION['prefijo']."empresas";
														$res_empre = fullQuery($sql_empre);
														while($row_empre = mysqli_fetch_array($res_empre)){
															echo '<option value="'.$row_empre['id'].'">'.$row_empre['detalle'].'</option>';
														}
														?>
													</select>
												</div>
											</div>
											<?PHP
											$contar = 1;
											while($contar <= 4){ // la cantidad de empresas existente
												$most = 'none';
												$tiploc = 'Locales';
												switch($contar){
													case 1:
														$codemp = 'alsea';
														$nomemp = 'Alsea';
														$most   = 'block';
														break;
													case 2:
														$codemp = 'burger';
														$nomemp = 'Burger King';
														break;
													case 3:
														$codemp = 'bucks';
														$nomemp = 'Starbucks';
														$tiploc = 'Tiendas';
														break;
													case 4:
														$codemp = 'chang';
														$nomemp = 'PF Chang&apos;s';
														break;														
												}
												?>
							                    <div class="control-group" id="EmpDiv<?PHP echo $contar;?>" style="display:<?PHP echo $most;?>">
													<label class="control-label" for="input01">Tipo de Documento:</label>
													<div class="controls">
														<select id="input01" class="span12" name="doc<?PHP echo $codemp;?>" >
								                          <option value="1">Organigrama (PDF)</option>
								                          <option value="2">C&oacute;digo de Conducta Alsea(PDF)</option>
														  <?PHP if($contar > 1){?>
									                          <option value="3">C&oacute;digo de Conducta <?PHP echo $nomemp;?> (PDF)</option>                          
									                          <option value="4"><?PHP echo $tiploc;?> (XLS)</option>
														  <?PHP } ?>
														</select>
													</div>
												</div>
												<?PHP
												$contar++;
											}
											?>
						                    <div class="form-actions">
												<button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
											</div>
						                  </form>
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
    <!-- Validation -->
	<script src="plugins/validate/jquery.validate.min.js"></script>
    <!--<script src="js/validar_detalles.js"></script>-->
    
   	<!-- Demo Scripts -->
    <script src="assets/js/demo/ui_comps.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>