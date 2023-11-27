<?php if ($cangus > 0) { ?>
  <div id="megustan" class="backend_table">
    <h3>Me Gusta</h3>
    <table>
      <tr>
        <th>Usuario</th>
      </tr>
      <?php
      while ($row_gus = mysqli_fetch_array($resgus)) {
        echo '
      <tr>
      <td>' . txtcod($row_gus['nombre']) . ' ' . txtcod($row_gus['apellido']) . '</td>
      </tr>
      ';
      }
      ?>
    </table>
  </div>
<?php } ?>