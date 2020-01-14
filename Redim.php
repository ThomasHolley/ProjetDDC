<?php
require_once 'Classes/PHPExcel.php';
require('fpdf_barcode.php');

//////////////// Créer Fichier Excel et Insertion/////////////////////////

/*function createfil(){
    $excel = new PHPExcel();

    $excel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Hello')
        ->setCellValue('B1', 'WOLRD');

    $file = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $file->save('test.xlsx');
}
*/
///////////// Récupération données fichier Excel////////////////////

$excelLoad = PHPExcel_IOFactory::load('Config.xlsx');
$excelLoad->setActiveSheetIndex(0);
$pdf = new PDF_BARCODE('p', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$i = 2;
while ($excelLoad->getActiveSheet()->getCell('A' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.

    $telephone = $excelLoad->getActiveSheet()->getCell('A' . $i)->getValue(); //La variable telephone prend pour valeur la cellule A
    $y = $excelLoad->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule B
    $x = $excelLoad->getActiveSheet()->getCell('C' . $i)->getValue(); //La variale largeur prend pour valeur la cellule C
    $dir = $excelLoad->getActiveSheet()->getCell('D' . $i)->getValue(); //La variable Image prend pour valeur la cellule D
    $files = glob($dir, GLOB_BRACE);

    foreach ($files as $dir) { //Boucle sur chaque fichiers du dossier
        $img = $dir;
        $size = getimagesize($img);    //Ajout de la taille du fichier à la variable "size"
        $barcode = explode('-', $img);
        $pdf->Image($img);
        $pdf->Text(10, 250, $img);
        $pdf->EAN13(10, 230, $barcode[1], 9, 1, 10);
        
        if ($size) { //Si la taille a une valeur
            if ($size['mime'] == 'image/jpeg') { # Images en JPEG
                $img_big = imagecreatefromjpeg($img); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y); # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                    or $img_mini = imagecreate($x, $y);    // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                imagejpeg($img_mini, $img);
            } elseif ($size['mime'] == 'image/png') { # Images en PNG   
                $img_big = imagecreatefrompng($img); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);   # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                    or $img_mini = imagecreate($x, $y); // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                imagepng($img_mini, $img);
            }
        }
    }
  
    $i++;
    
}

$pdf->Output('', 'I');
