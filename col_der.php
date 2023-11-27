<div class="sidebar" >

	<?php 

	$col_orlimtx = 100;

	if($_SERVER["REQUEST_URI"] == "/notanulado.php"){

		/*

		include("col_clasificados.php");

		include("col_recomendados.php");

		include("col_galerias.php");

		include("col_concurfotos.php");

		include("col_encuestas.php");

		*/

	}else{

		$tipoani = 1;

		include("columna/col_cumples.php");

		$tipoani = 2;

		include("columna/col_cumples.php");

		//include("columna/col_yammer.php");

		$tipo_col = 20; // Beneficios

		//include("columna/col_base.php");

		//$tipo_col = 18; // Clasificados

		//include("columna/col_base.php");

		$tipo_col = 14; // Recomendados

		include("columna/col_base.php");

		$tipo_col = 19; // Sorteos

		//include("columna/col_base.php");

		include("columna/col_galerias.php");

		$colsec = 11; // Incorporaciones

		include("columna/col_novedades_sec.php");

		$colsec = 13; // Oportunidades laborales

		//include("columna/col_novedades_sec.php");

		$colsec = 16; // Calendario

		include("columna/col_novedades_sec.php");

	}

	?>						

</div>