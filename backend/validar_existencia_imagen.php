<?php
if (isset($_GET['nombreImagen'])) {
    $nombreImagen = $_GET['nombreImagen'];
    $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '/backend/img/estadisticas/areas/' . $nombreImagen;

    if (file_exists($rutaImagen)) {
        echo json_encode(['existe' => true]);
    } else {
        echo json_encode(['existe' => false]);
    }
} else {
    echo json_encode(['existe' => false]);
}
?>
