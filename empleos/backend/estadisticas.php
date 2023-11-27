<?PHP
include ("../../cnfg/config.php");
include ("sechk.php");
include ("inc/inc_funciones_globales.php");

function mesActual($mes){
	$activo = (isset($_POST['mes'])) ? $_POST['mes'] : date('m');
	$dev = '';
	if ($mes == $activo){
		$dev = 'selected="selected"';
	}
	return $dev;
}
function anioActual($anio){
	$activo = (isset($_POST['ano'])) ? $_POST['ano'] : date('Y');
	$dev = '';
	if ($anio == $activo){
		$dev = 'selected="selected"';
	}
	return $dev;
}
// Por defecto, el mes actual
$fechadesde = date("Y-m")."-"."1";
$fechahasta = date("Y-m-d");

$fecha = (isset($_POST['fecha'])) ? $_POST['fecha'] : 'mes';
if(isset($_GET['fecha']) && $_GET['fecha'] == 'tot'){$fecha = 'tot';}
 
if (isset($_POST['posteado']) && $_POST['posteado'] == 1){
	if($fecha == 'mes'){
		$mes = $_POST['mes'];
		$ano = $_POST['ano'];
		$fechadesde = $ano.'-'.$mes.'-01';
		$fechahasta = $ano.'-'.$mes.'-31';
	}else{
		$fechadesde = $_POST['fecha_desde'];
		$fechahasta = $_POST['fecha_hasta'];
	}
}
if (isset($_GET['desde']) && isset($_GET['hasta'])){
	$fechadesde = $_GET['desde'];
	$fechahasta = $_GET['hasta'];
}
/*echo 'desde: '.$fechadesde;
echo '<br />';
echo 'hasta: '.$fechahasta;*/

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | Estad&iacute;sticas</title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="calendarDateInput.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
function submitformBuscar() {
    document.formbuscar.submit();
}
</script>
</head>
<body>
<div id="contenedor">
	<div id="header">  </div>
    <?PHP include ("menu.php");?>
	<div style="width:950px; margin:auto; height:auto" id="interior">
        <div align="center">
            <h1>Estad&iacute;sticas por per&iacute;odos</h1>
            <form id="formbuscar" name="formbuscar" method="post" action="estadisticas.php">
                <table cellpadding="4" cellspacing="4" bgcolor="#f6f6f6" style="color:#333">
                    <tbody>
                        <tr>
                            <td><input name="fecha" type="radio" value="mes" <?PHP if($fecha == 'mes'){echo 'checked="checked"';}?> />Mes</td>
                            <td>
                                <select id="mes" name="mes">
                                    <option value="01" <?PHP echo mesActual('01');?>>Enero</option>
                                    <option value="02" <?PHP echo mesActual('02');?>>Febrero</option>
                                    <option value="03" <?PHP echo mesActual('03');?>>Marzo</option>
                                    <option value="04" <?PHP echo mesActual('04');?>>Abril</option>
                                    <option value="05" <?PHP echo mesActual('05');?>>Mayo</option>
                                    <option value="06" <?PHP echo mesActual('06');?>>Junio</option>
                                    <option value="07" <?PHP echo mesActual('07');?>>Julio</option>
                                    <option value="08" <?PHP echo mesActual('08');?>>Agosto</option>
                                    <option value="09" <?PHP echo mesActual('09');?>>Septiembre</option>
                                    <option value="10" <?PHP echo mesActual('10');?>>Octubre</option>
                                    <option value="11" <?PHP echo mesActual('11');?>>Noviembre</option>
                                    <option value="12" <?PHP echo mesActual('12');?>>Diciembre</option>
                                </select>
                                <select name="ano" id="ano">
                                <?PHP
                                    $query_fechas = fullQuery("SELECT DISTINCT YEAR(fecha) AS anio FROM intranet_accesos_detalle ORDER BY anio DESC") or $error = "MySQL: ".mysqli_error();
                                    while($fechanio = mysqli_fetch_array($query_fechas)){
										$anio_sel = $fechanio['anio'];
                                    ?>
                                        <option value="<?PHP echo $anio_sel;?>" <?PHP echo anioActual($anio_sel);?>><?PHP echo $anio_sel;?></option>
                                    <?PHP } ?>
                                </select>					
                                <input name="posteado" id="posteado" type="hidden" value="1" />
                            </td>
                        </tr>
                        <tr bgcolor="#e6e6e6">
                            <td><input name="fecha" type="radio" value="per" <?PHP if($fecha == 'per'){echo 'checked="checked"';}?> /> Per&iacute;odo</td>
                            <td>
                                <?PHP
                                $query_fechas = "SELECT fecha FROM intranet_accesos_detalle ORDER BY fecha LIMIT 1";
								$res_fechas   = fullQuery($query_fechas);
                                $row_fechas   = mysqli_fetch_array($res_fechas);
                                $fechainicio  = (isset($_POST['fecha_desde']) && $fecha == 'mes') ? $fechadesde : $row_fechas['fecha'];
								//$fechadesde   = $fechainicio;
                                $query_fechas = "SELECT fecha FROM intranet_accesos_detalle ORDER BY fecha DESC LIMIT 1";
								$res_fechas   = fullQuery($query_fechas);
                                $row_fechas2  = mysqli_fetch_array($res_fechas);
                                $fechafin     = (isset($_POST['fecha_hasta']) && $fecha == 'mes') ? $fechahasta : $row_fechas2['fecha'];
								//$fechahasta   = $fechafin;
                                ?>							
                                <table>
                                    <tr>
                                        <td>Desde <script type="text/javascript">DateInput('fecha_desde', true, 'YYYY/MM/DD', '<?PHP echo $fechainicio;?>')</script></td>
                                    </tr>
                                    <tr>
                                        <td>Hasta <script type="text/javascript">DateInput('fecha_hasta', true, 'YYYY/MM/DD', '<?PHP echo $fechafin;?>')</script></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
						<tr>
	                        <td><input name="fecha" type="radio" value="tot" <?PHP if($fecha == 'tot'){echo 'checked="checked"';}?> disabled="disabled" /> Todo</td>
                            <td align="center" >
                                <a href="estadisticas.php?desde=<?PHP echo $row_fechas['fecha'];?>&hasta=<?PHP echo $row_fechas2['fecha'];?>&fecha=tot">Ver hist&oacute;rico total</a>
                            </td>
                        </tr>
                        <tr bgcolor="#e6e6e6">
                            <td colspan="2" align="center" bgcolor="#366">
                                <a href="javascript: submitformBuscar()"><img src="../img/buscar.png" alt="Buscar" border="0" /></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            </div>
		</div>

    	<?PHP /*********************************************** ESTADISTICAS ***************************************************/?>
		<div style="clear:both;"></div>
		<br /><br />
   		<h1 align="center">Estad&iacute;sticas</h1><br />
		<!-- -----------------------------------------------------Postulaciones------------------------------------------------------------- -->
    	<div style="width:728px; margin:auto; height:auto;">
            <div style="float:left; width:220px">
            	<?PHP $titulo = 'Postulaciones';?>
<!--                <h3><?PHP echo $titulo;?></h3>-->
                <br />
                <?PHP
                $sql = "SELECT tab.detalle AS tipobusq, bus.*,COUNT(bus.id) AS cant FROM empleos_stats_busq AS bus
							INNER JOIN empleos_tablas AS tab ON tab.id = bus.tipo
                                WHERE (DATE(bus.fecha) BETWEEN '$fechadesde' AND '$fechahasta') 
                                GROUP BY tipo
                                ORDER BY cant DESC";
                    //echo $sql;

                $grafico='<div class="row-latam">';
				$grafico.='	<table width="800px" border="0"><tr><td width="250px">';
				$totcant = 0;
				$cont = 0;
				$chart1 = "<img src='chart.php?data=";
				$chart2 = "";
				$grafico.= '<span style="font-size:14px;"><strong>'.$titulo.'</strong></span>:';
				$grafico.= "<br /><br />";
				
                $res = fullQuery($sql);
				$totcant = mysqli_num_rows($res);
                while($dato = mysqli_fetch_array($res)){
                    $cantidad = $dato['cant'];
					$nomvar = $dato['tipobusq'];
                ?>
<!--                    <div style="border:solid #CCC 1px; width:200px; height:30px; margin-bottom:10px; padding:5px">
                        <div>
                            <div>
                                <span class="tit">
                                    <?PHP echo $nomvar;?>:
                                </span>
                                <span class="nom">
                                    <?PHP echo $cantidad;?>
                                </span>
                            </div>
                        </div>
                    </div>-->
					<?PHP
					// Si no es el primer resultado agrega el separador
					if ($cont > 0){
						$chart1 .= "*";
						$chart2 .= "*";
					}
					$chart1 .= $cantidad; //nro de veces
					$chart2 .= $nomvar; //nombre del campo
					$grafico.= $nomvar.": ".$cantidad."<br />";
					$cont++;
					?>
				<?PHP }?>
                <?PHP
				$chart = $chart1."&amp;label=".$chart2."' /><br /><br />";
				$grafico.='</td><td>'.$chart.'</td></tr></table>';
				$grafico.='</div>';
				if($totcant > 0){echo $grafico;}else{echo 'No hay resultados para el per&iacute;odo.';}
				?>				
			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- -----------------------------------------------------TOP 10------------------------------------------------------------- -->
		<div style="width:728px; margin:auto; height:auto;">
            <div style="float:left; width:220px">
            	<?PHP $titulo = 'Top 10 mayor impacto';?>
                <h3><?PHP echo $titulo;?></h3>
                <br />
                <?PHP
                $sql = "(SELECT stat.tipo AS tipo, bus.nombre AS nombre, stat.postulados AS cant FROM empleos_stats_busq AS stat
							INNER JOIN empleos_busquedas AS bus ON stat.busqueda = bus.id
							WHERE tipo = 1 AND (DATE(bus.fecha) BETWEEN '$fechadesde' AND '$fechahasta') 
							GROUP BY nombre)
						UNION
						(SELECT stat.tipo AS tipo, bus.nombre AS nombre, stat.postulados AS cant FROM empleos_stats_busq AS stat
							INNER JOIN empleos_busque_refe AS bus ON stat.busqueda = bus.id
							WHERE tipo = 12  AND (DATE(bus.fecha) BETWEEN '$fechadesde' AND '$fechahasta') 
							GROUP BY nombre)	
						ORDER BY cant DESC LIMIT 10";
                    //echo $sql;

                $res = fullQuery($sql);
                while($dato = mysqli_fetch_array($res)){
                    $cantidad = $dato['cant'];
					$nomvar = $dato['nombre'];
                ?>
                    <div style="border:solid #CCC 1px; width:600px; height:20px; margin-bottom:10px; padding:5px">
                        <div>
                            <div>
                                <span class="tit">
                                    <?PHP echo $nomvar;?>:
                                </span>
                                <span class="nom">
                                    <?PHP echo $cantidad;?>
                                </span>
                            </div>
                        </div>
                    </div>
				<?PHP }?>
			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- ----------------------------------------------------- Postulaciones internas por estado ------------------------------------------------------------- -->
    	<div style="width:728px; margin:auto; height:auto;">
            <div style="float:left; width:220px">
            	<?PHP $titulo = 'Postulaciones internas por estado';?>
<!--                <h3><?PHP echo $titulo;?></h3>-->
                <br />
                <?PHP
                $sql = "SELECT est.nombre AS nomestado, bus.*,COUNT(bus.id) AS cant FROM empleos_stats_busq AS bus
							INNER JOIN empleos_estado AS est ON est.id = bus.estado
                                WHERE (DATE(bus.fecha) BETWEEN '$fechadesde' AND '$fechahasta') AND tipo = 1 
                                GROUP BY bus.estado
                                ORDER BY cant DESC";
                    //echo $sql;

                $grafico='<div class="row-latam">';
				$grafico.='	<table width="800px" border="0"><tr><td width="250px">';
				$totcant = 0;
				$cont = 0;
				$chart1 = "<img src='chart.php?data=";
				$chart2 = "";
				$grafico.= '<span style="font-size:14px;"><strong>'.$titulo.'</strong></span>:';
				$grafico.= "<br /><br />";
				
                $res = fullQuery($sql);
				$totcant = mysqli_num_rows($res);
                while($dato = mysqli_fetch_array($res)){
                    $cantidad = $dato['cant'];
					$nomvar = $dato['nomestado'];
                ?>
<!--                    <div style="border:solid #CCC 1px; width:200px; height:30px; margin-bottom:10px; padding:5px">
                        <div>
                            <div>
                                <span class="tit">
                                    <?PHP echo $nomvar;?>:
                                </span>
                                <span class="nom">
                                    <?PHP echo $cantidad;?>
                                </span>
                            </div>
                        </div>
                    </div>-->
					<?PHP
					// Si no es el primer resultado agrega el separador
					if ($cont > 0){
						$chart1 .= "*";
						$chart2 .= "*";
					}
					$chart1 .= $cantidad; //nro de veces
					$chart2 .= $nomvar; //nombre del campo
					$grafico.= $nomvar.": ".$cantidad."<br />";
					$cont++;
					?>
				<?PHP }?>
                <?PHP
				$chart = $chart1."&amp;label=".$chart2."' /><br /><br />";
				$grafico.='</td><td>'.$chart.'</td></tr></table>';
				$grafico.='</div>';
				if($totcant > 0){echo $grafico;}else{echo 'No hay resultados para el per&iacute;odo.';}
				?>				
			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- ----------------------------------------------------- Postulaciones referidos por estado ------------------------------------------------------------- -->
    	<div style="width:728px; margin:auto; height:auto;">
            <div style="float:left; width:220px">
            	<?PHP $titulo = 'Postulaciones referidos por estado';?>
<!--                <h3><?PHP echo $titulo;?></h3>-->
                <br />
                <?PHP
                $sql = "SELECT est.nombre AS nomestado, bus.*,COUNT(bus.id) AS cant FROM empleos_stats_busq AS bus
							INNER JOIN empleos_estado AS est ON est.id = bus.estado
                                WHERE (DATE(bus.fecha) BETWEEN '$fechadesde' AND '$fechahasta') AND tipo = 12 
                                GROUP BY bus.estado
                                ORDER BY cant DESC";
                    //echo $sql;

                $grafico='<div class="row-latam">';
				$grafico.='	<table width="800px" border="0"><tr><td width="250px">';
				$totcant = 0;
				$cont = 0;
				$chart1 = "<img src='chart.php?data=";
				$chart2 = "";
				$grafico.= '<span style="font-size:14px;"><strong>'.$titulo.'</strong></span>:';
				$grafico.= "<br /><br />";
				
                $res = fullQuery($sql);
				$totcant = mysqli_num_rows($res);
                while($dato = mysqli_fetch_array($res)){
                    $cantidad = $dato['cant'];
					$nomvar = $dato['nomestado'];
                ?>
<!--                    <div style="border:solid #CCC 1px; width:200px; height:30px; margin-bottom:10px; padding:5px">
                        <div>
                            <div>
                                <span class="tit">
                                    <?PHP echo $nomvar;?>:
                                </span>
                                <span class="nom">
                                    <?PHP echo $cantidad;?>
                                </span>
                            </div>
                        </div>
                    </div>-->
					<?PHP
					// Si no es el primer resultado agrega el separador
					if ($cont > 0){
						$chart1 .= "*";
						$chart2 .= "*";
					}
					$chart1 .= $cantidad; //nro de veces
					$chart2 .= $nomvar; //nombre del campo
					$grafico.= $nomvar.": ".$cantidad."<br />";
					$cont++;
					?>
				<?PHP }?>
                <?PHP
				$chart = $chart1."&amp;label=".$chart2."' /><br /><br />";
				$grafico.='</td><td>'.$chart.'</td></tr></table>';
				$grafico.='</div>';
				if($totcant > 0){echo $grafico;}else{echo 'No hay resultados para el per&iacute;odo.';}
				?>				
			</div>
			<div style="clear:both;"></div>
		</div>
<!-- ----------------------------------------------------- Postulaciones totales por estado ------------------------------------------------------------- -->
    	<div style="width:728px; margin:auto; height:auto;">
            <div style="float:left; width:220px">
            	<?PHP $titulo = 'Postulaciones totales por estado';?>
<!--                <h3><?PHP echo $titulo;?></h3>-->
                <br />
                <?PHP
                $sql = "SELECT est.nombre AS nomestado, bus.*,COUNT(bus.id) AS cant FROM empleos_stats_busq AS bus
							INNER JOIN empleos_estado AS est ON est.id = bus.estado
                                WHERE (DATE(bus.fecha) BETWEEN '$fechadesde' AND '$fechahasta') 
                                GROUP BY bus.estado
                                ORDER BY cant DESC";
                    //echo $sql;

                $grafico='<div class="row-latam">';
				$grafico.='	<table width="800px" border="0"><tr><td width="250px">';
				$totcant = 0;
				$cont = 0;
				$chart1 = "<img src='chart.php?data=";
				$chart2 = "";
				$grafico.= '<span style="font-size:14px;"><strong>'.$titulo.'</strong></span>:';
				$grafico.= "<br /><br />";
				
                $res = fullQuery($sql);
				$totcant = mysqli_num_rows($res);
                while($dato = mysqli_fetch_array($res)){
                    $cantidad = $dato['cant'];
					$nomvar = $dato['nomestado'];
                ?>
<!--                    <div style="border:solid #CCC 1px; width:200px; height:30px; margin-bottom:10px; padding:5px">
                        <div>
                            <div>
                                <span class="tit">
                                    <?PHP echo $nomvar;?>:
                                </span>
                                <span class="nom">
                                    <?PHP echo $cantidad;?>
                                </span>
                            </div>
                        </div>
                    </div>-->
					<?PHP
					// Si no es el primer resultado agrega el separador
					if ($cont > 0){
						$chart1 .= "*";
						$chart2 .= "*";
					}
					$chart1 .= $cantidad; //nro de veces
					$chart2 .= $nomvar; //nombre del campo
					$grafico.= $nomvar.": ".$cantidad."<br />";
					$cont++;
					?>
				<?PHP }?>
                <?PHP
				$chart = $chart1."&amp;label=".$chart2."' /><br /><br />";
				$grafico.='</td><td>'.$chart.'</td></tr></table>';
				$grafico.='</div>';
				if($totcant > 0){echo $grafico;}else{echo 'No hay resultados para el per&iacute;odo.';}
				?>				
			</div>
			<div style="clear:both;"></div>
		</div>
     </div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>