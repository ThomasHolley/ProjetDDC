<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dealer De Coque - Redimensionnement Images</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="Index_css.css">
</head>

<body>

  <header>
    <img src="ddc_logo.jpg" alt="Italian Trulli">
    <h1 id="text_titre"> Redimensionnement des Images</h1>
  </header>

  <form action="Redim.php" method="get">
    <input class="myButton" type="submit" value="Redimensionner!"> <!-- Au clique sur le bouton, appel de la page Redim.php -->
  </form>

  <p id="info"></p>


  <footer>
    <p>© copyright - Holley Thomas</p>
  </footer>

</body>

</html>
<script>
  function progress() {
    document.getElementById("info").innerHTML = "Images en cours de modification...";
  }
</script>