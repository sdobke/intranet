<?php

if (isset($id) && isset($tipo)) {

	/*

	 * Lista los comentarios

	 */

	$sql = "SELECT *

			FROM " . $_SESSION['prefijo'] . "comentarios C, " . $_SESSION['prefijo'] . "empleados E

			WHERE C.item =" . $id . "

			AND C.tipo ={$tipo}

			AND C.usuario_id = E.id

			AND C.activo = 1 AND C.del = 0

			ORDER BY fecha DESC

			";



	$res = fullQuery($sql);

	$con = mysqli_num_rows($res);

	if ($con > 0) {

?>

		<div class="comentarios">

			<h3 class="mb-4">Comentarios</h3>

			<?php

			if (mysqli_num_rows($res) > 0) {

				while ($comentario = mysqli_fetch_object($res)) {

			?>

					<div class="comentario">

						<p>
							<span class="fecha"><strong><?php echo txtcod($comentario->apellido . ', ' . $comentario->nombre); ?></strong>  | <?PHP echo fechaDet($comentario->fecha); ?></span>

							

							<br>

							<?php echo txtcod(nl2br($comentario->comentario)); ?>

						</p>

					</div>

			<?php

				}

			}

			?>

		</div>

<?php

	}

}

?>