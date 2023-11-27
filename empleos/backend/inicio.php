<?PHP include "../includes/conexion.php";?>
<?PHP include "sechk.php";?>
<?PHP 
	$dif  = cantidad('diferencia');
	$dif2 = cantidad('difepost');
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | Backend</title>
	<?PHP if (isset($_GET['ref'])){
		$ref = $_GET['ref'];
	}else{
		$ref = 'no';
	}
	if ($ref == 'si'){
		header("Location: inicio.php");
	}?>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
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
		case 4:
			$mensaje = 'Los valores deben ser num&eacute;ricos y no pueden exceder los 3000 pixels.';
		break;
		default:
			$mensaje = 'Cambios realizados correctamente.';
			break;
	}
	if ($error > 0){echo '<p class="error"><b>Por favor corrija el siguiente error:</b></p>';}
	$error_msg = '<span class="error">'.$mensaje.'</span><br /><br />';
}
?>
<div id="contenedor">
	<div id="header"></div>
    <?PHP include "menu.php";?>
    <?PHP echo $error_msg;?>
    <br />
    <?PHP

	// CUMPLEA�OS
	
	$sql_conf = "SELECT id, fechanac, confirmado,
					DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(fechanac), DAY(fechanac) ), SYSDATE() ) 
				FROM intranet_empleados
				WHERE DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(fechanac), DAY(fechanac) ), SYSDATE() ) <= 7
				AND DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(fechanac), DAY(fechanac) ), SYSDATE() ) > 0
					AND fechanac != '1111-11-11'
					AND empresa < 3
					AND confirmado = 0";
	
	$res_conf = fullQuery($sql_conf);
	$con_conf = mysqli_num_rows($res_conf);
	if($con_conf > 0){
		echo '<br /><div class="error" align="center"><strong><a href="confirma_cumples.php">Hay '.$con_conf.' cumplea&ntilde;os para confirmar. Haga click ac&aacute; para ir a la p&aacute;gina de confirmaci&oacute;n.</a></strong></div><br />';
	}

	// ANIVERSARIOS
	
	$sql_conf = "SELECT id, fechaing, confirmani,
					DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(fechaing), DAY(fechaing) ), SYSDATE() ) 
				FROM intranet_empleados
				WHERE DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(fechaing), DAY(fechaing) ), SYSDATE() ) <= 7
				AND DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(fechaing), DAY(fechaing) ), SYSDATE() ) > 0
					AND fechaing != '1111-11-11'
					AND empresa < 3
					AND confirmani = 0";
					
	$res_conf = fullQuery($sql_conf);
	$con_conf = mysqli_num_rows($res_conf);
	if($con_conf > 0){
		echo '<div class="error" align="center"><strong><a href="confirma_cumples.php">Hay '.$con_conf.' aniversarios para confirmar. Haga click ac&aacute; para ir a la p&aacute;gina de confirmaci&oacute;n.</a></strong></div><br /><br />';
	}
	?>
    <div class="tit-result"><strong>CONFIGURACI&Oacute;N GENERAL DEL SITIO</strong></div>
<div id="ccentro">
	  <div id="formulario" style="width:920px; float:left; border:0px solid #e6e6e6; padding:10px">
	    <form id="registerForm" name="registerForm" method="post" action="inicio_post.php">
        <table width="920" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td width="34%" height="35"><div align="right">Cantidad de d&iacute;as de anticipaci&oacute;n para mostrar cumplea&ntilde;os:</div></td>
            <td width="66%"><span style="float:left; width:600px">
            <input name="dias" type="text" class="txtfield" id="dias" value="<?PHP echo $dif;?>" size="5" maxlength="3"/>
            d&iacute;as (1 equivale al d&iacute;a del cumplea&ntilde;os, 2 incluye el d&iacute;a siguiente, 7 la semana)</span></td>
          </tr>
          <tr>
            <td width="34%" height="35"><div align="right">D&iacute;as a mostrar pasado el cumplea&ntilde;os:</div></td>
            <td width="66%"><span style="float:left; width:600px">
            <input name="dias2" type="text" class="txtfield" id="dias2" value="<?PHP echo $dif2;?>" size="5" maxlength="3"/>
            d&iacute;as</span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">E-mail de contacto:</div></td>
            <td><span style="float:left; width:266px">
              <input name="email" type="text" class="txtfield" id="email" size="30" maxlength="50" value="<?PHP echo cantidad('email');?>"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Nueva contrase&ntilde;a:</div></td>
            <td><span style="float:left; width:266px">
              <input name="password" type="password" class="txtfield" id="password" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Repetir contrase&ntilde;a:</div></td>
            <td><span style="float:left; width:266px">
              <input name="passwordrep" type="password" class="txtfield" id="passwordrep" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de &aacute;reas a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="cant_areas" type="text" class="txtfield" id="cant_areas" value="<?PHP echo cantidad('cant_areas');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de empleados por &aacute;rea a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="cant_empar" type="text" class="txtfield" id="cant_empar" value="<?PHP echo cantidad('cant_empar');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de empleados a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="cant_emple" type="text" class="txtfield" id="cant_areas" value="<?PHP echo cantidad('cant_emple');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de documentos a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="cant_docus" type="text" class="txtfield" id="cant_docus" value="<?PHP echo cantidad('cant_docus');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de Novedades y galer&iacute;as de fotos a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="cant_latam" type="text" class="txtfield" id="cant_latam" value="<?PHP echo cantidad('cant_latam');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de comunicaciones a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="cant_comun" type="text" class="txtfield" id="cant_comun" value="<?PHP echo cantidad('cant_comun');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de cumplea&ntilde;os en home a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="home_cumpl" type="text" class="txtfield" id="home_cumpl" value="<?PHP echo cantidad('home_cumpl');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de Novedades en home a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="home_latam" type="text" class="txtfield" id="home_latam" value="<?PHP echo cantidad('home_latam');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Cantidad de comunicaciones en home a mostrar:</div></td>
            <td><span style="float:left; width:266px">
              <input name="home_comun" type="text" class="txtfield" id="home_comun" value="<?PHP echo cantidad('home_comun');?>" size="30" maxlength="20"/>
            </span></td>
          </tr>
<!--		  <tr>
            <td height="35"><div align="right">Proveedor de clima:</div></td>
            <td><span style="float:left; width:266px">
              <select name="clima" id="clima">
                <option value="1" <?PHP if (cantidad('clima') == 1){echo 'selected="selected"';}?>>Google</option>
                <option value="2" <?PHP if (cantidad('clima') == 2){echo 'selected="selected"';}?>>Weather.com</option>
              </select>
            </span></td>
          </tr>-->
          <tr>
            <td height="35"><div align="right">Tama&ntilde;o m&aacute;ximo de fotos:</div></td>
            <td><span style="float:left; width:266px">
              <input name="fotow" type="text" class="txtfield" id="fotow" value="<?PHP echo cantidad('fotow');?>" size="10" maxlength="4"/>
            x 
            <input name="fotoh" type="text" class="txtfield" id="fotoh" value="<?PHP echo cantidad('fotoh');?>" size="10" maxlength="4"/>
            pixels</span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Duraci&oacute;n de los clasificados:</div></td>
            <td><span style="float:left; width:266px">
              <input name="clasivenc" type="text" class="txtfield" id="clasivenc" value="<?PHP echo cantidad('clasivenc');?>" size="30" maxlength="20"/>
              d&iacute;as
            </span></td>
          </tr>
          <tr>
            <td height="35"><div align="right">Modo prueba para env&iacute;o de tarjetas:</div></td>
            <td><span style="float:left; width:266px">
            <input name="modoprueba" type="radio" <?PHP if (cantidad('prueba') == 1){echo 'checked="checked"';}?> value="1" />Enviar al administrador
            <br />
            <input name="modoprueba" type="radio" <?PHP if (cantidad('prueba') == 0){echo 'checked="checked"';}?> value="0" />Enviar al empleado
            </span></td>
          </tr>
          <tr>
            <td height="35" colspan="2" align="center">
              <label>
                <input name="register" type="image" title="submit" value="Enviar" src="img/boton_ingresar.jpg" alt="enviar" />
              </label>            </td>
          </tr>
        </table>
	</form>
    </div>
  </div>
	<div style="clear:both;"></div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>