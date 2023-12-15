<?php
$su_tit = ($_POST['titulo'] != '') ? txtdeco($_POST['titulo']) : 'Sin título';
$su_tem = $_POST['tema'] != 0 ? $_POST['tema'] : 'NULL'; 
$su_tem_otro = $_POST['otroTema'] != '' ? "'" . txtdeco($_POST['otroTema']) . "'" : 'NULL'; 
$su_tex = txtdeco($_POST['texto']);
$su_fec = date("Y-m-d");
$su_usr = (isset($_POST['anonimo'])) ? 0 : $_SESSION['usrfrontend'];

if ($su_tex != '') {
  $sql_si = "INSERT INTO intranet_sugerencias (titulo,usuario,fecha,sugerencia,tema,tema_otro,activo) VALUES ('".$su_tit."',".$su_usr.",'".$su_fec."','".$su_tex."',".$su_tem.",".$su_tem_otro.",1)";
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
