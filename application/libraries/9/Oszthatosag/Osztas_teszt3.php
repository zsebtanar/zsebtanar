<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osztas_teszt3 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Test for square root
	function Generate($level) {

		if ($level <= 3) {
			$num1 = pow(2,rand(2,3));
			$num2 = pow(2,rand(4,5));
		} elseif ($level <= 6) {
			$num1 = pow(2,rand(1,3)) * pow(3,rand(0,1));
			$num2 = pow(2,rand(1,4)) * pow(3,rand(2,3));
		} else {
			$num1 = pow(2,rand(1,3)) * pow(3,rand(0,3)) * pow(5,rand(0,1));
			$num2 = pow(2,rand(1,4)) * pow(3,rand(0,3)) * pow(5,rand(2,3));
		}

		$gcd = gcd($num1, $num2);
		$num3 = (rand(1,2) == 1 ? $gcd : $gcd/2);

		// // Original exercise
		// $num1 = 48;
		// $num2 = 120;
		// $num3 = 12;
		// $gcd = gcd($num1, $num2);

		$correct 	= ($num3 == $gcd ? 0 : 1);
		$options 	= ['Igaz', 'Hamis'];
		$solution 	= $options[$correct];

		$question = 'Adja meg az alábbi állítás logikai értékét (igaz vagy hamis)!<br />'.The($num1, TRUE).' $'.$num1.'$ és '.The($num2).' $'.$num2.'$ legnagyobb közös osztója '.The($num3).' $'.$num3.'$.';

		$page[] = '<div class="alert alert-info"><strong>Közös osztó:</strong> az a szám, amivel mind a két szám osztható.</div>';
		$page[] = '<div class="alert alert-info"><strong>Legnagyobb közös osztó:</strong> a közös osztók közül a legnagyobb. Az $a$ és $b$ számok legnagyobb közös osztóját $(a;b)$-vel jelöljük.</div>';
		$page[] = 'A legnagyobb közös osztó kiszámításához először írjuk fel mindkét szám prímtényezős felbontását!';
		$hints[] = $page;

		$hints[][] = 'Az első szám prímtényezős felbontása: $'.$num1.'='.implode('\cdot',$this->CanonicForm($num1)).'$, ugyanis:'.$this->Factorization($num1);
		$hints[][] = 'A második szám prímtényezős felbontása: $'.$num2.'='.implode('\cdot',$this->CanonicForm($num2)).'$, ugyanis:'.$this->Factorization($num2);

		$page = [];
		$page[] = 'Most gyűjtsünk össze a közös prímtényezőket (ha mindkét számban előfordul, akkor a kisebb kitevőt nézzük): $$('.$num1.';'.$num2.')='.implode('\cdot',$this->CanonicForm($gcd)).'='.$gcd.'$$';
		$page[] = 'Mivel a legnagyobb közös osztó '.($gcd == $num3 ? 'megegyezik' : 'nem egyezik meg').' '.The($num3).' $'.$num3.'$-'.With($num3).', ezért az állítás <span class="label label-success">'.strtolower($solution).'</span>.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints
		);
	}

	function Factorization($num, $stage=0) {

		$primes = array_reverse(primefactor($num));

		$text = '$$\begin{array}{r|l}';

		if (!$stage) {
			$stage = count($primes);
		}

		for ($i=0; $i < $stage; $i++) { 
			$text .= $num.'&'.$primes[$i].'\\\\';
			$num /= $primes[$i];
		}

		$text .= '1&\end{array}$$';

		return $text;

	}

	function CanonicForm($num) {

		$primes_all = array_reverse(primefactor($num));
		$primes_distinct = array_unique($primes_all);

		foreach ($primes_distinct as $key => $prime) {
			$exponent = count(array_keys($primes_all, $prime));
			$primes_list[$key] = $prime.'^{'.$exponent.'}';
		}

		return $primes_list;
	}
}

?>