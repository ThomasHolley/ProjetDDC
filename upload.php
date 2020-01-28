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

    <form action="Redim.php">
        <button class="myButton" type="submit">Redimensionner</button>
    </form>

</body>

</html>

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