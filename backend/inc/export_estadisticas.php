
<script>
    function verificarExistenciaImagen(imgFolder, mesOpcion, anoOpcion, grafico = 'grafico') {
        $.ajax({
            url: "inc/validar_existencia_imagen.php",
            type: "GET",
            dataType: "json",
            data: { nombreImagen: imgFolder + "/" + mesOpcion + anoOpcion + ".png" },
            success: function(response) {
                if (response.existe) {
                    console.log("La imagen ya existe en el servidor");
                    document.getElementById("downloadToDeviceButton").style.display = "block";
                } else {
                    descargarImagen(imgFolder, grafico);
                    document.getElementById("downloadToDeviceButton").style.display = "block";
                }
            },
            error: function(error) {
                console.error("Error en la solicitud AJAX");
            }
        });
    
    }


    function descargarImagen(imgFolder, grafico) {
        grafico = (grafico == 'grafico' ? 'grafico' : grafico);
        console.log("imgFolder: "+imgFolder);
        let mes = document.getElementById('mes');
        let mesOpcion = mes.options[mes.selectedIndex].text;
        
        let ano = document.getElementById('ano');
        let anoOpcion = ano.options[ano.selectedIndex].text;

        html2canvas(document.getElementById(`${grafico}`)).then(function(canvas) { //cambiar generos por el alias que se usara de manera global
            var imageData = canvas.toDataURL('image/png');
            
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Imagen exportada correctamente');
                }
            };
            
            xhr.open('POST', 'guardar_imagen_exportacion.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('image=' + imageData + '&mes=' + mesOpcion + '&ano=' + anoOpcion + '&imgFolder=' + imgFolder);
            
        });
    }

    $(document).ready(function() {
														
        function descargarImagenAlDispositivo(imgLocation) {
            let mes = $('#mes option:selected').text();
            let ano = $('#ano option:selected').val();
            
            let imageUrl = `/backend/img/estadisticas/${imgLocation}/${mes}${ano}.png`;
            
            let link = $('<a>', {
                href: imageUrl,
                download: `${mes}${ano}.png`
            });

            $('body').append(link);

            link[0].click();

            setTimeout(function() {
                link.remove();
            }, 100);
        }

        $('#downloadToDeviceButton').on('click', function() {
            let imgLocation = $(this).data('location');
            descargarImagenAlDispositivo(imgLocation);
        });
    });
</script>

