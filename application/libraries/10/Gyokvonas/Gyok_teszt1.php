<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyok_teszt1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Test for square root
	function Generate($level) {

		if ($level <= 3) {
			$num = rand(2,3);
		} elseif ($level <= 6) {
			$num = rand(4,6);
		} else {
			$num = rand(7,15);
		}

		$question = 'Adja meg az alábbi állítás logikai értékét (igaz vagy hamis)!';

		$subtype = rand(1,2);

		if ($subtype == 1) {
			$question .= '$$\sqrt{(-'.$num.')^2}='.$num.'$$';
			$answer = TRUE;
			$page[] = 'Először emeljük négyzetre a gyökjel alatti számot, majd végezzük el a gyökvonást:'
				.'$$\sqrt{(-'.$num.')^2}=\sqrt{'.strval(pow($num,2)).'}='.$num.'$$';
			$page[] = 'Tehát az állítás <span class="label label-success">igaz</span>.';
			$hints[] = $page;
		} elseif ($subtype == 2) {
			$question .= '$$\sqrt{-'.$num.'^2}='.$num.'$$';
			$answer = FALSE;
			$page[] = 'Először emeljük négyzetre a gyökjel alatti számot:'
				.'$$\sqrt{-'.$num.'^2}=\sqrt{-'.strval(pow($num,2)).'}$$';
			$page[] = 'A műveletet nem tudjuk elvégezni, mert negatív számból nem tudunk gyököt vonni.';
			$page[] = 'Tehát az állítás <span class="label label-success">hamis</span>.';
			$hints[] = $page;
		}

		$correct = ($answer ? 0 : 1);
		$options = ['Igaz', 'Hamis'];
		$solution = $options[$correct];

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints
		);
	}
}

?>