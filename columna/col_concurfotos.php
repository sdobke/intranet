<?PHP
$tipo_col = 15;
$nombre_tab = obtenerNombre($tipo_col);
$restriccion = (isset($_SESSION['tipoemp'])) ? $_SESSION['tipoemp']: 0;
$sql_buscon = " SELECT inov.id AS id, inov.activo AS activo, inov.titulo AS titulo, inov.texto AS texto FROM intranet_" . $nombre_tab . " AS inov 
					INNER JOIN intranet_link AS il ON inov.id = il.item 
						WHERE il.tipo = " . $tipo_col . " AND (il.part = " . $restriccion . " OR il.part = 0) AND inov.activo = 1
				ORDER BY RAND() LIMIT 1";
$res_buscon = fullQuery($sql_buscon);
$con_buscon = mysqli_num_rows($res_buscon);
if ($con_buscon == 1) { // agregado excluye tiendas
	$row_buscon = mysqli_fetch_array($res_buscon);
	$id_concurso = $row_buscon['id'];
	$titulo = $row_buscon['titulo'];
	$texto = $row_buscon['texto'];
	$sql_partic = "SELECT * FROM intranet_participantes WHERE concurso = " . $id_concurso . " AND tipoconcurso = $tipo_col AND activo = 1 ORDER BY RAND() LIMIT 1";
	$res_partic = fullQuery($sql_partic);
	$con_partic = mysqli_num_rows($res_partic);
	if ($con_partic == 1) {
		$row_partic = mysqli_fetch_array($res_partic);
		$id_usuario = $row_partic['usuario'];
		$tipo_user = $row_partic['usuario_ext'];
		$id_partici = $row_partic['id'];
		$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = " . $id_concurso . " AND tipo = " . $tipo_col . " AND usuario = " . $id_usuario . " AND usuario_ext = " . $tipo_user . " LIMIT 1";
		$res_fotos = fullQuery($sql_fotos);
		$con_fotos = mysqli_num_rows($res_fotos);
		if ($con_fotos == 1) {
			$row_fotos = mysqli_fetch_array($res_fotos);
			$link_foto = $row_fotos['link'];
			$id_foto = $row_fotos['id'];
			$epigrafe = $row_fotos['epigrafe'];
			$carpeta = explode("imagen", $link_foto, -1);
			$carpeta = end($carpeta);
			$link_foto = $carpeta . "imagen_" . $id_foto . "_home.jpg";
			$link_foto_no_disponible = "cliente/img/noDisponible.jpg"; //foto test de maqueta
			?>
			<div class="bloque300-st mt15 right">
				<div class="hd_accesos nettooffc b t14">Concursos de fotos</div>
				<div class="row_accesos">
					<div class="galeria_thumb mr10">
						<?PHP if (file_exists($link_foto)) { ?>
							<a href="concurfotos.php"><img src="<?PHP echo $link_foto; ?>" width="100px" height="76px" /></a><br />
						<?PHP }else{ ?>
							<img src="<?PHP echo $link_foto_no_disponible; ?>" width="100px" height="76px" /><br />
						<?PHP } ?>
					</div>
					<strong><?PHP echo $titulo; ?></strong><br />
					<?PHP echo $epigrafe; ?><br /><br />
					<a href="concurfotos.php">Ver m&aacute;s y participar>></a>
				</div>
			</div>
		<?PHP } ?>
	<?PHP } else { ?>
		<div class="bloque300-st mt15 right">
			<div class="hd_accesos nettooffc b t14">Concursos de fotos</div>
			<div class="row_accesos">
				
				<strong><?PHP echo $titulo; ?></strong><br />
				<?PHP echo $texto; ?><br /><br />
				<?PHP //echo $epigrafe; //no está definida en este bloque?>
				<a href="concurfotos.php">Ver m&aacute;s y participar>></a>
			</div>
		</div>		
	<?PHP } ?>
<?PHP } ?>