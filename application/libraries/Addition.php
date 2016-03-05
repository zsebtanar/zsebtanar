<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addition {

	/**
	 * Class constructor
	 */
	public function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
	}

	/**
	 * Add numbers
	 */
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

		$num1 = 22341;
		$num2 = 323982;

		$correct = $num1+$num2;
		$question = 'Adjuk össze az alábbi számokat!'.equationAddition(array($num1, $num2),0);

		if ($correct > 9999) {
		$solution = '$'.number_format($correct,0,',','\\,').'$';
		} else {
		$solution = '$'.$correct.'$';
		}

		$explanation = $this->Explanation(array($num1, $num2));
		// $explanation = FALSE;

		return array(
			'question'		=> $question,
			'correct'		=> $correct,
			'solution'		=> $solution,
			'explanation'	=> $explanation,
			'hint_replace'	=> TRUE
		);
	}

	// Explanation for addition
	function Explanation($num_array)
	{
		foreach ($num_array as $key => $num) {
			$digits_num = str_split($num);
			$digits_all[] = $digits_num;
			$lengths_all[] = count($digits_num);
		}

		$length = max($lengths_all);

		$remain_old = 0;
		$remain_new = 0;

		$values = array(
			"egyesek",
			"tízesek",
			"százasok",
			"ezresek",
			"tízezresek",
			"százezresek",
			"milliósok",
			"tízmilliósok",
			"százmilliósok",
			"milliárdosok",
			"tízmilliárdosok",
			"százmilliárdosok"
		);

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

			$text = 'Adjuk össze '.(in_array($ind, [0,4]) ? 'az' : 'a').' <b>'.$values[$ind].'</b> helyén lévő számjegyeket'.
				($remain_old > 0 ? ' (az előző számolásnál kapott maradékkal együtt):' : ':');

			if (count($digits) > 1 || $remain_old > 0) {
				$text .= ' $'.($remain_old > 0 ? '\textcolor{green}{'.$remain_old.'}+' : '').
					implode('+', $digits).'='.$sum_sub.'$.';
			}

			if ($sum_sub >= 10 && $ind != $length-1) {
				$text .= ' Írjuk le az utolsó jegyet '.$values[$ind].' oszlopába, az elsőt pedig '
				.$values[$ind+1].' oszlopa fölé:';
				$remain_new = ($sum_sub / 10) % 10;
			}

			$text .= equationAddition($num_array, $ind);

			if ($ind == $length - 1) {
				$text .= 'Tehát az összeg <span class="label label-success">$'.array_sum($num_array).'$</span>.';
			}

			$explanation[] = $text;

			$remain_old = $remain_new;
			$remain_new = 0;
		}
		return $explanation;
	}
}

?>