<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hurtrapez_koriv {

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
		$radius	= rand(5,9);	// arc radius

		$bottom += ($bottom-$top) % 2;	// modify bottom

		// // Original exercise
		// $bottom = 5;
		// $top = 2;
		// $side = 2.5;
		// $radius	= 5;

		$question = 'Az $ABCD$ húrtrapéz oldalainak hossza: $AB='.$bottom.'\,\text{cm}$, $BC='.round2($side).'\,\text{cm}$, $CD='.$top.'\,\text{cm}$ és $DA='.round2($side).'\,\text{cm}$. A trapéz belső szögeit egy-egy $'.$radius.'\,\text{mm}$ sugarú körívvel jelöltük. Számítsa ki a négy körív hosszának összegét! <i>(Válaszát egész mm-re kerekítve adja meg!)</i>'.$this->Trapez($bottom, $top, $side);

		$correct = round(2*$radius*pi());

		$hints = $this->Hints($bottom, $top, $side, $radius);
		$solution = '$'.$correct.'\,\text{mm}$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'labels'	=> ['right' => '$\,\text{mm}$']
		);
	}

	function Hints($bottom, $top, $side, $radius) {

		$circum = 2*$radius*pi();
		$page[] = 'Mivel a trapéz belső szögeinek összege $360°$, így ha a négy körívet egymás mellé rajzoljuk, egy teljes kört kapunk.';
		$page[] = 'Ezért a négy körív hossza összesen egy $'.$radius.'\,\text{mm}$ sugarú kör kerületével egyenlő:';
		$page[] = '$$K=2\cdot r\cdot\pi=2\cdot'.$radius.'\cdot3,14\approx'.round2($circum).'\,\text{mm}$$';
		$page[] = 'Ennek egészekre kerekített értéke <span class="label label-success">$'.round($circum).'$</span>$\,\text{mm}$.';
		$hints[] = $page;

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

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>