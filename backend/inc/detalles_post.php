<?PHP
$usarcoma = 0;
$coma = '';
$backarch = 'detalles_post.php';

if ($tipotit != '' && !is_numeric($tipotit)) {
	$titulo   = txtdeco(reemplazo_comillas($_POST[$tipotit]));
	$qtit1    = $tipotit . " = '" . ($titulo) . "'";
	$usarcoma = 1;
} else {
	$qtit1    = '';
}

$coma = ($usarcoma == 1) ? ", " : "";
if ($usatexto == 1) {
	$texto    = txtdeco(reemplazo_comillas($_POST['texto']));
	$qtxt1    = $coma . "texto = '" . $texto . "'";
	$usarcoma = 1;
} else {
	$qtxt1    = '';
}

$coma = ($usarcoma == 1) ? ", " : "";
if ($usafecha > 0) {
	$fecdi    = substr($_POST['fecha'], 0, 2);
	$fecme    = substr($_POST['fecha'], 3, 2);
	$fecan    = substr($_POST['fecha'], 6, 4);
	$fecha    = $fecan . '-' . $fecme . '-' . $fecdi;
	$qfec1    = $coma . "fecha = '" . $fecha . "'";
	$usarcoma = 1;
} else {
	$qfec1    = '';
}
$coma = ($usarcoma == 1) ? ", " : "";
if ($usahora == 1) {
	$hora     = $_POST['hora'];
	$qhor1    = $coma . "hora = '" . $hora . "'";
	$usarcoma = 1;
} else {
	$qhor1    = '';
}
$coma = ($usarcoma == 1) ? ", " : "";
if ($usacolor == 1) {
	$color     = substr($_POST['color'], 1, 6);
	$qcol1     = $coma . "color = '" . $color . "'";
	$usarcoma = 1;
} else {
	$qcol1    = '';
}

$coma = ($usarcoma == 1) ? ", " : "";
if ($activable == 1) {
	$activo    = (isset($_POST['activo']) && $_POST['activo'] == 'on') ? 1 : 0;
	$qact1     = $coma . "activo = " . $activo;
	$usarcoma  = 1;
} else {
	$qact1     = '';
}

$coma = ($usarcoma == 1) ? ", " : "";

$qemloc = '';

if ($usarest == 1) { // restricciones

	if (isset($_POST)) {
		$link_areas = '';
		$contarea = 1;
		foreach ($_POST as $vari => $dato) {
			//echo '<br>Post: '.$vari;
			if (substr($vari, 0, 6) == 'valor_') {
				if ($contarea > 1) {
					$link_areas .= ',';
				}
				$link_areas .= substr($vari, 6, 10);
				$contarea++;
			}
		}
		//echo '<br>Datos: '.$link_areas;
		//if ($link_areas != '') {
		$sql_chk_link = "SELECT id FROM " . $_SESSION['prefijo'] . "link WHERE tipo = " . $tipo . " AND item = " . $id;
		$res_chk_link = fullQuery($sql_chk_link);
		if (mysqli_num_rows($res_chk_link) == 0) {
			$sql_ins_link = "INSERT INTO " . $_SESSION['prefijo'] . "link (tipo,item,part) VALUES (" . $tipo . ", " . $id . ", '" . $link_areas . "')";
			$res_ins_link = fullQuery($sql_ins_link);
		} else {
			$row_chk_link = mysqli_fetch_assoc($res_chk_link);
			$link_id = $row_chk_link['id'];
			$sql_ins_link = "UPDATE " . $_SESSION['prefijo'] . "link SET part = '" . $link_areas . "' WHERE id = " . $link_id;
			$res_ins_link = fullQuery($sql_ins_link);
		}
		//}
	}
}

// Combos
$qcombos = '';

$query_combos = "SELECT tab.nombre AS tabla, cbo.campo AS variable, cbo.multiple 
					FROM " . $_SESSION['prefijo'] . "combos AS cbo
					INNER JOIN " . $_SESSION['prefijo'] . "tablas AS tab ON (cbo.combo = tab.id)
					WHERE cbo.tabla = " . $tipo;
$resul_combos = fullQuery($query_combos, 'detalles_post.php');
while ($row_combos = mysqli_fetch_array($resul_combos)) {
	$var_combo    = $row_combos['variable'];
	if (isset($_POST[$var_combo])) {
		$post_combo = $_POST[$var_combo];
		if ($tipodet == 'empleados' && $var_combo == 'area') {
			$sqlar = "DELETE FROM " . $_SESSION['prefijo'] . "empleados_areas WHERE empleado = " . $id;
			fullQuery($sqlar);
			foreach ($post_combo as $areaemple) {
				$sqlar = "INSERT INTO " . $_SESSION['prefijo'] . "empleados_areas (area,empleado) VALUES (" . $areaemple . "," . $id . ")";
				fullQuery($sqlar);
			}
		} else {
			if ($row_combos['multiple'] == 1) {
				$post_combo = '"' . implode(',', $post_combo) . '"';
			}
			$qcombos .= ', ' . $var_combo . ' = ' . $post_combo;
		}
	}
}

$qfile = '';
if ($usalink == 1) {
	if (isset($_POST['url']) && $_POST['url'] != '') {
		$qlnk1 = ', peso, tipoarc';
		$pos = strpos($_POST['url'], 'iframe');
		if ($pos !== false) { // Si tiene iframe, se lo quitamos
			$url_final = explode('"', $_POST['url']);
			$_POST['url'] = $url_final[1];
		}
		$ext = urlExt($_POST['url']);
		$qlnk2    = ", 0, '" . $ext . "'";
	} elseif (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
		$carptip   = $tipodet;
		$sqldoc    = "SELECT * FROM intranet_docs WHERE id = " . $id . " AND del = 0";
		$resdoc    = fullQuery($sqldoc);
		$condoc    = mysqli_num_rows($resdoc);
		if ($condoc == 1) { // Si hay uno en la DB
			$rowdoc = mysqli_fetch_assoc($resdoc);
			$docant = $rowdoc['link'];
			if (file_exists('../' . $docant)) { // si existe el archivo
				$empcod   = $_POST['empresa'];
				$empdir   = emp_dir($empcod);
				$docext   = arch_ext($_FILES['archivo']['name']);
				$peso     = filesize($_FILES['archivo']['tmp_name']);
				$nomarc   = urlFriendly(nomArch($titulo));
				$nuevolnk = "cliente/" . $carptip . "/" . $empdir . "/" . $id . "_" . $nomarc . "." . $docext;
				if (move_uploaded_file($_FILES['archivo']['tmp_name'], "../" . $nuevolnk)) {
					$qfile = ", link = '" . $nuevolnk . "', peso = '" . $peso . "', tipoarc = '" . $docext . "' ";
					unlink('../' . $docant); // borramos el anterior
				}
			}
		}
	}
}

// Vars
$qvars = '';

$query_vars = "SELECT * FROM " . $_SESSION['prefijo'] . "vars WHERE tabla = " . $tipo;
$resul_vars = fullQuery($query_vars);
while ($row_vars = mysqli_fetch_array($resul_vars)) {
	$var_var = $row_vars['variable'];
	$vtipo   = $row_vars['tipo'];
	$valor_vars = $_POST[$var_var];
	if ($tipodet == "galerias" && $var_var == 'home' && $valor_vars == 1) { // Si es galería de fotos
		$sql_home = "UPDATE " . $_SESSION['prefijo'] . "galerias SET home = 0";
		$res_home = fullQuery($sql_home, 'detalles_post.php');
	}

	if ($vtipo == 5) { // Fechas
		if (strpos($valor_vars, '/') !== false) { // Si usa "/"
			$fecin = explode('/', $valor_vars);
			$vardi    = $fecin[0];
			$varme    = $fecin[1];
			$varan    = $fecin[2];
		} else {
			$vardi    = substr($valor_vars, 0, 2);
			$varme    = substr($valor_vars, 2, 2);
			$cantanio = (strlen($valor_vars) == 6) ? 2 : 4;
			$varan    = substr($valor_vars, 4, $cantanio);
			if ($cantanio == 2) {
				$varan = ($varan > substr(date("Y"), 2, 2)) ? "19" . $varan : "20" . $varan;
			}
		}
		$valor_vars = $varan . '-' . $varme . '-' . $vardi;
	}
	if ($vtipo == 1 && ($valor_vars == '' || $valor_vars == 0)) {
		$valor_vars = 0;
	}
	if ($var_var == 'seourl') {
		if ($tipodet == "revista") { //Edimp
			$valor_vars = ($valor_vars == '') ? urlFriendly($_POST['titulo']) : urlFriendly($valor_vars);
		} else {
			$valor_vars = ($valor_vars == '') ? urlFriendly($_POST[$tipotit]) : urlFriendly($valor_vars);
		}
		// Confirmamos que no exista
		$seochk = "SELECT seourl FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE seourl = '{$valor_vars}' AND id != " . $id;
		$reschk = fullQuery($seochk);
		$conchk = mysqli_num_rows($reschk);
		if ($conchk == 1) { // Si ya existe
			$seocnt = 1;
			$seoseguir = 1;
			while ($seocnt < 1000 && $seoseguir == 1) { // Le agregamos números hasta encontrar uno que no exista
				$seochk2 = "SELECT seourl FROM " . $_SESSION['prefijo'] . $nombretab . " WHERE seourl = '{$valor_vars}_{$seocnt}'";
				$reschk2 = fullQuery($seochk2);
				$conchk2 = mysqli_num_rows($reschk2);
				if ($conchk2 == 0) {
					$valor_vars = $valor_vars . '_' . $seocnt;
					$seoseguir = 0;
				}
				$seocnt++;
			}
		}
	}
	$valor_pass_orig = '';
	if ($vtipo == 4) {
		$valor_pass_orig = $valor_vars;
		$valor_vars = hash('sha512', $valor_vars);
	}
	/*
	if($var_var == 'password'){
		echo '<br>tipo: '.$tipodet.' | var:'.$var_var.' | post:'.$_POST['password'].' | Pass ingresado '.$valor_pass_orig.' | hash: '.$valor_vars.' | guardado: '.$noticia['password'].'<br><br>';
	}
	*/
	if ($tipodet == 'empleados' && $var_var == 'password' && isset($noticia['password']) && $noticia['password'] == $valor_pass_orig) { // Empleados si es empleados y la password es igual, no la toca
	} else {
		$qvars .= ($vtipo == 1 || $vtipo == 3) ? ', ' . $var_var . ' = ' . $valor_vars : ', ' . $var_var . ' = "' . txtdeco(reemplazo_comillas($valor_vars)) . '"';
	}
}

if ($anidable == 1) {
	$qvars .= ', parent = ' . $_POST['parent'];
}

if ($tipo == 3 || $tipo == 7 || $tipo == 20) {
	if (isset($_POST['tipoalerta'])) {

		if ($_POST['tipoalerta'] == 2) { // Si es diferida
			// Luego borramos alertas para este ítem
			$sql_aldel = "DELETE FROM intranet_alertas 
				WHERE tipo = " . $tipo . " AND item = " . $id;
			$res_aldel = fullQuery($sql_aldel);
			//$alermin = $_POST['alertamin'];
			$alermin = '00';
			$alerthor = $_POST['alertahora'] . ':' . $alermin . ':00';
			$sql_al = "INSERT INTO intranet_alertas (fecha, hora, tipo, item) VALUES ('" . $_POST['alertafecha'] . "','" . $alerthor . "'," . $tipo . "," . $id . ")";
			//echo $sql_al;
			$res_al = fullQuery($sql_al);
		}
	}
}

$id       = $_POST['id'];
$error    = '';

$query  = "UPDATE " . $_SESSION['prefijo'] . $nombretab . " SET " . $qtit1 . $qtxt1 . $qfec1 . $qhor1 . $qcombos . $qvars . $qcol1 . $qact1 . $qemloc . $qfile . " WHERE id = $id";

//echo $query;
fullQuery($query);

$timestamp = date("Y-m-d");
$anio      = substr($timestamp, 0, 4);
$mes       = substr($timestamp, 5, 2);
$dia       = substr($timestamp, 8, 2);

if ($usafotos == 1 && $tipo <> 4) {
	// Imagenes
	$link_img = '';
	$generar_ppal = 0;

	// Fotos
	$query = "SELECT id,link FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo;
	$resul = fullQuery($query, 'detalles_post.php');
	$conta = mysqli_num_rows($resul);

	if ($conta > 0) { // Si ya existían fotos con su carpeta
		$row   	  = mysqli_fetch_array($resul);
		$link_fot = $row['link'];
		$link_fot_1 = explode("imagen", $link_fot, -1);
		$carpeta  = end($link_fot_1);
	}
	if(!file_exists("../".$carpeta)){
		$carpeta  = "cliente/fotos/" . $anio . "/" . $mes . "/" . $dia . "/" . $id . "-" . $tipo . "/";
		hacerDir("../cliente/fotos/" . $anio . "/" . $mes . "/" . $dia . "/" . $id . "-" . $tipo);
	}
	$contador = 1;
	$contnuevas = 0;
	$carp = 'tempimg';
	while ($contador < 200) {
		$car_fil = $carp . '/'. $tipo.'_'. $id . '_' . $contador . '.jpg';
		if (file_exists($car_fil)) {
			$id_fot = nuevoID("fotos");
			$link   = $carpeta . "imagen_" . $id_fot . ".jpg";
			// ancho y alto límite de las fotos
			$fotow  = config('fotow');
			$fotoh  = config('fotoh'); // ancho y alto límite de las fotos
			//$epi = $_POST['epi_'.$contador];
			$epi = '';
			subirImagen($car_fil, $link, $fotow, $fotoh, $tipo, $id, $epi);
			if(file_exists($car_fil)){unlink($car_fil);}
			$contnuevas++;
		}
		$contador++;
	}

	// Fotos - Ppal
	if ($conta > 0 || $contador > 1) { // Si había fotos guardadas o si se agregaron ahora
		if (isset($_POST['ppal'])) { // Foto Principal
			$ppal = $_POST['ppal'];
			if ($ppal > 0) { // Si hay una principal seleccionada
				// Primero vemos si la principal es la misma
				$sql_ppal = "SELECT id FROM " . $_SESSION['prefijo'] . "fotos WHERE ppal = 1 AND id = " . $ppal . " AND tipo = " . $tipo;
				$res_ppal = fullQuery($sql_ppal);
				$con_ppal = mysqli_num_rows($res_ppal);
				if ($con_ppal == 0) { // Si no es la misma
					$query  = "UPDATE " . $_SESSION['prefijo'] . "fotos SET ppal = 1 WHERE id = " . $ppal;
					$resul  = fullQuery($query, 'detalles_post.php');
					$generar_ppal = 2; // Con el ID
				} elseif ($_POST['generada'] == 1) { // Si se regeneró la misma
					$generar_ppal = 1; // La genera de nuevo.
				}
			} else { // Si no hay principal seleccionada seteamos como 0
				$query  = "UPDATE " . $_SESSION['prefijo'] . "fotos SET ppal = 0 WHERE item = " . $id . " AND tipo = " . $tipo;
				$resul  = fullQuery($query);
				$generar_ppal = 1;
			}
		} else {
			$generar_ppal = 1;
		}
	}

	// Fotos - Epigrafes
	$query = "SELECT id,link FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo;
	$resul = fullQuery($query);
	while ($row = mysqli_fetch_array($resul)) {
		$img_id = $row['id'];
		$epig = (isset($_POST['epig_' . $img_id])) ? $_POST['epig_' . $img_id] : '';
		$query_epi = "UPDATE " . $_SESSION['prefijo'] . "fotos SET epigrafe = '" . reemplazo_comillas($epig) . "' WHERE id = " . $img_id;
		$res_epi = fullQuery($query_epi, 'detalles_post.php');
	}

	// Fotos - Borrar
	$query = "SELECT id,link FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo;
	$resul = fullQuery($query, 'detalles_post.php');
	while ($row = mysqli_fetch_array($resul)) {
		$img_id = $row['id'];
		$link_img = $row['link'];
		$base_link = explode('imagen', $link_img);
		$base_link = current($base_link);
		if (isset($_POST['borrar' . $img_id])) {
			$query_borra = "DELETE FROM " . $_SESSION['prefijo'] . "fotos WHERE id = " . $img_id;
			$resul_borra = fullQuery($query_borra);
			$lk_th = '../' . $base_link . 'img_' . $img_id . '_thumb.jpg';
			if (file_exists($lk_th)) {
				unlink($lk_th);
			}
			$lk_th = '../' . $base_link . 'img_' . $img_id . '_thumb_bk.jpg';
			if (file_exists($lk_th)) {
				unlink($lk_th);
			}
			if ($ppal == $img_id) { // Si se borró la que era principal
				$generar_ppal = 1;
			}
			unlink("../" . $link_img);
		}
	}

	// Fotos - Borrar destacadas si no quedan fotos

	$query = "SELECT id FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $id . " AND tipo = " . $tipo;
	$resul = fullQuery($query, 'detalles_post.php');
	$conta = mysqli_num_rows($resul);
	if ($conta == 0 && isset($link_img) && $link_img != '') {
		borrarDestacadas($link_img, 0, 0, 1);
		$generar_ppal = 0;
	}

	// Generar ppales
	if ($generar_ppal > 0) {
		if ($generar_ppal == 1) {
			borrarDestacadas(0, $id, $tipo, 1);
			generaPpal($tipo, $id);
		} elseif ($generar_ppal == 2) {
			generaPpal($tipo, $id, 1, $ppal);
		} else {
			creaThumbs($tipo, $id, 180, 180, 1); // Crea thumbnails de backend
		}
	}
	if ($contnuevas > 0) {
		creaThumbs($tipo, $id); // Crea thumbnails
		creaThumbs($tipo, $id, 180, 180, 1); // Crea thumbnails de backend
	}
}

if ($tipodet == 'empleados') { // Empleados
	$contador = 1;
	$carp = 'tempimg';
	while ($contador < 2) {
		$car_fil = $carp . '/' . $tipo . '_' . $id . '_' . $contador . '.jpg';
		$car_des = '../cliente/fotos/';
		$car_des .= $id;
		$car_des .= '.jpg';
		if (file_exists($car_fil)) {
			$epi = '';
			subirImagenSinDB($car_fil, $car_des, 300, 300, 0);
			unlink($car_fil);
		}
		$contador++;
	}
	// Usuarios admins
	$sql_usrad = "SELECT id FROM " . $_SESSION['prefijo'] . "tablas WHERE administrador = 1";
	$res_usrad = fullQuery($sql_usrad);
	while ($row_usrad = mysqli_fetch_array($res_usrad)) {
		$tipo_usrad = $row_usrad['id'];
		if (isset($_POST['admi_' . $tipo_usrad])) { // si esa sección fue seleccionada
			$post_usrad = $_POST['admi_' . $tipo_usrad];
			$sql_usrad2 = "SELECT * FROM " . $_SESSION['prefijo'] . "usr_adm WHERE tabla = " . $tipo_usrad . " AND usuario = " . $id;
			$res_usrad2 = fullQuery($sql_usrad2, 'detalles_post.php');
			$con_usrad2 = mysqli_num_rows($res_usrad2);
			if ($con_usrad2 == 0) { // Si no está insertamos la opción
				$idadm = nuevoID("usr_adm");
				$sql_ins_usrad2 = "INSERT INTO " . $_SESSION['prefijo'] . "usr_adm (id, tabla, usuario) VALUES (" . $idadm . "," . $tipo_usrad . ", " . $id . ")";
				$res_ins_usrad2 = fullQuery($sql_ins_usrad2, 'detalles_post.php | insert admin usuario');
			}
		} else { // Si el valor no fue seleccionado
			// Borramos de la DB
			$sql_delm = "DELETE FROM " . $_SESSION['prefijo'] . "usr_adm WHERE tabla = " . $tipo_usrad . " AND usuario = " . $id;
			$res_delm = fullQuery($sql_delm, 'detalles_post.php | borrar usuario admin');
		}
	}
	// Minisitios admins
	$sql_minem = "SELECT id FROM " . $_SESSION['prefijo'] . "minisitios";
	$res_minem = fullQuery($sql_minem);
	while ($row_minem = mysqli_fetch_array($res_minem)) {
		$tipo_minem = $row_minem['id'];
		if (isset($_POST['mini_' . $tipo_minem])) { // si ese minisitio fue seleccionado
			$post_minem = $_POST['mini_' . $tipo_minem];
			$sql_minem2 = "SELECT * FROM " . $_SESSION['prefijo'] . "minisitios_usuarios WHERE minisitio = " . $tipo_minem . " AND usuario = " . $id;
			$res_minem2 = fullQuery($sql_minem2, 'detalles_post.php');
			$con_minem2 = mysqli_num_rows($res_minem2);
			if ($con_minem2 == 0) { // Si no está insertamos la opción
				$idmu = nuevoID("minisitios_usuarios");
				$sql_ins_minem2 = "INSERT INTO " . $_SESSION['prefijo'] . "minisitios_usuarios (id, minisitio, usuario) VALUES (" . $idmu . ", " . $tipo_minem . ", " . $id . ")";
				$res_ins_minem2 = fullQuery($sql_ins_minem2, 'detalles_post.php | insert minisitios usuario');
			}
		} else { // Si el valor no fue seleccionado
			// Borramos de la DB
			$sql_delm = "DELETE FROM " . $_SESSION['prefijo'] . "minisitios_usuarios WHERE minisitio = " . $tipo_minem . " AND usuario = " . $id;
			$res_delm = fullQuery($sql_delm, 'detalles_post.php | borrar usuario minisitio');
		}
	}
	// Set empresa
	$emple_emp = $_POST['empresa'];
	$sqlemp = "UPDATE " . $_SESSION['prefijo'] . "empleados SET empresa = " . $emple_emp . " WHERE id = " . $id;
	$resemp = fullQuery($sqlemp, 'detalles_post.php | asignar empresa a empleado');
}

if ($usavideos == 1) {
	function estaURL()
	{
		$pageURL = 'http';
		//if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		$pageURL = explode('/backend', $pageURL);
		$pageURL = current($pageURL);
		return $pageURL;
	}
	$hayvideos = 0;

	// Videos - Agregar
	$query = "SELECT * FROM " . $_SESSION['prefijo'] . "videos WHERE item = " . $id . " AND tipo = " . $tipo . " AND del = 0";
	$resul = fullQuery($query, 'detalles_post.php');
	$conta = mysqli_num_rows($resul);

	if ($conta > 0) {
		$hayvideos = 1;
		while ($row = mysqli_fetch_array($resul)) {
			$link_vid = $row['link'];
			$id_vid   = $row['id'];
			$link_vid = explode("video_", $link_vid, -1);
			$carpeta  = end($link_vid);
			$hayvideos = 1;
			if (isset($_POST['videpi' . $id_vid]) && $_POST['videpi' . $id_vid] != '') {
				$epi = reemplazo_comillas($_POST['videpi' . $id_vid]);
				$sqlup = "UPDATE " . $_SESSION['prefijo'] . "videos SET epigrafe = '" . $epi . "' WHERE id = " . $id_vid;
				$resup = fullQuery($sqlup);
			} else {
				$epi = 'Video ID ' . $id_vid;
			}
		}
	} else {
		hacerDir("../cliente/videos/" . $anio . "/" . $mes . "/" . $dia . "/" . $id . "-" . $tipo);
		$carpeta  = "cliente/videos/" . $anio . "/" . $mes . "/" . $dia . "/" . $id . "-" . $tipo . "/";
	}

	// Sube los Videos
	$contador = 1;
	$carp = 'tempvid';
	while ($contador < 200) {
		// Para buscar otros formatos que no sean MP4, recorrer archivos sin su extensión y leerla.
		$car_fil = $carp . '/' . $id . '_' . $contador . '.mp4';
		if (file_exists($car_fil)) {
			$hayvideos = 1;
			$id_vid = nuevoID("videos");
			$link   = $carpeta . "video_" . $id_vid . ".mp4";
			copy($car_fil, '../' . $link);
			unlink($car_fil);
			$epi = 'Video ID ' . $id_vid;
			$query2  = "INSERT INTO " . $_SESSION['prefijo'] . "videos (id, tipo, item, ppal, link, epigrafe, usuario) VALUES (" . $id_vid . "," . $tipo . "," . $id . ",0,'" . $link . "','" . $epi . "',0)";
			fullQuery($query2);
		}
		$contador++;
	}
	// Videos - Borrado

	$query = "SELECT * FROM " . $_SESSION['prefijo'] . "videos WHERE item = " . $id . " AND tipo = " . $tipo;
	$resul = fullQuery($query, 'detalles_post.php');
	while ($row = mysqli_fetch_array($resul)) {
		$img_id = $row['id'];
		$titvid = $row['epigrafe'];
		$link_img = $row['link'];
		$base_link = explode('video_', $link_img);
		$base_link = current($base_link);
		if (isset($_POST['video_' . $img_id])) {
			$query_borra = "DELETE FROM " . $_SESSION['prefijo'] . "videos WHERE id = " . $img_id;
			$resul_borra = fullQuery($query_borra);
			$lk_th = '../' . $base_link . 'video_' . $img_id . '.mp4';
			if (file_exists($lk_th)) {
				unlink($lk_th);
			}
		}
	}

	$query = "SELECT id FROM " . $_SESSION['prefijo'] . "videos WHERE item = " . $id . " AND tipo = " . $tipo;
	$resul = fullQuery($query, 'detalles_post.php');
	$conta = mysqli_num_rows($resul);

	// Videos - Armar XML
	$link_xml = '../' . $carpeta . $id . '.xml';
	if (file_exists($link_xml)) {
		unlink($link_xml);
	}
	if ($conta > 0) {
		$datosxml = '<?xml version="1.0" encoding="UTF-8"?>
							<playlist version="1" xmlns="http://xspf.org/ns/0/">
							   <trackList>';
		$filev = fopen($link_xml, "w+");
		$query = "SELECT * FROM " . $_SESSION['prefijo'] . "videos WHERE item = " . $id . " AND tipo = " . $tipo . " AND del = 0 ORDER BY ppal DESC";
		$resul = fullQuery($query);
		while ($row = mysqli_fetch_array($resul)) {
			$link_xml = $row['link'];
			//$datosxml.= '<track>
			// <title>Video</title>
			//  <location>http://www.youtube.com/watch?v='.$link_xml.'</location>
			//	 <image>http://www.movieposter.com/posters/archive/main/9/A70-4902</image>
			//  </track>';
			$datosxml .= '<track>
				 <title>' . $row['epigrafe'] . '</title>
				 <location>' . estaURL() . '/' . $link_xml . '</location>
			  </track>';
		}
		$datosxml .= '</trackList>
						</playlist>
						';
		fwrite($filev, $datosxml);
		fclose($filev);
	}
}

if ($tipodet == "revista") { // Edición Impresa
	// Tapita
	$carpeta  = "../" . config('carp_edimp') . "/";
	if (is_uploaded_file($_FILES['pdf']['tmp_name'])) {
		$url_link   = $carpeta . "revista_" . $id . ".pdf";
		move_uploaded_file($_FILES['pdf']['tmp_name'], $url_link);
	}
	if (is_uploaded_file($_FILES['imagenbk']['tmp_name'])) {
		$url_link  = $carpeta . "imagen_" . $id . ".jpg";
		$fotow  = cantidad('fotow');
		$fotoh  = cantidad('fotoh'); // ancho y alto límite de las fotos
		subirImagenSinDB('imagenbk', $url_link, $fotow, $fotoh);
	}
}

if ($tipodet == 'encuestas') { // Encuestas

	// Campos - Borrar
	$query = "SELECT id FROM " . $_SESSION['prefijo'] . "encuestas_opc WHERE encuesta = " . $id;
	$resul = fullQuery($query, 'detalles_post.php');
	while ($row = mysqli_fetch_array($resul)) {
		$enc_id = $row['id'];
		if (isset($_POST['borrar' . $enc_id])) {
			$sql_fot = "SELECT fotos.link FROM " . $_SESSION['prefijo'] . "fotos WHERE tipo = " . $tipo . " AND item = " . $enc_id;
			$res_fot = fullQuery($sql_fot);
			while ($row_fot = mysqli_fetch_array($res_fot)) {
				unlink("../" . str_replace('imagen', 'thumb', $row_fot['link']));
				unlink("../" . $row_fot['link']);
			}
			$sql_fot = "DELETE FROM " . $_SESSION['prefijo'] . "fotos WHERE tipo = " . $tipo . " AND item = " . $enc_id;
			$res_fot = fullQuery($sql_fot, 'detalles_post.php');
			$query_borra = "DELETE FROM " . $_SESSION['prefijo'] . "encuestas_opc WHERE id = " . $enc_id;
			$resul_borra = fullQuery($query_borra);
			$query_borra = "DELETE FROM " . $_SESSION['prefijo'] . "encuestas_votos WHERE encuesta = " . $id . " AND opcion = " . $enc_id;
			$resul_borra = fullQuery($query_borra);
		}
		$sql_activos = "SELECT id FROM " . $_SESSION['prefijo'] . "encuestas_opc WHERE encuesta = " . $id;
		$res_activos = fullQuery($sql_activos);
		while ($row_activos = mysqli_fetch_array($res_activos)) {
			$id_act = $row_activos['id'];
			$valor_act = (isset($_POST['activo' . $id_act])) ? 1 : 0;
			$sql_act_mod = "UPDATE " . $_SESSION['prefijo'] . "encuestas_opc SET activo = " . $valor_act . " WHERE id = " . $id_act;
			$res_act_mod = fullQuery($sql_act_mod, 'detalles_post.php');
		}
	}
	// Campos - Modificar
	$sql_opc = "SELECT id, valor FROM " . $_SESSION['prefijo'] . "encuestas_opc WHERE encuesta = " . $id;
	$res_opc = fullQuery($sql_opc, 'detalles_post.php');
	while ($row_opc = mysqli_fetch_array($res_opc)) {
		$id_opc    = $row_opc['id'];
		$item_opc  = $row_opc['valor'];
		$item_post = $_POST['opcion_' . $id_opc];
		$item_color = substr($_POST['color_' . $id_opc], 1, 6);
		$sql_cambio = "UPDATE " . $_SESSION['prefijo'] . "encuestas_opc SET valor = '$item_post', color = '$item_color' WHERE id = $id_opc";
		$res_cambio = fullQuery($sql_cambio, 'detalles_post.php');
	}
	// Agregar campos
	$contador = 1;
	while ($contador < 21) {
		if (isset($_POST['opcion' . $contador]) && $_POST['opcion' . $contador] != '') {
			$opc_valor = $_POST['opcion' . $contador];
			$id_opc = nuevoID("encuestas_opc");
			$col_opc = substr($_POST['color' . $contador], 1, 6);
			$sql_opc = "INSERT INTO " . $_SESSION['prefijo'] . "encuestas_opc (id, encuesta, valor, votos, color) VALUES ($id_opc, $id, '$opc_valor', 0, '$col_opc')";
			$res_opc = fullQuery($sql_opc, 'detalles_post.php');
			// Imagen
			/*
			if (is_uploaded_file($_FILES['imagen'.$contador]['tmp_name'])){
				$hayfotos = 1;
				$id_fot = nuevoID("fotos");
				$link   = $carpeta."imagen_".$id_fot.".jpg";
				// ancho y alto l�mite de las fotos
				$fotow  = config('fotow');
				$fotoh  = config('fotoh');
				subirImagen('imagen'.$contador, $link, $fotow, $fotoh, $tipo, $id_opc);
				$thumbh = config('thumbh');
				creaThumbs($tipo, $id_opc, 0, $thumbh);
			}*/
		}
		$contador++;
	}
}
if ($tipodet == "concurfotos" && isset($_POST['mod_activos'])) { // Si es Concursos
	$id_nombres_mod = explode("-", $_POST['mod_activos']);
	foreach ($id_nombres_mod as $key => $value) {
		$id_item = $value;
		if ($id_item > 0) {
			$sql = "UPDATE " . $_SESSION['prefijo'] . "participantes SET activo = " . $_POST['activo' . $id_item] . " WHERE id = $id_item";
			$res = fullQuery($sql, 'detalles_post.php');
		}
	}
}

if ($tipodet == "areas") { // Areas
	if ($multiemp == 1) {
		$sqlarem = "DELETE FROM " . $_SESSION['prefijo'] . "areas_emp WHERE area = " . $id;
		$resarem = fullQuery($sqlarem, $backarch);
		$sql_emp = "SELECT id FROM " . $_SESSION['prefijo'] . "empresas ORDER BY id";
		$res_emp = fullQuery($sql_emp, $backarch);
		while ($row_emp = mysqli_fetch_array($res_emp)) {
			$idemp = $row_emp['id'];
			if (isset($_POST['emp' . $idemp])) {
				$sae = "INSERT INTO " . $_SESSION['prefijo'] . "areas_emp (area,empresa) VALUES (" . $id . ", " . $idemp . ")";
				$rae = fullQuery($sae, $backarch);
			}
		}
	}
}

if ($error == '') {
	$msg = 0;
} else {
	$msg = 1;
}
if ($tipodet == 'novedades') { // Si son novedades
	$sql_cache = "UPDATE " . $_SESSION['prefijo'] . "config SET valor = '1' WHERE parametro = 'cache'";
	$res_cache = fullQuery($sql_cache, 'detalles_post.php');
}
