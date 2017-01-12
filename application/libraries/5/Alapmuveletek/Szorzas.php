<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szorzas {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		if ($level <= 1) {
			$num1 = rand(1, 9);
			$num2 = rand(1, 9);
		} elseif ($level <= 2) {
			$num1 = rand(10, 99);
			$num2 = rand(5, 9);
		} else {
			$num1 = rand(101, 199);
			$num2 = rand(10, 12);
		}

		// $num1 = 2413;
		// $num2 = 92234;

		if ($num1 < $num2) {
			list($num1, $num2) = array($num2, $num1);
		}
		
		$correct = $num1*$num2;
		$question = 'Szorozzuk össze az alábbi számokat!'.$this->Equation_Mult($num1, $num2);
		$hints = $this->Hints_Mult($num1, $num2);
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Equation_Mult($num1, $num2, $col1=-1, $col2=-1, $color=TRUE) {

		$digits1 = str_split($num1);
		$digits2 = str_split($num2);

		$length1 = count($digits1);
		$length2 = count($digits2);

		$remain_old = 0;
		$remain_new = 0;

		$equation = '$$\begin{align}';
		$eq_first_row = '\underline{';

		$digit2 = ($col2 == -1 ? -1 : $digits2[$length2-1-$col2]);

		// First number
		foreach ($digits1 as $key => $digit) {

			if ($col1 == $length1-1-$key && $color && $digit2 != 0) {
				$eq_first_row .= '\textcolor{blue}{'.$digit.'}';
			} else {
				$eq_first_row .= $digit;
			}

			// Add space after every third digits
			$needspace = $key < $length1-1 && ($length1-$key-1) % 3 == 0;
			if ($needspace) {
				$eq_first_row .= '\,';
			}
		}

		$eq_first_row .= '}&\cdot';

		// Second number
		foreach ($digits2 as $key => $digit) {

			if ($col2 == $length2-1-$key && $color) {
				$eq_first_row .= '\textcolor{blue}{'.$digit.'}';
			} else {
				$eq_first_row .= $digit;
			}

			// Add space after every third digits
			$needspace = $key < $length2-1 && ($length2-$key-1) % 3 == 0;
			if ($needspace) {
				$eq_first_row .= '\,';
			}
		}

		$eq_first_row .= '\\\\ ';

		// Equation lines
		if ($col1 == -1 && $col2 == -1) {

			$eq_lines = '?&';
			$eq_header = '';

		} else {

			$eq_lines = '';
			$eq_header = '';

			for ($ind2=$length2-1; $ind2 >= $col2; $ind2--) {

				$line = '';
				$digit2 = $digits2[$length2-1-$ind2];

				// Multiply by 0
				if ($digit2 == 0) {

					if ($color) {
						$eq_lines = preg_replace('/\\\\\\\\ $/', '\textcolor{red}{0}\\\\\\ ', $eq_lines);					
					} else {
						$eq_lines = preg_replace('/\\\\\\\\ $/', '0\\\\\\ ', $eq_lines);
					}
				} else {

					if ($ind2 == $col2) { // current line

						for ($ind1=0; $ind1 < $length1; $ind1++) {

							$digit1 = $digits1[$length1-1-$ind1];

							$mult = $digit1 * $digit2 + $remain_old;

							if ($mult >= 10 && $ind1 != $length1-1) {
								$remain_new = floor($mult / 10);
								$mult_digit = $mult % 10;
							} else {
								$mult_digit = $mult;
							}
							
							$mult_digit = (is_null($mult_digit) ? '\phantom{0}' : $mult_digit);
							$mult_digit = ($ind1 > $col1 ? '\phantom{0}' : $mult_digit);
							
							// Add extra space after every third digits
							$space = (($ind1-($length2-1-$ind2)+30) % 3 == 2 ? '\,' : '');

							if ($ind1 == $length2-1-$col2) {

								if ($ind1 == $col1 && $color) {
									$line = $space.'\textcolor{red}{'.$mult_digit.'}&'.$line;
								} else {
									$line = $space.$mult_digit.'&'.$line;
								}

							} else {

								if ($ind1 == $col1 && $color) {
									$line = $space.'\textcolor{red}{'.$mult_digit.'}'.$line;
								} else {
									$line = $space.$mult_digit.$line;
								}
							}

							// Equation header
							if ($ind1 == $col1) {

								if ($remain_old != 0 && $color) {
									$eq_header = '\,\tiny{\textcolor{blue}{'.$remain_old.'}}\,'.$eq_header;
								} else {
									$eq_header = '\phantom{\normalsize{0}}'.$eq_header;
								}

								if ($remain_new != 0 && $color) {
									$eq_header = '\tiny{\textcolor{red}{'.$remain_new.'}}\,'.$eq_header;
								}

							} elseif ($ind1 < $col1) {

								$eq_header = '\phantom{\normalsize{0}}'.$eq_header;

							}
							
							$remain_old = $remain_new;
							$remain_new = 0;
						}

					} else { // complete line

						$mult = $digit2 * $num1;
						$mult_digits = str_split($mult);
						$mult_length = count($mult_digits);

						for ($ind1=0; $ind1<$mult_length; $ind1++) {

							// Add extra space after every third digits
							$space = (($ind1-($length2-1-$ind2)+30) % 3 == 2 ? '\,' : '');

							$mult_digit = $mult_digits[$mult_length-1-$ind1];
							$ind = $length2-1-$ind2;
							$line = $space.$mult_digit.($ind1 == $length2-1-$ind2 ? '&' : '').$line;
						}
					}

					$eq_lines .= $line.'\\\\ ';
				}
			}
		}

		$eq_header .= '&\\\\ ';

		$equation .= ($color ? $eq_header : '').$eq_first_row.$eq_lines.'\end{align}$$';

		return $equation;
	}

	function Hints_Mult($num1, $num2) {

		$digits1 = str_split($num1);
		$digits2 = str_split($num2);

		$length1 = count($digits1);
		$length2 = count($digits2);

		$remain_old = 0;
		$remain_new = 0;

		$order = array(
			"első",
			"második",
			"harmadik",
			"negyedik",
			"ötödik",
			"hatodik",
			"hetedik",
			"nyolcadik",
			"kilencedik",
			"tizedik"
		);

		// Multiply numbers
		for ($ind2=$length2-1; $ind2 >= 0; $ind2--) {

			$digit2 = $digits2[$length2-1-$ind2];
			$num_array[] = $digit2*$num1;
			$step = $length2-$ind2;

			if ($length2 > 1) {
				$text = '<b>'.$step.'. lépés:</b> A második szám '.$order[$length2-1-$ind2].' számjegye $'.$digit2.'$. ';
				if ($digit2 == 0) {
					$text .= 'Írjuk le a $0$-t a sor végére!';
				} elseif ($length1 > 1) {
					$text .= 'Szorozzuk meg ezzel '.The($num1).' $'.$num1.'$ minden számjegyét hátulról kezdve!';
				} else {
					$text .= 'Szorozzuk meg ezzel '.The($num1).' $'.$num1.'$-'.The($num1).'!';
				}
			} else {
				$text = 'Szorozzuk meg '.The($digit2).' $'.$digit2.'$-'.With($digit2).' ';
				if ($length1 > 1) {
					$text .= 'az első szám minden számjegyét hátulról kezdve!';
				} else {
					$text .= 'az első számot!';
				}
			}

			// print_r($length1);

			// print_r($ind2);

			// $text .= $this->Equation_Mult($num1, $num2, $length1, $ind2, $color=TRUE);
			$hints[][] = $text;

			if ($digit2 != 0) {

				for ($ind1=0; $ind1 < $length1; $ind1++) {

					$digit1 = $digits1[$length1-1-$ind1];
					$mult = $digit1 * $digit2 + $remain_old;

					$text = 'Szorozzuk meg '.(in_array($length1-1-$ind1, [0, 4]) ? 'az' : 'a').' '.$order[$length1-1-$ind1].' számjegyet';
					if ($remain_old != 0) {
						$text .= ' (ne felejtsük el hozzáadni az előbb kapott $'.$remain_old.'$-'.Dativ($remain_old).'!): $'.$digit2.'\cdot'.$digit1.'+'.$remain_old.'='.$mult.'$!';
					} else {
						$text .= ': $'.$digit2.'\cdot'.$digit1.'='.$mult.'$!';
					}

					if ($mult >= 10 && $ind1 != $length1-1) {

						$digit_next = $digits1[$length1-2-$ind1];
						$remain_new = floor($mult/10);
						$mult2 = $mult % 10;
						$text .= ' Írjuk '.The($mult2).' $'.$mult2.'$-'.Dativ($mult2).' alulra, '.The($remain_new).' $'.$remain_new.'$-'.Dativ($remain_new).' pedig '.The($digit_next).' $'.$digit_next.'$ fölé:';
					} else {
						$text .= ' Írjuk az eredményt alulra:';
					}

					$remain_old = $remain_new;
					$remain_new = 0;

					$text .= $this->Equation_Mult($num1, $num2, $ind1, $ind2);

					if ($ind1 == $length1-1 && $length2 == 1 && count($num_array) == 1) {
						$text .= 'Tehát a megoldás <span class="label label-success">$'.round2($num1*$num2).'$</span>.';
					}

					$hints[][] = $text;

				}
			}	
		}

		// Add subtotals
		if (count($num_array) > 1) {
			$step = $length2+1;
			$sum = array_sum($num_array);
			$prod = $num1*$num2;
			$col = count(str_split($sum));
			$subtext[] = '<b>'.$step.'. lépés:</b> Adjuk össze a szorzás során kapott számokat!'.$this->Equation_Add($num_array, $col, $color=FALSE).'Tehát a megoldás <span class="label label-success">$'.round2($prod).'$</span>.';
			$subtext[] = $this->Hints_Add($num_array);
			$hints[] = $subtext;
		}

		return $hints;
	}

	function Equation_Add($numbers, $col=-1, $color=TRUE) {

		// Get digits for each number
		foreach ($numbers as $key => $number) {
			$digits_num = str_split($number);

			for ($i=0; $i < count($numbers)-$key-1; $i++) { 
				$digits_num[] = NULL;
			}

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

	function Hints_Add($num_array) {

		foreach ($num_array as $key => $num) {
			$digits_num = str_split($num);

			for ($i=0; $i < count($num_array)-$key-1; $i++) { 
				$digits_num[] = NULL;
			}

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

			$text .= $this->Equation_Add($num_array, $ind);

			$hints[] = $text;

			$remain_old = $remain_new;
			$remain_new = 0;
		}
		return $hints;
	}
}

?>