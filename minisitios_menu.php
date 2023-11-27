<div id="menu" class="mb15">
	<div class="left">	
		<ul class="topnav">
			<li><a href="/index.php">Volver a Intranet</a></li>
            <?PHP
			$smm = "SELECT * FROM intranet_minisitios WHERE tipo = 1";
			$rmm = fullQuery($smm);
			while($rrm = mysqli_fetch_array($rmm)){
				?>
				<li><a href="/<?PHP echo $rrm['link'];?>"><?PHP echo $rrm['nombre'];?></a></li>
			<?PHP }?>
		</ul>
	</div>
    <?PHP if($usrger == 1){?>
    	<div class="right mt5 mr10 loginger">
			<a href="/out.php">Salir</a>
		</div>
	<?PHP } ?>
</div>