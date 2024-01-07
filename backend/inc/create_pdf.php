<?php
require('../libraries/fpdf.php');

error_log("Before PDF creation");

if (isset($_POST['mesOpcion']) && isset($_POST['anoOpcion'])) {
    $mesOpcion = $_POST['mesOpcion'];
    $anoOpcion = $_POST['anoOpcion'];
    $title = $_POST['title'];
    $title2 = $_POST['title2'];
    $title3 = $_POST['title3'];
    $location = $_POST['location'];
    $subtitle = $mesOpcion . ' ' . $anoOpcion;
    $chartData = $_POST['chartData'];
    $chartData2 = $_POST['chartData2'];
    $chartData3 = $_POST['chartData3'];
    $title_aux = null;
    $pdf = new FPDF();

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
        case 'comentarios':
            $title_aux = "Comentarios";
            break;
        case 'me_gusta':
            $title_aux = "Me gusta";
            break;
        
        default:
           
            break;
    }
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, ($title_aux != null ? $title_aux : $title ) , 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, $subtitle, 0, 1, 'C');

    $imagePath = "../img/estadisticas/$location/$mesOpcion$anoOpcion.png";

    $imgWidth = 300;
    $imgheight = 140;
    $imgTop = 50;
   

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
    
    $x = 30; 
    $y = 50; 
    $anchoBox = ($pdf->GetPageWidth() - 60) / 3; 
    $altoBox = 20;
    print($location);
    if($location != "comentarios" && $location != "me_gusta") {
   
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
        if(!empty($chartData)) {
            $pdf->setY(35);
            $pdf->setX(30);
            $pdf->SetFont('Arial', 'B', 12); 
            $pdf->Cell(0, 10, $title, 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 10);
            $chartDataArray = json_decode($chartData, true);
            print_r($chartDataArray);
            foreach ($chartDataArray as $c) {
               
            $pdf->Rect($x, $y, $anchoBox, $altoBox);
            
            $pdf->SetXY($x, $y); 
            $pdf->MultiCell($anchoBox, 10, $c , 0, 'L');

            $y += $altoBox + 5;
            
            }
        }

        $chartDataArray2 = json_decode($chartData2, true);
        print_r($chartDataArray2);
        if(!empty($chartDataArray2)) {
       
            $pdf->SetFont('Arial', 'B', 12); 
            $pdf->setY(35);
            $pdf->setX(25);
            $pdf->Cell(0, 10, $title2, 0, 1, 'C');            

            $y = 50;
            $newX = 10 + $anchoBox + 30;

            $pdf->SetFont('Arial', '', 10);

            foreach ($chartDataArray2 as $c2) {
            
                $pdf->Rect($newX, $y, $anchoBox, $altoBox);
        
                $pdf->SetXY($newX, $y);
                $pdf->MultiCell($anchoBox, 10, $c2, 0, 'C');
        
                $y += $altoBox + 5;
            }        
        }
        $chartDataArray3 = json_decode($chartData3, true);
        print_r($chartDataArray3);
        if(!empty($chartDataArray3)) {
       
            $pdf->SetFont('Arial', 'B', 12); 
            $pdf->setY(35);
            $pdf->setX(140);
            $pdf->Cell(0, 10, $title3, 0, 1, 'C');            

            $y = 50;
            $newX2 = 10 + $anchoBox + 90;

            $pdf->SetFont('Arial', '', 10);

            foreach ($chartDataArray3 as $c2) {
            
                $pdf->Rect($newX2, $y, $anchoBox, $altoBox);
        
                $pdf->SetXY($newX2, $y);
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

