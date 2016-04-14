<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szogfajtak2 {

	// Define type of angle
	function Generate($level) {

		$CI =& get_instance();

		$options = array(
			'nullszög',
			'hegyesszög',
			'derékszög',
			'tompaszög',
			'egyenesszög',
			'homorúszög',
			'teljesszög',
			'forgásszög'
		);

		$angles = array(
			0,
			rand(1,89),
			90,
			rand(91,179),
			180,
			rand(181,359),
			360,
			rand(365,720)
		);

		$index 		= rand(0,count($angles)-1);

		$angle_type = $options[$index];
		$angle 		= $angles[$index];
		$solution 	= $angle_type;

		$question = 'Milyen típusú az a szög, ami $'.$angle.'°$-os?';

		$CI->load->library('5/Szogek/Szogfajtak1', NULL, 'Szogfajtak');
		$hints = $CI->Szogfajtak->Hints();
		
		shuffle($options);
		$correct = array_search($angle_type, $options);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'options' 	=> $options,
			'solution'	=> $solution,
			'hints' 	=> $hints
		);
	}
}

?>