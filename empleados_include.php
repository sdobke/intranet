<?PHP
$tipo = 4;
//agrega_acceso($tipo);
$nombre = obtenerNombre($tipo);
$titsec = $nombre;
// busqueda
$buscampo1 = "nombre";
$buscampo2 = "apellido";
$query_orden = 'emp.apellido, emp.nombre';
$otro_query = " emp.activo = '1' AND emp.del = 0 ";
$otro_inner = ' INNER JOIN intranet_empleados_areas iea ON iea.empleado = emp.id INNER JOIN intranet_areas ia ON ia.id = iea.area ';
$query_sel = ',ia.id AS areaempl ';
if (isset($_REQUEST['cod']) || isset($_REQUEST['busqueda'])) {
	//$otro_query = " emp.area = " . $areaemp . " AND emp.activo = '1' AND dat.empresa = " . $cod. " AND emp.fecharet = '3000-01-01'";
	if (isset($_REQUEST['cod'])) {
		$otro_query .= ' AND emp.empresa = "' . $_REQUEST['cod'] . '"';
	}
	if (isset($_REQUEST['areaemp']) && $_REQUEST['areaemp'] > 0) {
		//$otro_query.= ' AND ( emp.area = '.$_REQUEST['areaemp'].' ';
		//$otro_query.= ' AND ( FIND_IN_SET('.$_REQUEST['areaemp'].',emp.area) ';
		$otro_query .= ' AND ( ia.id = ' . $_REQUEST['areaemp'] . ' ';
		$sqlsaa = "SELECT id FROM intranet_areas WHERE parent = " . $_REQUEST['areaemp'];
		$ressaa = fullQuery($sqlsaa);
		if (mysqli_num_rows($ressaa) > 0) {
			$subareasin = '0';
			while ($rowsaa = mysqli_fetch_array($ressaa)) {
				$subareasin .= ',' . $rowsaa['id'];
				//$otro_query.= ' OR FIND_IN_SET('.$rowsaa['id'].',emp.area) ';
			}
			$otro_query .= " OR ia.id IN (" . $subareasin . ") ";
		}
		$otro_query .= " ) ";
	}
	if (isset($_REQUEST['lugaremp']) && $_REQUEST['lugaremp'] > 0) {
		$otro_query .= ' AND emp.lugar = ' . $_REQUEST['lugaremp'];
	}
} else {
	//$otro_query = '';
	//$otro_query = " activo = '1' AND emp.fecharet = '3000-01-01'";
}
//echo $otro_query;
if (isset($_REQUEST['busqueda'])) {
	$otro_query .= ' AND (emp.nombre LIKE "%' . $_REQUEST['busqueda'] . '%" OR emp.apellido LIKE "%' . $_REQUEST['busqueda'] . '%") ';
}
$cadbusca = 'SELECT emp.id AS id, emp.nombre as nombre, emp.apellido, emp.empresa, emp.interno, emp.puesto, emp.fechanac, emp.email ' . $query_sel . ' FROM intranet_empleados AS emp ' . $otro_inner . ' WHERE 1 AND ' . $otro_query . ' GROUP BY emp.id ORDER BY ' . $query_orden;
//$limit = cantidad('cant_emple'); //Cantidad de resultados por página
$limit  = cantidad('cant_emple'); //Cantidad de resultados por página
include "inc/prepara_paginador.php";
$titula = "";
$lupa = '... <img src="/img_new/herramientas/lupa.png" />';
//$lupa = '';
//debug($query);
$result = fullQuery($query);
$contar_alto = mysqli_num_rows($result);
$cont = 1;
$tarant = 0;
function getAreas($userid)
{
	$dev = '';
	if ($userid != '') {
		$sqlus = "SELECT ia.nombre AS nom FROM intranet_empleados ie INNER JOIN intranet_empleados_areas iea ON iea.empleado = ie.id
	                INNER JOIN intranet_areas ia ON ia.id = iea.area
	                WHERE ie.id = " . $userid;
		//if($userid==212){debug($sqlus);}
		$resus = fullQuery($sqlus);
		if (mysqli_num_rows($resus) > 0) {
			$co = 0;
			while ($rowar = mysqli_fetch_array($resus)) {
				if ($co > 0) {
					$dev .= ', ';
				}
				$dev .= txtcod($rowar['nom']);
				$co++;
			}
		}
	}
	return $dev;
}
while ($persona = mysqli_fetch_array($result)) {
	$empl_id = $persona['id'];
	$empl_area = getAreas($empl_id);
	$empl_nomb = txtcod($persona['nombre']);
	$empl_apel = txtcod($persona['apellido']);
	$empl_empr = obtenerDato('detalle', 'empresas', $persona['empresa']);
	$empl_inte = $persona['interno'];
	$empl_pues = '<!--Puesto:--> ' . txtcod($persona['puesto']);
	$empl_cump = fechaDet($persona['fechanac'], 'diames');
	$empl_email = $persona['email'];
	$empl_nomap = cortarTxt($empl_apel . ', ' . $empl_nomb, 28, ' ');
?>
	<div class="col">
		<div class="card h-100">
			<div class="row g-3 cusuario">
				<div class="col-xl-4 col-lg-12">
					<?php $emp_foto = (file_exists("cliente/fotos/" . $empl_id . ".jpg")) ? $empl_id : "sinfoto"; ?>
					<img src="/cliente/fotos/<?PHP echo $emp_foto; ?>.jpg" alt="<?PHP echo $empl_nomap; ?>" class="user_pic" />
				</div>
				<div class="col-xl-8 col-lg-12">
					<div class="user_name"><a href="mailto:<?PHP echo $empl_email; ?>" title="<?PHP echo $empl_email; ?>"><?PHP echo $empl_nomap; ?></a></div>
					<div class="card-text">
						<?PHP //$tamtx = (strlen($empl_nomap) > 29) ? 10 : 11;
						$tamtx = 9;
						$maxtxt = 300;
						if ($empl_area != '') {
							$spanarea = '';
							if (strlen($empl_area) > $maxtxt) {
								$span_area_in = "'" . $empl_area . "'";
								$spanarea = 'onmouseover="tooltip.show(' . $span_area_in . ')" onmouseout="tooltip.hide();"';
							}
						?>
							Área:
							<span <?PHP echo $spanarea; ?>><?PHP echo cortarTxt($empl_area, $maxtxt, $lupa); ?></span>
							<?PHP
							if ($persona['puesto'] != '') {
								$spanpues = '';
								if (strlen($empl_pues) > 30) {
									$span_pues_in = "'" . $empl_pues . "'";
									$spanpues = 'onmouseover="tooltip.show(' . $span_pues_in . ')" onmouseout="tooltip.hide();"';
								}
							?>
								<br><!--<span class="lupa" <?PHP //echo $spanpues; ?>>--><i class="bi bi-briefcase"></i> <?PHP echo cortarTxt($empl_pues, $maxtxt, $lupa); ?></span>
							<?PHP } ?>
						<?PHP } ?>
						<?PHP if ($empl_inte != 0) { ?><br><i class="bi bi-telephone"></i> <?PHP echo $empl_inte; ?><?PHP } ?>
							<?PHP if ($empl_email != '') {
							?>
								<?PHP /*if ($empl_inte != 0 && $empl_email != '') {
						echo '|';
					} */ ?>
								<br><i class="bi bi-envelope"></i> <a href="mailto:<?PHP echo $empl_email; ?>"> Enviar email</a>
								<!--<br><a onmouseover="tooltip.show('<?PHP //echo $empl_email; ?>');" onmouseout="tooltip.hide();" href="mailto:<?PHP echo $empl_email; ?>">
						<i class="bi bi-envelope"></i></a>-->
							<?PHP } ?>
							<?PHP if ($empl_cump != '00 de 00') { ?><br />Cumplea&ntilde;os: <?PHP echo $empl_cump;
																																							} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?PHP $cont++; ?>
<?PHP } ?>