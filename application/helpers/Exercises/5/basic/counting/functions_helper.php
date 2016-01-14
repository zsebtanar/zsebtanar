<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*** SZÁMOLÁS ***/

/* Count apples from 1 to 20 */
function count_apples($level=1) {

	if ($level == 1) {
		$num = rand(0,4);
	} elseif ($level == 2) {
		$num = rand(5,9);
	} elseif ($level == 3) {
		$num = rand(10,20);
	}

	$question = 'Hány darab alma van a fán?<div class="text-center"><img class="img-question" width="50%" src="'.RESOURCES_URL.'/count_apples/tree'.$num.'.png"></div>';
	$correct = $num;
	$solution = '$'.$correct.'$';

	return array(
		'question' 		=> $question,
		'correct' 		=> $correct,
		'solution'		=> $solution
	);
}

/* Define parity of number */
function parity($level=1) {

	if ($level == 1) {
		$len = 1;
	} elseif ($level == 2) {
		$len = 3;
	} elseif ($level == 3) {
		$len = 5;
	}

	$num = numGen(rand(ceil($len/2),$len), 10);

	$question = 'Páros vagy páratlan az alábbi szám?$$'.$num.'$$';

	$options = array('páros', 'páratlan');
	$index = $num%2;
	$solution = $options[$index];

	shuffle($options);

	$correct = array_search($solution, $options);
	$type = 'quiz';
	$explanation = 'A $'.$num.'$ azért '.$solution.' szám, mert az utolsó jegye $'.strval($num%10).'$.';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type,
		'explanation' => $explanation
	);
}

/* Count even/odd numbers */
function count_parity($level=1) {

	if ($level == 1) {
		$no = rand(2,3); 
		$len = 1;
	} elseif ($level == 2) {
		$no = rand(4,6);
		$len = 3;
	} elseif ($level == 3) {
		$no = rand(7,10);
		$len = 5;
	}

	for ($i=0; $i < $no; $i++) { 
		$num[$i] = numGen(rand(ceil($len/2),$len), 10);
	}

	$parity = array('páros', 'páratlan');
	$par = rand(0,1);

	$question = 'Hány szám '.$parity[$par].' az alábbiak közül?$$\begin{align}';
	$correct = 0;

	foreach ($num as $key => $value) {
		$correct = ($value%2 == $par ? ++$correct : $correct);
		if ($value > 9999) {
			$value = number_format($value,0,',','\,');
		}
		$question .= $value.' & \\\\';
		$explanation[] = 'A $\textcolor{'.($value % 2 == $par ? 'green' : 'red').'}{'.$value.'}$'
			.' <b>'.$parity[$value%2].'</b> szám, mert az utolsó jegye $'.strval($value%10).'$.';
	}

	$explanation[] = 'Mivel a számok közül összesen $'.$correct.'$ db <b>'.$parity[$par].'</b>, ezért a megoldás is $'.$correct.'$ lesz.';

	$question = rtrim($question, '\\\\').'\end{align}$$';
	$solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'explanation' => $explanation
	);
}
?>