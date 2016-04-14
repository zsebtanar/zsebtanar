<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szamolas_1_20 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Generate random number between 1 and 20
	function Generate($level) {

		$num = rand(max(0,2*($level-2)), min(20,3*$level));

		$question = 'Hány darab alma van a fán?
			<div class="text-center">
				<img class="img-question" height="200px" src="'.base_url().'resources/exercises/count_apples/tree'.$num.'.png">
			</div>';
		$correct = $num;
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution
		);
	}
}

?>