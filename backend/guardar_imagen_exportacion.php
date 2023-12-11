<?php
if (isset($_POST['image'])) {
    $imageData = $_POST['image'];
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];
    $imgFolder = $_POST['imgFolder'];

    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    $rutaGuardado = $_SERVER['DOCUMENT_ROOT'] . "/backend/img/estadisticas/$imgFolder/" . $mes ."". "$ano.png";
    
    if (file_put_contents($rutaGuardado, $imageData)) {
        echo 'Imagen guardada correctamente en: ' . $rutaGuardado;
    } else {
        echo 'Error al guardar la imagen en: ' . $rutaGuardado;
    }
} else {
    echo 'No se recibió ninguna imagen para guardar';
}
?>
