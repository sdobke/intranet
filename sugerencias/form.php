<?php
if(isset($_POST['suge_sent'])){
  include("sugerencias/post.php");
}
?>
<form name="sugerencias" method="post">
  <div class="row gy-2 gx-3 align-items-center">
    <div class="col-6">
      <label class="visually-hidden" for="titulo">Título</label>
      <input class="form-control" name="titulo" placeholder="Título">
    </div>
    <div class="col-4">
      <label class="visually-hidden" for="tema">Tema</label>
      <select class="form-select" name="tema" id="tema">
        <option value="0">Tema</option>
        <?php
        $sql_te = "SELECT * FROM intranet_sugerencias_temas WHERE del = 0 ORDER BY nombre";
        $res_te = fullQuery($sql_te);
        if (mysqli_num_rows($res_te) > 0) {
          while ($row_te = mysqli_fetch_array($res_te)) { ?>
            <option value="<?php echo $row_te['id']; ?>"><?php echo txtcod($row_te['nombre']); ?></option>
        <?php }
        } ?>
      </select>
    </div>
    <div class="col-2">
      <div class="form-check form-switch">
        <input class="form-check-input" name="anonimo" type="checkbox" id="anonimo">
        <label class="form-check-label" for="anonimo">Anónimo</label>
      </div>
    </div>
  </div>
  <div class="mt-3 row">
    <div class="col-12">
      <label for="exampleFormControlTextarea1" class="form-label">Comentarios</label>
      <textarea onkeyup="javascript:showIfNotEmpty(this.value,'suge_send')" class="form-control" name="texto" id="exampleFormControlTextarea1" rows="5"></textarea>
    </div>
    <div class="col-12">
      <input type="submit" class="btn btn-primary" name="suge_sent" id="suge_send" style="display:none" value="Enviar" />
    </div>
  </div>
</form>