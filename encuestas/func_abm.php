<?PHP
// ------------- ALTA CAMPO ------------- //
		if ($func == 1){ //ALTA
			$query_id  = "SELECT id FROM ".$_SESSION['prefijo']."encuestas_campos WHERE encuesta = ".$_SESSION['actiform']." ORDER BY id DESC LIMIT 1";
			$resul_id  = fullQuery($query_id);
			$datos_id  = mysqli_fetch_array($resul_id);
			$campo_id  = $datos_id['id']+1;
			$error     = 'ok';
			$nombre    = $_POST['nombre'];
			$querychk  = "SELECT nombre FROM ".$_SESSION['prefijo']."encuestas_campos WHERE nombre = '".$nombre."' AND encuesta = ".$_SESSION['actiform'];
			$resulchk  = fullQuery($querychk);
			$contachk  = mysqli_num_rows($resulchk);
			if ($contachk > 0){
				$error = "Ya existe un campo con el nombre <strong>".$nombre."</strong>";
			}
			$tipo      = $_POST['tipo'];
			$tipodb    = obtenerDato('nombre', 'encuestas_tipos',$tipo);
			
			if(isset($_POST['orden']) && $_POST['orden'] != ''){
				$orden  = $_POST['orden'];
			}else{
				// DEFINE EL ORDEN DEL CAMPO
				$query_rec_campos = "SELECT orden FROM ".$_SESSION['prefijo']."encuestas_campos WHERE encuesta = ".$_SESSION['actiform']." ORDER BY orden DESC LIMIT 1";
				$resul_rec_campos = fullQuery($query_rec_campos);
				$row_campos       = mysqli_fetch_array($resul_rec_campos);
				$orden_campo      = $row_campos['orden']+1;
				// FIN DEFINE EL ORDEN DEL CAMPO
			}
			if($error == 'ok'){
				$opciones = 0;
				$totopcio = 0; // variable que guarda la cantidad de opciones que contiene
				if($tipo == 4){
					if(isset($_POST['opcion1'])){
						$contador = 1;
						while(isset($_POST['opcion'.$contador])&& $_POST['opcion'.$contador] != ''){
							if($_POST['opcion'.$contador] != 'anula_opcion'){
								$opciones = 1;
								// DEFINE EL ORDEN DE LA OPCION
								$query_rec_opc = "SELECT orden FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = ".$campo_id." ORDER BY orden DESC LIMIT 1";
								$resul_rec_opc = fullQuery($query_rec_opc);
								$row_opc       = mysqli_fetch_array($resul_rec_opc);
								$orden_opc     = $row_opc['orden']+1;
								// FIN DEFINE EL ORDEN DE LA OPCION
								
								$opcion_post = decTx($_POST['opcion'.$contador]);
								// CALCULO EL ULTIMO ID
								$query_cuent_opc = "SELECT id FROM ".$_SESSION['prefijo']."encuestas_opciones ORDER BY id DESC LIMIT 1";
								$resul_cuent_opc = fullQuery($query_cuent_opc);
								$row_cuent_opc = mysqli_fetch_array($resul_cuent_opc);
								$canti_cuent_opc = $row_cuent_opc['id']+1;
								
								$query_alta_opc = ("INSERT into ".$_SESSION['prefijo']."encuestas_opciones (id, opcion, campo, orden) VALUES ($canti_cuent_opc, '$opcion_post', $campo_id ,$orden_opc)");
								$resul_alta_opc = fullQuery($query_alta_opc);
								$totopcio = $canti_cuent_opc;
							}
							$contador++;
						}
					}
					$cantidad_opciones = 250;
					if ($totopcio > 0){
						$cantidad_opciones = 3;
					}
					$tipodb  = "VARCHAR ( ".$cantidad_opciones." )";
				}
				if ($tipo == 6){
					$opciones = 1;
				}
				$nombre = decTx($nombre);
				$query   = "INSERT INTO ".$_SESSION['prefijo']."encuestas_campos (id,nombre,tipo,opciones,orden,encuesta) VALUES ({$campo_id},'{$nombre}', {$tipo}, {$opciones}, {$orden_campo}, {$_SESSION['actiform']})";
				$result  = fullQuery($query);
				$query2  = "ALTER TABLE ".$_SESSION['prefijo']."encuestas_listado ADD COLUMN `{$campo_id}` {$tipodb} NULL";
				$result2 = fullQuery($query2);
			}
			 	if ($error != 'ok'){
			  	echo "ERROR: ".$error;
			}else{
				echo "Datos ingresados correctamente.";
			   }
		}
		
// ------------- MODIFICAR ------------- //

		if ($func == 2){ //MODIFICAR
			$mod_nombres      = $_POST['mod_nombres'];
			$mod_tipos        = $_POST['mod_tipos'];
			$mod_opciones     = $_POST['mod_opciones'];
			$mod_opciones_new = $_POST['mod_opciones_new'];
			
			if($mod_nombres != '0') { // VERIFICA SI HAY CAMBIOS DE NOMBRES
				$id_nombres_mod = explode("-", $mod_nombres);
				foreach ($id_nombres_mod as $key => $value) {
					$nuevo_nom_id = $value;
					if ($nuevo_nom_id > 0){
						// levanta el viejo nombre
						$query_mod_nom   = "SELECT nombre,tipo,opciones FROM ".$_SESSION['prefijo']."encuestas_campos WHERE id = {$nuevo_nom_id}";
						$resul_mod_nom   = fullQuery($query_mod_nom);
						$row_mod_nom     = mysqli_fetch_array($resul_mod_nom);
						$viejo_mod_nom   = codTx($row_mod_nom['nombre']);
						$viejo_mod_tip   = $row_mod_nom['tipo'];
						$viejo_mod_opc   = $row_mod_nom['opciones'];
						
						// toma el nuevo nombre del post
						$nuevo_nom_tx	 = decTx($_POST['nombre_'.$nuevo_nom_id]);
						
						// reemplaza el nombre en el campo
						$query_mod_nom   = "UPDATE ".$_SESSION['prefijo']."encuestas_campos SET nombre = '{$nuevo_nom_tx}' WHERE id = {$nuevo_nom_id}";
						$resul_mod_nom   = fullQuery($query_mod_nom);
						
						// ¿tiene opciones? Si tiene, cuenta cuántas
						if ($viejo_mod_opc == 1){
							$nuevo_mod_tip   = 'VARCHAR ( 3 )';
						
						}else{// Si no tiene opciones levanta el tipo y lo guarda en la variable $nuevo_mod_tip.
							$nuevo_mod_tip = obtenerDato('nombre', 'encuestas_tipos',$viejo_mod_tip);
						}
					}
				}
			}
			
			if($mod_tipos != '0') { // VERIFICA SI HAY CAMBIOS DE TIPOS
				$id_tipos_mod = explode("-", $mod_tipos);
				foreach ($id_tipos_mod as $key => $value) {
					$mod_campo_id = $value;
					if ($mod_campo_id > 0){
						$nuevo_tipo_id	 = $_POST['tipo_'.$mod_campo_id];
						// levanta los datos del campo
						$query_ver_tip   = "SELECT nombre,tipo FROM ".$_SESSION['prefijo']."encuestas_campos WHERE id = {$mod_campo_id}";
						$resul_ver_tip   = fullQuery($query_ver_tip);
						$row_ver_tip     = mysqli_fetch_array($resul_ver_tip);
						$viejo_mod_nom   = $row_ver_tip['nombre'];
						//$viejo_mod_nom   = aplanaTexto($viejo_mod_nom);
						$viejo_mod_tip   = $row_ver_tip['tipo'];
						// reemplaza el tipo en el campo
						$query_mod_tip   = "UPDATE ".$_SESSION['prefijo']."encuestas_campos SET tipo = '{$nuevo_tipo_id}' WHERE id = {$mod_campo_id}";
						$resul_mod_tip   = fullQuery($query_mod_tip);
					}
				}
			}
			
			if($mod_opciones != '0') { // VERIFICA SI HAY CAMBIOS DE OPCIONES
				$mod_opcion_post = explode("-", $mod_opciones);
				foreach ($mod_opcion_post as $key => $value) {
					if ($value > 0){ // RECORRE CADA OPCION
						$array_opc = explode(",", $value);
						$campo_id     = $array_opc[0];
						$opc_a_mod_id = $array_opc[1];
						$mantiene     = $array_opc[2];
			
						// CAMBIAR LA OPCION POR EL NUEVO NOMBRE
						$nueva_opcion_nombre = decTx($_POST['c'.$campo_id.'op'.$opc_a_mod_id]);
						$query_mod_opc = "UPDATE ".$_SESSION['prefijo']."encuestas_opciones SET opcion = '{$nueva_opcion_nombre}' WHERE id = {$opc_a_mod_id}";
						//echo "<br/><br/>query cambia nombre opcion: ".$query_mod_opc;
						$resul_mod_opc = fullQuery($query_mod_opc);
						
						// PREGUNTAR SI QUIERE QUE MANTENGA LOS VALORES QUE TIENEN SELECCIONADA ESTA OPCION CON EL NUEVO NOMBRE O SI LOS ELIMINA PONIENDO 0
						if($mantiene == 1){ //Si no mantiene los valores pone a 0 todos los reclamos que tengan esa opción seleccionada
							$query_no_mantiene = "UPDATE ".$_SESSION['prefijo']."encuestas_listado SET `{$campo_id}` = 0 WHERE `{$campo_id}` = {$opc_a_mod_id}";
							//echo "<br/><br/>query cambia reclamos a 0: ".$query_no_mantiene;
							$resul_no_mantiene = fullQuery($query_no_mantiene);
						}
					}
				}
			}
			if($mod_opciones_new != '0') { // VERIFICA SI SE AGREGARON OPCIONES
				$new_opciones = explode("-", $mod_opciones_new);
				$new_opciones = array_unique($new_opciones);
				foreach ($new_opciones as $key => $value){
					$campo_id     = $value;
					$query_agregar_opcion = "UPDATE ".$_SESSION['prefijo']."encuestas_campos SET opciones = 1 WHERE id = {$campo_id}";// Cambia el campo opcion a 1 en el registro de campos
					$resul_agregar_opcion = fullQuery($query_agregar_opcion);
					$cont_post_op = 0;
					while($cont_post_op <= 20){
						if(isset($_POST['newopcion_'.$campo_id.'_'.$cont_post_op])&& $_POST['newopcion_'.$campo_id.'_'.$cont_post_op]!= ''){
							$post_opcion = decTx($_POST['newopcion_'.$campo_id.'_'.$cont_post_op]);
							// establece el orden de la opcion
							$query_rec_opc = "SELECT orden FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = {$campo_id} ORDER BY orden DESC LIMIT 1";
							$resul_rec_opc = fullQuery($query_rec_opc);
							$row_opc       = mysqli_fetch_array($resul_rec_opc);
							$opcion_orden  = $row_opc['orden']+1;
							
							$query_agrega_opc = "INSERT INTO ".$_SESSION['prefijo']."encuestas_opciones (opcion, campo, orden) VALUES ('{$post_opcion}', {$campo_id}, {$opcion_orden})";
							$resul_agrega_opc = fullQuery($query_agrega_opc);
						}
					$cont_post_op++;
					}
				}
			}
		
		}

// ------------- BAJA CAMPO ------------- //

		if ($func == 3){ //BAJA
			$id          = $_GET['id'];
			//$campo_baja  = $_GET['campo'];
			//$campo_baja  = aplanaTexto($campo_baja);
			// Borra el campo de la tabla campos
			$sql_cb = "SELECT nombre FROM ".$_SESSION['prefijo']."encuestas_campos WHERE id={$id}";
			$res_cb = fullQuery($sql_cb);
			if(mysqli_num_rows($res_cb) == 1){
				$row_cb = mysqli_fetch_assoc($res_cb);
				$nomcam = aplanaTexto(codTx($row_cb['nombre']));
				$query_baja  = "DELETE FROM ".$_SESSION['prefijo']."encuestas_campos WHERE id={$id}";
				$result_baja = fullQuery($query_baja);
				// Borra el campo de la tabla listado
				$query_baja  = "ALTER TABLE ".$_SESSION['prefijo']."encuestas_listado DROP COLUMN `{$id}`";
				$result_baja = fullQuery($query_baja);
				//echo $query_baja;
				// Borra las opciones relativas a ese campo
				$query_baja  = "DELETE FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = {$id}";
				$result_baja = fullQuery($query_baja);
				if ($error == 'ok'){
					$msg = "Campo borrado exitosamente.";
				}else{
					$msg = "Error ". $error;
				}
			}else{
				$msg = "Error: no existe el campo a eliminar.";
			}
		}
		
// ------------- BAJA OPCION ------------- //


		if ($func == 4){ //BAJA DE OPCION
			$id_opc_del        = $_GET['id'];
			$campo_opc         = $_GET['campo_id'];
			$query_opc_del     = "SELECT nombre FROM ".$_SESSION['prefijo']."encuestas_campos WHERE id = {$campo_opc}";
			$resul_opc_del     = fullQuery($query_opc_del);
			$row_opc_del       = mysqli_fetch_array($resul_opc_del);
			$campo_opc_nom     = aplanaTexto(codTx($row_opc_del['nombre']));
			$query_opc_del     = "DELETE FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE id = {$id_opc_del}";
			$result_opc_del    = fullQuery($query_opc_del);
			// También se cambian los valores de los reclamos que tengan esa opción como seleccionada por null
			$query_baja_opc  = "UPDATE ".$_SESSION['prefijo']."encuestas_listado SET `{$campo_opc}` = '' WHERE `{$campo_opc}` = '{$id_opc_del}'";
			$result_baja_opc = fullQuery($query_baja_opc);
			if ($error == 'ok'){
				$msg = "Opci&oacute;n borrada exitosamente.";
			// SI ERA LA UNICA OPCION, CAMBIA OPCION A 0 EN EL CAMPO	
				$query_chk = "SELECT id FROM ".$_SESSION['prefijo']."encuestas_opciones WHERE campo = {$campo_opc}";
				$resul_chk = fullQuery($query_chk);
				$canti_chk = mysqli_num_rows($resul_chk);
				if($canti_chk==0){
					$query_chk = "UPDATE ".$_SESSION['prefijo']."encuestas_campos SET opciones = 0 WHERE id = {$campo_opc}";
					$resul_chk = fullQuery($query_chk);
				}
			}else{
				$msg = "Error: ". $error;
			}
		}

// ------------- BAJAR O SUBIR ORDEN DE OPCION ------------- //

		if ($func >= 5 && $func <= 6){ //SUBIR EL ORDEN DE UNA OPCION
			moverCampo("".$_SESSION['prefijo']."encuestas_opciones",$func);
		}

// ------------- BAJAR O SUBIR ORDEN DE CAMPO ------------- //

		if ($func >= 7 && $func <= 8){ //SUBIR EL ORDEN DE UN CAMPO
			moverCampo("".$_SESSION['prefijo']."encuestas_campos",$func);
		}
?>