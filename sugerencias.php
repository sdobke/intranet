<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

$tipo = 29;
agrega_acceso($tipo);
$respuesta = '';
$estilo_hidden = '';
if(isset($_POST['comentario'])){
	include("sugerencias_post.php");
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Sugerencias</title>
		<?PHP include ("head.php"); ?>
		<link href="css/campos.css" rel="stylesheet" type="text/css" />
		<link href="css/secciones.css" rel="stylesheet" type="text/css" />
		<link href="css/minisitios.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
			$(document).ready(function() {
				$('#anonima').change(function() {
					if($(this).is(":checked")) {
						$( "#ocultar" ).hide();
					}else{
						$( "#ocultar" ).show();
					}
				});
			});
		</script>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner"><?PHP include("header.php");?>
				<?php include_once 'menu.php'; ?>
				<div class="container">
					<div class="col_ppal left">
						<div class="hd-seccion">Sugerencias para la Intranet</div>												
						<?PHP
						$nombre = '';
						$email = '';
						if (isset($_SESSION['usrfrontend'])) {
							$sql = "SELECT * FROM intranet_empleados WHERE id = " . $_SESSION['usrfrontend'];
							$res = fullQuery($sql);
							$row = mysqli_fetch_array($res);
							$nombre = $row['nombre'] . ' ' . $row['apellido'];
							$email = $row['email'];
						} ?>
                        <?PHP if($respuesta != ''){
							echo '<div style="clear:both"></div><div class="info_box_mini">'.$respuesta.'</div>
							<br />
									<div align="center"><a href="'.$pre_url.'sugerencias.php" class="button"><span class="icon icon68"></span><span class="label">&iquest;Otra sugerencia?</span></a></div>
							';
						}else{?>
                            <div class="formularios left <?PHP echo $estilo_hidden;?>">
                                <form action="#" method="post" id="login_usr" name="login_usr">
                                    <div class="left w100 mb5 c444444 b">Enviar sugerencia an&oacute;nima: </div>
                                    <div class="left w100 mb15">
                                        <input type="checkbox" name="anonima" id="anonima" value="0" />
                                    </div>
                                    <div id="ocultar">
                                        <div class="left w100 mb5 c444444 b">Nombre: </div>
                                        <div class="left w100 mb15">
                                            <input class="input-text" type="text" name="nombre" value="<?PHP echo $nombre; ?>" style="width:600px" />
                                        </div>
                                        <div class="left w100 mb5 c444444 b">Email</div>
                                        <div class="left w100 mb15">
                                            <input class="input-text" type="text" name="email" value="<?PHP echo $email; ?>" style="width:600px" />
                                        </div>
                                    </div>
                                    <div class="left w100 mb5 c444444 b">Sugerencia: </div>
                                    <div class="left w100 mb15">
                                        <textarea name="comentario" id="comentario" cols="45" rows="5"></textarea>
                                    </div>
                                    <div class="left w100 brd-t pt10 ar">
                                        <a href="javascript:document.getElementById('login_usr').submit();" class="button"><span class="icon icon68"></span><span class="label">Enviar</span></a>
                                    </div>
                                </form>
                            </div>						
					<?PHP }?>
				</div>
				<?php include("col_der.php"); ?>
			</div>
			<div class="clr"></div>
		</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>