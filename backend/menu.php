<?PHP
function ActMenu($item, $getvar = '', $adic = 0)
{
	$phpself = explode('/', $_SERVER['PHP_SELF']);
	$nomact = end($phpself);
	if ($item == 'estadisticas.php') {
		$nomact = explode('.php', $nomact);
		$nomact = current($nomact);
		$nomact = substr($nomact, 0, -2);
		$item = 'estadisticas';
	}
	//echo $nomact.' | '.$item;
	$devolver = ($nomact == $item) ? ' class="active"' : '';
	if ($adic > 0 && isset($_GET[$getvar])) {
		if ($adic == $_GET[$getvar]) {
			$devolver = ' class="active"';
		} else {
			$devolver = '';
		}
	}
	if (($getvar == 'nada' && isset($_GET['secc']) || ($getvar == 'secc' && !isset($_GET['secc'])))) {
		$devolver = '';
	}
	return $devolver;
}
$cadmu = 0;
if (isset($_SESSION['usrfrontend'])) {
	$sadmu = "SELECT * FROM intranet_usr_adm WHERE usuario = " . $_SESSION['usrfrontend'] . " AND del = 0";
	$radmu = fullQuery($sadmu);
	$cadmu = mysqli_num_rows($radmu);
	if ($cadmu > 0) {
		$row_admu = mysqli_fetch_assoc($radmu);
	}
}
$encadm = 0;
?>
<aside id="sidebar">
	<nav id="navigation" class="collapse">
		<ul>
			<?PHP
			$backact = (ActMenu('index.php')) ? ' class="active"' : '';
			if ($_SESSION['backend'] = md5('Backend4dmn!')) { ?>
				<li <?PHP echo $backact; ?>>
				<a href="index.php">
					<span title="General">
						<i class="icon-home"></i>
						<span class="nav-title">General</span>
					</span>
				</a>
					<ul class="inner-nav">
						<li <?PHP echo $backact; ?>><a href="index.php"><i class="icol-dashboard"></i> Backend</a></li>
					</ul>
				</li>
			<?PHP } ?>
			<?PHP
			$tipo = GetPost('tipo');
			$wheremenu = '';
			if ($cadmu > 0) {
				$concadmu = 1;
				$idsadm = '';
				$rademu = fullQuery($sadmu);
				while ($row_admu = mysqli_fetch_array($rademu)) {
					if ($row_admu['tabla'] == 52) {
						$encadm = 1;
					}
					if ($concadmu > 1) {
						$idsadm .= ', ';
					}
					$idsadm .= $row_admu['tabla'];
					$concadmu++;
				}
				$wheremenu = " AND id IN (" . $idsadm . ")";
			}
			$sql_menu = "SELECT * FROM " . $_SESSION['prefijo'] . "tablas WHERE 1 " . $wheremenu . " AND menu = 1 ORDER BY ordenmenu, detalle";
			$res_menu = fullQuery($sql_menu);
			while ($row_menu = mysqli_fetch_array($res_menu)) {
				$menuid = $row_menu['id'];
				$menunom = $row_menu['nombre'];
				$item_sel = ($tipo == $menuid) ? ' class="active"' : '';
				$icono_arch = ($row_menu['icono'] != '') ? $row_menu['icono'] : 'certificate';
				echo '<li' . $item_sel . '>';
				echo '<a href="listado.php?tipo=' . $menuid .'">';
				echo '<span title="' . txtcod($row_menu['detalle']) . '">';
				echo '	<i class="icon-' . $icono_arch . '"></i>';
				echo '	<span class="nav-title">' . txtcod($row_menu['detalle']) . '</span>';
				echo '	</span>';
				echo '</a>';
				echo '<ul class="inner-nav">';
				if ($row_menu['ordenable'] == 1) {
					echo '<li' . ActMenu('ordenar.php') . '><a href="ordenar.php?tipo=' . $menuid . '&ord=1"><i class="icol-sort"></i> Ordenar</a></li>';
				}
				if ($menunom == 'novedades') {
					$sql_menov = "SELECT sec.nombre AS nom, sec.id FROM " . $_SESSION['prefijo'] . "secciones AS sec WHERE sec.del = 0 ORDER BY sec.nombre";
					$res_menov = fullQuery($sql_menov);
					while ($row_menov = mysqli_fetch_array($res_menov)) {
						echo '<li' . ActMenu('listado.php', 'secc', $row_menov['id']) . '><a href="listado.php?tipo=' . $menuid . '&secc=' . $row_menov['id'] . '"><i class="icol-application-cascade"></i> ' . ucwords(txtcod($row_menov['nom'])) . '</a></li>';
					}
				}
				//echo '<li'.ActMenu('listado.php').'><a href="listado.php?tipo='.$menuid.'"><i class="icol-application-view-list"></i> Listado</a></li>';
				if ($menunom == 'organigrama' || $menunom == 'codigo') {
					echo '<li' . ActMenu('organigramas.php', 'nada', 0) . '><a href="organigramas.php?tipo=' . $menuid . '"><i class="icol-application-view-list"></i> Modificar Documentos</a></li>';
				} else {
					echo '<li' . ActMenu('listado.php', 'nada', 0) . '><a href="listado.php?tipo=' . $menuid . '"><i class="icol-application-view-list"></i> Listado</a></li>';
					if ($row_menu['alta'] == 1) {
						echo '<li' . ActMenu('alta.php') . '><a href="alta.php?tipo=' . $menuid . '"><i class="icol-add"></i> Nuevo/a</a></li>';
					}
				}
				echo '</ul>';
				echo '</li>
				';
			}
			/*
			if($_SESSION['id_usr'] == 1){
                $minisit = (ActMenu('minisitios.php')) ? ' class="active"' : '';
                if(config("minisitios") == 1){ ?>
                    <li<?PHP echo $minisit;?>>
                        <span title="Minisitios">
                            <i class="icon-certificate"></i>
                            <span class="nav-title">Minisitios</span>
                        </span>
                        <ul class="inner-nav">
                            <li <?PHP echo $minisit;?>><a href="minisitios.php"><i class="icol-dashboard"></i> Acceder</a></li>
                        </ul>
                    </li>
                <?PHP } ?>
			<?PHP } */ ?>
			<?PHP
			/*
			if($encadm == 1 || $_SESSION['id_usr'] == 1){?>
                <li>
                    <span title="Encuestas">
                        <i class="icon-certificate"></i>
                        <span class="nav-title">Encuestas</span>
                    </span>
                    <ul class="inner-nav">
                        <li><a href="../encuestas/inicio.php"><i class="icol-dashboard"></i> Acceder</a></li>
                    </ul>
                </li>
            <?PHP }*/ ?>
			<?PHP
			
            $stats = (ActMenu('estadisticas.php')) ? ' class="active"' : '';
			//if($_SESSION['id_usr'] == 1){?>
                <li <?PHP echo $stats;?>>
                    <span title="Estad&iacute;sticas">
                        <i class="icon-stats-up"></i>
                        <span class="nav-title">Estad&iacute;sticas</span>
                    </span>
                    <?PHP
                    $stat2 = (ActMenu('estadisticas02.php')) ? ' class="active"' : '';
                    $stat3 = (ActMenu('estadisticas03.php')) ? ' class="active"' : '';
                    ?>
                    <ul class="inner-nav">
                        <li <?PHP echo ActMenu('estadisticas01.php','nada',0);?>><a href="estadisticas01.php"><i class="icol-dashboard"></i> Por d&iacute;a</a></li>
                        <li <?PHP echo ActMenu('estadisticas02.php','nada',0);?>><a href="estadisticas02.php"><i class="icol-dashboard"></i> Ranking de p&aacute;ginas</a></li>
                        <li <?PHP echo ActMenu('estadisticas03.php','nada',0);?>><a href="estadisticas03.php"><i class="icol-dashboard"></i> Secciones por meses</a></li>
                        <li <?PHP echo ActMenu('estadisticas06.php','nada',0);?>><a href="estadisticas06.php"><i class="icol-dashboard"></i> Me Gusta</a></li>
						<li <?PHP echo ActMenu('estadisticas08.php','nada',0);?>><a href="estadisticas08.php"><i class="icol-dashboard"></i> Comentarios</a></li>
						<li <?PHP echo ActMenu('estadisticas09.php','nada',0);?>><a href="estadisticas09.php"><i class="icol-dashboard"></i> Por genero</a></li>
						<li <?PHP echo ActMenu('estadisticas10.php','nada',0);?>><a href="estadisticas10.php"><i class="icol-dashboard"></i> Por rango etario</a></li>
						<li <?PHP echo ActMenu('estadisticas11.php','nada',0);?>><a href="estadisticas11.php"><i class="icol-dashboard"></i> Por Area</a></li>
						<!-- <li <?PHP// echo ActMenu('estadisticas12.php','nada',0);?>><a href="estadisticas12.php"><i class="icol-dashboard"></i> Accesos a información por medio</a></li> -->
                    </ul>
                </li>
            <?PHP //} ?>

		</ul>
	</nav>
	<div style="display:none;" class="nav_up" id="nav_up"></div>
	<div style="display:none;" class="nav_down" id="nav_down"></div>
</aside>