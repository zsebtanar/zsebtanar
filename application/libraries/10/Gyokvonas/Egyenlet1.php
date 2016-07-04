<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlet1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Solve equation for square root
	function Generate($level) {

		if ($level <= 3) {
			$num = rand(2,5);
			$exp = 2;
		} elseif ($level <= 6) {
			$num = rand(5,10);
			$exp = rand(2,3);
		} else {
			$num = rand(10,15);
			$exp = rand(3,4);
		}

		$question = 'Oldja meg az alábbi egyenletet a nemnegatív valós számok halmazán!'
			.'$$\sqrt{x}='.$num.'^'.$exp.'$$';

		$exp2 		= 2 * $exp;
		$correct 	= pow($num, $exp2);
		$solution 	= '$'.$correct.'$';
		

		$page[] = 'Emeljük négyzetre az egyenlet mindkét oldalát:$$x=\left('.$num.'^'.$exp.'\right)^2$$';
		$page[] = '<div class="alert alert-info"><b>Hatványazonosság:</b> Hatványt úgy hatványozunk, hogy a kitevőket összeszorozzuk:$$\left(a^n\right)^k=a^{n\cdot k}$$</div>';
		$page[] = 'A fenti azonosságot felhasználva átírhatjuk a jobb oldalt:'
			.'$$\left('.$num.'^'.$exp.'\right)^2='.$num.'^{'.$exp2.'}$$';
		$page[] = '<b>Megjegyzés</b>: egy szám hatványát az <b>x<sup>y</sup></b> gomb segítségével lehet kiszámolni:<div class="text-center"><kbd>'.$num.'</kbd> <kbd>x<sup>y</sup></kbd> <kbd>'.$exp2.'</kbd> <kbd>=</kbd></div>';
		$page[] = 'Tehát a megoldás <span class="label label-success">$'.$correct.'$</span>.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}
}

?>