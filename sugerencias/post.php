<?php
$su_tit = ($_POST['titulo'] != '') ? txtdeco($_POST['titulo']) : 'Sin título';
$su_tem = $_POST['tema'];
$su_tex = txtdeco($_POST['texto']);
$su_fec = date("Y-m-d");
$su_usr = (isset($_POST['anonimo'])) ? 0 : $_SESSION['usrfrontend'];
if ($su_tex != '') {
  $sql_si = "INSERT INTO intranet_sugerencias (titulo,usuario,fecha,sugerencia,tema) VALUES ('".$su_tit."',".$su_usr.",'".$su_fec."','".$su_tex."',".$su_tem.")";
  $res_si = fullQuery($sql_si);
?>
  <div class="alert alert-success" role="alert">
    Sugerencia enviada. ¡Muchas gracias!
  </div>
<?php } else { ?>
  <div class="alert alert-danger" role="alert">
    No podemos guardar una sugerencia sin comentarios. Por favor complete el campo "comentarios" y vuelva a intentarlo. ¡Gracias!
  </div>
<?php } ?>