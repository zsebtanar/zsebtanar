<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Range_mean_stdev {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Generate random number between 1 and 20
	function Generate($level) {

		$set = $this->Set($level);

		if ($level <= 3) {
			$question = 'Határozza meg az alábbi adatsor terjedelmét!';
			$correct = array(min($set), max($set));
			$solution = 'az adatsor terjedelme: $['.min($set).';'.max($set).']$';
			$type = 'range';
			$hints = $this->Hints($set, 'range');
		} elseif ($level <= 6) {
			$question = 'Határozza meg az alábbi adatsor átlagát!';
			$correct = array_sum($set)/count($set);
			$solution = '$'.$correct.'$';
			$type = 'int';
			$hints = $this->Hints($set, 'mean');
		} else {
			$question = 'Határozza meg az alábbi adatsor szórását!';
			$correct = stdev($set);
			$solution = '$'.$correct.'$';
			$type = 'int';
			$hints = $this->Hints($set, 'stdev');
		}

		$question .= '$$'.implode(';', $set).'$$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type'		=> $type,
			'hints' 	=> $hints
		);
	}

	function Set($level) {

		if ($level <= 3) {

			$length = rand(2,3);
			for ($i=0; $i < $length; $i++) {
				$set[] = rand(1,5);
			}

		} elseif ($level <= 6) {

			$length = rand(4,5);
			$average = rand(2,5);
			$total = $average*$length; // Total amount to be distributed among numbers
			for ($i=0; $i < $length; $i++) {
				$num = ($total > 0 ? rand(1, $total) : 0);
				$set[] = $num;
				$total -= $num;
			}

		} else {

			$length = 2*rand(5,6);
			$average = rand(2,5);
			$stdev = pow(rand(1,3),2);
			$total = $stdev*$length; // Total amount to be distributed among differences
			for ($i=0; $i < $length/2; $i++) {
				if ($total >= 18 && $average >= 3) {
					$set[] = $average+3;
					$set[] = $average-3;
					$total -= 18;
				} elseif ($total >= 8 && $average >= 2) {
					$set[] = $average+2;
					$set[] = $average-2;
					$total -= 8;
				} elseif ($total >= 2 && $average >= 1) {
					$set[] = $average+1;
					$set[] = $average-1;
					$total -= 2;
				} else {
					$set[] = $average;
					$set[] = $average;
				}
			}
			// print_r($total);
		}

		sort($set);

		return $set;
	}

	function Hints($set, $type) {

		if ($type == 'range') {

			$page[] = 'A terjedelemhez meg kell határoznunk az adatsor minimumát és maximumát.';
			$page[] = 'A minimum: $'.min($set).'$';
			$page[] = 'A maximum: $'.max($set).'$';
			$page[] = 'Az adatsor terjedelme: <span class="label label-success">$['.min($set).';'.max($set).']$</span>.';
			$hints[] = $page;

		} elseif ($type == 'mean') {

			$mean = array_sum($set)/count($set);
			$page[] = 'Az átlaghoz először meg kell határoznunk az adatsor <b>összegét</b>:'
				.'<div class="text-center">$'.implode("$$+$$", $set).'='.strval(array_sum($set)).'$</div>';
			$page[] = 'Most osszuk el az összeget az adatsor <b>méretével</b>:'
				.'$$\frac{'.strval(array_sum($set)).'}{'.count($set).'}='.$mean.'$$';
			$page[] = 'Tehát az adatsor átlaga: <span class="label label-success">$'.$mean.'$</span>.';
			$hints[] = $page;

		} elseif ($type == 'stdev') {

			$mean = array_sum($set)/count($set);
			$page[] = 'A szóráshoz először határozzuk meg az adatsor <b>átlagát</b>. Ehhez adjuk össze a számokat:'
				.'<div class="text-center">$'.implode("$$+$$", $set).'='.strval(array_sum($set)).'$</div>';
			$page[] = 'Most osszuk el az összeget az adatsor méretével:'
				.'$$\frac{'.strval(array_sum($set)).'}{'.count($set).'}='.$mean.'$$';
			$page[] = 'Tehét az adatsor átlaga: $'.$mean.'$.';
			$hints[] = $page;

			$page = [];
			$text = 'Vonjuk ki minden számból az átlagot, és emeljük négyzetre a különbséget:';
			foreach ($set as $key => $number) {
				$diff[$key] = $number - $mean;
				$diffsq[$key] = pow(($number - $mean),2);
				$text .= '$$('.$number.'-'.$mean.')^2='.($number >= $mean ? $diff[$key].'^2' : '('.$diff[$key].')^2')
					.'='.strval($diffsq[$key]).'$$';
			}
			$page[] = $text;
			$hints[] = $page;

			$page = [];
			$page[] = 'Adjuk össze az így kapott számokat:'
				.'<div class="text-center">$'.implode("$$+$$", $diffsq).'='.strval(array_sum($diffsq)).'$</div>';
			$page[] = 'Most osszuk el az összeget az adatsor méretével:'
				.'$$\frac{'.strval(array_sum($diffsq)).'}{'.count($diffsq).'}='.strval(pow(stdev($set),2)).'$$';
			$page[] = 'Ez az adatsor szórásnégyzete. A szórást úgy kapjuk meg, ha ebből gyököt vonunk:'
				.'$$\sqrt{'.strval(pow(stdev($set),2)).'}='.stdev($set).'$$';
			$page[] = 'Tehát az adatsor szórása: <span class="label label-success">$'.stdev($set).'$</span>.';
			$hints[] = $page;

		}

		return $hints;
	}
}

?>