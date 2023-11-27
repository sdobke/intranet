<?PHP
include "cnfg/config.php";
include "inc/funciones.php";

function getChildren($parent) {
  $children = '';
  $query = "SELECT * FROM intranet_areas WHERE parent = ".$parent;
  $result = fullQuery($query);
  if(mysqli_num_rows($result) > 0){
    $children = array();
    $i = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $children[$i] = array();
        $children[$i]['name'] = $row['nombre'];
        $children[$i]['children'] = getChildren($row['id']);
    $i++;
    }
  }
return $children;
}

$finalResult = getChildren(0);
echo '<pre>';
print_r($finalResult);
echo '</pre>';