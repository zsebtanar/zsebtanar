<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subsets {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define total number of subsets of a set
	function Generate($level) {

		if ($level <= 3) {
			$numbers = range(1,5);
			$set_size = rand(3,4);
			$subset_size = 2;
		} elseif ($level <= 6) {
			$numbers = range(1,10);
			$set_size = rand(4,6);
			$subset_size = rand(2,3);
		} else {
			$numbers = range(1,20);
			$set_size = rand(7,10);
			$subset_size = rand(3,4);
		}

		$set = $this->Set($numbers, $set_size);
		$subset = NumText($subset_size);

		$num = rand(max(0,2*($level-2)), min(20,3*$level));

		$question = 'Hány darab '.($subset == 'kettő' ? 'két' : $subset).'elemű részhalmaza van '
			.The($set[0]).' $\{'.implode(";", $set).'\}$ halmaznak?';
		$correct = binomial_coeff(count($set), $subset_size);
		$hints = $this->Hints($set_size, $subset_size);
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Set($numbers, $set_size) {

		for ($i=0; $i < $set_size; $i++) { 
			shuffle($numbers);
			$set[] = array_pop($numbers);
		}

		sort($set);

		return $set;
	}

	function Hints($n, $k) {

		$binom = binomial_coeff($n, $k);

		$page[] = 'Fogalmazzuk át a kérdést: hányféleképpen választhatunk ki $'.$k
			.'$ különböző számot a halmaz $'.$n.'$ eleme közül?';
		$page[] = 'Ezt a számot az <b>ismétlés nélküli kombináció</b> segítségével tudjuk kiszámolni.';
		$page[] = '$'.$n.'$ elem közül $'.$k.'$ különböző darabot '
			.'${'.$n.'\choose '.$k.'}$-féleképpen (ejtsd: <i>"'.$n.' alatt a '
			.$k.'"</i>) lehet kiválasztani.';
		$page[] = '$${'.$n.'\choose '.$k.'}=\frac{'.$n.'!}{'.$n.'!('
			.$n.'-'.$k.')!}=\frac{'.strval(fact($n)).'}{'
			.strval(fact($n)).'\cdot'.strval(fact($n-$k)).'}='.$binom.'$$';
		$page[] = '<b>Megjegyzés</b>: az eredményt számológéppel a <kbd>nCr</kbd> gombbal lehet kiszámolni: '
			.'<kbd>'.$n.'</kbd> <kbd>Shift</kbd> <kbd>nCr</kbd> <kbd>'.$k.'</kbd> <kbd>=</kbd> '
			.'<kbd>'.$binom.'</kbd>.';
		$page[] = 'Tehát a részhalmazok száma <span class="label label-success">$'.$binom.'$</span>.';

		$hints[] = $page;
		return $hints;
	}
}

?>