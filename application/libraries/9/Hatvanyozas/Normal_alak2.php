<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Normal_alak2 {

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
		$number = $coeff * $power;

		$question = 'Határozzuk meg az alábbi szám normál alakját!$$'.round2($number,9).'=x\cdot10^y$$';
		$correct = [$coeff, $exp];

		$solution = '$'.round2($number,9).'=$';

		$page[] = 'Először bontsuk a számot egy $1$ és $10$ közötti szám és $10$ egy hatványára:$$'.round2($number,9).'='.round2($coeff,9).'\cdot'.round2($power,9).'$$';
		$page[] = 'Írjuk fel a második számot $10$ hatványaként:$$'.round2($number,9).'='.round2($coeff,9).'\cdot10^{'.$exp.'}$$';
		$page[] = 'Tehát az $x=$<span class="label label-success">$'.round2($coeff,9).'$</span>, és az $y=$<span class="label label-success">$'.$exp.'$</span>.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints' 	=> $hints,
			'type'		=> 'list',
			'labels'	=> ['$x$', '$y$']
		);
	}
}

?>