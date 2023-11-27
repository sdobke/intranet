<?PHP 
function getPost($tipo){
	if(isset($_GET[$tipo])){
		$devolver = $_GET[$tipo];
	}elseif(isset($_POST[$tipo])){
		$devolver = $_POST[$tipo];
	}else{
		$devolver = 1;
	}
	return $devolver;
}
function parametro($parametro, $tipo, $tabla='empleos_tablas'){
	$sql = "SELECT ".$parametro." FROM ".$tabla." WHERE id = $tipo";
	$res = fullQuery($sql);
	$row = mysqli_fetch_array($res);
	return $row[$parametro];
}
function FechaDet($fecha,$formato='largo',$con_anio='n'){ //el formato de la fecha debe ser 2010-04-02
	$dia =substr($fecha, 8, 2);
	$mes =substr($fecha, 5, 2);
	switch ($mes) {
		case 1: 
			$mes = "enero"; 
			break;
		case 2: 
			$mes = "febrero"; 
			break;
		case 3: 
			$mes = "marzo"; 
			break;
		case 4: 
			$mes = "abril"; 
			break;
		case 5: 
			$mes = "mayo"; 
			break;
		case 6: 
			$mes = "junio"; 
			break;
		case 7: 
			$mes = "julio"; 
			break;
		case 8: 
			$mes = "agosto"; 
			break;
		case 9: 
			$mes = "septiembre"; 
			break;
		case 10: 
			$mes = "octubre"; 
			break;
		case 11: 
			$mes = "noviembre"; 
			break;	
		case 12: 
			$mes = "diciembre"; 
			break;
	}
	if($formato == 'corto'){
		$mes = substr($mes, 0, 3);
	}
	$devuelvo = $dia." de ".$mes;
	if($con_anio == 's'){
		$anio=substr($fecha, 0, 4);
		$devuelvo .=" de ".$anio;
	}
	if($formato == 'M-y'){
		$devuelvo = substr($mes, 0, 3).'-'.substr($fecha, 0, 4);
	}
	if($formato == 'm-y'){
		$devuelvo = substr($fecha, 5, 2).'/'.substr($fecha, 2, 2);
	}
	if($devuelvo == '00 de 00 de 0000'){
		$devuelvo = '';
	}
	return $devuelvo;
}

function empresa($cod){
	switch ($cod){
		case 1:
			$emp = "Alsea";
			break;
		case 2:
			$emp = "Burger King";
			break;
		case 3:
			$emp = "Starbucks Coffee";
			break;
		default:
			$emp = "Alsea";
			break;
	}
	return $emp;
}

function empresa_cod($cod){
	$emp = strtolower(empresa($cod));
	return $emp;
}

function emp_dir($cod){
	$emp = ($cod == 3) ? 'starbucks' : str_replace(' ','',empresa_cod($cod));
	return $emp;
}

function area($area){
	$query = fullQuery("SELECT * FROM empleos_areas where id = $area");
	$row = mysqli_fetch_array($query);
	$resultado = $row['nombre'];
	return $resultado;	
}

function sector($sector){
	$query = fullQuery("SELECT * FROM empleos_sectores where id = $sector");
	$row = mysqli_fetch_array($query);
	$resultado = $row['nombre'];
	return $resultado;	
}

function tipoFoto($cod){
	$query = fullQuery("SELECT * FROM empleos_tablas where id = $cod");
	$row = mysqli_fetch_array($query);
	$resultado = $row['nombre'];
	return $resultado;	
}

function sitio($codigo){
	switch ($codigo){
		case 1:
			$sitio="sca";
			break;
		case 2:
			$sitio="bkg";
			break;
		case 3:
			$sitio="sbk";
			break;
		case 4:
			$sitio="als";
			break;			
	}
	return $sitio;
}
function itemMenu($dir, $valor, $nombre=''){
	echo '<a href="'.$valor.'.php">';
	if ($dir == $valor){
		echo '<strong>';
	}
	if($nombre == ''){$nombre = $valor;}
	echo ucwords($nombre);
	if ($dir == $valor){
		echo '</strong>';
	}
	echo '</a>';
}

function boldSelecFin($var, $valor){
	if ($var == $valor){
		echo '</strong>';
	}
}

function fullQuery($query){
	$resultado = fullQuery($query) or die($error = '<br /><br />Error de MySQL: "'.mysqli_error().'" en query:<br /> '.$query);
	return $resultado;
}

function nuevoID($tabla){
	$prefijo = 'empleos';
	$res = fullQuery("SELECT id FROM ".$prefijo."_".$tabla." ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_array($res);
	$id  = $row['id']+1;
	return $id;
}

function paginador($limit, $contar, $pag, $variables=''){
	
	if ($limit == 0){
		$totalpages = 1;
	}else{
		$totalpages = $contar / $limit;
	}
	$totalpages = ceil($totalpages);
	if($pag == 1){
		$actualpage = '<strong>1</strong>';
	}else{
		$actualpage = "<strong>".$pag."</strong>";
	}
	
	if($pag < $totalpages){
		$nv = $pag+1;
		$pv = $pag-1;
		$pag_sig     = "<a href=?".$variables."&limit=$limit&pag=$nv>Siguiente ></a>";
		$pag_ant     = "<a href=?".$variables."&limit=$limit&pag=$pv>&lsaquo; Anterior</a>";
		$pag_primera = "<a href=?".$variables."&limit=$limit&pag=1><< </a>";
		$pag_ultima  = "<a href=?".$variables."&limit=$limit&pag=$totalpages\"> >></a>";
	}
	if($pag == '1'){
		$nv = $pag+1;
		$pag_sig     = "<a href=?".$variables."&limit=$limit&pag=$nv> Siguiente &rsaquo; </a>";
		$pag_ant     = "&lsaquo; Anterior";
		$pag_primera = "<< ";
		$pag_ultima  = "<a href=?".$variables."&limit=$limit&pag=$totalpages> >></a>";
	
	}elseif($pag == $totalpages){
		$pv = $pag-1;
		$pag_sig     = " Siguiente &rsaquo;";
		$pag_ant     = "<a href=?".$variables."&limit=$limit&pag=$pv>&lsaquo; Anterior </a>";
		$pag_primera = "<a href=?".$variables."&limit=$limit&pag=1><< </a>";
		$pag_ultima  = " >>";
	}
	if($totalpages == '1' || $totalpages == '0'){
		$pag_sig     = "Siguiente &rsaquo;";
		$pag_ant     = "&lsaquo; Anterior";
		$pag_primera = "<< ";
		$pag_ultima  = " >>";
	}
	$devolver = '<span>';
	$devolver.= $pag_primera;
	$devolver.= '</span> <span>';
	$devolver.= $pag_ant;
	$devolver.= '</span> <span style="color:#000;">';
	$devolver.= $actualpage;
	$devolver.= ' - ';
	$devolver.= $totalpages;
	$devolver.= '</span> <span>';
	$devolver.= $pag_sig;
	$devolver.= '</span> <span>';
	$devolver.= $pag_ultima;
	$devolver.= '</span>';

	return $devolver;
}

function cortarTexto($texto, $tamano, $puntos="..."){
   $texto=strip_tags($texto);
   if(strlen($texto)<=$tamano)return $texto;
   $body = explode(" ", $texto);
   $output = $body[0];
   $i=1;
   
   while((strlen($output)+strlen($body[$i])+1)<=$tamano and $body[$i]){
     $output .= " ".$body[$i];
     $i++;
   }
   return $output.$puntos;
}

function obtenerNombre($tipo){
	$sql_nom = fullQuery("SELECT nombre FROM empleos_tablas WHERE id = ".$tipo);
	$row_nom = mysqli_fetch_array($sql_nom);
	return $row_nom['nombre'];
}

function obtenerDato($campo,$tabla,$id,$prefijo='intranet'){
	$sql_nom = "SELECT ".$campo." FROM ".$prefijo."_".$tabla." WHERE id = ".$id;
	$res_nom = fullQuery($sql_nom);
	$row_nom = mysqli_fetch_array($res_nom);
	$devolver = '';
	if(strpos($campo, ',')){
		$partes = explode(",", $campo);
		foreach($partes as $key => $value){
			$devolver.= " ".$row_nom[$value];
		}
	}else{
		$devolver = $row_nom[$campo];
	}
	return $devolver;
}

function hacerDir($dir){
	$partes = explode("/", $dir);
	$dir_acum = '';
	foreach ($partes as $key => $value) {
		$dir_parcial = $value;
		if($dir_acum != ''){
			$dir_acum .= "/";
		}
		$dir_acum .= $dir_parcial;
		if (!is_dir($dir_acum."/") && $dir_acum != '.' && $dir_acum != '..'){
				mkdir($dir_acum, 0777);
		}
	}
}
function reemplazo_comillas($texto){
	$origen = array('�','`','�','�',"'",'"');
	$destin = array('&rsquo;','&lsquo;','&ldquo;','&rdquo;','&acute;','&quot;');
	$texto  = str_replace($origen,$destin,$texto);
	return $texto;
}
function optSel($var, $valor){
	if ($var == $valor){
		echo 'selected="selected"';
	}
}
function decodeTexto($texto){
	$texto = utf8_decode(utf8_encode($texto));
	$original  = array("�","�","�","�","�","�","�","�","�","�","�","�"," ");
	$reemplazo = array("a","a","e","e","i","i","o","o","u","u","n","n","_");
	$devuelve  = strtolower(str_replace($original,$reemplazo,$texto));
	return $devuelve;
}

function imagenPpal($id, $tipo, $dest=1){
	$sql_fotos = "SELECT * FROM empleos_fotos WHERE item = $id AND tipo = $tipo AND ppal = 1";
	$res_fotos = fullQuery($sql_fotos);
	$cont_foto_ppal = mysqli_num_rows($res_fotos);
	if ($cont_foto_ppal == 1){
		$row_fotos = mysqli_fetch_array($res_fotos);
		$foto_ppal = ($dest == 1) ? end(explode("imagen",$row_fotos['link'], -1))."imagen_nota.jpg" : end(explode("imagen",$row_fotos['link'], -1))."imagen_ppal.jpg";
		return $foto_ppal;
	}else{
		return '0';
	}
	
}

function agrega_acceso($tipo){
	$fecha = date("Y-m-d");
	$sql = "SELECT * FROM empleos_accesos_fechas WHERE fecha = '$fecha' AND seccion = $tipo";
	//echo '<br />'.$sql;
	$res = fullQuery($sql);
	$con = mysqli_num_rows($res);
	if($con == 0){
		$id = nuevoID('accesos_fechas');
		$sql = "INSERT INTO empleos_accesos_fechas (id,fecha,accesos,seccion) VALUES ($id,'$fecha',0,$tipo)";
		//echo '<br />'.$sql;
		$res = fullQuery($sql);
	}
	$sql = "UPDATE empleos_accesos_fechas SET accesos = accesos+1 WHERE fecha = '$fecha' AND seccion = $tipo";
	//echo '<br />'.$sql;
	$res = fullQuery($sql);
	// estadistica detallada
	$usuario = (isset($_SESSION['usrfrontend'])) ? $_SESSION['usrfrontend'] : 0;
	$id = nuevoID('accesos_detalle');
	$sql = "INSERT INTO empleos_accesos_detalle (id,fecha,accesos,seccion,empleado) VALUES ($id,'$fecha',0,$tipo,$usuario)";
	//echo '<br />'.$sql;
	$res = fullQuery($sql);
}

function verRestriccion($id_emp){
	$sql = "SELECT empresa, oficinas FROM empleos_empleados WHERE id = ".$id_emp;
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
	return $valor;
}
?>