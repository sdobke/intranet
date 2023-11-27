<?php
$sql_lec = "SELECT emp.apellido, emp.nombre FROM intranet_docs_emp ide LEFT JOIN intranet_empleados emp ON emp.id = ide.emp WHERE ide.doc = ".$id;
$res_lec= fullQuery($sql_lec);
if(mysqli_num_rows($res_lec) > 0){
  echo '<div id="stats"><h3>Confirmación de lectura</h3>';
  while($row_lec = mysqli_fetch_assoc($res_lec)){
    echo '<br>'.$row_lec['apellido'].', '.$row_lec['nombre'];
  }
  echo '</div>';
}
