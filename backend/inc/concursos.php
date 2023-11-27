<?PHP
include_once("inc/img.php");
function verPartic($campo, $usuario, $concurso, $tipo){
	$sql_verp = "SELECT ".$campo." FROM ".$_SESSION['prefijo']."participantes WHERE usuario = ".$usuario." AND concurso = ".$concurso." AND tipoconcurso = ".$tipo;
	//echo '<br /><br />'.$sql_verp;
	$res_verp = fullQuery($sql_verp);
	$row_verp = mysqli_fetch_array($res_verp);
	$devolver = $row_verp[$campo];
	return $devolver;
}
//Eliminación
if(isset($_GET['id']) && isset($_GET['tipo']) && isset($_GET['delfoto']) && isset($_GET['delpart']) ){
	$query  = "DELETE FROM ".$_SESSION['prefijo']."participantes WHERE id = ".$_GET['delpart'];
	$result = fullQuery($query);
	//borra las fotos
	$query = "SELECT link FROM ".$_SESSION['prefijo']."fotos WHERE id = ".$_GET['delfoto'];
	$resul = fullQuery($query);
	while($row = mysqli_fetch_array($resul)){
		$link_img = $row['link'];
		if(file_exists('../'.$link_img)){
			unlink('../'.$link_img);
		}
		borrarDestacadas($link_img,$_GET['delfoto']);
	}
	
	$query  = "DELETE FROM ".$_SESSION['prefijo']."fotos WHERE id = ".$_GET['delfoto'];
	$result = $result = fullQuery($query);
}
// Activos e inactivos
if(isset($_POST['guardar'])){
	$sql_act = "SELECT id FROM ".$_SESSION['prefijo']."participantes WHERE tipoconcurso = ".$tipo." AND concurso = ".$id;
	$res_act = fullQuery($sql_act);
	while($row_act = mysqli_fetch_array($res_act)){
		$idpart = $row_act['id'];
		if(isset($_POST['activo'.$idpart])){
			$act_val = $_POST['activo'.$idpart];
			$sql_acti = "UPDATE ".$_SESSION['prefijo']."participantes SET activo = ".$act_val." WHERE id = ".$idpart;
			$res_acti = fullQuery($sql_acti);
		}
	}
}
// Ver participantes
$sql_fotos = "SELECT ifot.*, par.votos, emp.apellido, emp.nombre FROM ".$_SESSION['prefijo']."fotos AS ifot
				JOIN ".$_SESSION['prefijo']."participantes AS par ON (par.concurso = ifot.item AND par.tipoconcurso = ifot.tipo AND par.usuario = ifot.usuario  AND par.usuario_ext = ifot.usuario_ext)
				JOIN ".$_SESSION['prefijo']."empleados AS emp ON emp.id = par.usuario
				WHERE par.concurso = {$id} AND par.tipoconcurso = {$tipo}
				GROUP BY ifot.id
				ORDER BY par.activo, par.id";
$res_parti = fullQuery($sql_fotos);
if(mysqli_num_rows($res_parti) > 0){
	$contador = 0;
	?>	
	<div class="control-group">
    	<div class="row-fluid">
        	<div class="span12 widget">
            	<div class="widget-header">
                	<span class="title">Participantes</span>
	            </div>
    	        <div class="gallery">
					<ul>
						<?PHP 
                        while($row_fotos = mysqli_fetch_array($res_parti)){
                            $link_thmb1 = explode("imagen","../".$row_fotos['link'], -1);
                            $link_thmb = end($link_thmb1).'img_'.$row_fotos['id'].'_thumb_bk.jpg';
                            $link_thmb2 = end($link_thmb1).'img_'.$row_fotos['id'].'_thumb.jpg';
                            $link_foto = "../".$row_fotos['link'];
                            $id_foto_c = $row_fotos['id'];
                            $epigrafe  = txtcod($row_fotos['epigrafe']);
                            $votos_foto = $row_fotos['votos'];
                            $nomape = txtcod($row_fotos['apellido']).', '.txtcod($row_fotos['nombre']);
                            if(!file_exists($link_thmb) && file_exists($link_foto)){
                                creaThumbs($tipo, $id, 180, 180, 1,$row_fotos['usuario']); // Crea thumbnail de backend
                            }
                            if(!file_exists($link_thmb2) && file_exists($link_foto)){
                                creaThumbs($tipo, $id, 0, 0, 0,$row_fotos['usuario']); // Crea thumbnail de front
                            }
                            if(file_exists($link_foto)){
                                echo '<li style="width:160px; height:300px">
                                        <span class="thumbnail" style="width:150px">
                                        ';
                                    echo '<a href="'.$link_foto.'" rel="prettyPhoto[gal]"><img alt="" src="'.$link_thmb.'"></a>
                                    ';
                                echo '</span>
                                ';
                                echo '<span class="actions">
                                ';
                                $id_partic = verPartic("id", $row_fotos['usuario'], $id, $tipo);
                                    echo '<a href="'.$link_foto.'" rel="prettyPhoto[gal]"><i class="icon-search"></i></a>';
                                    echo '<a href="detalles.php?tipo='.$tipo.'&id='.$id.'&delfoto='.$id_foto_c.'&delpart='.$id_partic.'"><i class="icon-remove"></i></a>';
                                echo '</span>
                                ';
                                echo '<div class="texto10" style="padding:6px; background-color:#EEE">'.$row_fotos['epigrafe'];
                                echo '<br /><strong>'.$nomape.'</strong><br />'.$votos_foto.' votos<br />';
                                if($activable == 1){
                                    // activo
                                    $foto_activa = verPartic("activo", $row_fotos['usuario'], $id, $tipo);
                                    //echo '<br />'.$foto_activa.' | '.$id_conc.'<br />';
                                    ?>
                                    Activo:   <input name="activo<?PHP echo $id_partic;?>" type="radio" value="1" <?PHP if($foto_activa == 1){echo 'checked="checked"';}?> onchange="javascript:cambiado(<?PHP echo $id_partic;?>)"/>
                                    Inactivo: <input name="activo<?PHP echo $id_partic;?>" type="radio" value="0" <?PHP if($foto_activa == 0){echo 'checked="checked"';}?> onchange="javascript:cambiado(<?PHP echo $id_partic;?>)"/>
                                    </div>
                                <?PHP } 
                                echo '<div style="clear:both"></div>
                                ';
                                echo '</li>
                                ';            
                            }
                        }
                        ?>
	            	</ul>
				</div>
        	</div>
    	</div>
	</div>
<?PHP
}
// Votantes
$votantes = '';
$sql_votantes = "SELECT ie.nombre,ie.apellido,vo.id AS idvotos, em.nombre AS emp, ar.nombre AS area FROM ".$_SESSION['prefijo']."empleados AS ie 
					INNER JOIN ".$_SESSION['prefijo']."votos AS vo ON ie.id = vo.usuario
					INNER JOIN ".$_SESSION['prefijo']."empresas AS em ON em.id = ie.empresa
					INNER JOIN ".$_SESSION['prefijo']."areas AS ar ON ar.id = ie.area
					WHERE vo.tabla = ".$tipo." AND vo.item = ".$id."
					GROUP BY ie.id
					ORDER BY idvotos";
$res_votantes = fullQuery($sql_votantes);
$cant_votantes = mysqli_num_rows($res_votantes);
if($cant_votantes > 0){
?>
    <div class="control-group">
        <div class="row-fluid">
            <div class="span12 widget">
                <div class="widget-header">
                    <span class="title">Votantes: <?PHP echo $cant_votantes;?></span>
                </div>
                <div class="widget-content table-container">
                    <table id="demo-dtable-02" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Empresa</th>
                                <th>Area</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                while($row_votantes = mysqli_fetch_array($res_votantes)){
                                    ?>
                                    <tr>
                                        <td>
                                            <?PHP
                                            echo $row_votantes['apellido'];
                                            if($row_votantes['nombre'] != ''){echo ', ';}
                                            echo $row_votantes['nombre'];
                                            ?>
                                        </td>
                                        <td><?PHP echo $row_votantes['emp'];?></td>
                                        <td><?PHP echo $row_votantes['area'];?></td>
                                    </tr>
                                <?PHP } ?>
                        </tbody>
                        <?PHP if($cant_votantes > 20){?>
                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Empresa</th>
                                    <th>Area</th>
                                </tr>
                            </tfoot>
                        <?PHP } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?PHP }else{ ?>
	<div class="control-group">
        <div class="row-fluid">
        	No hay votos.
        </div>
    </div>
<?PHP } ?>