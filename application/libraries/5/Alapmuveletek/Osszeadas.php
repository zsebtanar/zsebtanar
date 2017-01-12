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

	function Generate($level) {

		if ($level <= 1) {
			$num1 = rand(1,9);
			$num2 = rand(1,9);
		} elseif ($level <= 2) {
			$num1 = rand(10,99);
			$num2 = rand(10,99);
		} else {
			$num1 = rand(100,999);
			$num2 = rand(100,999);
		}

		$correct = $num1+$num2;
		$question = 'Adjuk össze az alábbi számokat!'.$this->Equation(array($num1, $num2));
		$hints = $this->Hints(array($num1, $num2));
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Equation($numbers, $col=-1, $color=TRUE) {

		// Get digits for each number
		foreach ($numbers as $key => $number) {
			$digits_num = str_split($number);

			$digits_all[] = $digits_num;
			$lengths_all[] = count($digits_num);
			$eq_lines[] = '';
		}

		$length = max($lengths_all);

		$remain_old = 0;
		$remain_new = 0;

		$eq_header = '';
		$eq_sum = '';

		for ($ind=0; $ind < $length; $ind++) { 

			// Get digits of current column
			$digits = [];

			foreach ($digits_all as $key => $digits_num) {
				$digits[] = array_pop($digits_num);
				$digits_all[$key] = $digits_num;
			}

			// Define remainer
			$sum_sub = array_sum($digits) + $remain_old;
			if ($sum_sub >= 10 && $ind != $length-1) {
				$remain_new = ($sum_sub/10) % 10;
				$sum_sub = $sum_sub % 10;
			}

			// Update header
			if ($ind <= $col) {
				if ($ind == $col) {

					if ($remain_old > 0 && $color) {
						$eq_header = '\,\textcolor{blue}{\tiny{'.$remain_old.'}}\,'.$eq_header;
					} else {
						$eq_header = '\phantom{\normalsize{0}}'.$eq_header;
					}

					if ($remain_new > 0 && $color) {
						$eq_header = '\textcolor{red}{\tiny{'.$remain_new.'}}\,'.$eq_header;
					}

					if ($color) {
						$eq_sum = '\textcolor{red}{'.$sum_sub.'}'.$eq_sum;
					} else {
						$eq_sum = $sum_sub.$eq_sum;
					}

				} else {

					$eq_header = '\phantom{\normalsize{0}}'.$eq_header;
					$eq_sum = $sum_sub.$eq_sum;

					if ($ind % 3 == 2) {
						$eq_header = '\,'.$eq_header;
						$eq_sum = '\,'.$eq_sum;
					}
				}
			}

			// Store equation lines
			foreach ($digits as $key => $digit) {
				$digit = ($digit == NULL ? '\phantom{0}' : $digit);
				if ($ind == $col && $color) {
					$eq_lines[$key] = '\textcolor{blue}{'.$digit.'}'.$eq_lines[$key];
				} else {
					$eq_lines[$key] = $digit.$eq_lines[$key];
				}
				if ($ind % 3 == 2) {
					$eq_lines[$key] = '\,'.$eq_lines[$key];
				}
			}

			$remain_old = $remain_new;
			$remain_new = 0;
		}



		if ($col == -1) {
			$eq_sum = '?';
		}

		// Include sum
		$equation = '$$\begin{align}'.($color && $col != (-1) ? $eq_header.'&\\\\ ' : '');
		foreach ($eq_lines as $key => $eq_line) {
			if ($key+1 == count($eq_lines)) {
				$equation .= '+\,';
			}
			$equation .= $eq_line.'&\\\\ ';
		}

		$equation .= '\hline'.$eq_sum.'\end{align}$$';

		return $equation;
	}

	function Hints($num_array) {

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
				$text .= ' Írjuk le az utolsó jegyet '.The($values[$ind]).' '.$values[$ind].' oszlopába, az elsőt pedig '
				.$values[$ind+1].' oszlopa fölé:';
				$remain_new = ($sum_sub / 10) % 10;
			}

			$text .= $this->Equation($num_array, $ind);

			if ($ind == $length - 1) {
				$text .= 'Tehát az összeg <span class="label label-success">$'.round2(array_sum($num_array)).'$</span>.';
			}

			$hints[][] = $text;

			$remain_old = $remain_new;
			$remain_new = 0;
		}

		return $hints;
	}
}

?>