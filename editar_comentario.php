<?php include "cnfg/config.php"; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head.php"); ?>		
		<style>
			/*
			 * Ajusto el ancho para el popup
			*/
			#middle {
				width: 650px;
			}
			.middle_inner {
				width: 620px;
				min-height: 330px;
			}
			.footer_inner {
				width: 628px;
			}
		</style>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="index.php"><img src="img_new/logo.png" /></a>
					</div>
					<?php //include_once 'login.php'; ?>					
				</div>
				<?php //include_once 'menu.php'; ?>
				<div class="container">
					<div class="col_ppal left">						
						<div class="cabecera_nota mb20 pb20 brd-b">

							<h3>
								<span class="tup">
									<a href="javascript:void(0);">Editar Comentario</a>
								</span>
							</h3>

							<?php
							
//echo '<pre>';
//print_r($_REQUEST);
//print_r($_SESSION);
//echo '</pre>';

							if (isset($_REQUEST["sendEdit"])) {
								$comentario = $_REQUEST["comentario"];
								$update = "UPDATE intranet_comentarios
											SET comentario = '$comentario'				
											WHERE comentario_id =" . $_REQUEST['comentario_id'];
								$res = fullQuery($update);
								?>
								<div>
									<p><strong>El comentario fue modificado!</strong></p>
									<p>
										<input type="button" style="border: 1px solid gray;" onclick="window.opener.location = window.opener.location; return window.close();" value="Cerrar" />
									</p>
									<hr />
								</div>
								<?php
							}
							if (isset($_SESSION['usrfrontend']) && isset($_REQUEST['comentario_id'])) {
								$comentario_id = $_REQUEST['comentario_id'];
								$sql = "SELECT *
										FROM intranet_comentarios C, intranet_empleados E
										WHERE C.comentario_id = $comentario_id
										AND C.usuario_id = E.id
										ORDER BY fecha DESC
										";
								$res = fullQuery($sql);
								$row = mysqli_fetch_object($res);
								$id = $row->id;
								$tipo = $row->tipo;
								$comentario = $row->comentario;
								?>
								<div>
									<form name="comentar" id="comentar" action="" method="post">
										<input type="hidden" name="id" value="<?php echo $id; ?>" />
										<input type="hidden" name="tipo" value="<?php echo $tipo; ?>" />
										<label for="comentario">Modificar comentario: </label><br />
										<textarea id="comentario" name="comentario" cols="50" rows="5"  style="border: 1px solid gray;"><?php echo nl2br($comentario); ?></textarea><br />
										<input type="submit" name="sendEdit" value="Comentar!"  style="border: 1px solid gray;" />
									</form>
									<hr />
								</div>
							<?php } else { ?>
								<div style="border: 1px solid red;">Para poder comentar esta seccion debes estar logueado.</div>
							<?php }
							?>
						</div>
					</div>
					
				</div>
				<?php //include("col_der.php"); ?>
			</div>
			<div class="clr"></div>
		</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>