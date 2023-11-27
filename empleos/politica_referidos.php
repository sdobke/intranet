<?PHP
include "../cnfg/config.php";
include "../inc/funciones.php";
require_once("../login_init.php");

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?PHP echo $cliente;?> Intranet | Home</title>
		<?PHP include ("head_empleos.php"); ?>
		
	</head>
	<body>
		<div id="middle">
			<div class="middle_inner">
				<div id="header" class="mb10">
					<div id="logo" >
						<a href="index.php"><img src="/cliente/img/logo.png" /></a>
					</div>
					<?php include_once '../login.php'; ?>					
				</div>
				<?php include_once '../menu.php'; ?>
				<div class="container mb10 pb15 brd-bs t30 pt15 nettooffc c999999"><a href="index.php">Gesti&oacute;n de Talento</a> | Empleos</div>
				<div class="container_inner">
					<div class="col_ppal right">
                       	  <div class="textos">
                              <div class="poli">
                                <p class="titulos">Programa de referidos</p>
								<br />
                                    <p class="tit">1. ASPECTOS GENERALES</p>
                                      
                                <p>&nbsp;</p>
                                    <p class="sub">1.1. Objetivo</p>
                                    <p>&nbsp;</p>
                                    <p>Proponer a los empleados de la Compa��a que identifiquen y presenten personas talentosas que tengan inter�s de trabajar en Alsea,  en virtud de hacer del reclutamiento y selecci�n una responsabilidad y una tarea compartida por todos. 
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p>La recomendaci�n por parte de los propios miembros de una organizaci�n es una de las mejores fuentes de reclutamiento, dado que quienes conocen el trabajo y el entorno de Alsea est�n en muy buenas condiciones de identificar qui�nes podr�an ajustarse mejor a la cultura y tipo de negocio.
                                      
                                    </p>
                                <p>&nbsp;</p>
                                    <p class="sub">1.2. Alcance</p>
                                    <p>&nbsp;</p>
                                    <p> El contenido de este documento es de observancia general para todos los integrantes de Burger King Argentina, Starbucks Coffee Argentina y Alsea  Servicios Compartidos. </p>
                                    <p>&nbsp;</p>
                                    <p>Se podr� referir para posiciones activas de las UEN y SCA, independientemente de �rea / UEN donde se desempe�a el referidor.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                <p class="sub">1.3. Publicaci�n de Avisos 
                                      
                                    </p>
                                    <p>&nbsp;	</p>
                                    <p>a)	Las vacantes incluidas dentro de la pol�tica de referidos estar�n detalladas en Intranet de la firma.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub">1.4. Alcance y Consideraciones
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub2">a)	Los candidatos referidos participar�n del proceso de selecci�n habitual y de acuerdo a las pol�ticas de Reclutamiento y Selecci�n de Alsea.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub2">b)	Todos los empleados podr�n referir candidatos para las posiciones vacantes publicadas en la secci�n del programa dentro de la Intranet.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub2">c)	Se podr� referir candidatos a�n cuando la persona referida no aplica a las b�squedas activas a trav�s desde un �cono desarrollado en  Intranet a fin de ser considerado en b�squedas futuras
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub2">d)	Ser� v�lido referir a amigos, conocidos y primos que cumplan con los requerimientos definidos en la descripci�n del aviso
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub2">e)	Cuando ingrese un nuevo colaborador a trav�s del presente programa, el referido recibir� una gratificaci�n individual a modo de agradecimiento por el compromiso en el proceso de selecci�n. Dicha gratificaci�n ser� percibida si y s�lo si el referido permanece en la compa��a por un plazo m�nimo de 3 meses. Estar�n habilitados para participar del sorteo,  todos los miembros de Alsea - UEN excepto:
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">i.	Directores de UEN / SCA
                                    </p>
                                    <p class="sub3">ii.	Gerentes de UEN / SCA
                                    </p>
                                    <p class="sub3">iii.	�reas de RRHH
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub2">f)	No se considerar�n candidatos v�lidos a:
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">i.	Padres, Hermanos, T�os
                                    </p>
                                    <p class="sub3">ii.	Candidatos transferidos previamente o que hayan postulado internamente.
                                    </p>
                                    <p class="sub3">iii.	Candidatos que est�n contratados temporalmente 
                                    </p>
                                    <p class="sub3">iv.	Candidatos que hayan sido contactados o reclutados por RRHH o ya est�n registrados en la base de datos con fecha anterior a la referencia.
                                    </p>
                                    <p class="sub3">v.	Ex empleados.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                <p class="sub2">g)	Validez de la referencia
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">i.	La referencia del candidato tendr� una validez en el sistema de 1 a�o a partir de la fecha de referencia
                                    </p>
                                    <p class="sub3">ii.	Pasado este lapso, la informaci�n del candidato quedar� en la base de datos a disposici�n para nuevas b�squedas.
                                    </p>
                                    <p class="sub3">iii.	En caso de que el candidato referido sea contratado como empleado luego de este per�odo, la referencia perder� validez.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="tit">2. PROCEDIMIENTO</p>
                                    <p>&nbsp;</p>
                                    <p>El programa de referidos se llevar� a cabo de acuerdo a los lineamientos establecidos a continuaci�n dentro del marco de la Pol�tica de Reclutamiento y Selecci�n.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">i.	Los empleados deber�n ingresar a Intranet para conocer las oportunidades laborales activas dentro del programa de referidos. Para referir candidatos que no apliquen a las b�squedas activas pudiendo ser potenciales candidatos a futuro, se habilitar� un �cono especial en Intranet
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">ii.	Si consideran recomendar a una persona, deber� enviar un mail a la casilla del programa indicando: posici�n / �rea para la cual lo est�n refiriendo y el Nombre y Apellido del referido Es importante tener en cuenta que los datos est�n completos a los fines de identificar correctamente referido y referidor.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">iii.	Enviada la recomendaci�n, llegar� a la casilla del referidor un e-mail de agradecimiento.
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">iv.	El referido participar� del proceso de selecci�n habitual de acuerdo con la pol�tica de Reclutamiento y Selecci�n
                                      
                                    </p>
                                    <p>&nbsp;</p>
                                    <p class="sub3">v.	Cuando ingreso un referido, el referidor percibir� la gratificaci�n si y s�lo si, el candidato referido cumple con un periodo m�nimo de permanencia de 3 meses y cumple con las condiciones detalladas en "Alcances y Consideraciones".
                                      
                                    </p>
                                </div>
                       	  </div>
                       
                         </div>
				<?php include 'col_izq.php'; ?>
			</div>
			<div class="clr"></div>
		</div>
		<?PHP include("footer.php");?>
	</body>
</html>