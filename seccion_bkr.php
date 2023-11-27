<div class="cuerpo_nota mb15 pb15 brd-b">
	<div class="buscador_areas left">
		<div class="left w100 mb10 brd-b pb5 c444444 b">Seleccione el a&ntilde;o</div>
		<div class="left w100">
			<form action="#" method="post">
				<select name="anio" id="anio" onchange="return sendFormanio(this.value);">
					<option value="0" >Seleccione una opci&oacute;n...</option>
					<?PHP
                        $valoranio = $_REQUEST["anio"];
                        $sql_a = "SELECT YEAR(fecha) AS anio FROM intranet_revista GROUP BY anio ORDER BY anio DESC";
						$res_a = fullQuery($sql_a);
                        while($row_a = mysqli_fetch_array($res_a)){
							$selected = "";
							if($bka = $row_a['anio'] == $_REQUEST["anio"])
							$selected = 'selected="selected"';
							?>
							<option <?php echo $selected; ?> value="<?PHP echo $row_a['anio'];?>" ><?PHP echo $row_a['anio']; ?></option>
						<?PHP } ?>
				</select>
			</form>
		</div>
	</div>
</div> 
            
<?PHP 
$bkrcond = (isset($valoranio)) ? "WHERE YEAR(fecha) = '".$valoranio."'" : "";
$sql_bk = "SELECT * FROM intranet_revista ".$bkrcond." ORDER BY fecha DESC";
//echo $sql_bk;
$res_bk = fullQuery($sql_bk, $ea);
$con_bk = mysqli_num_rows($res_bk);
if($con_bk > 0){?>
	<div class="cuerpo_nota brd-b">
    <?PHP
	$carpeta  = config('carp_edimp')."/";
	while ($bk = mysqli_fetch_array($res_bk)) {
		$bkid = $bk['id'];
		$bkpdf  = $carpeta.config('edimp')."_".$bkid.".pdf";
		if(file_exists($bkpdf)){
			$bkfec = fechaDet($bk['fecha']);
			$bkfoto = (file_exists($carpeta."imagen_".$bkid.".jpg")) ? $carpeta."imagen_".$bkid.".jpg" : "/cliente/img/noDisponible.jpg";
			echo '
			<ul class="empleados bkrev">
				<li>
					<div class="box1"><a href="'.$bkpdf.'"><img src="'.$bkfoto.'" width="171" height="171" class="aligncenter" /></a></div>
					<div class="box3 brd-ts">
						<div class="left w100 t14"><a href="'.$bkpdf.'">Edici&oacute;n N&deg;'.$bk['edicion'].'</a></div>
						
					</div>
					<div class="box4 brd-ts">
						<div class="date c0099ff">'.$bkfec.'</div>
						
					</div>
				</li>
			</ul>
			';
		}
	}?>
	</div>
<?PHP } ?>
<script>
	function sendFormanio(anio){
		window.location.href = "/seccion.php?anio="+anio+"&tipo=12"
	}
</script>