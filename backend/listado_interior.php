<li data-group="<?PHP echo $texto_grupos; ?>">
	<a class="title"><?PHP echo $texto_grupos; ?></a>
	<?PHP
	$contnot = 0;
	if ($usafecha == 1 && $indice == "fecha") {
		$fechanio = substr($row_meses['fecha'], 0, 4);
		$fechames = substr($row_meses['fecha'], 5, 2);
		$querynueva = $query . ' AND YEAR(fecha) = ' . $fechanio . ' AND MONTH(fecha) = ' . $fechames . ' AND fecha > DATE_SUB(CURDATE(), INTERVAL ' . config("limite_listado") . ' MONTH) ORDER BY fecha DESC';
	}
	if ($indice != 'fecha') {
		$ordenmas = ($tipodet == "empleados") ? ',nombre' : ''; // empleados
		$otroquer = (parametro('otroquery', $tipo) != '') ? ' AND ' . parametro('otroquery', $tipo) : '';
		$querynueva = $query . ' AND SUBSTR(' . $indice . ', 1,1) = "' . $texto_grupos . '" ' . $otroquer . ' AND del = 0 ORDER BY ' . $indice . $ordenmas;
	}
	$result   = fullQuery($querynueva);
	$contador = mysqli_num_rows($result);
	if ($contador > 0) {
		while ($noticia = mysqli_fetch_array($result)) {
			$contnot++;
	?>
			<ul>
				<li style="border-bottom:solid 1px #EEE">
					<div style="float:left; width:1000px">
						<a href="detalles.php?tipo=<?PHP echo $tipo; ?>&id=<?PHP echo $noticia['id']; ?>">
							<?PHP if ($usacolor == 1) {
								echo '<div style="float:left; margin-right:5px; width:20px; height:20px; background-color:#' . $noticia['color'] . '">&nbsp;</div>';
							} ?>
							<?PHP
							if ($usafotos == 1 || $tipodet == "revista") { // imagen principal: si usa foto o si es edicion impresa
								echo '<span class="thumbnail">';
								$item = $noticia['id'];
								include("inc/muestra_imagen_ppal.php");
								echo '</span>';
							}
							?>
							<?PHP
							$titulo = ($tipotit != '') ? $noticia[$tipotit] : '';
							if ($tipodet == 'empleados') { // Si es empleados
								$titulo = $noticia['apellido'] . ', ' . $noticia['nombre'];
							}
							if (is_numeric($titulo)) { // Si se guardó un nro en vez de título, es el nro de tabla. Se busca el nombre de ese item
								//$tab_cso = obtenerNombre($titulo);
								$sql_cso = "SELECT * FROM " . $nombretab . " WHERE id = " . $noticia['id'];
								$res_cso = fullQuery($sql_cso);
								$row_cso = mysqli_fetch_array($res_cso);
								$titulo  = txtcod($row_cso[$tipotit]);
								if ($tipodet == "revista") { // Si es edicion impresa
									$titulo = 'Edici&oacute;n Nro.' . $titulo;
								}
							}
							echo txtcod(cortarTexto($titulo, 100));
							if ($usafecha == 1) {
								$texto_fecha = ($usafecha == 2) ? 'Vencimiento' : 'Fecha';
								echo '<span style="font-size: 11px; display: block;" class="muted">';
								echo $texto_fecha . ': ' . FechaDet($noticia['fecha'], 'largo', 's');
								echo '</span>';
							}
							if ($usahora == 1) {
								echo '<span style="font-size: 11px; display: block;" class="muted">';
								echo 'Hora: ' . substr($noticia['hora'], 0, 5);
								echo '</span>';
							}
							?>
						</a>
					</div>
					<?PHP // ELIMINAR
					$link_bot_elim = ($borrable > 0) ? "javascript:mostrar('eliminar'); ocultar('bot_elim')" : "javascript:confirmDelete('listado.php?tipo=" . $tipo . "&amp;id=" . $noticia['id'] . "')";
					?>
					<div style="float:right; margin-right:80px; margin-top:20px"><button class="btn btn-small" onclick="<?PHP echo $link_bot_elim; ?>"><i class="icol-cross"></i></button></div>
				</li>
				<!--<a href="javascript:confirmDelete('baja.php?tipo=<?PHP echo $tipo; ?>&id=<?PHP echo $noticia['id']; ?>')">test</a>-->
			</ul>
		<?PHP } ?>
	<?PHP } ?>
</li>