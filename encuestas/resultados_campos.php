<div id="contenedor"  class="buscador-minisitio mt10" style="overflow: auto;">

	<div id="centro">
		<?PHP
		// ************* RESULTADOS POR CAMPO

		$query_campos = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_campos WHERE opciones = 1 ORDER BY orden";
		$resul_campos = fullQuery($query_campos);

		while ($campo = mysqli_fetch_array($resul_campos)) {
			$grafico = '<div class="row-latam">';
			$grafico.='	<table width="800px" border="0"><tr><td width="250px">';

			$id_campo = $campo['id'];
			$titulocampo = codTx($campo['nombre']);
			$tipo_campo = $campo['tipo'];
			$contenido = '`'.$campo['id'].'`';
			$totcant = 0;
			$cont = 0;
			$grafico.= '<span style="font-size:14px;"><strong>' . ucwords($titulocampo) . '</strong></span>:';
			$grafico.= "<br /><br />";
			$chart1 = "<img src='chart.php?data=";
			$chart2 = "";

			if ($tipo_campo == 6) { // SOLO PARA EL CASO DE V/F (Sí o No)
				$query_datos = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_listado WHERE 1 " . $buscatexto . " AND " . $contenido . " = 1 " . $cadresto; // resultados "SI"
				$resul_datos = fullQuery($query_datos);
				$cantidad = mysqli_num_rows($resul_datos); //cantidad de veces que aparece ese valor
				$chart1 .= $cantidad; //nro de veces
				$totcant = $totcant + $cantidad;
				$grafico.= "Si: " . $cantidad . "<br />";

				$query_datos = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_listado WHERE 1 " . $sql_tc_grf . $buscatexto . " AND " . $contenido . " = 0 " . $cadresto; // resultados "NO"
				$resul_datos = fullQuery($query_datos);
				$cantidad = mysqli_num_rows($resul_datos); //cantidad de veces que aparece ese valor
				$chart1 .= "*" . $cantidad; //nro de veces
				$chart2 .= "Si*No"; //nombre del campo
				$grafico.= "No: " . $cantidad . "<br />";
				$totcant = $totcant + $cantidad;
			}

			$query_graf = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = $id_campo";
			$resul_graf = fullQuery($query_graf);
			if ($titulocampo == 'Tienda') {
				$graficob = '<div class="row-latam">';
				$graficob.='	<table width="800px" border="0"><tr><td width="250px">';
				$chartb1 = "";
				$chartb2 = "";

				$graficob.= "<br /><br />";
			}

			while ($dato_graf = mysqli_fetch_array($resul_graf)) { // recorre cada resultado posible
				$id_opc = $dato_graf['id'];
				$nombre_opc = codTx($dato_graf['opcion']);
				$query_datos2 = "SELECT * FROM ".$_SESSION['prefijo']."encuestas_listado WHERE 1 " . $buscatexto . " AND " . $contenido . " = " . $id_opc . " " . $cadresto;
				$resul_datos2 = fullQuery($query_datos2);
				$cantidad = mysqli_num_rows($resul_datos2); //cantidad de veces que aparece ese valor
				$totcant = $totcant + $cantidad;
				// por cada resultado posible muestra las cantidades
				if ($cont > 0) {
					$chart1 .= "*";
					$chart2 .= "*";
				}

				$chart1 .= $cantidad; //nro de veces
				$chart2 .= cortarTexto($nombre_opc, 25); //nombre del campo

				$grafico.= $nombre_opc . ": " . $cantidad . "<br />";
				$cont++;
			}
			$chart = $chart1 . "&label=" . $chart2 . "' /><br /><br />";
			$grafico.='</td><td>' . $chart . '</td></tr></table>';
			$grafico.='</div>';

			if ($totcant > 0) {
				echo $grafico;
			}
		}
		?>
	</div>

	<div style="clear:both;"></div>
</div>
