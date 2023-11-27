<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
require_once("../login_init.php");

$id = getPost('id');
$tipo = getPost('tipo');

$sql_bus_emp = "UPDATE empleos_stats_busq SET postulados = postulados + 1 WHERE busqueda = ".$id." AND tipo = ".$tipo;
$res_bus_emp = fullQuery($sql_bus_emp);

$email = obtenerDato('email','empleados',$_SESSION['usrfrontend']);
if(isset($email) && $email != ''){
	include("includes/pictureclass.php");
	$link_imagen = "docs/logos_cartas.jpg";
	$nombre   = $_POST['nombre'];
	//$email    = 'sdobke@gmail.com'; // test
	$puesto   = obtenerDato('nombre','busque_refe',$id,'empleos');
	$para     = $nombre." <".$email.">";
	
	$asunto   = html_entity_decode("Gracias por participar del Programa de Referidos");
	$mensaje = '<div style="width:600px">
					<p style="font-family: Tahoma, Geneva, sans-serif;font-size:24px;color:#006;">Programa de Referidos</p>';
	if($id == 999 && $tipo = 999){
		$texto_msg = 'Gracias por participar del Programa de Referidos<br /><br />Guardaremos el CV de tu Referido en nuestra base de datos para contactarle en b&uacute;squedas futuras acordes con su perfil.';
	}else{
		$texto_msg = 'Evaluaremos el perfil de tu Referido y, en caso de que aplique a la b&uacute;squeda, lo estaremos incorporando al proceso de selecci&oacute;n activo.';
		$mensaje.= '    <p style="font-family: Tahoma, Geneva, sans-serif;font-weight:bold;color:#92d050;">'.$puesto.'</p>';
	}
	$mensaje.= '	<p style="font-family: Arial, Helvetica, sans-serif;font-size:14px;color:#006;">
					Gracias por participar del programa de Referidos.<br/><br/>';
	$mensaje.= $texto_msg;			
	$mensaje.= '	<br/><br/>
					Saludos,<br/><br/>
					Gesti&oacute;n de Talentos Alsea
					</p>
				';
	$bildmail = new bildmail();
	$bildmail->from('Empleos, Formacion y Desarrollo - Alsea','empleos@alsea.com.ar');
	$bildmail->to($email,$nombre);
	$bildmail->subject($asunto);
	$bildmail->settext($mensaje.'<div align="left">'.$bildmail->setbild($link_imagen).'</div></div>');
	$bildmail->send();
}
?>