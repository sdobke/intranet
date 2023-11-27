<?PHP require_once("../../../cnfg/config.php");?>
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
		new uvumiCropper('imagen',{
		onComplete:function(top,left,width,height){
		$('input_top').set('value', top);
		$('input_left').set('value', left);
		$('input_width').set('value', width);
		$('input_height').set('value', height);
		},
		preview:true,
		coordinatesOpacity:1,
		keepRatio:true,
		mini:{
			x:63,
			y:63
		}
		});
		function darOK(){
			window.opener.document.getElementById('habilita_envio').value = 'si';
			window.opener.document.getElementById('result').innerHTML="<br /><span class='del'>Recuerde hacer click en 'modificar' para guardar los cambios realizados.</span>";
		}
	</script>
</head>
<body>
	<div id="main">
		<div>
        <form id="myForm" action="save-thumb-bk.php" method="post" >
        <table><tr><td>
			<p>Doble click para abarcar el m&aacute;ximo posible
<?PHP
			$id = $_GET['id'];
			$dir_ppal = "../../../bk/";
			$ori_ppal = $dir_ppal."imagen_".$id.".jpg";
			$des_ppal = $dir_ppal."imagen_".$id.".jpg";
			$tmp_ppal = $dir_ppal."temp.jpg";
			copy ($ori_ppal, $tmp_ppal);
?>
   			<img id="imagen" src="<?PHP echo $ori_ppal;?>" alt="recorte"/>
				<input name="filename" id="filename" type="hidden" value="<?PHP echo $tmp_ppal;?>" />
                <input name="destino" id="destino" type="hidden" value="<?PHP echo $des_ppal;?>" />
                <input name="images_path" id="images_path" value="<?PHP echo $dir_ppal;?>" type="hidden"/>
                <input id="input_top" name="top" type="hidden" />
                <input id="input_left" name="left" type="hidden" />
                <input id="input_width" name="width" type="hidden" />
                <input id="input_height" name="height" type="hidden" />
                <input id="max_width" name="max_width" type="hidden" value="200" />
                <input id="max_height" name="max_height" type="hidden" value="200" />
                
			</p>
        </td><td>
        <button type="submit" onclick="javascript:darOK();"> Recortar </button>
        </td></tr></table>
        </form>
        </div>
	</div>
</body>
</html>