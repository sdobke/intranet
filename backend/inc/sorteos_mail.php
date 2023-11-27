<?PHP 

$tipo   = 19;
$nombre = obtenerNombre($tipo);
function reemplazaTextosMail($texto,$nombre,$sorteo){
	$ori = array('#nombre#','#sorteo#');
	$des = array($nombre,$sorteo);
	return str_replace($ori,$des,reemplazo_comillas($texto));
}
$mensaje_sistema = '';
$id = (isset($_POST['id'])) ? $_POST['id'] : 1;
$sql = "SELECT * FROM ".$_SESSION['prefijo']."sorteos WHERE id = ".$id;
$res = fullQuery($sql);
$noticia = mysqli_fetch_array($res);
$sql_mail = "SELECT valor FROM ".$_SESSION['prefijo']."config WHERE parametro = 'email'";
$res_mail = fullQuery($sql_mail) or die($error_sql = mysqli_error());
$row_mail = mysqli_fetch_array($res_mail);
$mail_adm = $row_mail['valor'];
$titulo  = $noticia['titulo'];
$ganadores = $_POST['ganadores'];
$id_ganadores = explode("-", $ganadores);
foreach ($id_ganadores as $key => $value) {
	$id_ganador = $value;
	if($id_ganador > 0){
		$id_guar  = nuevoID('sorteos_ganadores');
		$sql_guar = "INSERT INTO ".$_SESSION['prefijo']."sorteos_ganadores (id, sorteo, empleado) VALUES ($id_guar, $id, $id_ganador)";
		$res_guar = fullQuery($sql_guar);
		$sql_gana = "SELECT * FROM ".$_SESSION['prefijo']."empleados WHERE id = $id_ganador";
		$res_gana = fullQuery($sql_gana);
		$row_gana = mysqli_fetch_array($res_gana);
		$ganador_id       = $row_gana['id'];
		$ganador_mail     = ($modo_prueba == 0) ? $row['email'] : $mail_adm;
		$ganador_nombre   = $row_gana['nombre'];
		$ganador_apellido = $row_gana['apellido'];
		$texto     		  = reemplazaTextosMail($_POST['texto'],$ganador_nombre,$titulo);
		$asunto    		  = reemplazaTextosMail($_POST['motivo'],$ganador_nombre,$titulo);
		$para 			  = $ganador_nombre." ".$ganador_apellido." <".$ganador_mail.">";
		$sql_mail = "SELECT valor FROM ".$_SESSION['prefijo']."config WHERE parametro = 'email'";
		$res_mail = fullQuery($sql_mail) or die($error_sql = mysqli_error());
		$row_mail = mysqli_fetch_array($res_mail);
		$mail_de  = $row_mail['valor'];
		$mime_boundary = "ALSEA-ARG-".md5(time());
		$headers =  "From: Intranet Alsea Argentina <".$mail_de.">\n";
		$headers .= "Reply-To: Intranet Alsea Argentina <".$mail_de.">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		$mensaje = "";
		$mensaje .= "--$mime_boundary\n";
		$mensaje .= "Content-Type: text/html; charset=iso-8859-1\n";
		$mensaje .= "Content-Transfer-Encoding: 8bit\n\n";
		$mensaje .= "<html><body style=\"font-family:Arial; font-size:10px;\">\n";
	
		$mensaje .= $texto;
			
		$mensaje .= "</body></html>\n";
		$mensaje .= "--$mime_boundary--\n\n";
		if (!mail ($para,$asunto,$mensaje,$headers)){
			$mensaje_sistema.= "<br />Error enviando el e-mail a:".$para."<br />";
		}
		//echo $mensaje;
	}
}
$mensaje_sistema = ($mensaje_sistema == '') ? "Mensaje/s enviado/s correctamente." : $mensaje_sistema;
$tipo   = 19;
$nombre = obtenerNombre($tipo);
// desactivar el sorteo
$sql2 = "UPDATE ".$_SESSION['prefijo'].$nombre." SET activo = 0 WHERE id = ".$id;
$res2 = fullQuery($sql2);
$sql3 = "DELETE FROM ".$_SESSION['prefijo']."participantes WHERE tipoconcurso = ".$tipo." AND concurso = ".$id;
$res3 = fullQuery($sql3);
?>
      
		<h1>Sorteo: <?PHP echo $titulo;?></h1>
        <div align="center">
        	<h3>Env&iacute;o de mensajes a ganadores</h3>
			<p><?PHP echo $mensaje_sistema;?></p>
        </div>
  </div>
	<div style="clear:both;"></div>
</div>