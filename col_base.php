<?PHP
$col_adic1 = $col_adic2 = '';
$col_base_rest = $col_base_acti = $col_base_sel = $col_where_acti = $col_sel_fec = $col_where_restipo = $col_where_rest = $col_where_fecha = $col_sel_txt = '';
$col_orlimtx = 100;

$tipo_coldet = obtenerDato('nombre', 'tablas', $tipo_col);
switch ($tipo_coldet) {
	case 'clasificados':
		$col_adic1 = ' y Publicar clasificado';
		$col_adic2 = 'Publicar clasificado';
		$col_where_fecha = " AND fecha >= NOW() ";
		$col_orlimtx = 80;
		break;
	case 'recomendados':
		$col_adic1 = ' y Publicar una recomendaci&oacute;n';
		$col_adic2 = 'Publicar una recomendaci&oacute;n';
		break;
	case 'sorteos':
		$col_orlimtx = 145;
		break;
}
$col_nombre_tab = obtenerNombre($tipo_col);
$col_rest_limit = config('col_' . substr($col_nombre_tab, 0, 5));
$col_usafotos  = parametro('fotos', $tipo_col);
$col_usatexto  = parametro('texto', $tipo_col);
$col_usafecha  = parametro('fecha', $tipo_col);
$col_activable = parametro('activable', $tipo_col);
$col_tipotit   = (parametro('titulo', $tipo_col) == '') ? 'titulo' : parametro('titulo', $tipo_col);
$col_order_by  = (parametro("orden", $tipo_col) != '') ? parametro("orden", $tipo_col) : $col_tipotit;
$col_usarest   = parametro('restriccion', $tipo_col);
$col_nombredet = parametro('detalle', $tipo_col);

$col_base_titu = ", inov." . $col_tipotit . " AS titulo ";

if ($col_usarest == 1) {
	$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp'] : 0;
	$col_base_rest = " INNER JOIN " . $_SESSION['prefijo'] . "link AS il ON inov.id = il.item ";

	$col_base_restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp'] : 0;
	//$col_where_rest = " AND (il.part = " . $col_base_restriccion . " OR il.part=0) ";

	$col_where_rest = " AND FIND_IN_SET(" . $restriccion . ",il.part) ";

	$col_where_restipo = " AND il.tipo = " . $tipo_col;
}
if ($col_activable == 1) {
	$col_where_acti = " AND inov.activo = 1 ";
	$col_base_sel = ", inov.activo AS activo ";
}
if ($col_usafecha > 0) {
	$col_sel_fec = ", inov.fecha AS fecha";
}
if ($col_usatexto == 1) {
	$col_sel_txt = ", inov.texto AS texto";
}

$col_sql_rest = " SELECT inov.id AS id " . $col_base_sel . " " . $col_sel_txt . " " . $col_base_titu . " " . $col_sel_fec . " FROM " . $_SESSION['prefijo'] . $col_nombre_tab . " AS inov ";
$col_sql_rest .= $col_base_rest;
$col_sql_rest .= " WHERE 1 " . $col_where_restipo . " " . $col_where_rest . " " . $col_where_acti . " AND inov.del = 0 " . $col_where_fecha . " ORDER BY " . $col_order_by . " LIMIT " . $col_rest_limit;
//echo '<!--'.$col_sql_rest.'-->';

//echo $col_sql_rest;

$col_res_rest = fullQuery($col_sql_rest);
$col_con_rest = mysqli_num_rows($col_res_rest);
if ($col_con_rest > 0) {
?>
	<div id="<?php echo $tipo_coldet; ?>" class="seccion">
		<div class="row item">
			<div class="titulo">
				<h5><?PHP echo $col_nombredet; ?></h5>
			</div>
			<?PHP
			while ($col_row_rest = mysqli_fetch_array($col_res_rest)) {
			?>
				<div class="datos row">
					<?PHP
					$col2 = 'col';
					$col_limtx = $col_orlimtx;
					$col_base_id     = $col_row_rest['id'];
					$col_base_tit      = '<a href="nota.php?tipo=' . $tipo_col . '&id=' . $col_base_id . '">' . txtcod($col_row_rest['titulo']) . '</a>';
					if ($col_usafecha > 0) {
						$col_base_fec = $col_row_rest['fecha'];
						$col_base_fectx = ($col_usafecha == 2) ? 'Vence el ' : '';
					}
					if ($col_usafotos == 1) {
						$col_sql_fotos = "SELECT * FROM " . $_SESSION['prefijo'] . "fotos WHERE item = " . $col_base_id . " AND tipo = " . $tipo_col . " AND ppal = 1";
						$col_res_fotos = fullQuery($col_sql_fotos);
						$col_row_fotos = mysqli_fetch_array($col_res_fotos);
						$col_clasefoto = 0;
						$col_con_fotos = mysqli_num_rows($col_res_fotos);
						if ($col_con_fotos == 1) {
							$col2 .= '-8';
					?>
							<div class="col-4">
								<?PHP
								$col_clasefoto = 1;
								$col_link_foto = $col_row_fotos['link'];
								$col_link_ppal = explode("imagen", $col_link_foto, -1);
								$col_link_ppal = end($col_link_ppal) . "thumb.jpg";
								$col_foto_ppal_clasi = (!file_exists($col_link_ppal)) ? $col_link_foto : $col_link_ppal;
								$col_id_foto   = $col_row_fotos['id'];
								$col_epigrafe  = $col_row_fotos['epigrafe'];
								if (file_exists($col_link_foto)) { ?>
									<img src="<?PHP echo $col_foto_ppal_clasi; ?>" />
								<?PHP } else { ?>
									<img src="cliente/img/noDisponible.jpg" />
								<?PHP } ?>
							</div>
						<?PHP } // Fin si hay fotos 
						?>
					<?PHP } // Fin Usa Fotos	
					?>
					<div class="<?php echo $col2; ?>">
						<?PHP if ($col_usafecha > 0) { ?>
							<span class="fecha"><?PHP echo $col_base_fectx . fechaDet($col_base_fec, 'corto'); ?></span><br />
						<?PHP } ?>
						<div class="tituloitem"><?PHP echo $col_base_tit; ?></div>
						<?PHP if ($col_usatexto == 1) {
							$col_limtx = $col_limtx - (strlen($col_base_tit));
							echo '<p class="texto">' . cortarTexto(txtcod($col_row_rest['texto']), $col_limtx) . '</p>';
						} ?>
					</div>
				</div>
			<?PHP } // fin While
			?>
			<div class="vermas">
				<div class="col">
					<a href="seccion.php?tipo=<?PHP echo $tipo_col; ?>">
						<?PHP if ($col_con_rest > 0) {
							echo 'Ver m&aacute;s' . $col_adic1;
						} else {
							echo $col_adic2;
						} ?> >>
					</a>
				</div>
			</div>
		</div>
	</div>
<?PHP } ?>