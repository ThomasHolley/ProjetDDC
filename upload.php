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

        <form action="test1.php" id="form">
            <button class="myButton" type="submit">Redimensionner</button>
        </form>
    </div>
    <footer>
        <p>Tous droits reservés - Thomas Hg - Dealer De Coque</p>
    </footer>
</body>

</html>
<!--------------------------------------------- SCRIPT -------------------------------------------------------------------------->
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }
</script>
<!--------------------------------------------- PHP ------------------------------------------------------------------------->
<?php

extract($_POST);
$error = array();
$extension = array("jpeg", "jpg", "png", "gif");
foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
    $file_name = $_FILES["images"]["name"][$key];
    $file_tmp = $_FILES["images"]["tmp_name"][$key];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);

    if (in_array($ext, $extension)) {
        move_uploaded_file($file_tmp = $_FILES["images"]["tmp_name"][$key], "Visuel/" . $file_name);
    }
}

?>