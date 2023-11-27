<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
include "../inc/inc_docs.php";
require_once("../login_init.php");
$id = getPost('id');
// ------------------------------------ Post ----------------------------
if(isset($_POST['nombre'])){
/*
	// Buscamos si el empleado ya está registrado
	$sql_bus_emp = "SELECT * FROM empleos_postulantes WHERE id_emp = ".$_SESSION['usrfrontend'];
	$res_bus_emp = fullQuery($sql_bus_emp);
	$con_bus_emp = mysqli_num_rows($res_bus_pos);
	if($con_bus_emp == 0){
		$id_postulante = nuevoID('empleos_postulantes');
	}else{
		$id_postulante = $con_bus_emp['id_emp'];
	}
*/
	$sql_bus_emp = "UPDATE empleos_busquedas SET cantidad = cantidad + 1 WHERE id = ".$id;
	$res_bus_emp = fullQuery($sql_bus_emp);

	$sql_bus_emp = "UPDATE empleos_stats_busq SET postulados = postulados + 1 WHERE busqueda = ".$id;
	$res_bus_emp = fullQuery($sql_bus_emp);
	
	$id_postulante = nuevoID('postulantes','empleos_');
	// Linkeamos al postulante con la búsqueda
	$sql_bus_pos = "INSERT INTO empleos_busqueda_postulantes (postulante, busqueda, estado) VALUES (".$id_postulante.", ".$id.", 0)";
	$res_bus_pos = fullQuery($sql_bus_pos);
	// Guardamos al postulante
	$sql_postu_in = "INSERT INTO empleos_postulantes (id, nombre, fechanac, direccion, telefono, email, id_emp, cv) VALUES
	(".$id_postulante.", '".$_POST['nombre']."', '".$_POST['fecha']."', '".$_POST['domicilio']."', '".$_POST['tel']."', '".$_POST['mail']."', 0, '')";
	$res_postu_in = fullQuery($sql_postu_in);
	// Guardamos empleos
	$cont = 1;
	while($cont <= 9){
		if(isset($_POST['empresa'.$cont]) && $_POST['empresa'.$cont] != ''){
			$fecha_egre = (isset($_POST['chk'.$cont])) ? '1111-11-11' : $_POST['egr'.$cont];
			$sql_empl = "INSERT INTO empleos_trabajos (empresa, posicion, fechaing, fechaegr, descripcion, postulante) VALUES 
			('".$_POST['empresa'.$cont]."', '".$_POST['posi'.$cont]."', '".$_POST['ing'.$cont]."', '".$fecha_egre."', '".$_POST['desc'.$cont]."',".$id_postulante.")";
			$res_empl = fullQuery($sql_empl);
		}
		$cont++;
	}
	// Guardamos estudios
	$cont = 10;
	while($cont <= 14){
		if(isset($_POST['nivel'.$cont]) && $_POST['nivel'.$cont] != ''){
			$sql_empl = "INSERT INTO empleos_educacion (nivel, carrera, otra, estado, aprobadas, materias, postulante) VALUES 
			('".$_POST['nivel'.$cont]."', ".$_POST['carrera'.$cont].", '".$_POST['otra'.$cont]."', '".$_POST['estado'.$cont]."', '".$_POST['materias_ap'.$cont]."', '".$_POST['materias'.$cont]."',".$id_postulante.")";
			$res_empl = fullQuery($sql_empl);
		}
		$cont++;
	}
	// Guardamos idiomas
	$cont = 15;
	while($cont <= 23){
		if(isset($_POST['idioma'.$cont]) && $_POST['idioma'.$cont] != ''){
			$sql_empl = "INSERT INTO empleos_idiomas (idioma, oral, escrito, lectura, postulante) VALUES 
			('".$_POST['idioma'.$cont]."', '".$_POST['oral'.$cont]."', '".$_POST['escr'.$cont]."', '".$_POST['lect'.$cont]."', ".$id_postulante.")";
			$res_empl = fullQuery($sql_empl);
		}
		$cont++;
	}
	// Guardamos conocimientos
	$cont = 24;
	while($cont <= 39){
		if(isset($_POST['otrocon'.$cont]) && $_POST['otrocon'.$cont] != ''){
			$sql_empl = "INSERT INTO empleos_conocimientos (nombre, descr, postulante) VALUES 
			('".$_POST['otrocon'.$cont]."', '".$_POST['otrodes'.$cont]."', ".$id_postulante.")";
			$res_empl = fullQuery($sql_empl);
		}
		$cont++;
	}
	// Guardamos el CV
	$classResult = "info_box";
	$errmsg = 'Gracias por tu postulaci&oacute;n.';
	if (is_uploaded_file($_FILES['curriculum']['tmp_name'])){
		//echo 'Hay un archivo subido';
		$ext        = arch_ext($_FILES['curriculum']['name']);
		$nombrearch = txtcod(nomArch($_FILES['curriculum']['name']));
		$link       = "cv_".$id_postulante.".".$ext;
		if(move_uploaded_file($_FILES['curriculum']['tmp_name'], "docs/cvs/".$link)){
			$sql_cv = "UPDATE empleos_postulantes SET cv = '".$link."' WHERE id = ".$id_postulante;
			$res_cv = fullQuery($sql_cv);
		}
	}else{
		if($_FILES['curriculum']['error'] != 4){ // el 4 es que no lo subió
			$errmsg = 'Error: '.$_FILES['curriculum']['error'];
			$classResult = "alert_box";
		}
	}
	$email = obtenerDato('email','empleados',$_SESSION['usrfrontend']);
	if(isset($email) && $email != ''){
		include("includes/pictureclass.php");
		$link_imagen = "docs/logos_cartas.jpg";
		$nombre   = $_POST['nombre'];
		//$email    = 'sdobke@gmail.com'; // test
		$puesto   = obtenerDato('nombre','busquedas',$id,'empleos_');

		$para     = $nombre." <".$email.">";
		
		$asunto   = html_entity_decode("Gracias por tu postulaci&oacute;n");

		$mensaje = '<div style="width:600px">
						<p style="font-family: Tahoma, Geneva, sans-serif;font-size:24px;color:#006;">Postulaciones Internas</p>
						<p style="font-family: Tahoma, Geneva, sans-serif;font-weight:bold;color:#92d050;">'.$puesto.'</p>
						<p style="font-family: Arial, Helvetica, sans-serif;font-size:14px;color:#006;">
						Gracias por participar del programa  de Postulaciones Internas.<br/><br/>
						En breve estaremos evaluando tu perfil y contact&aacute;ndote para coordinar una entrevista de orientaci&oacute;n.<br/><br/>
						Saludos,<br/><br/>
						Gesti&oacute;n de Talentos Alsea</p>
					';

		$bildmail = new bildmail();
		$bildmail->from('Empleos, Formacion y Desarrollo - Alsea','empleos@alsea.com.ar');
		$bildmail->to($email,$nombre);
		$bildmail->subject($asunto);
		$bildmail->settext($mensaje.'<div align="left">'.$bildmail->setbild($link_imagen).'</div></div>');
		@$bildmail->send();
		
//echo '<br>mail enviado a: '.$email.'<br>';
	}
}
// -----------------------------------------Fin Post --------------------------?>
<?PHP


if(!isset($_POST['nombre'])){
	function agregaCampos($nro,$vis=0,$agrega=1,$tipo){
		if(!isset($resto)){
	$resto = "";
}
		switch($tipo){
			case 1:
				$campo1 = 'Empresa';
				$varcp1 = 'empresa';
				$resto .= '<tr><td>Posici&oacute;n:</td><td><input type="text" name="posi'.$nro.'" id="posi'.$nro.'" /></td></tr>';
				$resto .= '<tr><td>Fecha de ingreso:</td><td><script type="text/javascript">DateInput("ing'.$nro.'", true, "YYYY/MM/DD")</script></td></tr>';
				$resto .= '<tr><td>Fecha de egreso:</td><td><div style="float:left"><script type="text/javascript">DateInput("egr'.$nro.'", true, "YYYY/MM/DD")</script></div> o 
							<input name="chk'.$nro.'" id="chk'.$nro.'" type="checkbox" value="1" /> al presente</td></tr>';
				$resto .= '<tr><td>Descrip. de tareas:</td><td><textarea id="desc'.$nro.'" name="desc'.$nro.'"></textarea></td></tr>';
				break;
			case 2:
				$campo1 = 'Nivel de estudio';
				$varcp1 = 'nivel';
				$resto .= '<tr><td>Carrera:</td><td><select name="carrera'.$nro.'" id="otra'.$nro.'" />';
				$resto .= '<option value="0" selected="selected">Seleccion&aacute; una carrera</option>';
				$sql = 'select * FROM empleos_carrera ORDER BY nombre';
				$res = fullQuery($sql);
				while($row = mysqli_fetch_array($res)){
					$resto .= '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
				}
				$resto .= '</select></td>';
				$resto .= '</td></tr>';
				$resto .= '<tr><td>Otra:</td><td><input type="text" name="otra'.$nro.'" id="otra'.$nro.'" /></td></tr>';
				$resto .= '<tr><td>Estado:</td><td><input type="text" name="estado'.$nro.'" id="estado'.$nro.'" /></td></tr>';
				$resto .= '<tr><td>Cantidad de materias aprobadas:</td><td><input type="text" name="materias_ap'.$nro.'" id="materias_ap'.$nro.'" size="5" /></td></tr>';
				$resto .= '<tr><td>Cantidad total de materias:</td><td><input type="text" name="materias'.$nro.'" id="materias'.$nro.'" size="5" /></td></tr>';
				break;
			case 3:
				$campo1 = 'Idioma';
				$varcp1 = 'idioma';
				$resto .= '<tr><td colspan="2">';
				$resto .= '<table>';
				$resto .= '<tr align="center" style="font-weight:bold">
							<td align="left">Nivel</td><td width="70">B&aacute;sico</td><td width="70">Intermedio</td><td width="70">Avanzado</td><td width="70">Biling&uuml;e</td>
						   </tr>';
				$resto .= '<tr align="center">';
				$resto .= '<td align="left">Oral</td>';
				$resto .= '<td><input type="radio" name="oral'.$nro.'" id="oral'.$nro.'" value="1" /></td>';
				$resto .= '<td><input type="radio" name="oral'.$nro.'" id="oral'.$nro.'" value="2" /></td>';
				$resto .= '<td><input type="radio" name="oral'.$nro.'" id="oral'.$nro.'" value="3" /></td>';
				$resto .= '<td><input type="radio" name="oral'.$nro.'" id="oral'.$nro.'" value="4" /></td>';
				$resto .= '<tr align="center">';
				$resto .= '<td align="left">Escrito</td>';
				$resto .= '<td><input type="radio" name="escr'.$nro.'" id="escr'.$nro.'" value="1" /></td>';
				$resto .= '<td><input type="radio" name="escr'.$nro.'" id="escr'.$nro.'" value="2" /></td>';
				$resto .= '<td><input type="radio" name="escr'.$nro.'" id="escr'.$nro.'" value="3" /></td>';
				$resto .= '<td><input type="radio" name="escr'.$nro.'" id="escr'.$nro.'" value="4" /></td>';
				$resto .= '<tr align="center">';
				$resto .= '<td align="left">Lectura</td>';
				$resto .= '<td><input type="radio" name="lect'.$nro.'" id="lect'.$nro.'" value="1" /></td>';
				$resto .= '<td><input type="radio" name="lect'.$nro.'" id="lect'.$nro.'" value="2" /></td>';
				$resto .= '<td><input type="radio" name="lect'.$nro.'" id="lect'.$nro.'" value="3" /></td>';
				$resto .= '<td><input type="radio" name="lect'.$nro.'" id="lect'.$nro.'" value="4" /></td>';
				$resto .= '</tr>';
				$resto .= '</table>';
				$resto .= '</td></tr>';
				break;
			case 4:
				$campo1 = 'Nombre';
				$varcp1 = 'otrocon';
				$resto .= '<tr><td>Descripci&oacute;n:</td><td><textarea id="otrodes'.$nro.'" name="otrodes'.$nro.'"></textarea></td></tr>';
				break;
		}
		if ($vis == 1){$ver = "block";}else{$ver = "none";}
		$resultado  = '<div id="DivCont'.$nro.'" style="display:'.$ver.'">';
		if ($vis == 0){$resultado .= '<br />-------------------------------------------------------------------<br /><br />';}
		$resultado .= '<table>';
		$resultado .= '<tr><td>'.$campo1.':</td><td><input type="text" name="'.$varcp1.$nro.'" id="'.$varcp1.$nro.'"';
		if ($agrega == 1){
			$resultado .= ' onfocus="agregaCpos('.$nro.')" ';
		}
		$resultado .= '/></td></tr>';
		$resultado .= $resto;
		$resultado .= '</table>';
		
		$resultado .= '</div>';
		return $resultado;
	}
}
?>


<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_empleos.php"); ?>
		<script type="text/javascript" src="calendarDateInput.js"></script>
		<script type="text/javascript">
		function agregaCpos(nro){
			var nroDiv = nro+1;
			document.getElementById('DivCont'+nroDiv).style.display = 'block';
		/*	document.getElementById('agregar_fotos').value = '1';*/
		}
		</script>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
					<?php include_once '../login.php'; ?>					
				</div>
				<?php include_once '../menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Formaci&oacute;n</div>
				<div class="container_inner">
					<div class="col_ppal right">
                            <div class="textos">
                                <p class="titulos">Postulaciones Internas</p>
                            	<br /><br />
                                <?PHP
								if(isset($_POST['nombre'])){ ?>
								<div class="<?php echo $classResult; ?>">
									<?php echo $errmsg;; ?>
								</div>
									
								<?php }else{
								?>
                                <form id="postulacion" name="postulacion" action="postulacion.php" enctype="multipart/form-data" method="post">
                                	<input name="id" id="id" type="hidden" value="<?PHP echo $id;?>" />
                                    <table>
                                        <tr>
                                            <td colspan="2" align="center"><strong>DATOS PERSONALES</strong><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td>Nombre y Apellido:</td>
                                            <td width="480"><input name="nombre" type="text" id="nombre" />
                                      </tr>
                                        <tr>
                                            <td>Fecha de Nacimiento:</td>
                                            <td><script type="text/javascript">DateInput('fecha', true, 'YYYY/MM/DD')</script></td>
                                        </tr>
                                        <tr>
                                            <td>Domicilio:</td>
                                            <td><input type="text" id="domicilio" name="domicilio" /></td>
                                        </tr>
                                        <tr>
                                            <td>Tel&eacute;fono:</td>
                                            <td><input type="text" id="tel" name="tel" /></td>
                                        </tr>
                                        <tr>
                                            <td>E-mail:</td>
                                            <td><input type="text" id="mail" name="mail" /></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><br /><strong>EXPERIENCIA LABORAL</strong><br /><br /></td>
                                        </tr>
                                        <tr><td colspan="2">
                                        	<?PHP
											$cont_cpos = 1;
											$agregar = (isset($agregar) && $agregar == 0) ? 0 : 1;
											echo agregaCampos(1,1,$agregar,1);
											$cont_cpos++;
											while ($cont_cpos < 10){
												echo agregaCampos($cont_cpos,0,1,1);
												$cont_cpos++;
											}
											?>
                                        </td></tr>
                                        <tr>
                                            <td colspan="2" align="center"><br /><strong>EDUCACION</strong><br /><br /></td>
                                        </tr>
                                        <tr><td colspan="2">
                                        	<?PHP
											$agregar = (isset($agregar) && $agregar == 0) ? 0 : 1;
											echo agregaCampos($cont_cpos,1,$agregar,2);
											$cont_cpos++;
											while ($cont_cpos < 15){
												echo agregaCampos($cont_cpos,0,1,2);
												$cont_cpos++;
											}
											?>
                                        </td></tr>
                                        <tr>
                                            <td colspan="2" align="center"><br /><strong>IDIOMAS</strong><br /><br /></td>
                                        </tr>
                                        <tr><td colspan="2">
                                        	<?PHP
											$agregar = (isset($agregar) && $agregar == 0) ? 0 : 1;
											echo agregaCampos($cont_cpos,1,$agregar,3);
											$cont_cpos++;
											while ($cont_cpos < 24){
												echo agregaCampos($cont_cpos,0,1,3);
												$cont_cpos++;
											}
											?>
                                        </td></tr>
                                        <tr>
                                            <td colspan="2" align="center"><br /><strong>OTROS CONOCIMIENTOS</strong><br /><br /></td>
                                        </tr>
                                        <tr><td colspan="2">
                                        	<?PHP
											$agregar = (isset($agregar) && $agregar == 0) ? 0 : 1;
											echo agregaCampos($cont_cpos,1,$agregar,4);
											$cont_cpos++;
											while ($cont_cpos < 40){
												echo agregaCampos($cont_cpos,0,1,4);
												$cont_cpos++;
											}
											?>
                                        </td></tr>
                                        <tr>
                                            <td colspan="2" align="center"><br /><strong>CURRICULUM VITAE</strong><br /><br /></td>
                                        </tr>
                                        <tr>
                                        	<td>Adjuntar CV:</td>
											<td><input type="file" name="curriculum" id="curriculum" lang="es" />
                                        </tr>
										<tr>
										  <td colspan="2" align="center">
                                        	<br /><br /><input class="boton_postu" type="submit" value="Postularme" />
                                        </td></tr>
									</table>
								</form>
                                <?PHP } ?>
							</div>
                            <a href="javascript:history.back(1)">Volver</a>
                        
                         </div>
				<?php include 'col_izq.php'; ?>
			</div>
			<div class="clr"></div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>