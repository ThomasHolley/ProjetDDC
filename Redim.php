<?php
///////////// Redimensionnement des images ////////////////////
require('fpdf_barcode.php');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;


///////////// Fonctions ////////////////////

function mb_strrev($str) // Fonction pour inversement des caractères
{
    $r = '';
    for ($i = mb_strlen($str); $i >= 0; $i--) {
        $r .= mb_substr($str, $i, 1);
    }
    return $r;
}

///////////////////////////////////////////////////////////////////////

$inputFileName = 'Config.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
$spreadsheet->setActiveSheetIndex(0); //La page Excel 0 est chargé
$dir = 'Visuel/*';  //Chemin où ce trouve les visuels
$files = glob($dir, GLOB_BRACE);
$pdf = new PDF_BARCODE('p', 'mm', array(100, 240)); //creation d'un nouveau pdf avec code bar

$i = 2; // i commence à la deuxième ligne du tableau excel
while ($spreadsheet->getActiveSheet()->getCell('A' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.
    $telephone = $spreadsheet->getActiveSheet()->getCell('A' . $i)->getValue(); //La variable telephone prend pour valeur la cellule B

    foreach ($files as $dir) { //Boucle sur chaque fichiers du dossier
        $img = $dir;
        $size = getimagesize($img);    //Ajout de la taille du fichier à la variable "size"
        $newtext = substr($img, 8); //Découpe le chemin et le nom des fichiers pour séléctionner uniquement le nom du fichier
        $part = explode('-', $newtext); // Découpage du nom de l'image par des "-"

        if ($part[1] == $telephone) { // si segment 2 du nom = Cellule B du tableau alors x et y prennent pour valeur C et D
            $y = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule C
            $x = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(); //La variale largeur prend pour valeur la cellule D
            $YPX = $y * 4.1;
            $XPX = $x * 4.1;
            $pdf->AddPage(); //Ajout d'une page sur le PDF
            $pdf->SetFont('Arial', '', 12); // Paramètrage de la police d'écriture

            if ($size) { //Si le fichier a une taille
                if ($size['mime'] == 'image/jpeg') { # Images en JPEG
                    $img_big = imagecreatefromjpeg($img); # On ouvre l'image d'origine
                    $img_new = imagecreate($XPX, $YPX); # création de la miniature
                    $img_mini = imagecreatetruecolor($XPX, $YPX)
                        or $img_mini = imagecreate($XPX, $YPX);    // copie de l'image, avec de bonnes couleurs
                    imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]); //l'image est redimensionné
                    imageresolution($img_mini, 300);
                    imagejpeg($img_mini, $img, 100); // L'image est sauvegardé en JPEG avec une qualité de 100
                } elseif ($size['mime'] == 'image/png') { # Images en PNG
                    $img_big = imagecreatefrompng($img); # On ouvre l'image d'origine
                    $img_new = imagecreate($XPX, $YPX);   # création de la miniature
                    $img_mini = imagecreatetruecolor($XPX, $YPX)
                        or $img_mini = imagecreate($XPX, $YPX); // copie de l'image, avec de bonnes couleurs.
                    imagecopyresampled($img_mini, $img_big, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]); //l'image est redimensionné
                    imageresolution($img_mini, 300);
                    imagepng($img_mini, $img, 9); // L'image est sauvegardé en PNG avec une qualité au maximum
                }
            }
            $textinv0 = mb_strrev($part[0]); //Inversion du codebar
            $textinv = mb_strrev($part[1]); //Inversion du Modele de tel
            $textinv2 = mb_strrev($part[2]); //Inversion De la matiere du tel
            $pdf->Image($img); //Ajout de l'image sur le PDF
            $pdf->Text(1, 230, $textinv); // Ajout du modele du tel sur le pdf
            $pdf->Text(30, 230, $textinv2); // Ajout de la matiere du tel sur le pdf
            $pdf->EAN13(5, 200, $textinv0, 20, 0.35, 10); // Ajout d'un code bar du numéro du produit.
        }
    }
    $i++;
}
$pdf->Output('Commandes du ' . date("d.m.y") . '.pdf', 'I'); // Enregistrement du PDF avec pour nom la date du jour
