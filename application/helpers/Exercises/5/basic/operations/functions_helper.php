<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- BASIC OPERATIONS

// Addition
function basic_addition($level)
{
  if ($level == 1) {
    $minhossz = 1;
    $maxhossz = 2;
  } elseif ($level == 2) {
    $minhossz = 3;
    $maxhossz = 6;
  } elseif ($level == 3) {
    $minhossz = 7;
    $maxhossz = 10;
  }
  
  $szam1 = numGen(rand($minhossz,$maxhossz),10);
  $szam2 = numGen(rand($minhossz,$maxhossz),10);
  
  if ($szam2 < $szam1) {
    list($szam1, $szam2) = array($szam2, $szam1);
  }
  
  $options = '';
  $correct = $szam1+$szam2;
  if ($szam1 > 999) { $szam1 = number_format($szam1,0,',','\,');}
  if ($szam2 > 999) { $szam2 = number_format($szam2,0,',','\,');}
  $question = 'Adjuk össze az alábbi számokat!$$\begin{align}'.$szam1.'\\\\ +\,'.$szam2.'\\\\ \hline?\end{align}$$';

  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\\\,').'$';
  } else {
    $solution = '$'.$correct.'$';
  }
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
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
  
  $szam1 = numGen(rand($minhossz,$maxhossz),10);
  $szam2 = numGen(rand($minhossz,$maxhossz),10);
  $szam3 = $szam1+$szam2;
  
  if (rand(1,2) == 1) {
    $szam1b = $szam1 + $modosit1;
  } else {
    $szam1b = max(0,$szam1 - $modosit1);
  }
  
  if ($level > 1) {
    if (rand(1,2) == 1) {
      $szam2b = $szam2 + $modosit2;
    } else {
      $szam2b = max(0,$szam2 - $modosit2);
    }
  } else {
    $szam2b = $szam2;
  }
  
  if ($level > 1) {
    list($szam1, $szam2) = array($szam2, $szam1);
  }
  
  $options = '';
  $correct = $szam1b+$szam2b;
  if ($szam1 > 9999) { $szam1 = number_format($szam1,0,',','\,');}
  if ($szam1b > 9999) { $szam1b = number_format($szam1b,0,',','\,');}
  if ($szam2 > 9999) { $szam2 = number_format($szam2,0,',','\,');}
  if ($szam2b > 9999) { $szam2b = number_format($szam2b,0,',','\,');}
  if ($szam3 > 9999) { $szam3 = number_format($szam3,0,',','\,');}
  $question = 'Ha tudjuk, hogy $$'.$szam1.'+'.$szam2.'='.$szam3.',$$akkor mennyivel egyenlő $$'.$szam1b.'+'.$szam2b.'?$$';

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