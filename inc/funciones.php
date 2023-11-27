<?PHP
// ---------------
// Funciones de DB
// ---------------
function guardaLog($error, $file = "log.txt")
{
	$fp = fopen($file, "a+");
	fputs($fp, date("Y-m-d G:i:s") . ': ' . $error . '
	
	');
	fclose($fp);
}
function fullQuery($query)
{
	$resultado = mysqli_query($_SESSION['conexion'], $query);
	if (!$resultado) {
		$debug = debug_backtrace();
		$file = $debug[0]['file'];
		$error = 'Error de MySQL: "' . mysqli_error($_SESSION['conexion']) . '" en query:
		"' . $query . '"
		Archivo: ' . $file . ' line ' . $debug[0]['line'] . '
		';
		if (isset($debug[1]['file'])) {
			$error .= ' -> ' . $debug[1]['file'] . ' line ' . $debug[1]['line'];
		}
		if (isset($debug[2]['file'])) {
			$error .= ' -> ' . $debug[2]['file'] . ' line ' . $debug[2]['line'];
		}
		if (isset($debug[3]['file'])) {
			$error .= ' -> ' . $debug[3]['file'] . ' line ' . $debug[3]['line'];
		}
		guardaLog($error);
		echo '<br /><br />Existe un error. El administrador del sistema ya fue informado. Intente nuevamente m&aacute;s tarde. Gracias y disculpe las molestias ocasionadas. <a href="/index.php">Volver</a>';
		die;
	}
	return $resultado;
}
function nuevoID($tabla, $prefijo = '')
{
	if ($prefijo == '') {
		$prefijo = $_SESSION['prefijo'];
	}
	$sql = "SELECT id FROM " . $prefijo . $tabla . " ORDER BY id DESC LIMIT 1";
	//echo $sql;
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if ($con == 1) {
		$row = mysqli_fetch_assoc($res);
		$id  = $row['id'] + 1;
	} else {
		$id = 1;
	}
	return $id;
}
function obtenerDato($campo, $tabla, $id, $prefijo = '?', $tipoid = 'id')
{
	if ($prefijo == '?') {
		$prefijo = $_SESSION['prefijo'];
	}
	$devolver = '';
	if ($id != '') {
		$sql_nom = "SELECT " . $campo . " FROM " . $prefijo . $tabla . " WHERE " . $tipoid . " = " . $id;
		$res_nom = fullQuery($sql_nom, 'funcion obtenerDato en funciones.php');
		$con_nom = mysqli_num_rows($res_nom);
		if ($con_nom == 1) {
			$row_nom = mysqli_fetch_assoc($res_nom);
			if (strpos($campo, ',')) {
				$partes = explode(",", $campo);
				foreach ($partes as $key => $value) {
					$devolver .= " " . $row_nom[$value];
				}
			} else {
				$devolver = $row_nom[$campo];
			}
		}
	}
	return $devolver;
}
function obtenerNombre($tipo)
{
	return obtenerDato('nombre', 'tablas', $tipo);
}
// ------------------
// Funciones de texto
// ------------------
function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br>')
{
	mb_regex_encoding('UTF-8');
	//replace MS special characters first
	$search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u');
	$replace = array('\'', '\'', '"', '"', '-');
	$text = preg_replace($search, $replace, $text);
	//make sure _all_ html entities are converted to the plain ascii equivalents - it appears
	//in some MS headers, some html entities are encoded and some aren't
	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	//try to strip out any C style comments first, since these, embedded in html comments, seem to
	//prevent strip_tags from removing html comments (MS Word introduced combination)
	if (mb_stripos($text, '/*') !== FALSE) {
		$text = mb_eregi_replace('#/*.*?*/#s', '', $text, 'm');
	}
	//introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be
	//'<1' becomes '< 1'(note: somewhat application specific)
	$text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text);
	$text = strip_tags($text, $allowed_tags);
	//eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one
	$text = preg_replace(array('/^ss+/', '/ss+$/', '/ss+/u'), array('', '', ' '), $text);
	//strip out inline css and simplify style tags
	$search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
	$replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
	$text = preg_replace($search, $replace, $text);
	//on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears
	//that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains
	//some MS Style Definitions - this last bit gets rid of any leftover comments */
	$num_matches = preg_match_all("/<!--/u", $text, $matches);
	if ($num_matches) {
		$text = preg_replace('/<!--(.)*-->/isu', '', $text);
	}
	return $text;
}
function decode($texto)
{
	$original  = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", " ", "-", "�", "?");
	$reemplazo = array("a", "a", "e", "e", "i", "i", "o", "o", "u", "u", "n", "n", "_", "_", "", "");
	$devuelve  = strtolower(str_replace($original, $reemplazo, $texto));
}
function decoTxt($texto)
{
	//$texto = utf8_decode(utf8_encode($texto));
	$devuelve = decode($texto);
	$devuelve = str_replace('</p>', '</p><br />', utf8_encode($texto));
	//return $devuelve;
	return $texto;
}
function txtcod($texto, $backend = 0)
{ // Codifica o decodifica UTF-8
	$devolver = $texto;
	if (config('codificacion') == '1') { // utf8
		$devolver = utf8_encode($texto);
	}
	if ($backend == 0) {
		$ori = '--';
		$des = '&mdash;';
		$devolver = str_replace($ori, $des, $devolver);
	}
	return $devolver;
}
function txtdeco($texto)
{ // Codifica o decodifica UTF-8
	$devolver = $texto;
	if (config('codificacion') == '1') { // utf8
		$devolver = utf8_decode($texto);
	}
	$ori = '&mdash;';
	$des = '--';
	$devolver = str_replace($ori, $des, $devolver);
	return $devolver;
}
function acentos($texto)
{
	//if(config("codificacion") > 1){
	setlocale(LC_ALL, 'es_AR');
	$texto = iconv("ISO-8859-1", "ASCII//TRANSLIT", $texto);
	//}
	$orig  = array("Á", "É", "Í", "Ó", "Ú", "Ü", "Ñ", "á", "é", "í", "ó", "ú", "ü", "ñ", '"', ',', ':', ';', "'", ' ', '¿', '?', '!', '¡', '“', '”', chr(39), chr(34));
	$dest  = array("A", "E", "I", "O", "U", "U", "Ñ", "a", "e", "i", "o", "u", "u", "n", "", "", "", "", "", '-', '', '', '', '', '', '', '', '');
	$devuelve  = str_replace($orig, $dest, $texto);
	$devuelve  = str_replace($orig, $dest, $texto);
	return $devuelve;
}
function reemplazo_comillas($texto, $tipo = 0)
{
	$origen = array('`', '‘', '´', '“', '”', "'", "    ", "   ", "  ");
	$destin = array('&lsquo;', '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;', '&apos;', " ", " ", " ");
	//echo '<br>tip: '.$tipo;
	if ($tipo == 1) { // para SEO URL
		$origen = array('&lsquo', '&rsquo', '&ldquo', '&rdquo', '&apos', '&mdash;', '&mdash', '`', '‘', '´', '“', '”', "'", "    ", "   ", "  ");
		$destin = '';
	}
	$texto  = str_replace($origen, $destin, $texto);
	$nuevorigen = array('<a href=&quot;', '&quot;>');
	$nuevodesti = array('<a href="', '">');
	$texto  = str_replace($nuevorigen, $nuevodesti, $texto);
	return $texto;
}
function string_limit_words($string, $word_limit)
{
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}
function urlFriendly($texto)
{
	$dev = 0;
	if ($dev == 1) {
		echo '<br>orig: ' . $texto;
	}
	$texto = strtolower($texto);
	if ($dev == 1) {
		echo '<br>lower: ' . $texto;
	}
	$texto = acentos($texto);
	if ($dev == 1) {
		echo '<br>acentos: ' . $texto;
	}
	$texto = reemplazo_comillas($texto, 1);
	if ($dev == 1) {
		echo '<br>reemplazo_comillas: ' . $texto;
	}
	//$texto = htmlspecialchars($texto, ENT_COMPAT,'ISO-8859-1', true);
	//echo '<br>html: '.$texto;
	$texto = string_limit_words($texto, 9);
	if ($dev == 1) {
		echo '<br>string_limit_words: ' . $texto;
	}
	$texto = preg_replace("/[^0-9a-zA-Z\-]/", "", $texto);
	$texto = str_replace('--', '-', $texto);
	$texto = substr($texto, 0, 220);
	if ($dev == 1) {
		echo '<br>final: ' . $texto;
	}
	return $texto;
}
function cortarTexto($texto, $tamano, $puntos = "...")
{
	$texto = strip_tags($texto);
	if (strlen($texto) <= $tamano) return $texto;
	$body = explode(" ", $texto);
	$output = $body[0];
	$i = 1;
	while ((strlen($output) + strlen($body[$i]) + 1) <= $tamano and $body[$i]) {
		$output .= " " . $body[$i];
		$i++;
	}
	return $output . $puntos;
}
function cortarTxt($texto, $tamano, $puntos = "...")
{
	if (strlen($texto) > $tamano) {
		$texto = substr($texto, 0, $tamano) . $puntos;
	}
	return $texto;
}
function calculaEdad($fechanacimiento)
{
	list($ano, $mes, $dia) = explode("-", $fechanacimiento);
	$ano_diferencia  = date("Y") - $ano;
	$mes_diferencia = date("m") - $mes;
	$dia_diferencia   = date("d") - $dia;
	if ($dia_diferencia < 0 || $mes_diferencia < 0)
		$ano_diferencia--;
	return $ano_diferencia;
}
function FechaDet($fecha, $formato = 'largo')
{
	$dia = substr($fecha, 8, 2);
	$mes = substr($fecha, 5, 2);
	$anio = substr($fecha, 0, 4);
	if ($dia == '00' || $mes == '00' || $anio == '0000') {
		$devuelvo = 'Fecha Incorrect';
	} else {
		$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "S�bado");
		switch ($mes) {
			case 1:
				$nmes = "enero";
				break;
			case 2:
				$nmes = "febrero";
				break;
			case 3:
				$nmes = "marzo";
				break;
			case 4:
				$nmes = "abril";
				break;
			case 5:
				$nmes = "mayo";
				break;
			case 6:
				$nmes = "junio";
				break;
			case 7:
				$nmes = "julio";
				break;
			case 8:
				$nmes = "agosto";
				break;
			case 9:
				$nmes = "septiembre";
				break;
			case 10:
				$nmes = "octubre";
				break;
			case 11:
				$nmes = "noviembre";
				break;
			case 12:
				$nmes = "diciembre";
				break;
		}
		if ($formato == 'corto') {
			$nmes = substr($nmes, 0, 3);
		}
		$devuelvo = $dia . " de " . $nmes . " de " . $anio;
		if ($formato == 'my') {
			$devuelvo = $nmes . ' ' . $anio;
		}
		if ($formato == 'My') {
			$devuelvo = substr($nmes, 0, 3) . ' ' . $anio;
		}
		if ($formato == 'M-y') {
			$devuelvo = substr($nmes, 0, 3) . '<br />' . $anio;
		}
		if ($formato == 'M') {
			$devuelvo = substr($nmes, 0, 3);
		}
		if ($formato == 'dia') {
			$devuelvo = $dias[date('w')];
		}
		if ($formato == 'puntos') {
			$devuelvo = $dia . '.' . $mes . '.' . $anio;
		}
		if ($formato == 'diames') {
			$devuelvo = $dia . ' de ' . $nmes;
		}
		if ($formato == 'barras') {
			$devuelvo = $dia . '/' . $mes . '/' . $anio;
		}
	}
	return $devuelvo;
}
// ----------------------
// Funciones de Variables
// ----------------------
function getPost($VARiable, $default = 0, $tipo = 0, $numero = 1)
{
	if (strpos($VARiable, ';') == false) {
		if ($numero == 1) {
			if (isset($_GET[$VARiable]) && is_numeric($_GET[$VARiable])) {
				$devolver = $_GET[$VARiable];
			} elseif (isset($_POST[$VARiable])) {
				$devolver = $_POST[$VARiable];
			} else {
				$devolver = $default;
			}
		} else {
			if (isset($_GET[$VARiable])) {
				$devolver = $_GET[$VARiable];
			} elseif (isset($_POST[$VARiable])) {
				$devolver = $_POST[$VARiable];
			} else {
				$devolver = $default;
			}
		}
	} else {
		$devolver = $default;
	}
	if ($devolver == $default) { // Si no hay valor
		if ($default == 'ultimo' && $tipo > 0) {
			$nombretab = obtenerDato('nombre', 'tablas', $tipo);
			$devolver = nuevoID($nombretab);
		}
	}
	if (empty($devolver))
		$devolver = 0;
	return $devolver;
}
function config($parametro)
{
	$query = "SELECT valor FROM " . $_SESSION['prefijo'] . "config where parametro = '$parametro'";
	$resul = fullQuery($query);
	$conta = mysqli_num_rows($resul);
	if ($conta == 1) {
		$row = mysqli_fetch_array($resul);
		$dif = $row['valor'];
	} else {
		$dif = '';
	}
	return $dif;
}
function parametro($parametro, $tipo, $tabla = 'tablas')
{
	$sql = "SELECT " . $parametro . " FROM " . $_SESSION['prefijo'] . $tabla . " WHERE id = $tipo";
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if ($con == 1) {
		$row = mysqli_fetch_array($res);
		return $row[$parametro];
	} else {
		return 0;
	}
}
// -------------------------
// Funciones de Estadísticas
// -------------------------
function agrega_acceso($tipo)
{
	$usuario = (isset($_SESSION['usrfrontend'])) ? $_SESSION['usrfrontend'] : 0;
	$fecha = date("Y-m-d");
	$sql = "SELECT id FROM " . $_SESSION['prefijo'] . "accesos_detalle WHERE fecha = '$fecha' AND seccion = " . $tipo . " AND empleado = " . $usuario;
	//echo '<br />'.$sql;
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if ($con == 0) {
		$id = nuevoID('accesos_detalle');
		$sql = "INSERT INTO " . $_SESSION['prefijo'] . "accesos_detalle (id,fecha,accesos,seccion,empleado) VALUES ($id,'" . $fecha . "',1,$tipo,$usuario)";
		//echo '<br />'.$sql;
		$res = fullQuery($sql);
	} else {
		$row = mysqli_fetch_object($res);
		$sql = "UPDATE " . $_SESSION['prefijo'] . "accesos_detalle SET accesos = accesos+1 WHERE id = " . $row->id;
		//echo '<br />'.$sql;
		$res = fullQuery($sql);
	}
}
function agrega_acceso_nota($tipo, $item)
{
	$usuario = (isset($_SESSION['usrfrontend'])) ? $_SESSION['usrfrontend'] : 0;
	$sql = "SELECT id FROM " . $_SESSION['prefijo'] . "accesos_notas WHERE item = " . $item . " AND tipo = " . $tipo . " AND usuario = " . $usuario;
	$res = fullQuery($sql);
	if (mysqli_num_rows($res) == 0) {
		$sqli = "INSERT INTO " . $_SESSION['prefijo'] . "accesos_notas (tipo,item,usuario,fecha) VALUES (" . $tipo . "," . $item . "," . $usuario . ",'" . date("Y-m-d") . "')";
		fullQuery($sqli);
	}
}
function agrega_intento_acceso_nota($tipo, $item)
{
	$usuario = (isset($_SESSION['usrfrontend'])) ? $_SESSION['usrfrontend'] : 0;
	$sqli = "INSERT INTO " . $_SESSION['prefijo'] . "accesos_notas_intentos (tipo,item,usuario,fecha) VALUES (" . $tipo . "," . $item . "," . $usuario . ",'" . date("Y-m-d") . "')";
	fullQuery($sqli);
}
// ---------------------
// Funciones de archivos
// ---------------------
function hacerDir($dir)
{
	$partes = explode("/", $dir);
	$dir_acum = '';
	foreach ($partes as $key => $value) {
		$dir_parcial = $value;
		if ($dir_acum != '') {
			$dir_acum .= "/";
		}
		$dir_acum .= $dir_parcial;
		if (!is_dir($dir_acum . "/") && $dir_acum != '.' && $dir_acum != '..') {
			mkdir($dir_acum, 0777);
		}
	}
}
// ---------------------
// Funciones de imágenes
// ---------------------
function imagenPpal($id, $tipo, $dest = 2)
{
	$devolver = array();
	$sql_fotos = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = $id AND tipo = $tipo AND ppal = 1";
	$res_fotos = fullQuery($sql_fotos);
	$cont_foto_ppal = mysqli_num_rows($res_fotos);
	if ($cont_foto_ppal == 0) { // Si no existe una foto principal la crea
		if (fotoCrearPpal($id, $tipo) == 1) {
			$sql_fotos = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = $id AND tipo = $tipo AND ppal = 1";
			$res_fotos = fullQuery($sql_fotos);
			$cont_foto_ppal = 1;
		}
	}
	$foto_ppal = 'no';
	if ($cont_foto_ppal > 0) {
		$row_fotos = mysqli_fetch_array($res_fotos);
		$epigrafe = $row_fotos['epigrafe'];
		$lnk = explode("imagen", $row_fotos['link'], -1);
		switch ($dest) {
			case 0:
				$foto_ppal = $row_fotos['link'];
				break;
			case 1:
				$foto_ppal = end($lnk) . "nota.jpg";
				break;
			case 2:
				$foto_ppal = end($lnk) . "dest.jpg";
				break;
			case 3:
				$foto_ppal = end($lnk) . "sec.jpg";
				break;
			case 4:
				$foto_ppal = end($lnk) . "thumb.jpg";
				break;
		}
		$devolver['link'] = $foto_ppal;
		$devolver['epi'] = $epigrafe;
		$devolver['full'] = $row_fotos['link'];
		return $devolver;
	} else {
		return '0';
	}
}
function fotoCrearPpal($id, $tipo)
{
	$sql = "SELECT id FROM " . $_SESSION['prefijo'] . "fotos WHERE tipo = " . $tipo . " AND item = " . $id . " AND ppal = 1 LIMIT 1";
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if ($con == 1) {
		$row = mysqli_fetch_assoc($res);
		include_once("backend/inc/img.php");
		if (generaPpal($tipo, $id, 1, $row['id']) == 0) {
			$devo = 0;
		} else {
			$devo = 1;
		}
	} else {
		$sql = "SELECT id FROM " . $_SESSION['prefijo'] . "fotos WHERE tipo = " . $tipo . " AND item = " . $id . " LIMIT 1";
		$res = fullQuery($sql);
		$con = mysqli_num_rows($res);
		if ($con == 1) {
			include_once("backend/inc/img.php");
			if (generaPpal($tipo, $id, 1) == 0) {
				$devo = 0;
			} else {
				$devo = 1;
			}
		} else {
			$devo = 0;
		}
	}
	return $devo;
}
// -------------------
// Funciones del sitio
// -------------------
function ConfirmaEmp($dni)
{
	$sql = "SELECT * FROM " . $_SESSION['prefijo'] . "empleados WHERE dni = '" . $dni . "'";
	$res = fullQuery($sql);
	return mysqli_num_rows($res);
}
function optSel($var, $valor, $tipo = "0")
{
	$devolver = '';
	if (is_array($valor)) {
		if (in_array($var, $valor)) {
			$devolver = ($tipo == 0) ? 'selected="selected"' : 'checked="checked"';
		}
	} else {
		if ($var == $valor) {
			$devolver = ($tipo == 0) ? 'selected="selected"' : 'checked="checked"';
		}
	}
	return $devolver;
}
function paginador($limit, $contar, $pag, $variables)
{
	$devolver = '';
	//$btn_dbl_left = '<button class="btn btn-small btn-dark">$btn_dbl_left<i class="far fa-caret-square-left"></i></button>';
	$btn_left = '<i class="bi bi-chevron-left"></i>';

	//$btn_dbl_left_dis = '<button class="btn btn-small btn-secondary nolink">$btn_dbl_left_dis<i class="far fa-caret-square-left"></i></button>';
	$btn_dbl_left = '<i class="bi bi-chevron-double-left"></i>';
	//$btn_dbl_right = '<button class="btn btn-small btn-dark">btn_dbl_right<i class="far fa-caret-square-right"></i></button>';
	$btn_right = '<i class="bi bi-chevron-right"></i>';
	//$btn_dbl_right_dis = '<button class="btn btn-small btn-secondary nolink">btn_dbl_right_dis<i class="far fa-caret-square-right"></i></button>';
	$btn_dbl_right = '<i class="bi bi-chevron-double-right"></i>';
	$pag_ultima = 0;
	$pag_primera = 0;
	$pag_sig = 0;
	$pag_ant = 0;
	if ($limit == 0) {
		$totalpages = 1;
	} else {
		$totalpages = $contar / $limit;
	}
	$totalpages = ceil($totalpages);
	if ($totalpages == 0) {
		$totalpages = 1;
	}
	if ($pag == 1) {
		//$actualpage = '<strong>1</strong>';
		$actualpage = '<span class="page-link" href="#">1 de ' . $totalpages . '</span>';
	} else {
		//$actualpage = "<strong>" . $pag . "</strong>";
		$actualpage = '<span class="page-link" href="#">' . $pag . ' de ' . $totalpages . '</span>';
	}
	if ($pag < $totalpages) {
		$nv = $pag + 1;
		$pv = $pag - 1;
		//$pag_sig     = '<a href="?' . $variables . '&limit=' . $limit . '&pag=' . $nv . '"><button class="btn btn-small btn-dark">Siguiente $pag_sig<i class="fas fa-caret-square-right"></i></button></a>';
		$pag_sig     = '<li class="page-item"><a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=' . $nv . '">' . $btn_right . '</a> </li>';

		//$pag_ant     = '<a href="?' . $variables . '&limit=' . $limit . '&pag=' . $pv . '">' . '<button class="btn btn-small btn-dark">pag_ant<i class="fas fa-caret-square-left"></i> Anterior</button></a>';
		$pag_ant     = '<li class="page-item"><a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=' . $pv . '">' . $btn_left . '</a></li>';

		//$pag_primera = '<a href="?' . $variables . '&limit=' . $limit . '&pag=1">pag_primera' . $btn_dbl_left . ' </a>';
		$pag_primera = '<li class="page-item"> <a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=1">' . $btn_dbl_left . '</a> </li>';

		//$pag_ultima  = '<a href="?' . $variables . '&limit=' . $limit . '&pag=' . $totalpages . '"> pag_ultima ' . $btn_dbl_right . '</a>';
		$pag_ultima  = '<li class="page-item"><a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=' . $totalpages . '">' . $btn_dbl_right . '</a></li>';
	}
	if ($pag == '1') {
		$nv = $pag + 1;
		//$pag_sig     = '<a href="?' . $variables . '&limit=' . $limit . '&pag=' . $nv . '"><button class="btn btn-small btn-dark"> pag_sig1 Siguiente <i class="fas fa-caret-square-right"></i></button></a>';
		$pag_sig     = '<li class="page-item"><a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=' . $nv . '">' . $btn_right . '</a> </li>';
		//$pag_ant     = '<button class="btn btn-small btn-secondary nolink">pag_ant1 <i class="fas fa-caret-square-left"></i> Anterior</button>';
		$pag_ant     = '<li class="page-item disabled"><a class="page-link">' . $btn_left . '</a></li>';
		$pag_primera = '<li class="page-item disabled"> <a class="page-link">' . $btn_dbl_left . '</a> </li>';

		//$pag_ultima  = '<a href="?' . $variables . '&limit=' . $limit . '&pag=' . $totalpages . '"> pag_ultima1 ' . $btn_dbl_right . '</a>';
		$pag_ultima  = '<li class="page-item"><a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=' . $totalpages . '">' . $btn_dbl_right . '</a></li>';
	} elseif ($pag == $totalpages) {
		$pv = $pag - 1;
		//$pag_sig     = '<button class="btn btn-small btn-secondary nolink">Siguiente <i class="fas fa-caret-square-right"></i></button>';
		$pag_sig     = '<li class="page-item disabled"><a class="page-link">' . $btn_right . '</a> </li>';

		//$pag_ant     = '<a href="?' . $variables . '&limit=' . $limit . '&pag=' . $pv . '">' . '<button class="btn btn-small btn-dark"><i class="fas fa-caret-square-left"></i> Anterior</button> </a>';
		$pag_ant     = '<li class="page-item"><a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=' . $pv . '">' . $btn_left . '</a></li>';

		//$pag_primera = '<a href="?' . $variables . '&limit=' . $limit . '&pag=1">' . $btn_dbl_left . ' </a>';
		$pag_primera = '<li class="page-item"> <a class="page-link" href="?' . $variables . '&limit=' . $limit . '&pag=1">' . $btn_dbl_left . '</a> </li>';
		$pag_ultima  = '<li class="page-item disabled"><a class="page-link">' . $btn_dbl_right . '</a></li>';
	}
	if ($totalpages == '1' || $totalpages == '0') {
		//$pag_sig     = '<button class="btn btn-small btn-secondary nolink">Siguiente <i class="fas fa-caret-square-right"></i></button>';
		$pag_sig     = '<li class="page-item disabled"><a class="page-link">' . $btn_right . '</a> </li>';
		//$pag_ant     = '<button class="btn btn-small btn-secondary nolink"><i class="fas fa-caret-square-left"></i> Anterior</button>';
		$pag_ant     = '<li class="page-item disabled"><a class="page-link">' . $btn_left . '</a></li>';

		$pag_primera = '<li class="page-item disabled"> <a class="page-link">' . $btn_dbl_left . '</a> </li>';
		$pag_ultima  = '<li class="page-item disabled"><a class="page-link">' . $btn_dbl_right . '</a></li>';
	}
	$devolver .= '<nav aria-label="Page navigation">';
	$devolver .= '<ul class="pagination justify-content-center mt-5">';
	//$devolver .= '<span>';
	$devolver .= $pag_primera;
	//$devolver .= '</span> <span>';
	$devolver .= $pag_ant;
	$devolver .= '<li class="page-item active" aria-current="page">';
	$devolver .= $actualpage;
	//$devolver .= ' de ';
	//$devolver .= $totalpages;
	$devolver .= '</li>';
	$devolver .= $pag_sig;
	//$devolver .= '</span> <span>';
	$devolver .= $pag_ultima;
	//$devolver .= '</span>';
	$devolver .= '</ul>';
	$devolver .= '</nav>';
	return $devolver;
}
function verRestriccion($id_emp)
{
	/*
	$sql = "SELECT empresa, oficinas FROM intranet_empleados WHERE id = ".$id_emp;
	$res = fullQuery($sql);
	$row = mysqli_fetch_array($res);
	switch($row['empresa']){
		case 1: // alsea
			$valor = 1;
			break;
		case 2: // bk
			switch($row['oficinas']){
				case 1:
					$valor = 2;
					break;
				case 4:
					$valor = 4;
					break;
				case 6:
					$valor = 6;
					break;
			}
			break;
		case 3: // sbx
			switch($row['oficinas']){
				case 1:
					$valor = 3;
					break;
				case 5:
					$valor = 5;
					break;
				case 6:
					$valor = 7;
					break;
			}
			break;
	}
	return $valor;*/
	$sql = "SELECT oficinas FROM intranet_empleados WHERE id = " . $id_emp;
	$res = fullQuery($sql);
	$row = mysqli_fetch_assoc($res);
	return $row['oficinas'];
}
function empresa_cod($cod)
{
	$emp = strtolower(empresa($cod));
	return $emp;
}
function emp_dir($cod)
{
	$emp = str_replace(' ', '', empresa_cod($cod));
	return $emp;
}
function area($dato)
{
	if (is_numeric($dato)) {
		$sql = "SELECT * FROM intranet_areas where id = " . $dato;
		$res = fullQuery($sql);
		$con = mysqli_num_rows($res);
		if ($con > 0) {
			$row = mysqli_fetch_assoc($res);
			$resp = $row['nombre'];
		} else {
			$resp = 0;
		}
	} else {
		$resp = 0;
	}
	return $resp;
}
function sector($dato)
{
	if (is_numeric($dato)) {
		$sql = "SELECT * FROM intranet_sectores where id = " . $dato;
		$res = fullQuery($sql);
		$con = mysqli_num_rows($res);
		if ($con > 0) {
			$row = mysqli_fetch_assoc($res);
			$resp = $row['nombre'];
		} else {
			$resp = 0;
		}
	} else {
		$resp = 0;
	}
	return $resp;
}
function empresa($dato)
{
	if (is_numeric($dato)) {
		$sql = "SELECT * FROM intranet_empresas where id = " . $dato;
		$res = fullQuery($sql, "funciones.php | función empresa()");
		$con = mysqli_num_rows($res);
		if ($con > 0) {
			$row = mysqli_fetch_assoc($res);
			$resp = $row['nombre'];
		} else {
			$resp = 1;
		}
	} else {
		$resp = 1;
	}
	return $resp;
}
function preparaVideo($link)
{
	$dev = '';
	if (strpos($link, 'v=') !== false) { // Si es un link completo
		$vidlink = explode("v=", $link);
		$vidlink = end($vidlink);
	} else { // si es el comprimido
		//https://youtu.be/Vv-jFz1292I?t=1
		$vidlink = explode("youtu.be/", $link);
		$vidlink = end($vidlink);
	}
	if (strpos($vidlink, '&') !== false) {
		$vidlink = explode("&", $vidlink);
		$vidlink = $vidlink[0];
	}
	if (strpos($vidlink, '?') !== false) {
		$vidlink = explode("?", $vidlink);
		$vidlink = $vidlink[0];
	}
	$dev = '<div class="video-responsive"><iframe src="https://www.youtube.com/embed/' . $vidlink . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
	return $dev;
}
function get_string_between($string, $start, $end)
{
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) return '';
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}
function getContents($str, $startDelimiter, $endDelimiter)
{
	$contents = array();
	$startDelimiterLength = strlen($startDelimiter);
	$endDelimiterLength = strlen($endDelimiter);
	$startFrom = $contentStart = $contentEnd = 0;
	while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
		$contentStart += $startDelimiterLength;
		$contentEnd = strpos($str, $endDelimiter, $contentStart);
		if (false === $contentEnd) {
			break;
		}
		$contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
		$startFrom = $contentEnd + $endDelimiterLength;
	}
	return $contents;
}
function dbVarClean($vari)
{
	$dev = $vari;
	$prohibidos = array('&', 'update', 'delete', 'insert', 'alter');
	if (in_array(strtolower($vari), $prohibidos)) {
		$dev = '';
	}
	return $dev;
}
function optChild($tabla, $id, $selected, $nombre = "", $no_mostrar_hijos = 0, $query_base = '')
{
	$dev = '';
	$where_ocultar = ($no_mostrar_hijos == 0) ? " AND tabla.id <> " . $id : "";
	$sql = "SELECT * FROM " . $tabla . " as tabla WHERE parent = " . $id . " " . $where_ocultar . " AND del = 0 ORDER BY nombre";
	if ($query_base != '') {
		$sqlb = explode("|", $query_base);
		$sql = $sqlb[0] . " AND parent = " . $id . " " . $where_ocultar . " " . $sqlb[1];
		//$sql = str_replace(" AND parent = 0 ","",$sql);
	}
	//echo '<br>' . $sql;
	$res = fullQuery($sql);
	if (mysqli_num_rows($res) > 0) {
		while ($row = mysqli_fetch_array($res)) {
			$mostrar = $nombre . ' > ' . $row['nombre'];
			$dev .= '<option value="' . $row['id'] . '" ' . optSel($row['id'], $selected) . ' >' . txtcod($mostrar) . '</option>';
			$dev .= optChild($tabla, $row['id'], $selected, $mostrar, $no_mostrar_hijos, $query_base);
		}
	}
	return $dev;
}
function ChildList($tabla, $id, $nompar = '')
{
	$dev = array();
	$sql = "SELECT par.nombre AS nomparent, tab.* FROM " . $tabla . " tab 
						LEFT JOIN " . $tabla . " par ON tab.parent = par.id
							WHERE tab.parent = " . $id . " AND tab.del = 0
							ORDER BY tab.nombre";
	//echo $sql;
	$res = fullQuery($sql);
	if (mysqli_num_rows($res) > 0) {
		while ($row = mysqli_fetch_array($res)) {
			if (isset($row['nomparent'])) {
				$row['nombre'] = $row['nomparent'] . ' > ' . $row['nombre'];
			}
			if ($nompar != '') {
				$row['nombre'] = $nompar . ' > ' . $row['nombre'];
			}
			$dev[] = $row;
			/*
			echo '<br><pre>';
			print_r($row);
			echo '</pre>';
*/
			$new = ChildList($tabla, $row['id'], $row['nombre']);
			//echo '<br>base: '.$row['nombre'].'<br>';
			if (!empty($new)) {
				$dev[] = $new;
			}
		}
	}
	return $dev;
}
function deepValues(array $array)
{
	$values = array();
	foreach ($array as $level) {
		if (is_array($level) && !isset($level['id'])) {
			$values = array_merge($values, deepValues($level));
		} else {
			$values[] = $level;
		}
	}
	return $values;
}
function childBox($tabla_combo, $res_area, $areas_act, $level = 1, $accumulative = '')
{
	$dev = '';
	$sql_child = "SELECT * FROM " . $_SESSION['prefijo'] . $tabla_combo . " WHERE parent = " . $res_area . " AND del = 0 ORDER BY nombre";
	$res_child = fullQuery($sql_child);
	if (mysqli_num_rows($res_child) > 0) {
		//$cont_chesi = 0;
		$level++;
		while ($row_child = mysqli_fetch_array($res_child)) {
			$thisid = $row_child['id'];
			if (in_array($thisid, $areas_act)) {
				$es_activo = 'checked="checked"';
			} else {
				//$todos = 0;
				$es_activo = '';
			}
			$padleft = $level * 10;
			$accumulative = $accumulative . ' child_of_' . $res_area;
			$dev .= '<div class="child" style="padding-left:' . $padleft . 'px"><input class="' . $accumulative . '" type="checkbox" onclick="changeParent(' . $res_area . ');childrenAct(' . $thisid . ')" id="valor_' . $thisid . '" name="valor_' . $thisid . '" ' . $es_activo . ' /> ' . txtcod($row_child['nombre']) . '</div>';
			$dev .= childBox($tabla_combo, $thisid, $areas_act, $level, $accumulative);
		}
	} // Fin sub áreas
	return $dev;
}
function traerEmpleado($row, $multiemp, $campo_ani) // Para cumpleaños y aniversarios
{
	$mostrar = 1;
	$dev = '<div class="row g-1 user">';
	$file = "cliente/fotos/" . $row['id'] . ".jpg";
	$cump_link_foto = (file_exists($file)) ? $file : "/cliente/fotos/sinfoto.jpg";
	$cump_fecha = (substr($row[$campo_ani], 5, 5) == date("m-d")) ? "Hoy" : FechaDet($row[$campo_ani], 'diames');
	$cnom = explode(" ", $row['nombre']);
	$cnom = current($cnom);
	$cump_nombre = ucwords(txtcod(strtolower($row['apellido'] . ", " . $cnom)));
	$cump_email = $row['email'];
	$cump_nomail = '';
	if ($cump_email != '' && $cump_email != '-') {
		$cump_nomail .= '<a href="mailto:' . $row['email'] . '" title="' . $row['email'] . '">';
	}
	$cump_nomail .= $cump_nombre;
	if ($cump_email != '') {
		$cump_nomail .= '</a>';
	}
	//$cump_int = (is_numeric($row['interno']) &&  $row['interno'] > 0) ? '(Int. ' . $row['interno'] . ')' : '';
	$cump_int = ($row['interno'] != '' && $row['interno'] != '0') ? '<i class="fas fa-phone"></i> ' . $row['interno'] : '';
	$cump_lug = ($row['lugar'] != '') ? '<br>' . txtcod($row['lugar']) : '';
	$dev .= '
		<div class="col-4">
		';
	//<a href="empleados.php?id=' . $row['id'] . '">
	$dev .= '<img src="' . $cump_link_foto . '" alt="' . $cump_nombre . '" class="user_pic" />';
	//</a>
	$dev .= '</div>
		<div class="col-8">
			<span class="user_bday">' . $cump_fecha . '</span>
			<div class="user_name">' . $cump_nomail . '</div>
				<small>
			';
	if ($multiemp == 1) {
		$dev .= empresa($row['empresa']);
	}
	if ($campo_ani == 'fechaing') {
		$aniyears = calculaEdad($row[$campo_ani]);
		if ($aniyears > 0) {
			//echo '<br>Aniver: '.$row[$campo_ani].' fecha: '.date("m-d");
			$mesani = substr($row[$campo_ani], 5, 2);
			$diaani = substr($row[$campo_ani], 8, 2);
			if ($mesani > date("m") || ($mesani == 1 && date("m") == 12) || ($mesani == date("m") && $diaani > date("d"))) {
				$aniyears++;
			}
			$dev .= $aniyears . ' años | ';
		} else {
			$mostrar = 0;
		}
	}
	/*
	if (strpos($row['area'], ',') !== false) {
		$allareas = explode(',', $row['area']);
		$cont = 1;
		foreach ($allareas as $areaid) {
			$userarea = txtcod(obtenerDato('nombre', 'areas', $areaid));
			if ($userarea != '') {
				if ($cont > 1) {
					$dev .= ' | ';
				}
				$dev .= $userarea;
			}
			$cont++;
		}
	} else {
		$userarea = txtcod(obtenerDato('nombre', 'areas', $row['area']));
		if ($userarea != '') {
			$dev .= $userarea;
		}
	}
	*/
	$sqlarea = "SELECT ia.nombre FROM intranet_areas ia INNER JOIN intranet_empleados_areas iea ON iea.area = ia.id WHERE iea.empleado = " . $row['id'];
	//echo $sqlarea;
	$resarea = fullQuery($sqlarea);
	$userarea = '';
	if (mysqli_num_rows($resarea) > 0) {
		$contarea = 0;
		while ($rowarea = mysqli_fetch_array($resarea)) {
			if ($contarea > 0) {
				$userarea .= ', ';
			}
			$userarea .= txtcod($rowarea['nombre']);
			$contarea++;
		}
	}
	//echo '<br><br>'.$userarea;
	if ($userarea != '') {
		//echo '<br>Hay dato: '.$userarea;
		$dev .= $userarea;
	}
	$dev .= $cump_lug;
	$dev .= '<br>' . $cump_int;
	$dev .= '
	</small></div></div>';
	if ($mostrar == 0) {
		$dev = '';
	}
	return $dev;
}
function getAreaDocs($id)
{
	$dev = 0;
	$sqlarea = "SELECT ia.id, ia.nombre FROM intranet_areas ia INNER JOIN intranet_empleados_areas iea ON iea.area = ia.id WHERE iea.empleado = " . $id;
	//echo $sqlarea;
	$resarea = fullQuery($sqlarea);
	if (mysqli_num_rows($resarea) > 0) {
		while ($rowarea = mysqli_fetch_array($resarea)) {
			$arid = $rowarea['id'];
			$sqldoc = "SELECT * FROM intranet_docs WHERE area = " . $arid . " AND del = 0";
			//echo $sqldoc;
			$resdoc = fullQuery($sqldoc);
			if (mysqli_num_rows($resdoc) > 0) {
				$dev = $arid;
			}
		}
	}
	return $dev;
}
function debug($data, $show = 0)
{
	$display = ($show == 0) ? 'none' : 'block';
	echo '<div id="debug" style="display:' . $display . '">';
	if (is_array($data) || is_object($data)) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	} else {
		echo $data;
	}
	echo '</div>';
}
