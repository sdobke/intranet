<?PHP
$lnkemp = (isset($cod) && $cod > 0) ? $cod : 1;
?>
<ul>
	<li><a href="sobre.php?cod=<?PHP echo $lnkemp;?>">Sobre <?PHP echo $cliente;?></a></li>
	<li><a href="mision.php?cod=<?PHP echo $lnkemp;?>">Misi&oacute;n, Visi&oacute;n y Valores</a></li>
	<li><a href="areas.php?cod=<?PHP echo $lnkemp;?>">&Aacute;reas</a></li>
</ul>