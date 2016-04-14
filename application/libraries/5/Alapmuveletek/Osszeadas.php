<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osszeadas {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Add numbers
	function Generate($level) {

		if ($level <= 3) {
			$length1 = 1;
			$length2 = 1;
		} elseif ($level <= 6) {
			$length1 = 2;
			$length2 = 2;
		} else {
			$length1 = 3;
			$length2 = 2;
		}

		$num1 = numGen($length1, 10);
		$num2 = numGen($length2, 10);

		$correct = $num1+$num2;
		$question = 'Adjuk össze az alábbi számokat!'.equationAddition(array($num1, $num2));

		if ($correct > 9999) {
		$solution = '$'.number_format($correct,0,',','\\,').'$';
		} else {
		$solution = '$'.$correct.'$';
		}

		$hints = $this->Hints(array($num1, $num2));

		return array(
			'question'	=> $question,
			'correct'	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	// Hints for addition
	function Hints($num_array)
	{
		foreach ($num_array as $key => $num) {
			$digits_num = str_split($num);
			$digits_all[] = $digits_num;
			$lengths_all[] = count($digits_num);
		}

		$length = max($lengths_all);

		$remain_old = 0;
		$remain_new = 0;

		for ($ind=0; $ind < $length; $ind++) {

			$digits = [];

			foreach ($digits_all as $key => $digits_num) {
				$digit = array_pop($digits_num);
				if ($digit != NULL) {
					$digits[] = $digit;
				}
				$digits_all[$key] = $digits_num;
			}

			$sum_sub = array_sum($digits) + $remain_old;
			$text = '';

			$text = 'Adjuk össze '.(in_array($ind, [0,4]) ? 'az' : 'a').' <b>'.placeValues($ind).'</b> helyén lévő számjegyeket'.
				($remain_old > 0 ? ' (az előző számolásnál kapott maradékkal együtt):' : ':');

			if (count($digits) > 1 || $remain_old > 0) {
				$text .= ' $'.($remain_old > 0 ? '\textcolor{green}{'.$remain_old.'}+' : '').
					implode('+', $digits).'='.$sum_sub.'$.';
			}

			if ($sum_sub >= 10 && $ind != $length-1) {
				$text .= ' Írjuk le az utolsó jegyet '.placeValues($ind).' oszlopába, az elsőt pedig '
				.placeValues($ind+1).' oszlopa fölé:';
				$remain_new = ($sum_sub / 10) % 10;
			}

			$text .= equationAddition($num_array, $ind);

			if ($ind == $length - 1) {
				$text .= 'Tehát az összeg <span class="label label-success">$'.array_sum($num_array).'$</span>.';
			}

			$hints[][] = $text;

			$remain_old = $remain_new;
			$remain_new = 0;
		}
		return $hints;
	}
}

?>