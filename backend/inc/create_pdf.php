<?php
require('../libraries/fpdf.php');

error_log("Before PDF creation");

if (isset($_POST['mesOpcion']) && isset($_POST['anoOpcion'])) {
    $mesOpcion = $_POST['mesOpcion'];
    $anoOpcion = $_POST['anoOpcion'];
    $title = $_POST['title'];
    $title2 = $_POST['title2'];
    $location = $_POST['location'];
    $subtitle = $mesOpcion . ' ' . $anoOpcion;
    $chartData = $_POST['chartData'];
    $chartData2 = $_POST['chartData2'];

    $pdf = new FPDF();

    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, (stripos($title, "Comentarios") !== false ? 'Comentarios' : $title) , 0, 1, 'C');

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
    
    if($location != "comentarios") {
        if(!empty($chartData)){
            $chartDataArray = json_decode($chartData, true);
            $pdf->SetFont('Arial', '', 10);
            
            foreach ($chartDataArray as $c) {
                
                if($location == 'ranking_paginas') {
                    $acessos =  $c['detalle'] . ': ' . $c['acessos'];
                 
                  
                    $pdf->Rect($x, $y, $anchoBox, $altoBox);
                    
                    $pdf->SetXY($x, $y); 
                    $pdf->MultiCell($anchoBox, 10, $acessos, 0, 'C');
    
                    $y += $altoBox + 5;
    
                } 
                else {
                    $diaCant =  $c['dia'] . ': ' . $c['cant'];
                    $pdf->Cell(0, 10, $diaCant, 0, 1, 'C');
                }
            }
        }
    } else {
      
        if(!empty($chartData)){
        
          
            $pdf->setY(30);
            $pdf->setX(30);
            $pdf->SetFont('Arial', 'B', 12); 
            $pdf->Cell(0, 10, $title, 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 10);
            $chartDataArray = json_decode($chartData, true);
            foreach ($chartDataArray as $c) {
               
            $pdf->Rect($x, $y, $anchoBox, $altoBox);
            
            $pdf->SetXY($x, $y); 
            $pdf->MultiCell($anchoBox, 10, $c , 0, 'C');

            $y += $altoBox + 5;
            
            }
        }

        $chartDataArray2 = json_decode($chartData2, true);
        if(!empty($chartDataArray2)) {
       
            $pdf->SetFont('Arial', 'B', 12); 
            $pdf->setY(30);
            $pdf->setX(80);
            $pdf->Cell(0, 10, $title2, 0, 1, 'C');            

            $y = 40;
            $newX = 10 + $anchoBox + 10;

            $pdf->SetFont('Arial', '', 10);

            foreach ($chartDataArray2 as $c2) {
            
                $pdf->Rect($newX, $y, $anchoBox, $altoBox);
        
                $pdf->SetXY($newX, $y);
                $pdf->MultiCell($anchoBox, 10, $c2, 0, 'C');
        
                $y += $altoBox + 5;
            }        
        }
    }


    $pdfFilePath = "../img/pdfs/$location/$mesOpcion$anoOpcion.pdf";
    $pdf->Output('F', $pdfFilePath);

    error_log("After PDF creation");
}
?>

