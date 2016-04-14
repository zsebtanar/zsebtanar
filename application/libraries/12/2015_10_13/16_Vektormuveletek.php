<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 16_Vektormuveletek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$Cx = rand(-4,4);
		$Cy = rand(-4,4);

		$vx = pow(-1,rand(0,1)) * rand(1,3);
		$vy = pow(-1,rand(0,1)) * rand(1,3);

		$mult = rand(2,3);

		$Tx = $Cx + $vx;
		$Ty = $Cy + $vy;

		// $Cx = 2;
		// $Cy = -4;
		// $vx = 1;
		// $vy = -1;

		// $mult = 2;

		// $Cx = 3;
		// $Cy = -2;
		// $vx = 2;
		// $vy = 1;

		// $mult = 3;
		
		$question = 'A $PRST$ rombusz középpontja a $K('.$Cx.';'.$Cy.')$ pont, egyik csúcspontja a $T('.$Tx.';'.$Ty.')$ pont. Tudjuk, hogy az $RT$ átló hossza '.($mult==2 ? 'fele' : 'harmada').' a $PS$ átló hosszának. Adja meg a $P$, az $R$ és az $S$ csúcsok koordinátáit!'.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 1);

		$correct = 2;
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($Cx, $Cy, $vx, $vy, $mult);

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}

	function Hints($Cx, $Cy, $vx, $vy, $mult) {

		list($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy) = $this->Coordinates1($Cx, $Cy, $vx, $vy, $mult);

		$hints[][] = 'Ábrázoljuk a $K('.$Cx.';'.$Cy.')$ pontot!'.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 0);
		$hints[][] = 'Ábrázoljuk a $T('.$Tx.';'.$Ty.')$ pontot!'.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 1);

		return $hints;
	}

	function Rhombus($Cx, $Cy, $vx, $vy, $mult, $progress=0) {

		$width 	= 400;

		list($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy) = $this->Coordinates1($Cx, $Cy, $vx, $vy, $mult);

		// print_r('T('.$Tx.';'.$Ty.')<br />');
		// print_r('R('.$Rx.';'.$Ry.')<br />');
		// print_r('S('.$Sx.';'.$Sy.')<br />');
		// print_r('P('.$Px.';'.$Py.')<br />');

		$bottom = min($Py, $Ry, $Sy, $Ty, 0);
		$top 	= max($Py, $Ry, $Sy, $Ty, 0);
		$left 	= min($Px, $Rx, $Sx, $Tx, 0);
		$right 	= max($Px, $Rx, $Sx, $Tx, 0);

		// print_r('bottom: '.$bottom.'<br />');
		// print_r('top: '.$top.'<br />');
		// print_r('left: '.$left.'<br />');		
		// print_r('right: '.$right.'<br />');

		$linesx = $top - $bottom + 3;
		$linesy = $right - $left + 3;

		$unit = $width / $linesy;
		if ($unit < 40) {
			$unit = 40;
			$width = $unit * $linesy;
		} 
		$height = $unit * $linesx;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		// Origo
		$Ox = (1.5 - $left)*$unit;
		$Oy = $height - (1.5 - $bottom)*$unit;
		if ($unit < 45) {
			$fontsize = 10;
		} elseif ($unit < 50) {
			$fontsize = 11;
		} else {
			$fontsize = 12;
		}

		// Guides
		for ($i=0; $i < $linesy; $i++) { 
			$x = (0.5+$i)*$unit;
			$svg .= DrawLine($x, 0, $x, $height, '#F2F2F2');
			$num = $i+$left-1;
			if ($num == 0) {
				$svg .= DrawText($Ox+$unit/3, $Oy+$unit/2, '$0$', $fontsize);
			} else {
				$svg .= DrawLine($x, $Oy-5, $x, $Oy+5, 'black', 2);
				$svg .= DrawText($x, $Oy+$unit/2, '$'.$num.'$', $fontsize);
			}
		}

		for ($i=0; $i < $linesx; $i++) { 
			$y = (0.5+$i)*$unit;
			$svg .= DrawLine(0, $y, $width, $y, '#F2F2F2');
			$num = $top-$i+1;
			if ($num != 0) {
				$svg .= DrawLine($Ox+5, $y, $Ox-5, $y, 'black', 2);
				if ($num <= 9 && $num > 0) {
					$shift = $unit/3;
				} elseif ($num > -10 || $num > 9) {
					$shift = $unit/2;
				} else {
					$shift = $unit/1.7;
				}
				$svg .= DrawText($Ox-$shift, $y+$unit/5, '$'.$num.'$', $fontsize);
			}
		}

		// Axes
		$svg .= DrawVector($Ox, $height, $Ox, 0, 'black', 10, 2);
		$svg .= DrawVector(0, $Oy, $width, $Oy, 'black', 10, 2);
		$svg .= DrawText($width-$unit/2, $Oy-$unit/3, '$x$', $fontsize*1.5);
		$svg .= DrawText($Ox+$unit/2, $unit/3, '$y$', $fontsize*1.5);

		// Calculate coordinates
		list($CCx, $CCy) = $this->Coordinates2($Cx, $Cy, $height, $left, $bottom, $unit);
		list($TTx, $TTy) = $this->Coordinates2($Tx, $Ty, $height, $left, $bottom, $unit);

		if ($progress == 0) {

			$svg .= DrawCircle($CCx, $CCy, 3, 'red', 1, 'red');
			$svg .= DrawPath($Ox, $CCy, $CCx, $CCy, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			$svg .= DrawPath($CCx, $CCy, $CCx, $Oy, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);

		} elseif ($progress == 1) {

			$svg .= DrawCircle($CCx, $CCy, 3, 'blue', 1, 'blue');
			$svg .= DrawCircle($TTx, $TTy, 3, 'red', 1, 'red');
			$svg .= DrawPath($Ox, $TTy, $TTx, $TTy, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			$svg .= DrawPath($TTx, $TTy, $TTx, $Oy, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Coordinates1($Cx, $Cy, $vx, $vy, $mult) {

		$Tx = $Cx + $vx;
		$Ty = $Cy + $vy;

		$Rx = $Cx - $vx;
		$Ry = $Cy - $vy;

		$Px = $Cx + $mult * $vx;
		$Py = $Cy - $mult * $vy;

		$Sx = $Cx - $mult * $vx;
		$Sy = $Cy + $mult * $vy;

		return array($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy);
	}

	function Coordinates2($Px, $Py, $height, $left, $bottom, $unit) {

		$PPx = (1.5 - ($left - $Px))*$unit;
		$PPy = $height - (1.5 - ($bottom - $Py))*$unit;

		return array($PPx,$PPy);
	}
}

?>