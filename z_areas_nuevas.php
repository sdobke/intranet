<?php
include "cnfg/config.php";
include "inc/funciones.php";

// Primero al nuevo formato de asociación de usuarios con sus áreas


$sql = "SELECT id,area FROM intranet_empleados WHERE del = 0";
$res = fullQuery($sql);
while($row = mysqli_fetch_array($res)){
  $idemp = $row['id'];
  $areas = explode(',',$row['area']);
  foreach ($areas as $area){
    $sql2 = "INSERT INTO intranet_empleados_areas (empleado,area) VALUES (".$idemp.",".$area.")";
    fullQuery($sql2);
  }
}


//Reorganización de áreas

/*
$areas = "'ADMINISTRACIÓN','COMERCIAL','DEPÓSITO','LIMPIEZA'";
$sql = "SELECT * FROM intranet_areas WHERE nombre IN (" . utf8_decode($areas) . ") AND del = 0";
echo $sql;
$res = fullQuery($sql);
$con = mysqli_num_rows($res);
echo '<br>Cantidad: '.$con;
if ($con > 0) {
  while ($row = mysqli_fetch_array($res)) {
    $areaid = $row['id'];
    $areanom = $row['nombre'];
    echo '<br>Area: ' . $areaid.' Nombre: '.$areanom;
    
  $sql3 = "SELECT id FROM intranet_areas WHERE nombre = 'FULL ".$areanom."'";
  echo '<br>'.$sql3;
  $res3 = fullQuery($sql3);
  $row3 = mysqli_fetch_assoc($res3);
  $nuearea = $row3['id'];
  
    $sql2 = "SELECT ie.id
            FROM intranet_empleados ie 
            INNER JOIN intranet_empleados_areas iea ON iea.empleado = ie.id
            INNER JOIN intranet_areas ia ON ia.id = iea.area
            WHERE ia.id = " . $areaid;
    echo '<br>Buscamos usuarios en esas áreas: ' . $sql2;
    $res2 = fullQuery($sql2);
    $con2 = mysqli_num_rows($res2);
    if ($con2 > 0) {
      while ($row2 = mysqli_fetch_array($res2)) {
        $sql4 = "UPDATE intranet_empleados_areas SET area = " . $nuearea . " WHERE area = " . $areaid . " AND empleado = " . $row2['id'];
        echo '<br>' . $sql4;
        fullQuery($sql4);
      }
    }
  }
}
*/


