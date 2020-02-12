<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$sheet = $spreadsheet->getActiveSheet(0); //La feuille de travail Excel "0" est chargé

$highestDataRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
for ($row = 2; $row <= $highestDataRow; $row++) {
    if ($_POST["Modele"] = $spreadsheet->getActiveSheet()->getCell('B' . $row)->getValue()) {
        $sheet->getCoordinates();
        $sheet->removeRow($row, 1);
    }
}

$writer = new Xlsx($spreadsheet);
$writer->save('Config.xlsx');
