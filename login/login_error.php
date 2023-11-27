<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<title><?PHP echo $cliente;?> Intranet | Comentarios</title>
<?PHP include ("head.php");?>
</head>
<body onload="show5()">    <?PHP include ("header.php");?>
		<div id="main-wrapper">
		<?PHP include ("menu.php");?><div id="content-wrapper">
                <!--[if lt IE 7]>
                    <table><tr valign="top">
                    	<td>
                <![endif]-->
				<?PHP include ("col_izq.php");?>
                <!--[if lt IE 7]>
	            	</td>
                    <td>
				<![endif]-->
				<div id="right-wrapper">
                    <!--[if lt IE 7]>
                        <table><tr valign="top"><td colspan="2">
                    <![endif]-->
					<?PHP include ("top.php");?>
                    <!--[if lt IE 7]>
                        </td></tr>
                        <tr valign="top"><td>
                    <![endif]-->
					<div id="main-content">
					<div id="novedades">
						<div id="novedades-header"><img src="img/titulos/comentarios.gif" alt="Comentarios" /></div>
                        <br />
                        <div align="center">
							<?PHP 
							$usuario = $_GET['usr'];
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
							
							$mensaje = "--$mime_boundary\n";
							$mensaje .= "Content-Type: text/html; charset=iso-8859-1\n";
							$mensaje .= "Content-Transfer-Encoding: 8bit\n\n";
							$mensaje .= "<html><body>";
							
							$mensaje .= $texto;
							
							$mensaje .= "</body></html>\n";
							$mensaje .= "--$mime_boundary--\n\n";
							
							if (@mail ($para,$asunto,$mensaje,$headers)){
								$mensaje_error = "Se envi&oacute; un aviso al administrador del sistema.";
							}else{
								$mensaje_error = "Error enviando el aviso al administrador del sistema.<br />Por favor contacte al secto Recursos Humanos.";
							}
                            ?>
                            <div class="mod-b-fecha">
								<?PHP echo $mensaje_error;?>
                            </div>
                            <br /><br />
                        </div>
					</div>
                </div>
                    <!--[if lt IE 7]>
	            		</td>
                    	<td>
					<![endif]-->
                    <?PHP include ("col_der.php");?>
                    <!--[if lt IE 7]>
	            		</td>
                    	</tr></table>
					<![endif]-->
				</div>
				<!--[if lt IE 7]>
	            		</td>
                    </tr></table>
				<![endif]-->
			</div>
		<?PHP include "footer.php";?>
	</div>
</body>
</html>