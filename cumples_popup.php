<?PHP
include "cnfg/config.php";
include "inc/funciones.php";
$tipo = 25;
agrega_acceso($tipo);
function edad($edad){
	list($anio,$mes,$dia) = explode("-",$edad);
	$anio_dif = date("Y") - $anio;
	$mes_dif = date("m") - $mes;
	$dia_dif = date("d") - $dia;
	if ($dia_dif < 0 || $mes_dif < 0)
	$anio_dif--;
	return $anio_dif;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/reset.css" rel="stylesheet" type="text/css" />
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/home.css" rel="stylesheet" type="text/css" />
<link href="css/slider.css" rel="stylesheet" type="text/css" />
<link href="css/fonts.css" rel="stylesheet" type="text/css" />
<link href="css/formatos.css" rel="stylesheet" type="text/css" />
<link href="css/css3-buttons.css" rel="stylesheet" type="text/css"  media="screen" /> 
<link href="css/nota.css" rel="stylesheet" type="text/css" />
<title><?PHP echo $cliente;?> Intranet | Home</title>
</head>
<body>
<?PHP 
	$dif  = cantidad('diferencia');
	$dif2 = cantidad('difepost');
	
	$fechahoy = date('Y-m-d');
$cump_sqlcomun = ", DATE_FORMAT(fechanac, '%M %d') AS bday, CONCAT(IF(MONTH(fechanac)=1 
		AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(fechanac, '-%m-%d')) AS fakebirthday 
		FROM ".$_SESSION['prefijo']."empleados AS emp
		INNER JOIN ".$_SESSION['prefijo']."empresas AS empr ON empr.id = emp.empresa
		
		WHERE CONCAT(IF(MONTH(fechanac)=1 AND MONTH(CURRENT_DATE())=12, YEAR(CURRENT_DATE())+1, YEAR(CURRENT_DATE())), DATE_FORMAT(fechanac, '-%m-%d')) 
		BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL " . $dif . " DAY) 
		
		AND fechanac != '1111-11-11'
		AND emp.del = 0
		AND area < 1000
		AND activo = 1
";
$cump_sqlcomun .= "ORDER BY fakebirthday, emp.nombre ";
$cump_sql = "SELECT emp.nombre, emp.fechanac " . $cump_sqlcomun;
$cump_result = fullQuery($cump_sql);
$contar = mysqli_num_rows($cump_result);
$cump_sql = "SELECT emp.id, emp.nombre, emp.apellido, emp.puesto AS puesto, emp.empresa, emp.area, emp.email, emp.interno, emp.fechanac" . $cump_sqlcomun;
$cump_result = fullQuery($cump_sql);
if ($contar >= 1) {
	$cump_result = fullQuery($cump_sql);
	?>
	<div class="bloque300 left" style="width:100%">
		<div class="hd_birth nettooffc b t20" style="width:100%">Cumplea&ntilde;os del mes</div>
		<?PHP
		while ($cump_emp = mysqli_fetch_array($cump_result)) {
			$file = "/cliente/fotos/" . $cump_emp['id']. ".jpg";
			$cump_link_foto = (file_exists($file)) ? $file : "/cliente/fotos/sinfoto.jpg";
			$cump_fecha = ($cump_emp['fechanac'] == date("Y-m-d")) ? "Hoy" : FechaDet($cump_emp['fechanac'],'diames');
			$cnom = explode(" ", $cump_emp['nombre']);
			$cnom = current($cnom);
			$cump_nombre = ucwords(strtolower(txtcod($cump_emp['apellido']) . ", " . $cnom));
			$cump_email = $cump_emp['email'];
			$cump_nomail = '';
			if ($cump_email != '') {
				$cump_nomail.= '<a href="mailto:' . $cump_emp['email'] . '" title="' . $cump_emp['email'] . '">';
			}
			$cump_nomail.= $cump_nombre;
			if ($cump_email != '') {
				$cump_nomail.= '</a>';
			}
			$cump_int = ($cump_emp['interno'] != '') ? '(Int. ' . $cump_emp['interno'] . ')' : '&nbsp';
			?>
			<div class="row_birth brd-b brd-r mr5 mb5" style="float:left; height:70px; background-color:#FFF">
				<a href="empleados.php?id=<?PHP echo $cump_emp['id']; ?>">
					<img src="<?PHP echo $cump_link_foto; ?>" alt="<?PHP echo $cump_nombre; ?>" width="50" height="50" align="left" class="mr10" />
				</a>
				<span class="tahoma t11"><?PHP echo $cump_fecha; ?></span>
				<div class="arial t15 b cb21a41"><?PHP echo $cump_nomail; ?></div>
				<?PHP 
				if($multiemp == 1){echo empresa($cump_emp['empresa']);}
				$userarea = obtenerDato('nombre','areas',$cump_emp['area']);
				if($multiemp == 1 && $userarea != ''){echo ' | ';}
				if($userarea != ''){echo $userarea;}
				?>
				<span class="tahoma t10"><?PHP echo $cump_int; ?></span>
			</div>
		<?PHP /*
			  <div class="mod-b-content">
			  <div class="cumple-foto">
			  <a href="empleados.php?id=<?PHP echo $cump_emp['id']; ?>">
			  <img src="/cliente/fotos/<?PHP echo $cump_link_foto; ?>.jpg" alt="<?PHP echo $cump_nombre; ?>" />
			  </a>
			  </div>
			  <div class="cumple-datos">
			  <div class="mod-b-fecha"><?PHP echo $cump_fecha; ?></div>
			  <div class="mod-b-texto-rojo"><?PHP echo $cump_nomail; ?></div>
			  <div class="mod-b-texto"><?PHP echo empresa($cump_emp['empresa']); ?> | <?PHP echo area($cump_emp['area']); ?></div>
			  <?PHP echo $cump_int; ?>
			  </div>
			  </div>
			  <?PHP */
		} ?>
		<?PHP
		if ($contar > 6) {
			if ($contar <= 8) {
				$wid = 614;
				$hei = 420;
			} else {
				$wid = 632; //508
				$hei = 404;
			} 
		}
		?>
	</div>
<?PHP } ?>
    </div>
</body>
</html>