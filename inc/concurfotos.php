<?PHP
$concuact = $noticia['activo'];
$tipovoto = 0; // 0 elije uno entre varios y 1 le pone puntaje a cada uno
$id_concurso = $id;

function resultadosVotos($id_partici,$tipo,$concu_id,$tipovoto)
{
	$sql_partic_res = "SELECT * FROM intranet_participantes WHERE id = $id_partici";
	$res_partic_res = fullQuery($sql_partic_res);
	$row_partic_res = mysqli_fetch_array($res_partic_res);
	$votos_user = ($row_partic_res['votos'] > 0) ? $row_partic_res['votos'] : 'Ninguno';
	$prome_user = $row_partic_res['promedio'];
							
	$resultados = 'Votos: '.$votos_user.'<br />';
	if($tipovoto == 1){
		$promedio = $prome_user;
		$decimales = end(explode('.',$promedio));
		if($decimales == '00'){
			$promedio = current(explode('.',$promedio));
		}
		$resultados.= 'Promedio: '.$promedio;
	}
	return $resultados;
}
$logueado = 0;
$con_voto_usr = 0;

if(isset($_SESSION['usrfrontend'])){// si el usuario está logueado
	$logueado = 1;
	$id_usr_front = $_SESSION['usrfrontend'];
	// Determinamos si votó
	$sqlvot = "SELECT id FROM intranet_votos WHERE item = {$id} AND tabla = {$tipo} AND usuario = {$id_usr_front}";
	$resvot = fullQuery($sqlvot);
	$convot = mysqli_num_rows($resvot);
	$con_voto_usr = ($convot > 0) ? 1 : 0;
	if($tipovoto == 0 && $con_voto_usr == 0){// si se vota un único ganador y si no votó
		if(isset($_GET['v'])){ 
			$voto_hecho = $_GET['v'];
			$sql_dar_voto = "INSERT INTO intranet_votos (voto, usuario, tabla, item) VALUES (1, {$id_usr_front}, {$tipo}, {$id})";
			$res_dar_voto = fullQuery($sql_dar_voto);
			$sql_suma = "SELECT votos FROM intranet_participantes WHERE id = {$voto_hecho}"; // buscamos todos los votos para este item
			$res_suma = fullQuery($sql_suma);
			$row_suma = mysqli_fetch_array($res_suma);
			$resultado = $row_suma['votos'] + 1;
			$sql_guarda = "UPDATE intranet_participantes SET votos = {$resultado} WHERE id = {$voto_hecho}";
			$res_guarda = fullQuery($sql_guarda);
		}
	}
}
$link_destino_query = (isset($link_destino)) ? $link_destino : $_SERVER['PHP_SELF'];
$queryget = '';	
$contget  = 1;
foreach ($_GET as $key => $value) {
	if ($key != "login") {  // ignora este valor
		$queryget .= ($contget == 1) ? '?' : '&';
		$queryget .= $key."=".$value;
	}
	$contget++;
}
$headerloc = $link_destino_query.$queryget;
$formloc   = explode('/',$_SERVER['PHP_SELF']);
$formloc   = end($formloc).$queryget;
?>
<br />
<div class="cuerpo_nota brd-b"></div><br />
<div class="cuerpo_nota">
	<ul class="empleados">
		<?PHP 
            $orden_votos = ($tipovoto == 0) ? "votos" : "promedio";
			$sql_partic = "SELECT * FROM intranet_participantes WHERE concurso = {$id_concurso} AND tipoconcurso = {$tipo} AND activo = 1 ORDER BY {$orden_votos} DESC";
            $res_partic = fullQuery($sql_partic);
            $con_parti = mysqli_num_rows($res_partic);
            $cuenta_fotos = 0;
            while($row_partic = mysqli_fetch_array($res_partic)){ // RECORRE PARTICIPANTES
                $id_usuario = $row_partic['usuario'];
                $votos_user = ($row_partic['votos'] > 0) ? $row_partic['votos'] : 'Ninguno';
                $prome_user = $row_partic['promedio'];
                $id_partici = $row_partic['id'];
                $sql_parti = "SELECT * FROM intranet_fotos WHERE item = ".$id_concurso." AND tipo = $tipo AND usuario = ".$id_usuario;
                $res_parti = fullQuery($sql_parti);
                while($row_parti = mysqli_fetch_array($res_parti)){ // RECORRE FOTOS 
                    ?>
                    <li>
						<?PHP
						// foto
						$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$id_concurso." AND tipo = ".$tipo." AND usuario = ".$id_usuario;
						$res_fotos = fullQuery($sql_fotos);
						$row_fotos = mysqli_fetch_array($res_fotos);
						$link_foto = $row_fotos['link'];
						$link_thumb= explode("imagen","../".$row_fotos['link'], -1);
						$link_thumb= end($link_thumb).'img_'.$row_fotos['id'].'_thumb.jpg';
						$id_foto   = $row_fotos['id'];
						$epigrafe  = $row_fotos['epigrafe'];
						if(file_exists($link_foto)){
                            ?>
							<div class="box1">
								<a href="<?PHP echo $link_foto;?>" target="_blank" rel="prettyPhoto">
									<img src="<?PHP echo $link_thumb;?>" alt="<?PHP echo $epigrafe;?>" title="<?PHP echo $epigrafe;?>" class="aligncenter" width="166" height="166" />
								</a>
							</div>
						<?PHP } ?>
                            <div class="box2"><strong><?PHP echo $epigrafe;?></strong></div>
                            <div class="box3">
                                <div class="date"><?PHP echo obtenerDato("nombre,apellido","empleados",$id_usuario);?></div>
                                <div class="clr"></div>
                            </div>
                            <div class="box4 brd-ts">
                                <div class="date c0099ff"><?PHP echo resultadosVotos($id_partici,$tipo,$id_concurso,$tipovoto);?></div>
                                <div class="clr"></div>
                            </div>
    
                        <?PHP
                        if($usavotos == 1 && $concuact == 1 && $con_parti > 0){ // si los votos están habilitados y el concurso activo
                            if($logueado == 1){// si el usuario está logueado
                                if($tipovoto == 0){ // si se vota eligiendo 1 sólo ítem
                                    $sql_voto_usr = "SELECT * FROM intranet_votos WHERE usuario = {$id_usr_front} AND tabla = {$tipo} AND item = {$id_concurso}";
                                    $res_voto_usr = fullQuery($sql_voto_usr);
                                    $con_voto_usr = mysqli_num_rows($res_voto_usr); 
                                    $row_voto_usr = mysqli_fetch_array($res_voto_usr);
                                    if($con_voto_usr == 0){ // si no votó
                                        ?>
                                        <div class="box5 brd-ts">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td height="20" valign="middle" width="100%">
                                                            <div align="center"><a class="button" style="float:none" href="<?PHP echo $formloc;?>&v=<?PHP echo $id_partici;?>">&iexcl;Vot&aacute; esta foto!</a></div>
                                                    </td>
                                                    <td width="10%" align="right"></td>
                                                </tr>
                                            </table>
                                            <div class="clr"></div>
                                        </div>
                                   <?PHP }
                                }
                            }
                        } // Fin votación
                        ?>
                    </li>
                <?PHP } // FIN RECORRIDA FOTOS ?>
                <?PHP 
                $cuenta_fotos++;
            } // FIN RECORRIDA PARTICIPANTES 
            ?>
        </ul>
	<?PHP if($tipovoto == 0 && $con_voto_usr == 1){?>
    	<div style="clear:both"></div><div align="center" style="border-top: 1px solid; margin-top:15px; padding-top:5px;">Tu voto ya est&aacute; registrado para este concurso.</div>
    <?PHP } ?>
    <br />
</div>