<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function DrawLine($x1, $y1, $x2, $y2, $color='black', $width=1) {

  $svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="'.$width.'" />';

  return $svg;
}

function DrawText($x, $y, $text, $fontsize=10, $color='black', $alpha=0) {

  $svg = '<text font-size="'.$fontsize.'" x="'.$x.'" y="'.$y.'" fill="'.$color.'" transform="rotate('.$alpha.' '.$x.','.$y.')">'.$text.'</text>';

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

function DrawVector($Ax, $Ay, $Bx, $By, $color='black', $arrow_width=5, $width=1, $angle=45) {

  $svg = DrawLine($Ax, $Ay, $Bx, $By, $color, $width);

  list($C1x, $C1y) = Rotate($Bx, $By, $Ax, $By-($Ay-$By), $angle);
  list($C2x, $C2y) = LinePoint($Bx, $By, $C1x, $C1y, $arrow_width);
  $svg .= DrawLine($Bx, $By, $C2x, $C2y, $color, $width);

  list($D1x, $D1y) = Rotate($Bx, $By, $Ax, $By-($Ay-$By), -$angle);
  list($D2x, $D2y) = LinePoint($Bx, $By, $D1x, $D1y, $arrow_width);
  $svg .= DrawLine($Bx, $By, $D2x, $D2y, $color, $width);

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

// Calculates intersection between line1 containing A and B, and line2 containing C perpendicular to line1.
function PerpendicularIntersect($Ax, $Ay, $Bx, $By, $Cx, $Cy) {

  // Vector of line1
  $Vx = $Bx - $Ax;
  $Vy = $By - $Ay;

  // [Nx, Ny] = [-Vy, Vx]                  <- normal vector of line1 = vector of line2

  // (Px - Ax)/(Py - Ay) = Vx/Vy           <- P is on line1
  // (Px - Cx)/(Py - Cy) = Nx/Ny = -Vy/Vx  <- P is on line2

  // Px = Vx/Vy * (Py - Ay) + Ax
  // Px = -Vy/Vx * (Py - Cy) + Cx

  // Vx/Vy * (Py - Ay) + Ax = -Vy/Vx * (Py - Cy) + Cx
  // Py * (Vx/Vy + Vy/Vx) = Vy/Vx * Cy + Vx/Vy * Ay + Cx - Ax
  // Py = (Vy/Vx * Cy + Vx/Vy * Ay + Cx - Ax) / (Vx/Vy + Vy/Vx)

  $Py = ($Vy/$Vx * $Cy + $Vx/$Vy * $Ay + $Cx - $Ax) / ($Vx/$Vy + $Vy/$Vx);
  $Px = $Vx/$Vy * ($Py - $Ay) + $Ax;

  return array($Px, $Py);
}

// Calculates third point of triangle given by two points and angles
function Triangle($Ax, $Ay, $Bx, $By, $alpha, $beta) {

  // Rotate AB vector around A with alpha
  list($BBx, $BBy) = Rotate($Ax, $Ay, $Bx, $By, $alpha);

  // Rotate AB vector around B with -beta
  list($AAx, $AAy) = Rotate($Bx, $By, $Ax, $Ay, -$beta);

  list($Px, $Py) = IntersectLines($Ax, $Ay, $BBx, $BBy, $Bx, $By, $AAx, $AAy);

  return array($Px, $Py);
}

// Calculates intersect of two lines
function IntersectLines($A1x, $A1y, $A2x, $A2y, $B1x, $B1y, $B2x, $B2y) {

  // Vector of line1
  $Ax = $A1x - $A2x;
  $Ay = $A1y - $A2y;

  // Vector of line1
  $Bx = $B1x - $B2x;
  $By = $B1y - $B2y;

  // (Px - A1x)/(Py - A1y) = Ax/Ay <- P is on line1
  // (Px - B1x)/(Py - B1y) = Bx/By <- P is on line2

  // Px = Ax/Ay * (Py - A1y) + A1x
  // Px = Bx/By * (Py - B1y) + B1x

  // Ax/Ay * (Py - A1y) + A1x = Bx/By * (Py - B1y) + B1x
  // Py * (Ax/Ay - Bx/By) = - Bx/By * B1y + Ax/Ay * A1y + B1x - A1x
  // Py = (- Bx/By * B1y + Ax/Ay * A1y + B1x - A1x) / (Ax/Ay - Bx/By)

  $Py = (- $Bx/$By * $B1y + $Ax/$Ay * $A1y + $B1x - $A1x) / ($Ax/$Ay - $Bx/$By);
  $Px = $Ax/$Ay * ($Py - $A1y) + $A1x;

  return array($Px, $Py);
}

// Calculate point along line
function LinePoint($Ax, $Ay, $Bx, $By, $length) {

  $Vx = ($Bx - $Ax);
  $Vy = ($By - $Ay);

  $Vlength = sqrt(pow($Vx,2) + pow($Vy,2));

  $Px = $Ax + $Vx / $Vlength * $length;
  $Py = $Ay + $Vy / $Vlength * $length;

  return array($Px, $Py);
}

// Translate point with length in direction
function Translate($Px, $Py, $length, $Ax, $Ay, $Bx, $By) {

  $Vx = ($Bx - $Ax);
  $Vy = ($By - $Ay);

  $Vlength = sqrt(pow($Vx,2) + pow($Vy,2));

  $Px += $Vx / $Vlength * $length;
  $Py += $Vy / $Vlength * $length;

  return array($Px, $Py);
}

// Rotate point P around center C with angle alpha
function Rotate($Cx, $Cy, $Px, $Py, $alpha) {

  $alpha = ToRad($alpha);

  $PPx = $Cx + cos($alpha)*($Px - $Cx) - sin($alpha)*($Py - $Cy);
  $PPy = $Cy - sin($alpha)*($Px - $Cx) - cos($alpha)*($Py - $Cy); 

  return array($PPx, $PPy);
}

// Length of vector
function Length($Ax, $Ay, $Bx, $By) {

  $Vx = ($Bx - $Ax);
  $Vy = ($By - $Ay);

  $length = sqrt(pow($Vx,2) + pow($Vy,2));

  return $length;
}
?>