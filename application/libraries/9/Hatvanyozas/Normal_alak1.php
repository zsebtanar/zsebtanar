<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Normal_alak1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		if ($level <= 1) {
			$coeff	= rand(10,99)/10;
			$exp 	= pow(-1, rand(1,2)) * rand(2,3);
		} elseif ($level <= 2) {
			$coeff	= rand(100,999)/100;
			$exp 	= -rand(3,4);
		} else {
			$coeff	= rand(1000,9999)/1000;
			$exp 	= rand(4,5);
		}

		$power = pow(10, $exp);
		$correct = $coeff * $power;

		$question = 'Határozzuk meg az alábbi normál alakban felírt szám értékét!$$'.round2($coeff,9).'\cdot10^{'.$exp.'}$$';

		$solution = '$'.round2($correct,9).'$';

		$page[] = 'Először végezzük el a hatványozást:$$10^{'.$exp.'}='.round2($power,9).'$$';
		$page[] = 'Most szorozzuk össze a két számot:$$'.round2($coeff).'\cdot'.round2($power,9).'=\textcolor{green}{'.round2($correct,9).'}$$';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints' 	=> $hints
		);
	}
}

?>