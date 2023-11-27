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
	if($tipovoto == 1){ // si se vota con puntajes (Deshabilitado)
		/*
		$foto_votada  = (isset($_POST['foto_votada'])) ? $_POST['foto_votada'] : 0;
		if(isset($_POST['voto'.$foto_votada])){ // si acaba de votar
			$id_concurso  = $_POST['id_concurso'];
			$id_usr_part = obtenerDato("usuario","fotos",$foto_votada);
			$id_participante = $_POST['id_partici'];
			$voto = $_POST['voto'.$foto_votada];
			$sql_voto_usr = "SELECT * FROM intranet_votos WHERE tabla = ".$tipo." AND item = ".$foto_votada." AND usuario = ".$id_usr_front;
			$res_voto_usr = fullQuery($sql_voto_usr);
			$con_voto_usr = mysqli_num_rows($res_voto_usr);
			if($con_voto_usr == 0){ // si el usuario no votó este ítem
				$sql_dar_voto = "INSERT INTO intranet_votos (voto, usuario, tabla, item) VALUES ($voto, $id_usr_front, ".$tipo.", $foto_votada)";
				//echo '<br />'.$sql_dar_voto.'<br />';
				$res_dar_voto = fullQuery($sql_dar_voto);
				$sql_suma = "SELECT voto FROM intranet_votos WHERE item = ".$foto_votada." AND tabla = ".$tipo; // buscamos todos los votos para este item
				//echo '<br />'.$sql_suma.'<br />';
				$res_suma = fullQuery($sql_suma);
				$sumatoria = 0;
				while($row_suma = mysqli_fetch_array($res_suma)){
					$sumatoria = $sumatoria + $row_suma['voto']; // los sumamos
				}
				$sql_cambiar_votos = "SELECT votos FROM intranet_participantes WHERE id = ".$id_participante; // calculamos total de votos
				//echo '<br />'.$sql_cambiar_votos.'<br />';
				$res_cambiar_votos = fullQuery($sql_cambiar_votos);
				$row_cambiar_votos = mysqli_fetch_array($res_cambiar_votos);
				$cant_votos_new = $row_cambiar_votos['votos']+1; // agregamos este voto
				$prom_votos_new = $sumatoria / $cant_votos_new; // sacamos el promedio
				// guardamos los datos
				$sql_guarda = "UPDATE intranet_participantes SET votos = ".$cant_votos_new.", promedio = ".$prom_votos_new." WHERE id = ".$id_participante;
				//echo '<br />'.$sql_guarda.'<br />';
				$res_guarda = fullQuery($sql_guarda);
			}
		}
		*/
	}else{ // si se vota un único ganador
		if(isset($_POST['voto'])){ 
			$voto_hecho = $_POST['voto'];
			$sql_revisa = "SELECT * FROM intranet_participantes WHERE id = $voto_hecho";
			//echo '<br />'.$sql_revisa;
			$res_revisa = fullQuery($sql_revisa);
			$row_revisa = mysqli_fetch_array($res_revisa);
			$concurso_votado = $row_revisa['concurso'];
			// buscamos votos de este usuario para este item
			$sql_voto_usr = "SELECT * FROM intranet_votos WHERE usuario = $id_usr_front AND item = $concurso_votado AND tabla = $tipo";
			//echo '<br />'.$sql_voto_usr;
			$res_voto_usr = fullQuery($sql_voto_usr);
			$con_voto_usr = mysqli_num_rows($res_voto_usr);
			if($con_voto_usr == 0){ // si el usuario no votó este ítem
				$sql_dar_voto = "INSERT INTO intranet_votos (voto, usuario, tabla, item) VALUES (1, $id_usr_front, $tipo, $concurso_votado)";
				//echo '<br />'.$sql_dar_voto.'<br />';
				$res_dar_voto = fullQuery($sql_dar_voto);
				$sql_suma = "SELECT votos FROM intranet_participantes WHERE id = $voto_hecho"; // buscamos todos los votos para este item
				//echo '<br />'.$sql_suma.'<br />';
				$res_suma = fullQuery($sql_suma);
				$row_suma = mysqli_fetch_array($res_suma);
				$resultado = $row_suma['votos'] + 1;
				$sql_guarda = "UPDATE intranet_participantes SET votos = $resultado WHERE id = $voto_hecho";
				//echo '<br />'.$sql_guarda.'<br />';
				$res_guarda = fullQuery($sql_guarda);
			}
		}
	}
}
if($logueado == 1){
	$sql_yapart  = "SELECT id FROM intranet_participantes WHERE concurso = ".$id_concurso." AND tipoconcurso = $tipo AND usuario = ".$_SESSION['usrfrontend'];
	//echo $sql_yapart;
	$res_yapart  = fullQuery($sql_yapart);
	$con_yapart  = mysqli_num_rows($res_yapart);
	$yaparticipa = ($con_yapart > 0) ? 1 : 0;
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
	<form id="votos" name="votos" method="post" action="<?PHP echo $formloc;?>">
        <ul class="empleados">
            <?PHP 
            $orden_votos = ($tipovoto == 0) ? "votos" : "promedio";
			$sql_partic = "SELECT * FROM intranet_participantes WHERE concurso = ".$id_concurso." AND tipoconcurso = $tipo AND activo = 1 ORDER BY $orden_votos DESC";
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
						$link_thumb= current(explode('.jpg',$row_fotos['link'])).'_thumb.jpg';
						$id_foto   = $row_fotos['id'];
						$epigrafe  = $row_fotos['epigrafe'];
						if(file_exists($link_foto)){
                            ?>
							<div class="box1">
								<a href="<?PHP echo $link_foto;?>" target="_blank" rel="prettyPhoto">
									<img src="<?PHP echo $link_thumb;?>" alt="<?PHP echo $epigrafe;?>" title="<?PHP echo $epigrafe;?>" class="aligncenter" width="166" height="125" />
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
                                    $sql_voto_usr = "SELECT * FROM intranet_votos WHERE usuario = $id_usr_front AND tabla = $tipo AND item = $id_concurso";
                                    $res_voto_usr = fullQuery($sql_voto_usr);
                                    $con_voto_usr = mysqli_num_rows($res_voto_usr); 
                                    $row_voto_usr = mysqli_fetch_array($res_voto_usr);
                                    if($con_voto_usr == 0){ // si no votó
                                        ?>
                                        <div class="box5 brd-ts">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td height="20" valign="middle">
                                                            ¡Votá esta foto!
                                                            <input type="radio" name="radio" id="radio" value="<?PHP echo $id_partici;?>" />
                                                        </form>
                                                    </td>
                                                    <td width="10%" align="right"></td>
                                                </tr>
                                            </table>
                                            <div class="clr"></div>
                                        </div>
                                   <?PHP }
                                }else{ // si se da puntaje a cada una (deshabilitado)
									/*
                                    if($con_voto_usr == 0){ // si el usuario no votó este ítem
                                        echo '<form id="votos'.$id_partici.'" name="votos'.$id_partici.'" method="post" action="'.$formloc.'">';
                                            echo 'Ponele puntaje a esta foto: ';
                                            echo '<select id="voto'.$id_fotor.'" name="voto'.$id_foto.'">';
                                                $contar_voto = 1;
                                                while($contar_voto < 11){
                                                    echo '<option value="'.$contar_voto.'">'.$contar_voto.'</option>';
                                                    $contar_voto++;
                                                }
                                            echo '</select>';
                                            echo '<input type="hidden" name="id_concurso" id="id_concurso" value="'.$id_concurso.'">';
                                            echo '<input type="hidden" name="foto_votada" id="foto_votada" value="'.$id_foto.'">';
                                            echo '<input type="hidden" name="id_partici" id="id_partici" value="'.$id_partici.'">';
                                            echo '<input type="submit" name="button_'.$noticia['id'].'" id="button_'.$noticia['id'].'" value="Votar" />';
                                            echo '</form>';
                                    }else{
                                        echo "Tu voto ya est&aacute; registrado para este item.<br />";
                                    }
									*/
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
		<?PHP 
		if($tipovoto == 0 && $concuact == 1 && $logueado == 1 && $con_voto_usr == 0 && $con_parti > 0){
        	echo '<div align = "center"><input type="submit" name="button_'.$id_concurso.'" id="button_'.$id_concurso.'" class="button" value="Votar" style="height: 30px;" /></div>';
		}?>
    </form>
	<?PHP if($tipovoto == 0 && $con_voto_usr == 1){echo '<div align="center" style="border-top: 1px solid; margin-top:15px; padding-top:5px;">Tu voto ya est&aacute; registrado para este concurso.</div>';}?>
    <br />
</div>