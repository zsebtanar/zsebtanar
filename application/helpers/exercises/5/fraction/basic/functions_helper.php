<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// --- FRACTIONS

// Define numerator
function fraction_numerator($level)
{
  if ($level == 1) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level == 2) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } elseif ($level == 3) {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $question = 'Mekkora a számláló az alábbi törtben?$$\\frac{'.$num.'}{'.$denom.'}$$';
  $correct = $num;
  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

// Define denominator
function fraction_denominator($level)
{
  if ($level == 1) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level == 2) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } elseif ($level == 3) {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $question = 'Mekkora a nevező az alábbi törtben?$$\\frac{'.$num.'}{'.$denom.'}$$';
  $correct = $denom;
  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

// Compare fraction with 1
function fraction_compare_1($level)
{
  if ($level == 1) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level == 2) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } elseif ($level == 3) {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $rand = rand(1,3);

  if ($rand == 1) {
    list($num, $denom) = array($denom, $num);
  } elseif ($rand == 2) {
    $num = $denom;
  }

  $frac = $num/$denom;

  $question = 'Melyik relációs jel kerül a kérdőjel helyére?$$\\frac{'.$num.'}{'.$denom.'}\\qquad?\\qquad1$$';
  $options = array(0 => '>', 1 => '<', 2 => '=');

  if ($frac > 1) {
    $correct = 0;
  } elseif ($frac < 1) {
    $correct = 1;
  } else {
    $correct = 2;
  }

  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'options' 	=> $options,
	);
}

?>