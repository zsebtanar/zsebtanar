<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ottusa_pontok1 {

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
		$wins = ($wins == $rounds ? $wins+pow(-1,rand(0,1)) : $wins);
		$defeats = $total - $wins;
		$points = $default+($wins-$rounds)*$extra;
		$diff = $wins-$rounds;

		$question = 'Egy öttusaversenyen $'.$ppl.'$ résztvevő indult. A vívás az első szám, ahol mindenki mindenkivel egyszer mérkőzik meg. Aki $'.$rounds.'$ győzelmet arat, az $'.$default.'$ pontot kap. Aki ennél több győzelmet arat, az minden egyes további győzelemért $'.$extra.'$ pontot kap '.The($default).' $'.$default.'$ ponton felül. Aki ennél kevesebbszer győz, attól annyiszor vonnak le $'.$extra.'$ pontot '.The($default).' $'.$default.'$-'.From($default).', ahány győzelem hiányzik '.The($rounds).' $'.$rounds.'$-'.To($rounds).'. (A mérkőzések nem végződhetnek döntetlenre.) ';
		$type = 'int';

		$question .= 'Hány pontot kapott a vívás során Péter, akinek $'.$defeats.'$ veresége volt?';
		$correct = $points;
		$solution = '$'.$correct.'$';
		$total = $ppl-1;
		$diff = $wins-$rounds;

		if ($diff > 0) {

			$page[] = 'Péter $'.$total.'$ mérkőzésből $'.$defeats.'$-'.Dativ($defeats).' vesztett el, azaz a többi $'.$total.'-'.$defeats.'='.$wins.'$ mérkőzést megnyerte.';
			$page[] = 'Mivel Péter legalább $'.$rounds.'$ mérkőzést nyert, ezért kap $'.$default.'$ pontot.';
			$page[] = 'Az előírt $'.$rounds.'$ mérkőzésen túl további $'.$wins.'-'.$rounds.'='.$diff.'$ alkalommal nyert, ami további $'.$diff.'\cdot'.$extra.'='.strval($diff*$extra).'$ pontot jelent.';
			$page[] = 'Tehát Péter összesen $'.$default.'+'.strval($diff*$extra).'=$ <span class="label label-success">$'.$correct.'$</span> pontot kapott.';
			$hints[] = $page;

		} else {

			$diff *= -1;
			$page[] = 'Péter győztes meccseinek száma $'.$rounds.'$ helyett mindössze $'.$wins.'$ volt, ami $'.$diff.'$-'.With($diff).' kevesebb.';
			$page[] = 'Ezért a $'.$default.'$ pontnál $'.$diff.'\cdot'.$extra.'='.strval($diff*$extra).'$-'.By($diff*$extra).' kevesebb pontot kap.';
			$page[] = 'Tehát Péter összesen $'.$default.'-'.strval($diff*$extra).'=$ <span class="label label-success">$'.$correct.'$</span> pontot kapott.';
			$hints[] = $page;

		}

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type,
			'hints'		=> $hints
		);
	}
}

?>