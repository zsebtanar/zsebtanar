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
?>