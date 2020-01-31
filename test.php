<?php
///////////// Redimensionnement des images ////////////////////

require 'vendor/autoload.php';
require('code39.php');



use PhpOffice\PhpSpreadsheet\Spreadsheet;




///////////////////////////////////////////////////////////////////////

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$spreadsheet->setActiveSheetIndex(0); //La feuille de travail Excel "0" est chargé
$dir = 'Visuel/*';  //Chemin où ce trouve les visuels
$files = glob($dir, GLOB_BRACE);




$pdf = new PDF_Code39('p', 'mm', array(100, 240)); //creation d'un nouveau pdf avec code bar
$pdf->SetCompression(0);



$i = 2; // i commence à la deuxième ligne du tableau excel
while ($spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.
    $telephone = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable telephone prend pour valeur la cellule B


    foreach ($files as $dir) { //Boucle sur chaque fichiers du dossier

        $img = $dir;
        $size = getimagesize($img);    //Ajout de la taille du fichier à la variable "size"
        $newtext = substr($img, 7); //Découpe le chemin et le nom des fichiers pour séléctionner uniquement le nom du fichier
        $part = explode('-', $newtext); // Découpage du nom de l'image par des "-"

        if ($part[2] == $telephone) { // si segment 2 du nom = Cellule B du tableau alors x et y prennent pour valeur C et D
            if ($telephone == 'MUG') {
                $y = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule C
                $x = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(); //La variale largeur prend pour valeur la cellule D
                $YPX = $y * 10; //Calcul du ratio
                $XPX = $x * 10; //Calcul du ratio
                $pdf->AddPage(); //Ajout d'une page sur le PDF



                if ($size) { //Si le fichier a une taille
                    if ($size['mime'] == 'image/jpeg') { # Images en JPEG
                        $img_source = imagecreatefromjpeg($img); # On ouvre l'image d'origine
                        $img_dest = imagecreatetruecolor($XPX, $YPX);
                        imageresolution($img_dest, 300, 300);
                        imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]); //l'image est redimensionné
                        imageflip($img_dest, IMG_FLIP_HORIZONTAL);
                        imagejpeg($img_dest, $img, 100); // L'image est sauvegardé en JPEG avec une qualité de 100


                    }
                }
                $pdf->Image($img, 10, 20); //Ajout de l'image sur le PDF
            }

            if ($part[2] == $telephone) { // si segment 2 du nom = Cellule B du tableau alors x et y prennent pour valeur C et D
                if ($telephone != 'MUG') {
                    $y = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule C
                    $x = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(); //La variale largeur prend pour valeur la cellule D
                    $YPX = $y * 14; //Calcul du ratio
                    $XPX = $x * 14; //Calcul du ratio
                    $pdf->AddPage(); //Ajout d'une page sur le PDF
                    $pdf->SetFont('Arial', '', 12); // Paramètrage de la police d'écriture
                    $pdf->SetTextColor(107, 107, 71);


                    if ($size) { //Si le fichier a une taille
                        if ($size['mime'] == 'image/jpeg') { # Images en JPEG
                            $img_source = imagecreatefromjpeg($img); # On ouvre l'image d'origine
                            $img_dest = imagecreatetruecolor($XPX, $YPX);
                            imageresolution($img_dest, 300, 300);
                            imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]); //l'image est redimensionné
                            imageflip($img_dest, IMG_FLIP_HORIZONTAL);
                            $color = imagecolorallocate($img_dest, 107, 107, 71);
                            drawBorder($img_dest, $color, 3);
                            imagejpeg($img_dest, $img, 100); // L'image est sauvegardé en JPEG avec une qualité de 100


                        }
                    }

                    $pdf->Image($img); //Ajout de l'image sur le PDF
                    $pdf->Text(5, 238, $part[0]); // Ajout du CLIENT
                    $pdf->Text(13, 238, $part[1]); // Ajout du NUMERO DE COMMANDE
                    $pdf->Text(28, 238, $part[2]); // Ajout MODELE DE TELEPHONE
                    $pdf->Text(80, 238, $part[3]); // Ajout de la MATIERE DE TELEPHONE
                    $pdf->Code39(30, 205, $part[1]); // Ajout d'un code bar du numéro du produit.

                }
            }
        }
    }
    $i++;
}
$pdf->Output('Commandes du ' . date("d.m.y") . '.pdf', 'I'); // Enregistrement du PDF avec pour nom la date du jour

/// Supprime les fichiers du dossier Visuel

$path = 'Visuel/'; //ne pas oublier le slash final
$rep = opendir($path);
//$i=0;
while ($file = readdir($rep)) {
    if ($file != '..' && $file != '.' && $file != '' && $file != '.htaccess') {
        unlink($path . $file);
        //$i++;
    }
}

function drawBorder(&$img, &$color, $thickness = 1)
{
    $x1 = 0;
    $y1 = 0;
    $x2 = ImageSX($img) - 1;
    $y2 = ImageSY($img) - 1;

    for ($i = 0; $i < $thickness; $i++) {
        ImageRectangle($img, $x1++, $y1++, $x2--, $y2--, $color);
    }
}
