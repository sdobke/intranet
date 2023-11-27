<?PHP
include "../cnfg/config.php";
include "../backend/inc/sechk.php";
include "../inc/funciones.php";
include_once("func_encuestas.php");
function aplanaTexto($texto){
	$original  = array(" ","á","é","í","ó","ú","ñ","ü","-");
	$reemplazo = array("_","a","e","i","o","u","n","u","_");
	$devuelve  = str_replace($original,$reemplazo,strtolower($texto));
	return $devuelve;
}

global $error;
$error = 'ok';
$tipo = 52;
$id = 1;
$_SESSION['prefijo'] = 'intranet_';

if(isset($_POST['funcf']) && $_POST['funcf'] == 1){ // Si es un nuevo form
	$id = nuevoID("encuesta_datos");
	$titulo = $_POST['titulo'];
	$sql = "INSERT INTO ".$_SESSION['prefijo']."encuesta_datos (id, titulo, activa, del) VALUES (".$id.", '".$titulo."', 0, 0)";
	$res = fullQuery($sql);
}

if(isset($_GET['del'])){
	$sqld = "SELECT id FROM ".$_SESSION['prefijo']."encuesta_datos WHERE id = ".$_GET['del'];
	$resd = fullQuery($sqld);
	$cond = mysqli_num_rows($resd);
	if($cond == 1){
		$sqld1 = "UPDATE ".$_SESSION['prefijo']."encuesta_datos SET del = 1 WHERE id = ".$_GET['del'];
		$resd1 = fullQuery($sqld1);
	}
}

if(isset($_GET['act'])){
	$sqla = "SELECT id, titulo FROM ".$_SESSION['prefijo']."encuesta_datos WHERE id = ".$_GET['act'];
	$resa = fullQuery($sqla);
	$cona = mysqli_num_rows($resa);
	if($cona == 1){
		$rowa = mysqli_fetch_assoc($resa);
		$_SESSION['actiform'] = $_GET['act'];
		$_SESSION['actinomb'] = $rowa['titulo'];
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<title><?PHP echo $cliente;?> Intranet | Encuestas</title>
		<?PHP include ("head.php"); ?>
		<style>
			.success_box,
			.alert_box,
			.info_box
			{
				width: 900px !important;
			}
		</style>
		<script type="text/javascript" src="inc/scripts.js"></script>
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="/index.php"><img src="../img_new/logo.png" width="170" height="75" /></a>
					</div>
				<?php include_once '../login.php'; ?>					
				</div>
				<div class="container">
					<div class="hd-minisitio mb5">Encuestas</div>					
					<div class="left w100" >						
						<?PHP include "menu.php";?>
						<div class="contenidos" align="center" style="width:100%; margin:auto; padding:50px; text-align:left">
                        	<strong>Nueva Encuesta</strong><br />
                        	<div class="formularios left mb15">
                                <form name="formcrear" id="formcrear" method="post" action="encuestas.php">
                                    T&iacute;tulo de la encuesta: <input name="titulo" type="text" />
                                    <br /><br />
                                    <input name="funcf" type="hidden" value="1"/>
                                    <a href="javascript:void(0);" class="button" id="grabarTipo" onclick="submitformCrear();">
                                        <span class="icon icon67"></span>
                                        <span class="label">Crear</span>
                                    </a>
                                </form>
                            </div>
							<div style="clear:both"></div>
                            <strong>Listado de encuestas</strong><br />
                            <div class="formularios left mb15">
                                <form action="encuestas.php" method="post" id="formod" name="formod">
                                        <table width="100%" cellpadding="5" cellspacing="1" id="autor" style="color:#333">
                                          <thead>
                                            <tr>
                                              <th>Nombre</th>
                                              <th>Marcar como activa</th>
                                              <th>Eliminar</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?PHP
                                            $sql3 = "SELECT * FROM ".$_SESSION['prefijo']."encuesta_datos WHERE del = 0 ORDER BY id DESC";
                                            $res3 = fullQuery($sql3);
                                            while($row3 = mysqli_fetch_array($res3)){
                                                ?>
                                                <tr>
                                                    <td><?PHP echo $row3['titulo'];?></td>
                                                    <td><a class="btn" href="encuestas.php?act=<?PHP echo $row3['id'];?>">Activar</a></td>
                                                    <td><a class="btn" href="encuestas.php?del=<?PHP echo $row3['id'];?>">Borrar</a></td>
                                                </tr>
                                            <?PHP }?>
                                          </tbody>
                                    </table>
                                  </form>
                             </div>
						</div>
					</div>
					<div class="left w100 ac brd-t mt10"><img src="../img_new/cierre.png" width="630" height="71" /></div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>