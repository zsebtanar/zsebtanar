<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test {

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

		$question 	= $this->DrawTriangle();
		$correct 	= ($types[2] == 'belső' ? $angles[2] : 180-$angles[2]);
		$solution 	= '$'.$correct.'°$-os';
		$hints[]	= $this->DrawTriangle();
		$hints[]        = $this->DrawTriangle('30');
		$hints[]        = $this->DrawTriangle('60');

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
		$angles[0] = 120;
		$angles[1] = 30;
		$angles[2] = 30;
		return $angles;
	}

	// Draw triangle (svg)
	function DrawTriangle($Ca=NULL,$Caa=NULL,$Cb=NULL,$Cbb=NULL,$Cc=NULL,$Ccc=NULL) { // captions (c) for inner (a) and outer (aa) angles

		$width 		= 400;
		$height 	= 300;
		$color1 	= '#F2F2F2';
		$color2 	= 'black';
		$padding	= 30;

		$arc_length = 70;

		$arc_radius_inner 	= 40;
		$arc_radius_outer1 	= 37;
		$arc_radius_outer2 	= 43;

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
					<svg width="'.$width.'" height="'.$height.'">';
		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		$svg .= $this->DrawLine($Ax, $Ay, $Bx, $By);
		$svg .= $this->DrawLine($Ax, $Ay, $Cx, $Cy);
		$svg .= $this->DrawLine($Bx, $By, $Cx, $Cy);

		// Nodes
		$svg .= '<text font-size="15" x="'.$Ax.'" y="'.strval($height-10).'" fill="black">$A$</text>';
		$svg .= '<text font-size="15" x="'.$Bx.'" y="'.strval($height-10).'" fill="black">$B$</text>';
		$svg .= '<text font-size="15" x="'.strval($Cx-10).'" y="'.strval($Cy-10).'" fill="black">$C$</text>';

		// Arc
		if ($Ca) { // caption for A inner
			$svg .= $this->DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, $arc_radius_inner, 7,-2, $Ca);
		}
		if ($Caa) { // caption for A outer
			$svg .= $this->DrawLine($Ax, $Ay, $AAx, $AAy);
			$svg .= $this->DrawArc($Ax, $Ay, $Cx, $Cy, $AAx, $AAy, $arc_radius_outer1);
			$svg .= $this->DrawArc($Ax, $Ay, $Cx, $Cy, $AAx, $AAy, $arc_radius_outer2, 5, 10, $Caa);
		}
		if ($Cb) { // caption for B inner
			$svg .= $this->DrawArc($Bx, $By, $Cx, $Cy, $Ax, $Ay, $arc_radius_inner, 30, 7, $Cb);
		}
		if ($Cbb) { // caption for B outer
			$svg .= $this->DrawLine($Bx, $By, $BBx, $BBy);
			$svg .= $this->DrawArc($Bx, $By, $BBx, $BBy, $Cx, $Cy, $arc_radius_outer1);
			$svg .= $this->DrawArc($Bx, $By, $BBx, $BBy, $Cx, $Cy, $arc_radius_outer2, 0, 0, $Cbb);
		}
		if ($Cc) { // caption for C inner
			$svg .= $this->DrawArc($Cx, $Cy, $Ax, $Ay, $Bx, $By, $arc_radius_inner, 25, 20, $Cc);
		}
		if ($Ccc) { // caption for C outer
			$svg .= $this->DrawLine($Cx, $Cy, $CCx, $CCy);
			$svg .= $this->DrawArc($Cx, $Cy, $Bx, $By, $CCx, $CCy, $arc_radius_outer1);
			$svg .= $this->DrawArc($Cx, $Cy, $Bx, $By, $CCx, $CCy, $arc_radius_outer2, 5, 5, $Ccc);
		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function DrawLine($x1, $y1, $x2, $y2) {

		$svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="black" stroke-width="1" />';

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
			$svg .= '<text font-size="15" x="'.$ccx.'" y="'.$ccy.'" fill="black">'.$text.'°</text>';
		}

		return $svg;
	}
}

?>