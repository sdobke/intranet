<?PHP 
include "cnfg/config.php";
include "inc/funciones.php";
if(is_numeric($_SESSION['usrfrontend']) && $_SESSION['usrfrontend'] > 0){
  $sql = "SELECT * FROM intranet_empleados WHERE id = ".$_SESSION['usrfrontend'];
  $res = fullQuery($sql);
  $con = mysqli_num_rows($res);
  if($con > 0){
    $sql2 = "UPDATE intranet_empleados SET token = '' WHERE id = ".$_SESSION['usrfrontend'];
    $res2 = fullQuery($sql2);
  }
}
session_destroy();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="0;url=index.php">
<title><?PHP echo $cliente;?> Intranet | Home</title>
</head>
<body>
</body>
</html>