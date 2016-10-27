<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Primtenyezos_felbontas {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define members of intersection/union/difference of sets
	function Generate($level) {

		$factors = $this->Factors($level);
		$num = array_product($factors);

		$question = 'Írd fel '.The($num).' $'.$num.'$-'.Dativ($num).' prímtényezők szorzataként! <i>(Például: $2*2*3$)</i>';
		$correct = $factors;
		$solution = '$'.implode("$$*$$", $factors).'$';

		$hints = $this->Hints($num, $factors);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'type'		=> 'single_list',
			'separator' => '*'
		);
	}

	function Factors($level) {

		$factors = [];

		if ($level <= 3) {
			$primes = [2, 2, 3, 3, 5];
			$factor_number = 2;
		} elseif ($level <= 6) {
			$primes = [2, 2, 3, 3, 5, 7];
			$factor_number = 2;
		} else {
			$primes = [2, 2, 3, 5, 7, 11, 13];
			$factor_number = 3;
		}

		for ($i=0; $i < $factor_number; $i++) {
			shuffle($primes);
			$factors[] = array_pop($primes);
		}

		return $factors;
	}

	function Factorization($num, $factors, $stage=0) {

		$text = '$$\begin{array}{r|l}';

		for ($i=0; $i < $stage; $i++) {
			if ($i == $stage-1) {
				$text .= $num.'&\textcolor{red}{'.$factors[$i].'}\\\\';
			} else {
				$text .= $num.'&'.$factors[$i].'\\\\';
			}
			$num /= $factors[$i];
		}

		$text .= '\textcolor{blue}{'.$num.'}&\end{array}$$';

		return $text;

	}

	function Hints($num, $factors) {

		$num2 = $num;

		for ($i=0; $i < count($factors); $i++) { 
			$f = $factors[$i]; // factor
			$q = $num2 / $f; // quotient
			$page[0] = The($num2,1).' $'.$num2.'$ osztható $'.$f.'$-'.With($f).', mert: $$'.$num2.':'.$f.'='.$q.'$$';
			$page[1] = 'Írjuk le '.The($f).' $\textcolor{red}{'.$f.'}$-'.Dativ($f).' '.The($num2).' $'.$num2.'$ mellé, '.The($q).' $\textcolor{blue}{'.$q.'}$-'.Dativ($q).' pedig alá:'.$this->Factorization($num, $factors, $i+1);
			$hints[] = $page;
			$num2 /= $f;
		}

		$hints[][] = The($num,1).' $'.$num.'$ prímtényezős felbontását a jobb oldali számok adják meg, vagyis: $'.$num.'=$<span class="label label-success">$'.implode('*', $factors).'$</span>.';

		return $hints;
	}
}

?>