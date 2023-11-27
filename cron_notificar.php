<?PHP

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$cron = 1;
include_once("cnfg/config.php");
include_once("inc/funciones.php");
include_once("backend/inc/func_backend.php");
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
  $sql = "SELECT * FROM intranet_alertas WHERE fecha = '" . date("Y-m-d") . "' AND hora = '" . date("H:i") . ":00'";
  //$sql = "SELECT * FROM intranet_alertas WHERE fecha = '" . date("Y-m-d") . "' ";
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
        $debug = 0;
        $testing = 0;
        include("backend/inc/notificar.php");
        $cant_al = $row['enviada']+1;
        $sql_un = "UPDATE intranet_alertas SET enviada = ".$cant_al." WHERE id = ".$row['id'];
        $res_un = fullQuery($sql_un);
      }
    }
  }
  // borramos alertas viejas
  $sql_aldel = "DELETE FROM intranet_alertas WHERE fecha < '" . date("Y-m-d") . "'";
  //$res_aldel = fullQuery($sql_aldel);
  ?>
</body>

</html>