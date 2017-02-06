<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlet_exp {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		if ($level <= 1) {
			$base 	= rand(2,3);
			$result	= rand(5,10);
		} elseif ($level <= 2) {
			$base 	= rand(4,5);
			$result	= rand(10,15);
		} else {
			$base 	= rand(6,7);
			$result	= rand(15,20);
		}

		// // Original exercise
		// $base = 2;
		// $result = 10;
		
		$question = 'Oldja meg a következő egyenletet a valós számok halmazán! Válaszát három tizedesjegyre kerekítve adja meg!$$'.$base.'^x='.$result.'$$';

		$exponent1 = log10($result)/log10($base);
		$exponent2 = round2($exponent1, 5);
		$correct = round1($exponent1, 3);
		$solution = '$'.round2($correct, 3).'$';

		$page[] = 'Vegyük mindkét oldal $10$-es alapú logaritmusát:$$\log_{10}\left('.$base.'^x\right)=\log_{10}'.$result.'$$';
		$page[] = '<div class="alert alert-info"><strong>Logaritmus azonossága:</strong><br />Hatvány logaritmusa egyenlő az alap logaritmusának és a kitevőnek a szorzatával:$$\log_ab^k=k\cdot\log_ab$$</div>';
		$page[] = 'Az azonosság felhasználásával át tudjuk írni a baloldali kifejezést:$$x\cdot\log_{10}'.$base.'=\log_{10}'.$result.'$$';
		$hints[] = $page;

		$page = [];
		$page[] = 'Osszuk el mindkét oldalt $\log_{10}'.$base.'$-'.With($base).'!$$x=\frac{\log_{10}'.$result.'}{\log_{10}'.$base.'}\approx'.$exponent2.'$$';
		$page[] = '<b>Megjegyzés</b>: a számológépen a tízes alapú logaritmust a <b>log</b> gomb jelöli:<div class="text-center"><kbd>'.$result.'</kbd> <kbd>log</kbd> <kbd>&divide;</kbd> <kbd>'.$base.'</kbd>  <kbd>log</kbd> <kbd>=</kbd></div>';
		$page[] = 'A megoldás három tizedesjegyre kerekített értéke <span class="label label-success">$'.round2($correct, 3).'$</span>.';
		$hints[] = $page;

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'youtube'	=> 'SjUJ6ah1-pU'
		);
	}
}

?>