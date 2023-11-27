<?php
$doc = explode("/", $_SERVER["PHP_SELF"]);
$doc = end($doc);
$doc = explode(".",$doc);
$doc = current($doc);

function itemMenuBack($link,$nombre){
	echo '<li><a href="'.$link.'.php"';
	global $doc;
	if ($doc == $link){
		echo 'class="current"';
	}
	if ($nombre == ''){
		$nombre = ucwords($link);
	}
	echo '>'.$nombre.'</a></li>';
}
?>
<div id="style-bckend">
      <ul>
		<li><a href="listado.php?tipo=1">Busquedas para empleados</a></li>
        <li><a href="listado.php?tipo=12">Busquedas para referidos</a></li>
        <li><a href="listado.php?tipo=9">Areas</a></li>
        <li><a href="listado.php?tipo=8">UEN</a></li>
        <li><a href="listado.php?tipo=11">Carreras</a></li>
        <!--<li><a href="listado.php?tipo=13">Acciones de Branding</a></li>-->
		<li><a href="estadisticas.php">Estad&iacute;sticas</a></li>        
      </ul>
</div>