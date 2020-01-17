<?php
///////////// Redimensionnement des images ////////////////////
require_once 'Classes/PHPExcel.php';
require('fpdf_barcode.php');

$excelLoad = PHPExcel_IOFactory::load('Config.xlsx'); //Le programme charge le fichier Excel
$excelLoad->setActiveSheetIndex(0); //La page Excel 0 est chargé
$dir = 'Visuel/*';  //Chemin où ce trouve les visuels
$files = glob($dir, GLOB_BRACE);
$pdf = new PDF_BARCODE('p', 'mm', array(100, 240)); //creation d'un nouveau pdf avec code bar


$i = 2; // i commence à la deuxième ligne du tableau excel
while ($excelLoad->getActiveSheet()->getCell('A' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.
    $telephone = $excelLoad->getActiveSheet()->getCell('A' . $i)->getValue(); //La variable telephone prend pour valeur la cellule A

    foreach ($files as $dir) { //Boucle sur chaque fichiers du dossier
        $img = $dir;
        $size = getimagesize($img);    //Ajout de la taille du fichier à la variable "size"
        $newtext = substr($img, 8);
        $part = explode('-', $newtext); // découpage du nom de l'image par des "-"

        if ($part[1] == $telephone) { // si segment 2 du nom = Cellule A du tableau alors x et y prennent pour valeur B et C
            $y = $excelLoad->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule B
            $x = $excelLoad->getActiveSheet()->getCell('C' . $i)->getValue(); //La variale largeur prend pour valeur la cellule C
            $XPX = $x * 3.3;
            $YPX = $y * 3.3;
            $pdf->AddPage(); //Ajout d'une page sur le PDF
            $pdf->SetFont('Arial', '', 12); // Paramètrage de la police d'écriture

            if ($size) { //Si le fichier a une taille
                if ($size['mime'] == 'image/jpeg') { # Images en JPEG
                    $img_big = imagecreatefromjpeg($img); # On ouvre l'image d'origine
                    $img_new = imagecreate($XPX, $YPX); # création de la miniature
                    $img_mini = imagecreatetruecolor($XPX, $YPX)
                        or $img_mini = imagecreate($XPX, $YPX);    // copie de l'image, avec le redimensionnement.
                    imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]);
                    imagejpeg($img_mini, $img, 100);
                } elseif ($size['mime'] == 'image/png') { # Images en PNG
                    $img_big = imagecreatefrompng($img); # On ouvre l'image d'origine
                    $img_new = imagecreate($XPX, $YPX);   # création de la miniature
                    $img_mini = imagecreatetruecolor($XPX, $YPX)
                        or $img_mini = imagecreate($XPX, $YPX); // copie de l'image, avec le redimensionnement.
                    imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]);
                    imagepng($img_mini, $img, 9);
                }
            }
            $pdf->Image($img); //Ajout de l'image sur le PDF
            $pdf->Text(1, 230, $part[1]);
            $pdf->Text(30, 230, $part[2]); // Ajout du modèle de tel et de la matière sur le pdf
            $pdf->EAN13(10, 200, $part[0], 20, 0.35, 10); // Ajout d'un code bar du numéro du produit.
        }
    }
    $i++;
}

$pdf->Output('Commandes du ' . date("m.d.y") . '.pdf', 'D'); // Enregistrement du PDF avec pour nom la date du jour
