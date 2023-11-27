<?PHP
echo '<div class="control-group">
		<label class="control-label" for="admins">Administraci&oacute;n de secciones: </label>';
        echo '<div class="controls">';
$sql_adms = "SELECT * FROM ".$_SESSION['prefijo']."tablas WHERE administrador = 1 ORDER BY detalle";
$res_adms = fullQuery($sql_adms);
$muestra_checks_adm = '';
while($row_adms = mysqli_fetch_array($res_adms)){
	$es_activo_adm = '';
	$query_adm = "SELECT * FROM ".$_SESSION['prefijo']."usr_adm WHERE tabla = ".$row_adms['id']." AND usuario = ".$id;
	$resul_adm = fullQuery($query_adm);
	$conta_adm = mysqli_num_rows($resul_adm);
	if($conta_adm > 0){
		$es_activo_adm = 'checked="checked"';
	}
	$muestra_checks_adm.= '<br /><input type="checkbox" id="admi_'.$row_adms['id'].'" name="admi_'.$row_adms['id'].'" '.$es_activo_adm.' /> '.txtcod($row_adms['detalle']).'
	';
}
echo $muestra_checks_adm;
echo '</div>
</div>
';
?>