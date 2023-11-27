<?PHP 
$activo = 0;
if(isset($_SESSION['actiform']) && $_SESSION['actiform'] > 0){
	$sqlch = "SELECT * FROM ".$_SESSION['prefijo']."encuesta_datos WHERE id = ".$_SESSION['actiform'];
	$resch = fullQuery($sqlch);
	$conch = mysqli_num_rows($resch);
	if($conch == 1){
		$activo = 1;
	}
}
if($activo == 0){
	$_SESSION['actiform'] = 0;
	$_SESSION['actinomb'] = _("No definido");
}
?>
<div class="menu-minisitio">
    <ul class="subnav ms-menu left">
        <li><a class="bot" href="encuestas.php">Encuestas</a></li>
        <li><a class="bot" href="nueva.php">Nueva Encuesta</a></li>
        <?PHP if(isset($_SESSION['actiform']) && $_SESSION['actiform'] > 0){?>
            <li><a class="bot" href="datos.php">T&iacute;tulo y Restricciones</a></li>
            <li><a class="bot" href="campos.php">Campos</a></li>
            <li><a class="bot" href="resultados.php">Resultados</a></li>
            <li><a class="bot" href="/backend/index.php">Volver al Backend</a></li>
        <?PHP } ?>
    </ul>
	<div class="clr"></div>
</div>
<div><br />
	Encuesta activa: <?PHP echo $_SESSION['actinomb'];?>
</div>