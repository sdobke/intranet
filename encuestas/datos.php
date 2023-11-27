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
$id = $_SESSION['actiform'];
$_SESSION['prefijo'] = 'intranet_';
$sqlen = "SELECT titulo FROM intranet_encuesta_datos WHERE id = ".$id;
$resen = fullQuery($sqlen);
$rowen = mysqli_fetch_assoc($resen);
$nombre = $rowen['titulo'];

// SI SE EJECUTO UNA FUNCION DE ABM
if (isset($_GET['func']) || isset($_POST['func'])){
	$nombre = $_POST['nombre'];
	$sql_mod = "UPDATE intranet_encuesta_datos SET titulo = '".$nombre."' WHERE id = ".$id;
	$res_mod = fullQuery($sql_mod);	
	
	//Restricciones
	$sql_borra = "DELETE FROM ".$_SESSION['prefijo']."link WHERE item = ".$id." AND tipo = ".$tipo;
	$res_borra = fullQuery($sql_borra);
	if(isset($_POST['todos'])){ // si se seleccionaron todos
		// se agrega el link de "todos"
		$nuevo_id =  nuevoID("link"); // genero un nuevo número de ID
		$sql_ins_link = "INSERT INTO ".$_SESSION['prefijo']."link (id,tipo,item,part) VALUES (".$nuevo_id.", ".$tipo.", ".$id.", 0)";
		$res_ins_link = fullQuery($sql_ins_link);
	}else{ // sinó se guardan los que correspondan
		// Recorremos todos los tipos de restricción
		$sql_valores = "SELECT * FROM ".$_SESSION['prefijo']."tipoemp";
		$res_valores = fullQuery($sql_valores);
		while($row_valores = mysqli_fetch_array($res_valores)){
			$tipo_partic = $row_valores['id'];
			if(isset($_POST['valor_'.$tipo_partic])){ // si ese tipo de restricción fue seleccionado
				$post_tipo = $_POST['valor_'.$tipo_partic];
				$sql_link = "SELECT * FROM ".$_SESSION['prefijo']."link WHERE item = ".$id." AND part = ".$tipo_partic." AND tipo = ".$tipo;
				$res_link = fullQuery($sql_link, 'detalles_post.php');
				$con_link = mysqli_num_rows($res_link);
				if($con_link == 0){ // insertamos la opción
					$nuevo_id =  nuevoID("link"); // genero un nuevo número de ID
					$sql_ins_link = "INSERT INTO ".$_SESSION['prefijo']."link (id,tipo,item,part) VALUES (".$nuevo_id.", ".$tipo.", ".$id.", ".$tipo_partic.")";
					$res_ins_link = fullQuery($sql_ins_link, 'detalles_post.php');
				}
			}
		}
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    <script type="text/javascript" src="/js/jsapi"></script>
		<script type="text/javascript">
			google.load("jquery", "1");
			google.setOnLoadCallback(function(){
				$("#todos").click(function(){
					var chks=$("input:checkbox[name^='valor_']");
					chks.attr("checked",$(this).is(":checked"))
				})
				$("input[name^='valor_']").click(function(){
					var todos=$("input:checkbox[name^='valor_']")
					var activos=$("input:checked[name^='valor_']")
					$("#todos").attr("checked",todos.length==activos.length)
				})
			})
		</script>
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
						<div class="contenidos" align="center" style="width:500px; margin:auto; padding:50px; text-align:left">
                        	<form name="formod" id="formod" method="post" action="datos.php">
	                        	T&iacute;tulo de la encuesta: <input name="nombre" value="<?PHP echo $nombre;?>" type="text" />
                                <br /><br />
                                <?PHP 
								$tipoarchivo = 'detalles';
								include("../backend/inc/restricciones.php");
								?>
                                <input name="func" type="hidden" value="2"/>
								<br /><br />
								<a href="javascript:void(0);" class="button" id="grabarTipo" onclick="submitformMod();">
									<span class="icon icon67"></span>
									<span class="label">Modificar Todo</span>
								</a>
                            </form>
							<div style="clear:both"></div>
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