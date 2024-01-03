<?php
require('../libraries/fpdf.php');

error_log("Before PDF creation");

if (isset($_POST['mesOpcion']) && isset($_POST['anoOpcion'])) {
    $mesOpcion = $_POST['mesOpcion'];
    $anoOpcion = $_POST['anoOpcion'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $subtitle = $mesOpcion . ' ' . $anoOpcion;

    $pdf = new FPDF();

    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, $subtitle, 0, 1, 'C');

    $imagePath = "../img/estadisticas/$location/$mesOpcion$anoOpcion.png";

    $imgWidth = 300;
    $imgheight = 140;
    switch ($location) {
        case 'genero':
            $imgWidth = 220;
            $imgheight = 110;
            break;
        case 'seccion_meses':
            $imgWidth = 220;
            $imgheight = 110;
            break;
        case 'rango_etario':
            # code...
            break;
        
        default:
            # code...
            break;
    }

    $pdf->Image($imagePath, 10, 30, $imgWidth, $imgheight, 'PNG');

    $pdfFilePath = "../img/pdfs/$location/$mesOpcion$anoOpcion.pdf";
    $pdf->Output('F', $pdfFilePath);

    error_log("After PDF creation");
}
?>

