<?PHP
if (isset($_SESSION['usrfrontend'])) {
	$id_votante = $_SESSION['usrfrontend'];
	if (isset($_POST['voto']) || (isset($_POST['otra']) && $_POST['otra'] != '')) { // Si hubo un voto
		$encuesta = $_POST['encuesta_id'];
		if (isset($_POST['voto'])) {
			$sql_chk = "SELECT id FROM intranet_participantes WHERE tipoconcurso = " . $tipo . " AND concurso = " . $id . " AND usuario = " . $id_votante;
			$res_chk = fullQuery($sql_chk);
			$con_chk = mysqli_num_rows($res_chk);
			if ($con_chk == 0) {
				$voto = $_POST['voto'];
				$sql_voto = "SELECT votos FROM intranet_encuestas_opc WHERE id = " . $voto;
				$res_voto = fullQuery($sql_voto);
				$con_voto = mysqli_num_rows($res_voto);
				if ($con_voto == 1) {
					$row_voto = mysqli_fetch_assoc($res_voto);
					$nue_voto = $row_voto['votos'] + 1;
					$sql_voto = "UPDATE intranet_encuestas_opc SET votos = " . $nue_voto . " WHERE id = " . $voto;
					$res_voto = fullQuery($sql_voto);
				} else {
					$nueval = $_POST['otra'];
					$id_nueva  = nuevoID('encuestas_opc');
					$voto = $id_nueva;
					$sql_nuevo = "INSERT INTO intranet_encuestas_opc (id, encuesta, valor, votos, color, activo) VALUES (" . $id_nueva . ", " . $encuesta . ", '" . $nueval . "', 1, 'CCCCCC', 0)";
					$res_nuevo = fullQuery($sql_nuevo);
				}
				$sql_voto = "INSERT INTO intranet_participantes (tipoconcurso, concurso, usuario, votos, promedio, activo) VALUES (" . $tipo . ", $encuesta, $id_votante, 0, 0, 0)";
				$res_voto = fullQuery($sql_voto);

				$sql_voto = "INSERT INTO intranet_encuestas_votos (encuesta, opcion, empleado) VALUES (" . $encuesta . ", " . $voto . ", " . $id_votante . ")";
				$res_voto = fullQuery($sql_voto);
			}
		}
	}
}

$sql_query = "SELECT encu.* FROM intranet_encuestas AS encu
						INNER JOIN intranet_encuestas_opc AS enop ON enop.encuesta = encu.id
						WHERE encu.activo = 1 AND encu.id = " . $id;
$res_query = fullQuery($sql_query);
$con_query = mysqli_num_rows($res_query);
if ($con_query > 0) {
	$ver_res = 0;
	$ver_res_link = 1;
	$voto_si = 0;
	$orden_resul = ' ORDER BY id';
	$enc_row  = mysqli_fetch_assoc($res_query);
	$permitir_libre = $enc_row['permitir_libre'];
	$titulo   = txtcod($enc_row['titulo']);
	$pregunta = txtcod($enc_row['pregunta']);
	$id_enc   = $enc_row['id'];

	if (isset($id_votante)) { // Si el usuario está logueado
		$end_sql_bus = "SELECT id FROM intranet_participantes WHERE tipoconcurso = $tipo AND concurso = $id_enc AND usuario = $id_votante";
		$end_res_bus = fullQuery($end_sql_bus);
		$end_con_bus = mysqli_num_rows($end_res_bus);
		echo '<div align="center"><h3>' . $pregunta . "</h3></div>";
		if ($end_con_bus > 0 || isset($_POST['voto'])) { // Si ya había votado
			$ver_res = 1;
			$voto_si = 1;
		} else {
			if ($ver_res_link > 0) {
				$sql_encu = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = " . $id_enc . " AND activo = 1 " . $orden_resul;
				$res_encu = fullQuery($sql_encu);
				echo '<form action="nota.php?tipo=' . $tipo . '&amp;id=' . $id . '" method="post"><div id="photos"><table cellspacing="3" cellpadding="3">';
				while ($enc_row = mysqli_fetch_array($res_encu)) {
					$sql_encfot = "SELECT * FROM intranet_fotos WHERE tipo = $tipo AND item = " . $enc_row['id'];
					$res_encfot = fullQuery($sql_encfot);
					$con_encfot = mysqli_num_rows($res_encfot);
					if ($con_encfot == 1) {
						$row_encfot = mysqli_fetch_array($res_encfot);
						$opcion_foto = '<div class="photo"><a href="#foto' . $row_en['id'] . '"><img src="' . str_replace('imagen', 'thumb', $row_encfot['link']) . '" /></a></div>
											<div id="foto' . $row_en['id'] . '"><img src="' . $row_encfot['link'] . '" /></div>';
					} else {
						$opcion_foto = '';
					}
					$id_opc = $enc_row['id'];
					$nom_op = $enc_row['valor'];
					$sql_opn = "SELECT id FROM intranet_encuestas_opc ORDER BY id DESC";
					$res_opn = fullQuery($sql_opn);
					$row_opn = mysqli_fetch_assoc($res_opn);
					$id_opc_nueva = $row_opn['id'] + 1;
					echo '<tr>';
					echo '<td><input type="radio" name="voto" value="' . $id_opc . '" /></td><td>' . $opcion_foto . $nom_op . '</td>';
					echo '</tr>';
				}
				if($permitir_libre == 1){
					echo '<tr><td><input type="radio" name="voto" value="'.$id_opc_nueva.'" /></td><td><table><tr><td>Otra:</td><td><textarea name="otra" ></textarea></td></tr></table></td></tr>';
				}
				echo '</table><br />
				<input type="submit" class="btn btn-primary" value="Participar" />
				<input type="hidden" name="encuesta_id" value="' . $id_enc . '" />
				';
				echo '</div></form>';
			}
		}
	} else {
		$ver_res = 1;
	}
	if ($ver_res == 1 && $enc_row['mostrar_resultados'] == 1) { // mostramos los resultados
		$total = 0;
		echo '<div>';
		$devuelve = '<br /><div id="photos"><table>
				';
		// Primero los totales
		$sql_encu_voto = "SELECT SUM(votos) AS cantidad FROM intranet_encuestas_opc WHERE encuesta = " . $id_enc . " AND activo = 1";
		$res_encu_voto = fullQuery($sql_encu_voto);
		$row_encu_voto = mysqli_fetch_array($res_encu_voto);
		$total_votos   = $row_encu_voto['cantidad'];
		$sql_encu_voto = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = " . $id_enc . " AND activo = 1 " . $orden_resul;
		$res_encu_voto = fullQuery($sql_encu_voto);
		while ($enc_row_voto = mysqli_fetch_array($res_encu_voto)) {
			//$total = $total + $enc_row_voto['votos'];
			$sql_encfot = "SELECT * FROM intranet_fotos WHERE tipo = " . $tipo . " AND item = " . $enc_row_voto['id'];
			$res_encfot = fullQuery($sql_encfot);
			$con_encfot = mysqli_num_rows($res_encfot);
			if ($con_encfot == 1) {
				$row_encfot = mysqli_fetch_array($res_encfot);
				$opcion_foto = '<div class="photo"><a href="#foto' . $row_encfot['id'] . '"><img src="' . str_replace('imagen', 'thumb', $row_encfot['link']) . '" /></a></div>
											<div id="foto' . $row_encfot['id'] . '"><img src="' . $row_encfot['link'] . '" /></div>
											';
			} else {
				$opcion_foto = '';
			}
			$porcentaje = ($enc_row_voto['votos'] == 0) ? 0 : (int)(($enc_row_voto['votos'] / $total_votos) * 100);
			$devuelve .= '<tr><td align="left">' . $opcion_foto . $enc_row_voto['valor'] . ':</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $porcentaje . '% (' . $enc_row_voto['votos'] . ' respuestas)</td></tr>
					';
			$color = "#" . $enc_row_voto['color'];
			$largo = ($porcentaje < 100) ? $porcentaje * 2 : $porcentaje;
			$largo = $largo * 2;
			$devuelve .= '<tr><td colspan="2"><div style="background-color:' . $color . ';width:' . $largo . 'px;height:20px; margin-bottom:15px">&nbsp;</div></td></tr>
					';
		}

		$devuelve .= "</table></div>
			";
		echo $devuelve;
		echo "<br /><strong>Total de respuestas: " . $total_votos . "</strong>";
		echo '</div><br /><br />';
	}
	if ($voto_si == 1) {
		echo '<br /><div style="font-size:20px;" align="center">Tu respuesta ya est&aacute; registrada.';
		if (isset($nueval) && $nueval != '') {
			echo '<br />Vamos a activar tu respuesta luego de leerla.';
		}
		echo '</div>';
	}
} else {
	echo 'No existen encuestas activas. Volv&eacute; pronto.';
}
