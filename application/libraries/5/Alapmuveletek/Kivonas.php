<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kivonas {

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

		if ($num1 < $num2) {
			list($num1, $num2) = array($num2, $num1);
		}
		
		$correct = $num1-$num2;
		$question = 'Végezzük el az alábbi kivonást!'.$this->Equation($num1, $num2);
		$hints = $this->Hints($num1, $num2);
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Equation($num1, $num2, $col=-1) {

		// Get digits for each number
		$digits1 = str_split($num1);
		$digits2 = str_split($num2);

		$length1 = count($digits1);
		$length2 = count($digits2);

		$diff = $num1 - $num2;
		$diff_digits = str_split($diff);
		$diff_length = count($diff_digits);

		$remain_old = 0;
		$remain_new = 0;

		$eq_header = '';
		$eq_has_header = FALSE;
		$eq_sum = '';
		$eq_line1 = '';
		$eq_line2 = '';

		for ($ind=0; $ind < $length1; $ind++) { 

			// Get digits of current column
			$digit1 = array_pop($digits1);
			$digit2 = array_pop($digits2);

			// Define remainer
			$result_sub = $digit1 - ($digit2 + $remain_old);
			if ($result_sub < 0 && $ind != $length1-1) {
				$remain_new = 1;
				$result_sub += 10;
			}

			// Update header
			if ($ind <= min($col, $diff_length-1)) {
				if ($ind == $col) {

					if ($remain_old > 0) {
						$eq_header = '\,\textcolor{blue}{\tiny{'.$remain_old.'}}\,'.$eq_header;
						$eq_has_header = TRUE;
					} else {
						$eq_header = '\phantom{\normalsize{0}}'.$eq_header;
					}

					if ($remain_new > 0) {
						$eq_header = '\textcolor{red}{\tiny{'.$remain_new.'}}\,'.$eq_header;
						$eq_has_header = TRUE;
					}

					$eq_sum = '\textcolor{red}{'.$result_sub.'}'.$eq_sum;

				} else {

					$eq_header = '\phantom{\normalsize{0}}'.$eq_header;
					$eq_sum = $result_sub.$eq_sum;

					if ($ind % 3 == 2) {
						$eq_header = '\,'.$eq_header;
						$eq_sum = '\,'.$eq_sum;
					}
				}
			}

			// Store equation lines
			$digit2 = ($digit2 == NULL ? '\phantom{0}' : $digit2);
			if ($ind == $col) {
				$eq_line1 = '\textcolor{blue}{'.$digit1.'}'.$eq_line1;
				$eq_line2 = '\textcolor{blue}{'.$digit2.'}'.$eq_line2;
			} else {
				$eq_line1 = $digit1.$eq_line1;
				$eq_line2 = $digit2.$eq_line2;
			}
			if ($ind % 3 == 2) {
				$eq_line1 = '\,'.$eq_line1;
				$eq_line2 = '\,'.$eq_line2;
			}

			$remain_old = $remain_new;
			$remain_new = 0;
		}

		if ($col == -1) {
			$eq_sum = '?';
		}

		// Include sum
		$equation = '$$\begin{align}';
		$equation .= $eq_line1.'&\\\\ ';
		$equation .= ($eq_has_header ? $eq_header.'&\\\\ ' : '');
		$equation .= '-\,'.$eq_line2.'&\\\\ ';
		$equation .= '\hline'.$eq_sum.'\end{align}$$';

		return $equation;
	}

	function Hints($num1, $num2) {

		$digits1 = str_split($num1);
		$digits2 = str_split($num2);

		$length1 = count($digits1);
		$length2 = count($digits2);

		$remain_old = 0;
		$remain_new = 0;

		$diff = $num1 - $num2;
		$diff_digits = str_split($diff);
		$diff_length = count($diff_digits);

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

		for ($ind=0; $ind < $length1; $ind++) {

			// Get digits of current column
			$digit1 = array_pop($digits1);
			$digit2 = array_pop($digits2);

			$digit2b = $digit2 + $remain_old;

			// Define remainer
			$result_sub = $digit1 - $digit2b;
			if ($result_sub < 0 && $ind != $length1-1) {
				$remain_new = 1;
				$result_sub += 10;
				$digit1b = $digit1 + 10;
				$result_digit = $result_sub % 10;
			} else {
				$digit1b = $digit1;
				$result_digit = $result_sub;
			}

			$text = '';

			$text = 'Nézzük meg '.(in_array($ind, [0,4]) ? 'az' : 'a').' '.$values[$ind].' helyén lévő számjegyeket! ';
			if ($remain_old > 0 && $digit2 != NULL) {
				$text .='(Az előző számolásnál kapott maradékot '.The($digit2).' $'.$digit2.'$-'.To($digit2)
					.' adjuk: $'.$digit2.'+'.$remain_old.'='.$digit2b.'$.) ';
			} elseif ($digit2 == NULL) {
				$text .= 'Az üres helyre $0$-t írunk'.($remain_old > 0 ? ', viszont a maradékot ne felejtsük el hozzászámolni! ' : '. ');
			}

			$text .= 'Mennyit kell adni '.The($digit2b).' $'.$digit2b.'$-'.To($digit2b).', hogy $'
				.$digit1b.'$-'.Dativ($digit1b).' kapjunk? $'.$result_sub.'$-'.Dativ($result_sub).', mert $'
				.$digit2b.'+'.$result_sub.'='.$digit1b.'$. ';

			if ($remain_new == 1) {
				$article = The($result_digit);
				$Article = str_replace('a', 'A', $article);
				$text .= $Article.' $'.$result_digit.'$-'.Dativ($result_digit).' leírjuk alulra, az $1$-et pedig '
					.(in_array($ind+1, [0,4]) ? 'az' : 'a').' '.$values[$ind+1].' fölé:';
			} elseif ($result_digit != 0 || $ind < $diff_length) {
				$text .= 'Az eredményt írjuk le alulra:';
				
			}

			$text .= $this->Equation($num1, $num2, $ind);

			if ($ind == $length1 - 1) {
				$text .= 'Tehát a különbség <span class="label label-success">$'.round2($num1-$num2).'$</span>.';
			}

			$hints[][] = $text;

			$remain_old = $remain_new;
			$remain_new = 0;
		}

		return $hints;
	}
}

?>