<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyokvonas {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		if ($level <= 3) {
			$num = rand(2,3);
		} elseif ($level <= 6) {
			$num = rand(4,6);
		} else {
			$num = rand(7,15);
		}

		$question = 'Mennyi az $x$ értéke az alábbi kifejezésben?$$\sqrt{x}='.$num.'$$';
		$correct = pow($num, 2);

		$page[] = 'Az $x$ értékét akkor kapjuk meg, ha mindkét oldalt négyzetre emeljük:$$(\sqrt{x})^2=('.$num.')^2$$';
		$page[] = 'Tehát az $x$ értéke <span class="label label-success">$'.$correct.'$</span>.';
		$hints[] = $page;

		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}
}

?>