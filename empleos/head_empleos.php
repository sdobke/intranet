<?PHP 
if($_SESSION['ipfrom'] != 'ofi'){
	//exit;
}
$minisitio = 1;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="/css/reset.css" rel="stylesheet" type="text/css" />
<link href="/css/general.css" rel="stylesheet" type="text/css" />
<link href="/css/home.css" rel="stylesheet" type="text/css" />
<link href="/css/nota.css" rel="stylesheet" type="text/css" />
<link href="/css/secciones.css" rel="stylesheet" type="text/css" />
<link href="/css/slider.css" rel="stylesheet" type="text/css" />
<link href="/css/fonts.css" rel="stylesheet" type="text/css" />
<link href="/css/css3-buttons.css" rel="stylesheet" type="text/css"  media="screen" /> 
<link href="/css/formatos.css" rel="stylesheet" type="text/css" />
<link href="/cliente/mods.css" rel="stylesheet" type="text/css" />
<link href="css/empleos.css" rel="stylesheet" type="text/css" />

<!-- esta hoja de estilo pisa todos los estilos anteriores<link href="css/minisitio.css" rel="stylesheet" type="text/css" />-->

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.tools.min.js"></script>
<script type="text/javascript" src="../inc/scripts.js"></script>


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