<?PHP
include ("../../cnfg/config.php");
include ("sechk.php");
include ("inc/inc_funciones_globales.php");
function valor_param($parametro){
	$query = fullQuery("SELECT valor FROM empleos_config where parametro = '$parametro'") or die(mysqli_error());
	$row = mysqli_fetch_array($query);
	$dif = $row['valor'];
	return $dif;
}

$error_msg = '';
if(isset($_GET['error'])){
	$error = $_GET['error'];
	switch ($error){
		case 1:
			$mensaje = 'Debe repetir su nueva password si es que desea cambiarla.';
			break;
		case 2:
			$mensaje = 'Debe ingresar una nueva password y repetirla si desea cambiar la anterior. Si no es as&iacute;, simplemente deje ambas en blanco.';
			break;
		case 3:
			$mensaje = 'No coincide su nueva password en la repetici&oacute;n.';
			break;
		case 4:
			$mensaje = 'Los valores deben estar entre 1 y 30.';
			break;
		case 5:
			$mensaje = 'Los valores deben ser num&eacute;ricos y no pueden exceder los 3000 pixels.';
		break;
		default:
			$mensaje = 'Cambios realizados correctamente.';
			break;
	}
	//if ($error > 0){echo '<p class="error"><b>Por favor corrija el siguiente error:</b></p>';}
	$error_msg = '<span class="error">'.$mensaje.'</span><br /><br />';
}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | Inicio</title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="contenedor">
	<div id="header">  </div>
    <?PHP include("menu.php");?>
	<div style="width:728px">
	    <?PHP echo $error_msg;?>
		<div id="formulario" style="width:920px; border:0px solid #e6e6e6; padding:10px">
	    	Por favor seleccione una opci&oacute;n del men&uacute;.
            <!--<form id="registerForm" name="registerForm" method="post" action="inicio_post.php">
                <table width="920" border="0" cellspacing="5" cellpadding="0">
                    <tr>
                        <td width="34%" height="35"><div align="right">Vencimiento de los avisos:</div></td>
                        <td width="66%">
                            <input name="vencimiento" type="text" class="txtfield" id="vencimiento" value="<?PHP echo valor_param('vencimiento');?>" size="5" maxlength="3" />
                            d&iacute;as
                        </td>
                    </tr>
                    <tr>
                        <td height="35" colspan="2" align="center">
                            <input name="register" type="submit" title="Modificar" value="Modificar" />
                        </td>
                    </tr>
                </table>
			</form>-->
		</div>
	</div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>