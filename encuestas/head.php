<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" charset="utf-8" />-->
<script type="text/javascript" src="../inc/jquery.js"></script>
<script type="text/javascript" src="../inc/custom-form-elements.js"></script>
<script type="text/javascript" src="calendarDateInput.js"></script>
<script type="text/javascript">
function cambiado(obj,tipo){
	if(tipo == 1){
		var variable = 'usuarios';
	}else{
		var variable = 'passwords'
	}
	document.getElementById(variable).value = document.getElementById(variable).value+'-'+obj;
}
function mostrarOcultar(){
	document.getElementById('vergerentes').style.display = 'none';
}
function confirmDelete(delUrl) {
	if (confirm("¿Está seguro/a que quiere eliminar ese registro?")) {
		document.location = delUrl;
	}
}
function refreshCont(text, id){
	$("#contResp" + id).empty();
	$("#contResp" + id).append('<span>' + text + '</span>');
}


function ajErr(a, b, c){alert(a.status);alert(c);}	
</script>


<link href="../css/reset.css" rel="stylesheet" type="text/css" />
<link href="../css/general.css" rel="stylesheet" type="text/css" />
<link href="../css/home.css" rel="stylesheet" type="text/css" />
<link href="../css/nota.css" rel="stylesheet" type="text/css" />
<link href="../css/campos.css" rel="stylesheet" type="text/css" />
<link href="../css/minisitios.css" rel="stylesheet" type="text/css" />
<link href="../css/fonts.css" rel="stylesheet" type="text/css" />
<link href="../css/formatos.css" rel="stylesheet" type="text/css" />
<link href="../css/css3-buttons.css" rel="stylesheet"  type="text/css"  media="screen">
<link href="../css/secciones.css" rel="stylesheet"  type="text/css"  media="screen">
	<!--<script type="text/javascript" src="../js/jquery.js"></script>-->
	<script type="text/javascript" src="../js/jquery.tools.min.js"></script>
	<!-- <script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
		  <script type="text/javascript">
		  $(document).ready(function() 
			  { 
				  $("#table").tablesorter({ 
			  widgets: ['zebra'] 
			  }); 
			  } 
		  ); 
		  </script> -->
	<script type="text/javascript"> 
		$(document).ready(function(){

			$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled - Adds empty span tag after ul.subnav

			$("ul.topnav li span").click(function() { //When trigger is clicked...

				//Following events are applied to the subnav itself (moving subnav up and down)
				$(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click

				$(this).parent().hover(function() {
				}, function(){	
					$(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
				});

				//Following events are applied to the trigger (Hover events for the trigger)
			}).hover(function() { 
				$(this).addClass("subhover"); //On hover over, add class "subhover"
			}, function(){	//On Hover Out
				$(this).removeClass("subhover"); //On hover out, remove class "subhover"
			});

		});
	</script>