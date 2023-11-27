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
				$insert = "INSERT INTO intranet_me_gusta (item, tipo, usuario_id) VALUES ('{$id}', '{$tipo}', '" . $_GET['usuario_id'] . "');";
				$reinse = fullQuery($insert);
				$megusvoto = 1;
			} else {
				/*
				 * si el usuario logueado no tiene votos, este puede votar
				 */
				$linkMeGusta  = $_SERVER['SCRIPT_NAME'] . "?id=" . $id . "&tipo={$tipo}&usuario_id=" . $_SESSION['usrfrontend'];
				$linkMeGusta = "<div ".$divmodo."><a href='" . $linkMeGusta . "' class='btn btn-primary'>Me gusta <i class='far fa-thumbs-up'></i></a></div>";
			}
		} else {
			$megusvoto = 1;
		}
	}
	echo '<div class="megusta row"><div class="col-12">';
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
		
		echo '<div '.$divmodo.'>';
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
					$nomgus .= (file_exists($lnkgus)) ? '<div class="fleft tip txazul" data-tip="' . $fotgus . '">' : '<div>';
					$nyape = ucwords(strtolower(txtcod(obtenerDato('nombre,apellido', 'empleados', $row['usuario_id']))));
					if ($congus > 0) {
						$nomgus .= ',&nbsp;';
					}
					$nomgus .= $nyape;
					$nomgus .= '</div>';
				}
				$congus++;
			}
		}
		if($modoprivado == 1){
			$nomgus = '<div class="fleft">'.$totalGusta . ' persona';
			if ($totalGusta > 1) {
				$nomgus .= 's';
			}
			$nomgus.='</div>';
		}
		$plur = ($totalGusta <> 1) ? 's' : '';
		if ($megusvoto == 1) {
			//$totalGusta1 = $totalGusta - 1;
			$txtotv = ($totalGusta > 0) ? '<div class="fleft">Esto le gusta a&nbsp;</div>' . $nomgus . '<div class="fleft">&nbsp;y a vos.</div>' : '<div class="fleft">Esto te gusta.</div>';
			echo $txtotv . '<div class="fleft">&nbsp;<i class="far fa-thumbs-up"></i></div>';
		} else {
			if ($totalGusta > 0) {
				echo '<div class="fleft">Esto le gusta a&nbsp;</div>' . $nomgus . '&nbsp;<i class="far fa-thumbs-up"></i>';
			}
		}
		echo '</div>';
	}
	echo '</div></div>';
}
