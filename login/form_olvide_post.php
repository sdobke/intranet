<?php
if (isset($_POST['email_olvido']) || isset($_POST['email_forzar'])) {
  if (isset($_POST['email_olvido'])) {
    $postMail = $_POST['email_olvido'];
    $olv = 1;
  } else {
    $postMail = $_POST['email_forzar'];
    $olv = 0;
  }
  if (strpos($postMail, '@') !== false) {
    $usuario_lost = dbVarClean($postMail);
    $user_lost = $_SESSION['conexion']->prepare('SELECT id,email,nombre,apellido FROM intranet_empleados WHERE email = ? AND del = 0 AND activo = 1');
    $user_lost->bind_param('s', $usuario_lost);
    $user_lost->execute();
    $l_res = $user_lost->get_result();
    $l_can = $l_res->num_rows;
    if ($l_can == 1) {
      $l_row = $l_res->fetch_assoc();
    } else { // Si no aparece el mail
      if ($olv == 1) { // Si es un usuario que lo olvidó
        $msg_error = "No encontramos ese email.";
      } else {
        $usuario_lost = dbVarClean($_POST['usuario']);
        $user_lost = $_SESSION['conexion']->prepare('SELECT id,email,nombre,apellido FROM intranet_empleados WHERE usuario = ? AND del = 0 AND activo = 1');
        $user_lost->bind_param('s', $usuario_lost);
        $user_lost->execute();
        $l_res = $user_lost->get_result();
        $l_can = $l_res->num_rows;
        if ($l_can == 1) {
          $l_row = $l_res->fetch_assoc();
          if ($l_row['email'] == '') {
            $numail = dbVarClean($_POST['email_forzar']);
            $newmail = $_SESSION['conexion']->prepare('UPDATE intranet_empleados SET email = ? WHERE id = ?');
            $newmail->bind_param('si', $numail, $l_row['id']);
            $newmail->execute();
            $sendMail = $numail;
          }
        }
      }
    }
    if ($l_can == 1) {
      $idveri = $l_row['id'];
      $sendMail = $l_row['email'];
      $sendNomApe = $l_row['nombre'] . ' ' . $l_row['apellido'];

      //$mail = new PHPMailer(true);
      try {
        //Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        //echo config("mailhost").' | '.config("mailuser").' | '.config("mailpass").' | '.config("mailport");
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = true;
        $mail->Host       = config("mailhost");
        $mail->Port = config("mailport");
        $mail->isHTML(true);
        $mail->Username   = config("mailuser");
        $mail->Password   = config("mailpass");
        $mail->SMTPAutoTLS = true;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->setFrom(config("mailfrom"), config("empresa"));
        $mail->addAddress($sendMail, $sendNomApe);
        //$mail->addAddress('sdobke@gmail.com', 'Sergio Dobkevicius');
        //$mail->addAddress('ellen@example.com');
        $mail->addReplyTo(config("mailfrom"), config("empresa"));
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->CharSet = 'UTF-8';
        $codigo = hash('sha512', 'IntraPass' . $idveri . date("Y-d-m-h-s-v"));
        $sqlupd = 'UPDATE intranet_empleados SET verificador = "' . $codigo . '" WHERE id = ' . $idveri;
        $resupd = fullQuery($sqlupd);
        //$mail->AddEmbeddedImage($site_link . '/cliente/img/header_mail.jpg', 'header', 'header_mail.jpg ');
        $mail->Subject = 'Intranet Corporativa Bercovich - Solicitud de reinicio de contraseña.';
        $mensaje   = 'Este correo llegó porque solicitó un reinicio de su contraseña. Si no fue así, ignore el mensaje.<br>Si desea reiniciar su contraseña, por favor siga <a href="' . $site_link . '/confirmar/' . $codigo . '">este link.</a>';
        $mail->Body    = '<div style="width:100px;border-bottom:1px dotted red"><img src="' . $site_link . '/cliente/img/header_mail.jpg" /></div>
          <div style="padding-top:50px">' . $mensaje . '</div>';
          //echo '<br>'.$mensaje;
        $mail->AltBody = $mensaje;
        $mail->send();
        $msg_ok = "Enviamos un correo a su cuenta para realizar el cambio de contraseña.";
      } catch (Exception $e) {
        $msg_error = "Hubo un problema al intentar enviar el correo. Se guardó el error en el registro.";
        guardaLog("Error de mail. Mailer Error: {$mail->ErrorInfo}");
      }
    }
  } else {
    $msg_error = "Email inválido.";
  }
}
