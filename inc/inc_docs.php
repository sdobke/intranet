<?PHP

function nomArch($arch)

{

	$ext = current(explode('.', $arch));

	return $ext;

}

function arch_ext($arch)

{ //trae la extensión de un archivo

	$ext = explode('.', $arch);

	$ext = end($ext);

	return $ext;

}

function urlExt($doc)

{

	$verext = explode('/', $doc);

	$extens = $verext[3];

	$exten2 = '';

	switch ($extens) {

		case 'spreadsheets':

			$exten2 = 'xlsx';

			break;

		case 'document':

			$exten2 = 'docx';

			break;

		case 'presentation':

			$exten2 = 'pptx';

			break;

	}

	return $exten2;

}

function arch_img($ext)

{ //define la imagen del archivo

	switch (strtolower($ext)) {

		case "xls":

			$img = "file-excel";

			break;

		case "xlsx":

			$img = "file-excel";

			break;

		case "doc":

			$img = "file-word";

			break;

		case "docx":

			$img = "file-word";

			break;

		case "pdf":

			$img = "file-pdf";

			break;

		case "ppt":

			$img = "file-ppt";

			break;

		case "pps":

			$img = "file-ppt";

			break;

		case "pptx":

			$img = "file-ppt";

			break;

		case "ppsx":

			$img = "file-ppt";

			break;

		case "pot":

			$img = "file-ppt";

			break;

		case "html":

			$img = "file-html";

			break;

		case "htm":

			$img = "filetype-html";

			break;

		case "rtf":

			$img = "file-richtext";

			break;

		case "zip":

			$img = "file-zip";

			break;

		case "rar":

			$img = "file-zip";

			break;

		case "gif":

			$img = "file-image";

			break;

		case "jpg":

			$img = "image";

			break;

		default:

			$img = "file";

			break;

	}

	return $img;

}

function medidaDocs($peso)

{

	$medida = "Bytes";

	if ($peso > 1024) {

		$peso = round($peso / 1024);

		$medida = "Kb";

	}

	if ($peso > 1024) {

		$peso = round($peso / 1024, 2);

		$medida = "Mb";

	}

	if ($peso > 1024) {

		$peso = round($peso / 1024, 2);

		$medida = "Gb";

	}

	return $peso . " " . $medida;

}



// Funciones para agregar negrita al t�tulo de la empresa seleccionada

function neg1($codigo)

{

	if (isset($_GET['cod']) && $_GET['cod'] == $codigo) {

		return "<strong>";

	}

}

function neg2($codigo)

{

	if (isset($_GET['cod']) && $_GET['cod'] == $codigo) {

		return "</strong>";

	}

}

function neg3($area)

{

	if (isset($_GET['area']) && $_GET['area'] == $area) {

		return "<strong>";

	}

}

function neg4($area)

{

	if (isset($_GET['area']) && $_GET['area'] == $area) {

		return "</strong>";

	}

}

function neg5($sector)

{

	if (isset($_GET['sector']) && $_GET['sector'] == $sector) {

		return "<strong>";

	}

}

function neg6($sector)

{

	if (isset($_GET['sector']) && $_GET['sector'] == $sector) {

		return "</strong>";

	}

}

function neg7($anio)

{

	if (isset($_GET['anio']) && $_GET['anio'] == $anio) {

		return "<strong>";

	}

}

function neg8($anio)

{

	if (isset($_GET['anio']) && $_GET['anio'] == $anio) {

		return "</strong>";

	}

}

function cuentaArchivos($dir, $tipo = '')

{

	$a = 0;

	if (is_dir($dir)) {

		if ($gd = opendir($dir)) {

			while (($archivo = readdir($gd)) !== false) {

				if ($archivo != '.' && $archivo != '..') {

					if (arch_ext($archivo) == $tipo || $tipo == '') {

						$a++;

					}

				}

			}

			closedir($gd);

		}

	} else {

		$a = "no es dir: " . $dir;

	}

	return $a;

}

