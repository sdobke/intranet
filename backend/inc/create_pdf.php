<?php
require('../libraries/fpdf.php');

error_log("Before PDF creation");

if (isset($_POST['mesOpcion']) && isset($_POST['anoOpcion'])) {
    $mesOpcion = $_POST['mesOpcion'];
    $anoOpcion = $_POST['anoOpcion'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $subtitle = $mesOpcion . ' ' . $anoOpcion;
    $chartData = $_POST['chartData'];

    $pdf = new FPDF();

    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, $subtitle, 0, 1, 'C');

    $imagePath = "../img/estadisticas/$location/$mesOpcion$anoOpcion.png";

    $imgWidth = 300;
    $imgheight = 140;
    $imgTop = 50;
    switch ($location) {
        case 'genero':
            $imgWidth = 220;
            $imgheight = 120;
            break;
        case 'seccion_meses':
            $imgWidth = 220;
            $imgheight = 110;
            break;
        case 'dia_semanas':
            $imgWidth = 180;
            $imgheight = 100;
            $imgTop = 100;
            break;
        case 'dia_mes':
            print("entro");
            $imgWidth = 180;
            $imgheight = 100;
            $imgTop = 100;
            break;
        
        default:
           
            break;
    }

    $maxIntentos = 2;
    $intentos = 0;
    if($location != 'ranking_paginas' || $location != 'ranking_paginas' || $location != 'ranking_paginas' ) {
        //espero creacion de imagen
        while (!file_exists($imagePath) && $intentos < $maxIntentos) {
            sleep(1); 
            $intentos++;
        }

        if(file_exists($imagePath)) {
            $pdf->Image($imagePath,  10, $imgTop, $imgWidth, $imgheight , 'PNG');
        } 
    }
    

    $x = 10;
    $y = 40;
    $anchoBox = 80;
    $altoBox = 20;


    if(!empty($chartData)){
        $chartDataArray = json_decode($chartData, true);
        $pdf->SetFont('Arial', '', 10);

        $chartDataArray = json_decode($chartData, true);
        foreach ($chartDataArray as $c) {
            if($location == 'ranking_paginas') {
                $acessos =  $c['detalle'] . ': ' . $c['acessos'];
              
                $pdf->Rect($x, $y, $anchoBox, $altoBox);
                
                $pdf->SetXY($x, $y); 
                $pdf->MultiCell($anchoBox, 10, $acessos, 0, 'C');

                $y += $altoBox + 5;

            } else {
                $diaCant =  $c['dia'] . ': ' . $c['cant'];
                $pdf->Cell(0, 10, $diaCant, 0, 1, 'C');
            }
        }
    }

    $pdfFilePath = "../img/pdfs/$location/$mesOpcion$anoOpcion.pdf";
    $pdf->Output('F', $pdfFilePath);

    error_log("After PDF creation");
}
?>

