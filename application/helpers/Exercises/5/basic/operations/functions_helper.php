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
  if ($num1 > 999) { $num1 = number_format($num1,0,',','\,');}
  if ($num2 > 999) { $num2 = number_format($num2,0,',','\,');}
  $question = 'Adjuk össze az alábbi számokat!$$\begin{align}'.$num1.'\\\\ +\,'.$num2.'\\\\ \hline?\end{align}$$';

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }

  $explanation = basic_addition_explanation($num1, $num2);

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

function basic_addition_explanation($num1, $num2)
{
  $explanation[] = 'A utolsó oszloptól kezdve minden oszlopban összeadjuk a számjegyeket.';
  $explanation[] = 'Az eredmény utolsó jegyét beírjuk az oszlop alá, a többit pedig a szomszéd oszlop fölé (legközelebb ezt is összeadjuk a többivel).';

  $digits1 = str_split($num1);
  $digits2 = str_split($num2);
  $digits_sum = str_split($num1+$num2);

  print_r(array_pop($digits1));
  print_r($digits1);
  print_r($digits2);
  print_r($digits_sum);


  $length = max(count($digits1), count($digits2));

  for ($i=0; $i < $length; $i++) {
    $article = (in_array($i, [1,5]) ? 'az' : 'a');
    $digit1 = array_pop($digits1);
    $digit2 = array_pop($digits2);
    $sum = $digit1 + $digit2;
    if ($sum >= 10) {
      $digits_sum = str_split($sum);
      
      $digit_sum1 = array_pop($digits_sum);
      $digit_sum2 = array_pop($digits_sum);
      
      $article_sum1 = addArticle($digit_sum1);
      $article_sum2 = addArticle($digit_sum2);
      
      $dativ1 = addSuffixDativ($digit_sum1);
      $dativ2 = addSuffixDativ($digit_sum2);
      $article2 = (in_array($i+1, [1,5]) ? 'az' : 'a');

      $number_top = $digit_sum2;
    } else {
      $number_top = '';
    }
    $text = basic_addition_generate_number($num1, $num2, $number_top, $i);
    print_r($text);
  }

  return;
}

// Generate addition for explanation
function basic_addition_generate_number($num1, $num2, $number_top=NULL, $value=NULL)
{
  return;
}

// Addition modified
function basic_addition_mod($level)
{
  if ($level == 1) {
    $minhossz = 1;
    $maxhossz = 2;
    $modosit1 = rand(1,2);
    $modosit2 = rand(1,2);
  } elseif ($level == 2) {
    $minhossz = 3;
    $maxhossz = 6;
    $modosit1 = rand(3,4);
    $modosit2 = rand(3,4);
  } elseif ($level == 3) {
    $minhossz = 7;
    $maxhossz = 10;
    $modosit1 = rand(5,9);
    $modosit2 = rand(5,9);
  }
  
  $num1 = numGen(rand($minhossz,$maxhossz),10);
  $num2 = numGen(rand($minhossz,$maxhossz),10);
  $szam3 = $num1+$num2;
  
  if (rand(1,2) == 1) {
    $num1b = $num1 + $modosit1;
  } else {
    $num1b = max(0,$num1 - $modosit1);
  }
  
  if ($level > 1) {
    if (rand(1,2) == 1) {
      $szam2b = $num2 + $modosit2;
    } else {
      $szam2b = max(0,$num2 - $modosit2);
    }
  } else {
    $szam2b = $num2;
  }
  
  if ($level > 1) {
    list($num1, $num2) = array($num2, $num1);
  }
  
  $options = '';
  $correct = $num1b+$szam2b;
  if ($num1 > 9999) { $num1 = number_format($num1,0,',','\,');}
  if ($num1b > 9999) { $num1b = number_format($num1b,0,',','\,');}
  if ($num2 > 9999) { $num2 = number_format($num2,0,',','\,');}
  if ($szam2b > 9999) { $szam2b = number_format($szam2b,0,',','\,');}
  if ($szam3 > 9999) { $szam3 = number_format($szam3,0,',','\,');}
  $question = 'Ha tudjuk, hogy $$'.$num1.'+'.$num2.'='.$szam3.',$$akkor mennyivel egyenlő $$'.$num1b.'+'.$szam2b.'?$$';

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }
  $type = 'int';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}


?>