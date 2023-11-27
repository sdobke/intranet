<?PHP
include("inc/pictureclass.php");
$modo_prueba = config('prueba'); //0 es funcional, 1 es prueba
//$modo_prueba = 1; //0 es funcional, 1 es prueba
function generarTarjeta($tipo,$id,$anios,$empresa=1){
	if($tipo == 1){$empresa = 1;}
	//$anios = 2;
	$tipo_tar     = ($tipo == 0) ? "cumple" : "aniver";
	$archivo_base = "cron/img-tarjetas/tarjeta_".$tipo_tar."_".$empresa.".jpg";
	echo $archivo_base;
	$font = 'cron/img-tarjetas/'.$tipo_tar.'.ttf';
	$image = imagecreatefromjpeg($archivo_base);
	if($tipo == 1){ // aniversario
		$fontcolor = imagecolorallocate($image, 0xCC, 0x11, 0x11);
		$fontsize = 13;
		$valorx = ($anios < 10) ? 257 : 252;
		imagettftext($image, $fontsize, 0, $valorx, 210, $fontcolor, $font, $anios);
	}
	imagejpeg($image,"tempimg/".$tipo_tar.$id.".jpg",90);
	imagedestroy($image);
}
function obtenerEdad($fechanacimiento){ // para el formato 2010-07-23
	$fechabase = explode("-", $fechanacimiento);
	$dianac = $fechabase[2];
	$mesnac = $fechabase[1];
	$anyonac = $fechabase[0];
	
	if ( ($mesnac < date("m")) || (($dianac <= date("d")) && ($mesnac==date("m"))) )
	{
	//Si el �ltimo cumplea�os ya pas� este a�o
	$edad = date("Y") - $anyonac;
	}
	else
	{
	//Si el �ltimo cumplea�os todav�a no pas� le restamos 1 a la edad
	$edad= date("Y") - $anyonac - 1 ;
	}
	return $edad;
}
$mail_adm = config("email");
$hoy = date('Y-m');
$tipo    = 0; // tipo 0 es cumplea�os y tipo 1 es aniversario
while ($tipo <= 1){
	
	if($tipo == 0){ // cumplea�os
		$campo_tabla  = "fechanac";
		$dir_archivo  = "tempimg/cumple";
		$campo_confir = "confirmado";
		
	}else{ // aniversario
		$campo_tabla = "fechaing";
		$dir_archivo = "tempimg/aniver";
		$campo_confir = "confirmani";
	}
	$sql = "SELECT * FROM ".$_SESSION['prefijo']."empleados WHERE MONTH(".$campo_tabla.") = MONTH(CURDATE()) AND DAY(".$campo_tabla.") = DAY(CURDATE()) AND empresa < 3 AND ".$campo_confir." = 1";
	//echo '<br />'.$sql;
	$res = fullQuery($sql);
	
	while($row = mysqli_fetch_array($res)){
		$id       = $row['id'];
		$link_imagen = $dir_archivo.$id.".jpg";
		$nombre   = $row['nombre'];
		$apelli   = $row['apellido'];
		$email    = ($modo_prueba == 0) ? $row['email'] : $mail_adm; // test
		//$email    = ($modo_prueba == 0) ? $row['email'] : 'sdobke@gmail.com'; // test
		$edad     = obtenerEdad($row[$campo_tabla]);
		$empresa  = $row['empresa'];
		$para     = $nombre." ".$apelli." <".$email.">";
		//echo '<br /><br />para: '.$para;
		generarTarjeta($tipo,$id,$edad,$empresa);
		
		$asunto   = "Muchas felicidades ".ucwords(strtolower($nombre))."!";
		$mensaje = ($modo_prueba == 0) ? '' : 'MENSAJE DE PRUEBA<br />'; // test
		// PRUEBAS
		if($modo_prueba == 1){
			echo '<br />';
			echo $para;
			echo '<br />';
			echo $asunto;
			echo '<br />';	
			echo $mensaje.'<img src="'.$link_imagen.'" />';
			echo $link_imagen;
		}else{
		
			$bildmail = new bildmail();
			$bildmail->from("Comunicaciones Internas",$mail_adm);
			$bildmail->to($email,$nombre." ".$apelli);
			$bildmail->subject($asunto);
			$bildmail->settext('<div align="center">'.$mensaje.$bildmail->setbild($link_imagen).'</div>');
			$bildmail->send();
			
			$sql_desconf = "UPDATE ".$_SESSION['prefijo']."empleados SET ".$campo_confir." = 0 WHERE id = ".$id;
			$res_desconf = fullQuery($sql_desconf);
		}
	}
	$tipo++;
}
?>