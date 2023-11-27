<?PHP
$sql_sorteos = "SELECT * FROM intranet_sorteos WHERE id = ".$id;
$res_sorteos = fullQuery($sql_sorteos);
$con_sorteos = mysqli_num_rows($res_sorteos);
$partic = 0; // 0 para no participa, 1 para ya participaba y 2 para acaba de anotarse
if($con_sorteos > 0){
	$sql_sort = "SELECT id FROM intranet_participantes WHERE tipoconcurso = ".$tipo." AND concurso = ".$id." AND usuario = ".$_SESSION['usrfrontend'];
	$res_sort = fullQuery($sql_sort);
	$con_sort = mysqli_num_rows($res_sort);
	if($con_sort == 1){
		$partic = 1;
	}
	if(isset($_POST['participa'])){ // si el usuario acaba de anotarse
		if($con_sort == 0){
			$nueid = nuevoID('participantes');
			$sql_sorteo_agr = "INSERT INTO intranet_participantes (id, tipoconcurso,concurso,usuario,votos,promedio,activo) VALUES (".$nueid.", ".$tipo.",".$id.",".$_SESSION['usrfrontend'].",'0','0.00','0')";
			//echo $sql_sorteo_agr;
			$res_sorteo_agr = fullQuery($sql_sorteo_agr);
			$partic = 2;
		}
	}
	if($partic == 0){
		echo '<div align="center">
			<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="sorteo">
				<button name="participar" class="btn btn-primary btn-lg"><i class="fa-solid fa-trophy"></i> Quiero Participar</button>
				<input type="hidden" name="tipo" value="'.$tipo.'" />
				<input type="hidden" name="id" value="'.$id.'" />
				<input type="hidden" name="participa" value="1" />
			</form></div>';
	}else{
		switch($partic){
			case 1:
				$txsor = 'Ya est&aacute;s participando de este sorteo.';
				break;
			case 2:
				$txsor = 'Gracias por participar de este sorteo.';
				break;
		}
		echo '<div class="success_box left mb15 mt15">'.$txsor.'</div>';
	}
}else{
	echo 'Existe un error ingresando a esta p&aacute;gina.';
}
echo '<br /><br />';
?>