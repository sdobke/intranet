<?PHP 
$dif  = cantidad('diferencia');
$dif2 = cantidad('difepost');

function edad($edad){
	list($anio,$mes,$dia) = explode("-",$edad);
	$anio_dif = date("Y") - $anio;
	$mes_dif = date("m") - $mes;
	$dia_dif = date("d") - $dia;
	if ($dia_dif < 0 || $mes_dif < 0)
	$anio_dif--;
	return $anio_dif;
}
$fechahoy = date('Y-m-d');

$sqlcomun = ", CONCAT_WS('.', 100-MONTH(fechanac), 100-DAY(fechanac)) AS diames,
		CAST(CONCAT_WS('.', YEAR(fechanac), MONTH(fechanac), DAY(fechanac)) AS DATE),
        CAST(CONCAT_WS('.', 1980, MONTH(fechanac), DAY(fechanac)) AS DATE) + INTERVAL YEAR(SYSDATE()) - 1980 YEAR +
        INTERVAL CAST(CONCAT_WS('.', 1980, MONTH(fechanac), DAY(fechanac)) AS DATE) + INTERVAL YEAR(SYSDATE()) - 1980 YEAR < SYSDATE() YEAR AS nbd,
		DAYOFYEAR(NOW())-DAYOFYEAR(fechanac) AS diferir

		FROM intranet_empleados

		WHERE IF(DAYOFYEAR(fechanac) >= DAYOFYEAR(NOW())-".$dif2.",
		DAYOFYEAR(fechanac) - DAYOFYEAR(NOW()),
		DAYOFYEAR(fechanac) - DAYOFYEAR(NOW()) +
		DAYOFYEAR(CONCAT(YEAR(NOW()),'-12-31'))) < ".$dif." 
		AND fechanac != '1111-11-11'
";
$sqlcomun .= "ORDER BY diferir > 0,diames +0 DESC, nbd  ";

$sql = "SELECT nombre , fechanac ".$sqlcomun;

$result = fullQuery($sql);
$contar = mysqli_num_rows($result);

$sql = "SELECT id,nombre,apellido,cargo,empresa,area,email,interno,fechanac".$sqlcomun."limit ".cantidad('home_cumpl');

$result = fullQuery($sql);

    if($contar >= 1){
	
    $result = fullQuery($sql);
    while ($persona = mysqli_fetch_array($result)) {?>

		<div class="row-cumple">
			<div class="row-cumple-img"><a href="../includes/empleados.php?id=<?PHP echo $persona['id'];?>"><img src="/cliente/fotos/<?PHP if (file_exists("/cliente/fotos/".$persona['id'].".jpg")){echo $persona['id'];}else{echo "sinfoto";}?>.jpg" align="left" border="0"/></a></div>
			<div class="row-cumple-txt">
				<span class="fecha">
					<?PHP if ($persona['fechanac'] == date("Y-m-d")){ echo "Hoy"; }else{ echo FechaDet($persona['fechanac']);}?>
                </span>
                <br />
                <span class="nom"><?PHP if($persona['email']!=''){?><a href="mailto:<?PHP echo $persona['email'];?>" title="<?PHP echo $persona['email'];?>"><?PHP }?><?PHP echo txtcod($persona['apellido']).", ".txtcod($persona['nombre']);?><?PHP if($persona['email']!=''){?></a><?PHP }?></span>
                <br />
				<?PHP //echo edad($persona['fechanac']);?><!-- a�os<br />--><?PHP echo area($persona['area']);?> | <?PHP echo empresa($persona['empresa']);?>
                <br />
                <?PHP
                	if($persona['interno']!=''){?>                
		                Interno: <?PHP echo $persona['interno'];?>
                <?PHP }else{ echo "&nbsp;";}?>
			</div>
			<!--<div style="clear:both;"></div>-->
	 	</div>
<?PHP }?>
<?PHP if($contar > 6){
			if($contar <= 8){
				$wid=490;
				$hei=420;
			}else{
				$wid=508;
				$hei=404;
			}
			?>
   	<a href="javascript:;" onclick="MM_openBrWindow('../includes/cumples_popup.php','cumplepop','scrollbars=yes,width=<?php echo $wid;?>,height=<?php echo $hei;?>')">Ver m&aacute;s (<?PHP echo $contar;?>)</a>
<?PHP } ?>
<?PHP }else{?>

        NO HAY CUMPLEA&Ntilde;OS EN ESTE PER&Iacute;ODO

<?PHP }?>   