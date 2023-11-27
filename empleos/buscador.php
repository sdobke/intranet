<?PHP
if(!isset($link_destino_search)){$link_destino_search = end(explode("/", $_SERVER['PHP_SELF']));}

?>
<!--BUSCADOR-->
<div id="buscar">
    <form action="<?PHP echo $link_destino_search;?>" method="post">
        <div class="texto">
        	<!--<img src="img/buscador.gif" alt="Usuario" />-->
            Buscador
        </div>
        <div><input class="input-text-buscar" type="text" name="busqueda" id="busqueda"/></div>
        <input type="hidden" name="tipo" id="tipo" value="<?PHP echo $tipo;?>"/>
        <div><input class="input-button" type="image" src="../img/boton-buscar.gif" /></div>
    </form>
</div>