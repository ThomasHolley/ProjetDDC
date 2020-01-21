<?php
///////////// Redimensionnement des images ////////////////////
require_once 'Classes/PHPExcel.php';
require('fpdf_barcode.php');


function mb_strrev($str){
    $r = '';
    for ($i = mb_strlen($str); $i>=0; $i--) {
        $r .= mb_substr($str, $i, 1);
    }
    return $r;
}
function hex2rgb($hex) {
    $color = str_replace('#','',$hex);
    $rgb = array(
       'r' => hexdec(substr($color,0,2)),
       'g' => hexdec(substr($color,2,2)),
       'b' => hexdec(substr($color,4,2)),
    );
    return $rgb;
 }
function rgb2cmyk($var1,$g=0,$b=0) {
    if (is_array($var1)) {
            $r = $var1['r'];
            $g = $var1['g'];
            $b = $var1['b'];
    } else {
            $r = $var1;
    }
    $cyan = 1 - $r/255;
    $magenta = 1 - $g/255;
    $yellow = 1 - $b/255;
    $black = min($cyan, $magenta, $yellow);
    $cyan = @round(($cyan - $black) / (1 - $black) * 100);
    $magenta = @round(($magenta - $black) / (1 - $black) * 100);
    $yellow = @round(($yellow - $black) / (1 - $black) * 100);
    $black = round($black * 100);
    return array(
            'c' => $cyan,
            'm' => $magenta,
            'y' => $yellow,
            'k' => $black,
    );
}
  
$color=rgb2cmyk(hex2rgb('#FF0000'));



$excelLoad = PHPExcel_IOFactory::load('Config.xlsx'); //Le programme charge le fichier Excel
$excelLoad->setActiveSheetIndex(0); //La page Excel 0 est chargé
$dir = 'Visuel/*';  //Chemin où ce trouve les visuels
$files = glob($dir, GLOB_BRACE);
$pdf = new PDF_BARCODE('p', 'mm', array(100, 240)); //creation d'un nouveau pdf avec code bar



$i = 2; // i commence à la deuxième ligne du tableau excel
while ($excelLoad->getActiveSheet()->getCell('B' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.
    $telephone = $excelLoad->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable telephone prend pour valeur la cellule A

    foreach ($files as $dir) { //Boucle sur chaque fichiers du dossier
        $img = $dir;
        $size = getimagesize($img);    //Ajout de la taille du fichier à la variable "size"
        $newtext = substr($img, 8); //Découpe le chemin et le nom des fichiers pour séléctionner uniquement le nom du fichier
        $part = explode('-', $newtext); // découpage du nom de l'image par des "-"

        if ($part[1] == $telephone) { // si segment 2 du nom = Cellule A du tableau alors x et y prennent pour valeur B et C
            $y = $excelLoad->getActiveSheet()->getCell('C' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule B
            $x = $excelLoad->getActiveSheet()->getCell('D' . $i)->getValue(); //La variale largeur prend pour valeur la cellule C
            $XPX = $x * 4.1
            ;
            $YPX = $y * 4.1;
            $pdf->AddPage(); //Ajout d'une page sur le PDF
            $pdf->SetFont('Arial', '', 12); // Paramètrage de la police d'écriture

            if ($size) { //Si le fichier a une taille
                if ($size['mime'] == 'image/jpeg') { # Images en JPEG
                    $img_big = imagecreatefromjpeg($img); # On ouvre l'image d'origine
                    $img_new = imagecreate($XPX, $YPX); # création de la miniature
                    $img_mini = imagecreatetruecolor($XPX, $YPX)
                        or $img_mini = imagecreate($XPX, $YPX);    // copie de l'image, avec de bonnes couleurs.
                    imageresolution($img_mini, 300);
                    imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]); //l'image est redimensionné
                    imagejpeg($img_mini, $img, 100); // L'image est sauvegardé en JPEG avec une qualité de 100
                } elseif ($size['mime'] == 'image/png') { # Images en PNG
                    $img_big = imagecreatefrompng($img); # On ouvre l'image d'origine
                    $img_new = imagecreate($XPX, $YPX);   # création de la miniature
                    $img_mini = imagecreatetruecolor($XPX, $YPX)
                        or $img_mini = imagecreate($XPX, $YPX); // copie de l'image, avec de bonnes couleurs.
                    imagefill($img_mini, 0,0,$color);
                    imageresolution($img_mini, 300);
                    imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]);//l'image est redimensionné
                    imagepng($img_mini, $img, 9); // L'image est sauvegardé en PNG avec une qualité de 100
                }
            }
            $textinv0 = mb_strrev($part[0]); //Inversion du codebar
            $textinv = mb_strrev($part[1]); //Inversion 
            $textinv2 = mb_strrev($part[2]); //Inversion 
            $pdf->Image($img); //Ajout de l'image sur le PDF
            $pdf->Text(1, 230, $textinv);
            $pdf->Text(30, 230, $textinv2); // Ajout du modèle de tel et de la matière sur le pdf
            $pdf->EAN13(5, 200, $textinv0, 20, 0.35, 10); // Ajout d'un code bar du numéro du produit.
        }
        
    }
    $i++;
    
}

$pdf->Output('Commandes du ' . date("d.m.y") . '.pdf', 'D'); // Enregistrement du PDF avec pour nom la date du jour
