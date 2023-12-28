<?PHP
//$sql_confirmar = "SELECT * FROM ".$_SESSION['prefijo'] . $nombretab . " WHERE del = 0 ";
$debug = 0; // 0 envía, 1 envía y muestra log, 2 envía solamente a cuenta testing, 3 no envía
$site_link = 'https://' . $server;
$sql_areas = "SELECT part FROM intranet_link WHERE item = " . $id . " AND tipo = " . $tipo;
$res_areas = fullQuery($sql_areas);
if (mysqli_num_rows($res_areas) == 1) {
  $row_areas = mysqli_fetch_assoc($res_areas);
  if ($row_areas['part'] != '') {
    $sql_areaid = '';
    $cont = 0;
    $areas_ok = explode(',', $row_areas['part']);
    foreach ($areas_ok as $area_id) {
      if ($cont > 0) {
        $sql_areaid .= ',';
      }
      $sql_areaid .= $area_id;
      $cont++;
    }
    $sql_mail = "
              SELECT emp.nombre, emp.apellido, emp.email
              FROM intranet_empleados emp 
              INNER JOIN intranet_empleados_areas iea ON iea.empleado = emp.id
              WHERE 1
              
              AND iea.area IN (" . $sql_areaid . ")
              AND email != '' AND email != '-' AND emp.activo = 1 AND emp.del = 0 GROUP BY emp.id ORDER BY emp.id
              ";
    if ($debug > 0) {
      echo $sql_mail;
    }
    $res_mail = fullQuery($sql_mail);
    $debug_users_list = '';
    $cantmails = mysqli_num_rows($res_mail);
    if ($cantmails > 0) {
      $tandas = $cantmails / 95;
      for ($i = 0; $i <= $tandas; $i++) {
        $offset = $i * 95;
        $sql_mail_tanda = $sql_mail . ' LIMIT ' . $offset . ',95';
        if ($debug > 0) {
          echo $sql_mail_tanda;
        }
        $res_mail_tanda = fullQuery($sql_mail_tanda);
        $bcc = array();

        while ($l_row = mysqli_fetch_array($res_mail_tanda)) {
          $sendMail = $l_row['email'];
          $sendNomApe = txtcod($l_row['nombre']) . ' ' . txtcod($l_row['apellido']);
          $bcc[] = ['name' => $sendNomApe, 'email' => $sendMail];
          $debug_users_list .= ',' . $sendNomApe . '(' . $sendMail . ')';
        }
        $destin = array();
        $destin[] = ['name' => 'Empleados Bercovich', 'email' => 'intranet@bercovich.com.ar'];
        if ($debug > 0) {
          echo '<pre>';
          print_r($bcc);
          echo '</pre>';
        }


        if($debug == 2){
          $destin = array();
          $destin[] = ['name' => 'Sergio Gmail', 'email' => 'sdobke@gmail.com'];
        }
        
        //$destin[] = ['name' => 'Sergio Dobke', 'email' => 'sergio@dobke.com.ar'];
        //$site_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        
        //$mail = new PHPMailer(true);

        //Server settings
        // Brevo ex SendinBlue
        $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $credentials);
        switch ($tipo) {
          case 7: // Novedades
          case 20: // Beneficios
            $backend_link = $site_link . '/nota.php?id=' . $id . '&tipo=' . $tipo;
            break;
          case 3: // Docs
            $backend_link = $site_link . '/docs.php';
            break;
        }
        guardaLog(date("Y-m-d H:i") . " | Intento de envio: " . txtcod($titulonota) . " | Area: ".$sql_areaid." | Destinatarios: " . $debug_users_list . "
        ", 'log_mail.txt');
        //$debug = 1;
        $tipotx = obtenerDato('detalle', 'tablas', $tipo);
        $mensaje   = 'Este correo llegó porque hay nueva información que se le solicita acceder. <strong>' . $tipotx . ': ' . txtcod($titulonota) . '</strong><br>Por favor siga <a href="' . $backend_link . '">este link</a>.';
        $body    = '<div style="width:100px;border-bottom:1px dotted red"><img src="' . $site_link . '/cliente/img/header_mail.jpg" /></div>
      <div style="padding-top:50px">' . $mensaje . '</div>';
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
          'subject' => 'Intranet Corporativa Bercovich - Alerta desde la Intranet',
          'sender' => ['name' => 'Bercovich', 'email' => 'bercovich@mails.intranet.com.ar'],
          'replyTo' => ['name' => 'Bercovich', 'email' => 'bercovich@mails.intranet.com.ar'],
          'to' => $destin,
          'bcc' => $bcc,
          'htmlContent' => '<html><body>' . $body . '</body></html>'
        ]);

        try {
          if ($debug <= 2) {
            $result_sent = $apiInstance->sendTransacEmail($sendSmtpEmail);
            $datoslog = json_decode($result_sent, true);
            $datoslog = implode(' - ',$datoslog);
            guardaLog(date("Y-m-d H:i") . " | Error de mail. Mailer Error:". $datoslog, 'log_mail.txt');
          }
          if ($debug == 1) {
            print_r($result_sent);
          }
        } catch (Exception $e) {
          if ($debug > 0) {
            echo $e->getMessage(), PHP_EOL;
          }
          $error = "Hubo un problema al intentar enviar el correo. Se guardó el error en el registro.";
          guardaLog(date("Y-m-d H:i") . " | Error de mail. Mailer Error: ".$e->getMessage(), 'log_mail_err.txt');
        }
      } // End for


      /*
        
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
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = config("mailport");
        $mail->setFrom(config("mailfrom"), config("empresa"));
        $debug_users_list = '';
        if(isset($testing) && $testing == 1){
          $sendMail = 'sdobke@gmail.com';
          $sendNomApe = 'Sergio para Testing ';
          $mail->addAddress($sendMail, $sendNomApe);
        }else{
          while ($l_row = mysqli_fetch_array($res_mail)) {
            $sendMail = $l_row['email'];
            $sendNomApe = $l_row['nombre'] . ' ' . $l_row['apellido'];
            $mail->addAddress($sendMail, $sendNomApe);
            $debug_users_list.= ','.$sendNomApe.'('.$sendMail.')';
          }
        }
        //echo '<br>Enviar a '.$sendMail.' -> '.$sendNomApe;
        //$mail->addAddress('sdobke@gmail.com', 'Sergio Dobkevicius');
        //$mail->addAddress('ellen@example.com');
        $mail->addReplyTo(config("mailfrom"), config("empresa"));
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //$mail->AddEmbeddedImage($site_link . '/cliente/img/header_mail.jpg', 'header', 'header_mail.jpg ');
        $mail->Subject = 'Intranet Corporativa Bercovich - Alerta desde la Intranet';
        switch ($tipo) {
          case 7: // Novedades
          case 20: // Beneficios
            $backend_link = $site_link . '/nota.php?id='.$id.'&tipo='.$tipo;
            break;
          case 3: // Docs
            $backend_link = $site_link . '/docs.php';
            break;
        }
        //$debug = 1;
        $tipotx = obtenerDato('detalle', 'tablas', $tipo);
        $mensaje   = 'Este correo llegó porque hay nueva información que se le solicita acceder. <strong>' . $tipotx . ': ' . txtcod($titulonota) . '</strong><br>Por favor siga <a href="' . $backend_link . '">este link</a>.';
        $body    = '<div style="width:100px;border-bottom:1px dotted red"><img src="' . $site_link . '/cliente/img/header_mail.jpg" /></div>
    <div style="padding-top:50px">' . $mensaje . '</div>';
        //echo '<br>' . $mensaje;
        $mail->AltBody = $mensaje;
        if($debug == 0 || $testing == 1){
          $mail->send();
          $msg_ok = "Alerta enviada por correo.";
        }else{
          echo '<br>Modo debug.<br>'.$mensaje.' para usuarios: '.$debug_users_list;
        }
        
      } catch (Exception $e) {
        $error = "Hubo un problema al intentar enviar el correo. Se guardó el error en el registro.";
        guardaLog("Error de mail. Mailer Error: {$mail->ErrorInfo}");
      }
      */
    } // En if cantmails > 0
  }
}

// WhatsApp
$waToken = 'EAALTiu1eOE0BANouZAvNtDZAcTMZAdPmFLCUZBC7WOMTKKWxtuokWWy37vdxJj6vdnp2ao0RS7fjhSSOmoRxcV5SNjvrCYfMpCQNZC3VD47HuYUHgnVY5NVLLiQhH2rB0wA31yfwAHDyyDPmD3bvJD5CzPe69K6tgjF0NtgLjJIfMpVkld3RGeZBwWjOsKJRkWsR6EbKa7IAZDZD';
$waUser = '101211375981071';
$waApp = '795543594743885';
