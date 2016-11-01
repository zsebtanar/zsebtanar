<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ido {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$units = array(
			array(
				'short' => 'mp',
				'long'	=> 'másodperc',
				'long2'	=> 'másodpercnek',
				'mult'	=> 60
				),
			array(
				'short' => 'p',
				'long'	=> 'perc',
				'long2'	=> 'percnek',
				'mult'	=> 60
				),
			array(
				'short' => 'ó',
				'long'	=> 'óra',
				'long2'	=> 'órának',
				'mult'	=> 24
				),
			array(
				'short' => 'nap',
				'long'	=> 'nap',
				'long2'	=> 'napnak',
				'mult'	=> 7
				),
			array(
				'short' => 'hét',
				'long'	=> 'hét',
				'long2'	=> 'hétnek',
				'mult'	=> 4
				),
			array(
				'short' => 'hónap',
				'long'	=> 'hónap',
				'long2'	=> 'hónapnak',
				'mult'	=> 12
				),
			array(
				'short' => 'év',
				'long'	=> 'év',
				'long2'	=> 'évnek',
				)
			);

		if ($level <= 3) {
			$indexFrom 	= rand(1,2);
			$indexTo 	= $indexFrom - rand(1,1);
		} elseif ($level <= 6) {
			$indexFrom 	= rand(2,3);
			$indexTo 	= $indexFrom - rand(1,2);
		} else {
			$indexFrom 	= rand(3,3);
			$indexTo 	= $indexFrom - rand(2,3);
		}

		$value = rand(1,20) * pow(10, rand(0,2));

		// Calculate multiplier
		$mult = 1;
		for ($i=$indexFrom; $i > $indexTo; $i--) { 
			$mult *= $units[$i-1]['mult'];
		}

		// Switch direction
		if (rand(1,2) == 1) {
			list($indexFrom, $indexTo) = array($indexTo, $indexFrom);
			$correct = $value;
			$value *= $mult;
		} else {
			$correct = $value * $mult;
		}

		$valueText		= BigNum($value);
		$correctText	= BigNum($correct);

		$unitFrom	= $units[$indexFrom];
		$unitTo 	= $units[$indexTo];

		$question = 'Számoljuk ki, hogy $'.$valueText.'$ '.$unitFrom['long'].' hány '.$unitTo['long2'].' felel meg!';

		$solution = '$'.$correctText.'$ '.$unitTo['long'];

		$hints = $this->Hints($units, $indexFrom, $indexTo, $value);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'labels'	=> ['right' => $unitTo['short']]
		);
	}

	function Hints($units, $indexFrom, $indexTo, $value) {

		// First page with details
		$page[] = $this->UnitsSummary($units);

		$details = 'Ez azt jelenti, hogy:<ul>';

		for ($i=0; $i < count($units)-1; $i++) {

			$mult 		= $units[$i]['mult'];
			$unitFrom1 	= $units[$i]['short'];
			$unitFrom2 	= $units[$i]['long'];
			$unitTo1 	= $units[$i+1]['short'];
			$unitTo2 	= $units[$i+1]['long2'];
			$end 		= ($i<count($units)-2 ? ',' : '.');

			$details .= '<li>$'.$mult.'$ '.$unitFrom1.' =$1$ '.$unitTo1.', azaz $'.$mult.'$ '.$unitFrom2.' $1$ '.$unitTo2.' felel meg'.$end.'</li>';

		}

		$details .= '</ul>';

		$page[] = [$details];
		$hints[] = $page;

		// Additional pages

		if ($indexFrom > $indexTo) {

			for ($i=$indexFrom; $i > $indexTo; $i--) {

				$mult 		= $units[$i-1]['mult'];
				$unitFrom 	= $units[$i]['short'];
				$unitTo 	= $units[$i-1]['short'];
				$valueNew 	= $value * $mult;

				$valueText		= BigNum($value);
				$valueNewText	= BigNum($valueNew);

				$result 	= ($i == $indexTo+1 ? '<span class="label label-success">$'.$valueNewText.'$</span>' : $valueNewText); 

				$hints[][] 	= $this->UnitsSummary($units).'Az ábráról leolvasható, hogy $1$ '.$unitFrom.' =$'.$mult.'$ '.$unitTo.', azaz<br /><br /><div class="text-center">$'.$valueText.'$ '.$unitFrom.' =$'.$valueText.'\cdot'.$mult.'$ '.$unitTo.' ='.$result.' '.$units[$i-1]['short'].'</div>';

				$value = $valueNew;
			}

		} else {

			for ($i=$indexFrom; $i < $indexTo; $i++) { 

				$mult 		= $units[$i]['mult'];
				$unitTo 	= $units[$i+1]['short'];
				$unitFrom 	= $units[$i]['short'];
				$valueNew 	= $value / $mult;

				$valueText		= BigNum($value);
				$valueNewText	= BigNum($valueNew);

				$result 	= ($i == $indexTo-1 ? '<span class="label label-success">$'.$valueNewText.'$</span>' : $valueNewText); 

				$hints[][] = $this->UnitsSummary($units).'Az ábráról leolvasható, hogy $'.$mult.'$ '.$unitFrom.' =$1$ '.$unitTo.', azaz<br /><br /><div class="text-center">$'.$valueText.'$ '.$unitFrom.' =$'.$valueText.':'.$mult.'$ '.$unitTo.' ='.$result.' '.$unitTo.'</div>';

				$value = $valueNew;
			}
		}

		return $hints;
	}

	function UnitsSummary($units) {

		$text = '<div class="alert alert-info"><strong>Tömeg-mértékegységek</strong>$$';

		foreach ($units as $index => $unit) {
			$text .= '\text{'.$unit['short'].'}';
			if ($index < count($units)-1) {
				$text .= '\overset{\small{\cdot'.$unit['mult'].'}}{\longrightarrow}';
			}
		}

		$text .= '$$</div>';

		return $text;
	}
}

?>