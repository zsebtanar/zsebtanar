<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hatvany {

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
			$exp 	= rand(2,3);
		} elseif ($level <= 2) {
			$base 	= rand(4,9);
			$exp 	= rand(4,5);
		} else {
			$base 	= rand(10,15);
			$exp 	= rand(6,9);
		}

		$question = 'Számoljuk ki az alábbi kifejezés értékét!$$'.$base.'^'.$exp.'$$';
		$correct = pow($base, $exp);
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($base, $exp);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($base, $exp) {

		$mult = array_fill(0, $exp, $base);
		$result = pow($base, $exp);

		$hints[][] = '<div class="alert alert-info"><b>Hatványozás</b><br/>Egy szám $k$-adik hatványa azt jelenti, hogy a számot $k$-szor szorozzuk össze önmagával:$$a^k=\underbrace{a\cdot a\cdot a\cdots a}_{k}$$</div>';
		
		$page[] = 'A kifejezést a következőképpen tudjuk felírni:$$'.$base.'^'.$exp.'='.implode('\cdot', $mult).'$$';
		$page[] = '<b>Megjegyzés</b>: az eredményt a számológépen az <b>x<sup>y</sup></b> gomb segítségével lehet kiszámolni:<div class="text-center"><kbd>'.$base.'</kbd> <kbd>Shift</kbd> <kbd>x<sup>y</sup></kbd> <kbd>'.$exp.'</kbd> <kbd>=</kbd></div>';
		$page[] = 'Tehát az eredmény <span class="label label-success">$'.$result.'$</span>.';
		$hints[] = $page;

		return $hints;
	}
}

?>