<?PHP 
	$query_col_mail = fullQuery("SELECT valor FROM ".$_SESSION['prefijo']."config where parametro = 'email'") or die(mysqli_error());
	$row_col_mail   = mysqli_fetch_array($query_col_mail);
	$email = $row_col_mail['valor'];
?>
<div class="sidebar left">
	<div class="bloque300-st-g left">
		<div class="row_menu_tit brd-b"><span class="arial t15">Empleos</span></div>
		<div class="row_menu brd-b"><span class="arial t15"><a href="programa_postulaciones_internas.php">Postulaciones Internas</a></span></div>
		<div class="row_menu brd-b"><span class="arial t15"><a href="programa_referidos.php">Programa de Referidos</a></span></div>
		<div class="row_menu brd-b"><span class="arial t15"><a href="novedades.php">Novedades</a></span></div>
	</div>
<!--	<div class="bloque300-st-g left t15" style="margin-top:5px">
		<div class="row_menu_tit brd-b"><span class="arial">Formaci&oacute;n</span></div>
		<div class="row_menu brd-b"><span class="arial t15"><a href="formacion.php">Plan Anual de Formaci&oacute;n</a></span></div>
	</div>-->
</div>