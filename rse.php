<?PHP include "cnfg/config.php";?>
<?PHP include "inc/funciones.php";?>
<?PHP
$tipo   = 10;
agrega_acceso($tipo);
$nombre = obtenerNombre($tipo);
if (isset($_POST['busqueda'])){$busqueda = $_POST['busqueda'];}elseif (isset($_GET['busqueda'])){$busqueda = $_GET['busqueda'];}
if (isset($busqueda)){
	if ($busqueda<>''){
	  //Cuento la cantidad de palabras
	  $trozos=explode(" ",$busqueda);
	  $numero=count($trozos);
	  if ($numero==1) {
		//Si tengo una palabra
		$cadbusca="SELECT * FROM intranet_".$nombre." WHERE titulo LIKE '%$busqueda%' OR texto LIKE '%$busqueda%' ORDER BY fecha DESC";
	  }elseif ($numero>1){
		//Si tengo m�s de una palabra
		$cadbusca="SELECT *, MATCH (titulo, texto) AGAINST ('$busqueda' IN BOOLEAN MODE) as aprox FROM intranet_".$nombre." WHERE MATCH (titulo, texto) AGAINST ('$busqueda' IN BOOLEAN MODE) ORDER BY aprox DESC";
	  }
	  if (isset($_GET['id'])){
		  $id = $_GET['id'];
		  $cadbusca="SELECT * FROM intranet_".$nombre." WHERE id = $id";
	  }
	}else{
		$cadbusca="SELECT * FROM intranet_".$nombre." ORDER BY fecha DESC";
		$busqueda = '';
	}
}else{
	$cadbusca="SELECT * FROM intranet_".$nombre." ORDER BY fecha DESC";
	$busqueda = '';
}	
  $result = fullQuery($cadbusca);
  $contar = mysqli_num_rows($result);
  $limit  = cantidad('cant_latam');//Cantidad de resultados por p�gina
					if ($limit == 0){
						$limitsql = ' ';
						$limit = $contar;
					}else{
						$limitsql = 'ok';
					}		
					// FIN CUENTA DE REGISTROS Y LIMITE
					if (isset($_GET['pag'])){
						$pag = $_GET['pag'];//Toma la p�gina
					}
					if(empty($pag)){//Si la p�gina est� vac�a
						$pag = '1';//Setea como p�gina 1
					}
					$start = ($pag-1)*$limit;//setar la p�gina de inicio
					$start = round($start,0);//redondea
					if ($limitsql == 'ok'){
						$limitsql = ' LIMIT '.$start.', '.$limit;
					}					
					$query   = $cadbusca.$limitsql;
					$result  = fullQuery($query);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" charset="utf-8" />
<title><?PHP echo $cliente;?> Intranet | <?PHP echo ucwords($nombre);?></title>
<script type="text/javascript" src="inc/scripts.js"></script>
</head>
<body>
<div id="contenedor">
	<?PHP include ("header.php");?>
	<?PHP include "col_izq.php";?>
	<div id="ccentro">
    	<div id="caja-buscador">
        	<form action="<?PHP echo $nombre;?>.php" method="post">
            	<div id="caja-home"><a href="index.php">&nbsp;HOME</a> | <a href="mailto:<?PHP echo $email;?>">CONTACTENOS</a></div>
                <div style="float:left; width:100px; margin-left:20px;">Buscar <?PHP echo ucwords($nombre);?>: </div>
	            <div style="float:left; width:326px">
					<input name="busqueda" type="text" class="txtfield" style="width:324px" id="busqueda"/>
                </div>
                <div style="float:left; width:120px"><input name="search" type="image" title="submit" src="img/botbusca.gif" alt="enviar" align="right"/></div>
            </form>
		</div>
        <?PHP require_once("menu_sup.php");?>
	    <div style="width:700px; float:left; padding-left:10px">
        <?PHP if (file_exists("img/".$nombre.".png")){echo '<img src="img/'.$nombre.'.png" />&nbsp;';}?><span class="tituloseccion"><?PHP echo "Responsabilidad Social Empresaria";//echo ucwords($nombre);?></span>
        <div class="separador"></div>
	<?PHP while ($noticia = mysqli_fetch_array($result)) {?>
		<div class="row-latam-full">
    		<span class="fecha"><?PHP if ($noticia['fecha'] == date("Y-m-d")){echo "Hoy";}else{echo fechaDet($noticia['fecha']);}?></span>
        	<br />
        	<span class="tit"><a href="nota.php?id=<?PHP echo $noticia['id'];?>&tipo=<?PHP echo $tipo;?>"><?PHP echo $noticia['titulo'];?></a></span>
        	<?PHP 
				// imagen principal
				$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$noticia['id']." AND tipo = ".$tipo." ORDER BY id";
				$res_fotos = fullQuery($sql_fotos);
				$total_fotos = mysqli_num_rows($res_fotos);
				if ($total_fotos > 0){
					$row_fotos = mysqli_fetch_array($res_fotos);
					$foto_ppal = $row_fotos['link'];
					$sql_fotos = "SELECT * FROM intranet_fotos WHERE item = ".$noticia['id']." AND tipo = ".$tipo." AND ppal = 1";
					$res_fotos = fullQuery($sql_fotos);
					$cont_foto_ppal = mysqli_num_rows($res_fotos);
					if ($cont_foto_ppal == 1){
						$row_fotos = mysqli_fetch_array($res_fotos);
						$foto_ppal = end(explode("imagen",$row_fotos['link'], -1))."imagen_ppal.jpg";
						echo '<div class="row-latam-img">';
						echo '<img src="'.$foto_ppal.'"/>';
                		echo '</div>';
					}
				}?>
            <br />
        	<?PHP echo cortarTexto($noticia['texto'], 150);?>
            
        	<div style="clear:both;"></div>
    </div>
<?PHP }?>
	    
	    </div>
<?PHP // paginador
		$variables = "busqueda=".$busqueda; // variables para el paginador
		echo paginador($limit, $contar, $pag, $variables);
?>
	  </div>
	<div style="clear:both;"></div>
</div>
<?PHP include "footer.php";?>
</body>
</html>