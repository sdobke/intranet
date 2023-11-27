<?PHP 
include "includes/conexion.php";
include "includes/inc_funciones_globales.php";
include "includes/inc_docs.php";
include "login_init.php";

$tipo   = isset($_GET['tipo']) ? $_GET['tipo'] : 7;
$tipo   = isset($_POST['tipo']) ? $_POST['tipo'] : $tipo;
$nombre = obtenerNombre($tipo);
$nombre_titulo = $nombre;
$titsec = $nombre;

$usafotos = 1;
$usafecha = 1;
$usahora  = 1;
$usatexto = 1;
$texto    = 'texto';
$titulado = '';
$continuacion = 0;
$usavotos = 0;
$usadocs = 0;

if($tipo == 17){ //sugerencias
	$usafotos = 0;
	$usahora  = 0;
	$texto    = 'sugerencia';
	$titulado = '<div class="titulo" style="font-size:12px">SUGERENCIA</div>';
	$continuacion = 1;
	//$otro_query = "activo = 1";
}
if($tipo == 14){ // Recomendados
	$usafecha = 0;
	$otro_query = "activo = 1";
}
if($tipo == 18 || $tipo == 23){ // clasificados y novedes en locales
	$usahora  = 0;
}

if($tipo == 2){ // Comint
	$titsec = 'Comunicaciones Internas';
	$usatexto = 0;
}
if($tipo == 22){ // Seguridad e higiene
	$usahora = 0;
	$usadocs = 1;
	$usafecha = 0;
	
/*$sql_usr_loc = "SELECT id FROM intranet_tyl WHERE usuario = ".$_SESSION['usrfrontend'];
$res_usr_loc = fullQuery($sql_usr_loc);
$con_usr_loc = mysqli_num_rows($res_usr_loc);
if($con_usr_loc > 0){
	$row_usr_loc = mysqli_fetch_array($res_usr_loc);
	$usuario_local = $row_usr_loc['id'];
}else{
	$usuario_local = '0';
}

$otro_query = "usuario = ".$usuario_local;
*/
$otro_query = "usuario = ".$_SESSION['usrfrontend'];
}
if($tipo == 23){ // Locales
	$nombre_titulo = 'novedades';
}
if($tipo == 20){ //beneficios
	$usahora = 0;
}
$link_destino_search = $nombre.'.php';
//$link_destino_search = 'novedades.php';
$id = isset($_GET['id']) ? $_GET['id'] : 1;
$where_adicional = (isset($otro_query)) ? " AND ".$otro_query : '';
$query = "SELECT * FROM intranet_".$nombre." WHERE id = ".$id.$where_adicional;

$result = fullQuery($query);
$noticia = mysqli_fetch_array($result);
if($usatexto == 1){
	$noticia_texto = $noticia[$texto];
}
agrega_acceso(55);
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title><?PHP echo $cliente;?> Intranet | <?PHP echo ucwords($nombre);?></title>
<link type="text/css" rel="stylesheet" href="css/lightbox.css" media="screen" />
<?PHP include ("head.php");?>
<script type="text/javascript" src="includes/prototype.js"></script>
<script type="text/javascript" src="includes/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="includes/lightbox.js"></script>
</head>
	<body onload="show5()">    <?PHP include ("header.php");?>
		<div id="main-wrapper">
			<?PHP include ("menu.php");?>
			<div id="content-wrapper">
                <!--[if lt IE 7]>
                    <table><tr valign="top">
                    	<td>
                <![endif]-->
				<?PHP include ("col_izq.php");?>
                <!--[if lt IE 7]>
	            	</td>
                    <td>
				<![endif]-->
				<div id="right-wrapper">
                    <!--[if lt IE 7]>
                        <table><tr valign="top"><td colspan="2">
                    <![endif]-->
					<?PHP include ("top.php");?>
                    <!--[if lt IE 7]>
                        </td></tr>
                        <tr valign="top"><td>
                    <![endif]-->
					<div id="main-content">
						<?PHP include ("includes/tamanio-fuente.php");?>
						<div id="novedades" style="width:700px; padding-bottom:10px; padding-left:10px">
							<div id="novedades-header">
                            	<a href="<?PHP echo $nombre_titulo;?>.php">
                                	<img src="img/titulos/<?PHP echo $nombre_titulo;?>.gif" alt="<?PHP echo $nombre_titulo;?>" title="<?PHP echo $nombre_titulo;?>" />
                                </a>
                            	<div class="mod-b-texto" style="float:right; padding-top:10px"><a href="<?PHP echo $nombre_titulo;?>.php" >Volver a <?PHP echo $nombre_titulo;?>&nbsp;</a></div>
                          </div>
                            
							<?PHP 
							if ($usafecha == 1){
							?>
								<div class="mod-b-fecha">
									<br />
									<?PHP if ($noticia['fecha'] == date("Y-m-d")){echo "Hoy";}else{echo fechaDet($noticia['fecha']);}?>
									<?PHP 
									if ($usahora == 1){
									?>
										- <b><?PHP echo substr($noticia['hora'],0,5);?></b>
									<?PHP } ?>
								</div>
							<?PHP } ?>
							<div class="titulo">
								<?PHP echo $noticia['titulo'];?>
								<br />
							</div>
							<div class="texto">
								<table width="690"><tr><td>
									<?PHP 
									if($usafotos == 1){
										// imagenes
										$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY id";
										$res_fotos = fullQuery($sql_fotos);
										$total_fotos = mysqli_num_rows($res_fotos);
										if ($total_fotos > 0){ // hay fotos
											
											$row_fotos = mysqli_fetch_array($res_fotos);
											$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$id." AND tipo = ".$tipo." AND ppal = 1";
											$res_fotos = fullQuery($sql_fotos);
											$cont_foto_ppal = mysqli_num_rows($res_fotos);
											$alto_foto = 30;
											if ($cont_foto_ppal == 1){ // hay ppal
												$row_fotos = mysqli_fetch_array($res_fotos);
												$foto_ppal = end(explode("imagen",$row_fotos['link'], -1))."imagen_nota.jpg";
												$tam_foto  = GetImageSize($foto_ppal);
												$alto_foto = $tam_foto[1]+40;
												$link_foto_1 = $row_fotos['link'];
											}
											
											$sql_fotos_trip = "SELECT * FROM intranet_fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY id LIMIT 1";
											$res_fotos_trip = fullQuery($sql_fotos_trip);
											$row_fotos_trip = mysqli_fetch_array($res_fotos_trip);
											$link_foto_1    = $row_fotos_trip['link'];
											?>
											<div class="foto-nota" style="height:<?PHP echo $alto_foto;?>px;">
												<a href="<?PHP echo $link_foto_1;?>" rel="lightbox[roadtrip]" >
													<?PHP if(isset($foto_ppal)){?><img src="<?PHP echo $foto_ppal;?>" /><br /><?PHP } ?>
													<span class="foto-nota-texto-gal">&nbsp;Ver galer&iacute;a de fotos&nbsp;</span>
												</a>
											</div>
											<?PHP 
											$sql_fotos_trip = "SELECT * FROM intranet_fotos WHERE item = ".$id." AND tipo = ".$tipo." ORDER BY id LIMIT 1,1000";
											$res_fotos_trip = fullQuery($sql_fotos_trip);
											while ($row_fotos_trip = mysqli_fetch_array($res_fotos_trip)){
											?>
											   <a href="<?PHP echo $row_fotos_trip['link'];?>" rel="lightbox[roadtrip]"></a>
											 <?PHP } ?>
										<?PHP } ?>
									<?PHP } ?>
									<?PHP // texto
									if($usatexto == 1){
									echo $titulado;
									echo str_replace('&quot;' ,'"', $noticia_texto);
									}
									if($continuacion == 1){
										echo '<div class="separador"></div>';
										echo '<br /><div class="titulo" style="font-size:12px">RESPUESTA</div>';
										echo str_replace('&quot;' ,'"', $noticia['respuesta']);
									}
									?>
								<?PHP if($usadocs == 1){
                                    $sql_doc = "SELECT * FROM intranet_docs WHERE empresa = 0 AND sector = ".$noticia['id'];
                                    $res_doc = fullQuery($sql_doc);
                                    $con_doc = mysqli_num_rows($res_doc);
                                    if($con_doc > 0){
                                        $doc = mysqli_fetch_array($res_doc);
                                        $nombre_doc = $doc['nombre'];
                                        echo '<div class="mod-b-fecha"><strong>Documento adjunto:</strong> <a href="../'.$doc['link'].'" target="_blank">'.$nombre_doc.'</a></div>';
                                    }
                                }?>
        						</td></tr></table>
            				</div>
							<?PHP
                            if($usavotos == 1){ // si los votos est&aacute;n habilitados
                            echo '<p>';
                                if(isset($_SESSION['usrfrontend'])){// si el usuario est&aacute; logueado
                                    $id_usr_front = $_SESSION['usrfrontend'];
                                    if(isset($_POST['voto'])){ // si acaba de votar
                                        $sql_voto_usr = "SELECT * FROM intranet_votos WHERE tabla = ".$tipo." AND item = ".$noticia['id']." AND usuario = ".$id_usr_front;
                                        $res_voto_usr = fullQuery($sql_voto_usr);
                                        $con_voto_usr = mysqli_num_rows($res_voto_usr);
                                        if($con_voto_usr == 0){ // si el usuario no vot&oacute; este �tem
                                            $voto = $_POST['voto'];
                                            $voto_usr = $_SESSION['usrfrontend'];
                                            $sql_dar_voto = "INSERT INTO intranet_votos (voto, usuario, tabla, item) VALUES ($voto, $voto_usr, $tipo, $id)";
                                            //echo '<br />'.$sql_dar_voto.'<br />';
                                            $res_dar_voto = fullQuery($sql_dar_voto);
                                            $sql_suma = "SELECT voto FROM intranet_votos WHERE item = ".$id." AND tabla = ".$tipo; // buscamos todos los votos para este item
                                            //echo '<br />'.$sql_suma.'<br />';
                                            $res_suma = fullQuery($sql_suma);
                                            $sumatoria = 0;
                                            while($row_suma = mysqli_fetch_array($res_suma)){
                                                $sumatoria = $sumatoria + $row_suma['voto']; // los sumamos
                                            }
                                            $sql_cambiar_votos = "SELECT votos FROM intranet_".$nombre." WHERE id = ".$id; // calculamos total de votos
                                            //echo '<br />'.$sql_cambiar_votos.'<br />';
                                            $res_cambiar_votos = fullQuery($sql_cambiar_votos);
                                            $row_cambiar_votos = mysqli_fetch_array($res_cambiar_votos);
                                            $cant_votos_new = $row_cambiar_votos['votos']+1; // agregamos este voto
                                            $prom_votos_new = $sumatoria / $cant_votos_new; // sacamos el promedio
                                            // guardamos los datos
                                            $sql_guarda = "UPDATE intranet_".$nombre." SET votos = ".$cant_votos_new.", promedio = ".$prom_votos_new." WHERE id = ".$id;
                                            //echo '<br />'.$sql_guarda.'<br />';
                                            $res_guarda = fullQuery($sql_guarda);
                                        }
                                    }
                                    $sql_voto_usr = "SELECT usuario FROM intranet_votos WHERE tabla = ".$tipo." AND item = ".$noticia['id']." AND usuario = ".$id_usr_front;
                                    $res_voto_usr = fullQuery($sql_voto_usr);
                                    $con_voto_usr = mysqli_num_rows($res_voto_usr);
                                    if($con_voto_usr == 0){ // si el usuario no vot&oacute; este �tem
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
                                        $formloc   = $_SERVER['PHP_SELF'].$queryget;
                                        echo '<form id="votos" name="votos" method="post" action="'.$formloc.'">';
                                        echo 'Darle puntaje a esta recomendaci&oacute;n: ';
                                        echo '<select id="voto" name="voto">';
                                        $contar_voto = 1;
                                        while($contar_voto < 11){
                                            echo '<option value="'.$contar_voto.'">'.$contar_voto.'</option>';
                                            $contar_voto++;
                                        }
                                        echo '<input type="submit" name="button_'.$noticia['id'].'" id="button_'.$noticia['id'].'" value="Votar" />';
                                        echo '</select>';
                                        echo '</form>';
                                    }else{
                                        echo "Tu voto ya est&aacute; registrado para esta recomendaci&oacute;n.<br />";
                                    }
                                }else{
                                    echo "Para votar es necesario que ingreses con tu usuario.";
                                    require_once("login.php");
                                }
                                // Se muestran los resultados
                                $sql_votos = "SELECT * FROM intranet_".$nombre." WHERE id = ".$noticia['id'];
                                $res_votos = fullQuery($sql_votos);
                                $row_votos = mysqli_fetch_array($res_votos);
                                echo "<br />";
                                echo "Cantidad de votos: ".$row_votos['votos'];
                                echo "<br />";
                                $promedio = $row_votos['promedio'];
                                $decimales = end(explode('.',$promedio));
                                if($decimales == '00'){
                                    $promedio = current(explode('.',$promedio));
                                }
                                echo "Promedio: ".$promedio;
                                echo '</p>';
                            }
                            ?>
						</div>
					</div>
				<!--[if lt IE 7]>
	            		</td>
                    	<td>
					<![endif]-->
                    <!--[if lt IE 7]>
	            		</td>
                    	</tr></table>
					<![endif]-->
				</div>
				<!--[if lt IE 7]>
	            		</td>
                    </tr></table>
				<![endif]-->
			</div>
			<?PHP include "footer.php";?>
		</div>
	</body>
</html>