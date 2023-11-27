<?PHP

function subirImagen($imgpost, $link, $limitew, $limiteh, $tipo, $id, $epi='',$usr=0,$usr_ext=0){
// parámetros:  ($imgpost = variable $_Files, $link = url completa, $limitew = ancho máximo, $limiteh = alto máximo, $tipo = de tabla según DB (7 es novedades), $id = de novedades, en el caso de ser 7 por ejemplo
	$url_link = '../'.$link;
	move_uploaded_file($_FILES[$imgpost]['tmp_name'], $url_link);
	$imagen = new SimpleImage();
	$imagen->load($url_link);
	$ancho = $imagen->getWidth();
	$alto  = $imagen->getHeight();
	if($ancho > $limitew || $alto > $limiteh){ // si el ancho o el alto son mayores a los especificados
		if($alto > $ancho){ // si el alto es mayor al ancho
			$imagen->resizeToHeight($limiteh); // llevamos la imagen al alto
		}else{
			$imagen->resizeToWidth($limitew); // sinó al ancho
		}
	}else{
		$imagen->resize($ancho,$alto);
	}
	$imagen->save($url_link);
	$id_fot = nuevoID("fotos");
	$query  = "INSERT INTO intranet_fotos (id, tipo, item, ppal, link, epigrafe, usuario, usuario_ext) VALUES ($id_fot,$tipo,$id,0,'$link','$epi',$usr,'$usr_ext')";
	//echo "<br />".$query;
	$result = fullQuery($query);
}

function fotoPpal($id_foto,$link,$tipo=1){ //Generación de foto ppal de nota | 1 es para nota, 0 para 63x63 y 2 para home
	$querynueva = "SELECT link FROM intranet_fotos WHERE id = ".$id_foto;
	$resul    = fullQuery($querynueva);
	$row_ppal = mysqli_fetch_array($resul);
	$link = $row_ppal['link'];
	$ori_ppal = "../".$link;
	$dir_ppal = end(explode("imagen",$ori_ppal, -1));
	$nom_fin  = 'imagen_nota.jpg';
	$des_ppal = $dir_ppal.$nom_fin;
	copy($ori_ppal, $des_ppal);
	$limitew = 430;
	$limiteh = 200;
			
	$imagen = new SimpleImage();
	$imagen->load($des_ppal);
	$ancho = $imagen->getWidth();
	$alto  = $imagen->getHeight();
	if($ancho > $limitew || $alto > $limiteh){
		if($limiteh*$ancho/$alto < $limitew){
			$imagen->resizeToHeight($limiteh);
		}else{
			$imagen->resizeToWidth($limitew);
		}
	}
	$imagen->save($des_ppal);
	
	if($tipo == 0){
		$nom_fin  = 'imagen_ppal.jpg';
		$des_ppal = $dir_ppal.$nom_fin;
		copy($ori_ppal, $des_ppal);
		$limitew = 63;
		$limiteh = 63;
		$imagen = new SimpleImage();
		$imagen->load($des_ppal);
		$ancho = $imagen->getWidth();
		$alto  = $imagen->getHeight();
		$imagen->resize($limitew, $limiteh);
		$imagen->save($des_ppal);
	}
	
	if($tipo == 2){
		$nom_fin  = 'imagen_'.$id_foto.'_home.jpg';
		$des_ppal = $dir_ppal.$nom_fin;
		copy($ori_ppal, $des_ppal);
		$limitew = 200;
		$imagen = new SimpleImage();
		$imagen->load($des_ppal);
		$ancho = $imagen->getWidth();
		$alto  = $imagen->getHeight();
		$imagen->resizeToWidth($limitew);
		$imagen->save($des_ppal);
	}
}

function generaPpal($tipo, $item){ //Genera la foto principal automáticamente
	$query = "SELECT * FROM intranet_fotos WHERE tipo = $tipo AND item = $item ORDER BY RAND() LIMIT 1";
	$resul = fullQuery($query);
	$row   = mysqli_fetch_array($resul);
	$id_princ = $row['id'];
	$link  = $row['link'];
	fotoPpal($id_princ,$link,0); // 0 es mini y 1 es 430x200
	$query  = "UPDATE intranet_fotos SET ppal = '1' WHERE id = ".$id_princ;
	//echo "<br />".$query;
	$resul  = fullQuery($query);
}

function subirThumbs($imgpost, $link){
	$url_link = '../'.$link;
	move_uploaded_file($_FILES[$imgpost]['tmp_name'], $url_link);
	$imagen = new SimpleImage();
	$imagen->load($url_link);
	$imagen->resizeToHeight(125); // llevamos la imagen al alto
	$imagen->save($url_link);
}

function subirImagenSinDB($imgpost, $url_link, $limitew, $limiteh){
	move_uploaded_file($_FILES[$imgpost]['tmp_name'], $url_link);
	$imagen = new SimpleImage();
	$imagen->load($url_link);
	$ancho = $imagen->getWidth();
	$alto  = $imagen->getHeight();
	if($ancho > $limitew || $alto > $limiteh){ // si el ancho o el alto son mayores a los especificados
		if($alto > $ancho){ // si el alto es mayor al ancho
			$imagen->resizeToHeight($limiteh); // llevamos la imagen al alto
		}else{
			$imagen->resizeToWidth($limitew); // sinó al ancho
		}
	}else{
		$imagen->resize($ancho,$alto);
	}
	$imagen->save($url_link);
}
?>