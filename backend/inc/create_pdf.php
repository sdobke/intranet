<?php
require('../libraries/fpdf.php');

error_log("Before PDF creation");

if (isset($_POST['mesOpcion']) && isset($_POST['anoOpcion'])) {
    $mesOpcion = $_POST['mesOpcion'];
    $anoOpcion = $_POST['anoOpcion'];
    $title = $_POST['title'];
    $subtitle = $mesOpcion . ' ' . $anoOpcion;

    $pdf = new FPDF();

    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, $subtitle, 0, 1, 'C');

    $imagePath = "../img/estadisticas/rango_etario/$mesOpcion$anoOpcion.png";
    $pdf->Image($imagePath, 10, 30, 300, 140, 'PNG');

    $pdfFilePath = "../img/pdfs/rango_etario/$mesOpcion$anoOpcion.pdf";
    $pdf->Output('F', $pdfFilePath);

    error_log("After PDF creation");
}
?>

