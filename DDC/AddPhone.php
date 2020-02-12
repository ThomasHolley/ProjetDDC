<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$sheet = $spreadsheet->getActiveSheet(0); //La feuille de travail Excel "0" est chargé
$row = $sheet->getHighestDataRow() + 1; //La ligne à insérer est la derniere +1

$sheet->insertNewRowBefore($row, 1); //La ligne à insérer avant la derniere +1
$sheet->setCellValue('B' . $row, $_POST['Marque'] . $_POST['telephone']); //Ajout dans la colonne B de la marque + modele de tel
$sheet->setCellValue('C' . $row, $_POST['Hauteur']); //Ajout dans la colonne c de la Hauteur
$sheet->setCellValue('D' . $row, $_POST['Largeur']); //Ajout dans la colonne D de la Largeur

$writer = new Xlsx($spreadsheet); //Réécriture du fichier Excel
$writer->save('Config.xlsx'); //Sauvegarde du fichier Excel
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Dealer De Coque - Redimensionnement Images</title>
  <link rel="icon" type="image/ico" href="Images/ddc_logo.jpg" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="master_css.css" />
  <meta http-equiv="refresh" content="2; url=AddPhone.html" />
</head>

<body>
  <h1 id="Message_AddSuccess">Modèle ajouté avec succès</h1> <!-- Message d'ajout -->
</body>

</html>