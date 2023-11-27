<!-- old
<div class="titulo">
	<a href="nota.php?id=<?PHP echo $noticia['id'];?>&tipo=<?PHP echo $tipo;?>">
		<?PHP echo $noticia['titulo'];?>
	</a>
</div>
-->
<h2>
	<a href="../includes/nota.php?id=<?PHP echo $noticia['id'];?>&amp;tipo=<?PHP echo $tipo;?>">
		<?PHP echo $noticia['titulo'];?>
	</a>
</h2>