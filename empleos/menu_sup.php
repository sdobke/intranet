<?PHP
$path = $_SERVER['PHP_SELF'];
$archivo = basename($path, ".php");
echo 'arch'.$archivo;
?>
<!--------------- MENU ----------------------->
<div id="caja-buscador">
	<div id="caja-menu-sup" align="center">
    	<?PHP itemMenu($archivo, 'novedades');?> | <?PHP itemMenu($archivo, 'galerias', 'Galer&iacute;as de fotos');?> | <?PHP itemMenu($archivo, 'rse', 'RSE');?> | <?PHP itemMenu($archivo, 'encuestas');?> | <?PHP itemMenu($archivo, 'bk', 'Bk Noticias');?> | <?PHP itemMenu($archivo, 'sugerencias', 'Buz&oacute;n de Sugerencias');?> | <?PHP itemMenu($archivo, 'clasificados');?> | <?PHP itemMenu($archivo, 'recomendados');?> | <?PHP itemMenu($archivo, 'concurfotos', 'Concurso de fotos');?> | <?PHP itemMenu($archivo, 'beneficios', 'Beneficios');?>
    </div>
    <br /><br />
<div align="right"><?PHP include "includes/clima.php";?></div>
<!-- Hora -->
<div id="liveclock" align="right"></div>
</div>