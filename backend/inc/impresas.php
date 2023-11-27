<div class="control-group">
	<label class="control-label">Archivo PDF<?PHP if($tipoarchivo == 'alta'){?> <span class="required">*</span><?PHP } ?></label>
	<div class="controls">
    	<table><tr><td>
            <input type="file" name="pdf" id="pdf" data-provide="fileinput">
            <p class="help-block">.Solo formato PDF</p>
            <label for="pdf" class="error" generated="true" style="display:none;">Error de tipo de archivo</label>
        </td><td>
	        <?PHP if($tipoarchivo == 'detalles'){?>
                <div style="float:right; margin-left:15px">
                    <?PHP $pdf_file = "../".config('carp_edimp')."/".config('edimp')."_".$noticia['id'].".pdf";?>
                    PDF Actual: 
                    <a href="<?PHP echo $pdf_file;?>" target="_blank">Descargar</a>
                </div>
			<?PHP } ?>
        </td></tr></table>
	</div>
</div>
<div class="control-group">
	<label class="control-label">Imagen de tapa<?PHP if($tipoarchivo == 'alta'){?> <span class="required">*</span><?PHP } ?></label>
	<div class="controls">
	    <table><tr><td>
			<input type="file" name="imagenbk" id="imagen" data-provide="fileinput">
			<p class="help-block">.Solo formato JPG</p>
			<label for="imagen" class="error" generated="true" style="display:none;">Error de tipo de archvio</label>
        </td><td>
        	<?PHP if($tipoarchivo == 'detalles'){?>
                <div style="float:right; margin-left:15px">
                    <?PHP $foto_ppal = "../".config('carp_edimp')."/imagen_".$noticia['id'].".jpg";?>
                    Tapa Actual: 
                    <a href="<?PHP echo $foto_ppal;?>" target="_blank" rel="prettyPhoto[impresa]"><img src="<?PHP echo $foto_ppal;?>" width="75" /></a>
                </div>
            <?PHP } ?>
        </td></tr></table>
	</div>