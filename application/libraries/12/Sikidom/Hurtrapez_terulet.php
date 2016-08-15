<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hurtrapez_terulet {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		// Trapez data
		$bottom = rand(10,15);	// bottom
		$top 	= rand(4,9);	// top
		$side 	= rand(5,9);	// side

		$bottom += ($bottom-$top) % 2;	// modify bottom

		// // Original exercise
		// $bottom = 5;
		// $top = 2;
		// $side = 2.5;

		$question = 'Az $ABCD$ húrtrapéz oldalainak hossza: $AB='.$bottom.'\,\text{cm}$, $BC='.round2($side).'\,\text{cm}$, $CD='.$top.'\,\text{cm}$ és $DA='.round2($side).'\,\text{cm}$. Határozza meg az $ABC$ és $ACD$ háromszögek területének arányát!'.$this->Trapez($bottom, $top, $side);

		$correct = [$bottom, $top];

		$hints = $this->Hints($bottom, $top, $side);
		$solution = '$\frac{'.$correct[0].'}{'.$correct[1].'}$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> 'fraction'
		);
	}

	function Hints($bottom, $top, $side) {

		$hints[][] = 'Ha a trapéz magasságát $m$-mel jelöljük, a következőképpen tudjuk kiszámolni az $ABC$ háromszög területét:$$T_{ABC}=\frac{AB\cdot m}{2}$$'.$this->Trapez($bottom, $top, $side, 1);
		$hints[][] = 'Hasonló módon számolhatjuk ki az $ACD$ háromszög területét is:$$T_{ACD}=\frac{CD\cdot m}{2}$$'.$this->Trapez($bottom, $top, $side, 2);
		$hints[][] = 'Számoljük ki a két terület arányát vesszük, $\frac{m}{2}$-vel egyszerűsíthetünk:$$\require{cancel} \frac{T_{ABC}}{T_{ACD}} = \frac{\frac{AB\cdot\cancel{m}}{\cancel{2}}}{\frac{CD\cdot\cancel{m}}{\cancel{2}}}=\frac{AB}{CD}$$';
		$hints[][] = 'Tehát a két terület aránya <span class="label label-success">$\frac{'.$bottom.'}{'.$top.'}$</span>.';

		return $hints;
	}

	function Trapez($bottom, $top, $side, $progress=0) {

		$width 	= 350;
		$height = 250;

		$paddingX_top 		= 110;
		$paddingX_bottom 	= 20;
		$paddingY 			= 50;
		$radius				= 40;

		$Ax = $paddingX_bottom;
		$Ay = $height - $paddingY;
		$Bx = $width - $paddingX_bottom;
		$By = $height - $paddingY;
		$Cx = $width - $paddingX_top;
		$Cy = $paddingY;
		$Dx = $paddingX_top;
		$Dy = $paddingY;

		$Ex = $paddingX_top;
		$Ey = $height - $paddingY;
		$Fx = $width - $paddingX_top;
		$Fy = $height - $paddingY;

		$svg = '<div class="img-question text-center">
				<svg width="'.$width.'" height="'.$height.'">'
				// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
		;

		// Sides
		$svg .= DrawLine($Ax, $Ay, $Bx, $By, 'black', 2);
		$svg .= DrawLine($Bx, $By, $Cx, $Cy, 'black', 2);
		$svg .= DrawLine($Cx, $Cy, $Dx, $Dy, 'black', 2);
		$svg .= DrawLine($Dx, $Dy, $Ax, $Ay, 'black', 2);

		// Nodes
		$svg .= DrawText($Ax-13, $Ay, '$A$', 12);
		$svg .= DrawText($Bx+13, $By, '$B$', 12);
		$svg .= DrawText($Cx+13, $Cy, '$C$', 12);
		$svg .= DrawText($Dx-13, $Dy, '$D$', 12);

		if ($progress == 0) {

			// Arcs
			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Dx, $Dy, $radius);
			$svg .= DrawArc($Bx, $By, $Cx, $Cy, $Ax, $Ay, $radius);
			$svg .= DrawArc($Cx, $Cy, $Dx, $Dy, $Bx, $By, $radius);
			$svg .= DrawArc($Dx, $Dy, $Ax, $Ay, $Cx, $Cy, $radius);

			// Lengths
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$bottom.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		} elseif ($progress == 1) {

			// Height
			$svg .= DrawPath($Cx, $Cy, $Fx, $Fy, $color1='blue', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);

			// Triangle			
			$svg .= DrawLine($Ax, $Ay, $Bx, $By, 'red', 3);
			$svg .= DrawLine($Bx, $By, $Cx, $Cy, 'red', 3);
			$svg .= DrawLine($Cx, $Cy, $Ax, $Ay, 'red', 3);

			// Lengths
			$svg .= DrawText(($Cx+$Fx)/2-13, ($Cy+$Fy)/2+7, '$\color{blue}{m}$', 12, 'blue');
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$bottom.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		} elseif ($progress == 2) {

			// Height
			$svg .= DrawPath($Dx, $Dy, $Ex, $Ey, $color1='blue', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);

			// Triangle			
			$svg .= DrawLine($Ax, $Ay, $Cx, $Cy, 'red', 3);
			$svg .= DrawLine($Cx, $Cy, $Dx, $Dy, 'red', 3);
			$svg .= DrawLine($Dx, $Dy, $Ax, $Ay, 'red', 3);

			// Lengths
			$svg .= DrawText(($Dx+$Ex)/2-13, ($Dy+$Ey)/2+7, '$\color{blue}{m}$', 12, 'blue');
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$bottom.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>