<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Define odd numbers */
if (!function_exists('def_odd'))
{
	function def_odd($level=1) {

		$question = 'Melyik számokat nevezzünk páratlan számoknak?';
		$options = array(
			'Azokat, amik $0,2,4,6,8$-ra végződnek.',
			'Azokat, amik $1,3,5,7,9$-re végződnek.',
			'Azokat, amik $1,2,3,4,5$-re végződnek.'
		);
		$correct = 1;
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