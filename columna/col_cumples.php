<?PHP

$mostrar_solo_hoy = 1; // Si está activo muestra en la columna solamente los de hoy



if($tipoani == 1){

	$campo_ani = 'fechanac';

	$titu_widg = 'Cumpleaños';

	$modalnomb = 'cumples';

	$idnomb = 'cumpleanos';

}else{

	$campo_ani = 'fechaing';

	$titu_widg = 'aniversarios';

	$modalnomb = 'aniver';

	$idnomb = 'aniversarios';

}

$campo_ani = ($tipoani == 1) ? 'fechanac' : 'fechaing';

$dif = cantidad('diferencia');

//$dif2 = cantidad('difepost');



$usalugar = 1;

$fechahoy = date('Y-m-d');

$cump_select = ", DATE_FORMAT(".$campo_ani.", '%M %d') AS bday, CONCAT(IF(MONTH(".$campo_ani.")=1 

		AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(".$campo_ani.", '-%m-%d')) AS fakebday 

		FROM " . $_SESSION['prefijo'] . "empleados AS emp

		INNER JOIN " . $_SESSION['prefijo'] . "empresas AS empr ON empr.id = emp.empresa

		";

$cump_join = '';

if ($usalugar == 1) {

	$cump_join = " LEFT JOIN " . $_SESSION['prefijo'] . "empleados_lugar lug ON lug.id = emp.lugar ";

}

$cump_where_adic = "

		

		AND CONCAT(IF(MONTH(".$campo_ani.")=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(".$campo_ani.", '-%m-%d')) 

		BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL " . $dif . " DAY) 

		";

$cump_where = "

		WHERE 1

		AND ".$campo_ani." != '1111-11-11'

		AND emp.del = 0

		AND activo = 1

";

$where_hoy = " AND month(".$campo_ani.") = '" . date("m") . "' AND day(".$campo_ani.") = '" . date("d") . "' ";

$cump_orden = "ORDER BY fakebday, emp.nombre ";



$where_alternativa = ($mostrar_solo_hoy == 1) ? $where_hoy : $cump_where_adic;



$cump_sql = "SELECT emp.nombre, emp.".$campo_ani." " . $cump_select . $cump_join . $cump_where . $cump_where_adic . $cump_orden;



$cump_result = fullQuery($cump_sql);

$contar = mysqli_num_rows($cump_result);

if ($contar >= 1) {

	$cump_sql = "SELECT emp.id, emp.nombre, emp.apellido, emp.empresa, emp.puesto AS puesto, emp.email, emp.interno, emp.".$campo_ani." ";

	if ($usalugar == 1) {

		$cump_sql .= ",lug.nombre AS lugar ";

	}

	//$cump_sql_parcial = $cump_sql . $cump_select . $cump_join . $cump_where . $where_alternativa . $cump_orden . "limit " . cantidad('home_cumpl');

	$cump_sql_parcial = $cump_sql . $cump_select . $cump_join . $cump_where . $where_alternativa . $cump_orden;

	$cump_result = fullQuery($cump_sql_parcial);



	//if ( ( $mostrar_solo_hoy == 0 && $contar >= 1) || $mostrar_solo_hoy == 1 ) {

	//$cump_result = fullQuery($cump_sql);

?>

	<div id="<?php echo $idnomb;?>" class="card">

		<div class="card-body">


				<h4><?php echo ucfirst($titu_widg);?></h4>


			<div class="datos">

			<?PHP

			$empleCumple = '';

			while ($cump_emp = mysqli_fetch_array($cump_result)) {

				$empleCumple .= traerEmpleado($cump_emp, $multiemp, $campo_ani);

			}

			echo $empleCumple;

			if($empleCumple == ''){

				echo 'No hay '.$titu_widg.' el día de hoy.';

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

				

					<a href="javascript:void()" class="btn btn-outline-dark btn-sm more icon-link icon-link-hover" data-bs-toggle="modal" data-bs-target="#<?php echo $modalnomb;?>">Próximos <?php echo ucfirst($titu_widg);?> (<?php echo $contar; ?>) <i class="bi bi-chevron-right icon-link icon-link-hover"></i></a>

				

			<?php

				$empleCumple = '';

				while ($cump_emp = mysqli_fetch_array($cump_result)) {

					$empleCumple .= '<div class="col"><div class="card h-100">' . traerEmpleado($cump_emp, $multiemp, $campo_ani) . '</div></div>';

				}

			}

			?>

		</div>

	</div>

	<div class="modal fade" id="<?php echo $modalnomb;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-xl modal-fullscreen-xl-down">

			<div class="modal-content">

				<div class="modal-header">

					<h4 class="modal-title" id="exampleModalLabel">Próximos <?php echo ucfirst($titu_widg);?></h4>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

				</div>

				<div class="modal-body row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

					<?php echo $empleCumple; ?>

				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>

				</div>

			</div>

		</div>

	</div>







<?PHP } ?>