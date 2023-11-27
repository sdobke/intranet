<?PHP 
include ("../../cnfg/config.php");
include ("sechk.php");
include ("inc/inc_funciones_globales.php");

$tipo      = getPost('tipo');

$nombretab = obtenerNombre($tipo);

$nombredet = parametro('detalle',$tipo);
$usafecha  = parametro('fecha',$tipo); // 1 es fecha manual, 2 es vencimiento manual, 3 es fecha auto, 4 es vencimiento auto
$usatexto  = parametro('texto',$tipo);
$nomtitulo = (parametro('nombre_detalle',$tipo) != '') ? parametro('nombre_detalle',$tipo) : 'Nombre';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ALSEA Corporativo | <?PHP echo $nomtitulo;?></title>
<link href="css/style-home.css" rel="stylesheet" type="text/css" />
<?PHP if($usafecha == 1 || $usafecha == 2){echo '<script type="text/javascript" src="calendarDateInput.js"></script>
';}?>
<?PHP if($usatexto == 1){
echo '<script type="text/javascript" src="../../includes/ckeditor/ckeditor.js"></script>
';
echo '<script type="text/javascript" src="../../includes/ckeditor/navegadores.js"></script>
';
}?>
<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<div id="contenedor">
	<div id="header"> </div>
    <?PHP include "menu.php";?>
	<?PHP include "inc/buscador.php";?>
	<div class="tit-result"><a href="listado.php?tipo=<?PHP echo $tipo;?>">LISTADO DE <?PHP echo strtoupper($nombredet);?></a> - <strong>ALTA DE <?PHP echo strtoupper($nombredet);?></strong></div>
	<div style="clear:both;"></div>
	<div style="width:728px">
        <form action="alta_proc.php" method="post" id="form_latam" name="form_latam" enctype="multipart/form-data">
            <table>
            	<tr>
                	<td><input name="tipo" id="tipo" type="hidden" value="<?PHP echo $tipo;?>" />
                        <?PHP echo $nomtitulo;?>:
                    </td>
                    <td><input name="nombre" type="text" id="nombre" size="40" /></td>
                </tr>
                    <?PHP if($usafecha != 0){
                            if($usafecha == 1 || $usafecha == 2){
                                $titufecha = 'Fecha';
                                if($usafecha == 2){
                                    $titufecha = 'Vencimiento';
                                }
                                ?>
                                <tr>
									<td><?PHP echo $titufecha;?>:</td>
									<td><script type="text/javascript">DateInput('fecha', true, 'YYYY/MM/DD')</script></td>
                                </tr>
                            <?PHP }else{ ?>
                            	<tr style="height:0px"><td colspan="2"><input name="fecha" id="fecha" value="<?PHP echo date("Y-m-d");?>" type="hidden" /></td></tr>
                            <?PHP } ?>
                    <?PHP } ?>
                    <?PHP // COMBOS
                    $query_combos = "SELECT tab.nombre AS variable, tab.detalle AS nombre FROM empleos_combos AS cbo
                                        INNER JOIN empleos_tablas AS tab ON (cbo.combo = tab.id)
                                        WHERE cbo.tabla = ".$tipo;
                    $resul_combos = fullQuery($query_combos);
                    while($row_combos = mysqli_fetch_array($resul_combos)){
                        $titulo_combo = $row_combos['nombre'];
                        $var_combo    = $row_combos['variable'];
                        echo '<tr><td>'.$titulo_combo.':</td><td><select name="'.$var_combo.'">';
                        $sql_combo = "SELECT * FROM empleos_".$var_combo." WHERE del = 0";
                        $res_combo = fullQuery($sql_combo);
                        while($row_combo = mysqli_fetch_array($res_combo)){
                            echo '<option value="'.$row_combo['id'].'" >'.$row_combo['nombre'].'</option>';
                        }
                        echo '</select></td></tr>';
                    }
                    ?>
                    <?PHP if($usatexto == 1){?>
                        <tr>
                            <td>Texto:</td>
                            <td>
                                <textarea id="texto" name="texto"></textarea>
								<script type="text/javascript">
                                    //<![CDATA[
                    
                                    // This call can be placed at any point after the
                                    // <textarea>, or inside a <head><script> in a
                                    // window.onload event handler.
                    
                                    // Replace the <textarea id="editor"> with an CKEditor
                                    // instance, using default configurations.
                                    CKEDITOR.replace( 'texto' );
                    
                                    //]]>
                                </script>
                            </td>
                        </tr>
                    <?PHP } ?>
                    <tr><td colspan="2" align="center"><input name="Submit" type="submit" value="Crear" /></td></tr>
            </table>
		</form>
	</div>
	<div style="clear:both;"></div>
</div>
<?PHP include "inc/footer.php";?>
</body>
</html>