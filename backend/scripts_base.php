<!-- Core Scripts -->
<script src="assets/js/libs/jquery-1.8.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/libs/jquery.placeholder.min.js"></script>
<script src="assets/js/libs/jquery.mousewheel.min.js"></script>

<!-- Template Script -->
<script src="assets/js/template.js"></script>
<script src="assets/js/setup.js"></script>
<!-- Customizer, remove if not needed -->
<script src="assets/js/customizer.js"></script>
<!-- Uniform Script -->
<script src="plugins/uniform/jquery.uniform.min.js"></script>

<!-- jquery-ui Scripts -->
<script src="assets/jui/js/jquery-ui-1.9.2.min.js"></script>
<script src="assets/jui/jquery-ui.custom.min.js"></script>
<script src="assets/jui/jquery.ui.touch-punch.min.js"></script>
<!-- iButton -->
<script src="plugins/ibutton/jquery.ibutton.min.js"></script>
<script src="js/scroll-startstop.events.jquery.js" type="text/javascript"></script>
<script>
	$(function() {
		var $elem = $('#content');

		$('#nav_up').fadeIn('slow');
		$('#nav_down').fadeIn('slow');

		$(window).bind('scrollstart', function() {
			$('#nav_up,#nav_down').stop().animate({
				'opacity': '0.2'
			});
		});
		$(window).bind('scrollstop', function() {
			$('#nav_up,#nav_down').stop().animate({
				'opacity': '1'
			});
		});

		$('#nav_down').click(
			function(e) {
				$('html, body').animate({
					scrollTop: $elem.height()
				}, 800);
			}
		);
		$('#nav_up').click(
			function(e) {
				$('html, body').animate({
					scrollTop: '0px'
				}, 800);
			}
		);
	});
	$('.multiselector').first().resizable({
    resize: function() {
      //alert('resized');
    }
  });
</script>