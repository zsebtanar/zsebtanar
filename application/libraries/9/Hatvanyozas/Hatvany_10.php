<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hatvany_10 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		if ($level <= 1) {
			$base 	= rand(10,15);
			$exp 	= 0;
		} elseif ($level <= 2) {
			$base 	= 1;
			$exp 	= rand(3,9);
		} else {
			$base 	= 0;
			$exp 	= rand(3,9);
		}

		$question = 'Számoljuk ki az alábbi kifejezés értékét!$$'.$base.'^'.$exp.'$$';
		$correct = pow($base, $exp);
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($base, $exp, $level);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($base, $exp, $level) {

		$result = pow($base, $exp);

		if ($level <= 1) {
			$hints[][]	= '<div class="alert alert-info"><b>Nulladik hatvány</b><br/>Minden szám $0$-adik hatványa $1$-gyel egyenlő (a $0^0$ kifejezést nem értelmezzük).</div>';
		} elseif ($level <= 2) {
			$hints[][]	= '<div class="alert alert-info"><b>Egy hatványa</b><br/>Az $1$ akármelyik hatványa $1$-gyel egyenlő.</div>';
		} else {
			$hints[][]	= '<div class="alert alert-info"><b>Nulla hatványa</b><br/>A $0$ minden hatványa $0$-val egyenlő (a $0^0$ kifejezést nem értelmezzük).</div>';
		}

		return $hints;
	}
}

?>