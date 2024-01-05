<script>
    $('#downloadCsv, #downloadCsvDos').on('click', function() {
        let mes = document.getElementById('mes');
        let mesOpcion = mes.options[mes.selectedIndex].text;
        
        let ano = document.getElementById('ano');
        let anoOpcion = ano.options[ano.selectedIndex].text;
        let  chartDataCsv = null;

        let botonId = $(this).attr('id');
        if (botonId == 'downloadCsvDos'){
            chartDataCsv = <?php echo json_encode($dataCsv2); ?>;
        } else {
            chartDataCsv= <?php echo json_encode($dataCsv); ?>; 
        }

        let csvLocation = $(this).data('location');

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };
        
        xhttp.open("POST", "inc/create_csv.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var postData = "mesOpcion=" + encodeURIComponent(mesOpcion)
                                    + "&anoOpcion=" + encodeURIComponent(anoOpcion) 
                                    + "&location=" + encodeURIComponent(csvLocation)
                                    + "&chartDataCsv=" + encodeURIComponent(JSON.stringify(chartDataCsv));
        xhttp.send(postData);

        let csvFormato = $(this).data('formato');
        console.log(csvLocation, csvFormato);
        descargarImagenAlDispositivo(csvLocation, csvFormato);
    });
</script>