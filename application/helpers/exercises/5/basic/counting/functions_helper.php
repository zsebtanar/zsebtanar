<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*** SZÁMOLÁS ***/

/* Count apples from 1 to 20 */
function count_apples($level) {

	$num = rand(max(0,2*($level-2)), min(20,3*$level));

	$question = 'Hány darab alma van a fán?<div class="text-center"><img class="img-question" height="200px" src="'.RESOURCES_URL.'/count_apples/tree'.$num.'.png"></div>';
	$correct = $num;
	$solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

/* Define parity of number */
function parity($level) {

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
		$explanation[] = 'Azt, hogy egy szám páros vagy páratlan, az <b>utolsó számjegy</b> dönti el.';
		$explanation[] = 'Ha a szám utolsó számjegye<ul><li>$0$, $2$, $4$, $6$ vagy $8$, akkor a szám <b>páros</b>,</li><li>$1$, $3$, $5$, $7$ vagy $9$, akkor a szám <b>páratlan</b>.</li></ul>';
		$explanation[] = ucfirst(addArticle($num)).' $'.$num.'$ utolsó jegye $'.strval($num%10).'$, ezért '.addArticle($num).' $'.$num.'$ <b class="text-success">'.$solution.'</b>.';
	} else {
		$explanation[] = 'A $0$, $2$, $4$, $6$, $8$ <b>páros számok</b>.';
		$explanation[] = 'Az $1$, $3$, $5$, $7$, $9$ <b>páratlan számok</b>.';
		$explanation[] = ucfirst(addArticle($num)).' $'.$num.'$ <b>'.$solution.'</b> szám.';
	}

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
function count_parity($level) {

	$no = max(1, $level);
	$len = max(1, round($level/2));

	for ($i=0; $i < $no; $i++) { 
		$num[$i] = numGen(rand(ceil($len/2),$len), 10);
	}

	$parity = array('páros', 'páratlan');
	$par = rand(0,1);

	$question = 'Hány szám '.$parity[$par].' az alábbiak közül?$$\begin{align}';
	$correct = 0;
	$explanation[] = 'Ha egy szám utolsó számjegye<ul><li>$0$, $2$, $4$, $6$ vagy $8$, akkor a szám <b>páros</b>,</li><li>$1$, $3$, $5$, $7$ vagy $9$, akkor a szám <b>páratlan</b>.</li></ul>';

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

	$explanation[] = 'A számok közül összesen $'.$correct.'$ db <b>'.$parity[$par].'</b>, ezért a megoldás $\textcolor{green}{'.$correct.'}$.';

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