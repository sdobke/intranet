<?PHP
if($usavotos == 1){ // si los votos est�n habilitados

	if(isset($_POST['voto'])){ // si acaba de votar
		$voto = $_POST['voto'];
		$id_votado = $_POST['id_votado'];
		$voto_usr = $_SESSION['usrfrontend'];
		$sqlchk = "SELECT id FROM intranet_votos WHERE tabla = ".$tipo." AND usuario = ".$voto_usr." AND item = ".$id_votado;
		$reschk = fullQuery($sqlchk);
		if(mysqli_num_rows($reschk) == 0){
			$sql_dar_voto = "INSERT INTO intranet_votos (voto, usuario, tabla, item) VALUES ($voto, $voto_usr, $tipo, $id_votado)";
			//echo '<br />'.$sql_dar_voto.'<br />';
			$res_dar_voto = fullQuery($sql_dar_voto);
			$sql_suma = "SELECT voto FROM intranet_votos WHERE item = ".$id_votado." AND tabla = ".$tipo; // buscamos todos los votos para este item
			//echo '<br />'.$sql_suma.'<br />';
			$res_suma = fullQuery($sql_suma);
			$sumatoria = 0;
			while($row_suma = mysqli_fetch_array($res_suma)){
			    $sumatoria = $sumatoria + $row_suma['voto']; // los sumamos
			}
			$sql_cambiar_votos = "SELECT votos FROM intranet_".$nombre." WHERE id = ".$id_votado; // calculamos total de votos
			//echo '<br />'.$sql_cambiar_votos.'<br />';
			$res_cambiar_votos = fullQuery($sql_cambiar_votos);
			$row_cambiar_votos = mysqli_fetch_array($res_cambiar_votos);
			$cant_votos_new = $row_cambiar_votos['votos']+1; // agregamos este voto
			$prom_votos_new = $sumatoria / $cant_votos_new; // sacamos el promedio
			// guardamos los datos
			$sql_guarda = "UPDATE intranet_".$nombre." SET votos = ".$cant_votos_new.", promedio = ".$prom_votos_new." WHERE id = ".$id_votado;
			//echo '<br />'.$sql_guarda.'<br />';
			$res_guarda = fullQuery($sql_guarda);
		}
	}

// Mostrar para votar

echo '<div class="brd-b mt15 pt5 pb15 mb15" style="height:40px">';
    if(isset($_SESSION['usrfrontend'])){// si el usuario est� logueado
        $idusr = $_SESSION['usrfrontend'];
        $sql_voto_usr = "SELECT usuario FROM intranet_votos WHERE tabla = ".$tipo." AND item = ".$noticia['id']." AND usuario = ".$idusr;
        $res_voto_usr = fullQuery($sql_voto_usr);
        $con_voto_usr = mysqli_num_rows($res_voto_usr);
        if($con_voto_usr == 0){ // si el usuario no vot� este �tem
$link_destino_query = (isset($link_destino)) ? $link_destino : $_SERVER['PHP_SELF'];
$queryget = '';	
$contget  = 1;
foreach ($_GET as $key => $value) {
    if ($key != "login") {  // ignora este valor
        $queryget .= ($contget == 1) ? '?' : '&';
        $queryget .= $key."=".$value;
    }
    $contget++;
}
$headerloc = $link_destino_query.$queryget;
$formloc   = $_SERVER['PHP_SELF'].$queryget;
echo '<div style="float:left">';
echo '<form id="votos" name="votos" method="post" action="'.$formloc.'">';
echo 'Darle puntaje a esta recomendaci&oacute;n: ';
echo '<select id="voto" name="voto" class="select">';
$contar_voto = 1;
while($contar_voto < 11){
    echo '<option value="'.$contar_voto.'">'.$contar_voto.'</option>';
    $contar_voto++;
}
	echo '</select>';
	echo '<input type="hidden" name="id_votado" id="id_votado" value="'.$noticia['id'].'" />';
echo '<button type="submit" name="button_'.$noticia['id'].'" id="button_'.$noticia['id'].'"><span class="icon icon44"></span><span class="labeled">Votar</span></button>';
echo '</form>';
echo '</div>';
        }else{
echo "<div style='float:left'><strong>Tu voto ya fu&eacute; registrado para esta recomendaci&oacute;n.</strong></div><div class='clr'></div>";
        }
    }else{
        echo "<div style='float:left'>Para votar esta recomendaci&oacute;n es necesario que ingreses con tu usuario.</div><div class='clr'></div>";
        //require_once("login.php");
    }
    // Se muestran los resultados
    $sql_votos = "SELECT * FROM intranet_".$nombre." WHERE id = ".$noticia['id'];
    $res_votos = fullQuery($sql_votos);
    $row_votos = mysqli_fetch_array($res_votos);
    echo '<div style="float:left; padding-top:7px">';
	if($noticia['votos'] > 0){
$cantvot = $row_votos['votos'];
$vtp = ($cantvot > 1) ? 's' : '';
$vtx = '&nbsp;&nbsp;&nbsp;Promedio: '.$row_votos['promedio'].' ('.$cantvot.' voto'.$vtp.')';
	}else{
$cantvot = 0;
$vtx = '&nbsp;&nbsp;&nbsp;Sin votos';
	}
	echo $vtx;
	echo '</div>';
echo '</div>';
}
?>