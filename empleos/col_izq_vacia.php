<?PHP 
	$query_col_mail = fullQuery("SELECT valor FROM ".$_SESSION['prefijo']."config where parametro = 'email'") or die(mysqli_error());
	$row_col_mail   = mysqli_fetch_array($query_col_mail);
	$email = $row_col_mail['valor'];
?>

<div id="left-sidebar">
	<?PHP include ("col_alsea.php");?>
</div>