<?PHP
$errno = 2;
$titulo = getPost('titulo');
$id = nuevoID($nombretab);
if($tipo != 17){$id_usr = $_SESSION['usrfrontend'];}
$qextra1 = '';
$qextra2 = '';
/*
if($usafecha == 1){
	$fecha = getPost('fecha');
	if($fecha == 0){
		$err = 'fecha';
	}
}
*/
if($usafecha > 0){
	$dia  = getPost('dia');
	$mes  = getPost('mes');
	$anio = getPost('anio');
	if($dia == 0 || $mes == 0 || $anio == 0){
		$errno = 1;
	}else{
		$mes = (strlen($mes) == 1) ? '0'.$mes : $mes;
		$dia = (strlen($dia) == 1) ? '0'.$dia : $dia;
		$fecha = $anio.'-'.$mes.'-'.$dia;
	}
}
if($usafecha == 3){
	$fecha = date("Y-m-d");
}
if($usatexto == 1){
	$texto = getPost('texto');
	if($texto == ''){
		$errno = 1;
	}
}
if($usafotos > 0){ // Fotos
	$hayfotos = 0;
	$_SESSION["foto_id"] = 0;
	// Crea la carpeta
	$timestamp = date("Y-m-d");
	$anio      = substr($timestamp,0,4);
	$mes       = substr($timestamp,5,2);
	$dia       = substr($timestamp,8,2);
	hacerDir("cliente/fotos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo);
	$carpeta  = "cliente/fotos/".$anio."/".$mes."/".$dia."/".$id."-".$tipo."/";	
	
	if($usafotos == 1){// Sube las fotos múltiples
		$contador = 1;
		$carp = 'backend/tempimg';
		while ($contador < 200){
			$car_fil = $carp.'/'.'0_'.$contador.'.jpg';
			if (file_exists($car_fil)){
				$hayfotos = 1;
				$id_fot = nuevoID("fotos");
				$link   = $carpeta."imagen_".$id_fot.".jpg";
				// ancho y alto límite de las fotos
				$fotow  = config('fotow');
				$fotoh  = config('fotoh');
				//$epi = reemplazo_comillas($_POST['epi_'.$contador]);
				$epi = ($tipo == 15) ? $_POST['titulo'] : '';
				subirImagen($car_fil, $link, $fotow, $fotoh, $tipo, $id, $epi,$id_usr);
			}
			$contador++;
		}
	}
	if($usafotos == 2){ // Foto individual
		if (is_uploaded_file($_FILES['fotoind']['tmp_name'])){
			guardaLog('fuxion: formato foto'.$_FILES['fotoind']['type']);
			if($_FILES['fotoind']['type'] == 'image/jpeg'){
				$hayfotos = 1;
				$id_fot = nuevoID("fotos");
				$link   = $carpeta."imagen_".$id_fot.".jpg";
				// ancho y alto límite de las fotos
				$fotow  = config('fotow');
				$fotoh  = config('fotoh');
				$epi = ($tipo == 15) ? $_POST['titulo'] : '';
				$car_fil = 'temp/foto.jpg';
				if(move_uploaded_file($_FILES['fotoind']['tmp_name'], $car_fil)){
					subirImagen($car_fil, $link, $fotow, $fotoh, $tipo, $item, $epi,$id_usr);
					if(file_exists($car_fil)){unlink($car_fil);}
					$errno = 0;
				}else{
					$errno = 6;
				}
			}else{
				$errno = 5;
			}
		}else{
			$errno = 4;
		}
	}	
	// Prepara la principal
	if(isset($hayfotos) && $hayfotos == 1){
		//$tiponota = ($usatexto == 1) ? 1 : 0;
		generaPpal($tipo, $id, 1);
		creaThumbs($tipo, $id); // Crea thumbnails
		creaThumbs($tipo, $id, 180, 180, 1); // Crea thumbnails de backend
	}
}
if($tipo == '15' && $errno == 0){// Concurso de fotos
	$query  = "INSERT INTO intranet_participantes (tipoconcurso, concurso, usuario, votos) VALUES ({$tipo}, {$item}, {$id_usr}, 0)";
	/*
	$usuario_ext = 0;
	if($carga_manual == 1){ // si lo cargó el admin a mano
		// crea el nuevo usuario
		$usuario_ext = 1;
		$id_usr_ext = nuevoID('empleados_ext');
		$query_nuevo = "INSERT INTO intranet_empleados_ext (id, nombre, apellido) VALUES (".$id_usr_ext.", '".$_POST['nombre']."', '".$_POST['local']."')";
		$resul_nuevo = fullQuery($query_nuevo);
		// agrega al participante
		$query  = "INSERT INTO intranet_participantes (id, tipoconcurso, concurso, usuario, votos, usuario_ext) VALUES ($id_par, $tipo, $item, $id_usr_ext, 0, 1)";
	}*/
		
	$result = fullQuery($query);
}
$sqlins1 = $sqlins2 = '';
$qtit1    = ', titulo';
$qtit2    = ", '".utf8_decode($titulo)."'";
if($tipo == 14){ // Recomendados
	$salida = $_POST['salida'];
	$sqlins1 = ', salida';
	$sqlins2 = ', '.$salida;
}
if($usafecha > 0){
	$query_fecha1 = ", fecha";
	$query_fecha2 = ", '$fecha'";
}else{
	$query_fecha1 = "";
	$query_fecha2 = "";
}
if($usatexto == 1){
	$query_texto1 = ", texto";
	$query_texto2 = ", '$texto'";
}else{
	$query_texto1 = "";
	$query_texto2 = "";
}
if($tipo == 37 || $tipo == 22){ // Municipal
	$usauser = 1;
	$id_usr = $_POST['locales'];
}
if($usauser == 1){
	$usuario = $id_usr;
	$query_user1 = ", usuario";
	$query_user2 = ", $usuario";
}else{
	$query_user1 = "";
	$query_user2 = "";
}
if($tipo == 7){ // Novedades
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$timestamp = time();
	$varhora = date("H:i", $timestamp);
	$qextra1 = ', activo, seccion, hora';
	$qextra2 = ', 0, 9, "'.$varhora.'"';
}
if($tipo == 17){ // Sugerencias
	$qtit1 = ', tema';
	$query_texto1 = ', sugerencia';
	$qextra1 = ', nombre';
	$postnombre = (isset($_POST['nombre_an'])) ? 'anonimo' : $_POST['nombre'];
	$qextra2 = ', "'.$postnombre.'"';
}
if($usalink == 1){
	include("inc/inc_docs.php");
	switch($tipo){
		case 37:
			$empdir = 'municipal';
			break;
		case 22:
			$empdir = 'seguridad';
			break;
	}
	if (is_uploaded_file($_FILES['archivo']['tmp_name'])){
		/*
		$empresa = $_POST['empresa'];
		$empdir  = emp_dir($empresa);
		*/
		$ext      = arch_ext($_FILES['archivo']['name']);
		$peso     = filesize($_FILES['archivo']['tmp_name']);
		$nombrearc = nomArch($_FILES['archivo']['name']);
		$nombrear = txtcod($_FILES['archivo']['name']);
		$link     = "docs/".$empdir."/".$id."_".$nombrear;
		if(move_uploaded_file($_FILES['archivo']['tmp_name'], $link)){
			$sqld = "INSERT INTO intranet_docs (empresa,area,sector,link,peso,tipoarc,nombre,tabla) VALUES (0,0,".$id.", '".$link."', ".$peso.", '".$ext."', '".$nombrear."', ".$tipo.")";
			$resd = fullQuery($sqld);
			//echo '<br>'.$sqld;
		}
	}
}else{
	$qlnk1 = $qlnk2 = '';
}
if($errno == 2 && $tipo <> 15){ // Si no es concurso de fotos
	$query  = "INSERT INTO intranet_".$nombretab." (id".$qtit1.$sqlins1.$query_texto1.$query_fecha1.$query_user1.$qextra1.") VALUES ($id".$qtit2.$sqlins2.$query_texto2.$query_fecha2.$query_user2.$qextra2.") ";
	//echo '<br>'.$query;
	$result = fullQuery($query);
}
if($tipo == 37 || $tipo == 22){
	$errno = 3;
}
if($tipo == 15 && $errno == 0){ // Si es concurso y no hubo errores
	header("Location: nota.php?tipo=15&id=".$item."&ok=1");
	die();
}
?>