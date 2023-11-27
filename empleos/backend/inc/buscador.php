<?PHP
$buscados = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : '';
?>
<div id="caja-buscador">
	<form action="listado.php?tipo=<?PHP echo $tipo;?>" method="post">
        <table><tr><td><table><tr><td>
        <div style="float:left; width:270px" align="right"><strong><?PHP echo ucwords($nombredet);?>&nbsp;&nbsp;</strong></div>
        <div style="float:left; width:326px">
            <input name="busqueda" type="text" class="txtfield" style="width:324px" id="busqueda" value="<?PHP echo $buscados;?>"/>
        
			<?PHP // COMBOS
            $query_combos = "SELECT tab.nombre AS variable, tab.detalle AS nombre FROM empleos_combos AS cbo
                                INNER JOIN empleos_tablas AS tab ON (cbo.combo = tab.id)
                                WHERE cbo.tabla = ".$tipo;
            $resul_combos = fullQuery($query_combos);
			$conta_combos = mysqli_num_rows($resul_combos);
			if($conta_combos > 1){
				echo '</td></tr><tr><td align="right"><strong>Filtros</strong>&nbsp;&nbsp;&nbsp;&nbsp;';
				while($row_combos = mysqli_fetch_array($resul_combos)){
					$titulo_combo = $row_combos['nombre'];
					$var_combo    = $row_combos['variable'];
					// Valor seleccionado
					$$var_combo   = (isset($_POST['bus_'.$var_combo])) ? $_POST['bus_'.$var_combo] : 0;
					// SELECT
					echo '&nbsp;&nbsp;'.$titulo_combo.': <select name="bus_'.$var_combo.'">';
					echo '<option value="0">Todos</option>';
					$sql_combo = "SELECT * FROM empleos_".$var_combo." WHERE del = 0";
					$res_combo = fullQuery($sql_combo);
					while($row_combo = mysqli_fetch_array($res_combo)){
						$es_activo = ($$var_combo == $row_combo['id']) ? 'selected="selected"' : '';
						echo '<option value="'.$row_combo['id'].'" '.$es_activo.' >'.$row_combo['nombre'].'</option>';
					}
					echo '</select>';
				}
				echo '</td></tr></table></td><td><table><tr><td>';
			}
            ?>
		</div>
        <div style="width:100px; float:left;"><input name="search" type="image" title="submit" src="../img/botbusca.gif" alt="enviar" align="right"/></div>
        </td></tr></table></td></tr></table>
	</form>
</div>