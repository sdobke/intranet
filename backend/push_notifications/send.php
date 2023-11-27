<?php
$notif_texto = $vartitulo;
$notif_img = '';
if (isset($notif_texto) && isset($id)) {
	$not_foto = imagenPpal($id, $tipo, 4);
	if($not_foto != 0){
		$notif_img = $not_foto['link'];
	}
?>
	<script>
		window.onload = function() {
			window.notify = {
				list: [],
				id: 0,
				log: function(msg) {
					var console = document.getElementById('console');
					console.innerHTML += ("\n" + msg);
					console.scrollTop = console.scrollHeight;
				},
				compatible: function() {
					if (typeof Notification === 'undefined') {
						notify.log("Las notificaciones no están disponibles en su navegador");
						return false;
					}
					return true;
				},
				authorize: function() {
					if (notify.compatible()) {
						Notification.requestPermission(function(permission) {
							notify.log("Permiso para enviar notificaciones: " + permission);
						});
					}
				},
				showDelayed: function(seconds) {
					notify.log("Una notificación será enviada en " + seconds + " segundos. No es necesario tener esta pantalla en primer plano.");
					setTimeout(notify.show, (seconds * 1000));
				},
				show: function() {

					if (typeof Notification === 'undefined') {
						notify.log("Las notificaciones no están disponibles en su navegador.");
						return;
					}
					if (notify.compatible()) {
						notify.id++;
						var id = notify.id;
						notify.list[id] = new Notification("Notificacion #" + id, {
							body: "<?php echo $notif_texto; ?>",
							tag: id,
							icon: "<?php echo $notif_img; ?>",
							lang: "es",
							dir: "auto",
						});
						notify.log("Notification #" + id + " queued for display");
						notify.list[id].onclick = function() {
							notify.logEvent(id, "clicked");
						};
						notify.list[id].onshow = function() {
							notify.logEvent(id, "showed");
						};
						notify.list[id].onerror = function() {
							notify.logEvent(id, "errored");
						};
						notify.list[id].onclose = function() {
							notify.logEvent(id, "closed");
						};

						console.log("Created a new notification ...");
						console.log(notify.list[id]);
					}
				},
				logEvent: function(id, event) {
					notify.log("Notification #" + id + " " + event);
				}
			};
		};
	</script>

	<p>
		<button type="button" onclick="notify.authorize()">Autorizar</button>
		<button type="button" onclick="notify.show()">Mostrar</button>
		<button type="button" onclick="notify.showDelayed(10)">Mostrar en 10s</button>
		<!-- TODO : add a button that shows a notification using a 'tag' -->
	</p>

	<textarea id="console" readonly>Consola
------------------</textarea>
<?php }
