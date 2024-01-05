<?PHP // Comentarios
$con_comc = 0;
$sql_comtabs = "SELECT id FROM " . $_SESSION['prefijo'] . "tablas WHERE comentarios = 1";
$res_comtabs = fullQuery($sql_comtabs);
$contarhdr = mysqli_num_rows($res_comtabs);
if ($contarhdr > 0) {
	while ($row_comtabs = mysqli_fetch_array($res_comtabs)) {
		$tipo = $row_comtabs['id'];
		$tabnom = obtenerDato('nombre', 'tablas', $tipo);
		$tabtit  = parametro('titulo', $tipo);
		$sql_tabdet = "SELECT com.comentario AS cmt, com.fecha, com.id AS comid, nov.id AS novid, nov." . $tabtit . " AS tit 
			FROM " . $_SESSION['prefijo'] . "comentarios AS com INNER JOIN " . $_SESSION['prefijo'] . $tabnom . " AS nov 
			ON nov.id = com.item 
			WHERE com.activo = 0 AND com.tipo = " . $tipo . " 
			ORDER BY com.fecha 
			";
		$res_tabdet = fullQuery($sql_tabdet);
		$com_tabdet = mysqli_num_rows($res_tabdet);
		$con_comc = $con_comc + $com_tabdet;
	}
}

// Nuevos Clasificados y Recomendados

$vercla = '';
$sqlcl = "SELECT * FROM " . $_SESSION['prefijo'] . "clasificados WHERE del = 0 AND activo = 0";
$rescl = fullQuery($sqlcl);
$concl = mysqli_num_rows($rescl);
if ($concl > 0) {
	$claplu = ($concl > 1) ? 's' : '';
	$vercla = '<li><a href="listado.php?tipo=18">Hay ' . $concl . ' clasificado' . $claplu . ' para moderar. Haga click ac&aacute; para ir al listado de clasificados</a></li>';
}
$verrec = '';
$sqlre = "SELECT * FROM " . $_SESSION['prefijo'] . "recomendados WHERE del = 0 AND activo = 0";
$resre = fullQuery($sqlre);
$conre = mysqli_num_rows($resre);
if ($conre > 0) {
	$recplu = ($conre > 1) ? 's' : '';
	$verrec = '<li><a href="listado.php?tipo=14">Hay ' . $conre . ' recomendado' . $recplu . ' para moderar. Haga click ac&aacute; para ir al listado de recomendados</a></li>';
}

// Cumpleaños y Aniversarios

// CUMPLEAÑOS
$avicump = '';
$con_cump = $con_conf = 0;
/*
$campo_comp = 'fechanac';
$sql_cump = "SELECT id, fechaing, confirmani,
				DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(" . $campo_comp . "), DAY(" . $campo_comp . ") ), SYSDATE() ) AS diferencia 
			FROM intranet_empleados AS emp
			WHERE CONCAT(IF(MONTH(" . $campo_comp . ")=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(" . $campo_comp . ", '-%m-%d')) BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
				AND fechanac != '1111-11-11'
				AND empresa < 3
				AND confirmado = 0
				AND activo = 1
				AND area < 1000
				";
//echo '<!--'.$sql_conf.'-->';
$res_cump = fullQuery($sql_cump);
$con_cump = mysqli_num_rows($res_cump);
if ($con_cump > 0) {
	$avicump = '<li><a href="alertas.php">Hay ' . $con_cump . ' cumplea&ntilde;os para confirmar. Haga click ac&aacute; para ir a la p&aacute;gina de confirmaci&oacute;n.</a></li>';
}
// ANIVERSARIOS
$aviani = '';
$campo_comp = 'fechaing';
$sql_conf = "SELECT id, fechaing, confirmani,
				DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(" . $campo_comp . "), DAY(" . $campo_comp . ") ), SYSDATE() ) AS diferencia 
			FROM intranet_empleados AS emp
			WHERE CONCAT(IF(MONTH(" . $campo_comp . ")=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(" . $campo_comp . ", '-%m-%d')) BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
				AND fechaing != '1111-11-11'
				AND empresa < 3
				AND confirmani = 0
				AND activo = 1
				AND area < 1000
				";

$res_conf = fullQuery($sql_conf);
$con_conf = mysqli_num_rows($res_conf);
if ($con_conf > 0) {
	$aviani = '<li><a href="alertas.php">Hay ' . $con_conf . ' aniversarios para confirmar. Haga click ac&aacute; para ir a la p&aacute;gina de confirmaci&oacute;n.</a></li>';
}
*/
// CAMBIOS EMPLEADOS
$cant_alt = $cant_baj = 0;
$veralt = $verbaj = '';

if (config("altaemp") == 1) {
	/*
	if ($cliente == 'alsea') {
		// ALTAS
		$sql_empa = "SELECT valor FROM " . $_SESSION['prefijo'] . "config WHERE parametro = 'altas'";
		$res_empa = fullQuery($sql_empa);
		$row_empa = mysqli_fetch_assoc($res_empa);
		$cant_alt = $row_empa['valor'];
		if ($cant_alt > 0) {
			$veralt = '<li><a href="emplemod.php">Hay ' . $cant_alt . ' empleados para dar de alta. Haga click ac&aacute; para ir a la p&aacute;gina de configuraci&oacute;n.</a></li>';
		}

		// BAJAS
		$sql_empb = "SELECT valor FROM " . $_SESSION['prefijo'] . "config WHERE parametro = 'bajas'";
		$res_empb = fullQuery($sql_empb);
		$row_empb = mysqli_fetch_assoc($res_empb);
		$cant_baj = $row_empb['valor'];
		if ($cant_baj > 0) {
			$verbaj = '<li><a href="emplemod.php">Hay ' . $cant_baj . ' empleados para dar de baja. Haga click ac&aacute; para ir a la p&aacute;gina de configuraci&oacute;n.</a></li>';
		}
	}
	*/
}
$hayalerta = ($con_conf > 0 || $con_cump > 0 || $con_comc > 0 || $cant_alt > 0 || $cant_baj > 0 || $concl > 0 || $conre > 0) ? 1 : 0;
$totalerts = $con_conf + $con_cump + $con_comc + $cant_alt + $cant_baj + $concl + $conre;
//echo $con_conf.' | '.$con_cump.' | '.$con_comc.' | '.$cant_alt.' | '.$cant_baj;
?>
<div id="dropdown-lists">

	<div class="item-wrap">
		<?PHP if ($hayalerta == 1) { ?><a class="item" href="#" data-toggle="dropdown"><?PHP } ?>
			<span class="item-icon"><i class="icon-exclamation-sign"></i></span>
			<span class="item-label">Notificaciones</span>
			<?PHP if ($hayalerta == 1) { ?><span class="item-count"><?PHP echo $totalerts; ?></span><?PHP } ?>
			<?PHP if ($hayalerta == 1) { ?></a><?PHP } ?>
		<?PHP if ($hayalerta == 1) { ?>
			<ul class="dropdown-menu">
				<li class="dropdown-item-wrap">
					<ul>
						<?PHP
						if ($con_comc > 0) {
							$sql_comtabs = "SELECT id FROM " . $_SESSION['prefijo'] . "tablas WHERE comentarios = 1";
							$res_comtabs = fullQuery($sql_comtabs);
							while ($row_comtabs = mysqli_fetch_array($res_comtabs)) {
								$idtabtipo = $row_comtabs['id'];
								$tabnom = obtenerDato('nombre', 'tablas', $idtabtipo);
								$tabtit  = parametro('titulo', $idtabtipo);
								$sql_tabdet = "SELECT nov.id, nov." . $tabtit . " AS tit FROM " . $_SESSION['prefijo'] . "comentarios AS com 
				INNER JOIN " . $_SESSION['prefijo'] . $tabnom . " AS nov ON nov.id = com.item WHERE com.activo = 0 AND com.tipo = " . $idtabtipo . " 
				GROUP BY com.tipo, com.item ORDER BY com.fecha LIMIT 10";
								$res_tabdet = fullQuery($sql_tabdet);
								while ($row_tabdet = mysqli_fetch_array($res_tabdet)) {
									echo '<li>
							<a href="detalles.php?tipo=' . $idtabtipo . '&id=' . $row_tabdet['id'] . '">
								<!--<span class="thumbnail"><img src="assets/images/pp.jpg" alt=""></span>-->
								<!--<span class="details">-->
									<strong>Existen comentarios</strong> para aprobar en:
									<span class="time">' . txtcod($row_tabdet['tit']) . '</span>
								<!--</span>-->
							</a>
						</li>';
								}
							}
						}
						//echo $avicump;
						//echo $aviani;
						echo $veralt;
						echo $verbaj;
						echo $vercla;
						echo $verrec;
						?>
					</ul>
				</li>
				<li><a href="alertas.php">Ver todas</a></li>
			</ul>
		<?PHP } ?>
	</div>
</div>