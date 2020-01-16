<?php
require_once 'Classes/PHPExcel.php';

$excelLoad = PHPExcel_IOFactory::load('Config.xlsx');

$excelLoad->setActiveSheetIndex(0);

echo "<table border=1>";

$i = 1; // i commence à la deuxième ligne du tableau excel
while ($excelLoad->getActiveSheet()->getCell('A' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.

    $telephone = $excelLoad->getActiveSheet()->getCell('A' . $i)->getValue(); //La variable telephone prend pour valeur la cellule A
    $hauteur = $excelLoad->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule B
    $largeur = $excelLoad->getActiveSheet()->getCell('C' . $i)->getValue(); //La variale largeur prend pour valeur la cellule C     

    echo "
        <tr>
            <td>".$telephone."</td>
            <td>".$hauteur."</td>
            <td>".$largeur."</td>
        </tr>
    ";
    $i++;
}

echo "</table>";


?>
