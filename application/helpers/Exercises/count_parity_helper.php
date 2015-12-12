<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Count even/odd numbers */
if (!function_exists('count_parity'))
{
	function count_parity($level=1) {

		if ($level == 1) {
			$no = rand(2,3); 
			$len = 1;
		} elseif ($level == 2) {
			$no = rand(5,10);
			$len = 3;
		} elseif ($level == 3) {
			$no = rand(10,20);
			$len = 5;
		}

		for ($i=0; $i < $no; $i++) { 
			$num[$i] = numGen(rand(ceil($len/2),$len), 10);
		}

		$parity = array('páros', 'páratlan');
		$par = rand(0,1);

		$question = 'Hány szám '.$parity[$par].' az alábbiak közül?<br />';
		$correct = 0;

		foreach ($num as $key => $value) {
			$correct = ($value%2 == $par ? ++$correct : $correct);
			if ($value > 9999) {
				$value = number_format($value,0,',','\,');
			}
			$question .= '$'.$value.'$, ';
		}

		$question = rtrim($question, ', ');
		$type = 'int';
		$solution = '$'.$correct.'$';
		$options = '';

		return array(
			'question' 	=> $question,
			'options' 	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type
		);
	}
}