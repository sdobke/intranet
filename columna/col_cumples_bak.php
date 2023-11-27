<?PHP
$mostrar_solo_hoy = 1; // Si está activo muestra en la columna solamente los de hoy
function traerEmpleado($row, $multiemp)
{
	$dev = '<div class="row cusuario">';
	$file = "cliente/fotos/" . $row['id'] . ".jpg";
	$cump_link_foto = (file_exists($file)) ? $file : "/cliente/fotos/sinfoto.jpg";
	$cump_fecha = (substr($row['fechanac'], 5, 5) == date("m-d")) ? "Hoy" : FechaDet($row['fechanac'], 'diames');
	$cnom = explode(" ", $row['nombre']);
	$cnom = current($cnom);
	$cump_nombre = ucwords(txtcod(strtolower($row['apellido'] . ", " . $cnom)));
	$cump_email = $row['email'];
	$cump_nomail = '';
	if ($cump_email != '' && $cump_email != '-') {
		$cump_nomail .= '<a href="mailto:' . $row['email'] . '" title="' . $row['email'] . '">';
	}
	$cump_nomail .= $cump_nombre;
	if ($cump_email != '') {
		$cump_nomail .= '</a>';
	}
	//$cump_int = (is_numeric($row['interno']) &&  $row['interno'] > 0) ? '(Int. ' . $row['interno'] . ')' : '';
	$cump_lug = ($row['lugar'] != '') ? '<br>' . txtcod($row['lugar']) : '';
	$dev .= '
		<div class="col-4">
		';
	//<a href="empleados.php?id=' . $row['id'] . '">
	$dev .= '<img src="' . $cump_link_foto . '" alt="' . $cump_nombre . '" />';
	//</a>
	$dev .= '</div>
		<div class="col-8">
			<span>' . $cump_fecha . '</span>
			<div class="nombre">' . $cump_nomail . '</div>
			';
	if ($multiemp == 1) {
		$dev .= empresa($row['empresa']);
	}
	$userarea = txtcod(obtenerDato('nombre', 'areas', $row['area']));
	if ($multiemp == 1 && $userarea != '') {
		$dev .= ' | ';
	}
	if ($userarea != '') {
		$dev .= $userarea;
	}
	$dev .= $cump_lug;
	$dev .= '
	</div></div>';
	return $dev;
}
$dif = cantidad('diferencia');
//$dif2 = cantidad('difepost');

$usalugar = 1;
$fechahoy = date('Y-m-d');
$cump_select = ", DATE_FORMAT(fechanac, '%M %d') AS bday, CONCAT(IF(MONTH(fechanac)=1 
		AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(fechanac, '-%m-%d')) AS fakebday 
		FROM " . $_SESSION['prefijo'] . "empleados AS emp
		INNER JOIN " . $_SESSION['prefijo'] . "empresas AS empr ON empr.id = emp.empresa
		";
$cump_join = '';
if ($usalugar == 1) {
	$cump_join = " LEFT JOIN " . $_SESSION['prefijo'] . "empleados_lugar lug ON lug.id = emp.lugar ";
}
$cump_where_adic = "
		
		AND CONCAT(IF(MONTH(fechanac)=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(fechanac, '-%m-%d')) 
		BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL " . $dif . " DAY) 
		";
$cump_where = "
		WHERE 1
		AND fechanac != '1111-11-11'
		AND emp.del = 0
		AND activo = 1
";
$where_hoy = " AND month(fechanac) = '" . date("m") . "' AND day(fechanac) = '" . date("d") . "' ";
$cump_orden = "ORDER BY fakebday, emp.nombre ";

$where_alternativa = ($mostrar_solo_hoy == 1) ? $where_hoy : $cump_where_adic;

$cump_sql = "SELECT emp.nombre, emp.fechanac " . $cump_select . $cump_join . $cump_where . $cump_where_adic . $cump_orden;

$cump_result = fullQuery($cump_sql);
$contar = mysqli_num_rows($cump_result);
if ($contar >= 1) {
	$cump_sql = "SELECT emp.id, emp.nombre, emp.apellido, emp.empresa, emp.area, emp.puesto AS puesto, emp.email, emp.interno, emp.fechanac ";
	if ($usalugar == 1) {
		$cump_sql .= ",lug.nombre AS lugar ";
	}
	//$cump_sql_parcial = $cump_sql . $cump_select . $cump_join . $cump_where . $where_alternativa . $cump_orden . "limit " . cantidad('home_cumpl');
	$cump_sql_parcial = $cump_sql . $cump_select . $cump_join . $cump_where . $where_alternativa . $cump_orden;
	$cump_result = fullQuery($cump_sql_parcial);

	//if ( ( $mostrar_solo_hoy == 0 && $contar >= 1) || $mostrar_solo_hoy == 1 ) {
	//$cump_result = fullQuery($cump_sql);
?>
	<div id="cumples" class="seccion">
		<div class="row item">
			<div class="titulo">
				<h5>Cumplea&ntilde;os</h5>
			</div>
			<div class="datos row">
			<?PHP
			$empleCumple = '';
			while ($cump_emp = mysqli_fetch_array($cump_result)) {
				$empleCumple .= traerEmpleado($cump_emp, $multiemp);
			}
			echo $empleCumple;
			if($empleCumple == ''){
				echo '<div class="col">No hay cumpleaños el día de hoy.</div>';
			}
			?>
			</div>
			<?php
			// MODAL

			$sql_partes = $cump_select . $cump_join . $cump_where . $cump_where_adic . $cump_orden;
			$cump_sql_full = $cump_sql . $sql_partes;
			$cump_result = fullQuery($cump_sql_full);
			if ($contar >= 1) {
			?>
				<div class="vermas">
					<div class="col"><a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#cumpleanos">Próximos Cumpleaños (<?php echo $contar; ?>) >> </a></div>
				</div>
			<?php
				$empleCumple = '';
				while ($cump_emp = mysqli_fetch_array($cump_result)) {
					$empleCumple .= '<div class="col-xs-4 col-sm-4 col-lg-3" style="float:left">' . traerEmpleado($cump_emp, $multiemp) . '</div>';
				}
			}
			?>
		</div>
	</div>
	<div class="modal fade" id="cumpleanos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-fullscreen-xl-down">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Próximos Cumpleaños</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<?php echo $empleCumple; ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>



<?PHP } ?>