<?PHP
$data = explode('*',$data);
if ($label != '') $label = explode('*',$label);

$colores = array('00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a','00a0db', 'ea4300', 'eade00','4cbc44', 'eaa200', 'b51cea','a66f10','2510a6','056b1b','9b2a2a'); // colores

$chartBar ='<table class="graficas" width="600" cellspacing="0" cellpadding="2">';

// primero calculo la suma de todos los resultados
$maximo = 0;
$masgde = 0;
foreach ( $data as $ElemArray ) { 
	$maximo += $ElemArray;
	if($ElemArray > $masgde){$masgde = $ElemArray;}
}

if($maximo > 0){

	foreach ( $data as $posi => $dato ) {
		$porcentaje = round((( $dato / $maximo ) * 100),0);
		$tamanio_barra = $dato*350/$masgde;
		
		$chartBar .= '<tr>
		';
		$chartBar .= '<td width="150" style="font-size:10px;"><strong>'.( $label[$posi] ) .'</strong></td>
			';
		$chartBar .= '<td width="5%" style="font-size:10px;"><strong>'.( $dato ) .'</strong></td>
		';
		$chartBar .= '<td width="10%">'.$porcentaje.'%</td>
		';
		$chartBar .= '<td>
		';
		$chartBar .= '<table width="'.$tamanio_barra.'" bgcolor="#'.$colores[$posi].'" height="20px">
		';
		$chartBar .= '<tr><td></td></tr>
		';
		$chartBar .= '</table>
		';
		$chartBar .= '</td>
		';
		$chartBar .= '</tr
		>';
	}
}

$chartBar .= '</table>';
?>