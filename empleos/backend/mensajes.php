<?PHP include "../includes/conexion.php";?>
<?PHP include "sechk.php";?>
<?PHP include "../includes/inc_funciones_globales.php";

switch ($_GET['error']){

case 1:
	$error = "El ".$_GET['tipo']." ".$_GET['dato']." no puede eliminarse porque est&aacute; en uso por uno o m&aacute;s registros. Cambie el valor del/los registro/s que posea/n esta informaci&oacute;n y vuelva a intentarlo.";
	break;

case 2:
	$error = "El ".$_GET['tipo']." ".$_GET['dato']." se ha eliminado.";
	break;

}
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | Areas</title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="contenedor">
	<div id="header">  </div>
    <?PHP include "menu.php";?>
  <div class="tit-result"><strong>MENSAJE DEL SISTEMA</strong></div>

	<div style="width:728px; float:left">
				<?PHP echo $error;?>
	</div>

  <div style="clear:both;"></div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>