<?php
if ($_GET['error'] == 1) {
	$usuario = $_GET['usr'];
	$sql_usr = "SELECT * FROM intranet_empleados WHERE usuario = '$usuario'";
	$res_usr = fullQuery($sql_usr);
	$con_usr = mysqli_num_rows($res_usr);
	if ($con_usr > 0) {
		$row_usr = mysqli_fetch_array($res_usr);
		$nombre_completo = $row_usr['apellido'] . ', ' . $row_usr['nombre'];
		$texto = '<br />El usuario <strong>' . $nombre_completo . '</strong> intent&oacute; ingresar con el usuario: <strong>' . $usuario . '</strong> sin &eacute;xito. Por favor compruebe con sistemas que el nombre de usuario sea el mismo en LDAP y que el usuario exista en intranet.';
	} else {
		$texto = '<br />El usuario <strong>' . $usuario . '</strong> intent&oacute; ingresar pero ese nombre de usuario no existe en intranet. Por favor compruebe con sistemas que el nombre de usuario exista y sea el mismo en LDAP';
	}

	try {
		//Server settings
		$mail->isSMTP();
		//$mail->SMTPDebug  = 2;
		//$mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;
		//echo config("mailhost").' | '.config("mailuser").' | '.config("mailpass");
		$mail->Host       = config("mailhost");
		$mail->SMTPAuth   = true;
		$mail->Username   = config("mailuser");
		$mail->Password   = config("mailpass");
		$mail->SMTPSecure = true;
		$mail->SMTPAutoTLS = false;

		$mail->Port       = config("mailport");
		$mail->setFrom('no-responder@intranet.com.ar', 'Intranet Bercovich');
		$mail->addAddress($sendMail, $sendNomApe);
		//$mail->addAddress('sdobke@gmail.com', 'Sergio Dobkevicius');
		$mail->addReplyTo(config("mailfrom"), config("empresa"));

		//Content
		$mail->isHTML(true);
		$mail->CharSet = 'UTF-8';

		$mail->Subject = 'Intranet Corporativa Bercovich - Ingreso de usuario erroneo.';
		$mail->Body    = '<div style="width:100px;border-bottom:1px dotted red"><img src="' . $site_link . '/cliente/img/header_mail.jpg" /></div>
		<div style="padding-top:50px">' . $texto . '</div>';
		$mail->AltBody = $texto;
		$mail->send();
		$msg_ok = "Enviamos un correo al administrador del sistema.";
	} catch (Exception $e) {
		$msg_error = "Hubo un problema al intentar enviar el correo. Se guardó el error en el registro.";
		guardaLog("Error de mail. Mailer Error: {$mail->ErrorInfo}");
	}
}
