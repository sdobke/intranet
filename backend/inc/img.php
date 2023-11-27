<?PHP
function subirImagen($imgpost, $link, $limitew, $limiteh, $tipo, $id, $epi='',$usr=0){
// parámetros:  ($imgpost = variable $_Files, $link = url completa, $limitew = ancho máximo, $limiteh = alto máximo, $tipo = de tabla según DB (7 es novedades), $id = de novedades, en el caso de ser 7 por ejemplo
	$url_link = (strpos($_SERVER['PHP_SELF'],'backend') > 0) ? '../'.$link : $link;
	if(isset($_FILES[$imgpost]['tmp_name'])){
		move_uploaded_file($_FILES[$imgpost]['tmp_name'], $url_link);
	}else{
		copy($imgpost, $url_link);
		unlink($imgpost);
	}
	$imagen = new SimpleImage();
	$imagen->load($url_link);
	$ancho = $imagen->getWidth();
	$alto  = $imagen->getHeight();
	//echo '<br>Ancho: '.$ancho.' > '.$limitew.' | Alto: '.$alto.' > '.$limiteh;
	if($ancho > $limitew || $alto > $limiteh){ // si el ancho o el alto son mayores a los especificados
		if($alto > $ancho){ // si el alto es mayor al ancho
			//echo '<br>Alto es > Ancho';
			$imagen->resizeToHeight($limiteh); // llevamos la imagen al alto
		}else{
			//echo '<br>Ancho es > Alto';
			$imagen->resizeToWidth($limitew); // sinó al ancho
		}
	}else{
		$imagen->resize($ancho,$alto);
	}
	$imagen->save($url_link);
	$id_fot = nuevoID("fotos");
	$query  = "INSERT INTO ".$_SESSION['prefijo']."fotos (id, tipo, item, ppal, link, epigrafe, usuario) VALUES ($id_fot,$tipo,$id,0,'$link','$epi',$usr)";
	$result = fullQuery($query);
}
function subirVideo($vid, $link, $tipo, $id)
{
	$url_link = (strpos($_SERVER['PHP_SELF'],'backend') > 0) ? '../'.$link : $link;
	rename($vid, $url_link);
	$id_vid = nuevoID("videos");
	$query  = "INSERT INTO ".$_SESSION['prefijo']."videos (id, link, item, tipo, ppal, del) VALUES ({$id_vid}, '{$link}', {$id}, {$tipo}, 0, 0)";
	$result = fullQuery($query);
}
function fotoPpal($id,$link,$nom_fin,$tipo=1,$limitew=0,$limiteh=0,$inicial=0){
	$query    = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE id = ".$id;
	$resul    = fullQuery($query);
	$conta    = mysqli_num_rows($resul);
	$devo = 0;
	if($conta > 0){
		$row_ppal = mysqli_fetch_array($resul);
		$ori_ppal = (strpos($_SERVER['PHP_SELF'],'backend') > 0 && strpos($link, '../') === false) ? '../'.$link : $link;
		if(file_exists($ori_ppal)){
			$ori_ppal2 = explode("imagen",$ori_ppal, -1);
			$dir_ppal = end($ori_ppal2);
			$des_ppal = $dir_ppal.$nom_fin.'.jpg';
			copy($ori_ppal, $des_ppal);
			$imagen = new SimpleImage();
			$imagen->load($des_ppal);
			if($tipo == 0){ // alto y ancho fijos
				$imagen->centrar($limitew, $limiteh,$inicial);
			}
			if($tipo == 1){ // al máximo de ancho o alto
				$ancho = $imagen->getWidth();
				$alto  = $imagen->getHeight();
				if($ancho > $limitew || $alto > $limiteh){
					if($limiteh*$ancho/$alto < $limitew){
						$imagen->resizeToHeight($limiteh);
					}else{
						$imagen->resizeToWidth($limitew);
					}
				}
			}
			if($tipo == 2){ // al ancho
				$imagen->resizeToWidth($limitew);
			}
			if($tipo == 3){ // centrar
				$imagen->centrar($limitew, $limiteh,$inicial);
			}
			$imagen->save($des_ppal);
		}else{
			$devo = 0;
		}
	}else{
		$devo = 0;
	}
	return $devo;
}
function generaPpal($tipo, $item, $tiponota=1, $idfoto=0, $pre=''){ //Genera la foto principal automáticamente
	$devo = 0;
	if($pre == ''){
		$pre = (strpos($_SERVER['PHP_SELF'],'backend') > 0) ? '../' : '';
	}
	$azar = ($idfoto == 0) ? 'tipo = '.$tipo.' AND item = '.$item.' ORDER BY RAND() LIMIT 1' : ' id = '.$idfoto;
	if($idfoto == 0){ // Si no es una foto específica, primero se elimina cualquier principal que pueda existir
		$query = "UPDATE ".$_SESSION['prefijo']."fotos SET ppal = 0 WHERE tipo = ".$tipo." AND item = ".$item;
		$resul = fullQuery($query);
	}
	$query = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE ".$azar;
	$resul = fullQuery($query);
	$conta = mysqli_num_rows($resul);
	if($conta > 0){
		$row   = mysqli_fetch_array($resul);
		$id    = $row['id'];
		$link  = $pre.$row['link'];
		if(file_exists($link)){
			if($tiponota == 1){
				$fotowid = config('destacadaw');
				$fotohei = config('destacadah');
				$formato = 2;
				//$id,$link,$nom_fin,$tipo=1,$limitew=0,$limiteh=0
				fotoPpal($id, $link, 'nota', 3, config('notaw'), config('notah')); // Nota
				fotoPpal($id, $link, 'sec', 3, config('fotosecw'), config('fotosech')); // Sección
				fotoPpal($id, $link, 'thumb', 3, config('thumb'), config('thumb')); // Thumbnails
				fotoPpal($id, $link, 'wide', 3, config('widew'), config('wideh'),'top'); // Wide
				if($idfoto == 0){ // Si no se creó manualmente
					fotoPpal($id, $link, 'dest', 3, config('destacadaw'), config('destacadah')); // Home
					fotoPpal($id, $link, 'home', 3, config('destacadaw'), config('destacadah')); // Home
				}
			}else{ // Para posters turismo
				$fotowid = config('fotoposterw');
				$fotohei = 0;
				$formato = 2;
				fotoPpal($id, $link, 'col', 2, config('fotocolw'), 0); // columna
				fotoPpal($id, $link, 'dest', 3, $fotowid, $fotohei); // destacada
			}
			creaThumbs($tipo, $item);
			if(strpos($_SERVER['PHP_SELF'],'backend') > 0){creaThumbs($tipo, $item, 180, 180, 1);}
			$query  = "UPDATE ".$_SESSION['prefijo']."fotos SET ppal = '0' WHERE tipo = ".$tipo." AND item = ".$item;
			$resul  = fullQuery($query);
			$query  = "UPDATE ".$_SESSION['prefijo']."fotos SET ppal = '1' WHERE id = ".$id;
			$resul  = fullQuery($query);
			$devo = 1;
		}
	}
	return $devo;
}
function creaThumbs($tipo, $item, $anch=0, $alto=0, $backend=0,$usr=0){ //thumbs
	$query_user = ($usr == 0) ? '' : ' AND usuario = '.$usr;
	$query = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE tipo = ".$tipo." AND item = ".$item.$query_user;
	$resul = fullQuery($query);
	$conta = mysqli_num_rows($resul);
	if($conta > 0){
		if(($anch == 0 && $alto == 0) || $backend == 1){
			if($backend == 0){$anch = $alto = config('thumb');}
			while($row = mysqli_fetch_array($resul)){
				$id   = $row['id'];
				$link = $row['link'];
				$link = (strpos($_SERVER['PHP_SELF'],'backend') > 0) ? '../'.$link : $link;
				$nroth = ($backend == 0) ? '' : '_bk';
				if(!file_exists('img_'.$id.'_thumb'.$nroth)){
					fotoPpal($id, $link, 'img_'.$id.'_thumb'.$nroth, 0, $anch, $anch); // thumbnail
				}
			}
		}else{
			while($row = mysqli_fetch_array($resul)){
				$id   = $row['id'];
				$link = $row['link'];
				$ori_ppal = (strpos($_SERVER['PHP_SELF'],'backend') > 0) ? '../'.$link : $link;
				$dir_ppal = explode("imagen",$ori_ppal, -1);
				$dir_ppal = end($dir_ppal);
				$nrofoto = explode('imagen_',$ori_ppal);
				$nrofoto = explode('.jpg',end($nrofoto));
				$nrofoto = current($nrofoto);
				$des_ppal = $dir_ppal.'thumb_'.$nrofoto.'.jpg';
				copy($ori_ppal, $des_ppal);
				$imagen = new SimpleImage();
				$imagen->load($des_ppal);
				$ancho = $imagen->getWidth();
				$alto  = $imagen->getHeight();
				if($anch == 0){
					$imagen->resizeToHeight($alto);
				}elseif($alto == 0){
					$imagen->resizeToWidth($ancho);
				}else{
					$imagen->centrar($anch,$alto);
				}
				$imagen->save($des_ppal);
			}
		}
	}
}
function subirThumbs($imgpost, $link){
	$url_link = (strpos($_SERVER['PHP_SELF'],'backend') > 0) ? '../'.$link : $link;
	move_uploaded_file($_FILES[$imgpost]['tmp_name'], $url_link);
	$imagen = new SimpleImage();
	$imagen->load($url_link);
	$imagen->resizeToHeight(config('thumb')); // llevamos la imagen al alto
	$imagen->save($url_link);
}
function subirImagenSinDB($imgpost, $url_link, $limitew, $limiteh, $es_upload=1){
	if($es_upload == 1){
		move_uploaded_file($_FILES[$imgpost]['tmp_name'], $url_link);
	}else{
		copy($imgpost, $url_link);
	}
	$imagen = new SimpleImage();
	$imagen->load($url_link);
	$imagen->centrar($limitew,$limiteh);
	//$imagen->resizeToHeight($limiteh);
	$imagen->save($url_link);
}
function agregaImg($nro,$vis=0,$agrega=1){
	if ($vis == 1){$ver = "block";}else{$ver = "none";}
	$resultado  = '<div id="DivCont'.$nro.'" style="display:'.$ver.'">';
	$resultado .= '<table><tr><td><table><tr><td>Imagen</td><td>'.$nro.': </td><td><input type="file" name="imagen'.$nro.'" id="imagen'.$nro.'" class="txtfield" ';
	if ($agrega == 1){
		$resultado .= 'onmouseup="agregaCampos('.$nro.')" ';
	}
	$resultado .= '/></td><td>Ep&iacute;grafe: </td><td><textarea name="epi_'.$nro.'" class="epigrafe_new" /></textarea></td></tr></table></td></tr></table>';
	$resultado .= '</div>';
	return $resultado;
}
function agregaVid($nro,$vis=0,$agrega=1){
	if ($vis == 1){$ver = "block";}else{$ver = "none";}
	$resultado  = '<div id="DivContV'.$nro.'" style="display:'.$ver.'">
	';
	$resultado .= '<div class="control-group">
					<label class="control-label" for="video'.$nro.'">Video '.$nro.': </label>
						<div class="controls"><input type="text" name="video'.$nro.'" id="video'.$nro.'" class="span12" ';
	if ($agrega == 1){
		$resultado .= 'onmouseup="agregaCamposV('.$nro.')" ';
	}
	$resultado .= '/>
	';
	$resultado .= '</div></div></div>
	';
	return $resultado;
}
function borrarDestacadas($link_img,$id=0,$tipo=0,$todo=0){
	$link_fot = "../".$link_img;
	if($id > 0 && $tipo > 0 && $link_img == 0){
		$sql = "SELECT link FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo." LIMIT 1";
		$res = fullQuery($sql);
		$con = mysqli_num_rows($res);
		if($con == 1){
			$row = mysqli_fetch_assoc($res);
			$link_fot = '../'.$row['link'];
		}
	}
	$carpeta = explode("imagen",$link_fot, -1);
	$carpeta = current($carpeta);
	if(file_exists($carpeta."dest.jpg")){
		unlink($carpeta."dest.jpg");
	}
	if(file_exists($carpeta."nota.jpg")){
		unlink($carpeta."nota.jpg");
	}
	if(file_exists($carpeta."sec.jpg")){
		unlink($carpeta."sec.jpg");
	}
	if(file_exists($carpeta."wide.jpg")){
		unlink($carpeta."wide.jpg");
	}
	if(file_exists($carpeta."home.jpg")){
		unlink($carpeta."home.jpg");
	}
	if($todo == 1){
		if(file_exists($carpeta."thumb.jpg")){
			unlink($carpeta."thumb.jpg");
		}
	}
}
function borraFotos($id,$tipo){
	$query = "SELECT link FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo;
	
	$resul = fullQuery($query);
	while($row = mysqli_fetch_array($resul)){
		$link_img = $row['link'];
		
		if(file_exists('../'.$link_img)){
			unlink('../'.$link_img);
		}
		$link_arch = explode('.jpg',$link_img);
		$link_arch = current($link_arch);
		$lnk   = '../'.str_replace('imagen','img',$link_arch);
		$lnkt  = $lnk.'_thumb.jpg';
		$lnktb = $lnk.'_thumb_bk.jpg';
		if(file_exists($lnkt)){
			unlink($lnkt);
		}
		if(file_exists($lnktb)){
			unlink($lnktb);
		}
	}
	// si hay destacadas y no quedan fotos las borra
	if(isset($link_img)){
		borrarDestacadas($link_img.$id);
	}
	$query_borra = "DELETE FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo;
	$resul_borra = fullQuery($query_borra);
}
class SimpleImage {
   var $image;
   var $image_type;
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=80, $permissions=null) {
      $compression = config('calidad');
	  if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   function resize($width,$height) {
   	  $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }
	function centrar($width,$height,$inicio='0') { // $inicio marca el punto en la img orig. Top es arriba, 0 o centro es en el centro, bottom abajo
		//echo '<br>inicio: '.$inicio;
		$new_image = imagecreatetruecolor($width, $height);
		$iniw = $inih = 0;
		$formula = $this->getWidth() * $height / $this->getHeight();
		$alto_final = $this->getHeight();
		$ancho_final = $this->getWidth();
		// Determinar el punto de inicio en la imagen original
		if($formula < $width){ // Si es ancha
			$alto_final = $height * $ancho_final / $width;
			if($inicio == 'top'){
				$inih = 0;
			/*}elseif($inicio == 'bottom'){
				$inih = ($this->getHeight() - $alto_final);*/
			}else{ //($inicio == 0 || $inicio == 'centro')
				$inih = ($this->getHeight() - $alto_final) /2;
			}
			//echo '<br>Inicio '.$inicio.': '.$inih;
		}elseif($formula > $width){ // Si es alta
			$ancho_final = $width * $alto_final / $height;
			$iniw = ($this->getWidth() - $ancho_final) /2;
		}// si es igual deja los valores ancho y alto originales
		imagecopyresampled($new_image, $this->image, 0, 0, $iniw, $inih, $width, $height, $ancho_final, $alto_final);
		$this->image = $new_image;
   }
}
function agregaMp3($nro,$vis=0,$agrega=1){
	if ($vis == 1){$ver = "block";}else{$ver = "none";}
	$resultado  = '<div id="DivContM'.$nro.'" style="display:'.$ver.'">
	';
	$resultado .= '<div class="control-group">
					<label class="control-label" for="mp3'.$nro.'">Mp3 '.$nro.': </label>
						<div class="controls"><input type="text" name="mp3'.$nro.'" id="mp3'.$nro.'" class="span12" value="Titulo" />';
	$resultado .= '<input type="file" name="mp3f'.$nro.'" class="span12" ';
	if ($agrega == 1){
		$resultado .= 'onmouseup="agregaCamposM('.$nro.')" ';
	}
	$resultado .= '/>
	';
	$resultado .= '</div></div></div>
	';
	return $resultado;
}
