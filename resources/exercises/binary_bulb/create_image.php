<?php

// Auxiliary function to generate images
if (isset($_REQUEST['function'])) {
  if ($_REQUEST['function']) {
    $function = $_REQUEST['function'];
    $function();
  }
}

// Convert binary to decimal (with bulbs) - generate image
function binary_bulb()
{
  $szam = $_REQUEST['num'];
  $path = $_REQUEST['path'];

  header('Content-Type: image/png');
  $szamjegyek = str_split($szam);
  $terkoz = 5;
  $szelesseg = (80+$terkoz)*strlen($szam)-$terkoz;
  $magassag = 126;

  $image = imagecreatetruecolor($szelesseg, $magassag) or die('Nem sikerült képet létrehozni');
  $hatter = imagecolorallocate($image, 0, 0, 0);
  imagecolortransparent($image, $hatter);
  imagealphablending($image, false);

  foreach ($szamjegyek as $key => $value) {
    if ($value == 1) {
      $fajl = $path.'/binary_bulb/fel.png';
    } else {
      $fajl = $path.'/binary_bulb/le.png';
    }
    $kep = imagecreatefrompng($fajl);
    imagecopy($image, $kep, $key*(80+$terkoz), 0, 0, 0, 80, 126);
  }

  imagesavealpha($image, false);
  imagepng($image);
  imagedestroy($image);

  return;
}

?>