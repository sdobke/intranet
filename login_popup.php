<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?PHP include ("head.php");?>
<title><?PHP echo $cliente;?> Intranet | Home</title>
<script type="text/javascript">
	$('[placeholder]').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		}
		}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
		}
		}).blur().parents('form').submit(function() {
		$(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		input.val('');
		}
		})
	});
	function submitForm(linkdest) {
		document.getElementById("login_usr").submit();
		//document.login_usr.submit();
		//opener.location.reload();
		window.opener.location.href=linkdest;
		window.opener.focus();
		//this.close();
	}
	function anular(e) {
          tecla = (document.all) ? e.keyCode : e.which;
          return (tecla != 13);
     }
</script>
</head>
<body onkeypress="return anular(event)">
<?PHP
$formloc = (isset($_GET['formloc'])) ? $_GET['formloc'] : 'index.php';
if(strpos($formloc, "ANDVAR")!== false){
	$formloc_prt = explode("ANDVAR",$formloc);
	$formloc_var = end($formloc_prt);
	$formloc = prev($formloc_prt).'&'.$formloc_var;
}
//echo "usuario: ".$_SESSION['usrfrontend'];
?>
<div id="login_head" style="margin: 50px auto 0; width: 380px" align="center">
		<form action="<?PHP echo $formloc;?>" method="post" id="login_usr" name="login_usr">
			<table width="370" border="0" cellspacing="0" cellpadding="0">				
				<tr>
					<td><input type="submit" name="submita" value="" style="visibility: hidden" /></td>
					<td width="43%">
						<input type="text" id="usuario_red" name="usuario_red" value="" style="width:135px" placeholder="Usuario" /></td>
					<td width="43%"><input type="password" name="password" id="password" style="width:135px" placeholder="Password" /></td>
					<td width="14%" align="right">
						
						<a href="#" class="button" onclick="javascript:submitForm('<?PHP echo $formloc;?>')">
							<span class="icon icon64"></span>
						</a>
					</td>
				</tr>
				
			</table>
		</form>
	</div>
</body>
</html>