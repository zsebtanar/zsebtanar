<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Triangle_angles {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define triangle angle based on two given angles
	function Generate($level) {

		// Define random angles
		$angles = $this->GetAngles($level);

		// Define angle type
		$options 	= array('belső', 'külső');
		$types[0] 	= $options[rand(0,1)];
		$types[1] 	= $options[rand(0,1)];
		$types[2] 	= $options[rand(0,1)];

		$question = 'Egy $ABC$ háromszög $A$ csúcsnál lévő <b>'.$types[0].'</b> szöge $'
			.($types[0] == 'belső' ? $angles[0] : 180-$angles[0]).'°$-os, '
			.'$B$ csúcsnál lévő <b>'.$types[1].'</b> szöge $'
			.($types[1] == 'belső' ? $angles[1] : 180-$angles[1]).'°$-os. '
			.'Hány fokos a háromszög $C$ csúcsnál lévő <b>'.$types[2].'</b> szöge?';

		$question .= $this->DrawTriangle();

		$correct 	= ($types[2] == 'belső' ? $angles[2] : 180-$angles[2]);
		$solution 	= '$'.$correct.'°$-os';
		$hints 		= $this->Hints($angles, $types);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	// Define random angles
	function GetAngles($level) {

		if ($level <= 3) { // 30°, 60°, 90° ...

			$num1 = rand(1,4);
			$num2 = rand(1,5-$num1);
			$num3 = 6-($num1+$num2);
			$angles[0] = $num1 * 30;
			$angles[1] = $num2 * 30;
			$angles[2] = $num3 * 30;

		} elseif ($level <= 6) { // 10°, 20°, 30° ...

			$num1 = rand(1,16);
			$num2 = rand(1,17-$num1);
			$num3 = 18-($num1+$num2);
			$angles[0] = $num1 * 10;
			$angles[1] = $num2 * 10;
			$angles[2] = $num3 * 10;

		} else { // 1°, 2°, 3° ...

			$num1 = rand(1,178);
			$num2 = rand(1,179-$num1);
			$num3 = 180-($num1+$num2);
			$angles[0] = $num1;
			$angles[1] = $num2;
			$angles[2] = $num3;

		}

		return $angles;
	}

	function Hints($angles, $types) {

		$hints[][] = 'Rajzoljunk egy háromszöget! A csúcsoknál lévő <b>belső</b> szögeket $\alpha$-val, $\beta$-val és $\gamma$-val, '
			.'a <b>külső</b> szögeket pedig $\alpha\'$-vel, $\beta\'$-vel és $\gamma\'$-vel jelöljük:';

		return $hints;
	}

	
	function DrawTriangle($angle, $type) {

		$width 		= 400;
		$height 	= 300;
		$color1 	= '#F2F2F2';
		$color2 	= 'black';
		$padding	= 20;

		$arc_length = 70;
		$arc_radius = 20;

		// Outer points
		$AAx = $padding;
		$AAy = $height - $padding;

		$BBx = $width - $padding;
		$BBy = $height - $padding;

		$CCx = $width * 3/4;
		$CCy = $padding;

		// Inner points
		$Ax = $AAx + $arc_length;
		$Ay = $AAy;

		$Bx = $BBx - $arc_length;
		$By = $BBy;

		$ratio = 0.77;
		$Cx = $Ax + ($CCx - $Ax) * $ratio;
		$Cy = $CCy + ($Ay - $CCy) * (1-$ratio);

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">
					<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		$svg .= $this->DrawLine($AAx, $AAy, $BBx, $BBy);
		$svg .= $this->DrawLine($Ax, $Ay, $CCx, $CCy);
		$svg .= $this->DrawLine($Bx, $By, $Cx, $Cy);

		$svg .= $this->DrawArc($Bx, $By, $BBx, $BBy, $Cx, $Cy, $arc_radius, '$\alpha$');

		$svg .= '</svg></div>';

		return $svg;
	}

	function DrawLine($x1, $y1, $x2, $y2) {

		$svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="black" stroke-width="1" />';

		return $svg;
	}

	// Draws an arc between P1, P2, P3 (P1 is the center)
	function DrawArc($x1, $y1, $x2, $y2, $x3, $y3, $radius, $text=NULL) {

		// Draw arc
		$P12 = sqrt(pow($y2-$y1,2) + pow($x2-$x1,2));
		$P13 = sqrt(pow($y3-$y1,2) + pow($x3-$x1,2));
		$P23 = sqrt(pow($y3-$y2,2) + pow($x3-$x2,2));

		$xx2 = $x1 + ($x2 - $x1)/$P12*$radius;
		$yy2 = $y1 + ($y2 - $y1)/$P12*$radius;

		$xx3 = $x1 + ($x3 - $x1)/$P13*$radius;
		$yy3 = $y1 + ($y3 - $y1)/$P13*$radius;

		$alpha = $this->Angle($x1, $y1, $x2, $y2, $x3, $y3);

		$svg = '<path stroke="black" fill="none" d="M'.$xx2.','.$yy2.' A'.$radius.','.$radius.' 0 0,0 '.$xx3.','.$yy3.'" />';

		// Draw text
		$cx = ($xx2 + $xx3) / 2;
		$cy = ($yy2 + $yy3) / 2;

		$P1C = sqrt(pow($cy-$y1,2) + pow($cx-$x1,2));

		$ccx = $x1 + ($cx - $x1)/$P1C*($radius+7);
		$ccy = $y1 + ($cy - $y1)/$P1C*($radius+7);

		if ($text) {

			$svg .= '<text font-size="13" x="'.$ccx.'" y="'.$ccy.'" fill="black">'.$text.'</text>';

		}

		return $svg;
	}

	// Calculate angle between three points
	function Angle($x1, $y1, $x2, $y2, $x3, $y3) {

		$P12 = sqrt(($y2-$y1)^2 + ($x2-$x1)^2);
		$P13 = sqrt(($y3-$y1)^3 + ($x3-$x1)^2);
		$P23 = sqrt(($y3-$y2)^3 + ($x3-$x2)^2);

		$alpha = acos(($P12^2+$P13^2-$P23^2) / (2*$P12*$P13));

		return $alpha;
	}
}

?>