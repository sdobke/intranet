<?PHP
$sqlpar = "SELECT * FROM intranet_tablas WHERE id = " . $tipo;
$respar = fullQuery($sqlpar);
while ($rowpar = mysqli_fetch_assoc($respar)) {
	$nombretab = $tipodet = $rowpar['nombre'];
	$nombredet = $rowpar['detalle'];
	$usafotos  = $rowpar['fotos'];
	$usavideos = $rowpar['videos'];
	$usafecha  = $rowpar['fecha'];
	$usahora   = $rowpar['hora'];
	$tipotit   = ($rowpar['titulo'] == '') ? 'titulo' : $rowpar['titulo'];
	$usatexto  = $rowpar['texto'];
	$usacolor  = $rowpar['color'];
	$usavideos = $rowpar['videos'];
	$borrable  = $rowpar['tabla_dependencia'];
	$indice    = ($rowpar['indice'] != '') ? $rowpar['indice'] : $tipotit;
	$activable = $rowpar['activable'];
	$usarest   = $rowpar['restriccion'];
	$order_by    = ($rowpar['orden'] != '') ? $rowpar['orden'] : $tipotit;
	$otro_query  = $rowpar['otroquery'];
	$usauser = $rowpar['usuario'];
	$usacoment = $rowpar['comentarios'];
	$usalink = $rowpar['link'];
	$anidable = $rowpar['anidable'];
	$usavotos = $rowpar['votos'];
	$usalerta = $rowpar['usa_alertas'];
	$usagusta = $rowpar['usa_megusta'];
	if ($rowpar['campos_busqueda'] != '') {
		$buscampos = $rowpar['campos_busqueda'];
	} else {
		$buscampos = $tipotit;
		if ($usatexto == 1) {
			$buscampos .= ',texto';
		}
	}
}
