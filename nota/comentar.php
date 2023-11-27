<script language="JavaScript" type="text/javascript">

function Abrir_ventana (pagina) {

	var opciones="toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450,top=85,left=140";

	window.open(pagina,"",opciones);

}

</script>

<?php

if(isset($id) && isset($tipo)){

	/*

	 * inserta nuevo comentario

	 */

	if(isset($_REQUEST["comentario"]) && trim($_REQUEST["comentario"]) != "" && isset($_SESSION['usrfrontend'])){

		$fecha = date("Y-m-d h:i:s");

		$comtid = nuevoID('comentarios');

		$insertComentario = "INSERT INTO ".$_SESSION['prefijo']."comentarios (id, usuario_id ,comentario ,item ,tipo, fecha)

							VALUES (".$comtid.",

								 '".$_SESSION['usrfrontend']."','".txtdeco($_REQUEST["comentario"])."', '{$id}', '{$tipo}', '{$fecha}'

							);";

		$resComentario = fullQuery($insertComentario);

		echo '<div style="font-size:14px;">Tu comentario fue guardado y espera aprobaci&oacute;n del administrador.</div>';

	}

	if(isset($_SESSION['usrfrontend'])){ ?>

		<div>

			<form name="comentar" id="comentar" action="" method="post">

				<input type="hidden" name="id" value="<?php echo $id; ?>" />

				<input type="hidden" name="tipo" value="<?php echo $tipo; ?>" />

				<label for="comentario"><strong>Dejanos tu comentario: </strong></label><br />

				<textarea class="form-control" id="comentario" name="comentario" rows="5"></textarea>

				<input type="submit" class="btn btn-primary" value="Comentar" />

			</form>

		</div>

	<?php }else{ ?>

		<div style="border: 1px solid red;">Para poder comentar esta sección tenés que ingresar con tu usuario.</div>

	<?php } 

}

?>