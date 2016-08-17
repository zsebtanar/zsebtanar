<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teszt {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$question = 'Melyik a legkisebb természetes szám?';
		$correct = 0;
		$solution = '$'.$correct.'$';
		$hints[][] = 'A megoldás a <span class="label label-success">0</span>';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}
}

?>