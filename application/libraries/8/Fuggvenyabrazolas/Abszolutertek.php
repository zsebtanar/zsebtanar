<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Abszolutertek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		// Df = [x1, x2]
		$x1 = -rand(3, max(5,$level));
		$x2 = rand(3, max(5,$level));

		$x1 = -5;
		$x2 = 3;

		// f(x) = |x+a|+b
		$a = rand(-$x2+1,-$x1-1); // avoid excluding minimum
		$b = rand(-$level, $level);

		if ($level <= 3) {
			list($a, $b) = (rand(1,2) == 1 ? [0,$b] : [$a,0]);
		}
		
		// Range = [y1, y2]
		$y1 = $b;
		$y2 = max(abs($x1+$a), abs($x2+$a)) + $b;
		
		$question = 'Ábrázolja a $['.$x1.';'.$x2.']$ intervallumon értelmezett $x\rightarrow'.$this->AbsFunctionText($a,$b).'$ függvényt! Mekkor a függvény értékkészlete?';		;

		$hints = $this->Hints($x1, $x2, $a, $b, $y1, $y2);

		$correct = array($y1, $y2);
		$solution = '$['.$y1.';'.$y2.']$';
		$type = 'range';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> $type
		);
	}

	function AbsFunctionText($a,$b) {
		if ($a == 0) {
			if ($b == 0) {
				return '|x|';
			} else {
				return '|x|'.($b>0 ? '+'.$b : $b);
			}
		} else {
			if ($b == 0) {
				return '|x'.($a>0 ? '+'.$a : $a).'|';
			} else {
				return '|x'.($a>0 ? '+'.$a : $a).'|'.($b>0 ? '+'.$b : $b);
			}
		}
	}

	function Hints($x1, $x2, $a, $b, $y1, $y2) {

		$hints[][] = 'Először ábrázoljuk az $x\rightarrow'.$this->AbsFunctionText(0,0).'$ függvényt a $['.$x1.';'.$x2.']$ intervallumon!'.$this->AbsFunctionGraph($x1, $x2, $a, $b, 0);
		if ($a != 0) {
			$hints[][] = 'Most ábrázoljuk az $x\rightarrow'.$this->AbsFunctionText($a,0).'$ függvényt a $['.$x1.';'.$x2.']$ intervallumon! Ezt úgy kapjuk meg, ha az előző függvényt $'.abs($a).'$-'.With($a).' '.($a>0 ? 'balra' : 'jobbra').' toljuk az $x$ tengely mentén:'.$this->AbsFunctionGraph($x1, $x2, $a, $b, 1);
		}
		if ($b != 0) {
			$hints[][] = 'Most ábrázoljuk az $x\rightarrow'.$this->AbsFunctionText($a,$b).'$ függvényt a $['.$x1.';'.$x2.']$ intervallumon! Ezt úgy kapjuk meg, ha az előző függvényt $'.abs($b).'$-'.With($b).' '.($b>0 ? 'fölfelé' : 'lefelé').' toljuk az $y$ tengely mentén:'.$this->AbsFunctionGraph($x1, $x2, $a, $b, 2);
		}
		$hints[][] = 'Az ábráról könnyen leolvasható, hogy a függvény értékkészlete $['.$y1.';'.$y2.']$:'.$this->AbsFunctionGraph($x1, $x2, $a, $b, 3);

		return $hints;
	}

	// Draws function f(x) = |x+a|+b on [x1,x2]
	function AbsFunctionGraph($x1, $x2, $a, $b, $progress=0) {

		$bottom = min(abs($x1+$a), abs($x2+$a), 0) + $b;
		$top 	= max(abs($x1+$a), abs($x2+$a)) + $b;
		$left 	= $x1;
		$right 	= $x2;

		$linesx = $top - $bottom + 3;
		$linesy = $right - $left + 3;

		$unit 	= (400 / $linesy < 40 ? 40 : 400 / $linesy);
		$width 	= $unit * $linesy;
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

		// Coordinates for |x|
		list($X1A, $Y1A) = $this->Coordinates2($x1, abs($x1), $height, $left, $bottom, $unit);
		list($X2A, $Y2A) = $this->Coordinates2(0, 0, $height, $left, $bottom, $unit);
		list($X3A, $Y3A) = $this->Coordinates2($x2, abs($x2), $height, $left, $bottom, $unit);

		// Coordinates for |x+a|
		list($X1B, $Y1B) = $this->Coordinates2($x1, abs($x1+$a), $height, $left, $bottom, $unit);
		list($X2B, $Y2B) = $this->Coordinates2(-$a, 0, $height, $left, $bottom, $unit);
		list($X3B, $Y3B) = $this->Coordinates2($x2, abs($x2+$a), $height, $left, $bottom, $unit);

		// Coordinates for |x+a|+b
		list($X1C, $Y1C) = $this->Coordinates2($x1, abs($x1+$a)+$b, $height, $left, $bottom, $unit);
		list($X2C, $Y2C) = $this->Coordinates2(-$a, $b, $height, $left, $bottom, $unit);
		list($X3C, $Y3C) = $this->Coordinates2($x2, abs($x2+$a)+$b, $height, $left, $bottom, $unit);

		if ($progress == 0) {

			// End points for |x|
			$svg .= DrawCircle($X1A, $Y1A, 3, 'red', 1, 'red');
			$svg .= DrawCircle($X3A, $Y3A, 3, 'red', 1, 'red');

			// Lines for |x|
			$svg .= DrawLine($X1A, $Y1A, $X2A, $Y2A, 'red', 2);
			$svg .= DrawLine($X2A, $Y2A, $X3A, $Y3A, 'red', 2);

		} elseif ($progress == 1) {

			// End points for |x|
			$svg .= DrawCircle($X1A, $Y1A, 3, 'blue', 1, 'blue');
			$svg .= DrawCircle($X3A, $Y3A, 3, 'blue', 1, 'blue');

			// Lines for |x|
			$svg .= DrawPath($X1A, $Y1A, $X2A, $Y2A, $color1='blue', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);
			$svg .= DrawPath($X2A, $Y2A, $X3A, $Y3A, $color1='blue', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);

			// Vector from old to new
			$svg .= DrawVector($X2A, $Y2A, $X2B, $Y2B, 'green', 10, 2, 30);

			// End points for |x+a|
			$svg .= DrawCircle($X1B, $Y1B, 3, 'red', 1, 'red');
			$svg .= DrawCircle($X3B, $Y3B, 3, 'red', 1, 'red');

			// Lines for |x+a|
			$svg .= DrawLine($X1B, $Y1B, $X2B, $Y2B, 'red', 2);
			$svg .= DrawLine($X2B, $Y2B, $X3B, $Y3B, 'red', 2);

		} elseif ($progress == 2) {

			// End points for |x+a|
			$svg .= DrawCircle($X1B, $Y1B, 3, 'blue', 1, 'blue');
			$svg .= DrawCircle($X3B, $Y3B, 3, 'blue', 1, 'blue');

			// Lines for |x+a|
			$svg .= DrawPath($X1B, $Y1B, $X2B, $Y2B, $color1='blue', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);
			$svg .= DrawPath($X2B, $Y2B, $X3B, $Y3B, $color1='blue', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);

			// Vector from old to new
			$svg .= DrawVector($X2B, $Y2B, $X2C, $Y2C, 'green', 10, 2, 30);

			// End points for |x+a|+b
			$svg .= DrawCircle($X1C, $Y1C, 3, 'red', 1, 'red');
			$svg .= DrawCircle($X3C, $Y3C, 3, 'red', 1, 'red');

			// Lines for |x+a|+b
			$svg .= DrawLine($X1C, $Y1C, $X2C, $Y2C, 'red', 2);
			$svg .= DrawLine($X2C, $Y2C, $X3C, $Y3C, 'red', 2);

		} else {

			// Lines between end points and y axis
			if (abs($x1+$a) > abs($x2+$a)) {

				list($X1D, $Y1D) = $this->Coordinates2(0, abs($x1+$a)+$b, $height, $left, $bottom, $unit);
				$svg .= DrawPath($X1C, $Y1C, $X1D, $Y1D, $color1='green', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);

			} else {

				list($X3D, $Y3D) = $this->Coordinates2(0, abs($x2+$a)+$b, $height, $left, $bottom, $unit);
				$svg .= DrawPath($X3C, $Y3C, $X3D, $Y3D, $color1='green', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);
			}

			list($X2D, $Y2D) = $this->Coordinates2(0, $b, $height, $left, $bottom, $unit);
			$svg .= DrawPath($X2C, $Y2C, $X2D, $Y2D, $color1='green', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);

			// End points for |x+a|+b
			$svg .= DrawCircle($X1C, $Y1C, 3, 'red', 1, 'red');
			$svg .= DrawCircle($X3C, $Y3C, 3, 'red', 1, 'red');

			// Lines for |x+a|+b
			$svg .= DrawLine($X1C, $Y1C, $X2C, $Y2C, 'red', 2);
			$svg .= DrawLine($X2C, $Y2C, $X3C, $Y3C, 'red', 2);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Coordinates2($Px, $Py, $height, $left, $bottom, $unit) {

		$PPx = (1.5 - ($left - $Px))*$unit;
		$PPy = $height - (1.5 - ($bottom - $Py))*$unit;

		return array($PPx,$PPy);
	}
}

?>