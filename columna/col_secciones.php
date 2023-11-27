<?PHP
$tabsec = parametro('nombre', $tipo_col);
$titsec = parametro('detalle', $tipo_col);
$linkse = $tipo_col;
switch($tipo_col){
	case 15: // concurfotos
		$tabsec = 'concurfotos';
		$titsec = 'Concursos de fotos';
		break;
}
$linkseccion = 'seccion.php?tipo='.$linkse;
if($tipo_col == 11 && config('tipoenc') == 1){
	$linkseccion = 'encuesta_nueva.php';
}
$ltarg = '';
if($tipo_col == 20){
	$linkseccion = 'http://www.alseaalmaximo.com.ar';
	$ltarg = ' target="_blank"';
}
?>
<a href="<?PHP echo $linkseccion;?>"<?PHP echo $ltarg;?>>
	<div class="box_espacio <?PHP echo $colorbg;?> nettooffc mr5 t18 b mt5">
		<!--<div class="ic_<?PHP echo $tabsec;?>">&nbsp;</div>-->
		<div class="txt_espacio" style="float:left">
			<?PHP echo txtcod(ucwords($titsec));?>
		</div>
	</div>
</a>