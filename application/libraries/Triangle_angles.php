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

	function DrawTriangle() {

	}
}

?>