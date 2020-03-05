<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Dealer De Coque - Redimensionnement Images</title>
  <link rel="icon" type="image/ico" href="Images/ddc_logo.jpg" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="master_css.css" />
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
</head>

<body>
  <!-- Menu de Navigation -->
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="Index.html">Accueil</a>
    <a href="AddPhone.html">Ajouter un modèle</a>
    <a href="https://www.dealerdecoque.fr/fr/">Boutique</a>
    <a href="mailto:pokeway872@repshop.net?subject=Site De Redimensionnement">Contact</a>
    <a href="admin.html">Admin</a>
  </div>
  </div>

  <div id="main">
    <!-- Div pour transition -->
    <header>
      <img id="logo" src="Images/ddc_logo.jpg" alt="Logo Dealer De Coque" />
      <!-- Logo de Dealer De Coque -->
    </header>

    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span> <!-- Ouvrir le menu de navigation -->
    <br><input type="button" style="display :block; margin:auto" value="Telecharger le fichier Excel" onClick="window.location.href='Config.xlsx'"><br> <!-- Bouton de téléchargement du Excel -->

    <!-- <form action="DeletePhone.php" method="POST">
      Form de suppression d'un modèle de Téléphone
      <input type="text" name="Modele" placeholder="Modele à supprimer..." required />
      <input type="submit" value="Envoyer" />
    </form> -->

    <!------------------------------------------- PHP -------------------------------------------------------------------------->
    <?php
    require 'vendor/autoload.php';

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
    $spreadsheet->setActiveSheetIndex(0); //Utilisation de la page de travail 1.

    echo "<table border=1>";
    $i = 1; // i commence à la deuxième ligne du tableau excel
    while ($spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.
      $spreadsheet->getActiveSheet()->getStyle('B' . $i)
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
      $telephone = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable telephone prend pour valeur la cellule A
      $hauteur = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule B
      $largeur = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(); //La variale largeur prend pour valeur la cellule C     

      //Affichage du tableau avec les valeurs du Excel
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
<script>
  //Menu de navigation
  function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
  }

  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
  }
</script>