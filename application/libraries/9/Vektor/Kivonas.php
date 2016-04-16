<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kivonas {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$angle = rand(1,179);
		$length = rand(2,7);
		$correct = sqrt(pow($length,2) + pow($length,2) - 2*$length*$length*cos(toRad($angle)));
		
		$question = 'Az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorok $'.$angle.'°$-os szöget zárnak be egymással, és mindkét vektor hossza $'.$length.'$ egység.';
		$solution = '$'.str_replace('.', ',', round($correct*100)/100).'$';

		$question .= ' Számítsa ki az $\overrightarrow{AB}-\overrightarrow{AC}$ vektor hosszát legalább két tizedesjegy pontossággal!';
		$hints = $this->Hints($angle, $length);

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}

	function Hints($angle, $length) {

		$solution = round(sqrt(pow($length,2) + pow($length,2) - 2*$length*$length*cos(toRad($angle)))*100)/100;

		$hints[][] = 'Rajzoljuk fel az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorokat (nem kell, hogy valósághű legyen az ábra):'.$this->Vectors($angle, $length, 0);
		$hints[][] = 'Az $\overrightarrow{AB}-\overrightarrow{AC}$ vektor a $C$ pontból a $B$-be megy. Ekkor az $ABC$ egy egyenlő szárú háromszög, aminek két oldala $'.$length.'-'.$length.'$ egység, és a közbezárt szög $'.$angle.'°$:'.$this->Vectors($angle, $length, 1);
		$page[] = 'Ha egy háromszög két oldalát ($a$ és $b$) és a közbezárt szöget ($\alpha$) ismerjük, akkor a harmadik oldal hosszát a <b>koszinusz-tétel</b> segítségével tudjuk kiszámolni:$$c=\sqrt{a^2+b^2-2\cdot a\cdot b\cdot\cos\alpha}$$.';
		$page[] = 'Most az $a=b='.$length.'$, és $\alpha='.$angle.'°$. Ekkor a harmadik oldal hossza:$$\begin{eqnarray}'
			.'|\overrightarrow{AB}-\overrightarrow{AC}|&=&\sqrt{'.$length.'^2+'.$length.'^2-2\cdot '.$length.'\cdot '.$length.'\cdot\cos'.$angle.'°}\\\\'
			.' &=&\sqrt{'.strval(2*pow($length,2)).'-'.strval(2*pow($length,2)).'\cdot\cos'.$angle.'°}\end{eqnarray}$$';
		$page[] = 'Ennek a két tizedesjegyre kerekített értéke: <span class="label label-success">$'.$solution.'$</span>.';
		$hints[] = $page;

		return $hints;
	}

	function Vectors($angle, $size, $option=0) {

		$width 	= 400;
		$height = 250;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		$paddingX = 50;
		$paddingY = 40;
		$length = 170;

		$Ax = $width - $paddingX - $length;
		$Ay = $height - $paddingY;
		$Bx = $Ax + $length;
		$By = $Ay;
		list($Cx, $Cy) = Rotate($Ax, $Ay, $Bx, $By, 120);
		$Dx = $Cx + $length;
		$Dy = $Cy;

		$svg .= DrawText($Ax-5, $Ay+30, '$A$', 12);
		$svg .= DrawText($Bx-5, $By+30, '$B$', 12);
		$svg .= DrawText($Cx-5, $Cy-10, '$C$', 12);

		$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, 50);
		$svg .= DrawVector($Ax, $Ay, $Bx, $By, 'black', 10, 2, 10);
		$svg .= DrawVector($Ax, $Ay, $Cx, $Cy, 'black', 10, 2, 10);

		$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+30, '$'.$size.'$', 12);
		$svg .= DrawText(($Ax+$Cx)/2-23, ($Ay+$Cy)/2, '$'.$size.'$', 12);

		$svg .= DrawText($Ax+20, $Ay-15, '$'.$angle.'°$', 12);

		if ($option) {
			$svg .= DrawVector($Cx, $Cy, $Bx, $By, 'black', 10, 2, 10);
			$svg .= DrawText(($Bx+$Cx)/2+40, ($By+$Cy)/2-10, '$\overrightarrow{AB}-\overrightarrow{AC}$', 12, 'black');
		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>