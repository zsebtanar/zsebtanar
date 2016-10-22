<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ottusa_pontok2 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	// Generate random number between 1 and 20
	function Generate($level) {

		$ppl = rand(20,40);
		$rounds = rand(round($ppl/2), round($ppl*4/5));
		$default = rand(3,7)*50;
		$extra = rand(5,9);
		$total = $ppl-1;
		$wins = rand(0, $total);

		// // Original exercise
		// $ppl = 31;
		// $rounds = 21;
		// $default = 250;
		// $extra = 7;
		// $total = $ppl-1;
		// $wins = 16;

		$wins = ($wins == $rounds ? $wins+pow(-1,rand(0,1)) : $wins);
		$defeats = $total - $wins;
		$points = $default+($wins-$rounds)*$extra;
		$diff = $wins-$rounds;

		$question = 'Egy öttusaversenyen $'.$ppl.'$ résztvevő indult. A vívás az első szám, ahol mindenki mindenkivel egyszer mérkőzik meg. Aki $'.$rounds.'$ győzelmet arat, az $'.$default.'$ pontot kap. Aki ennél több győzelmet arat, az minden egyes további győzelemért $'.$extra.'$ pontot kap '.The($default).' $'.$default.'$ ponton felül. Aki ennél kevesebbszer győz, attól annyiszor vonnak le $'.$extra.'$ pontot '.The($default).' $'.$default.'$-'.From($default).', ahány győzelem hiányzik '.The($rounds).' $'.$rounds.'$-'.To($rounds).'. (A mérkőzések nem végződhetnek döntetlenre.) ';
		$type = 'int';

		$question .= 'Hány győzelme volt Bencének, aki $'.$points.'$ pontot szerzett?';
		$correct = $wins;
		$solution = '$'.$correct.'$';

		if ($diff > 0) {

			$page[] = 'Bence $'.$default.'$ pontnál többet szerzett, ami azt jelenti, hogy legalább $'.$rounds.'$ mérkőzést nyert.';
			$page[] = 'Számoljuk ki, Bence pontjainak száma mennyivel több, mint $'.$default.'$:$$'.$points.'-'.$default.'='.strval($diff*$extra).'$$';
			$page[] = 'Ha ezt a számot elosztjuk $'.$extra.'$-'.With($extra).', megkapjuk, hogy hány további versenyt nyert Bence:$$'.strval($diff*$extra).':'.$extra.'='.$diff.'$$';
			$page[] = 'Tehát Bencének összesen $'.$rounds.'+'.$diff.'=$ <span class="label label-success">$'.$correct.'$</span> győzelme volt.';
			$hints[] = $page;

		} else {

			$diff *= -1;
			$page[] = 'Bence $'.$default.'$ pontnál kevesebbet szerzett, ami azt jelenti, hogy kevesebb, mint $'.$rounds.'$ mérkőzést nyert.';
			$page[] = 'Számoljuk ki, Bence pontjainak száma mennyivel kevesebb, mint $'.$default.'$:$$'.$default.'-'.$points.'='.strval($diff*$extra).'$$';
			$page[] = 'Ha ezt a számot elosztjuk $'.$extra.'$-'.With($extra).', megkapjuk, hogy hány további versenyt vesztett el Bence:$$'.strval($diff*$extra).':'.$extra.'='.$diff.'$$';
			$page[] = 'Tehát Bencének összesen $'.$rounds.'-'.$diff.'=$ <span class="label label-success">$'.$correct.'$</span> győzelme volt.';
			$hints[] = $page;

		}

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type,
			'hints'		=> $hints,
			'youtube'	=> 'k5QljCHp8iw'
		);
	}
}

?>