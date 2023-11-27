<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?PHP
$area_emple = (isset($_SESSION['usrfrontend'])) ? obtenerDato('area','empleados',$_SESSION['usrfrontend']) : 0;
if( (!isset($_SESSION['usrfrontend']) || (isset($_SESSION['usrfrontend']) && ($_SESSION['tipousr'] != 1 && $area_emple != 9 && $area_emple != 24) ) ) && !isset($_SESSION["in"]) ){?>
	<meta http-equiv="refresh" content="0;url=../index.php" />
<?PHP 
}else{
	if(isset($_SESSION["in"])){
		$_SESSION['nombreusr'] = 'admin';
	}
}?>
<link rel="stylesheet" href="../css/styles.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="css/minisitio.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="../inc/scripts.js"></script>
<link rel="stylesheet" type="text/css" href="../inc/jqueryslidemenu.css" />
<style type="text/css">
html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
</style>
<script type="text/javascript" src="../inc/jquery.min.js"></script>
<script type="text/javascript" src="../inc/jqueryslidemenu.js"></script>