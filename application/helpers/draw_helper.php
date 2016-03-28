<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function DrawLine($x1, $y1, $x2, $y2, $color='black', $width=1) {

  $svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="'.$width.'" />';

  return $svg;
}

?>