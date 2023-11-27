<div id="caja-buscador">
	<form action="listado.php?tipo=<?PHP echo $tipo;?>" method="post">
        <div style="float:left; width:270px" align="right"><strong><?PHP echo ucwords($nombredet);?>&nbsp;&nbsp;</strong></div>
        <div style="float:left; width:326px">
            <input name="busqueda" type="text" class="txtfield" style="width:324px" id="busqueda"/>
        </div>
        <div style="float:left; width:120px"><input name="search" type="image" title="submit" src="../img/botbusca.gif" alt="enviar" align="right"/></div>
	</form>
</div>