<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hatvany_szorzat {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		if ($level <= 1) {
			$base 	= rand(2,3);
			$exp1 	= rand(2,5);
			$exp2 	= rand(2,5);
		} elseif ($level <= 2) {
			$base 	= rand(4,9);
			$exp1 	= rand(5,10);
			$exp2 	= rand(5,10);
		} else {
			$base 	= rand(10,15);
			$exp1 	= rand(10,15);
			$exp2 	= rand(10,10);
		}

		$question = 'Mennyi az $x$ értéke az alábbi kifejezésben?$$'.$base.'^'.$exp1.'\cdot'.$base.'^'.$exp2.'='.$base.'^x$$';
		$correct = $exp1 + $exp2;
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($base, $exp1, $exp2);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($base, $exp1, $exp2) {

		$exp = $exp1 + $exp2;

		$hints[][] = '<div class="alert alert-info"><b>Hatványok szorzata</b><br/>Azonos alapú hatványokat úgy is szorozhatunk, hogy a közös alapot a kitevők összegére emeljük:$$a^k\cdot a^n=a^{k+n}$$</div>';
		
		$hints[][] = 'Írjuk át a kifejezést a hatványazonosság segítségével:$$'.$base.'^'.$exp1.'\cdot'.$base.'^'.$exp2.'='.$base.'^{'.$exp1.'+'.$exp2.'}$$';
		$hints[][] = 'Tehát az $x$ értéke $'.$exp1.'+'.$exp2.'=$<span class="label label-success">$'.$exp.'$</span>.';

		return $hints;
	}
}

?>