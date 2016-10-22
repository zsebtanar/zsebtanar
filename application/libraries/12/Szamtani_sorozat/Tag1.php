<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define member of arithmetic & geometric series
	function Generate($level) {

		$d 		= pow(-1,rand(1,2))*rand($level, 2*$level);
		$a0 	= rand(-2*$level, 2*$level);
		$pos 	= rand($level+2, $level+4);

		// // Original exercise
		// $d = -12;
		// $pos = 5;
		// $a0 = 43;

		$a1 	= $a0 + ($pos-2) * $d;
		$a2 	= $a0 + ($pos-1) * $d;

		$question = 'Egy számtani sorozat '.OrderText($pos-1).' tagja $'.$a1.'$, '.OrderText($pos).' tagja $'.$a2.'$. Határozza meg a sorozat első tagját!';

		$correct = $a0;
		$solution = '$'.$correct.'$';

		$page[] = 'Tudjuk, hogy a $a_{'.strval($pos-1).'}='.$a1.'$, és $a_{'.$pos.'}='.$a2.'$.';
		$page[] = 'A differencia a szomszédos tagok különbsége:$$\begin{eqnarray}a_{'.strval($pos-1).'}+d&=&a_{'.$pos.'}\\\\ d&=&a_{'.$pos.'}-a_{'.strval($pos-1).'}\\\\ d&=&'.$a2.'-'.($a1>0 ? $a1 : '('.$a1.')\\\\ d&=&'.$a2.'+'.abs($a1)).'='.$d.'\end{eqnarray}$$';
		$page[] = '<div class="alert alert-info"><strong>Sorozat $n.$ tagja:</strong><br />Ha egy sorozat első eleme $a_1$, és a differenciája $d$, akkor a sorozat $n.$ tagja a következő képlettel számítható:$$a_n=a_1+(n-1)\cdot d$$</div>';
		$hints[] = $page;

		$temp = ($pos-1)*$d;
		$page = [];
		$page[] = 'Írjuk fel ezt az összefüggést $n='.$pos.'$-'.On($pos).'!$$a_{'.$pos.'}=a_1+('.$pos.'-1)\cdot '.($d>0 ? $d : '('.$d.')').'$$';
		$page[] = 'Helyettesítsük be $a_{'.$pos.'}$-'.Dativ($pos).', és számoljuk ki a jobb oldali kifejezést:$$'.$a2.'=a_1+'.strval($pos-1).'\cdot '.($d>0 ? $d : '('.$d.')').'=a_1'.($temp>0 ? '+'.$temp : $temp).'$$';
		if ($temp < 0) {
			$page[] = 'Adjunk hozzá mindkét oldalhoz $'.abs($temp).'$-'.Dativ($temp).'!$$'.$a2.'+'.abs($temp).'=a_1$$';
		} elseif ($temp > 0) {
			$page[] = 'Vonjunk ki mindkét oldalból $'.$temp.'$-'.Dativ($temp).'!$$'.$a2.'-'.$temp.'=a_1$$';
		}
		$page[] = 'Tehát az $a_1$ értéke <span class="label label-success">$'.$a0.'$</span>.';
		$hints[] = $page;
		
		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'youtube'	=> 'AE4dzJdVRyk'
		);
	}
}

?>