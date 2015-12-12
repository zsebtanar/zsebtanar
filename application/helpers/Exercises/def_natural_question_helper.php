<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Define question for natural numbers */
if (!function_exists('def_natural_question'))
{
	function def_natural_question($level=1) {

		$question = 'Az alábbiak közül melyik kérdésre válaszolunk mindig természetes számmal?';
		$options = array(
			'Hány darab...?',
			'Mekkora...?',
			'Hányadik...?'
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