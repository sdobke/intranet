<?PHP
$cantidad_de_fotos = 3;
$cantidad_de_muestras = 4;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?PHP require_once("inc/head.php");?>
</head>
<body onLoad="MM_preloadImages('img/rotulos_del_costado/calendarios_cambiado.png','img/rotulos_del_costado/tarjetas_cambiado.png','img/rotulos_del_costado/stickers_cambiado.png','img/rotulos_del_costado/juegos_cambiado.png','img/rotulos_del_costado/souvenirs_cambiado.png','img/rotulos_del_costado/portarretratos_cambiado.png')">
<?PHP require_once("inc/header.php");?>
<div align="center" class="div_central">
	<table width="992"><tr>
    	<td valign="top"><?PHP require_once("col_izq.php");?></td>
        <td>
		<table width="900"><tr><td>
        <div align="center" style="margin:30px; margin-left:60px; float:left">
        
        	<table background="img/rotulos_secundarios/tarjetones.png" width="276" height="276">
            	<tr>
                	<td height="100" colspan="2"></td>
                </tr>
                <tr>
                	<td height="170">
                    	<div align="center">
                        	<a href="/cliente/fotos/calendarios/tarjetones//cliente/fotos/1.jpg" rel="lightbox[roadtrip]"><img src="img/rotulos_secundarios/tarjetones_fotos.jpg"/></a>
<?PHP 
			$foto= 2;
			while ($foto <= $cantidad_de_fotos){
?>
				<a href="/cliente/fotos/calendarios/tarjetones//cliente/fotos/<?PHP echo $foto;?>.jpg" rel="lightbox[roadtrip]"></a>
<?PHP
				$foto++;
			}
?>
					</div>
                    </td>
                    <td>
                    <div align="center">
                    	<a href="/cliente/fotos/calendarios/tarjetones/muestras/1.jpg" rel="lightbox[roadtrip2]"><img src="img/rotulos_secundarios/tarjetones_muestras.jpg"/></a>
			<?PHP $foto= 2;
			while ($foto <= $cantidad_de_muestras){
?>
				<a href="/cliente/fotos/calendarios/tarjetones/muestras/<?PHP echo $foto;?>.jpg" rel="lightbox[roadtrip2]"></a>
<?PHP
				$foto++;
			}
?>
				</div>
                </td>
                </tr>
            </table>
        </div>
      	<div align="center" style="margin:30px; margin-left:80px; float:left">
            <img src="img/menu/tarjetas.png" alt="tarjetas" width="276" height="276">
        </div>
	</td></tr></table>
	<table width="900"><tr><td>
        <div align="center" style="margin:30px; margin-left:60px; float:left">
            <img src="img/menu/stickers.png" alt="stickers" width="276" height="278">
        </div>
        <div align="center" style="margin:30px; margin-left:80px; float:left">
            <img src="img/menu/juegos.png" alt="juegos" width="276" height="276">
        </div>
	</td></tr></table>
	<table width="900"><tr><td>
        <div align="center" style="margin:30px; margin-left:60px; float:left">
            <img src="img/menu/souvenirs.png" alt="souvenirs" width="276">
        </div>
        <div align="center" style="margin:30px; margin-left:80px; float:left">
            <img src="img/menu/portarretratos.png" alt="portarretratos" width="276" height="276">
        </div>
	</td></tr></table>
    </td>
    </tr>
</table>
</div>
</body>
</html>