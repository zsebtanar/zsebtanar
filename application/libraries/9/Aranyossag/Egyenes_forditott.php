<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenes_forditott {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define type of proportionality
	function Generate($level) {

		$sgn1	= rand(0, 1);
		$sgn2	= rand(0, 1);
		$const	= pow(-1, $sgn1) * rand(1, $level);
		$type 	= rand(0, 3);

		$options = ['egyenes arányosságot', 'fordított arányosságot', 'egyiket sem'];

		switch ($type) {
			case 0:
				if ($const == 1) {
					$fun = 'x';
				} elseif ($const == -1) {
					$fun = '-x';
				} else {
					$fun = $const.'x';
				}
				$correct = 0;
				break;
			
			case 1:
				if ($const == 1) {
					$fun = '\sqrt{x}';
				} elseif ($const == -1) {
					$fun = '-\sqrt{x}';
				} else {
					$fun = $const.'\sqrt{x}';
				}
				$correct 	= 2;
				break;

			case 2:
				$fun = ($const < 0 ? '-' : '').'\frac{'.abs($const).'}{x}';
				$correct = 1;
				break;

			case 3:
				$fun = $const.($sgn2 == 0 ? '+' : '-').'x';
				$correct = 2;
				break;
		}

		// // Original exercise
		// $type 	= 3;
		// $const 	= 5;
		// $sgn2 	= 1;

		// $funs = ['-5x','5\sqrt{x}','\frac{5}{x}','5-x'];
		// $corrects = [0,2,1,2];

		// $fun = $funs[$type];
		// $correct = $corrects[$type];

		$question 	= 'Az alábbi függvény a pozitív számok halmazán értelmezett:$$f(x)='.$fun.'$$Milyen arányosságot ír le a függvény?';
		$solution 	= $options[$correct];
		$hints		= $this->Hints($sgn2, $const, $type);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints
		);
	}

	function Hints($sgn2, $const, $type) {

		if ($type == 1) {
			$x 		= 4;
			$mult 	= 9;
		} else {
			$x		= rand(2,7);
			$mult 	= rand(2,6);
		}

		list($y1, $fun1) = $this->GetFunValue($sgn2, $const, $type, $x);
		list($y2, $fun2) = $this->GetFunValue($sgn2, $const, $type, $x*$mult);

		$text[] = 'Számoljuk ki a függvény értékét valahol, pl. az $x='.$x.'$ helyen:$$f('.$x.')='.$fun1.'$$';
		$text[] = 'Most szorozzuk meg az $x$ értékét pl. $'.$mult.'$-'.With($mult).': '
			.'$$'.$mult.'\cdot x='.strval($x*$mult).'$$';
		$text[] = 'Számoljuk ki itt is a függvény értékét:'
			.'$$f('.$mult.'\cdot'.$x.')=f('.strval($x*$mult).')='.$fun2.'$$';
		$hints[] = $text;
		$text = [];

		if ($type == 0) {

			$text[] = 'A <b>fordított arányosság</b> azt jelenti, hogy ahányszor megnövelem az $x$ értékét, '
				.'az $f(x)$ értéke annyiadrészére csökken:$$f('.$mult.'\cdot x)=\frac{1}{'.$mult.'}\cdot f(x)$$';
			$text[] = 'Ez most nem teljesül, mert: $$'.$y2.'\neq\frac{1}{'.$mult.'}\cdot '.($y1>=0 ? $y1 : '('.$y1.')').'$$';
			$text[] = 'Tehát a függvény <span class="label label-danger">nem</span> fordított arányosságot ír le.';
			$hints[] = $text;
			$text = [];

			$text[] = 'Az <b>egyenes arányosság</b> azt jelenti, hogy ahányszor megnövelem az $x$ értékét, '
				.'az $f(x)$ értéke is annyiszorosára nő:$$f('.$mult.'\cdot x)='.$mult.'\cdot f(x)$$';
			$text[] = 'Ez most teljesül, mert: $$'.$y2.'='.$mult.'\cdot '.($y1>=0 ? $y1 : '('.$y1.')').'$$';
			$text[] = 'Könnyen belátható, hogy ez nem csak az $x='.$x.'$-'.On($x).' igaz, hanem akármelyik $x$-re.';
			$text[] = 'Tehát a függvény <span class="label label-success">egyenes arányosságot</span> ír le.';
			$hints[] = $text;
			$text = [];

		} elseif ($type == 2) {

			$text[] = 'Az <b>egyenes arányosság</b> azt jelenti, hogy ahányszor megnövelem az $x$ értékét, '
				.'az $f(x)$ értéke is annyiszorosára nő:$$f('.$mult.'\cdot x)='.$mult.'\cdot f(x)$$';
			$text[] = 'Ez most nem teljesül, mert: $$'.$fun2.'\neq'.$mult.'\cdot '.($y1>=0 ? $fun1 : '('.$fun1.')').'$$';
			$text[] = 'Tehát a függvény <span class="label label-danger">nem</span> egyenes arányosságot ír le.';
			$hints[] = $text;
			$text = [];

			$text[] = 'A <b>fordított arányosság</b> azt jelenti, hogy ahányszor megnövelem az $x$ értékét, '
				.'az $f(x)$ értéke annyiadrészére csökken:$$f('.$mult.'\cdot x)=\frac{1}{'.$mult.'}\cdot f(x)$$';
			$text[] = 'Ez most teljesül, mert: $$'.$fun2.'=\frac{1}{'.$mult.'}\cdot '.($fun1>=0 ? $fun1 : '('.$fun1.')').'$$';
			$text[] = 'Könnyen belátható, hogy ez nem csak az $x='.$x.'$-'.On($x).' igaz, hanem akármelyik $x$-re.';
			$text[] = 'Tehát a függvény <span class="label label-success">fordított arányosságot</span> ír le.';
			$hints[] = $text;
			$text = [];

		} else {

			$text[] = 'Az <b>egyenes arányosság</b> azt jelenti, hogy ahányszor megnövelem az $x$ értékét, '
				.'az $f(x)$ értéke is annyiszorosára nő:$$f('.$mult.'\cdot x)='.$mult.'\cdot f(x)$$';
			$text[] = 'Ez most nem teljesül, mert: $$'.$y2.'\neq'.$mult.'\cdot '.($y1>=0 ? $y1 : '('.$y1.')').'$$';
			$text[] = 'Tehát a függvény <span class="label label-danger">nem</span> egyenes arányosságot ír le.';
			$hints[] = $text;
			$text = [];

			$text[] = 'A <b>fordított arányosság</b> azt jelenti, hogy ahányszor megnövelem az $x$ értékét, '
				.'az $f(x)$ értéke annyiadrészére csökken:$$f('.$mult.'\cdot x)=\frac{1}{'.$mult.'}\cdot f(x)$$';
			$text[] = 'Ez most nem teljesül, mert: $$'.$y2.'\neq\frac{1}{'.$mult.'}\cdot '.($y1>=0 ? $y1 : '('.$y1.')').'$$';
			$text[] = 'Tehát a függvény <span class="label label-danger">nem</span> fordított arányosságot ír le.';
			$text[] = 'Tehát a függvény <span class="label label-success">egyik arányosságot sem</span> írja le.';
			$hints[] = $text;
			$text = [];		
		}

		return $hints;
	}

	function GetFunValue($sgn2, $const, $type, $x) {

		switch ($type) {
			case 0:
				$y 		= $const*$x;
				if ($const == 1) {
					$fun = $x;
				} elseif ($const == -1) {
					$fun = '-'.$x;
				} else {
					$fun = $const.'\cdot'.$x.'='.$y;
				}
				
				break;
			
			case 1:
				$y 		= $const*sqrt($x);
				if ($const == 1) {
					$fun = '\sqrt{'.$x.'}='.$y;
				} elseif ($const == -1) {
					$fun = '-\sqrt{'.$x.'}='.$y;
				} else {
					$fun = $const.'\sqrt{'.$x.'}='.$const.'\cdot'.sqrt($x).'='.$y;
				}

				break;

			case 2:
				$fun 	= ($const < 0 ? '-' : '').'\frac{'.abs($const).'}{'.$x.'}';
				$y 		= $const/$x;
				break;

			case 3:
				$y 		= $const+pow(-1,$sgn2)*$x;
				$fun 	= $const.($sgn2 == 0 ? '+' : '-').$x.'='.$y;
				
				break;
		}

		return array($y, $fun);
	}
}

?>