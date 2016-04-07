<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vector_operations {

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

		// $angles = [60, 90, 120];
		// shuffle($angles);
		// $angle = $angles[0];
		$angle = 60;
		$length = rand(2,7);

		$question = 'Az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorok $'.$angle.'°$-os szöget zárnak be egymással, és mindkét vektor hossza $'.$length.'$ egység.';
		$correct = $numbers[$num];
		$solution = $correct;

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
				$page[] = 'Rajzoljuk fel az $\overrightarrow{AB}$ és $\overrightarrow{AC}$ vektorokat!'.$this->Vectors($angle, $length);
				$hints[] = $page;
				break;
			
			default:
				# code...
				break;
		}

		return $hints;
	}

	function Vectors($angle, $size) {

		$width 	= 400;
		$height = 250;

		$paddingX = 50;
		$paddingY = 50;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					.'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		if ($angle == 60) {

			$length = 100;
			$Ax = $paddingX;
			$Ay = $height - $paddingY
			$svg .= DrawLine();

		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>