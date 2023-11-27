<?PHP
if($tipodet == 'empleados'){ // empleados
	$lnkfoto = '../cliente/fotos/';
	$lnkfoto.= $id;
	$lnkfoto.= '.jpg';
	if(file_exists($lnkfoto)){
		echo '<div class="control-group">';
		echo '<div align="center" width="150" style="margin:auto">
				';
		echo '<span class="thumbnail" style="width:150px">
				';
			echo '<img alt="" src="'.$lnkfoto.'?tm='.date("m-d-H-i-s").'">
			';
		echo '</span>
			';
		echo '</div>
		';
		echo '</div>';
	}
}else{
	$sql_fotos = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY id";
	$res_fotos = fullQuery($sql_fotos);
	$total_fotos = mysqli_num_rows($res_fotos);
	if($total_fotos > 0){
		echo '<div class="control-group">';
		$vacio = 1;
		echo 'Fotos: '.$total_fotos;
		echo '<div class="gallery">
				<ul>
				';
		while($row_fotos = mysqli_fetch_array($res_fotos)){
			$link_thmb1 = explode("imagen","../".$row_fotos['link'], -1);
			$link_thmb = end($link_thmb1).'img_'.$row_fotos['id'].'_thumb_bk.jpg';
			$link_foto = "../".$row_fotos['link'];
			if(!file_exists($link_thmb) && file_exists($link_foto)){
				creaThumbs($tipo, $id, 180, 180, 1); // Crea thumbnails de backend
			}
			if(file_exists($link_foto)){
				echo '<li style="width:160px">
						<span class="thumbnail" style="width:150px">
						';
					echo '<a href="'.$link_foto.'" rel="prettyPhoto[nature]"><img alt="" src="'.$link_thmb.'"></a>
					';
				echo '</span>
				';
				/*echo '<span class="actions">
				';
					echo '<a href="'.$link_foto.'" rel="prettyPhoto[nature]"><i class="icon-search"></i></a>';
					echo '<a href="#"><i class="icon-pencil"></i></a>';
					echo '<a href="#"><i class="icon-remove"></i></a>';
				echo '</span>
				';*/
				echo '<div class="texto10 centro">Ep&iacute;grafe</div><textarea name="epig_'.$row_fotos['id'].'" class="epigrafe">'.$row_fotos['epigrafe'].'</textarea>';
				$ppal_fot = '';
				if($row_fotos['ppal'] == 1){
					$ppal_fot = 'checked = "checked" ';
					$vacio = 0;
				}
				echo '<div class="centro">
				';
				echo '<div class="foto_ppal texto10">Seleccionar como principal</div>
				';
				echo '<div class="fleft ml5">
					<input type="radio" name="ppal" id="ppal" value="'.$row_fotos['id'].'" '.$ppal_fot.' onclick="javascript:Abrir_ventana('.$row_fotos['id'].',\'generada\',\'1\')"/>
					</div>
		    	</div>
				';
				echo '<div style="clear:both"></div>
				';
				echo '<div class="centro">
				<div class="foto_ppal texto10 rojo">Eliminar</div>
					<div class="fleft ml5"><input type="checkbox" name="borrar'.$row_fotos['id'].'" id="borrar'.$row_fotos['id'].'" value="'.$row_fotos['id'].'" /></div>
				</div>
				';
				echo '</li>
				';
			}else{ // Si no existe la foto se elimina de la DB
				$sql_elim_fot = "DELETE FROM ".$_SESSION['prefijo']."fotos WHERE id = ".$row_fotos['id'];
				//$res_elim_fot = fullQuery($sql_elim_fot);
				
			}
		}
		echo '<li style="width:160px">
				<span class="thumbnail" style="width:150px">
				';
			echo '<img alt="" src="img/none.jpg">
			';
			echo '</span>
			';
			$selec_ppal = '';
			if($vacio == 1){
				$selec_ppal = 'checked = "checked" ';
			}
			echo '<div class="centro">
			';
			echo '<div class="foto_ppal texto10">Sin foto principal</div>
			';
			echo '<div class="fleft ml5"><input type="radio" name="ppal" id="ppal" value="0" '.$selec_ppal.' /></div>
			</div>
			';
			echo '</li>
		';
		echo '</ul></div>
		';
		echo '</div>';
	}
}