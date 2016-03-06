<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Count_Parity {

	// Class constructor
	public function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
	}

	// Count number of even/odd numbers
	function Generate($level) {

		$no = max(2, $level);
		$len = max(1, round($level/2));

		for ($i=0; $i < $no; $i++) { 
			$num[$i] = numGen(rand(ceil($len/2),$len), 10);
		}

		$parity = array('páros', 'páratlan');
		$par = rand(0,1);

		$question = 'Hány szám '.$parity[$par].' az alábbiak közül?$$\begin{align}';
		$correct = 0;
		$explanation[] = 'Ha egy szám utolsó számjegye
			<ul>
				<li>$0$, $2$, $4$, $6$ vagy $8$, akkor a szám <b>páros</b>,</li>
				<li>$1$, $3$, $5$, $7$ vagy $9$, akkor a szám <b>páratlan</b>.</li>
			</ul>';

		foreach ($num as $key => $value) {
			$correct = ($value%2 == $par ? ++$correct : $correct);
			if ($value > 9999) {
				$value = number_format($value,0,',','\,');
			}
			$question .= $value.' & \\\\';
			if ($value > 9) {
				$explanation[] = 'A $'.$value.'$'
					.' <b class="text-'.($value % 2 == $par ? 'success' : 'danger').'">'.$parity[$value%2].'</b> szám, mert az utolsó jegye $'.strval($value%10).'$.';
			} else {
				$explanation[] = 'A $'.$value.'$'
					.' <b class="text-'.($value % 2 == $par ? 'success' : 'danger').'">'.$parity[$value%2].'</b> szám.';
			}
		}

		$explanation[] = 'A számok közül összesen $'.$correct.'$ db <b>'.$parity[$par].'</b>, ezért a megoldás <span class="label label-success">$'.$correct.'$</span>.';

		$question = rtrim($question, '\\\\').'\end{align}$$';
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'explanation' => $explanation
		);
	}
}

?>