<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Define even numbers */
if (!function_exists('def_even'))
{
	function def_even($level=1) {

		$question = 'Melyik számokat nevezzünk páros számoknak?';
		$options = array(
			'Azokat, amik $0,2,4,6,8$-ra végződnek.',
			'Azokat, amik $1,3,5,7,9$-re végződnek.',
			'Azokat, amik $1,2,3,4,5$-re végződnek.'
		);
		$correct = 0;
		$solution = $options[$correct];
		shuffleAssoc($options);
		$type = 'quiz';

		return array(
			'question' 	=> $question,
			'options' 	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type
		);
	}
}