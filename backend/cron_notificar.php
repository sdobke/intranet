<?PHP
require_once('../vendor/autoload.php');
include_once("../cnfg/config.php");
include_once("inc/sechk.php");
include_once("../inc/funciones.php");
include_once("inc/func_backend.php");
include_once("../clases/clase_error.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es">
<!--<![endif]-->

<head>
</head>

<body>
  <?PHP
  $sql = "SELECT * FROM intranet_alertas WHERE fecha = '" . date("Y-m-d") . "' AND hora = '" . date("H:i") . "'";
  $sql = "SELECT * FROM intranet_alertas WHERE fecha = '" . date("Y-m-d") . "' ";
  $res = fullQuery($sql);
  if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_array($res)) {
      $tipo = $row['tipo'];
      $id = $row['item'];
      $tipotit   = (parametro('titulo', $tipo) == '') ? 'titulo' : parametro('titulo', $tipo);
      $nombretab = obtenerDato('nombre', 'tablas', $tipo);
      $sql_dato = "SELECT * FROM intranet_" . $nombretab . " WHERE id = " . $id;
      $res_dato = fullQuery($sql_dato);
      if (mysqli_num_rows($res_dato) == 1) {
        $noticia = mysqli_fetch_array($res_dato);
        $vartitulo = $noticia[$tipotit];
        $titulonota = $vartitulo;
        $debug = 1;
        $testing = 1;
        include("inc/notificar.php");
      }
    }
  }
  ?>
</body>

</html>