<?PHP
$sql_ad = "SELECT * FROM intranet_links WHERE del = 0 ORDER BY titulo";
$res_ad = fullQuery($sql_ad);
$con_ad = mysqli_num_rows($res_ad);
if ($con_ad > 0) {
?>
	<div class="" id="links">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="titulo">Accesos Directos</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<?PHP
					$cntad = 1;
					while ($row_ad = mysqli_fetch_array($res_ad)) {
					?>
						<a href="<?PHP echo $row_ad['link']; ?>" target="_blank"><?PHP echo txtcod($row_ad['titulo']); ?></a>
						<?PHP if ($cntad < $con_ad) {
							echo '/';
						} ?>
						<?PHP $cntad++; ?>
					<?PHP } ?>
				</div>
			</div>
		</div>
	</div>
<?PHP } ?>