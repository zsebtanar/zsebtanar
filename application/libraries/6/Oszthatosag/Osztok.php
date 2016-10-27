<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osztok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$factors = $this->Factors($level);
		$num = array_product($factors);
		$divisors = divisors($num);

		$question = 'Soroljuk fel '.The($num).' $'.$num.'$ összes pozitív osztóját! <i>(Például: $1;2;4$)</i>';
		$correct = $divisors;
		$solution = '$'.implode("$$;$$", $correct).'$';

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
			$factor_number = 3;
		} else {
			$primes = [2, 2, 3, 5, 7, 11, 13];
			$factor_number = 4;
		}

		for ($i=0; $i < $factor_number; $i++) {
			shuffle($primes);
			$factors[] = array_pop($primes);
		}

		return $factors;
	}

	function FactorizationEquation($num, $factors) {

		$text = '$$\begin{array}{r|l}';

		for ($i=0; $i < count($factors); $i++) {
			$text .= $num.'&'.$factors[$i].'\\\\';
			$num /= $factors[$i];
		}

		$text .= $num.'&\end{array}$$';

		return $text;

	}

	function Factorization($num) {

		$primes = array_reverse(primefactor($num));

		$text = '$$\begin{array}{r|l}';

		if (!$stage) {
			$stage = count($primes);
		}

		for ($i=0; $i < $stage; $i++) { 
			$text .= $num.'&'.$primes[$i].'\\\\';
			$num /= $primes[$i];
		}

		$text .= $num.'&\end{array}$$';

		return $text;

	}

	function Hints($num, $factors) {

		$divisors = divisors($num);

		$hints[][] = The($num,1).' $'.$num.'$ prímtényezős felbontása $'.$num.'='.implode('\cdot', $factors).'$, ugyanis:'.$this->FactorizationEquation($num, $factors);
		$page[] = The($num,1).' $'.$num.'$ összes osztóját úgy tudjuk meghatározni, hogy az összes lehetséges módon kiválasztunk néhány prímtényezőt, és összeszorozzuk.';
		$page[] = The($num,1).' $'.$num.'$ összes osztója: <span class="label label-success">$'.implode(';', $divisors).'$</span>.';

		foreach ($divisors as $divisor) {
			$subpage[] = '$$'.$divisor.'='.implode('\cdot', array_reverse(primefactor($divisor))).'$$';
		}
		
		$page[] = $subpage;
		$hints[] = $page;

		return $hints;
	}
}?>