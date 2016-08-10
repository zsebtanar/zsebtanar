<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terjedelem {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define range, mean, standard deviation
	function Generate($level) {

		$set = $this->Set($level);

		// // Original exercise
		// $set = [1,1,1,1,3,3,3,5,5,7];

		$question = 'Határozza meg az alábbi adatsor terjedelmét!';
		$correct = max($set)-min($set);
		$solution = '$'.$correct.'$';
		$hints = $this->Hints($set);

		$question .= '$$'.implode(';', $set).'$$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints' 	=> $hints
		);
	}

	function Set($level) {

		$length = rand(2*$level,3*$level);

		for ($i=0; $i < $length; $i++) {
			$set[] = rand(1,5);
		}

		sort($set);

		return $set;
	}

	function Hints($set) {

		$page[] = 'A terjedelemhez meg kell határoznunk az adatsor minimumát és maximumát.';
		$page[] = 'A minimum: $'.min($set).'$.';
		$page[] = 'A maximum: $'.max($set).'$.';
		$page[] = 'A terjedelem a kettő különbsége: $'.max($set).'-'.min($set).'=$<span class="label label-success">$'.strval(max($set)-min($set)).'$</span>.';
		$hints[] = $page;

		return $hints;
	}
}

?>