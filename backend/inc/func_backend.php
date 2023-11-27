<?PHP

function decodeTexto($texto){
	$original  = array(chr(146), chr(145), chr(147), chr(147),'&quot;','´','`','“','”',"'",'"');
	$reemplazo = array('');
	$texto  = str_replace($original,$reemplazo,$texto);
	$texto = utf8_encode($texto);
	$devuelve = acentos($texto);
	return $devuelve;
}
?>