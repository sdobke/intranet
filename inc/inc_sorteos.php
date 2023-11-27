<?PHP 
$ea = 'inc/inc_sorteos.php';
if(isset($_SESSION['tipousr']) && $_SESSION['tipousr'] == 1){
	if(isset($_POST['sorteo_usuario'])){ // si el usuario acaba de anotarse
		$sorteo_usuario = $_POST['sorteo_usuario'];
		$sorteo_id      = $_POST['sorteo_id'];
		$sql_sorteo_agr = "INSERT INTO intranet_participantes (`tipoconcurso`,`concurso`,`usuario`,`votos`,`promedio`,`activo`) VALUES ( '19','$sorteo_id','$sorteo_usuario','0','0.00','0')";
		$res_sorteo_agr = fullQuery($sql_sorteo_agr,$ea);
	}
	
	$sql_sorteos = "SELECT * FROM intranet_sorteos WHERE activo = 1 ORDER BY id DESC";
	$res_sorteos = fullQuery($sql_sorteos,$ea);
	$con_sorteos = mysqli_num_rows($res_sorteos);
	if($con_sorteos > 0){
		while($row_sorteos = mysqli_fetch_array($res_sorteos)){
			$id_sorteo   = $row_sorteos['id'];
			$hoy_sorteos = date("Y-m-d");
			$fec_sorteos = $row_sorteos['fecha'];
			if($fec_sorteos >= $hoy_sorteos){
				$altura = 124;?>
				<div id="caja-centro">
					<div style="float:left; width:234px; border-bottom:1px solid #c3c3c3"></div>
					<div align="justify" style="height:<?PHP echo $altura;?>px">
						<?PHP echo '<strong>'.$row_sorteos['titulo'].'</strong>';?>
						<div style="clear:both;"></div>
						<?PHP echo $row_sorteos['texto'];?>
						<?PHP if(isset($_SESSION['usrfrontend'])){?>
							<?PHP
							$sql_sort_ver = "SELECT id FROM intranet_participantes WHERE tipoconcurso = 19 AND concurso = $id_sorteo AND usuario = ".$_SESSION['usrfrontend'];
							$res_sort_ver = fullQuery($sql_sort_ver,$ea);
							$con_sort_ver = mysqli_num_rows($res_sort_ver);
							if($con_sort_ver > 0){
								echo "Ya est&aacute;s participando de este sorteo.";
							}else{
							?>
							<form action="<?PHP echo $_SERVER['../includes/PHP_SELF'];?>" method="post">
								<input type="hidden" id="sorteo_usuario" name="sorteo_usuario" value="<?PHP echo $_SESSION['usrfrontend'];?>" />
								<input type="hidden" id="sorteo_id" name="sorteo_id" value="<?PHP echo $id_sorteo;?>" />
							  <input name="boton_sorteo" type="submit" value="Quiero participar" />
							</form>
							<?PHP } ?>
						<?PHP }else{
							$popup = "MM_openBrWindow('login_popup.php?formloc=backend/".$nombre.".php', 'loginpop', 'scrollbars=yes,width=550,height=100')";
							echo '<a href="javascript:;" onclick="'.$popup.'">Para participar ten&eacute;s que ingresar con tu usuario.</a>';
						}?>
					</div>
				</div>
			<?PHP }?>
		<?PHP }?>
	<?PHP }?>
<?PHP }?>