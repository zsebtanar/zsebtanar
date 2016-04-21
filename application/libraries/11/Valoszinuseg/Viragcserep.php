<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viragcserep {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$flower = rand($level, 2*$level);
		$all 	= $flower + rand(1,2);
		$p 		= rand(80,95) / 100;

		$question = 'A kertészetben a sok virághagymának csak egy része hajt ki: $'.round2($p).'$ annak a valószínűsége, hogy egy elültetett virághagyma kihajt. Számítsa ki annak a valószínűségét, hogy $'.$all.'$ darab elültetett virághagyma közül legalább $'.$flower.'$ kihajt! Válaszát három tizedesjegyre kerekítve adja meg!';
		
		$text = 'Ha $'.$all.'$ virághagyma közül legalább $'.$flower.'$ hajt ki, az azt jelenti, hogy ';

		for ($i=$flower; $i <= $all; $i++) { 
			
			$text .= '$'.$i.'$';
			if ($i == $all-1) {
				$text .= ' vagy ';
			} elseif ($i == $all-2) {
				$text .= ', ';
			}
		}

		$text .= ' virághagyma hajt ki.';
		$page[] = $text;
		$page[] = 'Vizsgáljuk meg ezeket az eseteket külön-külön!';
		$hints[] = $page;

		$order = 1;
		for ($i=$flower; $i <= $all; $i++) {

			$p_i = binomial_coeff($all, $i) * pow($p, $i) * pow(1-$p, $all-$i);

			$page = [];
			$page[] = '<b>'.$order.'. eset:</b> $'.$all.'$ virághagyma közül $'.$i.'$ hajt ki.';
			$page[] = '$'.$all.'$ virághagyma közül $'.$i.'$-'.Dativ($i).' összesen ${'.$all.'\choose '.$i.'}$-féleképpen lehet kiválasztani.';
			$page[] = 'Annak a valószínűsége, hogy '.The($i).' $'.$i.'$ hagyma kihajt: $'.round2($p, 4).'^'.$i.'$.';
			$page[] = 'Annak a valószínűsége, hogy '.The($all-$i).' $'.strval($all-$i).'$ hagyma nem hajt ki: $(1-'.round2($p, 4).')^'.strval($all-$i).'$.';
			$page[] = 'Így ennek az esetnek a valószínűsége összesen:$${'.$all.'\choose '.$i.'}\cdot'.round2($p, 4).'^'.$i.'\cdot(1-'.round2($p, 4).')^'.strval($all-$i).'\approx'.round2($p_i, 4).'$$';
			$hints[] = $page;

			$p_all1[] = round1($p_i, 4);
			$p_all2[] = round2($p_i, 4);

			$order++;
		}

		$hints[][] = 'A keresett valószínűség tehát $'.implode('+', $p_all2).'='.array_sum($p_all1)'$, aminek a három tizedesjegyre kerekített értéke $<span class="label label-success">$'.round2(array_sum($p_all1), 3).'$</span>.';
		
		$correct = round1(array_sum($p_all1), 3);
		$solution = '$'.$correct.'$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}
}

?>