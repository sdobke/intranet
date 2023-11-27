<?PHP
$tabla_combo = 'minisitios';
echo '<div class="control-group">
		<label class="control-label" for="Minisitios">Admin Minisitios: </label>';
        echo '<div class="controls">';
$check_si = '';
$sql_minis = "SELECT * FROM ".$_SESSION['prefijo'].$tabla_combo;
$res_minis = fullQuery($sql_minis);
$muestra_checks = '';
while($row_minis = mysqli_fetch_array($res_minis)){
	$es_activo = '';
	$query_ma = "SELECT * FROM ".$_SESSION['prefijo']."minisitios_usuarios WHERE minisitio = ".$row_minis['id']." AND usuario = ".$id;
	$resul_ma = fullQuery($query_ma);
	$conta_ma = mysqli_num_rows($resul_ma);
	if($conta_ma > 0){
		$es_activo = 'checked="checked"';
	}
	$muestra_checks.= '<br /><input type="checkbox" id="mini_'.$row_minis['id'].'" name="mini_'.$row_minis['id'].'" '.$es_activo.' /> '.txtcod($row_minis['nombre']).'
	';
}
echo $muestra_checks;
echo '</div>
</div>
';
?>