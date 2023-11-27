<?PHP
$tipo_noti_foto = isset($tipo_noti_foto) ? $tipo_noti_foto : $tipo;
/*
?>
<div class="mod-b-fecha">
	<?PHP if ($noticia['fecha'] == date("Y-m-d")) {
		echo "Hoy";
	} else {
		echo fechaDet($noticia['fecha']);
	} ?>
</div>
<div class="titulo">
	<a href="nota.php?id=<?PHP echo $noticia['id']; ?>&tipo=<?PHP echo $tipo_noti_foto; ?>">
<?PHP echo $noticia['titulo']; ?>
    </a>
</div>
*/ ?>
<div class="tdest2 left">
	<div class="tdest2-h left">
		<strong>
			<a href="../includes/nota.php?id=<?PHP echo $noticia['id']; ?>&amp;tipo=<?PHP echo $tipo_noti_foto; ?>" style="text-decoration: none;color:white;">
				<?PHP echo $noticia['titulo']; ?>
			</a>
		</strong>
	</div>
	<div class="tdest2-titular left">
		<?PHP if ($noticia['fecha'] == date("Y-m-d")) {
			echo "Hoy";
		} else {
			echo fechaDet($noticia['fecha']);
		} ?>
	</div>
</div>
