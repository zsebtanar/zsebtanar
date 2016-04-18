<?php

// Auxiliary function to generate images
if (isset($_REQUEST['function'])) {
  if ($_REQUEST['function']) {
    $function = $_REQUEST['function'];
    $function();
  }
}

// Define fraction from rectangle - generate image
function fraction_rectangle()
{
  header('Content-Type: image/png');

  $row = $_REQUEST['row'];
  $col = $_REQUEST['col'];
  $num = $_REQUEST['num'];

  $a = 20;

  $w = $col * $a+1;
  $h = $row * $a+1;

  $img = imagecreatetruecolor($w, $h) or die('Nem sikerült képet létrehozni');

  $white = imagecolorallocate($img, 255, 255, 255);
  $black = imagecolorallocate($img, 0, 0, 0);
  $blue = imagecolorallocate($img, 0, 0, 255);

  imagefill($img, 0, 0, $white);
  // imagecolortransparent($img, $white);

  // colour squares
  $n = 0;
  for ($i=0; $i < $col; $i++) { 
    for ($j=0; $j < $row; $j++) { 
      if ($n < $num) {
        imagefilledrectangle($img, $i*$a, $j*$a, ($i+1)*$a, ($j+1)*$a, $blue);
        $n++;
      } else {
        break;
      }
    }
  }

  // grid
  for ($i=0; $i <= $row; $i++) { 
    imageline($img, 0, $i*$a, $w, $i*$a, $black);
  }
  for ($i=0; $i <= $col; $i++) { 
    imageline($img, $i*$a, 0, $i*$a, $h, $black);
  }

  imagepng($img);
  imagedestroy($img);

  return;
}

?>