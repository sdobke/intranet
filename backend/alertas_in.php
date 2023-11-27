<?PHP
$sql_comtabs = "SELECT id,nombre FROM " . $_SESSION['prefijo'] . "tablas WHERE comentarios = 1";
//echo $sql_comtabs;
$res_comtabs = fullQuery($sql_comtabs);
$contar = mysqli_num_rows($res_comtabs);
if ($contar > 0) {
	while ($row_comtabs = mysqli_fetch_array($res_comtabs)) {
		$tipo = $row_comtabs['id'];
		$nombre = $row_comtabs['nombre'];
		$tabnom = obtenerDato('nombre', 'tablas', $tipo);
		$tabtit  = parametro('titulo', $tipo);
		$usafotos = parametro('fotos', $tipo);
		$sql_tabdet = "SELECT com.comentario AS cmt, com.fecha, com.id AS comid, nov.id AS novid, nov." . $tabtit . " AS tit 
						FROM " . $_SESSION['prefijo'] . "comentarios AS com INNER JOIN " . $_SESSION['prefijo'] . $tabnom . " AS nov 
						ON nov.id = com.item 
						WHERE com.activo = 0 AND com.tipo = " . $tipo . " 
						ORDER BY com.fecha 
						";
		//echo $sql_tabdet;
		$res_tabdet = fullQuery($sql_tabdet);
		$con_tabdet = mysqli_num_rows($res_tabdet);
		if ($con_tabdet > 0) {
			?>
			<div class="row-fluid">
				<div class="widget">
					<div class="widget-header">
						<span class="title">Comentarios <?php echo $nombre; ?></span>
					</div>
					<div class="widget-content table-container">
						<form method="POST" action="alertas.php" id="formulario">
							<input name="tipo" type="hidden" value="<?PHP echo $tipo; ?>" />
							<table class="table table-striped table-checkable">
								<thead>
									<tr>
										<th class="checkbox-column">
											<i class="icol-accept"></i>
											<!--<input type="checkbox" class="uniform">-->
										</th>
										<th class="checkbox-column">
											<i class="icol-cancel"></i>
											<!--<input type="checkbox" class="uniform2">-->
										</th>
										<th>Secci&oacute;n</th>
										<th>T&iacute;tulo</th>
										<th>Comentario</th>
										<th>Fecha</th>
									</tr>
								</thead>
								<tbody>
									<?PHP
									while ($row_tabdet = mysqli_fetch_array($res_tabdet)) {
										$comid = $row_tabdet['comid'];
										$novid = $row_tabdet['novid'];
										$comentario = txtcod($row_tabdet['cmt']);
									?>
										<tr>
											<td class="checkbox-column">
												<input type="radio" class="uniform" name="aprobar_<?PHP echo $comid; ?>" value="1">
												<input name="id" type="hidden" id="id" value="<?PHP echo $comid; ?>" />
											</td>
											<td class="checkbox-column">
												<input type="radio" class="uniform" name="aprobar_<?PHP echo $comid; ?>" value="2">
												<input name="id" type="hidden" id="id" value="<?PHP echo $comid; ?>" />
											</td>

											<?PHP
											if ($usafotos == 1) { // imagen principal
												$item = $novid;
												echo '<td width="120"><a href="detalles.php?tipo=' . $tipo . '&id=' . $novid . '">';
												include("inc/muestra_imagen_ppal.php");
												echo '</a></td>';
											} ?>

											<td width="400"><a href="detalles.php?tipo=<?PHP echo $tipo; ?>&id=<?PHP echo $row_tabdet['novid']; ?>"><?PHP echo txtcod($row_tabdet['tit']); ?></a></td>
											<td width="400"><?PHP echo $comentario; ?></td>
											<td width="100"><?PHP echo FechaDet($row_tabdet['fecha'], 'puntos'); ?></td>
										</tr>
									<?PHP } //Cierra While
									?>
								</tbody>
							</table>
						</form>
					</div>
					<div class="toolbar btn-toolbar">
						<div class="btn-group">
							<span class="btn" onclick="formSubmit('formulario')"><i class="icol-accept"></i> Aprobar/Desaprobar Seleccionados</span>
						</div>
					</div>
				</div>
			</div>
		<?PHP } // Cierra if hay coments
		?>
	<?PHP } // Cierra while de tablas con coments
	?>
<?PHP } // Cierra if tablas con coments
?>
<?PHP
$contador_tipo = 0;
$haydatos = 0;

/*
?>

<form method="POST" action="alertas.php" id="cumples">
	<input name="envio" type="hidden" value="1" />
	<div class="row-fluid">
		<div class="widget">
			<div class="widget-content table-container">
				<?PHP
				while ($contador_tipo <= 1) {
					$campo_comp  = ($contador_tipo == 0) ? 'fechanac' : 'fechaing';
					$confircampo = ($contador_tipo == 0) ? 'confirmado' : 'confirmani';
					$tituform    = ($contador_tipo == 0) ? 'Cumplea&ntilde;os' : 'Aniversarios';
					$titunom     = ($contador_tipo == 0) ? 'Cumplea&ntilde;os' : 'Aniversario';

					$query = "SELECT id, nombre , apellido , fechanac , email , " . $confircampo . " , fechaing,
					DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(" . $campo_comp . "), DAY(" . $campo_comp . ") ), SYSDATE() ) AS diferencia 
					FROM intranet_empleados AS emp
						WHERE CONCAT(IF(MONTH(" . $campo_comp . ")=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(" . $campo_comp . ", '-%m-%d')) BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY) 
							AND " . $campo_comp . " != '1111-11-11'
							AND empresa < 3 ";

					$query .=	"AND " . $confircampo . " = 0";

					$query .= "	AND activo = 1
							AND area < 1000
							ORDER BY " . $confircampo . ", diferencia";
					$result = fullQuery($query);
					$contar = mysqli_num_rows($result);
					if ($contar > 0) {
						$haydatos = 1;
				?>
						<div class="widget-header"><span class="title"><?PHP echo $tituform; ?></span></div>
						<table class="table table-striped table-checkable">
							<thead>
								<tr>
									<th class="checkbox-column">
										<i class="icol-accept"></i>
										<!--<input type="checkbox" class="uniform">-->
									</th>
									<th class="checkbox-column">
										<i class="icol-cancel"></i>
										<!--<input type="checkbox" class="uniform2">-->
									</th>
									<th>Nombre</th>
									<th>Fecha <?PHP echo $titunom; ?></th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
								<?PHP
								while ($persona = mysqli_fetch_array($result)) {
									$perid = $persona['id'];
									$fechavar = ($contador_tipo == 0) ? fechaDet($persona['fechanac'], 'corto') : fechaDet($persona['fechaing'], 'corto');
									$confirmacion = $persona[$confircampo];
								?>
									<tr>
										<td class="checkbox-column">
											<input type="radio" class="uniform" name="dato<?PHP echo $campo_comp; ?>_<?PHP echo $perid; ?>" <?PHP if ($confirmacion == 1) {
																																																												echo 'checked="checked"';
																																																											} ?> value="1">
										</td>
										<td class="checkbox-column">
											<input type="radio" class="uniform" name="dato<?PHP echo $campo_comp; ?>_<?PHP echo $perid; ?>" <?PHP if ($confirmacion == 0) {
																																																												echo 'checked="checked"';
																																																											} ?> value="0">
										</td>
										<td width="400"><?PHP echo $persona['apellido'] . ', ' . $persona['nombre']; ?></td>
										<td width="200"><?PHP echo $fechavar; ?></td>
										<td><input type="text" name="email_<?PHP echo $campo_comp . '_' . $perid; ?>" value="<?PHP echo $persona['email']; ?>" /></td>
									</tr>
								<?PHP } //Cierra While
								?>
							</tbody>
						</table>
					<?PHP } ?>
					<?PHP $contador_tipo++; ?>
				<?PHP } ?>
			</div>
			<?PHP if ($haydatos == 1) { ?>
				<div class="toolbar btn-toolbar">
					<div class="btn-group">
						<span class="btn" onclick="formSubmit('cumples')"><i class="icol-accept"></i> Confirmar Seleccionados</span>
					</div>
				</div>
			<?PHP } ?>
		</div>
	</div>
</form>
<?php */ ?>