<?PHP
if(isset($_GET['file'])){
	$arch = $_GET['file'];
	if(file_exists($arch)){
		$zip = new ZipArchive;
		$res = $zip->open($arch);
		if ($res === TRUE) {
		  $zip->extractTo('./');
		  $zip->close();
		  echo 'OK!';
		} else {
		  echo 'Error!';
		}
	}else{
		echo 'No existe el archivo solicitado';
	}
}else{
	echo 'No se definió el archivo a descomprimir.';
}
?>