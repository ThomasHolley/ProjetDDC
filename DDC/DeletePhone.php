<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$sheet = $spreadsheet->getActiveSheet(0); //La feuille de travail Excel "0" est chargé

$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
for ($row = 2; $row <= $highestRow; ++$row) {
    if( $_POST["Modele"] = $spreadsheet->getActiveSheet()->getCell('B' . $row)->getValue()){
        $sheet->removeRow($row, 1);
    }
}

$writer = new Xlsx($spreadsheet);
$writer->save('Config.xlsx');


