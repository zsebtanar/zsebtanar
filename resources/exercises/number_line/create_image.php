<?php

// Auxiliary function to generate images
if (isset($_REQUEST['function'])) {
  if ($_REQUEST['function']) {
    $function = $_REQUEST['function'];
    $function();
  }
}

// Calculate stepsize of number line - generate image
function num_line_stepsize()
{
  header('Content-Type: image/png');

  $w = 400;
  $h = 50;
  $hspace = 10;
  $vspace = 30;

  $pontok = $_GET['pontok'];
  $poz1 = $_GET['poz1'];
  $poz2 = $_GET['poz2'];
  $ertek1 = $_GET['ertek1'];
  $ertek2 = $_GET['ertek2'];

  $img = imagecreatetruecolor($w, $h) or die('Nem sikerült képet létrehozni');

  $white = imagecolorallocate($img, 255, 255, 255);
  $black = imagecolorallocate($img, 0, 0, 0);

  imagefill($img, 0, 0, $white);
  imagecolortransparent($img, $white);

  // nyíl
  imageline($img, $hspace, $h-$vspace, $w-$hspace, $h-$vspace, $black);
  imageline($img, $w-$hspace, $h-$vspace, $w-$hspace-5, $h-$vspace+5, $black);
  imageline($img, $w-$hspace, $h-$vspace, $w-$hspace-5, $h-$vspace-5, $black);

  // beosztasok
  $osztaskoz = ($w - 2*$hspace - 40)/($pontok-1);
  $kezdoPoz = $hspace+20;
  // imagestring($img, 4, 0, 0, $pontok, $black);
  for ($i=0; $i < $pontok; $i++) { 
    $osztoPoz = $kezdoPoz+$i*$osztaskoz;
    imageline($img, $osztoPoz, $h-$vspace+5, $osztoPoz, $h-$vspace-5, $black);
    if ($poz1 == $i+1) {
      imagestring($img, 4, $osztoPoz - strlen($ertek1)*4, $h-20, $ertek1, $black);
    }
    if ($poz2 == $i+1) {
      imagestring($img, 4, $osztoPoz - strlen($ertek2)*4, $h-20, $ertek2, $black);
    }
  }

  imagepng($img);
  imagedestroy($img);

  return;
}

// Calculate position of number on number line - generate image
function num_line_position()
{
  header('Content-Type: image/png');

  $w = 400;
  $h = 50;
  $hspace = 10;
  $vspace = 30;

  $pontok = $_GET['pontok'];
  $poz1 = $_GET['poz1'];
  $poz2 = $_GET['poz2'];
  $poz3 = $_GET['poz3'];
  $ertek1 = $_GET['ertek1'];
  $ertek2 = $_GET['ertek2'];

  $img = imagecreatetruecolor($w, $h) or die('Nem sikerült képet létrehozni');

  $white = imagecolorallocate($img, 255, 255, 255);
  $black = imagecolorallocate($img, 0, 0, 0);

  imagefill($img, 0, 0, $white);
  imagecolortransparent($img, $white);

  // nyíl
  imageline($img, $hspace, $h-$vspace, $w-$hspace, $h-$vspace, $black);
  imageline($img, $w-$hspace, $h-$vspace, $w-$hspace-5, $h-$vspace+5, $black);
  imageline($img, $w-$hspace, $h-$vspace, $w-$hspace-5, $h-$vspace-5, $black);

  // beosztasok
  $osztaskoz = ($w - 2*$hspace - 40)/($pontok-1);
  $kezdoPoz = $hspace+20;
  // imagestring($img, 4, 0, 0, $pontok, $black);
  for ($i=0; $i < $pontok; $i++) { 
    $osztoPoz = $kezdoPoz+$i*$osztaskoz;
    imageline($img, $osztoPoz, $h-$vspace+5, $osztoPoz, $h-$vspace-5, $black);
    if ($poz1 == $i+1) {
      imagestring($img, 4, $osztoPoz - strlen($ertek1)*4, $h-20, $ertek1, $black);
    }
    if ($poz2 == $i+1) {
      imagestring($img, 4, $osztoPoz - strlen($ertek2)*4, $h-20, $ertek2, $black);
    }
    if ($poz3 == $i+1) {
      imagestring($img, 4, $osztoPoz-4, $h-20, '?', $black);
    }
  }

  imagepng($img);
  imagedestroy($img);

  return;
}

// Define operation shows on number line - generate image
function num_line_operation()
{
  header('Content-Type: image/png');

  $w = 400;
  $hspace = 10;
  $vspace = 30;

  $pontok = $_GET['pontok'];
  $poz1 = $_GET['poz1'];
  $poz2 = $_GET['poz2'];
  $ertek1 = $_GET['ertek1'];
  $ertek2 = $_GET['ertek2'];
  $szinese = $_GET['szinese'];
  $muveletek = $_GET['muveletek'];

  // milyen nagy legyen a kép?
  $muveletek = explode('_',$muveletek);
  $ivatmero = 0;
  foreach ($muveletek as $key => $value) {
    if ($key < count($muveletek) - 1 && $ivatmero < abs($value - $muveletek[$key+1])) {
      $ivatmero = abs($value - $muveletek[$key+1]);
    }
  }
  $osztaskoz = ($w - 2*$hspace - 40)/($pontok-1);
  $h = 1.5*$vspace + $ivatmero/2*$osztaskoz;

  // kep
  $img = imagecreatetruecolor($w, $h) or die('Nem sikerült képet létrehozni');

  $white = imagecolorallocate($img, 255, 255, 255);
  $black = imagecolorallocate($img, 0, 0, 0);
  $red = imagecolorallocate($img, 255, 0, 0);
  $green = imagecolorallocate($img, 51, 204, 51);
  $blue = imagecolorallocate($img, 0, 41, 163);

  imagefill($img, 0, 0, $white);
  imagecolortransparent($img, $white);

  // nyíl
  imageline($img, $hspace, $h-$vspace, $w-$hspace, $h-$vspace, $black);
  imageline($img, $w-$hspace, $h-$vspace, $w-$hspace-5, $h-$vspace+5, $black);
  imageline($img, $w-$hspace, $h-$vspace, $w-$hspace-5, $h-$vspace-5, $black);

  // beosztasok
  $osztaskoz = ($w - 2*$hspace - 40)/($pontok-1);
  $kezdoPoz = $hspace+20;
  for ($i=0; $i < $pontok; $i++) { 
    $osztoPoz = $kezdoPoz+$i*$osztaskoz;
    imageline($img, $osztoPoz, $h-$vspace+5, $osztoPoz, $h-$vspace-5, $black);
    if ($poz1 == $i+1) {
      imagestring($img, 4, $osztoPoz - strlen($ertek1)*4, $h-20, $ertek1, $black);
    }
    if ($poz2 == $i+1) {
      imagestring($img, 4, $osztoPoz - strlen($ertek2)*4, $h-20, $ertek2, $black);
    }
  }

  // ivek
  foreach ($muveletek as $key => $value) {
    if ($key < count($muveletek) - 1) {
      $width = abs($value-$muveletek[$key+1])*$osztaskoz;
      $cx = $kezdoPoz+(($value+$muveletek[$key+1])/2-1)*$osztaskoz;
      if ($szinese == 1) {
        if ($value < $muveletek[$key+1]) {
          $szin = $blue;
        } else {
          $szin = $green;
        }
      } else {
        $szin = $black;
      }
      imagearc($img, $cx, $h-$vspace, $width, $width, 180, 0, $szin);
    }
  }

  // kor
  $cx = $kezdoPoz+($muveletek[0]-1)*$osztaskoz;
  imagefilledarc($img, $cx, $h-$vspace, 10, 10, 0, 360, $red, IMG_ARC_PIE);

  imagepng($img);
  imagedestroy($img);

  return;
}

?>