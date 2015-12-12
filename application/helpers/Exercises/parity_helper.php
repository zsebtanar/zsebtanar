<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Define parity of numbers */
if (!function_exists('parity'))
{
	function parity($level=1) {

		if ($level == 1) {
			$num = rand(0,9); 
		} elseif ($level == 2) {
			$len = 3;
		} elseif ($level == 3) {
			$len = 5;
		}

		if ($level == 1) {

			$question = 'Páros vagy páratlan az alábbi szám?$$'.$num.'$$';
			$type = 'quiz';
			$options = array('páros', 'páratlan');
			$correct = $num%2;
			$solution = $options[$correct];

		} else {

			for ($i=0; $i < $len; $i++) { 
				$num[$i] = numGen(rand(round($len/2),$len), 10);
			}

			$correct = [];

			while (array_sum($correct) == 0) {
				$parity = array('párosak', 'páratlanok');
				$par = rand(0,1);

				foreach ($num as $key => $value) {
					$correct[$key] = ($value%2 == $par ? 1 : 0);
					if ($value > 9999) {
						$value = number_format($value,0,',','\,');
					}
					$options[$key] = '$'.$value.'$';
				}
			}

			$question = 'Mely számok '.$parity[$par].' az alábbi számok közül?';
			$type = 'multi';
			$solution = '';
		}

		return array(
			'question' 	=> $question,
			'options' 	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type
		);
	}
}