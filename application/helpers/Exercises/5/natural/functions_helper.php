<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*** NATURAL NUMBERS ***/

/* Define number value */
function decimal_number_value($level=1) {

	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} elseif ($level == 3) {
		$length = rand(5,6);
	}

	$number = numGen($length,10);

	$digits = str_split($number);
	$value = rand(round($length/2),$length);
	$correct = $digits[$length-$value];

	$values = array("az egyesek","a tízesek","a százasok","az ezresek","a tízezresek","a százezresek","a milliósok","a tízmilliósok","a százmilliósok","a milliárdosok");

	if ($number > 9999) {
		$number = number_format($number,0,',','\,');
	}

	if (rand(1,2) == 1) {
		$question = 'Melyik számjegy áll '.$values[$value-1].' helyén az alábbi számban?$$'.$number.'$$';
	} else {
		$question = 'Mi '.$values[$value-1].' helyén álló szám alaki értéke?$$'.$number.'$$';
	}

	$options = '';
	$correct = $correct;
	$solution = '$'.$correct.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

/* Define place value I. */
function decimal_place_value1($level=1)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} elseif ($level == 3) {
		$length = rand(5,6);
	}

	$szam = numGen($length,10);

	$szamjegyek = str_split($szam);
	$helyiertek = rand(round($length/2),$length);
	$szamjegy = $szamjegyek[$length-$helyiertek];

	$szam = modifySameDigits($szam,$length-$helyiertek);

	if (in_array($szamjegy,array(5,1))) {$nevelo = 'az';} else {$nevelo = 'a';}

	if ($szam > 9999) {
		$szam = number_format($szam,0,',','\,');
	}

	$question = 'Melyik helyen áll '.$nevelo.' $'.$szamjegy.'$ az alábbi számban?$$'.$szam.'$$';
	$helyiertekek = array("egyesek","tízesek","százasok","ezresek","tízezresek","százezresek","milliósok","tízmilliósok","százmilliósok","milliárdosok");

	$options = array_slice($helyiertekek,0,$length);
	$options = preg_replace( '/.$/', '$0 helyén.', $options);
	$options = preg_replace( '/^[^e]/', 'A $0', $options);
	$options = preg_replace( '/^e/', 'Az $0', $options);

	$correct = $helyiertek-1;
	$solution = $options[$helyiertek-1];

	$type = 'quiz';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

/* Define place value II. */
function decimal_place_value2($level=1)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} elseif ($level == 3) {
		$length = rand(5,6);
	}

	$szam = numGen($length,10);

	$szamjegyek = str_split($szam);
	$helyiertek = rand(round($length/2),$length);
	$szamjegy = $szamjegyek[$length-$helyiertek];

	$szam = modifySameDigits($szam,$length-$helyiertek);

	if (in_array($szamjegy,array(5,1))) {$nevelo = 'az';} else {$nevelo = 'a';}

	if ($szam > 9999) {
		$szam = number_format($szam,0,',','\,');
	}

	$question = 'Mi '.$nevelo.' $'.$szamjegy.'$ helyiértéke az alábbi számban?$$'.$szam.'$$';
	$helyiertekek = array(1,10,100,1000,10000,100000,1000000,10000000,100000000,1000000000);

	$options = array_slice($helyiertekek,0,$length);
	$options = preg_replace( '/000000000$/', '\\,000000000', $options);
	$options = preg_replace( '/000000$/', '\\,000000', $options);
	$options = preg_replace( '/000$/', '\\,000', $options);
	$options = preg_replace( '/^1/', '\$1', $options);
	$options = preg_replace( '/0$/', '0\$', $options);
	$options = preg_replace( '/1$/', '1\$', $options);

	$correct = $helyiertekek[$helyiertek-1];
	$solution = $options[$helyiertek-1];
	$solution = str_ireplace('\\,','\\\\,',$solution);
	$options = '';

	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

?>