<a id="bouton_retour" href="javascript:history.go(-1)">Retour</a>
<link rel="stylesheet" type="text/css" href="VisuDim_css.css" />

<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$spreadsheet->setActiveSheetIndex(0);

echo "<table border=1>";
$i = 1; // i commence à la deuxième ligne du tableau excel
while ($spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.

    $telephone = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable telephone prend pour valeur la cellule A
    $hauteur = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule B
    $largeur = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(); //La variale largeur prend pour valeur la cellule C     

    echo "
        <tr>
            <td>" . $telephone . "</td>
            <td>" . $hauteur . "</td>
            <td>" . $largeur . "</td>
        </tr>
    ";
    $i++;
}

echo "</table>";

?>