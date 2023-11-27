<?PHP
$id = nuevoID($nombretab);
$continuar = 1;
$error    = '';
$backarch = "alta.php";

if($tipotit != '' && !is_numeric($tipotit) ){
	$titulo   = txtdeco(reemplazo_comillas($_POST[$tipotit]));
	$qtit1    = ', '.$tipotit;
	$qtit2    = ", '".$titulo."'";
}else{
	$qtit1    = '';
	$qtit2    = "";
}

if($usatexto == 1){
	$texto    = txtdeco(reemplazo_comillas($_POST['texto']));
	$qtxt1    = ', texto';
	$qtxt2    = ", '".$texto."'";
}else{
	$qtxt1    = '';
	$qtxt2    = "";
}

if($usafecha > 0){
	$fecdi    = substr($_POST['fecha'],0,2);
	$fecme    = substr($_POST['fecha'],3,2);
	$fecan    = substr($_POST['fecha'],6,4);
	$fecha    = $fecan.'-'.$fecme.'-'.$fecdi;
	$qfec1    = ', fecha';
	$qfec2    = ", '".$fecha."'";	
}else{
	$qfec1    = '';
	$qfec2    = '';
}
if($usahora == 1) {
	$hora     = $_POST['hora'];
	$qhor1    = ', hora';
	$qhor2    = ", '".$hora."'";
}else{
	$qhor1    = '';
	$qhor2    = '';	
}

//$usalink = '';
if($usalink == 2) {
	$link     = $_POST['link'];
	$qlnk1    = ', link';
	$qlnk2    = ", '".$link."'";
}else{
	$qlnk1    = '';
	$qlnk2    = '';	
}

if($usacolor == 1) {
	$color     = $_POST['color'];
	$qcol1    = ', color';
	$qcol2    = ", '".$color."'";
}else{
	$qcol1    = '';
	$qcol2    = '';	
}

if($activable == 1){
	$activo    = (isset($_POST['activo'])) ? 1 : 0;
	$qact1     = ",activo";
	$qact2     = ",".$activo;
}else{
	$qact1     = $qact2 = '';
}
if($usalink == 1 && ($tipodet == "docs" || $tipodet == "politicas") ){
	if(isset($_POST['url']) && $_POST['url'] != ''){
		$qlnk1 = ', peso, tipoarc';
		$pos = strpos($_POST['url'], 'iframe');
		if ($pos !== false) { // Si tiene iframe, se lo quitamos
			$url_final = explode('"',$_POST['url']);
			$_POST['url'] = $url_final[1];
		}
		$ext = urlExt($_POST['url']);
		$qlnk2    = ", 0, '".$ext."'";
	}elseif (is_uploaded_file($_FILES['archivo']['tmp_name'])){
		$empresa = $_POST['empresa'];
		$empdir  = emp_dir($empresa);
		$ext      = arch_ext($_FILES['archivo']['name']);
		$peso     = filesize($_FILES['archivo']['tmp_name']);
		$nombre   = nomArch($_FILES['archivo']['name']);
		$nombrear = urlFriendly($titulo);
		$link     = "cliente/docs/".$empdir."/".$id."_".$nombrear.".".$ext;
		if(move_uploaded_file($_FILES['archivo']['tmp_name'], "../".$link)){
			$qlnk1 = ', link, peso, tipoarc';
			$qlnk2    = ", '".$link."', ".$peso.", '".$ext."'";
		}else{
			echo $_FILES['archivo']['error'];
			$qlnk1 = $qlnk2 = '';
			$continuar = 0;
		}
	}else{
		$continuar = 0;
		$error = 'No se carg&oacute; el documento.';
	}
}else{
	$qlnk1 = $qlnk2 = '';
}

// Combos
$qcombos1 = '';
$qcombos2 = '';

$query_combos = "SELECT tab.nombre AS tabla, cbo.campo AS variable, cbo.multiple
					FROM ".$_SESSION['prefijo']."combos AS cbo
					INNER JOIN ".$_SESSION['prefijo']."tablas AS tab ON (cbo.combo = tab.id)
					WHERE cbo.tabla = ".$tipo;
$resul_combos = fullQuery($query_combos);
while($row_combos = mysqli_fetch_array($resul_combos)){
	$var_combo    = $row_combos['variable'];
	$var_combo    = $row_combos['variable'];
	$post_combo = $_POST[$var_combo];
	if($tipodet == 'empleados' && $var_combo == 'area'){
		foreach($post_combo as $areaemple){
			$sqlar = "INSERT INTO " . $_SESSION['prefijo'] . "empleados_areas (area,empleado) VALUES (".$areaemple.",".$id.")";
			fullQuery($sqlar);
		}
	}else{
		if($row_combos['multiple'] == 1){
			$post_combo = '"'.implode(',',$post_combo).'"';
		}
		$qcombos1.= ', '.$var_combo;
		$qcombos2.= ', '.$post_combo;
	}
}

// Vars
$qvars1 = '';
$qvars2 = '';

$query_vars = "SELECT * FROM ".$_SESSION['prefijo']."vars WHERE tabla = ".$tipo;
$resul_vars = fullQuery($query_vars);
while($row_vars = mysqli_fetch_array($resul_vars)){
	$var_vars = $row_vars['variable'];
	$vtipo = $row_vars['tipo'];
	$valor_vars = (isset($_POST[$var_vars])) ? $_POST[$var_vars] : 0;
	$qvars1.= ', '.$var_vars;
	if($vtipo == 1 && ($_POST[$var_vars] == '' || $_POST[$var_vars] == 0) ){$valor_vars = 0;}
	if($vtipo == 4){$valor_vars = hash('sha512', $valor_vars);}
	if($vtipo == 5){ // Fechas
		$varfec = explode("/",$valor_vars);
		$vardi    = $varfec[2];
		$varme    = $varfec[1];
		$varan    = $varfec[0];
		if(strlen($vardi) > 2){
			$varan = $varfec[2];
			$vardi = $varfec[0];
		}
		if($vardi != '' && $varme != '' && $varan != ''){
			if(strlen($varme) == 1 ){$varme = '0'.$varme;}
			if(strlen($vardi) == 1 ){$vardi = '0'.$vardi;}
			$valor_vars = $varan.'-'.$varme.'-'.$vardi;
		}else{
			$valor_vars = '0000-00-00';
		}
	}
	if($var_vars == 'seourl'){
		if($tipodet == "revista"){ //Edimp
			$valor_vars = ($valor_vars == '') ? urlFriendly($_POST['titulo']) : urlFriendly($valor_vars);
		}else{
			$valor_vars = ($valor_vars == '') ? urlFriendly($_POST[$tipotit]) : urlFriendly($valor_vars);
		}
		// Confirmamos que no exista
		$seochk = "SELECT seourl FROM ".$_SESSION['prefijo'].$nombretab." WHERE seourl = '{$valor_vars}'";
		$reschk = fullQuery($seochk);
		$conchk = mysqli_num_rows($reschk);
		if($conchk == 1){ // Si ya existe
			$seocnt = 1;
			$seoseguir = 1;
			while($seocnt < 1000 && $seoseguir == 1){ // Le agregamos n�meros hasta encontrar uno que no exista
				$seochk2 = "SELECT seourl FROM ".$_SESSION['prefijo'].$nombretab." WHERE seourl = '{$valor_vars}_{$seocnt}'";
				$reschk2 = fullQuery($seochk2);
				$conchk2 = mysqli_num_rows($reschk2);
				if($conchk2 == 0){
					$valor_vars = $valor_vars.'_'.$seocnt;
					$seoseguir = 0;
				}
				$seocnt++;
			}
		}
	}
	$qvars2.= ($vtipo == 1 || $vtipo == 3) ? ', '.$valor_vars : ', "'.txtdeco(reemplazo_comillas($valor_vars)).'"';
}
if($anidable == 1){
	$qvars1.= ', parent';
	$qvars2.= ','.$_POST['parent'];
}
$timestamp = date("Y-m-d");
$anio      = substr($timestamp,0,4);
$mes       = substr($timestamp,5,2);
$dia       = substr($timestamp,8,2);

if ($usarest == 1) {

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
		if ($link_areas != '') {
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
		}
	}
}
if($continuar == 1){
	if($usafotos == 1){ // Fotos
		$hayfotos = 0;
		// Crea la carpeta
		hacerDir("../cliente/fotos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo);
		$carpeta  = "cliente/fotos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo."/";	
		
		// Sube las fotos
		$contador = 1;
		$carp = 'tempimg';
		while ($contador < 200){
			$car_fil = $carp.'/'.$tipo.'_0_'.$contador.'.jpg';
			if (file_exists($car_fil)){
				$hayfotos = 1;
				$id_fot = nuevoID("fotos");
				$link   = $carpeta."imagen_".$id_fot.".jpg";
				// ancho y alto límite de las fotos
				$fotow  = config('fotow');
				$fotoh  = config('fotoh'); // ancho y alto límite de las fotos
				//$epi = reemplazo_comillas($_POST['epi_'.$contador]);
				$epi = '';
				subirImagen($car_fil, $link, $fotow, $fotoh, $tipo, $id, $epi);
				if(file_exists($car_fil)){unlink($car_fil);}
			}
			$contador++;
		}
		// Prepara la principal
		if(isset($hayfotos) && $hayfotos == 1){
			//$tiponota = ($usatexto == 1) ? 1 : 0;
			$tiponota = 1;
			generaPpal($tipo, $id, $tiponota);
			creaThumbs($tipo, $id); // Crea thumbnails
			creaThumbs($tipo, $id, 180, 180, 1); // Crea thumbnails de backend
		}
	}

	/* PARA VIDEOS CON LINKS EXTERNOS
	
	if($usavideoslinks == 1){
		$query = "SELECT id FROM ".$_SESSION['prefijo']."videos WHERE item = ".$id." AND tipo = ".$tipo;
		$resul = fullQuery($query);
		$conta = mysqli_num_rows($resul);
		
		// Videos - Agregar
		if(isset($_POST['agregar_videos']) && $_POST['agregar_videos'] == 1){
			$contador = 1;
			while ($contador < 21){
				if (isset($_POST['video'.$contador]) && $_POST['video'.$contador] != ''){
					$linkv = $_POST['video'.$contador];
					$posi = strpos($linkv, '&');
					if ($posi !== false) {
						$linkv = substr($linkv,0,$posi);
					}
					$posi = strpos($linkv, 'v=');
					if ($posi !== false) {
						$linkv = substr($linkv,$posi+2);
					}
					$id_vid = nuevoID("videos");
					$queryv  = "INSERT INTO ".$_SESSION['prefijo']."videos (id, tipo, item, ppal, link) VALUES ($id_vid,$tipo,$id,0,'$linkv')";
					$resultv = fullQuery($queryv);
				}
				$contador++;
			}
	
			if($conta > 0){
				// Video - Ppal
				$tiponota = 1;
				if($conta == 0){
					$_POST['vppal'] = 0;
				}
				if(isset($_POST['vppal'])){ // Video Principal
					$vppal = $_POST['vppal'];
					$query  = "UPDATE ".$_SESSION['prefijo']."videos SET ppal = 0 WHERE item = ".$id." AND tipo = ".$tipo;
					$resul  = fullQuery($query);
					if($vppal != 0){
						//generaPpal($tipo, $id, $tiponota, $ppal);
						$query  = "UPDATE ".$_SESSION['prefijo']."videos SET ppal = 1 WHERE id = ".$_POST['vppal'];
						$resul  = fullQuery($query);
					}
				}
			}
		}
	}
	*/

	if($usavideos == 1){
		function estaURL() {
			 $pageURL = 'http';
			 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			 $pageURL .= "://";
			 if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			 } else {
			  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			 }
			 $pageURL = explode('/backend',$pageURL);
			 $pageURL = current($pageURL);
			 return $pageURL;
		}
		$hayvideos = 0;
		
		// Sube las fotos
		$contador = 1;
		$carp = 'tempvid';
		while ($contador < 200){
			// Para buscar otros formatos que no sean MP4, recorrer archivos sin su extensión y leerla.
			$car_fil = $carp.'/'.'0_'.$contador.'.mp4';
			if (file_exists($car_fil)){
				// Crea la carpeta
				$carpeta  = "cliente/videos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo."/";
				if(!file_exists("../".$carpeta)){
					hacerDir("../".$carpeta."/");
				}
				$hayvideos = 1;
				$id_vid = nuevoID("videos");
				$link   = $carpeta."video_".$id_vid.".mp4";
				copy($car_fil, '../'.$link);
				unlink($car_fil);
				//$epi = reemplazo_comillas($_POST['epi_'.$contador]);
				$epi = 'Video ID '.$id_vid;
				$query  = "INSERT INTO ".$_SESSION['prefijo']."videos (id, tipo, item, ppal, link, epigrafe, usuario) VALUES (".$id_vid.",".$tipo.",".$id.",0,'".$link."','".$epi."',0)";
				$result = fullQuery($query);
			}
			$contador++;
		}
		// Videos - Armar XML
		if($hayvideos == 1){
			$link_xml = '../'.$carpeta.$id.'.xml';
			if(file_exists($link_xml)){
				unlink($link_xml);
			}
			$datosxml = '<?xml version="1.0" encoding="UTF-8"?>
							<playlist version="1" xmlns="http://xspf.org/ns/0/">
							   <trackList>';
			$filev = fopen($link_xml,"w+"); 
			$query = "SELECT * FROM ".$_SESSION['prefijo']."videos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY ppal DESC";
			$resul = fullQuery($query);
			while($row = mysqli_fetch_array($resul)){
				$link_xml = $row['link'];
				//$datosxml.= '<track>
				// <title>Video</title>
			   //  <location>http://www.youtube.com/watch?v='.$link_xml.'</location>
			//	 <image>http://www.movieposter.com/posters/archive/main/9/A70-4902</image>
			//  </track>';
				$datosxml.= '<track>
				 <title>'.$row['epigrafe'].'</title>
				 <location>'.estaURL().'/'.$link_xml.'</location>
			  </track>';
			}
			$datosxml.= '</trackList>
						</playlist>
						';
			fwrite($filev,$datosxml);
			fclose($filev);
		}
	}
	// Fin Videos

	if($tipodet == "empleados"){ // Empleados
		$contador = 1;
		$carp = 'tempimg';
		while ($contador < 2){
			$car_fil = $carp.'/0_'.$contador.'.jpg';
			$car_des = '../cliente/fotos/';
			$car_des.= $id;
			$car_des.= '.jpg';
			if(file_exists($car_fil)){
				$epi = '';
				subirImagenSinDB($car_fil, $car_des, 300, 300, 0);
				unlink($car_fil);
			}
			$contador++;
		}
		// Usuarios admins
		$sql_usrad = "SELECT id FROM ".$_SESSION['prefijo']."tablas WHERE administrador = 1";
		$res_usrad = fullQuery($sql_usrad);
		while($row_usrad = mysqli_fetch_array($res_usrad)){
			$tipo_usrad = $row_usrad['id'];
			if(isset($_POST['admi_'.$tipo_usrad])){ // si esa sección fue seleccionada
				$post_usrad = $_POST['admi_'.$tipo_usrad];
				$sql_usrad2 = "SELECT * FROM ".$_SESSION['prefijo']."usr_adm WHERE tabla = ".$tipo_usrad." AND usuario = ".$id;
				$res_usrad2 = fullQuery($sql_usrad2, 'detalles_post.php');
				$con_usrad2 = mysqli_num_rows($res_usrad2);
				if($con_usrad2 == 0){ // Si no está insertamos la opción
					$idadm = nuevoID("usr_adm");
					$sql_ins_usrad2 = "INSERT INTO ".$_SESSION['prefijo']."usr_adm (id, tabla, usuario) VALUES (".$idadm.",".$tipo_usrad.", ".$id.")";
					$res_ins_usrad2 = fullQuery($sql_ins_usrad2, 'detalles_post.php | insert admin usuario');
				}
			}else{ // Si el valor no fue seleccionado
				// Borramos de la DB
				$sql_delm = "DELETE FROM ".$_SESSION['prefijo']."usr_adm WHERE tabla = ".$tipo_usrad." AND usuario = ".$id;
				$res_delm = fullQuery($sql_delm, 'detalles_post.php | borrar usuario admin');
			}
		}
		// Minisitios admins
		$sql_minem = "SELECT id FROM ".$_SESSION['prefijo']."minisitios";
		$res_minem = fullQuery($sql_minem);
		while($row_minem = mysqli_fetch_array($res_minem)){
			$tipo_minem = $row_minem['id'];
			if(isset($_POST['mini_'.$tipo_minem])){ // si ese minisitio fue seleccionado
				$post_minem = $_POST['mini_'.$tipo_minem];
				$sql_minem2 = "SELECT * FROM ".$_SESSION['prefijo']."minisitios_usuarios WHERE minisitio = ".$tipo_minem." AND usuario = ".$id;
				$res_minem2 = fullQuery($sql_minem2, 'detalles_post.php');
				$con_minem2 = mysqli_num_rows($res_minem2);
				if($con_minem2 == 0){ // Si no está insertamos la opción
					$idmu = nuevoID("minisitios_usuarios");
					$sql_ins_minem2 = "INSERT INTO ".$_SESSION['prefijo']."minisitios_usuarios (id, minisitio, usuario) VALUES (".$idmu.",".$tipo_minem.", ".$id.")";
					$res_ins_minem2 = fullQuery($sql_ins_minem2, 'detalles_post.php | insert minisitios usuario');
				}
			}else{ // Si el valor no fue seleccionado
				// Borramos de la DB
				$sql_delm = "DELETE FROM ".$_SESSION['prefijo']."minisitios_usuarios WHERE minisitio = ".$tipo_minem." AND usuario = ".$id;
				$res_delm = fullQuery($sql_delm, 'detalles_post.php | borrar usuario minisitio');
			}
		}
		// Set empresa
		$emple_emp = $_POST['empresa'];
		$sqlemp = "UPDATE ".$_SESSION['prefijo']."empleados SET empresa = ".$emple_emp." WHERE id = ".$id;
		$resemp = fullQuery($sqlemp, 'detalles_post.php | asignar empresa a empleado');
		
	}
	
	$repet = (isset($_POST['repeticiones'])) ? $_POST['repeticiones'] : 0;
	if($repet > 0){
		switch ($repet){
			case 1:
				$frec = "+1 day";
				break;
			case 2:
				$frec = "+7 day";
				break;
			case 3:
				$frec = "+15 day";
				break;
			case 4:
				$frec = "+1 month";
				break;
			case 5:
				$frec = "+2 month";
				break;
			case 6:
				$frec = "+3 month";
				break;
			case 7:
				$frec = "+4 month";
				break;
			case 8:
				$frec = "+6 month";
				break;
			case 9:
				$frec = "+12 month";
				break;			
		}
		$fecha_hasta = $_POST['fecha_hasta'];
		$c_inicio = $c_mes = strtotime($fecha);
		$c_fin    = strtotime($fecha_hasta);
	
		while($c_mes < $c_fin){
			$qfec2    = ", '".date("Y-m-d",$c_mes)."'";	
			$query  = "INSERT INTO ".$_SESSION['prefijo'].$nombretab." (id".$qtit1.$qtxt1.$qfec1.$qhor1.$qlnk1.$qcombos1.$qvars1.$qcol1.$qact1.") VALUES ($id".$qtit2.$qtxt2.$qfec2.$qhor2.$qlnk2.$qcombos2.$qvars2.$qcol2.$qact2.") ";
			//echo '<br><br>'.$query;
			$result = fullQuery($query);
			$c_mes = strtotime($frec, $c_mes);
			$id++;
		}
	}else{
		$query  = "INSERT INTO ".$_SESSION['prefijo'].$nombretab." (id".$qtit1.$qtxt1.$qfec1.$qhor1.$qlnk1.$qcombos1.$qvars1.$qcol1.$qact1.") VALUES ($id".$qtit2.$qtxt2.$qfec2.$qhor2.$qlnk2.$qcombos2.$qvars2.$qcol2.$qact2.") ";
		$result = fullQuery($query);
	}
	//echo $query;
	if($tipodet == "edimp"){ // Ediciones Impresas
		// Tapita
		$carpeta  = "../".config('carp_edimp')."/";
		if (is_uploaded_file($_FILES['pdf']['tmp_name'])){
		
			$url_link   = $carpeta.config('edimp')."_".$id.".pdf";
			move_uploaded_file($_FILES['pdf']['tmp_name'], $url_link);
		}
		if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
			
			$url_link  = $carpeta.config('edimp')."_".$id.".jpg";
			$url_thum  = $carpeta."thumb_".$id.".jpg";
			//tapa
			$fotow  = 140;
			$fotoh  = 195;
			subirImagenSinDB('imagen', $url_link, $fotow, $fotoh);
			$fotow  = 84;
			$fotoh  = 117;
			subirImagenSinDB($url_link, $url_thumb, $fotow, $fotoh, 0);
		}
	}
	if($tipodet == 'encuestas'){ // Encuestas
		$contador = 1;
		// Crea la carpeta
		hacerDir("../cliente/fotos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo);
		$carpeta  = "cliente/fotos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo."/";	
	
		while ($contador < 21){
			if (isset($_POST['opcion'.$contador]) && $_POST['opcion'.$contador] != ''){
				$opc_valor = $_POST['opcion'.$contador];
				$id_opc = nuevoID("encuestas_opc");
				$col_opc = substr($_POST['color'.$contador],1,6);
				$sql_opc = "INSERT INTO ".$_SESSION['prefijo']."encuestas_opc (id, encuesta, valor, votos, color) VALUES ($id_opc, $id, '$opc_valor', 0, '$col_opc')";
				$res_opc = fullQuery($sql_opc);
				// Imagen
				/*
				if (is_uploaded_file($_FILES['imagen'.$contador]['tmp_name'])){
					$hayfotos = 1;
					$id_fot = nuevoID("fotos");
					$link   = $carpeta."imagen_".$id_fot.".jpg";
					// ancho y alto límite de las fotos
					$fotow  = config('fotow');
					$fotoh  = config('fotoh');
					subirImagen('imagen'.$contador, $link, $fotow, $fotoh, $tipo, $id_opc);
					$thumbh = 50;
					creaThumbs($tipo, $id_opc, 0, $thumbh);
				}
				*/
			}
			$contador++;
		}
	}
}

if($tipodet == "areas"){ // Areas
	if($multiemp == 1){
		$sqlarem = "DELETE FROM ".$_SESSION['prefijo']."areas_emp WHERE area = ".$id;
		$resarem = fullQuery($sqlarem, $backarch);
		$sql_emp = "SELECT id FROM ".$_SESSION['prefijo']."empresas ORDER BY id";
		$res_emp = fullQuery($sql_emp,$backarch);
		while($row_emp = mysqli_fetch_array($res_emp)){
			$idemp = $row_emp['id'];
			if(isset($_POST['emp'.$idemp])){
				$sae = "INSERT INTO ".$_SESSION['prefijo']."areas_emp (area,empresa) VALUES (".$id.", ".$idemp.")";
				$rae = fullQuery($sae, $backarch);
			}
		}
	}else{
		$sqlarem = "INSERT INTO ".$_SESSION['prefijo']."areas_emp (area,empresa) VALUES (".$id.", 1)";
		$resarem = fullQuery($sqlarem, $backarch);
	}
}

if ($error == ''){
	$msg = 0;
}else{
	$msg = 1;
}
if($tipodet == 'novedades'){ // Novedades
	$sql_cache = "UPDATE ".$_SESSION['prefijo']."config SET valor = '1' WHERE parametro = 'cache'";
	$res_cache = fullQuery($sql_cache);
}

if($tipodet == "revista"){
	include("inc/bk_alta_proc.php");
}