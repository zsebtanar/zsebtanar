<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vector_addition {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$option = rand(2,2);
		$length = rand(2,7);

		if ($option == 1) {
			$angle = 60;
			$correct = $length * sqrt(3);
		} elseif ($option == 2) {
			$angle = 90;
			$correct = $length * sqrt(2);
		} elseif ($option == 3) {
			$angle = 120;
			$correct = $length;
		}
		
		$question = 'Az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorok $'.$angle.'°$-os szöget zárnak be egymással, és mindkét vektor hossza $'.$length.'$ egység.';
		$solution = '$'.str_replace('.', ',', round($correct*100)/100).'$';

		$question .= ' Számítsa ki az $\overrightarrow{AB}+\overrightarrow{AC}$ vektor hosszát legalább két tizedesjegy pontossággal!';
		$hints = $this->Hints($angle, $length);

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}

	function Hints($angle, $length) {

		

		switch ($angle) {
			case 60:
				$solution = round($length*sqrt(3)*100)/100;
				$hints[][] = 'Rajzoljuk fel az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorokat:'.$this->Vectors($angle, $length, 0);
				$hints[][] = 'Ekkor az $ABC$ egy szabályos háromszög:'.$this->Vectors($angle, $length, 1);
				$hints[][] = 'Az $\overrightarrow{AB}+\overrightarrow{AC}$ vektor kétszer akkora lesz, mint az $ABC$ háromszög magassága:'.$this->Vectors($angle, $length, 2);
				$hints[][] = 'Tudjuk, hogy egy $a$ oldalú szabályos háromszög magassága $a\cdot\frac{\sqrt{3}}{2}$, ezért az $\overrightarrow{AB}+\overrightarrow{AC}$ vektor hossza $2\cdot a\cdot\frac{\sqrt{3}}{2}=a\cdot\sqrt{3}='.$length.'\sqrt{3}$ lesz, ami két tizedesjegyre kerekítve <span class="label label-success">$'.str_replace('.', ',', $solution).'$</span>.';
				break;

			case 90:
				$solution = round($length*sqrt(2)*100)/100;
				$hints[][] = 'Rajzoljuk fel az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorokat:'.$this->Vectors($angle, $length, 0);
				$hints[][] = 'Ekkor a három pont egy négyzet csúcsai lesznek:'.$this->Vectors($angle, $length, 1);
				$hints[][] = 'Az $\overrightarrow{AB}+\overrightarrow{AC}$ vektor hossza a négyzet átlója lesz:'.$this->Vectors($angle, $length, 2);
				$hints[][] = 'Tudjuk, hogy egy $a$ oldalú négyzet átlója $a\cdot\sqrt{2}$, ezért az $\overrightarrow{AB}+\overrightarrow{AC}$ vektor hossza $a\cdot\sqrt{2}='.$length.'\cdot\sqrt{2}$ lesz, ami két tizedesjegyre kerekítve <span class="label label-success">$'.$solution.'$</span>.';
				break;

			case 120:
				$solution = $length;
				$hints[][] = 'Rajzoljuk fel az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorokat:'.$this->Vectors($angle, $length, 0);
				$hints[][] = 'Az $\overrightarrow{AB}+\overrightarrow{AC}$, és az $\overrightarrow{AB}$ egy olyan egyenlő szárú háromszög két oldalát határozzák meg, amelynek egyik szöge $60°$-os, így a háromszög szabályos, vagyis a vektor hossza <span class="label label-success">$'.$solution.'$</span>:'.$this->Vectors($angle, $length, 1);
				break;
			
			default:
				# code...
				break;
		}

		return $hints;
	}

	function Vectors($angle, $size, $option=0) {

		$width 	= 400;
		$height = 250;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		if ($angle == 60) {

			$paddingX = 50;
			$paddingY = 50;
			$length = 170;

			$Ax = $paddingX;
			$Ay = $height - $paddingY;
			$Bx = $Ax + $length;
			$By = $Ay;
			list($Cx, $Cy) = Rotate($Ax, $Ay, $Bx, $By, 60);
			$Dx = $Cx + $length;
			$Dy = $Cy;

			$svg .= DrawText($Ax-5, $Ay+30, '$A$', 12);
			$svg .= DrawText($Bx-5, $By+30, '$B$', 12);
			$svg .= DrawText($Cx-5, $Cy-10, '$C$', 12);

			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, 70);
			$svg .= DrawVector($Ax, $Ay, $Bx, $By, 'black', 10, 2, 30);
			$svg .= DrawVector($Ax, $Ay, $Cx, $Cy, 'black', 10, 2, 30);

			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+30, '$'.$size.'$', 12);
			$svg .= DrawText(($Ax+$Cx)/2-13, ($Ay+$Cy)/2, '$'.$size.'$', 12);

			if (!$option) {
				$svg .= DrawText($Ax+40, $Ay-15, '$60°$', 12);
			} else {
				$svg .= DrawPath($Bx, $By, $Cx, $Cy, 'black', 2, 'none', 2, 2);
				if ($option == 2) {
					$svg .= DrawPath($Bx, $By, $Dx, $Dy, 'black', 2, 'none', 2, 2);
					$svg .= DrawPath($Cx, $Cy, $Dx, $Dy, 'black', 2, 'none', 2, 2);
					$svg .= DrawVector($Ax, $Ay, $Dx, $Dy, 'black', 10, 2, 30);
					$svg .= DrawText($Dx-15, $Dy-10, '$\overrightarrow{AB}+\overrightarrow{AC}$', 12, 'black');
				} else {
					$svg .= DrawText($Ax+40, $Ay-15, '$60°$', 12);
				}
			}

		} elseif ($angle == 90) {

			$paddingX = 50;
			$paddingY = 40;
			$length = 170;

			$Ax = ($width-$length)/2;
			$Ay = $height - $paddingY;
			$Bx = $Ax + $length;
			$By = $Ay;
			list($Cx, $Cy) = Rotate($Ax, $Ay, $Bx, $By, $angle);
			$Dx = $Cx + $length;
			$Dy = $Cy;

			$svg .= DrawText($Ax-5, $Ay+30, '$A$', 12);
			$svg .= DrawText($Bx-5, $By+30, '$B$', 12);
			$svg .= DrawText($Cx-5, $Cy-10, '$C$', 12);

			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, 60);
			$svg .= DrawVector($Ax, $Ay, $Bx, $By, 'black', 10, 2, 30);
			$svg .= DrawVector($Ax, $Ay, $Cx, $Cy, 'black', 10, 2, 30);

			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+30, '$'.$size.'$', 12);
			$svg .= DrawText(($Ax+$Cx)/2-13, ($Ay+$Cy)/2, '$'.$size.'$', 12);

			if (!$option) {
				$svg .= DrawText($Ax+25, $Ay-15, '$90°$', 12);
			} else {
				$svg .= DrawPath($Bx, $By, $Dx, $Dy, 'black', 2, 'none', 2, 2);
				$svg .= DrawPath($Cx, $Cy, $Dx, $Dy, 'black', 2, 'none', 2, 2);
				if ($option == 2) {
					$svg .= DrawVector($Ax, $Ay, $Dx, $Dy, 'black', 10, 2, 30);
					$svg .= DrawText($Dx-15, $Dy-10, '$\overrightarrow{AB}+\overrightarrow{AC}$', 12, 'black');
				} else {
					$svg .= DrawText($Ax+25, $Ay-15, '$90°$', 12);
				}
			}

		} elseif ($angle == 120) {

			$paddingX = 50;
			$paddingY = 40;
			$length = 170;

			$Ax = $width - $paddingX - $length;
			$Ay = $height - $paddingY;
			$Bx = $Ax + $length;
			$By = $Ay;
			list($Cx, $Cy) = Rotate($Ax, $Ay, $Bx, $By, $angle);
			$Dx = $Cx + $length;
			$Dy = $Cy;

			$svg .= DrawText($Ax-5, $Ay+30, '$A$', 12);
			$svg .= DrawText($Bx-5, $By+30, '$B$', 12);
			$svg .= DrawText($Cx-5, $Cy-10, '$C$', 12);
			
			$svg .= DrawVector($Ax, $Ay, $Bx, $By, 'black', 10, 2, 30);
			$svg .= DrawVector($Ax, $Ay, $Cx, $Cy, 'black', 10, 2, 30);

			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+30, '$'.$size.'$', 12);
			$svg .= DrawText(($Ax+$Cx)/2-13, ($Ay+$Cy)/2, '$'.$size.'$', 12);

			if (!$option) {
				$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, 50);
				$svg .= DrawText($Ax+20, $Ay-15, '$120°$', 12);
			} else {
				$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Dx, $Dy, 50);
				$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, 60);
				$svg .= DrawPath($Bx, $By, $Dx, $Dy, 'black', 2, 'none', 2, 2);
				$svg .= DrawPath($Cx, $Cy, $Dx, $Dy, 'black', 2, 'none', 2, 2);
				$svg .= DrawVector($Ax, $Ay, $Dx, $Dy, 'black', 10, 2, 30);
				$svg .= DrawText($Dx-15, $Dy-10, '$\overrightarrow{AB}+\overrightarrow{AC}$', 12, 'black');
				$svg .= DrawText($Ax+30, $Ay-10, '$60°$', 12);
			}

		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>