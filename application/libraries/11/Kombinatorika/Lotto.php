<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lotto {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		if ($level <= 3) {
			$max 	= rand(4,5);
			$pull 	= rand(2,3);
		} elseif ($level <= 6) {
			$max 	= rand(6,8);
			$pull 	= rand(3,5);
		} else {
			$max 	= rand(9,12);
			$pull 	= rand(4,7);
		}

		$pull_text = [
			2 => 'kettőt',
			3 => 'hármat',
			4 => 'négyet',
			5 => 'ötöt',
			6 => 'hatot',
			7 => 'hetet'
		];

		$numbers = range(1, $max);
		$chosen = $numbers;
		shuffle($chosen);
		$chosen = array_slice($chosen, 0, $pull);
		sort($chosen);

		$question = 'Az osztály lottót szervez, melyben az $'.implode(',', $numbers).'$ számok közül húznak ki '.$pull_text[$pull].'. Tamás '.The($chosen[0]).' $'.implode(',', $chosen).'$ számokat jelöli be a szelvényen. Számítsa ki annak a valószínűségét, hogy Tamásnak telitalálata lesz!';

		$all = binomial_coeff($max, $pull);

		$page[] = 'Az összes lehetséges húzás száma:$${'.$max.'\choose '.$pull.'}='.$all.'$$';
		$page[] = '<b>Megjegyzés</b>: az eredményt számológéppel a <b>nCr</b> gombbal lehet kiszámolni:<br /><div class="text-center"><kbd>'.$max.'</kbd> <kbd>Shift</kbd> <kbd>nCr</kbd> <kbd>'.$pull.'</kbd> <kbd>=</kbd></div>';
		$page[] = 'A kedvező esetek száma $1$.';
		$page[] = 'A keresett valószínűség a kedvező és összes eset hányadosa, tehát <span class="label label-success">$\frac{1}{'.$all.'}$</span>.';
		$hints[] = $page;

		$correct = array(1, $all);
		$solution = '$\frac{1}{'.$all.'}$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> 'fraction'
		);
	}
}

?>