<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Triangle_inner_angles {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$AC = rand(1,9);
		$BC = rand(1,9);

		$question = 'Az $ABC$ derékszögű háromszög $AC$ befogója $'.$AC.'$ cm, $BC$ befogója $'.$BC.'$ cm hosszú. Számítsa ki az ABC háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

		$question = $this->Triangle();

		$alpha = atan($AC/$BC)*180/pi();
		$beta = atan($BC/$AC)*180/pi();

		$alphatext = str_replace('.', ',', round($alpha*100)/100);
		$betatext = str_replace('.', ',', round($beta*100)/100);

		$correct = array($alpha, $beta);

		$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'type'		=> 'inner_angles'
		);
	}

	function Triangle() {

		$width 	= 400;
		$height = 230;

		$paddingX = 50;
		$paddingY = 50;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					.'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		$Ax = $paddingX;
		$Ay = $height-$paddingY;

		$Bx = $width-$paddingX;
		$By = $height-$paddingY;

		$Cx = $width*0.7;
		$Cy = $paddingY;

		$svg .= DrawLine($Ax, $Ay, $Bx, $By);
		$svg .= DrawLine($Ax, $Ay, $Cx, $Cy);
		$svg .= DrawLine($Cx, $Cy, $Bx, $By);

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>