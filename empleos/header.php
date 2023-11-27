<!--[if lt IE 7]>
<script defer type="text/javascript" src="../includes/pngfix_map.js"></script>
<![endif]-->
<script type="text/javascript">
	<!--
	function MM_openBrWindow(theURL,winName,features) { //v2.0
	  testwindow=window.open(theURL,winName,features);
	  testwindow.moveTo(100,100);
	}
	//-->
</script>
<?PHP
$logo_header = 'logo';
if(isset($tipo) && ($tipo == 1 || $tipo == 27 || $tipo == 2 || $tipo == 3 || $tipo == 6)){
	$logo_header = (isset($_GET['cod'])) ? $logo_header = 'logo'.$_GET['cod'] : 'logo';
}
?>
<div id="header" style="background-image:url(/cliente/img/logo.png); background-repeat:no-repeat; width:990px; margin:auto; background-color:#003a62; padding-bottom:0px;">
<!--[if lt IE 7]>
<div id="header" style="background-image:url(img/<?PHP echo $logo_header.'ie.gif';?>); background-repeat:no-repeat; width:1024px; margin:auto; background-color:#003a62; padding-bottom:0px;">
<![endif]-->



<!--  <div id="logo">
  	<img src="img/<?PHP //echo $logo_header;?>" alt="Logo Alsea" />
  </div>-->
    <!-- LOGIN -->
	<?PHP require_once("login.php");?>
</div>