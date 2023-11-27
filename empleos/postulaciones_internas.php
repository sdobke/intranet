<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
require_once("../login_init.php");
$usrlog = (isset($_SESSION['tipousr']) && $_SESSION['tipousr'] == 1) ? 1 : 0;
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_empleos.php"); ?>
		
		<script type="text/javascript" src="includes/ocultar-mostrar.js"></script>

		<script type="text/javascript" src="../inc/scripts.js"></script>
		<link rel="stylesheet" type="text/css" href="../inc/jqueryslidemenu.css" />
		<style type="text/css">
		html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
		</style>
		<script type="text/javascript" src="../inc/jquery.min.js"></script>
		<script type="text/javascript" src="../inc/jqueryslidemenu.js"></script></head>
			<!--[if lt IE 7]>
		<script defer type="text/javascript" src="includes/pngfix_map.js"></script>
		<![endif]-->
		<script type="text/javascript">
			<!--
			function MM_openBrWindow(theURL,winName,features) { //v2.0
			  testwindow=window.open(theURL,winName,features);
			  testwindow.moveTo(100,100);
			}
			//-->
		</script>
        <link rel="stylesheet" href="css/ocultar-mostrar.css" type="text/css" media="screen" charset="utf-8" />
		
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
                            <div class="textos">
                                <div class="hd-seccion">Postulaciones Internas</div>
								<?PHP if($usrlog == 0){?><div align="left">No olvides ingresar tu usuario para postularte.</div><?PHP } ?>

                                <?PHP
								$query = fullQuery("SELECT valor FROM empleos_config where parametro = 'vencimiento'");
								$row = mysqli_fetch_array($query);
								$diferencia = $row['valor'];
								$sql_postu = "SELECT busq.id AS id, areas.nombre AS nomarea, uen.nombre AS nomuen, busq.nombre AS nombre, busq.texto AS detalle, busq.fecha AS fecha, busq.estado AS estado
												FROM empleos_busquedas AS busq 
												INNER JOIN empleos_area AS areas ON areas.id = busq.area
												INNER JOIN empleos_uen AS uen ON uen.id = busq.uen
												WHERE busq.del = 0
												ORDER BY estado, fecha DESC LIMIT 20";
												//WHERE DATE_ADD(busq.fecha, INTERVAL ".$diferencia." DAY) >= DATE(NOW())";
								$res_postu = fullQuery($sql_postu);
								$con_postu = mysqli_num_rows($res_postu);
								if($con_postu > 0){
									$cont = 1;
									?>
                                    <div style="float: left;position: relative;" class="puesto3">Puesto</div>
                                    <div style="float: left;position: relative;" class="area3">Area</div>
                                    <div style="float: left;position: relative;" class="uen3">UEN</div>
                                    <div style="float: left;position: relative;" class="vence3">Vence</div>
                                    <div style="float: left;position: relative;" class="estado3">Estado</div>
                                    <!--<div class="postu3">&nbsp;</div>-->
                                    <div style="clear:both"></div>
                                    <?PHP
                                    while($row_postu = mysqli_fetch_array($res_postu)){
                                        $pos_id = $row_postu['id'];
                                        $postulacion = '';
                                        $pos_est = $row_postu['estado'];
										$vencida = ($row_postu['fecha'] >= date("Y-m-d")) ? 0 : 1;
										$ndiv = ($cont % 2 == 0) ? '2' : '';
                                        if($pos_est == 1 && $vencida == 0 && $usrlog == 1){
                                            $postulacion = '<div class="postular"><a href="postulacion.php?id='.$pos_id.'">Postularme</a></div>';
                                        }
                                        ?>
                                        <div class="dhtmlgoodies_question">
                                            <div class="puesto<?PHP echo $ndiv;?>">
                                                <?PHP echo cortarTexto($row_postu['nombre'], 30);?>
                                            </div>
                                            <div class="area<?PHP echo $ndiv;?>">
                                                <?PHP echo $row_postu['nomarea'];?>
                                            </div>
                                            <div class="uen<?PHP echo $ndiv;?>">
                                                <?PHP echo $row_postu['nomuen'];?>
                                            </div>
                                            <div class="vence<?PHP echo $ndiv;?>">
                                                <?PHP echo FechaDet($row_postu['fecha'],'corto');?>
                                            </div>
                                            <div class="estado<?PHP echo $ndiv;?>">
                                                <?PHP echo ($vencida == 1 && $pos_est == 1) ? 'Vencida' : obtenerDato('nombre','estado',$pos_est,'empleos_');?>
                                            </div>
											<?PHP echo $postulacion;?>
                                        </div>
                                        <div class="dhtmlgoodies_answer">
                                            <div>
                                                <strong><?PHP echo $row_postu['nombre'];?></strong>
                                                <br /><br />
                                                <?PHP
                                                echo $row_postu['detalle'];
                                                /*
												if($pos_est == 3){
                                                    $sql_ingr = "SELECT ep.* FROM empleos_postulantes AS ep
                                                                        INNER JOIN empleos_busqueda_postulantes AS ebp ON ep.id = ebp.postulante
                                                                    WHERE ebp.busqueda = ".$pos_id." AND ebp.estado = 1";
													$res_ingr = fullQuery($sql_ingr);
                                                    $con_ingr = mysqli_num_rows($res_ingr);
                                                    if($con_ingr > 0){
                                                        echo '<br /><br /><strong>Postulantes ingresados:</strong><br />';
                                                        while($row_ingr = mysqli_fetch_array($res_ingr)){
                                                            echo '<br />'.$row_ingr['nombre'];
                                                        }
                                                    }
                                                }*/
                                                ?>
                                            </div>
                                        </div>
    
                                        <?PHP 
                                        $cont++;
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        initShowHideDivs();
                                        showHideContent(false,1);	// Automatically expand first item
                                    </script>
                                <?PHP }else{ echo 'No hay b&uacute;squedas activas en este momento.';}?>
							</div>
                            <br /><br />
                            <a href="javascript:history.back(1)">Volver</a>
                       
                         </div>
				<?php include 'col_izq.php'; ?>
			</div>
			<div class="clr"></div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>