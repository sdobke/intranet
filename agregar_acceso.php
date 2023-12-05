<?PHP
include "cnfg/config.php";
// include "inc/sechk.php";
include "inc/funciones.php";
if(isset($_POST['id']) && isset($_POST['tipo'])) {
  agrega_acceso_nota($_POST['tipo'],$_POST['id']);
}

$id = $_POST['id']; 

$sql_existe = "SELECT count(*) as existe FROM intranet_docs_emp WHERE doc = " . $id . " AND emp = " . $_SESSION['usrfrontend'];
$res_existe = fullQuery($sql_existe);
print("ewntrooo");
echo $_SESSION['usrfrontend'];
echo print_r($res_existe);
print "nuermo $id";
if ($res_existe->num_rows > 0) {
  $fila = $res_existe->fetch_assoc();
  $existe = $fila['existe'];
  if($existe == 0) {
    print("entro a insert");
    $sqlcl = "INSERT INTO intranet_docs_emp	(doc,emp,status) VALUES (" . $id . "," . $_SESSION['usrfrontend'] . ", 0)";
	  $rescl = fullQuery($sqlcl);
  }
}

