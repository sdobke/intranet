<?PHP
require_once("../../../cnfg/config.php");
require_once("../../../inc/funciones.php");
$thmbancho = config('destacadaw');
$thmbalto  = config('destacadah');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Recorte de imagen</title>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<script type="text/javascript" src="js/mootools-for-crop.js"> </script>
	<script type="text/javascript" src="js/UvumiCrop-compressed.js"> </script>

	<link rel="stylesheet" type="text/css" media="screen" href="css/uvumi-crop.css" />
	<style type="text/css">
	</style>
	<script type="text/javascript">
		new uvumiCropper('imagen', {
			onComplete: function(top, left, width, height) {
				$('input_top').set('value', top);
				$('input_left').set('value', left);
				$('input_width').set('value', width);
				$('input_height').set('value', height);
			},
			preview: true,
			coordinatesOpacity: 1,
			keepRatio: true,
			mini: {
				x: <?PHP echo $thmbancho; ?>,
				y: <?PHP echo $thmbalto; ?>
			}
		});

		function darOK() {
			window.opener.document.getElementById('habilita_envio').value = 'si';
			window.opener.document.getElementById('result').innerHTML = "<br /><span class='del'>Recuerde hacer click en 'modificar' para guardar los cambios realizados.</span>";
		}
	</script>
</head>

<body>
	<div id="main">
		<div>
			<form id="myForm" action="save-thumb.php" method="post">
				<table>
					<tr>
						<td align="center">
							<button type="submit" onclick="javascript:darOK();"> Recortar </button>
						</td>
					</tr>
					<tr>
						<td>
							<p>Doble click para abarcar el m&aacute;ximo posible<br />
								<?PHP
								$query    = "SELECT link, item FROM " . $_SESSION['prefijo'] . "fotos WHERE id = " . $_GET['id'];
								$resul    = fullQuery($query);
								$row_ppal = mysqli_fetch_array($resul);

								$ori_ppal = "../../../" . $row_ppal['link'];
								$dir_ppal = explode("imagen", $ori_ppal, -1);
								$dir_ppal = end($dir_ppal);
								$des_ppal = $dir_ppal . "dest.jpg";
								$tmp_ppal = $dir_ppal . "temp.jpg";
								copy($ori_ppal, $tmp_ppal);
								?>
								<img id="imagen" src="<?PHP echo $ori_ppal; ?>" alt="recorte" style="width:100%" />
								<input name="filename" id="filename" type="hidden" value="<?PHP echo $tmp_ppal; ?>" />
								<input name="destino" id="destino" type="hidden" value="<?PHP echo $des_ppal; ?>" />
								<input name="images_path" id="images_path" value="<?PHP echo $dir_ppal; ?>" type="hidden" />
								<input id="input_top" name="top" type="hidden" />
								<input id="input_left" name="left" type="hidden" />
								<input id="input_width" name="width" type="hidden" />
								<input id="input_height" name="height" type="hidden" />
								<input id="max_width" name="max_width" type="hidden" value="<?PHP echo $thmbancho; ?>" />
								<input id="max_height" name="max_height" type="hidden" value="<?PHP echo $thmbalto; ?>" />

							</p>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>

</html>