<?PHP 
$contador_tipo = 0;
while($contador_tipo <= 1){
	$campo_comp = ($contador_tipo == 0) ? 'fechanac' : 'fechaing';
	$confircampo = ($contador_tipo == 0) ? 'confirmado' : 'confirmani';
	
	$query2 = "SELECT id, nombre , apellido , fechanac , email , ".$confircampo." , fechaing,
					DATEDIFF( CONCAT_WS( '-', YEAR(SYSDATE()), MONTH(".$campo_comp."), DAY(".$campo_comp.") ), SYSDATE() ) AS diferencia 
					FROM intranet_empleados AS emp
					WHERE CONCAT(IF(MONTH(".$campo_comp.")=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(".$campo_comp.", '-%m-%d')) BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY) 
							AND ".$campo_comp." != '1111-11-11'
							AND empresa < 3 ";
                                    
	//$query2.="			AND ".$confircampo." = 0";
                                    
	$query2.="				AND activo = 1
							AND area < 1000
							ORDER BY ".$confircampo.", diferencia";
	//echo '<br>'.$query2;
	$res_ver2 = fullQuery($query2);
	while($row_ver2 = mysqli_fetch_array($res_ver2)){
		$idp      = $row_ver2['id'];
		$apro     = $_POST['dato'.$campo_comp.'_'.$idp];
		$email    = $_POST['email_'.$campo_comp.'_'.$idp];
		
		//echo '<br />apro: '.$apro.' | '.$row_ver2[$confircampo];
		if($email != '' && ($row_ver2['email'] != $email || $apro != $row_ver2[$confircampo]) ){
			//echo '<br>"'.$row_ver2['email'].'": "'.$email.'"';
			$query3  = "UPDATE intranet_empleados SET email = '".$email."', ".$confircampo." = '".$apro."' WHERE id = ".$idp;
			//echo '<br />Query: '.$query3;
			$result3 = fullQuery($query3);
		}
	}
	$contador_tipo++;
}
