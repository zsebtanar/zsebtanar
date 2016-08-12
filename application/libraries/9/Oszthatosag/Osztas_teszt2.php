<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osztas_teszt2 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Test for square root
	function Generate($level) {

		$rules = array(
			2 	=> 'az utolsó számjegy osztható $2$-vel',
			3 	=> 'a számjegyek összege osztható $3$-mal',
			4 	=> 'az utolsó $2$ számjegyből alkotott szám osztható $4$-gyel',
			5 	=> 'az utolsó számjegy osztható $5$-tel',
			8 	=> 'az utolsó $3$ számjegyből alkotott szám osztható $8$-cal',
			9 	=> 'a számjegyek összege osztható $9$-cel',
			10	=> 'az utolsó számjegy nulla',
			25 	=> 'az utolsó $2$ számjegyből alkotott szám osztható $25$-tel',
			100	=> 'az utolsó $2$ számjegy nulla'
		);

		$numbers = array_keys($rules);
		shuffle($numbers);

		$num1 = $numbers[0];
		$num2 = (rand(1,2) == 1 ? $num1 : $numbers[1]);

		// // Original exercise
		// $num1 = 3;
		// $num2 = 3;

		$question = 'Adja meg az alábbi állítás logikai értékét (igaz vagy hamis)!<br />Ha egy számban '.$rules[$num1].', akkor a szám osztható $'.$num2.'$-'.With($num2).'.';

		$hints = $this->Hints($num1, $num2, $rules);

		// Statement is true if
		// 1) two numbers are equal OR
		// 2) second number is divisor of first one
		$correct 	= ($num1 == $num2 || $num1 % $num2 == 0 ? 0 : 1);
		$options 	= ['Igaz', 'Hamis'];
		$solution 	= $options[$correct];

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints
		);
	}

	function Hints($num1, $num2, $rules) {

		$page[] = 'Írjunk fel néhány számot, amire igaz a feltétel, és nézzük meg, hogy oszthatók-e $'.$num2.'$-'.With($num2).':';

		$mult = 1;
		for ($i=1; $i < 5; $i++) {

			$mult = $mult*rand(5,20) + rand(10,30);

			if ($mult*$num1 % $num2 == 0) {
				$page[] = '<ul><li>$'.strval($mult*$num1).'$: ez a szám osztható $'.$num2.'$-'.With($num2).'.</li></ul>';
			} else {
				$page[] = '<ul><li>$'.strval($mult*$num1).'$: ez a szám nem osztható $'.$num2.'$-'.With($num2).'.</li></ul>';
				break;
			}
		}

		if ($num1 == $num2 || $num1 % $num2 == 0) {
			$page[] = 'Meg lehet mutatni, hogy az összes ilyen szám osztható $'.$num2.'$-'.With($num2).'.';
			$page[] = 'Tehát ez az állítás <span class="label label-success">igaz</span>.';
		} else {
			$page[] = 'Mivel találtunk egy ellenpéldát, ezért az állítás <span class="label label-success">hamis</span>.';
		}

		$hints[] = $page;

		return $hints;

	}
}

?>