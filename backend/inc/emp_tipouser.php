<?PHP $var_comparar = ($funcion_archivo == 'alta') ? '0' : $noticia['oficinas'];?>
<div class="control-group">
	<label class="control-label" for="emploc">Tipo de usuario: </label>
    <div class="controls">
		<select name="oficinas">
			<option value="1" <?PHP echo optSel($var_comparar,'1');?>>Oficinas</option>
			<option value="5" <?PHP echo optSel($var_comparar,'5');?>>Tiendas</option>
			<option value="4" <?PHP echo optSel($var_comparar,'4');?>>Locales</option>
            <option value="6" <?PHP echo optSel($var_comparar,'6');?>>Restaurantes</option>
			<option value="7" <?PHP echo optSel($var_comparar,'7');?>>Tiendas/Locales/Restaurantes Interior</option>
		</select>
	</div>
</div>