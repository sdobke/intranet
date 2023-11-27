<?PHP
$tipo   = 19;
$nombre = obtenerNombre($tipo);
$id = (isset($_POST['id'])) ? $_POST['id'] : 1;
$sql = "SELECT * FROM ".$_SESSION['prefijo'].$nombre." WHERE id = ".$id;
$res = fullQuery($sql);
$noticia = mysqli_fetch_array($res);
$titulo  = $noticia['titulo'];
		$sorteados = $_POST['sorteados'];
		$sql_part = "SELECT part.usuario AS partic, emple.nombre AS nom, emple.apellido AS ape, emple.interno AS interno,
		 emple.email AS mail, emple.id AS id 
			FROM ".$_SESSION['prefijo']."participantes AS part 
			INNER JOIN ".$_SESSION['prefijo']."empleados AS emple
			ON (part.usuario = emple.id) 
			WHERE ( tipoconcurso = ".$tipo." AND concurso = ".$id." ) 
			ORDER BY RAND()
			LIMIT ".$sorteados;
		//echo $sql_part;
		$res_part = fullQuery($sql_part);
		$num_part = mysqli_num_rows($res_part);
		$para = "";
		?>
        
		<h3>Sorteo: <?PHP echo $titulo;?></h3>
        <h3>Ganadores</h3>
        <div align="center">
            <table>
                <thead style="font-weight:bold">
                    <td width="200">
                        Usuario
                    </td>
                    <td width="100">
                        Interno
                    </td>
                    <td width="150">
                        E-mail
                    </td>
                </thead>
                <?PHP
				$conta_gan = 0;
				$ganadores = '0';
				while($row_part = mysqli_fetch_array($res_part)){
					$ganadores.= "-".$row_part['id'];
				?>
                <tbody>
                    <tr>
                        <td>
                            <?PHP echo $row_part['nom']." ".$row_part['ape'];?>
                        </td>
                        <td>
                            <?PHP echo $row_part['interno'];?>
                        </td>
                        <td>
                            <?PHP echo $row_part['mail'];?>
                        </td>
                    </tr>
                </tbody>
                <?PHP 
				if($conta_gan > 0){$para .= ',';}
				$para .= $row_part['nom']." ".$row_part['ape']." <".$row_part['mail'].">";
				$conta_gan ++;
				?>
				<?PHP } ?>
            </table>
        </div>
        <br />
        <div align="center">
        	<h3>Enviar e-mail al/los ganador/es</h3>
            <form action="<?PHP echo 'detalles.php?tipo='.$tipo.'&id='.$id; ?>" method="post">
            <input name="id" id="id" value="<?PHP echo $id;?>" type="hidden"/>
            <input name="opcion" id="opcion" value="M" type="hidden"/>
            <input name="tipo" id="tipo" value="<?PHP echo $tipo;?>" type="hidden"/>
            	<p>Motivo:
                  <label>
                    <input name="motivo" type="text" id="motivo" value="Ganaste el sorteo #sorteo# del sitio intranet Alsea" size="50" />
                  </label>
           	    <br /><br />
                Texto: 
				<textarea name="texto" cols="47" rows="8" id="texto">¡Felicidades #nombre#!
                    Ganaste el sorteo #sorteo#.
                    
                    En breve se van a contactar con vos para entregarte el premio.
				</textarea>
            	</p>
            	<p>Al usar #nombre# se va a reemplazar por el nombre del ganardor.<br />
           	    Al usar #sorteo# se va a reemplazar por el nombre del sorteo.</p>
            	<p>
                	<input type="hidden" name="ganadores" value="<?PHP echo $ganadores;?>" />
                    <input type="hidden" name="id" value="<?PHP echo $id;?>" />
            	    <input type="submit" name="boton" id="boton" value="enviar e-mail" />
           	    </p>
          </form>
            <?PHP
			// hacer envio de mail.
			$sql_mail = "SELECT valor FROM ".$_SESSION['prefijo']."config WHERE parametro = 'email'";
			$res_mail = fullQuery($sql_mail) or die($error_sql = mysqli_error());
			$row_mail = mysqli_fetch_array($res_mail);
			$mail_de  = $row_mail['valor'];
			$asunto   = "¡Felicidades! Ganaste un sorteo de Intranet Alsea.";
			$mime_boundary = "ALSEA-ARG-".md5(time());
			$headers =  "From: Intranet Alsea Argentina <".$mail_de.">\n";
			$headers .= "Reply-To: Intranet Alsea Argentina <".$mail_de.">\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			$mensaje = "";
			$mensaje .= "--$mime_boundary\n";
			$mensaje .= "Content-Type: text/html; charset=iso-8859-1\n";
			$mensaje .= "Content-Transfer-Encoding: 8bit\n\n";
			$mensaje .= "<html><body>";
			
			$mensaje .= "GANASTE!";
			
			$mensaje .= "</body></html>\n";
			$mensaje .= "--$mime_boundary--\n\n";
			?>
        </div>
  </div>
	<div style="clear:both;"></div>
</div>