<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*** NATURAL NUMBERS ***/

/* Define number value */
function decimal_number_value($level)
{

	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$digit = rand(1, $length);
	$correct = $digits[$length-$digit];

	$values = array(
		"egyesek",
		"tízesek",
		"százasok",
		"ezresek",
		"tízezresek",
		"százezresek",
		"milliósok",
		"tízmilliósok",
		"százmilliósok",
		"milliárdosok"
	);

	$num = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$article = ($digit == 1 || $digit == 4 ? 'az ' : 'a ');

	$question1 = 'Melyik számjegy áll '.$article.$values[$digit-1].' helyén az alábbi számban?$$'.$num.'$$';
	$question2 = 'Mi '.$article.$values[$digit-1].' helyén álló szám alaki értéke?$$'.$num.'$$';
	$question = (rand(1,2) == 1 ? $question1 : $question2);

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
function decimal_place_value1($level)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place_value = rand(round($length/2),$length);
	$digit = $digits[$length-$place_value];

	$num = modifySameDigits($num,$length-$place_value);

	$article = (in_array($digit,array(5,1)) ? 'az' : 'a');

	$num = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$question = 'Melyik helyen áll '.$article.' $'.$digit.'$ az alábbi számban?$$'.$num.'$$';
	$place_values = array(
		"egyesek",
		"tízesek",
		"százasok",
		"ezresek",
		"tízezresek",
		"százezresek",
		"milliósok",
		"tízmilliósok",
		"százmilliósok",
		"milliárdosok"
	);

	$options = array_slice($place_values,0,$length);
	$options = preg_replace( '/.$/', '$0 helyén.', $options);
	$options = preg_replace( '/^[^e]/', 'A $0', $options);
	$options = preg_replace( '/^e/', 'Az $0', $options);

	$correct = $place_value-1;
	$solution = $options[$place_value-1];

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
function decimal_place_value2($level)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place_value = rand(round($length/2),$length);
	$digit = $digits[$length-$place_value];

	$num = modifySameDigits($num,$length-$place_value);

	$article = (in_array($digit,array(5,1)) ? 'az' : 'a');

	$num = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$question = 'Mi '.$article.' $'.$digit.'$ helyiértéke az alábbi számban?$$'.$num.'$$';

	$correct = pow(10, $place_value-1);
	$solution = $options[$place_value-1];
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

/* Define real value */
function decimal_real_value($level)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place_value = rand(round($length/2),$length);
	$digit = $digits[$length-$place_value];

	$num = modifySameDigits($num,$length-$place_value);

	$article = (in_array($digit,array(5,1)) ? 'az' : 'a');

	$num = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$question = 'Mi '.$article.' $'.$digit.'$ helyiértéke az alábbi számban?$$'.$num.'$$';
	$place_values = array(1,10,100,1000,10000,100000,1000000,10000000,100000000,1000000000);

	$options = array_slice($place_values,0,$length);
	$options = preg_replace( '/000000000$/', '\\,000000000', $options);
	$options = preg_replace( '/000000$/', '\\,000000', $options);
	$options = preg_replace( '/000$/', '\\,000', $options);
	$options = preg_replace( '/^1/', '\$1', $options);
	$options = preg_replace( '/0$/', '0\$', $options);
	$options = preg_replace( '/1$/', '1\$', $options);

	$correct = $place_values[$place_value-1];
	$solution = $options[$place_value-1];
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