<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Dealer De Coque - Redimensionnement Images</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="master_css.css" />
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
<fieldset id="form_upload">
        <form action="Redim.php" id="form">
            <button class="myButton" type="submit" onclick="toggle_text()">Redimensionner</button><br>
            <span id="span_txt" style="display:none;"><img src="Iphoneload.gif" style="width: 50px; height:50px; margin:2%"></span>
        </form>
        </fieldset>
        <button class="myButton" onclick="window.location.href='Index.html'">Ajouter des Visuels supplémentaires</button>
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

    function toggle_text() {
        var span = document.getElementById("span_txt");
        if (span.style.display == "none") {
            span.style.display = "inline";
        } else {
            span.style.display = "none";
        }
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