<?PHP
if($debug == 1){echo '<br>carga de mailer';}
require_once('inc/phpmailer/PHPMailerAutoload.php');
$modo_prueba = config('prueba'); //0 es funcional, 1 es prueba
//$modo_prueba = 1; //0 es funcional, 1 es prueba
error_reporting(E_ALL);
function generarTarjeta($tipo,$id,$anios,$empresa=1){
	if($tipo == 1){$empresa = 1;}
	//$anios = 2;
	$tipo_tar     = ($tipo == 0) ? "cumple" : "aniver";
	$archivo_base = "cron/img-tarjetas/tarjeta_".$tipo_tar."_".$empresa.".jpg";
	echo '<br>Archivo Base: '.$archivo_base;
	$font = 'cron/img-tarjetas/'.$tipo_tar.'.ttf';
	$image = imagecreatefromjpeg($archivo_base);
	if($tipo == 1){ // aniversario
		$fontcolor = imagecolorallocate($image, 0xCC, 0x11, 0x11);
		$fontsize = 13;
		$valorx = ($anios < 10) ? 257 : 252;
		imagettftext($image, $fontsize, 0, $valorx, 210, $fontcolor, $font, $anios);
	}
	if(imagejpeg($image,"tempimg/".$tipo_tar.$id.".jpg",90)){
		echo "<br />Guardado archivo: tempimg/".$tipo_tar.$id.".jpg";
	}else{
		echo "<br />Error guardando: tempimg/".$tipo_tar.$id.".jpg";
	}
	imagedestroy($image);
}
function obtenerEdad($fechanacimiento){ // para el formato 2010-07-23
	$fechabase = explode("-", $fechanacimiento);
	$dianac = $fechabase[2];
	$mesnac = $fechabase[1];
	$anyonac = $fechabase[0];
	
	if ( ($mesnac < date("m")) || (($dianac <= date("d")) && ($mesnac==date("m"))) ){//Si el �ltimo cumplea�os ya pas� este a�o
		$edad = date("Y") - $anyonac;
	}else{ //Si el �ltimo cumplea�os todav�a no pas� le restamos 1 a la edad
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
	if($debug == 1){echo '<br>Tarjeta: '.$campo_tabla;}
	$sql = "SELECT * FROM ".$_SESSION['prefijo']."empleados WHERE MONTH(".$campo_tabla.") = MONTH(CURDATE()) AND DAY(".$campo_tabla.") = DAY(CURDATE()) AND empresa < 3 AND ".$campo_confir." = 1";
	
	// --------------
	// PRUEBA: borrar
	// --------------
	
	//$sql = "SELECT * FROM ".$_SESSION['prefijo']."empleados WHERE id = 351";
	
	// --------------
	// FIN PRUEBA: borrar
	// --------------
	
	
	$res = fullQuery($sql);
	$conta = mysqli_num_rows($res);
	// --------------
	// PRUEBA: borrar
	// --------------
	//$conta = 0; // QUITAR ESTA LINEA
	// --------------
	// FIN PRUEBA: borrar
	// --------------
	if($conta > 0){
		while($row = mysqli_fetch_array($res)){
			$id       = $row['id'];
			$link_imagen = $dir_archivo.$id.".jpg";
			$nombre   = $row['nombre'];
			$apelli   = $row['apellido'];
			$email    = ($modo_prueba == 0) ? $row['email'] : $mail_adm; // test
			//$email    = ($modo_prueba == 0) ? $row['email'] : 'sdobke@gmail.com'; // test
			echo '3';
			$edad     = obtenerEdad($row[$campo_tabla]);
			$empresa  = $row['empresa'];
	
			$para     = $nombre." ".$apelli." <".$email.">";
			echo '<br /><br />para: '.$para;
			generarTarjeta($tipo,$id,$edad,$empresa);
			$asunto   = "Muchas felicidades ".ucwords(strtolower($nombre))."!";
	
			$mensaje = ($modo_prueba == 0) ? '<img src="cid:header" />' : 'MENSAJE DE PRUEBA<br /><img src="cid:header" />'; // test
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
				$mail = new PHPMailer;
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->SMTPDebug = 1;
				$mail->Debugoutput = 'html';
				$test = $modo_prueba;
				if($test == 0){
					$mail->Host       = config("mailhost"); // SMTP server
					//$mail->SetFrom('dispositivo@alsea.com.ar', 'Comunicaciones Internas');
					$mail->SetFrom(config("email"), 'Comunicaciones Internas');
				}else{
					$mail->SMTPAuth   = true;
					$mail->Host       = config("mailhost"); // SMTP server
					$mail->Username   = config("mailuser"); // SMTP account username
					$mail->Password   = config("mailpass"); // SMTP account password	
				}
				$mail->Port       = 25;             // set the SMTP port
				$mail->AddAddress($email, $nombre." ".$apelli);
				$mail->AddReplyTo(config("email"), 'Comunicaciones Internas');
				$mail->Subject = $asunto;
				$mail->AddEmbeddedImage($link_imagen, "header", "header");
				$body = $mensaje;
				$mail->MsgHTML($body);
				if (!$mail->send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					$sql_desconf = "UPDATE ".$_SESSION['prefijo']."empleados SET ".$campo_confir." = 0 WHERE id = ".$id;			
					$res_desconf = fullQuery($sql_desconf);
					echo "Message sent!";
				}
			}
		}
	}
	$tipo++;
}
?>