<?php
if (isset($_POST['image'])) {
    $imageData = $_POST['image'];
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];

    echo "POST data: ";
var_dump($_POST);
echo "GET data: ";
var_dump($_GET);
    
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    $rutaGuardado = $_SERVER['DOCUMENT_ROOT'] . "/backend/img/estadisticas/" . $mes . "_" . "$ano .png";
    print $_SERVER['DOCUMENT_ROOT'] . "/img/estadisticas/" . $mes . "_" . "$ano .png";
    
    if (file_put_contents($rutaGuardado, $imageData)) {
        echo 'Imagen guardada correctamente en: ' . $rutaGuardado;
    } else {
        echo 'Error al guardar la imagen en: ' . $rutaGuardado;
    }
} else {
    echo 'No se recibió ninguna imagen para guardar';
}
?>
