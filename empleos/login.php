<div id="login">
	<div class="mod-b-texto-rojo" align="right"><?PHP echo $msg_error;?>&nbsp;<br /><br /></div>
	<?PHP
    $contraste = (isset($_GET['cod']) && $_GET['cod'] == 2) ? '_osc' : '';
    if(!isset($_SESSION['usrfrontend'])){
    ?>
        <div style="margin-right:10px; float:none" id="login-in">
            <form action="<?PHP echo $formloc;?>" method="post" id="login_usr" name="login_usr">
                <div><img src="img/usuario<?PHP echo $contraste;?>.png" alt="Usuario" /></div>
                <div><input class="input-text-login" type="text" name="usuario_red" /></div>
                <div><img src="img/contrasena<?PHP echo $contraste;?>.png" alt="Usuario" /></div>
                <div><input class="input-text-login" type="password" name="password" /></div>
                <div><input class="input-button" type="image" src="img/boton-ingresar.png" /></div>
            </form>
        </div>
    <?PHP 
    }else{
    ?>
        <div class="in<?PHP if((isset($_GET['cod']) && $_GET['cod'] == 2)){echo '_osc';}//if((isset($_GET['cod']) && $_GET['cod'] == 2) || !isset($_GET['cod'])){echo '_osc';}?>" style="margin-right:10px; float:none">
            Usuario: <?PHP echo $_SESSION['nombreusr'];?>
            <br /><a href="out.php">Cerrar sesi&oacute;n</a>
            <?PHP
			$sql_mini = "SELECT * FROM intranet_minisitios WHERE usuario = ".$_SESSION['usrfrontend'];
			$res_mini = fullQuery($sql_mini);
			while($row_mini = mysqli_fetch_array($res_mini)){
				echo '<br /><a href="'.$row_mini['link'].'">Ingresar: '.$row_mini['nombre'].'</a>';
			}
			?>
        </div>
    <?PHP }?>
    <!--[if lte IE 7]><br /><![endif]-->
</div>
<br /><br /><br /><br /><br />
