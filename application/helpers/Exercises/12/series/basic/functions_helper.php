<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// SERIES


// Define member of number sequence by explicit formula
function series_explicit($level)
{
  if ($level <= 3) {
    $a0 = rand(1,10);
    $a1 = rand(1,5);
    $a2 = 0;
    $a3 = 0;
    $formula =  ' '.$a1.'\\cdot n+ '.$a0;
    $index = rand(6,8);
  } elseif ($level <= 6) {
    $a0 = pow(-1,rand(1,2))*rand(1,15);
    $a1 = pow(-1,rand(1,2))*rand(1,10);
    $a2 = pow(-1,rand(1,2))*rand(1,5);
    $a3 = 0;
    $formula =  ' '.$a2.'\\cdot n^2+ '.$a1.'\\cdot n+ '.$a0;
    $index = rand(12,20);
  } else {
    $a0 = pow(-1,rand(1,2))*rand(1,20);
    $a1 = pow(-1,rand(1,2))*rand(1,15);
    $a2 = pow(-1,rand(1,2))*rand(1,10);
    $a3 = pow(-1,rand(1,2))*rand(1,5);
    $formula =  ' '.$a3.'\\cdot n^3+ '.$a2.'\\cdot n^2+ '.$a1.'\\cdot n+ '.$a0;
    $index = rand(21,30);
  }

  $formula = str_replace(' 1\\cdot ', ' ', $formula);
  $formula = str_replace(' -1\\cdot ', ' -', $formula);
  $formula = str_replace('+ -', '-', $formula);
  $formula2 = str_replace('n', $index, $formula);
  $formula2 = str_replace('\\', '\\\\', $formula2);

  $correct = $a0 + $a1*$index + $a2*pow($index,2) + $a3*pow($index,3);
  if ($correct > 9999) {
    $correct = number_format($correct,0,',','\,');
  }

  $solution = '$'.$correct.'$ (mert $'.$formula2.'='.$correct.'$)';
  $question = 'Egy sorozat képzési szabálya a következő:$$a_n='.$formula.'$$'
    .'Mekkora $a_{'.$index.'}$ értéke?';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define member of number sequence by recursive formula
function series_recursive($level)
{
  if ($level <= 3) {
    $a1 = pow(-1,rand(1,2))*rand(1,4); // initial value of a1
    $a2 = 0; // initial value of a2
    $coeff_an_1 = pow(-1,rand(1,2))*rand(1,3); // coefficient of a_{n-1} in recursive formula
    $coeff_an_2 = 0; // coefficient of a_{n-1} in recursive formula
    $coeff_n = 0; // coefficient of n in recursive formula
    $pow_n = 0; // power of n in recursive formula
    $coeff_a0 = pow(-1,rand(1,2))*rand(1,5); // constant term in recursive formula
    $index = 2; // index of member we are looking for
    $formula =  ' '.$coeff_an_1.'\\cdot a_{n-1}'
                .'+ '.$coeff_a0; // recursive formula
    $init = '$a_1='.$a1.'$'; // initial values
  } elseif ($level <= 6) {
    $a1 = pow(-1,rand(1,2))*rand(5,10);
    $a2 = 0;
    $coeff_an_1 = pow(-1,rand(1,2))*rand(4,7);
    $coeff_an_2 = 0;
    $coeff_n = pow(-1,rand(1,2))*rand(0,2);
    $pow_n = rand(1,3);
    $coeff_a0 = pow(-1,rand(1,2))*rand(5,10);
    $index = rand(2,3);
    $formula =  ' '.$coeff_an_1.'\\cdot a_{n-1}'
                .'+ '.$coeff_n.'\\cdot n^'.$pow_n
                .'+ '.$coeff_a0;
    $init = '$a_1='.$a1.'$';
  } else {
    $a1 = pow(-1,rand(1,2))*rand(2,4);
    $a2 = pow(-1,rand(1,2))*rand(1,3);
    $coeff_an_1 = pow(-1,rand(1,2))*rand(4,7);
    $coeff_an_2 = pow(-1,rand(1,2))*rand(2,5);
    $coeff_n = pow(-1,rand(1,2))*rand(2,5);
    $pow_n = rand(1,5);
    $coeff_a0 = pow(-1,rand(1,2))*rand(5,10);
    $index = rand(3,5);
    $formula =  ' '.$coeff_an_1.'\\cdot a_{n-1}'
                .'+ '.$coeff_an_2.'\\cdot a_{n-2}'
                .'+ '.$coeff_n.'\\cdot n^'.$pow_n
                .'+ '.$coeff_a0;
    $init = '$a_1='.$a1.'$ és $a_2='.$a2.'$';
  }

  $formula = str_replace(' 1\\cdot ', '', $formula);
  $formula = str_replace(' -1\\cdot ', ' -', $formula);
  $formula = str_replace('+ -', '-', $formula);
  $formula = str_replace('^1+', '+', $formula);
  $formula2 = str_replace('\\', '\\\\', $formula);

  $correct = recursiveSeries($a1, $a2, $coeff_an_1, $coeff_an_2, $coeff_n, $pow_n, $coeff_a0, $index);
  if ($correct > 9999) {
    $correct = number_format($correct,0,',','\,');
  }

  $an_1 = recursiveSeries($a1, $a2, $coeff_an_1, $coeff_an_2, $coeff_n, $pow_n, $coeff_a0, $index-1);
  if ($index > 2 && $coeff_an_2 != 0) {
    $an_2 = recursiveSeries($a1, $a2, $coeff_an_1, $coeff_an_2, $coeff_n, $pow_n, $coeff_a0, $index-2);
    if ($an_2 > 0) {
      $formula2 = str_replace('a_{n-2}', $an_2, $formula2);
    } else {
      $formula2 = str_replace('a_{n-2}', '('.$an_2.')', $formula2);
    }
  }

  if ($an_1 > 0) {
    $formula2 = str_replace('a_{n-1}', $an_1, $formula2);
  } else {
    $formula2 = str_replace('a_{n-1}', '('.$an_1.')', $formula2);
  }
  $formula2 = str_replace('n', $index, $formula2);

  $solution = '$'.$correct.'$ (mert $'.$formula2.'='.$correct.'$)';
  $question = 'Egy sorozat képzési szabálya a következő:$$a_n='.$formula.'$$'
    .'Ha tudjuk, hogy '.$init.', akkor mekkora $a_{'.$index.'}$ értéke?';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

?>