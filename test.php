<?php

require 'vendor/autoload.php';
require('code39.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;


$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx');
$spreadsheet->setActiveSheetIndex(0);
$dir = 'Visuel/*';
$files = glob($dir, GLOB_BRACE);

$pdf = new PDF_Code39('p', 'mm', array(100, 240));
$pdf->SetCompression(0);
$i = 2;

$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
for ($row = 1; $row <= $highestRow; ++$row) {
    $tabl = array(
        $telephone = $spreadsheet->getActiveSheet()->getCell('B' . $row)->getValue(),
        $y = $spreadsheet->getActiveSheet()->getCell('C' . $row)->getValue(),
        $x = $spreadsheet->getActiveSheet()->getCell('D' . $row)->getValue()
    );
    usort($tabl, 'cmp');



foreach ($files as $dir) {
    $img = $dir;
    $img = strtoupper($img);
    $size = getimagesize($img);
    $newtext = substr($img, 7);
    $newtext = str_replace("MC ", "MC-", "$newtext");
    $newtext = str_replace("MUG", "MUG-", "$newtext");
    $newtext = str_replace(" ", "", "$newtext");
    $part = explode('-', $newtext);

    usort($tabl, 'cmp');

    if ($part[2] == $telephone) {
        if ($telephone == 'MUG') {
            $YPX = $y * 12.5;
            $XPX = $x * 11;
            $pdf->AddPage();
            if ($size) {
                if ($size['mime'] == 'image/jpeg') {
                    $img_source = imagecreatefromjpeg($img);
                    $img_dest = imagecreatetruecolor($XPX, $YPX);
                    imageresolution($img_dest, 300, 300);
                    imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]);
                    imageflip($img_dest, IMG_FLIP_HORIZONTAL);
                    imagejpeg($img_dest, $img, 100);
                }
            }
            $pdf->Image($img, 5, 8);
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 20);
            $pdf->SetTextColor(107, 107, 71);
            $pdf->Image($img, 0, 0, 30, 60);
            $pdf->Text(15, 130, $part[0]);
            $pdf->Text(40, 130, $part[2]);
            $pdf->Code39(25, 100, $part[1]);
        }


        if ($part[2] == $telephone) {
            if ($telephone != 'MUG') {
                if ($telephone != "POP") {
                    $y = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue();
                    $x = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue();
                    $YPX = $y * 14;
                    $XPX = $x * 14;
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->SetTextColor(107, 107, 71);
                    $pdf->PageNo();
                    if ($size) {
                        if ($size['mime'] == 'image/jpeg') {
                            $img_source = imagecreatefromjpeg($img);
                            $img_dest = imagecreatetruecolor($XPX, $YPX);
                            imageresolution($img_dest, 300, 300);
                            imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]);
                            imageflip($img_dest, IMG_FLIP_HORIZONTAL);
                            $color = imagecolorallocate($img_dest, 107, 107, 71);
                            drawBorder($img_dest, $color, 3);
                            imagejpeg($img_dest, $img, 100);
                        }
                    }
                    $pdf->Image($img);
                    $pdf->Text(5, 238, $part[0]);
                    $pdf->Text(13, 238, $part[1]);
                    $pdf->Text(28, 238, $part[2]);
                    $pdf->Text(80, 238, $part[3]);
                    $pdf->Code39(30, 205, $part[1]);
                }
            }



            if ($part[2] == $telephone) {
                if ($telephone == 'POPSOCKET') {
                    $y = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue();
                    $x = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue();
                    $YPX = $y * 12.5;
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->SetTextColor(107, 107, 71);
                    if ($size) {
                        if ($size['mime'] == 'image/jpeg') {
                            $img_source = imagecreatefromjpeg($img);
                            $img_dest = imagecreatetruecolor($XPX, $YPX);
                            imageresolution($img_dest, 300, 300);
                            imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $XPX, $YPX, $size[0], $size[1]);
                            imageflip($img_dest, IMG_FLIP_HORIZONTAL);
                            $color = imagecolorallocate($img_dest, 107, 107, 71);
                            drawBorder($img_dest, $color, 3);
                            imagejpeg($img_dest, $img, 100);
                        }
                    }
                    $pdf->Image($img);
                    $pdf->Text(5, 238, $part[0]);
                    $pdf->Text(13, 238, $part[1]);
                    $pdf->Text(28, 238, $part[2]);
                    $pdf->Text(80, 238, $part[3]);
                    $pdf->Code39(30, 205, $part[1]);
                }
            }
        }
    }
}


}


$pdf->Output('Commandes du ' . date("d.m.y") . '.pdf', 'I');
$path = 'Visuel/'; //ne pas oublier le slash final
$rep = opendir($path);
//$i=0;
while ($file = readdir($rep)) {
    if ($file != '..' && $file != '.' && $file != '' && $file != '.htaccess') {
        unlink($path . $file);
        //$i++;
    }
}

///////////////////////// Fonction pour la création de bordures /////////////////////////
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
function cmp($telephone, $b)
{
    if ($telephone == $b) {
        return 0;
    }
    return ($telephone < $b) ? -1 : 1;
}
