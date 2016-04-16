<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viragcserep {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$numbers = ['one', 'two', 'three'];
		$num = rand(0,2);

		$question = $this->Vase();
		$correct = $numbers[$num];
		$solution = $correct;

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'type'		=> 'text'
		);
	}

	function Vase() {

		$sides			= 6;	// number of sides
		$height 		= 100;	// height of vase
		$radius_top 	= 100;	// radius of top circle
		$radius_bottom 	= 50;	// radius of bottom circle

		$alfa0 			= array(
			3 => rand(20, 40),
			4 => rand(-10, 10),
			5 => rand(-5, 5),
			6 => rand(-10, 10)
		);						// starting angle of vase nodes
		$alfa0			= $alfa0[$sides];

		$visible		= array(
			3 => [1,1,1],
			4 => [1,0,1,1],
			5 => [1,0,0,1,1],
			6 => [1,0,0,1,1,1]
		);						// ids of visible edges
		$visible 		= $visible[$sides];

		$perspective	= 0.4;	// 0 - view from side
								// 1 - view from top

		$padding_y		= 50;
		$canvas_width  	= 400;
		$canvas_height 	= 2*50 + $height + $perspective * ($radius_top + $radius_bottom);

		$svg = '<div class="img-question text-center">
					<svg width="'.$canvas_width.'" height="'.$canvas_height.'">'
					.'<rect width="'.$canvas_width.'" height="'.$canvas_height.'" fill="black" fill-opacity="0.2" />'
		;

		$center_top_x 		= $canvas_width/2;
		$center_top_y 		= $padding_y + $perspective * $radius_top;

		$center_bottom_x 	= $canvas_width/2;
		$center_bottom_y	= $center_top_y + $height;

		
		for ($i=0; $i < $sides; $i++) {

			$alfa = $alfa0 + $i*360/$sides;

			// Calculate points
			list($Px, $Py) 	= Rotate($center_top_x, $center_top_y, $center_top_x+$radius_top, $center_top_y, $alfa);
			list($Qx, $Qy) 	= Rotate($center_bottom_x, $center_bottom_y, $center_bottom_x+$radius_bottom, $center_bottom_y, $alfa);

			$Py = $center_top_y + $perspective * ($Py - $center_top_y);
			$Qy = $center_bottom_y + $perspective * ($Qy - $center_bottom_y);

			$points_top[] 		= [$Px, $Py];
			$points_bottom[] 	= [$Qx, $Qy];

			// Draw top circle
			if ($i > 0) {
				$svg .= DrawLine($points_top[$i-1][0], $points_top[$i-1][1], $Px, $Py, 'black', 2);
			}
			if ($i == $sides-1) {
				$svg .= DrawLine($points_top[0][0], $points_top[0][1], $Px, $Py, 'black', 2);
			}

			// Draw bottom circle
			if ($i > 0) {
				if ($visible[$i] && $visible[$i-1]) {
					$svg .= DrawLine($points_bottom[$i-1][0], $points_bottom[$i-1][1], $Qx, $Qy, 'black', 2);
				} else {
					$svg .= DrawPath($points_bottom[$i-1][0], $points_bottom[$i-1][1], $Qx, $Qy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
				}
			}
			if ($i == $sides-1) {
				if ($visible[$i] && $visible[0]) {
					$svg .= DrawLine($points_bottom[0][0], $points_bottom[0][1], $Qx, $Qy, 'black', 2);
				} else {
					$svg .= DrawPath($points_bottom[0][0], $points_bottom[0][1], $Qx, $Qy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
				}
			}

			// Draw sides
			if ($visible[$i]) {
				$svg .= DrawLine($Px, $Py, $Qx, $Qy, 'black', 2);
			} else {
				$svg .= DrawPath($Px, $Py, $Qx, $Qy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
			}
		}



		$svg .= '</svg></div>';

		return $svg;
	}
}

?>