<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parity {

	// Define parity of number
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		$len = max(1, $level);

		$num = numGen(rand(ceil($len/2),$len), 10);

		$question = 'Páros vagy páratlan az alábbi szám?$$'.$num.'$$';

		$options = array('páros', 'páratlan');
		$index = $num%2;
		$solution = $options[$index];

		shuffle($options);

		$correct = array_search($solution, $options);
		$type = 'quiz';

		if ($num > 9) {
			$hints[] = 'Azt, hogy egy szám páros vagy páratlan, az <b>utolsó számjegy</b> dönti el.';
			$hints[] = 'Ha a szám utolsó számjegye<ul><li>$0$, $2$, $4$, $6$ vagy $8$, akkor a szám <b>páros</b>,</li><li>$1$, $3$, $5$, $7$ vagy $9$, akkor a szám <b>páratlan</b>.</li></ul>';
			$hints[] = ucfirst(The($num)).' $'.$num.'$ utolsó jegye $'.strval($num%10).'$, ezért '.The($num).' $'.$num.'$ <span class="label label-success">'.$solution.'</span>.';
		} else {
			$hints[] = 'A $0$, $2$, $4$, $6$, $8$ <b>páros számok</b>.';
			$hints[] = 'Az $1$, $3$, $5$, $7$, $9$ <b>páratlan számok</b>.';
			$hints[] = ucfirst(The($num)).' $'.$num.'$ <span class="label label-success">'.$solution.'</span> szám.';
		}

		return array(
			'question' 	=> $question,
			'options' 	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type,
			'hints' 	=> $hints
		);
	}
}

?>