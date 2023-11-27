<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
include "login_init.php";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_empleos.php"); ?>
		
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
					<?php include_once '../login.php'; ?>					
				</div>
				<?php include_once '../menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Comentarios</div>
				<div class="container_inner">
					<div class="col_ppal right">
							<?PHP 
							$usuario = (isset($_GET['usr'])) ? $_GET['usr'] : 0;
							$sql_usr = "SELECT * FROM intranet_empleados WHERE usuario = '$usuario'";
							$res_usr = fullQuery($sql_usr);
							$con_usr = mysqli_num_rows($res_usr);
							if($con_usr > 0){
								$row_usr = mysqli_fetch_array($res_usr);
								$nombre_completo = $row_usr['apellido'].', '.$row_usr['nombre'];
								$texto = '<br />El usuario <strong>'.$nombre_completo.'</strong> intent&oacute; ingresar con el usuario: <strong>'.$usuario.'</strong> sin &eacute;xito. Por favor compruebe con sistemas que el nombre de usuario sea el mismo en LDAP y que el usuario exista en intranet.';
							}else{
								$texto = '<br />El usuario <strong>'.$usuario.'</strong> intent&oacute; ingresar pero ese nombre de usuario no existe en intranet. Por favor compruebe con sistemas que el nombre de usuario exista y sea el mismo en LDAP';
							}
							
							$asunto   = "Intranet: Ingreso de usuario erroneo.";
							$hoy      = date('Y-m-d');

							$emailfr  = cantidad('email');
								
							$para     = "Administrador de Intranet <".$emailfr.">";
							$mime_boundary = "ALSEA-ARG-".md5(time());
							$headers  = "From: Sistema intranet <info@alsea.com.ar>\n";
							$headers .= "Reply-To: Sistema intranet <info@alsea.com.ar>\n";
							$headers .= "MIME-Version: 1.0\n";
							$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
							$headers .= "X-Mailer: PHP/" . phpversion();
							if(!isset($mensaje))
								$mensaje = "";
							$mensaje .= "--$mime_boundary\n";
							$mensaje .= "Content-Type: text/html; charset=iso-8859-1\n";
							$mensaje .= "Content-Transfer-Encoding: 8bit\n\n";
							$mensaje .= "<html><body>";
							
							$mensaje .= $texto;
							
							$mensaje .= "</body></html>\n";
							$mensaje .= "--$mime_boundary--\n\n";
							
							if (@mail ($para,$asunto,$mensaje,$headers)){
								$mensaje_error = "Se envi&oacute; un aviso al administrador del sistema.";
								?>
								<div class="success_box">
									<?PHP echo $mensaje_error;?>
								</div>
								<?php
							}else{
								$mensaje_error = "Error enviando el aviso al administrador del sistema.<br />Por favor contacte al secto Recursos Humanos.";
								?>
								<div class="alert_box">
									<?PHP echo $mensaje_error;?>
								</div>
								<?php
							}
                            ?>
                            
                           
                         </div>
				<?php include 'col_izq.php'; ?>
			</div>
			<div class="clr"></div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>