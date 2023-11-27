<?PHP
$sql_com = "SELECT * FROM " . $_SESSION['prefijo'] . "comentarios WHERE tipo = " . $tipo . " AND item = " . $id . " AND del = 0";
$res_com = fullQuery($sql_com);
$con_com = mysqli_num_rows($res_com);
if ($con_com > 0) {
?>
	<div class="control-group">
		<div class="widget">
			<div class="widget-header">
				<span class="title">Comentarios</span>
				<div class="toolbar">
					<ul class="nav nav-pills">
						<li class="active"><a href="#tab-01" data-toggle="tab">Pendientes</a></li>
						<li><a href="#tab-02" data-toggle="tab">Aprobados</a></li>
						<li><a href="#tab-03" data-toggle="tab">No aprobados</a></li>
					</ul>
				</div>
			</div>
			<div class="tab-content">
				<?PHP
				$comcont = 1;
				while ($comcont <= 3) {
					$ccact = $comcont - 1;
					$panact = ($comcont == 1) ? ' active' : '';
					echo '<div class="tab-pane' . $panact . '" id="tab-0' . $comcont . '">';
					$sql_com = "SELECT com.id AS id, com.comentario AS com, com.fecha AS fec, emp.nombre AS nom, emp.apellido AS ape FROM " . $_SESSION['prefijo'] . "comentarios AS com LEFT JOIN " . $_SESSION['prefijo'] . "empleados AS emp ON emp.id = com.usuario_id WHERE com.tipo = " . $tipo . " AND com.item = " . $id . " AND com.activo = " . $ccact . " AND com.del = 0";
					//echo $sql_com;
					$res_com = fullQuery($sql_com);
					while ($row_com = mysqli_fetch_array($res_com)) {
				?>
						<div style=" border-bottom: dotted 1px; width: 100%; min-height:50px;">
							<table>
								<tr>
									<td width="840">
										<span class="com_fecha"><?PHP echo fechaDet($row_com['fec']); ?> | <?PHP echo txtcod($row_com['ape']) . ', ' . txtcod($row_com['nom']); ?>: </span><span class="tags"><?PHP echo txtcod($row_com['com']); ?></span>
									</td>
									<?PHP if ($comcont <> 2) { ?>
										<td align="right">
											<a href="?com=<?PHP echo $row_com['id']; ?>&id=<?PHP echo $id; ?>&tipo=<?PHP echo $tipo; ?>&val=1" title="Aprobar comentario" rel="tooltip"><span class="btn"><i class="icol-accept"></i> Aprobar</span></a>
										</td>
									<?PHP } ?>
									<?PHP if ($comcont < 3) { ?>
										<td align="right">
											<a href="?com=<?PHP echo $row_com['id']; ?>&id=<?PHP echo $id; ?>&tipo=<?PHP echo $tipo; ?>&val=2" title="Desaprobar comentario" rel="tooltip"><span class="btn"><i class="icol-cross"></i> Desaprobar</span></a>
										</td>
									<?PHP } ?>
								</tr>
							</table>
						</div>
					<?PHP } ?>
			</div>
		<?PHP
					$comcont++;
				}
		?>
		</div>
	</div>
	</div>
<?PHP } ?>