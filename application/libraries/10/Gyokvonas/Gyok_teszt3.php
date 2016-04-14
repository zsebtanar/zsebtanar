<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyok_teszt3 {

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
			$num = rand(2,3);
		} elseif ($level <= 6) {
			$num = rand(4,6);
		} else {
			$num = rand(7,15);
		}

		$question = 'Adja meg az alábbi állítás logikai értékét (igaz vagy hamis)!';

		$subtype = rand(1,4);

		if ($subtype == 1) {

			$question .= '$$2^\frac{'.$num.'}{2}=\sqrt{'.strval(pow(2,$num)).'}$$';
			$answer = TRUE;
			$page[] = 'Tudjuk, hogy $a^{\frac{b}{2}}=\sqrt{a^b}$.';
			$page[] = 'Ezt az összefüggést felhasználva:'
				.'$$2^{\frac{'.$num.'}{2}}=\sqrt{2^'.$num.'}=\sqrt{'.strval(pow(2,$num)).'}$$';
			$page[] = 'Tehát az állítás <span class="label label-success">igaz</span>.';
			$hints[] = $page;

		} elseif ($subtype == 2) {

			$num2 = (rand(1,2) == 1 ? $num+1 : $num-1);
			$question .= '$$2^\frac{'.$num.'}{2}=\sqrt{'.strval(pow(2,$num2)).'}$$';
			$answer = FALSE;
			$page[] = 'Tudjuk, hogy $a^{\frac{b}{2}}=\sqrt{a^b}$.';
			$page[] = 'Ezt az összefüggést felhasználva:'
				.'$$2^{\frac{'.$num.'}{2}}=\sqrt{2^'.$num.'}=\sqrt{'.strval(pow(2,$num)).'}\neq'
				.'\sqrt{'.strval(pow(2,$num2)).'}$$';
			$page[] = 'Tehát az állítás <span class="label label-success">hamis</span>.';
			$hints[] = $page;

		} elseif ($subtype == 3) {

			$num = $num - $num%2;
			$question .= '$$2^\frac{'.$num.'}{2}='.strval(pow(2,$num/2)).'$$';
			$answer = TRUE;
			$page[] = 'Egyszerűsítsük a kitevőt:$$\frac{'.$num.'}{2}='.strval($num/2).'$$';
			$page[] = 'Ezt felhasználva:'
				.'$$2^{'.strval($num/2).'}='.strval(pow(2,$num/2)).'$$';
			$page[] = 'Tehát az állítás <span class="label label-success">igaz</span>.';
			$hints[] = $page;

		} elseif ($subtype == 4) {
			
			$num = $num - $num%2;
			$num2 = (rand(1,2) == 1 ? $num/2+1 : $num/2-1);
			$question .= '$$2^\frac{'.$num.'}{2}='.strval(pow(2,$num2)).'$$';
			$answer = FALSE;
			$page[] = 'Egyszerűsítsük a kitevőt:$$\frac{'.$num.'}{2}='.strval($num/2).'$$';
			$page[] = 'Ezt felhasználva:'
				.'$$2^{'.strval($num/2).'}='.strval(pow(2,$num/2)).'\neq'.strval(pow(2,$num2)).'$$';
			$page[] = 'Tehát az állítás <span class="label label-success">hamis</span>.';
			$hints[] = $page;

		}

		$correct = ($answer ? 0 : 1);
		$options = ['Igaz', 'Hamis'];
		$solution = $options[$correct];

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints
		);
	}
}

?>