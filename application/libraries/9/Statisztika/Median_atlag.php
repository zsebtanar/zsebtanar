<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Median_atlag {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {
		
		$a1 = rand(-10*$level,10*$level);
		$a3 = rand(-10*$level,10*$level);
		$a3 = (abs($a1-$a3) < 6 ? $a1 + 6 : $a3);
		$a2 = ($a1 < $a3 ? rand($a1+1, $a3-1) : rand($a3+1, $a1-1));

		// // Original exercise
		// $a1 = 32;
		// $a2 = 28;
		// $a3 = 18;

		$sum = $a1 + $a2 + $a3;
		$a2 = ($a2 + 3 - $sum%3 >= max($a1, $a3) ? $a2 - $sum%3 : $a2 + 3 - $sum%3);

		$avg = ($a1 + $a2 + $a3)/3;
		$diff = $avg - $a2;

		$question = The($a1,TRUE).' $'.$a1.';x$ és $'.$a3.'$ számokról tudjuk, hogy a három szám átlaga $'.abs($diff).'$-'.With(abs($diff)).' '.($diff > 0 ? 'nagyobb' : 'kisebb').', mint a mediánja, továbbá $'.$a1.($a1<$a3 ? '\lt ' : '\gt ').'x'.($a1<$a3 ? '\lt ' : '\gt ').$a3.'$. Határozza meg az $x$ értékét!';
		$correct = $a2;
		$solution = '$'.$correct.'$';
		$type = 'int';

		$page[] = 'Írjuk fel az átlagot ($A$):$$'.($a3 > 0 ? 'A=\frac{'.$a1.'+x+'.$a3.'}{3}' : '\begin{eqnarray}A&=&\frac{'.$a1.'+x+('.$a3.')}{3}\\\\ &=&\frac{'.$a1.'+x'.$a3.'}{3}\end{eqnarray}').'$$';
		$page[] = 'Három szám közül a medián ($M$) a nagyság szerinti középső, vagyis $M=x$.';
		$page[] = 'A feladat szerint az átlag $'.abs($diff).'$-'.With(abs($diff)).' '.($diff > 0 ? 'nagyobb' : 'kisebb').', mint a medián, azaz:$$M=A'.($diff > 0 ? '-' : '+').abs($diff).'$$';
		$hints[] = $page;

		$page = [];
		$page[] = 'Helyettesítsük be $M$ és $A$ értékét, és fejezzük ki $x$-et:$$x=\frac{'.$a1.'+x'.($a3>0 ? '+' : '').$a3.'}{3}'.($diff > 0 ? '-' : '+').abs($diff).'$$';
		$page[] = 'Szorozzuk meg mindkét oldalt $3$-mal:$$3\cdot x=('.$a1.'+x'.($a3>0 ? '+' : '').$a3.')'.($diff > 0 ? '-' : '+').abs(3*$diff).'$$';
		$page[] = 'Elhagyhatjuk a zárójelet:$$3\cdot x='.$a1.'+x'.($a3>0 ? '+' : '').$a3.($diff > 0 ? '-' : '+').abs(3*$diff).'$$';
		$page[] = 'Vonjunk ki mindkét oldalból $x$-et:$$2\cdot x='.$a1.($a3>0 ? '+' : '').$a3.($diff > 0 ? '-' : '+').abs(3*$diff).'$$';
		$page[] = 'Végezzük el a műveleteket a jobb oldalon:$$2\cdot x='.strval($a1+$a3-3*$diff).'$$';
		$page[] = 'Osszuk el mindkét oldalt $2$-vel:$$x=\frac{'.strval($a1+$a3-3*$diff).'}{2}='.strval(($a1+$a3-3*$diff)/2).'$$';
		$page[] = 'Tehát a megoldás <span class="label label-success">$'.$a2.'$</span>.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'youtube'	=> '0A1ztUwk0LA'
		);
	}
}

?>