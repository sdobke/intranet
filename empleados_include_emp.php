<?PHP
$otro_query = ' AND activo = 1 ';
include "backend/inc/query_busqueda.php";
include "inc/prepara_paginador.php";
$titula = "";
$lupa = '&nbsp;<img src="img_new/herramientas/lupa.png" />';
$result = fullQuery($query);
$cont = 1;
$tarant = 0;
$contaremp = mysqli_num_rows($result);
if($contaremp > 0){
	while ($persona = mysqli_fetch_array($result)) {
		$empl_id = $persona['id'];
		$empl_area = txtcod($persona['area']);
		$empl_nomb = txtcod($persona['nombre']);
		$empl_apel = txtcod($persona['apellido']);
		$empl_empr = obtenerDato('detalle','empresas',$persona['empresa']);
		$empl_carg = txtcod($persona['cargo']);
		$empl_inte = $persona['interno'];
		$empl_cump = fechaDet($persona['fechanac'], 'diames');
		$empl_email = $persona['email'];
		$empl_tarea = $persona['tareas'];
		$empl_nomap = cortarTxt(strtoupper($empl_apel.', '.$empl_nomb),28,' ');
		?>
		<div style="width:300px; margin-right:5px; margin-bottom:5px; padding:5px; height:100px; font-size:10px; float: left; background-color: #f8f8f8; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif">
		<div style="float: left; margin-right:10px">
			<img src="/cliente/fotos/<?PHP if (file_exists("/cliente/fotos/" . $empl_id . ".jpg")) {
					echo $empl_id;
				} else {
					echo "sinfoto";
				} ?>.jpg" alt="<?PHP echo $empl_nomap; ?>" width="100" height="100" />
		</div>
		<div style="float: left;">
        	<?PHP
			// Fila 1: Nombre
			 
			$tamtx = 11;
			$spanom = '';
			if(strlen($empl_nomap) > 30){
				$span_nom_in = "'".$empl_nomap."'";
				$spanom = 'onmouseover="tooltip.show('.$span_nom_in.')" onmouseout="tooltip.hide();"';
            }
			?>
            <span style="color:#E18200; font-size:<?PHP echo $tamtx;?>px; font-weight:bold;" <?PHP echo $spanom;?>>
				<?PHP echo cortarTxt($empl_nomap,30);?>
			</span>
			<br />
            <?PHP // Fila 2: cargo
			if($empl_carg != ''){
				$tamtx = 9;
				$spancarg = '';
				if(strlen($empl_carg) > 34){
					$span_carg_in = "'".$empl_carg."'";
					$spancarg = 'onmouseover="tooltip.show('.$span_carg_in.')" onmouseout="tooltip.hide();"';
				}
				?>
				<span style="font-weight:bold; font-size:<?PHP echo $tamtx;?>px" <?PHP echo $spancarg;?>>
					<?PHP echo cortarTxt($empl_carg,34);?>
				</span>
				<br />
            <?PHP }
			// Fila 3: empresa y área
			if($empl_empr != ''){echo $empl_empr;}
			if($empl_area > 0){
				$spanarea = '';
				if(strlen(area($empl_area)) > 16){
					$span_area_in = "'".area($empl_area)."'";
					$spanarea = 'onmouseover="tooltip.show('.$span_area_in.')" onmouseout="tooltip.hide();"';
                }
				?>
				 | 
				<span <?PHP echo $spanarea;?>><?PHP echo cortarTxt(area($empl_area),16, $lupa); ?></span>
			<?PHP } 
            if($empl_empr != '' || $empl_area > 0){ echo '<br />';}
           
			// Fila 4: interno y email
		    if ($empl_inte != '') { ?>Interno: <?PHP echo $empl_inte; ?><?PHP } ?>
			<?PHP if ($empl_email != '') {
				?>
				<?PHP if ($empl_inte != '' && $empl_email != ''){echo '|';}?>
                <a onmouseover="tooltip.show('<?PHP echo $empl_email;?>');" onmouseout="tooltip.hide();" href="mailto:<?PHP echo $empl_email;?>">
					<img src="img_new/herramientas/sobre.jpg" />&nbsp; Enviar e-mail
				</a>
			<?PHP } 
            if ($empl_inte != '' || $empl_email != ''){echo '<br />';}
			// Fila 5: cumpleaños
			if ($empl_cump != '00 de 00') { ?>Cumplea&ntilde;os: <?PHP echo $empl_cump.'<br />'; }
            // Fila 6: tareas
            if ($empl_tarea != '') { ?><br />Tareas: <span onmouseover="tooltip.show('<?PHP echo $empl_tarea;?>');" onmouseout="tooltip.hide();"><?PHP echo cortarTxt($empl_tarea,23, $lupa); ?></span><?PHP } ?>
            <br /><a href="skype:sergio-fuxionweb?call"><img src="img_new/herramientas/skype.png" /> Llamar ahora</a>
		</div>		
	</div>
		<?PHP $cont++;?>
	<?PHP } ?>
	<div class="paginador t11 ac">
		<?PHP
		// paginador
		$variables = ""; // variables para el paginador
		if ($busqueda != '0' && $busqueda != '') {
			$variables = "busqueda=".$busqueda;
		}
		echo paginador($limit, $contar, $pag, $variables);
		?>
		<div class="clr"></div>
	</div>
<?PHP }else{ 
echo 'No encontramos resultados para su b&uacute;squeda.';
}?>