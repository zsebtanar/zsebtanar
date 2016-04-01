<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function DrawLine($x1, $y1, $x2, $y2, $color='black', $width=1) {

  $svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="'.$width.'" />';

  return $svg;
}

function DrawText($x, $y, $text, $fontsize=10, $color='black', $transform='') {

  $svg = '<text font-size="'.$fontsize.'" x="'.$x.'" y="'.$y.'" fill="'.$color.'" transform="'.$transform.'">'.$text.'</text>';

  return $svg;
}

function DrawCircle($cx, $cy, $r, $color1='black', $width=1, $color2='none') {

  $svg = '<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" stroke="'.$color1.'" stroke-width="'.$width.'" fill="'.$color2.'" />';

  return $svg;
}

function DrawPath($x1, $y1, $x2, $y2, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=0) {

  $svg = '<g fill="'.$color2.'" stroke="'.$color1.'" stroke-width="'.$width.'">
  			<path stroke-dasharray="'.$dasharray1.','.$dasharray2.'" d="M'.$x1.' '.$y1.' l'.strval($x2-$x1).' '.strval($y2-$y1).'" />
  		</g>';

  return $svg;
}

// Draws an arc between P1, P2, P3 (P1 is the center)
function DrawArc($x1, $y1, $x2, $y2, $x3, $y3, $radius, $modx=0, $mody=0, $text=NULL) {

  // Draw arc
  $P12 = sqrt(pow($y2-$y1,2) + pow($x2-$x1,2));
  $P13 = sqrt(pow($y3-$y1,2) + pow($x3-$x1,2));
  $P23 = sqrt(pow($y3-$y2,2) + pow($x3-$x2,2));

  $xx2 = $x1 + ($x2 - $x1)/$P12*$radius;
  $yy2 = $y1 + ($y2 - $y1)/$P12*$radius;

  $xx3 = $x1 + ($x3 - $x1)/$P13*$radius;
  $yy3 = $y1 + ($y3 - $y1)/$P13*$radius;

  $svg = '<path stroke="black" fill="none" d="M'.$xx2.','.$yy2.' A'.$radius.','.$radius.' 0 0,0 '.$xx3.','.$yy3.'" />';

  // Draw text
  if ($text) {
    $cx = ($xx2 + $xx3) / 2;
    $cy = ($yy2 + $yy3) / 2;

    $P1C = sqrt(pow($cy-$y1,2) + pow($cx-$x1,2));

    $ccx = $x1 + ($cx - $x1)/$P1C*($radius+$modx);
    $ccy = $y1 + ($cy - $y1)/$P1C*($radius+$mody);
    $svg .= '<text font-size="10" x="'.$ccx.'" y="'.$ccy.'" fill="black">$'.$text.'°$</text>';
  }

  return $svg;
}
?>