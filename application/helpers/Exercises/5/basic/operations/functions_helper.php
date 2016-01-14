<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- BASIC OPERATIONS

// Addition
function basic_addition($level)
{
  $num1 = numGen($level,10);
  $num2 = numGen($level,10);
  
  if ($num2 < $num1) {
    list($num1, $num2) = array($num2, $num1);
  }
  
  $correct = $num1+$num2;
  $num1b = ($num1 > 999 ? $num1b = number_format($num1,0,',','\,') : $num1);
  $num2b = ($num2 > 999 ? $num2b = number_format($num2,0,',','\,') : $num2);
  $question = 'Adjuk össze az alábbi számokat!$$\begin{align}'.$num1b.'\\\\ +\,'.$num2b.'\\\\ \hline?\end{align}$$';

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }

  $explanation = basic_addition_explanation($num1, $num2);

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
    'explanation' => $explanation
	);
}

// Explanation for addition
function basic_addition_explanation($num1, $num2)
{
  $digits1  = str_split($num1);
  $digits2  = str_split($num2);
  $digits3  = str_split($num1+$num2);
  $length   = count($digits3);

  $remain_old = 0;
  $remain_new = 0;

  $values = array(
    "az egyesek",
    "a tízesek",
    "a százasok",
    "az ezresek",
    "a tízezresek",
    "a százezresek",
    "a milliósok",
    "a tízmilliósok",
    "a százmilliósok",
    "a milliárdosok"
  );

  for ($i=0; $i < $length; $i++) {

    $digit1 = array_pop($digits1);
    $digit2 = array_pop($digits2);

    $sum = $digit1 + $digit2 + $remain_old;
    $text = '';


    $text = 'Adjuk össze '.$values[$i].' helyén lévő számjegyeket'.
      ($remain_old > 0 ? ' (az előző számolásnál kapott maradékkal együtt)' : '').
      (!$digit1 || !$digit2 ? '! Az üres helyekre $0$-t írunk: ' : '').
      ': $'.($remain_old > 0 ? '\textcolor{green}{'.$remain_old.'}+' : '').
      ($digit1 ? $digit1 : 0).'+'.
      ($digit2 ? $digit2 : 0).'='.$sum.'$.';

    if ($sum >= 10) {
      $text .= ' Mivel ez többjegyű szám, ezért az utolsó jegyét leírjuk '.$values[$i].' oszlopába, az elsőt pedig '
      .$values[$i+1].' oszlopa fölé:';
      $remain_new = ($sum / 10) % 10;
    }

    $text .= basic_addition_generate_equation(array($num1, $num2), $i); 

    $explanation[] = $text;

    $remain_old = $remain_new;
    $remain_new = 0;
  }

  return $explanation;
}

/**
 * Generate equation for addition
 *
 * Generates equation for adding numbers at specific place value
 *
 * @param array $numbers Numbers to add
 * @param int   $colum   Column of place value
 *
 * @return string $equation Equation
 */
function basic_addition_generate_equation($numbers, $column)
{
  // Get digits for each number
  foreach ($numbers as $number) {
    $digits_num = str_split($number);
    $digits_all[] = $digits_num;
    $line_num[] = '';
  }

  $sum = array_sum($numbers);
  $digits_sum = str_split($sum);
  $length = count($digits_sum);
  $line_sum = '';
  $remain_old = 0;
  $remain_new = 0;

  $equation = '$$\begin{align}';

  for ($i=0; $i < $length; $i++) { 

    // Get digits of current column
    $digits_column = [];
    foreach ($digits_all as $key => $digits) {
      $digits_column[] = array_pop($digits);
      $digits_all[$key] = $digits;
    }
    $digit_sum = array_pop($digits_sum);

    // Get new remainer
    $sum_column = array_sum($digits_column) + $remain_old;
    if ($sum_column >= 10) {
      $remain_new = ($sum_column/10) % 10;
    }

    // Update equation
    if ($i <= $column) {
      if ($i == $column) {

        // Include remainer
        $equation .= ($remain_new > 0 ? '\tiny{\textcolor{red}{'.$remain_new.'}}\,' : '').
          ($column == $length-1 && $remain_new > 0 ? '' : '&').
          ($remain_old > 0 ? '\,\tiny{\textcolor{blue}{'.$remain_old.'}}\,' : '').
          ($column == $length-1 && $remain_new > 0 ? '&' : '').'\\\\ ';

        $line_sum = '\hline '.
          '&\textcolor{red}{'.$digit_sum.'}'.$line_sum;
      } else {
         $line_sum = $digit_sum.$line_sum;
        if ($i % 3 == 2) {
          $line_sum = '\,'.$line_sum;
        }
      }
    }

    // Store equation lines
    foreach ($digits_column as $key => $digit) {
      if ($i == $column) {
        $line_num[$key] = ($digit != NULL ? '&\textcolor{blue}{'.$digit.'}' : '&\,\,\,').$line_num[$key];
      } else {
        $line_num[$key] = ($digit != NULL ? $digit : '\,\,').$line_num[$key];
      }
      if ($i % 3 == 2) {
        $line_num[$key] = '\,'.$line_num[$key];
      }
    }

    $remain_old = $remain_new;
    $remain_new = 0;
  }

  // Concatenate lines
  foreach ($line_num as $key => $line) {
    if ($key+1 == count($line_num)) {
      $equation .= '+';
    }
    $equation .= $line.'\\\\ ';
  }

  // Include sum
  $equation .= $line_sum.'\end{align}$$';

  return $equation;
}

?>