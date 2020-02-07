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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Dealer De Coque - Redimensionnement Images</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="master_css.css" />
    <meta http-equiv="refresh" content="2; url=AddPhone.html" />
</head>

<body >
<h1 id="Message_AddSuccess">Modèle ajouté avec succée</h1>
</body>

</html>