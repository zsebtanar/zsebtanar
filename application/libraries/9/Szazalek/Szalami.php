<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szalami {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Get value of sausage
	function Generate($level) {

		$unit = rand($level, 10*$level);
		$weight = rand(3+$level, 9+3*$level);

		$total = $unit * 100;

		$question = 'Ha $1\,\text{kg}$ szalámi ára $'.$total.'\,\text{Ft}$, akkor hány forintba kerül $'.$weight.'\,\text{dkg}$ szalámi?';
		$correct = $unit*$weight;
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($unit, $weight);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($unit, $weight) {

		$total = $unit * 100;
		$price = $unit * $weight;

		$page[] = 'Ha $1\,\text{kg}$ szalámi ára $'.$total.'\,\text{Ft}$, akkor $1\,\text{dkg}$ szalámi ára ennek $100$-adrésze:$$'.$total.':100='.$unit.'$$';
		$page[] = 'Ha $1\,\text{dkg}$ szalámi ára $'.$unit.'\,\text{Ft}$, akkor $'.$weight.'\,\text{dkg}$ szalámi $'.$weight.'$-'.Times($weight).' annyiba kerül:$$'.$unit.'\cdot'.$weight.'='.$price.'$$';
		$page[] = 'Tehát $'.$weight.'\,\text{dkg}$ szalámi <span class="label label-success">$'.$price.'$</span>$\,\text{Ft}$-ba kerül.';

		$hints[] = $page;

		return $hints;
	}
}

?>