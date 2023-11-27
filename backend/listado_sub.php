<?PHP
include_once("../cnfg/config.php");
include_once("../inc/funciones.php");
include_once("../clases/clase_error.php");
include_once("inc/sechk.php");
$backend = 1;
if(isset($_POST['area']) && $_POST['area'] > 0){
  $sql = "SELECT * FROM intranet_areas WHERE del = 0 AND parent = ".$_POST['area'];
  //echo $sql;
  $res = fullQuery($sql);
  $con = mysqli_num_rows($res);
  if($con > 0){
    echo '<select name = "bus_areas">';
      echo '<option value="'.$_POST['area'].'">Seleccione una subárea</option>';
      while($row = mysqli_fetch_array($res)){
        echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>
        ';
      }
    echo '</select>';
  }
}
