<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osztas_teszt1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Test for square root
	function Generate($level) {

		if ($level <= 1) {
			$num1 = rand(2,4);
			$num2 = rand(5,9);
		} elseif ($level <= 2) {
			$num1 = rand(4,9);
			$num2 = rand(10,20);
		} else {
			$num1 = rand(10,20);
			$num2 = rand(10,20);
		}

		// // Original exercise
		// $num1 = 6;
		// $num2 = 8;

		$mult 		= $num1 * $num2;
		$lcm 		= lcm($num1, $num2);
		$correct 	= ($mult == $lcm ? 0 : 1);
		$options 	= ['Igaz', 'Hamis'];
		$solution 	= $options[$correct];

		$question = 'Adja meg az alábbi állítás logikai értékét (igaz vagy hamis)!<br />'.
			'Ha egy szám osztható $'.$num1.'$-'.With($num1).' és $'.$num2.'$-'.With($num2).', akkor osztható $'.$mult.'$-'.With($mult).' is.';

		$page[] = 'Tudjuk, hogy ha egy szám osztható $a$-val, és osztható $b$-vel is, akkor $a$ és $b$ <b>legkisebb közös többszörösével</b> is osztható, amit $[a;b]$-vel jelölünk.';
		$page[] = 'Két szám legkisebb közös többszöröséhez először írjuk fel mindkét szám prímtényezős felbontását!';
		$hints[] = $page;

		$hints[][] = 'Az első szám prímtényezős felbontása: $'.$num1.'='.implode('\cdot',$this->CanonicForm($num1)).'$, ugyanis:'.$this->Factorization($num1);
		$hints[][] = 'A második szám prímtényezős felbontása: $'.$num2.'='.implode('\cdot',$this->CanonicForm($num2)).'$, ugyanis:'.$this->Factorization($num2);

		$page = [];
		$page[] = 'Most gyűjtsünk össze minden prímtényezőt a hozzátartozó kitevővel (ha mindkét számban előfordul, akkor a nagyobb kitevőt nézzük): $$['.$num1.';'.$num2.']='.implode('\cdot',$this->CanonicForm($lcm)).'='.$lcm.'$$';
		$page[] = 'Mivel a legkisebb közös többszörös '.($lcm == $mult ? 'megegyezik' : 'nem egyezik meg').' '.The($mult).' $'.$mult.'$-'.With($mult).', ezért az állítás <span class="label label-success">'.strtolower($solution).'</span>.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints,
			'youtube'	=> '-x85gDUMsio'
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