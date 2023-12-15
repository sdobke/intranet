<?php
if(isset($_POST['suge_sent'])){
  include("sugerencias/post.php");
}
?>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<form name="sugerencias" method="post">
  <div class="row gy-2 gx-3 align-items-center">
    <div class="col-6">
      <label class="visually-hidden" for="titulo">Título</label>
      <input class="form-control" name="titulo" placeholder="Título">
    </div>

    <div class="col-4" id="otroTemaInput" style="display:none">
        <label class="visually-hidden" for="otroTema">Otro Tema</label>
        <input class="form-control" name="otroTema" placeholder="Otro Tema">
    </div>
    <div class="col-2" id="volverSelectBtn" style="display:none">
        <button type="button" class="btn btn-secondary"><span class="bi bi-x"></span></button>
    </div>

    <div class="col-4" id="selectTema">
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

        <option value="otro">otro</option>
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
<script>  
$(document).ready(function(){
    $('#tema').change(function(){
        if($(this).val() === 'otro'){
            $('#otroTemaInput, #volverSelectBtn').show();
            $('#selectTema').hide();
            $('#suge_send').hide();
        } else {
            $('#otroTemaInput, #volverSelectBtn').hide();
            $('#suge_send').show();
        }
    });

    $('#volverSelectBtn').click(function(){
        $('#tema').val('0');
        $('#otroTemaInput, #volverSelectBtn').hide();
        $('#selectTema').show();
        $('#suge_send').show();
    });
});
</script>