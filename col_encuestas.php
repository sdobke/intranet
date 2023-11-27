<?PHP
require_once('inc/encuestas/AjaxPoll.inc.php');
//$orden_resul = ' ORDER BY votos DESC, id';
$orden_resul = ' ORDER BY id';
// variables del $_GET
$voto_si = 0;
// Encuesta
$tipo_col = 11;
$nombre_tab = obtenerNombre($tipo_col);
//$sql_encu = "SELECT * FROM intranet_encuestas WHERE activa = 1 AND ".$otro_query;
$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp']: 0;
$sql_encu = " SELECT inov.id AS id, inov.activo AS activo, inov.titulo AS titulo, inov.pregunta AS pregunta FROM intranet_" . $nombre_tab . " AS inov 
					INNER JOIN intranet_link AS il ON inov.id = il.item 
						WHERE il.tipo = " . $tipo_col . " AND (il.part = " . $restriccion . " OR il.part = 0)  AND activo = 1
				";
$res_encu = fullQuery($sql_encu);
$cont_enc = mysqli_num_rows($res_encu);
define('TEMPLATE_FILE', 'includes/encuestas/template.html');
if ($cont_enc > 0) {
	while ($enc_row = mysqli_fetch_array($res_encu)) {
		?>
		<div class="bloque300-st mt15 right">
			<div class="hd_accesos nettooffc b t14">Encuestas</div>
			<div class="row_accesos" >
				<?PHP
				
				$titulo = utf8_decode(utf8_encode($enc_row['titulo']));
				$pregunta = utf8_decode(utf8_encode($enc_row['pregunta']));
				$id_enc = $enc_row['id'];
				echo '<div class="mod-b-titulo" ><strong>' . $titulo . '</strong></div>';
				if (isset($_SESSION['usrfrontend'])) {
					$id_votante = $_SESSION['usrfrontend'];
					$end_sql_bus = "SELECT id FROM intranet_participantes WHERE tipoconcurso = 11 AND concurso = $id_enc AND usuario = $id_votante";
					//echo $end_sql_bus;
					$end_res_bus = fullQuery($end_sql_bus);
					$end_con_bus = mysqli_num_rows($end_res_bus);
					$ver_res = 0; //defino esta variable en 0 por que está indefinida
					if ($end_con_bus <> 0) { // Si ya hab�a votado
						$ver_res = 1;
						$voto_si = 1;
					} else {
						if ($ver_res == 0) {
							$ajaxPoll = new AjaxPoll(TEMPLATE_FILE, $id_enc);
							$sql_encu = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = " . $id_enc . $orden_resul;
							$res_encu = fullQuery($sql_encu);
							while ($enc_row = mysqli_fetch_array($res_encu)) {
								$valores[] = $enc_row['valor'];
							}
							$ajaxPoll->tag('options', $valores);
							$ajaxPoll->tag('question', $pregunta);
							echo $ajaxPoll->write();
							if (isset($_POST['votado'])) {
								echo '<br /><a href="' . $_SERVER['PHP_SELF'] . '?ver_res=1"><div style="font-size:10px; font-weight:bold;" align="center">Ver resultados</div></a>';
							}
						}
					}
				} else { // SI NO ESTA LOGUEADO
					$ver_res = 1;
				}
				if ($ver_res == 1) {
					// mostramos los resultados
					echo '<div style="font-size:10px;">';
					echo '<div style="font-size:10px; font-weight:bold;" align="center">' . $pregunta . "</div>";
					$sql_encu_voto = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = " . $id_enc . $orden_resul;
					$res_encu_voto = fullQuery($sql_encu_voto);
					$total = 0;
					while ($enc_row_voto = mysqli_fetch_array($res_encu_voto)) {
						$total = $total + $enc_row_voto['votos'];
					}
					$devuelve = "<br />";
					$sql_encu_voto = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = " . $id_enc . $orden_resul;
					$res_encu_voto = fullQuery($sql_encu_voto);
					while ($enc_row_voto = mysqli_fetch_array($res_encu_voto)) {
						$porcentaje = ($enc_row_voto['votos'] == 0) ? 0 : (int) (($enc_row_voto['votos'] / $total) * 100);
						$devuelve .= "<br />" . $enc_row_voto['valor'] . ": " . $porcentaje . "% (" . $enc_row_voto['votos'] . " respuestas)";
						$color = "#" . $enc_row_voto['color'];
						$largo = ($porcentaje < 100) ? $porcentaje * 2 : $porcentaje;
						$devuelve .= '&nbsp;<div style="background-color:' . $color . ';width:' . $largo . 'px;height:10px;">&nbsp;</div>';
					}
					echo $devuelve;
					echo "<br /><strong>Total de respuestas: " . $total . "</strong>";
					if (isset($_SESSION['usrfrontend'])) {
						if ($voto_si == 0) {
							echo '<br /><br /><a href="' . $_SERVER['PHP_SELF'] . '?ver_enc=1"><span style="font-size:10px; font-weight:bold;">Ver encuesta</span></a>';
						}
					} else {
						$popup = "MM_openBrWindow('login_popup.php?formloc=" . $_SERVER['PHP_SELF'] . "', 'loginpop', 'scrollbars=yes,width=550,height=140')";
						echo '<br /><br /><span class="link-login"><a href="javascript:;" onclick="' . $popup . '">Para participar ingres&aacute; con tu usuario.</a></span>';
						//echo '</div>';					
					}
					if ($voto_si == 1) {
						echo '<br /><br /><span style="color:red; font-size:13px;">Tu respuesta ya est&aacute; registrada.</span>';
					}
					echo '</div>';
				}
				?>
			</div>
		</div>
	<?PHP } ?>
<?PHP } ?>