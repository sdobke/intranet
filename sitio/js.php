<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script src="/assets/js/scripts.js"></script>



<script>

	$(document).ready(function() {

		$("#tipodato").change(function() {

			var action = 'seccion.php';

			var nro = $("#tipodato").val();

			if(nro == 4 || nro == 1){var action = 'areas.php';}

			//if(nro == 1){$("#busqueda").attr("name", "area");}

			if(nro == 3){var action = 'docs.php';}

			var tablas = [];

			<?php

      $sqltid = "SELECT id, detalle, buscador_texto bt FROM intranet_tablas WHERE buscador_texto != '' AND menu = 1";

      $restid = fullQuery($sqltid);

      while($rowtid = mysqli_fetch_array($restid)){

        echo "tablas[".$rowtid['id']."] = '".$rowtid['bt']."';";  

      }

      ?>

			$("#busqueda").attr("placeholder", "Ingrese "+tablas[nro]);

			$("#searchform").attr('action', action);

			//alert("Handler for .change() called.");

		});

	});

</script>