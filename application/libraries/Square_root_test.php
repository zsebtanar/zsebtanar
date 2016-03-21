<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Square_root_test {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define range of sine function (a+bsin(cx))
	function Generate($level) {

		$type = rand(1,3);
		$type = 2;

		list($question, $answer, $hints) = $this->Question($type, $level);

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

	function Question($type, $level) {

		if ($level <= 3) {
			$num = rand(2,3);
		} elseif ($level <= 6) {
			$num = rand(4,6);
		} else {
			$num = rand(7,15);
		}

		$question = 'Adja meg az alábbi állítás logikai értékét (igaz vagy hamis)!';

		if ($type == 1) {

			$subtype = rand(1,2);
			$subtype = 3;
			if ($subtype == 1) {
				$question .= '$$\sqrt{(-'.$num.')^2}='.$num.'$$';
				$answer = TRUE;
				$page[] = 'Először emeljük négyzetre a gyökjel alatti számot, majd végezzük el a gyökvonást:'
					.'$$\sqrt{(-'.$num.')^2}=\sqrt{'.strval(pow($num,2)).'}='.$num.'$$';
				$page[] = 'Tehát az állítás <span class="label label-success">igaz</span>.';
				$hints[] = $page;
			} elseif ($subtype == 2) {
				$question .= '$$\sqrt{-'.$num.'^2}='.$num.'$$';
				$answer = FALSE;
				$page[] = 'Először emeljük négyzetre a gyökjel alatti számot:'
					.'$$\sqrt{-'.$num.'^2}=\sqrt{-'.strval(pow($num,2)).'}$$';
				$page[] = 'A műveletet nem tudjuk elvégezni, mert negatív számból nem tudunk gyököt vonni.';
				$page[] = 'Tehát az állítás <span class="label label-success">hamis</span>.';
				$hints[] = $page;
			}

		} elseif ($type == 2) {

			$subtype = rand(1,4);
			$subtype = 4;
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

		} elseif ($type == 3) {

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
		}

		return array($question, $answer, $hints);
	}
}

?>