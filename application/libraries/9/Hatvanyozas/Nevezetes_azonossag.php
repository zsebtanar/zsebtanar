<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nevezetes_azonossag {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$eqLeft = array(
			'(a+b)^2',
			'(a-b)^2',
			'a^2-b^2',
			'(a+b)^3',
			'(a-b)^3',
			'a^3+b^3',
			'a^3-b^3',
			'(a+b+c)^2'
			);

		$eqRight = array(
			'a^2+2ab+b^2',
			'a^2-2ab+b^2',
			'(a+b)(a-b)',
			'a^3+3a^2b+3ab^2+b^3',
			'a^3-3a^2b+3ab^2+b^3',
			'(a+b)(a^2-ab+b^2)',
			'(a-b)(a^2+ab+b^2)',
			'a^2+b^2+c^2+2ab+2bc+2ca'
			);

		$ind = rand(0,7);

		$random = rand(1,2);
		if ($random == 2) {
			list($eqLeft, $eqRight) = array($eqRight, $eqLeft);
		}

		$question = 'Mivel egyenlő az alábbi kifejezés?$$'.$eqLeft[$ind].'$$';
		$correct = $ind;
		$options = $eqRight;
		foreach ($options as $key => $equation) {
			$options[$key] = preg_replace('/\^(\d)/', '&sup\1;', $equation);
		}
		shuffleAssoc($options);
		$solution = '$'.$eqRight[$ind].'$';

		$hints_text = '<div class="alert alert-info"><b>Nevezetes azonosságok</b><br/>$$\begin{eqnarray}';

		for ($i=0; $i < 7; $i++) { 
			$hints_text .= ($random==1 ? $eqLeft[$i].'&=&'.$eqRight[$i] : $eqRight[$i].'&=&'.$eqLeft[$i]).'\\\\';
		}

		$hints_text .= '\end{eqnarray}$$</div>';

		$hints[][] = $hints_text;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'options' 	=> $options,
			'solution'	=> $solution,
			'hints' 	=> $hints
		);
	}
}

?>