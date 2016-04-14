<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyok_teszt2 {

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

			$question .= '<br /><br /><div class="text-center">Minden $x\in\mathbf{R}$ esetén '.
				'$\sqrt{x^2}=x$.</div>';
			$answer = FALSE;
			$page[] = 'Az állítás azt mondja, hogy ha bármelyik valós számot négyzetre emelem,'
				.' majd gyököt vonok belőle, visszakapom az eredeti számot.';
			$page[] = 'Ez az állítás igaz a <b>pozitív</b> számokra és a $0$-ra, de a <b>negatív</b>'
				.' számokra nem.';
			$page[] = 'Pl. $x=-2$ esetén:'
				.'$$\sqrt{(-2)^2}=\sqrt{4}=2\neq-2$$';
			$page[] = 'Tehát az állítás <span class="label label-success">hamis</span>.';
			$hints[] = $page;

		} elseif ($subtype == 2) {

			$question .= '<br /><br /><div class="text-center">Minden $x\in\mathbf{R}^{+}$ esetén '.
				'$\sqrt{x^2}=x$.</div>';
			$answer = TRUE;
			$page[] = 'Az állítás azt mondja, hogy ha bármelyik pozitív számot négyzetre emelem,'
				.' majd gyököt vonok belőle, visszakapom az eredeti számot.';
			$page[] = 'Nézzük meg pl. $x=3$-ra:'
				.'$$\sqrt{3^2}=\sqrt{9}=3$$';
			$page[] = 'A $3$ helyére akármelyik pozitív számot beírhatjuk, az állítás igaz marad.';
			$page[] = 'Tehát az állítás <span class="label label-success">igaz</span>.';
			$hints[] = $page;

		} elseif ($subtype == 3) {

			$question .= '<br /><br /><div class="text-center">Minden $x\in\mathbf{R}$ esetén '.
				'$\sqrt{x^2}=|x|$.</div>';
			$answer = TRUE;
			$page[] = 'Az állítás azt mondja, hogy bármelyik valós számot ha négyzetre emelem,'
				.' majd gyököt vonok belőle, visszakapom az eredeti szám abszolút értékét.';
			$page[] = 'Az állítás igaz lesz minden <b>pozitív</b> számra (és a $0$-ra is).';
			$page[] = 'Pl. $x=3$ esetén:'
				.'$$\sqrt{3^2}=\sqrt{9}=3$$';
			$page[] = 'Továbbá, ez az állítás igaz lesz minden <b>negatív</b> számra is.';
			$page[] = 'Pl. $x=-4$ esetén:'
				.'$$\sqrt{(-4)^2}=\sqrt{16}=4=|-4|$$';
			$page[] = 'Tehát az állítás minden valós szám esetén <span class="label label-success">igaz</span>.';
			$hints[] = $page;

		} elseif ($subtype == 4) {

			$question .= '<br /><br /><div class="text-center">Minden $x\in\mathbf{R}$ esetén '.
				'$(\sqrt{x})^2=x$.</div>';
			$answer = FALSE;
			$page[] = 'Az állítás azt mondja, hogy bármelyik valós számból négyzetgyököt vonok,'
				.' majd négyzetre emelem, visszakapom az eredeti számot.';
			$page[] = 'Ez az állítás igaz a <b>pozitív</b> számokra és a $0$-ra.';
			$page[] = 'Pl. $x=4$ esetén:'
				.'$$(\sqrt{4})^2=2^2=4$$';
			$page[] = 'Viszont pl. $x=-2$ esetén negatív számból kellene gyököt vonni, aminek '
				.'nincs valós megoldása: $\sqrt{-2}^2=\sqrt{-4}$.';
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