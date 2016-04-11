<?php

// Auxiliary function to generate images
if (isset($_REQUEST['function'])) {
  if ($_REQUEST['function']) {
    $function = $_REQUEST['function'];
    $function();
  }
}

// Convert binary to decimal (with fingers) - generate image
function binary_finger()
{
  $utvonal = $_REQUEST['path'];
  $szam = $_REQUEST['num'];

  header('Content-Type: image/png');
  $szamjegyek = str_split($szam);

  if (strlen($szam) > 5) {
    $fajl1 = '';
    for ($i=5; $i < 10; $i++) { 
      if ($i < strlen($szam)) {
        $fajl1 = $fajl1.$szamjegyek[strlen($szam)-$i-1];
      } else {
        $fajl1 = $fajl1.'0';
      }
    }
  }

  $fajl2 = '';
  for ($i=0; $i < 5; $i++) { 
    if ($i < strlen($szam)) {
      $fajl2 = $szamjegyek[strlen($szam)-$i-1].$fajl2;
    } else {
      $fajl2 = '0'.$fajl2;
    }
  }

  $terkoz = 10;
  $magassag = 72;
  $szelesseg = 80;
  if (strlen($szam) > 5) {
    $vaszonszelesseg = $szelesseg*2+ $terkoz;
    $kezdopont = $szelesseg+$terkoz;
  } else {
    $vaszonszelesseg = 80;
    $kezdopont = 0;
  }

  $image = imagecreate($vaszonszelesseg, $magassag) or die('Nem sikerült képet létrehozni');
  $hatter = imagecolorallocate($image, 0, 0, 0);
  imagecolortransparent($image, $hatter);
  imagealphablending($image, false);

  if (strlen($szam) > 5) {
    $kep1 = imagecreatefrompng($utvonal.'/'.'png/'.$fajl1.'.png');
    imageflip($kep1, IMG_FLIP_HORIZONTAL);
    imagecopy($image, $kep1, 0, 0, 0, 0, $szelesseg, $magassag);
  }

  $kep2 = imagecreatefrompng($utvonal.'/'.'png/'.$fajl2.'.png');
  imagecopy($image, $kep2, $kezdopont, 0, 0, 0, $szelesseg, $magassag);

  imagepng($image);
  imagedestroy($image);

  return;
}

?>