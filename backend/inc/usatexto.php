<?PHP
if ($usatexto == 1){
	echo '
	<div class="control-group">
		<label class="control-label" style="width:50px">Texto</label>
		<div class="controls" style="width:90%; margin-left:100px">
			<textarea rows="15" class="editor" id="editor1" name="texto" >'.txtcod($vartexto).'</textarea>
		</div>
	</div>
	<div style="clear:both"></div>
	';
}
?>