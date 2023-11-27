<?PHP
	require_once("../../cnfg/config.php");
	$orden_resul = ' ORDER BY id';
	$id_votante = $_SESSION['usrfrontend'];
	define('TIME_LIMIT', 86400); //Time (in sec) after a user can vote again from a IP
	//session_start();

	/*if (!isset($_SESSION['ip'])) {
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['start_time'] = time();
	}
	else {
		if ( ($_SESSION['start_time'] - time()) < TIME_LIMIT) {
			die("ip=".$_SESSION['ip']);
		}
	}*/
	
	// PROHIBE EL VOTO DE UN MISMO USUARIO
	
	/**
	 * Function to increase the team counter
	 *
	 * @param string $team
	 * 
	 * @return void
	 */
	if (isset($_POST['option'])) {
		// sumamos el voto
		$voto = $_POST['option'];
		$sql_voto = "SELECT votos,encuesta FROM intranet_encuestas_opc WHERE id = ".$voto;
		$res_voto = fullQuery($sql_voto);
		$row_voto = mysqli_fetch_array($res_voto);
		$nue_voto = $row_voto['votos']+1;
		$encuesta = $row_voto['encuesta'];
		
		$sql_nom  = "SELECT * FROM intranet_encuestas WHERE id = ".$encuesta;
		$res_nom  = fullQuery($sql_nom);
		$row_nom  = mysqli_fetch_array($res_nom);
		
		$sql_voto = "UPDATE intranet_encuestas_opc SET votos = ".$nue_voto." WHERE id = ".$voto;
		$res_voto = fullQuery($sql_voto);
		
		$sql_voto = "INSERT INTO intranet_participantes (tipoconcurso, concurso, usuario, votos, promedio, activo) VALUES (11, $encuesta, $id_votante, 0, 0, 0)";
		$res_voto = fullQuery($sql_voto);
		
		$sql_voto = "INSERT INTO intranet_encuestas_votos (encuesta, opcion, empleado) VALUES ($encuesta, $voto, $id_votante)";
		$res_voto = fullQuery($sql_voto);
		
		// mostramos los resultados
		$pregunta = utf8_decode(utf8_encode($row_nom['pregunta']));
		echo '<div style="font-size:10px;">';
			echo '<div style="font-size:10px; font-weight:bold;" align="center">'.$pregunta."</div>";
			$sql_encu_voto = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = ".$encuesta;
			$res_encu_voto = fullQuery($sql_encu_voto);
			$total = 0;
			while($enc_row_voto = mysqli_fetch_array($res_encu_voto)){
				$total = $total + $enc_row_voto['votos'];
			}
			$devuelve = "<br />";
			$sql_encu_voto = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = ".$encuesta.$orden_resul;
			$res_encu_voto = fullQuery($sql_encu_voto);
			while($enc_row_voto = mysqli_fetch_array($res_encu_voto)){
				$porcentaje = ($enc_row_voto['votos'] == 0) ? 0 :(int)(($enc_row_voto['votos']/$total)*100);
				$devuelve .= "<br />".$enc_row_voto['valor'].": ". $porcentaje ."% (".$enc_row_voto['votos'] ." respuestas)";
				$color = "#".$enc_row_voto['color'];
				$largo = ($porcentaje < 100) ? $porcentaje*2 : $porcentaje;
				$devuelve .= '&nbsp;<div style="background-color:'.$color.';width:'.$largo.'px;height:10px;">&nbsp;</div>';
			}
			echo $devuelve;
			echo "<br /><strong>Total de respuestas: ".$total."</strong>";
			echo '<br /><br /><br /><span style="color:red; font-size:13px;">&iexcl;Gracias por participar!</span>';
			$ver_res_link = 0;
		echo '</div>';
	}
	// para archivos
	
	/*
	function increaseCounter($team) {
		$score = file_get_contents('includes/pollresult/'.$team.'.txt') or die("Error: Cannot read $team file.");
		
		$score = $score +1;
		
		$fp = fopen($team.'.txt', 'w') or die("Error: Cannot open $team file.");
		fwrite($fp, $score) or die("Error: Cannot write to $team file.");
		fclose($fp);		
	}
	if (isset($_POST['option'])) {
		$vote = $_POST['option'];
		
		$brazil = file_get_contents('brazil.txt');
		$england = file_get_contents('england.txt');
		$france = file_get_contents('france.txt');
		$germany = file_get_contents('germany.txt');
		
		$total = ($brazil+$germany+$france+$england);
		
		$return = "<BR /><BR />";
		
		$return .= "&nbsp;&nbsp;Brazil: ". (int) (($brazil/$total)*100)."% <BR />";
		$return .= "&nbsp;&nbsp;England: ". (int) (($england/$total)*100)."% <BR />";
		$return .= "&nbsp;&nbsp;France: ". (int) (($france/$total)*100)."% <BR />";
		$return .= "&nbsp;&nbsp;Germany: ". (int) (($germany/$total)*100)."% <BR />";		
		
		$return .= "<BR /><BR />";
		echo $return;
	*/

	else {
		echo "Error interno.";
	}	
?>