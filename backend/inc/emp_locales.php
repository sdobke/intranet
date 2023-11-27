<?PHP
if($noticia['area'] == 1002){ // Si es un local
	$tabla_combo = 'locales_loc';
	$codemp = $noticia['empresa'];
	$nomlt = ($codemp = 2) ? 'local' : 'tienda';
	$artlt = ($codemp = 2) ? 'el' : 'la';
	?>
	<div class="control-group">
		<label class="control-label" for="emploc"><?PHP echo ucwords($nomlt);?>: </label>
	    <div class="controls">
	    	<select name="emploc" class="span12" id="select">
	    		<option value="0">Seleccione <?PHP echo $artlt.' '.$nomlt;?></option>
				<?PHP
				$sql_minis = "SELECT * FROM ".$_SESSION['prefijo'].$tabla_combo." WHERE empresa = ".$codemp." ORDER BY nombre";
				$res_minis = fullQuery($sql_minis);
				while($row_minis = mysqli_fetch_array($res_minis)){
					$es_activo = '';
					if($row_minis['codigo'] == $noticia['local']){
						$es_activo = "selected='selected'";
					}
					echo '<option value="'.$row_minis['codigo'].'" '.$es_activo.'>'.$row_minis['nombre'].'</option>';
				}
				?>
			</select>
		</div>
	</div>
<?PHP } ?>