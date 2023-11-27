<?PHP
function leePost($var, $def=''){
	if(isset($_POST[$var])){
		return $_POST[$var];
	}else{
		return $def;
	}
}
$funcion_archivo = 'alta';
$vartitulo = ($error == '') ? '' : txtcod(leePost($tipotit));
if($usacolor == 1){
	$varcolor = ($error == '') ? '' : leePost('color');
}
if($usatexto == 1){
	$vartexto = ($error == '') ? '' : txtcod(leePost('texto'));
}
if($usahora == 1){
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$varhora = ($error == '') ? date("H:m") : leePost('hora', date("H:m"));
}
?>