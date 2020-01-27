<?php
///////////// Redimensionnement des images ////////////////////
require('fpdf_barcode.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

///////////////////////////////////////////////////////////////////////

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Config.xlsx'); //Initialisation du chargement du fichier Excel
$spreadsheet->setActiveSheetIndex(0); //La feuille de travail Excel "0" est chargé
$dir = 'Visuel/*';  //Chemin où ce trouve les visuels
$files = glob($dir, GLOB_BRACE);
$pdf = new PDF_BARCODE('p', 'mm', array(100, 240)); //creation d'un nouveau pdf avec code bar

$i = 2; // i commence à la deuxième ligne du tableau excel
while ($spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue()) { //Tant que la page excel est chargé, on garde en variable les valeurs des cellules.
    $telephone = $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(); //La variable telephone prend pour valeur la cellule B

    foreach ($files as $dir) { //Boucle sur chaque fichiers du dossier
        $img = $dir;
        $size = getimagesize($img);    //Ajout de la taille du fichier à la variable "size"
        $newtext = substr($img, 8); //Découpe le chemin et le nom des fichiers pour séléctionner uniquement le nom du fichier
        $part = explode('-', $newtext); // Découpage du nom de l'image par des "-"

        if ($part[1] == $telephone) { // si segment 2 du nom = Cellule B du tableau alors x et y prennent pour valeur C et D
            $y = $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(); //La variable hauteur prend pour valeur la cellule C
            $x = $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(); //La variale largeur prend pour valeur la cellule D
            $YPX = $y * 4;
            $XPX = $x * 4;
            $pdf->AddPage(); //Ajout d'une page sur le PDF
            $pdf->SetFont('Arial', '', 12); // Paramètrage de la police d'écriture

            $image = fct_redim_image($XPX,$YPX,'','redimImg',$dir,$img);

            $inv0 = mb_strrev($part[0]);
            $inv1 = mb_strrev($part[1]);
            $inv2 = mb_strrev($part[2]);
        }
    }
}

///////////// Fonctions ////////////////////

function mb_strrev($str) // Fonction pour inversement des caractères
{
    $r = '';
    for ($i = mb_strlen($str); $i >= 0; $i--) {
        $r .= mb_substr($str, $i, 1);
    }
    return $r;
}
// --------------------------------------------------------------------------------------------------
// fonction de REDIMENSIONNEMENT physique "proportionnel" et ENREGISTREMENT
// --------------------------------------------------------------------------------------------------
// retourne : 1 (vrai) si le redimensionnement et l enregistrement ont bien eu lieu, sinon rien (false)
// --------------------------------------------------------------------------------------------------
// La FONCTION : fct_redim_image($Wmax, $Hmax, $rep_Dst, $img_Dst, $rep_Src, $img_Src)
// Les paramètres :
// - $Wmax : LARGEUR maxi finale ----> ou 0 : largeur libre
// - $Hmax : HAUTEUR maxi finale ----> ou 0 : hauteur libre
// - $rep_Dst : répertoire de l image de Destination (déprotégé) ----> ou '' : même répertoire
// il faut s'assurer que les droits en écriture ont été donnés au dossier (chmod ou via logiciel FTP)
// - $img_Dst : NOM de l image de Destination ----> ou '' : même nom que l image Src
// - $rep_Src : répertoire de l image Source (déprotégé)
// - $img_Src : NOM de l image Source
// --------------------------------------------------------------------------------------------------
// si $rep_Dst = ''   : $rep_Dst=$rep_Src (même répertoire)
// si $img_Dst = '' : $img_Dst=$img_Src (même nom)
// Attention : si $rep_Dst='' ET $img_Dst='' : on écrase (remplace) l image source ($img_Src) !
// NB : $img_Dst et $img_Src doivent avoir la même extension (même type mime) !
// --------------------------------------------------------------------------------------------------
// 3 options :
// A- $Wmax != 0 et $Hmax != 0 : a LARGEUR maxi ET HAUTEUR maxi fixes
// B- si $Wmax = 0 : image finale a LARGEUR maxi fixe (hauteur libre)
// C- si $Hmax = 0 : image finale a HAUTEUR maxi fixe (largeur libre)
// --------------------------------------------------------------------------------------------------
// Extensions acceptees (traitees ici) : .jpg , .jpeg , .png
// Pour ajouter d autres extensions : voir la bibliothèque GD ou ImageMagick
// (GD) NE FONCTIONNE PAS avec les GIF ANIMES ou a fond transparent !
// --------------------------------------------------------------------------------------------------
// UTILISATION (exemple) :
// $redimOK = fct_redim_image(120,80,'reppicto/','monpicto.jpg','repimage/','monimage.jpg');
// if ($redimOK == 1) { echo 'Redimensionnement OK !';  }
// --------------------------------------------------------------------------------------------------
function fct_redim_image($Wmax, $Hmax, $rep_Dst, $img_Dst, $rep_Src, $img_Src) {
  // ------------------------------------------------------------------
 $condition = 0;
  // Si certains paramètres ont pour valeur '' :
   if ($rep_Dst == '') { $rep_Dst = $rep_Src; }  // (meme repertoire)
   if ($img_Dst == '') { $img_Dst = $img_Src; }  // (meme nom)
   if ($Wmax == '') { $Wmax = 0; }
   if ($Hmax == '') { $Hmax = 0; }
  // ------------------------------------------------------------------
  // si le fichier existe dans le répertoire, on continue...
 if (file_exists($rep_Src.$img_Src) && ($Wmax!=0 || $Hmax!=0)) { 
    // ----------------------------------------------------------------
    // extensions acceptées : 
   $ExtfichierOK = '" jpg jpeg png"';  // (l espace avant jpg est important)
    // extension
   $tabimage = explode('.',$img_Src);
   $extension = $tabimage[sizeof($tabimage)-1];  // dernier element
   $extension = strtolower($extension);  // on met en minuscule
    // ----------------------------------------------------------------
    // extension OK ? on continue ...
   if (strpos($ExtfichierOK,$extension) != '') {
       // -------------------------------------------------------------
       // récupération des dimensions de l image Src
      $size = getimagesize($rep_Src.$img_Src);
      $W_Src = $size[0];  // largeur
      $H_Src = $size[1];  // hauteur
       // -------------------------------------------------------------
       // condition de redimensionnement et dimensions de l image finale
       // -------------------------------------------------------------
       // A- LARGEUR ET HAUTEUR maxi fixes
      if ($Wmax != 0 && $Hmax != 0) {
         $ratiox = $W_Src / $Wmax;  // ratio en largeur
         $ratioy = $H_Src / $Hmax;  // ratio en hauteur
         $ratio = max($ratiox,$ratioy);  // le plus grand
         $W = $W_Src/$ratio;
         $H = $H_Src/$ratio;   
         $condition = ($W_Src>$W) || ($W_Src>$H);  // 1 si vrai (true)
      }
       // -------------------------------------------------------------
       // B- LARGEUR maxi fixe
      if ($Hmax != 0 && $Wmax == 0) {
         $H = $Hmax;
         $W = $H * ($W_Src / $H_Src);
         $condition = $H_Src > $Hmax;  // 1 si vrai (true)
      }
       // -------------------------------------------------------------
       // C- HAUTEUR maxi fixe
      if ($Wmax != 0 && $Hmax == 0) {
         $W = $Wmax;
         $H = $W * ($H_Src / $W_Src);         
         $condition = $W_Src > $Wmax;  // 1 si vrai (true)
      }
       // -------------------------------------------------------------
       // on REDIMENSIONNE si la condition est vraie
       // -------------------------------------------------------------
      if ($condition == 1) {
          // création de la ressource-image"Src" en fonction de l extension
          // et on crée une ressource-image"Dst" vide aux dimensions finales
         switch($extension) {
         case 'jpg':
         case 'jpeg':
           $Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src);
           $Ress_Dst = ImageCreateTrueColor($W,$H);
           break;
         case 'png':
           $Ress_Src = imagecreatefrompng($rep_Src.$img_Src);
           $Ress_Dst = ImageCreateTrueColor($W,$H);
            // fond transparent (pour les png avec transparence)
           imagesavealpha($Ress_Dst, true);
           $trans_color = imagecolorallocatealpha($Ress_Dst, 0, 0, 0, 127);
           imagefill($Ress_Dst, 0, 0, $trans_color);
           break;
         }
          // ----------------------------------------------------------
          // REDIMENSIONNEMENT (copie, redimensionne, ré-echantillonne)
         ImageCopyResampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src); 
          // ----------------------------------------------------------
          // ENREGISTREMENT dans le répertoire (avec la fonction appropriée)
         switch ($extension) { 
         case 'jpg':
         case 'jpeg':
           ImageJpeg ($Ress_Dst, $rep_Dst.$img_Dst);
           break;
         case 'png':
           imagepng ($Ress_Dst, $rep_Dst.$img_Dst);
           break;
         }
          // ----------------------------------------------------------
          // libération des ressources-image
         imagedestroy ($Ress_Src);
         imagedestroy ($Ress_Dst);
      }
       // -------------------------------------------------------------
   }
 }
// --------------------------------------------------------------------------------------------------
  // retourne : 1 (vrai) si le redimensionnement et l enregistrement ont bien eu lieu, sinon rien (false)
  // si le fichier a bien été créé
 if ($condition == 1 && file_exists($rep_Dst.$img_Dst)) { return true; }
 else { return false; }
}
// --------------------------------------------------------------------------------------------------
?> 