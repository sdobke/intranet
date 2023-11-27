<?PHP
include ("../../cnfg/config.php");
include ("sechk.php");
include ("inc/inc_funciones_globales.php");

$tipo   = getPost('tipo');
$nombretab = obtenerNombre($tipo);
$nombredet = parametro('detalle',$tipo);

$usafecha  = parametro('fecha',$tipo); // 1 es fecha manual, 2 es vencimiento manual, 3 es fecha auto, 4 es vencimiento auto
$usatexto  = parametro('texto',$tipo);
$nomtitulo = parametro('nombre_detalle',$tipo);
$usacanti  = parametro('cantidad',$tipo);

// busqueda
$buscampo1 = parametro('buscampo1',$tipo);
$buscampo2 = parametro('buscampo2',$tipo);

include "inc/query_busqueda.php";

$limit = parametro('resultados',$tipo);//Cantidad de resultados por p�gina

include "inc/prepara_paginador.php";
include "inc/muestra_errores.php";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | <?PHP echo $nombredet;?></title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function confirmDelete(delUrl) {
	if (confirm("�Est� seguro que quiere eliminar ese registro?")) {
		document.location = delUrl;
	}
}
</script>
</head>
<body>
<div id="contenedor">
	<div id="header">  </div>
    <?PHP include ("menu.php");?>
    <?PHP if($buscampo1 != ''){include ("inc/buscador.php");}?>
	<div class="tit-result">
    	<strong>LISTADO DE <?PHP echo strtoupper($nombredet);?></strong> - <a href="alta.php?tipo=<?PHP echo $tipo;?>">ALTA DE <?PHP echo strtoupper($nombredet);?></a>
    </div>
	<div style="clear:both;"></div>
	<div style="width:728px">
	<?PHP echo $error_msg;?>
        <form action="modificar.php" method="post">
        	<input name="tipo" id="tipo" type="hidden" value="<?PHP echo $tipo;?>" />
    	    <?PHP 
			$result = fullQuery($query);
			while($dato=mysqli_fetch_array($result)){
				?>
		        <input name="id" type="hidden" id="id" value="<?PHP echo $dato['id'];?>" />
        		<div class="caja-result">
					<div class="row-result" align="center">
						<span class="nom">
                           	<a href="detalles.php?tipo=<?PHP echo $tipo;?>&id=<?PHP echo $dato['id'];?>">
								<?PHP echo $dato['nombre'];?>
							</a>
						</span>
						<br />
						<?PHP if($usafecha != '0'){?>
							Fecha: <?PHP echo FechaDet($dato['fecha'],'largo','s');?>
						<?PHP } ?>
						<?PHP if($usacanti != ''){?>
							<br />
                            <?PHP echo $usacanti;?>: <?PHP echo $dato['cantidad'];?>
						<?PHP } ?>
					</div>
					<div align="center">
						<a href="javascript:confirmDelete('baja.php?tipo=<?PHP echo $tipo;?>&id=<?PHP echo $dato['id'];?>')">Eliminar Registro</a>
					</div>
					<br />
				</div>
	        <?PHP }?>
			<div style="clear:both;"></div>
			<div align="center"><input name="modificar" type="submit" id="modificar" value="Modificar Todo" /></div>
		</form>
		<div class="paginador">
			<?PHP
            $variables = "tipo=".$tipo."&busqueda=".$busqueda; // variables para el paginador
            echo paginador($limit, $contar, $pag, $variables);
            ?>
		</div>   
    </div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>