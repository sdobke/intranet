<?PHP
$sql_opciones = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_opc WHERE encuesta = ".$id." ORDER BY id";
$res_opciones = fullQuery($sql_opciones);
$total_opciones = mysqli_num_rows($res_opciones);
$vacio = 1;
							
if($total_opciones > 0){
	echo '<fieldset>
			<legend>Opciones</legend>';
	$cont = 1;
	while($row_opc = mysqli_fetch_array($res_opciones)){
		$item_activo = ($row_opc['activo'] == 1) ? 'checked="checked"' : '';
		?>
		<div class="control-group">
			<div class="controls" style="margin: auto;" align="center">
				Opci&oacute;n <?PHP echo $cont++;?>: 
				<input name="opcion_<?PHP echo $row_opc['id'];?>" type="text" id="opcion_<?PHP echo $row_opc['id'];?>" value="<?PHP echo $row_opc['valor'];?>" class="span3" />
				<?PHP /*
				$sql_imgs = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE tipo = ".$tipo." AND item = ".$row_opc['id'];
				$res_imgs = fullQuery($sql_imgs);
				$con_imgs = mysqli_num_rows($res_imgs);
				if($con_imgs == 1){
					$row_imgs = mysqli_fetch_array($res_imgs);
					echo '<img src="../'.str_replace('imagen', 'thumb', $row_imgs['link']).'" />';
				}*/
				?>
				<input name="color_<?PHP echo $row_opc['id'];?>" id="cp1" type="text" class="minicolors" value="#<?PHP echo $row_opc['color'];?>">
				Activo <input type="checkbox" class="uniform" name="activo<?PHP echo $row_opc['id'];?>" id="activo<?PHP echo $row_opc['id'];?>" <?PHP echo $item_activo;?>  value="<?PHP echo $row_opc['id'];?>" />
				Eliminar <input type="checkbox" class="uniform" name="borrar<?PHP echo $row_opc['id'];?>" id="borrar<?PHP echo $row_opc['id'];?>" value="<?PHP echo $row_opc['id'];?>" />
			</div>
		</div>
	<?PHP 
	}
	echo '</fieldset>';
}?>
<fieldset>
	<legend>Agregar opciones</legend>
	<div class="control-group">
		<div class="controls" style="margin: auto;" align="center">
			<?PHP
			echo agregaOpcion(1,1);
			$cont_opc = 2;
			while ($cont_opc < 21){
				echo agregaOpcion($cont_opc);
				$cont_opc++;
			}
			?>
		</div>
	</div>
</fieldset>
<fieldset>
	<legend>Participantes</legend>
    <div style="margin:15px">
		<?PHP
        $sql_part = 'SELECT concat( emple.nombre, " ", emple.apellido ) AS nombre, opc.valor AS opcion
            FROM '.$_SESSION['prefijo']."empleados AS emple 
            INNER JOIN ".$_SESSION['prefijo']."encuestas_votos AS votos
                ON (emple.id = votos.empleado)
            INNER JOIN ".$_SESSION['prefijo']."encuestas_opc AS opc
                ON (opc.id = votos.opcion)
            WHERE votos.encuesta = ".$id;
    
            
        $res_part = fullQuery($sql_part);
        while($row_part = mysqli_fetch_array($res_part)){
            echo $row_part['nombre'].': '.$row_part['opcion'].'<br />';
        }
        ?>
    </div>
</fieldset>
<fieldset>
	<legend>Resultados</legend>
    <div style="margin:15px">
<?PHP
	$orden_resul = ' ORDER BY id';
$devuelve = '<br /><div id="photos"><table>
				';
				// Primero los totales
				$sql_encu_voto = "SELECT SUM(votos) AS cantidad FROM ".$_SESSION['prefijo']."encuestas_opc WHERE encuesta = ".$id;
				$res_encu_voto = fullQuery($sql_encu_voto);
				$row_encu_voto = mysqli_fetch_array($res_encu_voto);
				$total_votos   = $row_encu_voto['cantidad'];
				$sql_encu_voto = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_opc WHERE encuesta = ".$id." AND activo = 1 ".$orden_resul;
				$res_encu_voto = fullQuery($sql_encu_voto);
				while($enc_row_voto = mysqli_fetch_array($res_encu_voto)){
					//$total = $total + $enc_row_voto['votos'];
					$sql_encfot = "SELECT * FROM ".$_SESSION['prefijo']."fotos WHERE tipo = 5 AND item = ".$enc_row_voto['id'];
					$res_encfot = fullQuery($sql_encfot);
					$con_encfot = mysqli_num_rows($res_encfot);
					if($con_encfot == 1){
						$row_encfot = mysqli_fetch_array($res_encfot);
						$opcion_foto = '<div class="photo"><a href="#foto'.$row_encfot['id'].'"><img src="'.str_replace('imagen', 'thumb', $row_encfot['link']).'" /></a></div>
											<div id="foto'.$row_encfot['id'].'"><img src="'.$row_encfot['link'].'" /></div>
											';
					}else{
						$opcion_foto = '';
					}
					$porcentaje = ($enc_row_voto['votos'] == 0) ? 0 :(int)(($enc_row_voto['votos']/$total_votos)*100);
					$devuelve .= '<tr><td align="left">'.$opcion_foto.$enc_row_voto['valor'].':</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $porcentaje .'% ('.$enc_row_voto['votos'] .' respuestas)</td></tr>
					';
					$color = "#".$enc_row_voto['color'];
					$largo = ($porcentaje < 100) ? $porcentaje*2 : $porcentaje;
					$largo = $largo * 2;
					$devuelve .= '<tr><td colspan="2"><div style="background-color:'.$color.';width:'.$largo.'px;height:20px; margin-bottom:15px">&nbsp;</div></td></tr>
					';
				}
				$sql_encu_voto = "SELECT COUNT(*) AS cantidad FROM ".$_SESSION['prefijo']."encuestas_opc WHERE encuesta = ".$id." AND activo = 0 GROUP BY activo";
				$res_encu_voto = fullQuery($sql_encu_voto);
				while($enc_row_voto = mysqli_fetch_array($res_encu_voto)){
					$votos = $enc_row_voto['cantidad'];
					$opcion_foto = '';
					$porcentaje = ($votos == 0) ? 0 :(int)(($votos/$total_votos)*100);
					$devuelve .= '<tr><td align="left">'.$opcion_foto.'Otros:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $porcentaje .'% ('.$votos .' respuestas)</td></tr>
					';
					$color = "#666";
					$largo = ($porcentaje < 100) ? $porcentaje*2 : $porcentaje;
					$largo = $largo * 2;
					$devuelve .= '<tr><td colspan="2"><div style="background-color:'.$color.';width:'.$largo.'px;height:20px; margin-bottom:15px">&nbsp;</div></td></tr>
					';
				}
			$devuelve.= "</table></div>
			";
			echo $devuelve;
			echo "<br /><strong>Total de respuestas: ".$total_votos."</strong>";
?>
</div></fieldset>