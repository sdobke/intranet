<?php

if (isset($_POST['mesOpcion']) && isset($_POST['anoOpcion'])) {

    $mesOpcion = $_POST['mesOpcion'];
    $anoOpcion = $_POST['anoOpcion'];
    $location = $_POST['location'];
    $chartData = json_decode($_POST['chartDataCsv'], true);

    $csvFilePath = "../img/csv/$location/$mesOpcion$anoOpcion.csv";
    $archivo = fopen($csvFilePath, 'w');

    if ($archivo) {
        foreach ($chartData as $fila) {
            fputcsv($archivo, $fila);
        }

        fclose($archivo);

        echo 'Archivo CSV creado con éxito en: ' . $csvFilePath;
    } else {
        echo 'Error al abrir el archivo CSV para escritura.';
    }

} else {
    echo 'Parámetros faltantes en la solicitud.';
}
?>
