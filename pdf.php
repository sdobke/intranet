<?PHP include "cnfg/config.php";?>
<?PHP include "inc/funciones.php";?>
<?PHP
$tipo   = 10;
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
<link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" charset="utf-8" />
<title><?PHP echo $cliente;?> Intranet | <?PHP echo ucwords($nombre);?></title>
<script type="text/javascript" src="inc/scripts.js"></script>
</head>
<body>
<div id="contenedor">
	<?PHP include ("header.php");?>
	<?PHP include "col_izq.php";?>
	<?PHP 
	
	  
   		$strPDF = "bknoticias37.pdf";
      	exec("convert \"{$strPDF}[0]\" -colorspace RGB -geometry 200 \"output.gif\"");
   

	
	?>
	<div style="clear:both;"></div>
</div>
<?PHP include "footer.php";?>
</body>
</html>