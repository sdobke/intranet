<?php

$modoprivado = 1;

$divmodo = '';

if($modoprivado == 1){$divmodo = 'class="fleft"';}

if (isset($id) && isset($tipo)) {

	$linkMeGusta = "";

	$megusvoto = 0;

	if (isset($_SESSION['usrfrontend'])) {

		$sql = "SELECT * FROM `intranet_me_gusta` WHERE `item` =" . $id . "	AND `tipo` ={$tipo}	AND `usuario_id` =" . $_SESSION['usrfrontend'];

		//echo $sql;

		$res = fullQuery($sql);

		if (mysqli_num_rows($res) == 0) {

			/*

			 * nuevo voto para esta seccion

			 */

			if (isset($_GET['usuario_id'])) {

				$insert = "INSERT INTO intranet_me_gusta (item, tipo, usuario_id, fecha) VALUES ('{$id}', '{$tipo}', '" . $_GET['usuario_id'] . "','".date("Y-m-d")."');";

				$reinse = fullQuery($insert);

				$megusvoto = 1;

			} else {

				/*

				 * si el usuario logueado no tiene votos, este puede votar

				 */

				$linkMeGusta  = $_SERVER['SCRIPT_NAME'] . "?id=" . $id . "&tipo={$tipo}&usuario_id=" . $_SESSION['usrfrontend'];

				$linkMeGusta = "<span ".$divmodo."><a href='" . $linkMeGusta . "' class='btn btn-primary icon-link'><i class='bi bi-hand-thumbs-up'></i>Me gusta <i class='far fa-thumbs-up'></i></a></span>";

			}

		} else {

			$megusvoto = 1;

		}

	}

	echo '<div class="megusta row g-0"><div class="col-12">';

	echo $linkMeGusta;

	/*

	* cuenta los me gusta de esta seccion

	*/

	$sql = "SELECT * FROM `intranet_me_gusta` WHERE `item` =" . $id . "	AND `tipo` ={$tipo}";

	$res = fullQuery($sql);

	$totalGusta = mysqli_num_rows($res);

	if ($totalGusta > 0) {

		if($modoprivado == 1){

			$divmodo = substr($divmodo,0,-1).' megustacant"';

		}

		

		echo '<span '.$divmodo.'>';

		$nomgus = '';

		$congus = 0;

		while ($row = mysqli_fetch_array($res)) {

			if ($row['usuario_id'] == $_SESSION['usrfrontend']) {

				$megusvoto = 1;

				$totalGusta--;

			} else {

				if ($modoprivado == 0) {



					$lnkgus = 'cliente/fotos/' . $row["usuario_id"] . '.jpg';

					$fotgus = (file_exists($lnkgus)) ? "<img src='" . $lnkgus . "' height='50'/>" : '';

					$nomgus .= (file_exists($lnkgus)) ? '<div class="fleft tip txazul" data-tip="' . $fotgus . '">' : '<span>';

					$nyape = ucwords(strtolower(txtcod(obtenerDato('nombre,apellido', 'empleados', $row['usuario_id']))));

					if ($congus > 0) {

						$nomgus .= ',&nbsp;';

					}

					$nomgus .= $nyape;

					$nomgus .= '</span>';

				}

				$congus++;

			}

		}

		if($modoprivado == 1){

			$nomgus = '<span>'.$totalGusta . ' persona';

			if ($totalGusta > 1) {

				$nomgus .= 's';

			}

			$nomgus.='</span>';

		}

		$plur = ($totalGusta <> 1) ? 's' : '';

		if ($megusvoto == 1) {

			//$totalGusta1 = $totalGusta - 1;

			$txtotv = ($totalGusta > 0) ? '<span class="ms-2">Esto le gusta a&nbsp;</span>' . $nomgus . '<span>&nbsp;y a vos.</span>' : '<span>Esto te gusta.</span>';

			echo $txtotv . '<span>&nbsp;<i class="far fa-thumbs-up"></i></span>';

		} else {

			if ($totalGusta > 0) {

				echo '<span class="ms-2">Esto le gusta a&nbsp;</span>' . $nomgus . '&nbsp;';

			}

		}

		echo '</span>';

	}

	echo '</div></div>';

}

