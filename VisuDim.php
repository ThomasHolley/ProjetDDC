<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Dealer De Coque - Redimensionnement Images</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="Index_css.css" />
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
</head>

<body>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="Index.html">Accueil</a>
    <a href="AddPhone.html">Ajouter un modèle</a>
    <a href="https://www.dealerdecoque.fr/fr/">Boutique</a>
    <a href="mailto:pokeway872@repshop.net?subject=Site De Redimensionnement">Contact</a>
  </div>
  </div>

  <div id="main">
    <header>
      <img id="logo" src="ddc_logo.jpg" alt="Logo Dealer De Coque" />
      <!-- Logo de Dealer De Coque -->

    </header>

    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
<!------------------------------------------- PHP -------------------------------------------------------------------------->
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
<!---------------------------------------------------------------------------------------------------------------------------------->
</div>


</body>
</html>
<!--------------------------------------------- SCRIPT ------------------------------------------------------------------------------------->
<script> //Script de Transition
  function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
  }

  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
  }
</script>





