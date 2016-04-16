<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rombusz {

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

		// $Cx = 0;
		// $Cy = 2;

		// $mult = 2;

		// $vx = 0;
		// $vy = 2;

		$Tx = $Cx + $vx;
		$Ty = $Cy + $vy;
		
		$question = 'A $PRST$ rombusz középpontja a $K('.$Cx.';'.$Cy.')$ pont, egyik csúcspontja a $T('.$Tx.';'.$Ty.')$ pont. Tudjuk, hogy az $RT$ átló hossza '.($mult==2 ? 'fele' : 'harmada').' a $PS$ átló hosszának. Adja meg a $P$, az $R$ és az $S$ csúcsok koordinátáit!'
			// .$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 4);
		;

		$hints = $this->Hints($Cx, $Cy, $vx, $vy, $mult);

		list($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy) = $this->Coordinates1($Cx, $Cy, $vx, $vy, $mult);

		$correct = array($Px, $Py, $Rx, $Ry, $Sx, $Sy);
		$solution = '<br />$P=('.$Px.';'.$Py.')$<br />'
			.'$R=('.$Rx.';'.$Ry.')$<br />'
			.'$S=('.$Sx.';'.$Sy.')$';
		$labels = ['$P$', '$R$', '$S$'];
		$type = 'coordinate';
		// $hints = [];

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'labels'	=> $labels,
			'hints'		=> $hints,
			'type'		=> $type
		);
	}

	function Hints($Cx, $Cy, $vx, $vy, $mult) {

		list($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy) = $this->Coordinates1($Cx, $Cy, $vx, $vy, $mult);

		$hints[][] = 'Ábrázoljuk a $K('.$Cx.';'.$Cy.')$ pontot!'.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 0);
		$hints[][] = 'Ábrázoljuk a $T('.$Tx.';'.$Ty.')$ pontot!'.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 1);
		$hints[][] = 'Az $R$ pontot úgy kapjuk meg, hogy a $T$ pontot tükrözzük a $K$ pontra:'
			.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 2);
		$hints[][] = 'Tudjuk, hogy az $RT$ átló hossza '.($mult==2 ? 'kétszer' : 'háromszor').' akkora, mint a $PS$ átló hosszának. Ezért a $P$ és az $S$ pontokat úgy kapjuk meg, hogy az $RT$ átlót elforgatjuk a $K$ körül $90°$-kal, és '.($mult==2 ? 'kétszeresére' : 'háromszorása').' nyújtjuk:'.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 3);
		$hints[][] = 'Tehát a koordináták:<br />'
			.'$P=($<span class="label label-success">$'.$Px.'$</span>$,$<span class="label label-success">$'.$Py.'$</span>$)$<br />'
			.'$R=($<span class="label label-success">$'.$Rx.'$</span>$,$<span class="label label-success">$'.$Ry.'$</span>$)$<br />'
			.'$S=($<span class="label label-success">$'.$Sx.'$</span>$,$<span class="label label-success">$'.$Sy.'$</span>$)$'
			.$this->Rhombus($Cx, $Cy, $vx, $vy, $mult, 4);

		return $hints;
	}

	function Rhombus($Cx, $Cy, $vx, $vy, $mult, $progress=0) {

		// Calculate coordinates (nodes)
		list($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy) = $this->Coordinates1($Cx, $Cy, $vx, $vy, $mult);

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

		$unit 	= (400 / $linesy < 40 ? 40 : 400 / $linesy);
		$width 	= $unit * $linesy;
		$height = $unit * $linesx;

		// Calculate coordinates (labels)
		list($C2x, $C2y) = $this->Coordinates3($Rx, $Ry, $Tx, $Ty, 'center');
		list($T2x, $T2y) = $this->Coordinates3($Cx, $Cy, $Tx, $Ty);
		list($R2x, $R2y) = $this->Coordinates3($Cx, $Cy, $Rx, $Ry);
		list($S2x, $S2y) = $this->Coordinates3($Cx, $Cy, $Sx, $Sy);
		list($P2x, $P2y) = $this->Coordinates3($Cx, $Cy, $Px, $Py);

		// Calculate coordinates on canvas (nodes)
		list($C3x, $C3y) = $this->Coordinates2($Cx, $Cy, $height, $left, $bottom, $unit);
		list($T3x, $T3y) = $this->Coordinates2($Tx, $Ty, $height, $left, $bottom, $unit);
		list($R3x, $R3y) = $this->Coordinates2($Rx, $Ry, $height, $left, $bottom, $unit);
		list($S3x, $S3y) = $this->Coordinates2($Sx, $Sy, $height, $left, $bottom, $unit);
		list($P3x, $P3y) = $this->Coordinates2($Px, $Py, $height, $left, $bottom, $unit);

		// Calculate coordinates on canvas (labels)
		list($C4x, $C4y) = $this->Coordinates2($C2x, $C2y, $height, $left, $bottom, $unit);
		list($T4x, $T4y) = $this->Coordinates2($T2x, $T2y, $height, $left, $bottom, $unit);
		list($R4x, $R4y) = $this->Coordinates2($R2x, $R2y, $height, $left, $bottom, $unit);
		list($S4x, $S4y) = $this->Coordinates2($S2x, $S2y, $height, $left, $bottom, $unit);
		list($P4x, $P4y) = $this->Coordinates2($P2x, $P2y, $height, $left, $bottom, $unit);

		// print_r('T('.$Tx.';'.$Ty.')<br />');
		// print_r('R('.$Rx.';'.$Ry.')<br />');
		// print_r('S('.$Sx.';'.$Sy.')<br />');
		// print_r('P('.$Px.';'.$Py.')<br />');

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

		if ($progress == 0) {

			if ($Cy != 0) {
				$svg .= DrawPath($Ox, $C3y, $C3x, $C3y, $color1='red', $width=2, $color2='none', $dasharray2asharray1=5, $dasharray2=5);
			}
			if ($Cx != 0) {
				$svg .= DrawPath($C3x, $C3y, $C3x, $Oy, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}

			// Draw point K
			$svg .= DrawCircle($C3x, $C3y, 3, 'red', 1, 'red');
			$svg .= DrawText($C4x, $C4y, '$K$', 15);

		} elseif ($progress == 1) {

			if ($Ty != 0) {
				$svg .= DrawPath($Ox, $T3y, $T3x, $T3y, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Tx != 0) {
				$svg .= DrawPath($T3x, $T3y, $T3x, $Oy, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}

			// Draw point T
			$svg .= DrawCircle($T3x, $T3y, 3, 'red', 1, 'red');
			$svg .= DrawText($T4x, $T4y, '$T$', 15);			

			// Draw point K
			$svg .= DrawCircle($C3x, $C3y, 3, 'blue', 1, 'blue');
			$svg .= DrawText($C4x, $C4y, '$K$', 15);

		} elseif ($progress == 2) {

			$svg .= DrawVector($C3x, $C3y, $T3x, $T3y, 'blue', 7, 2, 30);
			$svg .= DrawVector($C3x, $C3y, $R3x, $R3y, 'red', 7, 2, 30);

			// Draw point R
			$svg .= DrawText($R4x, $R4y, '$R$', 15);

			// Draw point K
			$svg .= DrawText($C4x, $C4y, '$K$', 15);

			// Draw point T
			$svg .= DrawText($T4x, $T4y, '$T$', 15);

		} elseif ($progress == 3) {

			$svg .= DrawVector($C3x, $C3y, $T3x, $T3y, 'blue', 7, 2, 30);
			$svg .= DrawVector($C3x, $C3y, $R3x, $R3y, 'blue', 7, 2, 30);
			$svg .= DrawVector($C3x, $C3y, $P3x, $P3y, 'red', 7, 2, 30);
			$svg .= DrawVector($C3x, $C3y, $S3x, $S3y, 'red', 7, 2, 30);

			// Draw point K
			$svg .= DrawText($C4x, $C4y, '$K$', 15);

			// Draw point T
			$svg .= DrawText($T4x, $T4y, '$T$', 15);

			// Draw point R
			$svg .= DrawText($R4x, $R4y, '$R$', 15);

			// Draw point S
			$svg .= DrawText($S4x, $S4y, '$S$', 15);

			// Draw point P
			$svg .= DrawText($P4x, $P4y, '$P$', 15);

		} else {

			$svg .= DrawLine($P3x, $P3y, $T3x, $T3y, 'red', 2);
			$svg .= DrawLine($S3x, $S3y, $R3x, $R3y, 'red', 2);
			$svg .= DrawLine($T3x, $T3y, $S3x, $S3y, 'red', 2);
			$svg .= DrawLine($R3x, $R3y, $P3x, $P3y, 'red', 2);

			if ($Py != $Ry && $Py != $Ty && $Py != 0) {
				$svg .= DrawPath($Ox, $P3y, $P3x, $P3y, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Px != $Rx && $Px != $Tx && $Px != 0) {
				$svg .= DrawPath($P3x, $P3y, $P3x, $Oy, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Ry != $Py && $Ry != $Sy && $Ry != 0) {
				$svg .= DrawPath($Ox, $R3y, $R3x, $R3y, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Rx != $Px && $Rx != $Sx && $Rx != 0) {
				$svg .= DrawPath($R3x, $R3y, $R3x, $Oy, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Sy != $Ry && $Sy != $Ty && $Sy != 0) {
				$svg .= DrawPath($Ox, $S3y, $S3x, $S3y, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Sx != $Rx && $Sx != $Tx && $Sx != 0) {
				$svg .= DrawPath($S3x, $S3y, $S3x, $Oy, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Ty != $Py && $Ty != $Sy && $Ty != 0) {
				$svg .= DrawPath($Ox, $T3y, $T3x, $T3y, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}
			if ($Tx != $Px && $Tx != $Sx && $Tx != 0) {
				$svg .= DrawPath($T3x, $T3y, $T3x, $Oy, $color1='blue', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
			}

			// Draw point T
			$svg .= DrawText($T4x, $T4y, '$T$', 15);

			// Draw point R
			$svg .= DrawText($R4x, $R4y, '$R$', 15);

			// Draw point S
			$svg .= DrawText($S4x, $S4y, '$S$', 15);

			// Draw point P
			$svg .= DrawText($P4x, $P4y, '$P$', 15);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Coordinates1($Cx, $Cy, $vx, $vy, $mult) {

		$Tx = $Cx + $vx;
		$Ty = $Cy + $vy;

		$Rx = $Cx - $vx;
		$Ry = $Cy - $vy;

		$Px = $Cx + $mult * $vy;
		$Py = $Cy - $mult * $vx;

		$Sx = $Cx - $mult * $vy;
		$Sy = $Cy + $mult * $vx;

		return array($Tx,$Ty,$Rx,$Ry,$Px,$Py,$Sx,$Sy);
	}

	function Coordinates2($Px, $Py, $height, $left, $bottom, $unit) {

		$PPx = (1.5 - ($left - $Px))*$unit;
		$PPy = $height - (1.5 - ($bottom - $Py))*$unit;

		return array($PPx,$PPy);
	}

	function Coordinates3($Px, $Py, $Qx, $Qy, $type='node') {

		if ($type == 'center') {

			$Cx = ($Px + $Qx) / 2;
			$Cy = ($Py + $Qy) / 2;

			list($C1x, $C1y) = Rotate($Cx, $Cy, $Px, $Py, 45);
			list($Rx, $Ry) = LinePoint($Cx, $Cy, $C1x, $C1y, 0.5);

		} else {

			$length = Length($Px, $Py, $Qx, $Qy);

			list($Rx, $Ry) = LinePoint($Px, $Py, $Qx, $Qy, $length+0.5);

		}

		return array($Rx, $Ry);
	}
}

?>