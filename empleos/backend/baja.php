<?PHP 
require_once("../../cnfg/config.php");
require_once("sechk.php");
require_once("inc/inc_funciones_globales.php");
require_once("inc/func_backend.php");

$tipo   = getPost('tipo');
$nombretab = obtenerNombre($tipo);
$id     = $_GET['id'];

$query = "UPDATE empleos_".$nombretab." SET del = 1 WHERE id = ".$id;
$resul = fullQuery($query);

/*
$query = "SELECT postulante FROM empleos_busqueda_postulantes WHERE busqueda = ".$id;
$resul = fullQuery($query);
while($row = mysqli_fetch_array($resul)){
	$id_postu = $row['postulante'];
	$query2 = "SELECT link FROM empleos_postulantes WHERE id = ".$id_postu;
	$resul2 = fullQuery($query2);
	$row2 = mysqli_fetch_array($resul2);
	$link = $row2['link'];
	if($link != ''){
		if(file_exists("../docs/cvs/".$link)){
			unlink("../docs/cvs/".$link);
		}
	}
	$query2 = "DELETE FROM empleos_postulantes WHERE id = ".$id_postu;
	$resul2 = fullQuery($query2);
	$query2 = "DELETE FROM empleos_conocimientos WHERE postulante = ".$id_postu;
	$resul2 = fullQuery($query2);
	$query2 = "DELETE FROM empleos_educacion WHERE postulante = ".$id_postu;
	$resul2 = fullQuery($query2);
	$query2 = "DELETE FROM empleos_idiomas WHERE postulante = ".$id_postu;
	$resul2 = fullQuery($query2);
	$query2 = "DELETE FROM empleos_trabajos WHERE postulante = ".$id_postu;
	$resul2 = fullQuery($query2);
}

$query = "DELETE FROM empleos_busqueda_postulantes WHERE busqueda = ".$id;
$resul = fullQuery($query);
*/
header("Location: listado.php?tipo=".$tipo."&error=".$msg);
?>