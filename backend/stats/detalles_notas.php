<?php
$getvars = '?sent=1';
foreach($_GET as $getvar => $getval){
  $getvars.='&'.$getvar.'='.$getval;
}
if(!isset($_GET['stat_sort']) || ( isset($_GET['stat_sort']) && $_GET['stat_sort'] == 'fecha') ){
  $snsort = 'ian.fecha';
}else{
  $snsort = 'ie.apellido';
}
$sqlsn = "SELECT ian.fecha, concat(ie.apellido,', ',ie.nombre) as nomape
  FROM intranet_accesos_notas ian 
  INNER JOIN intranet_empleados ie ON ie.id = ian.usuario
  WHERE ian.item = ".$id." AND ian.tipo = ".$tipo." ORDER BY ".$snsort;
$ressn = fullQuery($sqlsn);
if(mysqli_num_rows($ressn) > 0){
  echo '<div id="stats">
  <h3>Accesos</h3>';
  echo '<table>';
  echo '<tr><th><a href="'.$getvars.'&stat_sort=fecha">Fecha</a></th><th><a href="'.$getvars.'&stat_sort=usuario">Usuario</a></th></tr>';
  while($rowsn = mysqli_fetch_array($ressn)){
    echo '<tr>
      <td>'.FechaDet($rowsn['fecha'],'corto').'</td>
      <td>'.txtcod($rowsn['nomape']).'</td>
      </tr>';
  }
  echo '</table>';
  echo '</div>';
}