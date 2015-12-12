<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Count apples from 1 to 20 */
if (!function_exists('count_apples'))
{
	function count_apples($level=1) {

		if ($level == 1) {
			$num = rand(0,4);
		} elseif ($level == 2) {
			$num = rand(5,9);
		} elseif ($level == 3) {
			$num = rand(10,20);
		}

		$question = 'Hány darab alma van a fán?<div class="text-center"><img class="img-question" width="50%" src="'.RESOURCES_URL.'/count_apples/tree'.$num.'.png"></div>';
		$correct = $num;
		$options = '';
		$solution = '$'.$correct.'$';
		$type = 'int';

		return array(
			'question' 	=> $question,
			'options' 	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type
		);
	}
}