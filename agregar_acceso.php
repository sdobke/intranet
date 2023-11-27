<?PHP
include "cnfg/config.php";
include "inc/sechk.php";
include "inc/funciones.php";
if(isset($_POST['id']) && isset($_POST['tipo'])){
  agrega_acceso_nota($_POST['tipo'],$_POST['id']);
}