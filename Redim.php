<?php
require_once 'Classes/PHPExcel.php';

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

$i = 2;
while ($excelLoad->getActiveSheet()->getCell('A' . $i)->getValue()) {

    $telephone = $excelLoad->getActiveSheet()->getCell('A' . $i)->getValue();
    $hauteur = $excelLoad->getActiveSheet()->getCell('B' . $i)->getValue();
    $largeur = $excelLoad->getActiveSheet()->getCell('C' . $i)->getValue();
    $image = $excelLoad->getActiveSheet()->getCell('D' . $i)->getValue();


    $x = $largeur;
    $y = $hauteur;
    $dir = $image;
    $files = glob($dir, GLOB_BRACE);



    foreach ($files as $dir) {
        $file = $dir;
        $size = getimagesize($file);
        if ($size) {
            if ($size['mime'] == 'image/jpeg') { # Images en JPEG

                $img_big = imagecreatefromjpeg($file); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y); # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                    or $img_mini = imagecreate($x, $y);    // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                imagejpeg($img_mini, $file);
            } elseif ($size['mime'] == 'image/png') { # Images en PNG   
                $img_big = imagecreatefrompng($file); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);   # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                    or $img_mini = imagecreate($x, $y); // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                imagepng($img_mini, $file);
            }
        }
        
    }
    $i++;
    echo 'Images redimensionné ! <br>';
}

/////////////////////////////////////////////////////////////
