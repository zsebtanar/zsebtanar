<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- BASIC OPERATIONS

// Addition
function basic_addition($level)
{
  $length1 = $level;
  $length2 = max(rand(round($level/2), $level), 10);

  $num1 = numGen($length1, 10);
  $num2 = numGen($length2, 10);
  
  $correct = $num1+$num2;
  $question = 'Adjuk össze az alábbi számokat!'.basic_addition_generate_equation(array($num1, $num2));

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }

  $explanation = basic_addition_explanation(array($num1, $num2));

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
    'explanation' => $explanation
	);
}

// Explanation for addition
function basic_addition_explanation($num_array, $type='addition')
{
  foreach ($num_array as $key => $num) {
    $digits_num = str_split($num);

    if ($type == 'multiplication') {
      for ($i=0; $i < count($num_array)-$key-1; $i++) { 
        $digits_num[] = NULL;
      }
    }

    $digits_all[] = $digits_num;
    $lengths_all[] = count($digits_num);
  }

  $length = max($lengths_all);

  $remain_old = 0;
  $remain_new = 0;

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
    "milliárdosok",
    "tízmilliárdosok",
    "százmilliárdosok"
  );

  for ($ind=0; $ind < $length; $ind++) {

    $digits = [];

    foreach ($digits_all as $key => $digits_num) {
      $digit = array_pop($digits_num);
      if ($digit != NULL) {
        $digits[] = $digit;
      }
      $digits_all[$key] = $digits_num;
    }

    $sum_sub = array_sum($digits) + $remain_old;
    $text = '';

    $text = 'Adjuk össze '.(in_array($ind, [0,4]) ? 'az' : 'a').' '.$values[$ind].' helyén lévő számjegyeket'.
      ($remain_old > 0 ? ' (az előző számolásnál kapott maradékkal együtt):' : ':');

    if (count($digits) > 1 || $remain_old > 0) {
      $text .= ' $'.($remain_old > 0 ? '\textcolor{green}{'.$remain_old.'}+' : '').
        implode('+', $digits).'='.$sum_sub.'$.';
    }

    if ($sum_sub >= 10 && $ind != $length-1) {
      $text .= ' Írjuk le az utolsó jegyet '.$values[$ind].' oszlopába, az elsőt pedig '
      .$values[$ind+1].' oszlopa fölé:';
      $remain_new = ($sum_sub / 10) % 10;
    }

    $text .= basic_addition_generate_equation($num_array, $ind, $type); 

    $explanation[] = $text;

    $remain_old = $remain_new;
    $remain_new = 0;
  }

  return $explanation;
}

/**
 * Generate equation for addition
 *
 * Generates equation for adding numbers at specific place value.
 * For 'multiplication' type addition, digits are linearly shifted.
 *
 * @param array  $numbers Numbers to add
 * @param int    $col     Column index of place value
 * @param string $type    Type of addition (addition/multiplication)
 *
 * @return string $equation Equation
 */
function basic_addition_generate_equation($numbers, $col=-1, $type='addition')
{
  // Get digits for each number
  foreach ($numbers as $key => $number) {
    $digits_num = str_split($number);

    if ($type == 'multiplication') {
      for ($i=0; $i < count($numbers)-$key-1; $i++) { 
        $digits_num[] = NULL;
      }
    }

    $digits_all[] = $digits_num;
    $lengths_all[] = count($digits_num);
    $eq_lines[] = '';
  }

  $length = max($lengths_all);

  $remain_old = 0;
  $remain_new = 0;

  $eq_header = '';
  $eq_sum = '';

  for ($ind=0; $ind < $length; $ind++) { 

    // Get digits of current column
    $digits = [];

    foreach ($digits_all as $key => $digits_num) {
      $digits[] = array_pop($digits_num);
      $digits_all[$key] = $digits_num;
    }

    // Define remainer
    $sum_sub = array_sum($digits) + $remain_old;
    if ($sum_sub >= 10 && $ind != $length-1) {
      $remain_new = ($sum_sub/10) % 10;
      $sum_sub = $sum_sub % 10;
    }

    // Update header
    if ($ind <= $col) {
      if ($ind == $col) {

        if ($remain_old > 0) {
          $eq_header = '\,\textcolor{blue}{\tiny{'.$remain_old.'}}\,'.$eq_header;
        } else {
          $eq_header = '\phantom{\normalsize{0}}'.$eq_header;
        }

        if ($remain_new > 0) {
          $eq_header = '\textcolor{red}{\tiny{'.$remain_new.'}}\,'.$eq_header;
        }

        $eq_sum = '\textcolor{red}{'.$sum_sub.'}'.$eq_sum;

      } else {

        $eq_header = '\phantom{\normalsize{0}}'.$eq_header;
        $eq_sum = $sum_sub.$eq_sum;

        if ($ind % 3 == 2) {
          $eq_header = '\,'.$eq_header;
          $eq_sum = '\,'.$eq_sum;
        }
      }
    }

    // Store equation lines
    foreach ($digits as $key => $digit) {
      $digit = ($digit == NULL ? '\phantom{0}' : $digit);
      if ($ind == $col) {
        $eq_lines[$key] = '\textcolor{blue}{'.$digit.'}'.$eq_lines[$key];
      } else {
        $eq_lines[$key] = $digit.$eq_lines[$key];
      }
      if ($ind % 3 == 2) {
        $eq_lines[$key] = '\,'.$eq_lines[$key];
      }
    }

    $remain_old = $remain_new;
    $remain_new = 0;
  }



  if ($col == -1) {
    $eq_sum = '?';
  }

  // Include sum
  $equation = '$$\begin{align}'.$eq_header.'&\\\\ ';
  foreach ($eq_lines as $key => $eq_line) {
    if ($key+1 == count($eq_lines)) {
      $equation .= '+\,';
    }
    $equation .= $eq_line.'&\\\\ ';
  }

  $equation .= '\hline'.$eq_sum.'\end{align}$$';

  return $equation;
}

// Subtraction
function basic_subtraction($level)
{
  $length1 = $level;
  $length2 = max(rand(round($level/2), $level), 10);

  $num1 = numGen($length1, 10);
  $num2 = numGen($length2, 10);

  if ($num1 < $num2) {
    list($num1, $num2) = array($num2, $num1);
  }
  
  $correct = $num1-$num2;
  $question = 'Végezzük el az alábbi kivonást!'.basic_subtraction_generate_equation($num1, $num2);

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }

  $explanation = basic_subtraction_explanation($num1, $num2);

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'explanation' => $explanation
  );
}

// Explanation for subtraction
function basic_subtraction_explanation($num1, $num2)
{
  $digits1 = str_split($num1);
  $digits2 = str_split($num2);

  $length1 = count($digits1);
  $length2 = count($digits2);

  $remain_old = 0;
  $remain_new = 0;

  $diff = $num1 - $num2;
  $diff_digits = str_split($diff);
  $diff_length = count($diff_digits);

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
    "milliárdosok",
    "tízmilliárdosok",
    "százmilliárdosok"
  );

  for ($ind=0; $ind < $length1; $ind++) {

    // Get digits of current column
    $digit1 = array_pop($digits1);
    $digit2 = array_pop($digits2);

    $digit2b = $digit2 + $remain_old;

    // Define remainer
    $result_sub = $digit1 - $digit2b;
    if ($result_sub < 0 && $ind != $length1-1) {
      $remain_new = 1;
      $result_sub += 10;
      $digit1b = $digit1 + 10;
      $result_digit = $result_sub % 10;
    } else {
      $digit1b = $digit1;
      $result_digit = $result_sub;
    }

    $text = '';

    $text = 'Nézzük meg '.(in_array($ind, [0,4]) ? 'az' : 'a').' '.$values[$ind].' helyén lévő számjegyeket! ';
    if ($remain_old > 0 && $digit2 != NULL) {
      $text .='(Az előző számolásnál kapott maradékot '.AddArticle($digit2).' $'.$digit2.'$-'.AddSuffixTo($digit2)
        .' adjuk: $'.$digit2.'+'.$remain_old.'='.$digit2b.'$.) ';
    } elseif ($digit2 == NULL) {
      $text .= 'Az üres helyre $0$-t írunk'.($remain_old > 0 ? ', viszont a maradékot ne felejtsük el hozzászámolni! ' : '. ');
    }

    $text .= 'Mennyit kell adni '.AddArticle($digit2b).' $'.$digit2b.'$-'.AddSuffixTo($digit2b).', hogy $'
      .$digit1b.'$-'.AddSuffixDativ($digit1b).' kapjunk? $'.$result_sub.'$-'.AddSuffixDativ($result_sub).', mert $'
      .$digit2b.'+'.$result_sub.'='.$digit1b.'$. ';

    if ($remain_new == 1) {
      $article = AddArticle($result_digit);
      $Article = str_replace('a', 'A', $article);
      $text .= $Article.' $'.$result_digit.'$-'.AddSuffixDativ($result_digit).' leírjuk alulra, az $1$-et pedig '
        .(in_array($ind+1, [0,4]) ? 'az' : 'a').' '.$values[$ind+1].' fölé:';
    } elseif ($result_digit != 0 || $ind < $diff_length) {
      $text .= 'Az eredményt írjuk le alulra:';
      
    }

    $text .= basic_subtraction_generate_equation($num1, $num2, $ind);

    $explanation[] = $text;

    $remain_old = $remain_new;
    $remain_new = 0;
  }

  return $explanation;
}

/**
 * Generate equation for subtraction
 *
 * @param int $num1 First number
 * @param int $num2 Second number
 * @param int $col  Column index of place value
 *
 * @return string $equation Equation
 */
function basic_subtraction_generate_equation($num1, $num2, $col=-1)
{
  // Get digits for each number
  $digits1 = str_split($num1);
  $digits2 = str_split($num2);

  $length1 = count($digits1);
  $length2 = count($digits2);

  $diff = $num1 - $num2;
  $diff_digits = str_split($diff);
  $diff_length = count($diff_digits);

  $remain_old = 0;
  $remain_new = 0;

  $eq_header = '';
  $eq_has_header = FALSE;
  $eq_sum = '';
  $eq_line1 = '';
  $eq_line2 = '';

  for ($ind=0; $ind < $length1; $ind++) { 

    // Get digits of current column
    $digit1 = array_pop($digits1);
    $digit2 = array_pop($digits2);

    // Define remainer
    $result_sub = $digit1 - ($digit2 + $remain_old);
    if ($result_sub < 0 && $ind != $length1-1) {
      $remain_new = 1;
      $result_sub += 10;
    }

    // Update header
    if ($ind <= min($col, $diff_length-1)) {
      if ($ind == $col) {

        if ($remain_old > 0) {
          $eq_header = '\,\textcolor{blue}{\tiny{'.$remain_old.'}}\,'.$eq_header;
          $eq_has_header = TRUE;
        } else {
          $eq_header = '\phantom{\normalsize{0}}'.$eq_header;
        }

        if ($remain_new > 0) {
          $eq_header = '\textcolor{red}{\tiny{'.$remain_new.'}}\,'.$eq_header;
          $eq_has_header = TRUE;
        }

        $eq_sum = '\textcolor{red}{'.$result_sub.'}'.$eq_sum;

      } else {

        $eq_header = '\phantom{\normalsize{0}}'.$eq_header;
        $eq_sum = $result_sub.$eq_sum;

        if ($ind % 3 == 2) {
          $eq_header = '\,'.$eq_header;
          $eq_sum = '\,'.$eq_sum;
        }
      }
    }

    // Store equation lines
    $digit2 = ($digit2 == NULL ? '\phantom{0}' : $digit2);
    if ($ind == $col) {
      $eq_line1 = '\textcolor{blue}{'.$digit1.'}'.$eq_line1;
      $eq_line2 = '\textcolor{blue}{'.$digit2.'}'.$eq_line2;
    } else {
      $eq_line1 = $digit1.$eq_line1;
      $eq_line2 = $digit2.$eq_line2;
    }
    if ($ind % 3 == 2) {
      $eq_line1 = '\,'.$eq_line1;
      $eq_line2 = '\,'.$eq_line2;
    }

    $remain_old = $remain_new;
    $remain_new = 0;
  }

  if ($col == -1) {
    $eq_sum = '?';
  }

  // Include sum
  $equation = '$$\begin{align}';
  $equation .= $eq_line1.'&\\\\ ';
  $equation .= ($eq_has_header ? $eq_header.'&\\\\ ' : '');
  $equation .= '-\,'.$eq_line2.'&\\\\ ';
  $equation .= '\hline'.$eq_sum.'\end{align}$$';

  return $equation;
}

// Multiplication
function basic_multiplication($level)
{
  if ($level == 1) {
    $num1 = numGen(rand(5,6), 10);
    $num2 = numGen(1, 9);
  } elseif ($level == 2) {
    $num1 = numGen(rand(3,4), 10);
    $num2 = numGen(10, 32);
  } else {
    $num1 = numGen($level, 10);
    $num2 = numGen(33, 99);
  }

  if ($num1 < $num2) {
    list($num1, $num2) = array($num2, $num1);
  }

  $correct = $num1*$num2;
  $num1b = ($num1 > 999 ? $num1b = number_format($num1,0,',','\,') : $num1);
  $num2b = ($num2 > 999 ? $num2b = number_format($num2,0,',','\,') : $num2);
  $question = 'Szorozzuk össze az alábbi számokat!'.basic_multiplication_generate_equation($num1, $num2);

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }

  $explanation = basic_multiplication_explanation($num1, $num2);

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'explanation' => $explanation
  );
}

// Explanation for multiplication
function basic_multiplication_explanation($num1, $num2)
{
  $digits1 = str_split($num1);
  $digits2 = str_split($num2);

  $length1 = count($digits1);
  $length2 = count($digits2);

  $remain_old = 0;
  $remain_new = 0;

  $order = array(
    "első",
    "második",
    "harmadik",
    "negyedik",
    "ötödik",
    "hatodik",
    "hetedik",
    "nyolcadik",
    "kilencedik",
    "tizedik"
  );

  // Multiply numbers
  for ($ind2=$length2-1; $ind2 >= 0; $ind2--) {

    $digit2 = $digits2[$length2-1-$ind2];
    $num_array[] = $digit2*$num1;
    $step = $length2-$ind2;

    if ($length2 > 1) {
      $intro = '<b>'.$step.'. lépés:</b> A második szám '.$order[$ind2].' számjegye $'.$digit2.'$. ';
      if ($length1 > 1) {
        $intro .= 'Szorozzuk meg ezzel '.AddArticle($num1).' $'.$num1.'$ minden számjegyét hátulról kezdve!';
      } else {
        $intro .= 'Szorozzuk meg ezzel '.AddArticle($num1).' $'.$num1.'$-'.AddArticle($num1).'!';
      }
    } else {
      $intro = 'Szorozzuk meg '.AddArticle($digit2).' $'.$digit2.'$-'.AddSuffixWith($digit2).' ';
      if ($length1 > 1) {
        $intro .= 'az első szám minden számjegyét hátulról kezdve!';
      } else {
        $intro .= 'az első számot!';
      }
    }

    $explanation[] = $intro;

    $text = [];

    for ($ind1=0; $ind1 < $length1; $ind1++) {

      $digit1 = $digits1[$length1-1-$ind1];
      $mult = $digit1 * $digit2 + $remain_old;

      $subtext = 'Szorozzuk meg '.(in_array($length1-1-$ind1, [0, 4]) ? 'az' : 'a').' '.$order[$length1-1-$ind1].' számjegyet';
      if ($remain_old != 0) {
        $subtext .= ' (ne felejtsük el hozzáadni az előbb kapott $'.$remain_old.'$-'.AddSuffixDativ($remain_old).'!): $'.$digit2.'\cdot'.$digit1.'+'.$remain_old.'='.$mult.'$!';
      } else {
        $subtext .= ': $'.$digit2.'\cdot'.$digit1.'='.$mult.'$!';
      }

      if ($mult >= 10 && $ind1 != $length1-1) {

        $digit_next = $digits1[$length1-2-$ind1];
        $remain_new = floor($mult/10);
        $mult2 = $mult % 10;
        $subtext .= ' Írjuk '.AddArticle($mult2).' $'.$mult2.'$-'.AddSuffixDativ($mult2).' alulra, '.AddArticle($remain_new).' $'.$remain_new.'$-'.AddSuffixDativ($remain_new).' pedig '.AddArticle($digit_next).' $'.$digit_next.'$ fölé:';
      } else {
        $subtext .= ' Írjuk az eredményt alulra:';
      }

      $remain_old = $remain_new;
      $remain_new = 0;

      $subtext .= basic_multiplication_generate_equation($num1, $num2, $ind1, $ind2);

      $text[] = $subtext;
    }

    $explanation[] = $text;
  }

  // Add subtotals
  if (count($num_array) > 1) {
    $step = $length2;
    $explanation[] = '<b>'.$step.'. lépés:</b> Adjuk össze a szorzás során kapott számokat!';

    $explanation[] = basic_addition_explanation($num_array, 'multiplication');
  }

  return $explanation;
}

/**
 * Generate equation for multiplication
 *
 * Generates equation for multiplying numbers at specific place value
 *
 * @param int $num1 First number
 * @param int $num2 Second number
 * @param int $col1 Column of place value for $num1
 * @param int $col2 Column of place value for $num2
 *
 * @return string $equation Equation
 */
function basic_multiplication_generate_equation($num1, $num2, $col1=-1, $col2=-1)
{
  $digits1 = str_split($num1);
  $digits2 = str_split($num2);

  $length1 = count($digits1);
  $length2 = count($digits2);

  $remain_old = 0;
  $remain_new = 0;

  $equation = '$$\begin{align}';
  $eq_first_row = '\underline{';

  // First number
  foreach ($digits1 as $key => $digit) {
    if ($col1 == $length1-1-$key) {
      $eq_first_row .= '\textcolor{blue}{'.$digit.'}';
    } else {
      $eq_first_row .= $digit;
    }
  }

  $eq_first_row .= '}&\cdot';

  // Second number
  foreach ($digits2 as $key => $digit) {
    if ($col2 == $length2-1-$key) {
      $eq_first_row .= '\textcolor{blue}{'.$digit.'}';
    } else {
      $eq_first_row .= $digit;
    }
  }

  $eq_first_row .= '\\\\ ';


  // Equation lines
  if ($col1 == -1 && $col2 == -1) {

    $eq_lines = '?&';
    $eq_header = '';

  } else {

    $eq_lines = '';
    $eq_header = '';

    for ($ind2=$length2-1; $ind2 >= $col2; $ind2--) {

      $line = '';
      $digit2 = $digits2[$length2-1-$ind2];

      if ($ind2 == $col2) { // current line

        for ($ind1=0; $ind1 < $length1; $ind1++) {

          $digit1 = $digits1[$length1-1-$ind1];
          $mult = $digit1 * $digit2 + $remain_old;

          if ($mult >= 10 && $ind1 != $length1-1) {
            $remain_new = floor($mult / 10);
            $mult_digit = $mult % 10;
          } else {
            $mult_digit = $mult;
          }
          
          $mult_digit = (is_null($mult_digit) ? '\phantom{0}' : $mult_digit);
          $mult_digit = ($ind1 > $col1 ? '\phantom{0}' : $mult_digit);

          if ($ind1 == $length2-1-$col2) {

            if ($ind1 == $col1) {
              $line = '\textcolor{red}{'.$mult_digit.'}&'.$line;
            } else {
              $line = $mult_digit.'&'.$line;
            }

          } else {

            if ($ind1 == $col1) {
              $line = '\textcolor{red}{'.$mult_digit.'}'.$line;
            } else {
              $line = $mult_digit.$line;
            }
          }

          // Equation header
          if ($ind1 == $col1) {

            if ($remain_old != 0) {
              $eq_header = '\,\tiny{\textcolor{blue}{'.$remain_old.'}}\,'.$eq_header;
            } else {
              $eq_header = '\phantom{\normalsize{0}}'.$eq_header;
            }

            if ($remain_new != 0) {
              $eq_header = '\tiny{\textcolor{red}{'.$remain_new.'}}\,'.$eq_header;
            }

          } elseif ($ind1 < $col1) {

            $eq_header = '\phantom{\normalsize{0}}'.$eq_header;

          }
          
          $remain_old = $remain_new;
          $remain_new = 0;
        }

      } else { // complete line

        $mult = $digit2 * $num1;
        $mult_digits = str_split($mult);
        $mult_length = count($mult_digits);

        for ($ind1=0; $ind1<$mult_length; $ind1++) {

          $mult_digit = $mult_digits[$mult_length-1-$ind1];
          $ind = $length2-1-$ind2;
          $line = $mult_digit.($ind1 == $length2-1-$ind2 ? '&' : '').$line;
        }
      }

      $eq_lines .= $line.'\\\\ ';
    }
  }

  $eq_header .= '&\\\\ ';

  $equation .= $eq_header.$eq_first_row.$eq_lines.'\end{align}$$';

  return $equation;
}

// Division
function basic_division($level)
{
  $dividend = max(1, numGen($level, 10));

  if ($level == 1) {
    $divisor = rand(2, 9);
  } elseif ($level == 2) {
    $divisor = rand(11, 19);
  } else {
    $divisor = rand(20, 99);
  }

  $dividend = 9876432;
  $divisor = 12;

  if ($dividend < $divisor) {
    list($dividend, $divisor) = array($divisor, $dividend);
  }

  $quotient = floor($dividend / $divisor);
  $remain = $dividend % $divisor;
  $question = 'Szorozzuk össze az alábbi számokat!'.basic_division_generate_equation($dividend, $divisor);

  if ($quotient > 9999) {
    $solution = '$'.number_format($quotient,0,',','\\,').'$';
  } else {
    $solution = '$'.$quotient.'$';
  }

  $solution .= ', maradék $'.$remain.'$';

  phpinfo();

  // $explanation = basic_division_explanation($dividend, $divisor);
  $explanation = 'basic_division_explanation($dividend, $divisor)';

  return array(
    'question'  => $question,
    'correct'   => json_encode(array($quotient,$remain)),
    'solution'  => $solution,
    'type'      => 'division',
    'explanation' => $explanation
  );
}

// Explanation for division
function basic_division_explanation($dividend, $divisor)
{
  $digits = str_split($num1);

  $order = array(
    "első",
    "második",
    "harmadik",
    "negyedik",
    "ötödik",
    "hatodik",
    "hetedik",
    "nyolcadik",
    "kilencedik",
    "tizedik"
  );

  // Multiply numbers
  for ($ind=0; $ind < count($digits); $ind++) {

    $digit = $digits2[$length2-1-$ind];
    $num_array[] = $digit2*$num1;
    $step = $length2-$ind;

    if ($length2 > 1) {
      $intro = '<b>'.$step.'. lépés:</b> A második szám '.$order[$ind].' számjegye $'.$digit2.'$. ';
      if ($length1 > 1) {
        $intro .= 'Szorozzuk meg ezzel '.AddArticle($num1).' $'.$num1.'$ minden számjegyét hátulról kezdve!';
      } else {
        $intro .= 'Szorozzuk meg ezzel '.AddArticle($num1).' $'.$num1.'$-'.AddArticle($num1).'!';
      }
    } else {
      $intro = 'Szorozzuk meg '.AddArticle($digit2).' $'.$digit2.'$-'.AddSuffixWith($digit2).' ';
      if ($length1 > 1) {
        $intro .= 'az első szám minden számjegyét hátulról kezdve!';
      } else {
        $intro .= 'az első számot!';
      }
    }

    $explanation[] = $intro;

    $text = [];

    for ($ind1=0; $ind1 < $length1; $ind1++) {

      $digit1 = $digits1[$length1-1-$ind1];
      $mult = $digit1 * $digit2 + $remain_old;

      $subtext = 'Szorozzuk meg '.(in_array($length1-1-$ind1, [0, 4]) ? 'az' : 'a').' '.$order[$length1-1-$ind1].' számjegyet';
      if ($remain_old != 0) {
        $subtext .= ' (ne felejtsük el hozzáadni az előbb kapott $'.$remain_old.'$-'.AddSuffixDativ($remain_old).'!): $'.$digit2.'\cdot'.$digit1.'+'.$remain_old.'='.$mult.'$!';
      } else {
        $subtext .= ': $'.$digit2.'\cdot'.$digit1.'='.$mult.'$!';
      }

      if ($mult >= 10 && $ind1 != $length1-1) {

        $digit_next = $digits1[$length1-2-$ind1];
        $remain_new = floor($mult/10);
        $mult2 = $mult % 10;
        $subtext .= ' Írjuk '.AddArticle($mult2).' $'.$mult2.'$-'.AddSuffixDativ($mult2).' alulra, '.AddArticle($remain_new).' $'.$remain_new.'$-'.AddSuffixDativ($remain_new).' pedig '.AddArticle($digit_next).' $'.$digit_next.'$ fölé:';
      } else {
        $subtext .= ' Írjuk az eredményt alulra:';
      }

      $remain_old = $remain_new;
      $remain_new = 0;

      $subtext .= basic_multiplication_generate_equation($num1, $num2, $ind1, $ind);

      $text[] = $subtext;
    }

    $explanation[] = $text;
  }

  // Add subtotals
  if (count($num_array) > 1) {
    $step = $length2;
    $explanation[] = '<b>'.$step.'. lépés:</b> Adjuk össze a szorzás során kapott számokat!';

    $explanation[] = basic_addition_explanation($num_array, 'multiplication');
  }

  return $explanation;
}

/**
 * Generate equation for division
 *
 * Generates equation for dividing numbers at specific place value
 *
 * @param int $dividend Dividend
 * @param int $divisor  Divisor
 * @param int $col      Column of place value (start from beginning)
 *
 * @return string $equation Equation
 */
function basic_division_generate_equation($dividend, $divisor, $col=3)
{
  $digits = str_split($dividend);

  // Dividend
  $eq_dividend = '';
  foreach ($digits as $ind => $digit) {
    if ($ind == $col) {
      $eq_dividend .= '\textcolor{green}{\dot{'.$digit.'}}';
    } else {
      $eq_dividend .= $digit;
    }
  }
  if ($col != -1) {
    $eq_dividend = '\underline{'.$eq_dividend.'}';
  }

  // Divisor
  $eq_divisor = '';
  if ($col == -1) {
    $eq_divisor = strval($divisor);
  } else {
    $eq_divisor = '\textcolor{blue}{'.strval($divisor).'}';
  }

  // Result
  $eq_quotient = ($col == -1 ? '?' : '');
  $remain_prev = 0;
  for ($ind=0; $ind <= $col; $ind++) {

    $digit = $digits[$ind];
    $dividend_current = 10 * $remain_prev + $digit;
    $quotient_current = floor($dividend_current/$divisor);

    // Current result
    $digit_current = $quotient_current % 10;
    if ($ind < $col && ($digit_current != 0 || $eq_quotient != '')) {
        $eq_quotient .= $digit_current;
    } elseif ($digit_current != 0) {
      $eq_quotient .= '\textcolor{red}{'.$digit_current.'}';
    }

    // Equation lines
    $remain_current = $dividend_current % $divisor;
    $check_current  = $quotient_current * $divisor;
    $eq_space       = '';
    for ($j=0; $j < count($digits)-$ind-1; $j++) { 
      $eq_space .= '\phantom{0}';
    }
    if ($ind == $col) {
      $eq_lines[] = '\textcolor{blue}{'.($remain_prev == 0 ? '' : $remain_prev).$digit.'}'.$eq_space.'&';
    } elseif ($eq_quotient != '') {
      $eq_lines[] = $remain_prev.$digit.$eq_space.'&';
    }
    if ($quotient_current != 0) {
      $eq_lines[] = '-\underline{'.$check_current.'}'.$eq_space.'&';
      if ($ind == $col) {
        $eq_lines[] = '\textcolor{red}{'.$remain_current.'}'.$eq_space.'&';
      }
    }

    $remain_prev = $remain_current;
  }

  $equation = '$$\begin{align}'.$eq_dividend.'&:'.$eq_divisor.'='.$eq_quotient.'\\\\ ';
  $equation .= implode('\\\\ ', $eq_lines);
  $equation .= '\end{align}$$';

  return $equation;
}
?>