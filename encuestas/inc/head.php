<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/style-home.css" rel="stylesheet" type="text/css" />

<?PHP 
$title = "<title>Starbucks Argentina | Sistema de reclamos | ";

$tit_ruta    = explode('reclamos/', $_SERVER["PHP_SELF"]);
$tit_docu    = $tit_ruta[count($tit_ruta) - 1];

$tit_docu2   = explode('.', $tit_docu);
$tit_sinext  = $tit_docu2[count($tit_docu2) - 2];

$tit_sinext2 = explode('_', $tit_sinext);
if (count($tit_sinext2) > 1){
	$title .= $tit_sinext2[count($tit_sinext2) - 2];
	$title .=" | ";
	$title .= $tit_sinext2[count($tit_sinext2) - 1];
} else {
	$title .= $tit_sinext;
}

$title .="</title>";

echo ucwords($title);
?>
