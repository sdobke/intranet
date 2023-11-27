<?php
$doclink = 'No se encontr&oacute; documento.';
$sql_doc = "SELECT * FROM intranet_docs WHERE tabla = ".$tipo." AND sector = ".$noticia['id'];
$res_doc = fullQuery($sql_doc);
$con_doc = mysqli_num_rows($res_doc);
if($con_doc > 0){
	$doc = mysqli_fetch_assoc($res_doc);
	//if(file_exists($doc['link'])){
		$nombre_doc = $doc['nombre'];
		$doclink = '<div class="link">Descargar documento:</strong> <a href="../'.$doc['link'].'" target="_blank">'.$nombre_doc.'</a></div>';
	//}
}
echo $doclink;
?>