<div class="control-group">
	<label class="control-label" for="usuario">Usuario: </label>
	<div class="controls">
		<div class="span12" id="usuario">
			<?PHP 
			$usid = $noticia['usuario'];
			if($usid == 0){
				echo 'Anónimo';
			}else{
				echo txtcod(obtenerDato('apellido','empleados',$usid).', '.obtenerDato('nombre','empleados',$usid));
			}?>
		</div>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="fecha">Fecha: </label>
	<div class="controls">
		<div class="span12" id="fecha"><?PHP echo fechaDet($noticia['fecha']);?></div>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="sugerencia">Sugerencia: </label>
	<div class="controls">
		<div class="span12" id="sugerencia"><?PHP echo txtcod($noticia['sugerencia']);?></div>
	</div>
</div>