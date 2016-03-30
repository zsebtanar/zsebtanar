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
?>