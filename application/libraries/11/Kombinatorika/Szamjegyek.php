<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szamjegyek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$length = 1 + $level;

		// // Original exercise
		// $length = 3;

		$question = 'Hány olyan '.($length == 2 ? 'két' : NumText($length)).'jegyű pozitív egész szám van, amelynek minden számjegye különböző?';

		$num_list = range(9, 9-$length+2);
		$correct = 9*array_product($num_list);
		$solution = '$'.$correct.'$';

		$page[] = 'Összesen $10$ számjegy van: $0,1,2,3,4,5,6,7,8,9$.';
		$page[] = 'Az első számjegy helyére a $0$-n kívül bármelyik számjegy kerülhet, ami összesen $10-1=9$ lehetőség.';

		for ($i=1; $i < $length; $i++) { 
			$page[] = The($i+1, TRUE).' '.OrderText($i+1).' számjegy helyére bármelyik számjegy kerülhet, de mivel már felhasználtunk $'.$i.'$ darab számjegyet, már csak $10-'.$i.'='.strval(10-$i).'$ számjegy közül választhatunk.';
		}

		$page[] = 'A megoldást úgy kapjuk meg, ha a lehetőségeket összeszorozzuk:$$9\cdot'.implode('\cdot', $num_list).'='.$correct.'$$';
		$page[] = 'Tehát a megoldás <span class="label label-success">$'.$correct.'$</span>.';
		$hints[] = $page;

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'youtube'	=> 'xBQeR-fidzc'
		);
	}
}

?>