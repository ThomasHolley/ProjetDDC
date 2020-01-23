<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$sheet = $spreadsheet->getActiveSheet(0); //La feuille de travail Excel "0" est chargé
$row = $sheet->getHighestDataRow() + 1;

$sheet->insertNewRowBefore($row, 1);
$sheet->setCellValue('B' . $row, $_POST['telephone']);
$sheet->setCellValue('C' . $row, $_POST['Hauteur']);
$sheet->setCellValue('D' . $row, $_POST['Largeur']);

$writer = new Xlsx($spreadsheet);
$writer->save('Config.xlsx');



echo ("Nouveau modèle Ajouter au fichier");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Dealer De Coque - Redimensionnement Images</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<button id="Button_visualiser" onclick="location.href='Index.html'"" type=" button">
    Page d'acceuil</button>
<button id="Button_visualiser" onclick="location.href='AddPhone.html'"" type=" button">
    Ajouter un Appareil</button>
</body>

</html>