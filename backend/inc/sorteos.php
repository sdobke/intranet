<?PHP
$sortopc = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$sql_part = "SELECT concat(ie.nombre,' ',ie.apellido) AS nombre
					FROM ".$_SESSION['prefijo']."empleados AS ie
    					INNER JOIN ".$_SESSION['prefijo']."participantes AS ip ON (ie.id = ip.usuario)
					WHERE ip.tipoconcurso = {$tipo} AND ip.concurso =".$id;
$res_part = fullQuery($sql_part);
$num_part = mysqli_num_rows($res_part);
if ($tipoarchivo == 'detalles' and $num_part == 0)
echo '<div align="center"><h3>El concurso no tiene participantes</h3></div>';
if ($tipoarchivo == 'detalles' and $num_part >= 1) {
?>
        
    <form id="sortear" name="sortear" action="<?PHP echo 'detalles.php?tipo='.$tipo.'&id='.$id;?>" method="post">
		<input name="id" id="id" value="<?PHP echo $id;?>" type="hidden"/>
		<input name="opcion" id="opcion" value="S" type="hidden"/>
		<input name="tipo" id="tipo" value="<?PHP echo $tipo;?>" type="hidden"/>
		<input name="sortear" type="submit" id="sortear" value="Sortear" />
		<input name="sorteados" id="sorteados" value="1" size="5" maxlength="2" /> Ganadores (Hay <?PHP echo $num_part;?> participantes para este sorteo)
	</form>
	<br />
	<?PHP
	echo '<fieldset>
			<legend>Participantes</legend>';
		echo '<div style="margin:15px; font-size:10px">';
			while($row_part = mysqli_fetch_array($res_part)){
				//echo $row_part['nombre'].': '.$row_part['opcion'].'<br />';
				echo $row_part['nombre'].'<br />';
			}
		echo '</div>';
	echo '</fieldset>';
	if ($sortopc == 'S'){
		include("inc/sorteos_sortear.php");
	}
				
	if ($sortopc == 'M'){
		include("inc/sorteos_mail.php");
	}
}
?>