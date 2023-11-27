<?PHP 
function com_int($emp){
	$query = fullQuery($sql = "SELECT * FROM intranet_com_int WHERE empresa = ".$emp." ORDER BY fecha DESC LIMIT ".cantidad('home_comun')) or die(mysqli_error());
	$textos = "";
    while ($comint = mysqli_fetch_array($query)) {
		$textos.= "<div class='fecha'>";
		if ($comint['fecha'] == date("Y-m-d")){$textos.= "Hoy";}else{$textos.= fechaDet($comint['fecha']);}
		$textos.= "</div><a href='comint/".emp_dir($emp)."/".$comint['id'].".doc'><span class='comint_tit'>&raquo;".$comint['titulo']."</span></a>";
	}
return $textos;
}?>
<?PHP $altura = 124;?>
<div id="caja-centro">
	    <div style="float:left; width:234px; border-bottom:1px solid #c3c3c3"><img src="../includes/img/hdcomint.gif" width="234" height="37" /></div>
		<div id="comint-sca" align="justify" style="height:<?PHP echo $altura;?>px">
        	<?PHP echo com_int("1");?>
            <div style="clear:both;"></div>
            <a href="../includes/comint.php?cod=1">Ver m�s...</a>
        </div>
		<div id="comint-burger" align="justify" style="height:<?PHP echo $altura;?>px">
        	<?PHP echo com_int("2");?>
  			<div style="clear:both;"></div>
        	<a href="../includes/comint.php?cod=2">Ver m�s...</a>
        </div>
		<div id="comint-starbucks" align="justify" style="height:<?PHP echo $altura;?>px">
			<?PHP echo com_int("3");?>
        	<div style="clear:both;"></div>
            <a href="../includes/comint.php?cod=3">Ver m�s...</a>
        </div>
</div>