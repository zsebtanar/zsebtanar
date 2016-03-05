<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- COMPARE NUMBERS

// Define relation sign between numbers
function number_compare_sign($level)
{
  if ($level <= 3) {
    $hossz = rand(2,3); 
  } elseif ($level <= 6) {
    $hossz = rand(4,6);
  } else {
    $hossz = rand(7,10);
  }
  
  $szam1 = numGen($hossz,10);
  
  if (rand(1,4) == 1) {
    $szam2 = $szam1;
  } else {
    if (rand(1,4) == 1) {
      if (rand(1,2) == 1) {
        $hossz++;
      } else {
        $hossz--; 
      }
    }
    $szam2 = $szam1 + numGen(rand(floor($hossz/2),$hossz),10);
  }
  
  $relacios_jelek_egyenlo = array("<=",">=","=");
  $relacios_jelek_kisebb = array("<","<=","!=");
  $relacios_jelek_nagyobb = array(">",">=","!=");
  
  shuffle($relacios_jelek_egyenlo);
  shuffle($relacios_jelek_kisebb);
  shuffle($relacios_jelek_nagyobb);
  
  if (rand(1,4) == 1) {
    $szam2 = $szam1;
  }
  
  if ($szam1 == $szam2) {
    $jo = $relacios_jelek_egyenlo[0];
    $options = array($jo,"!=","<",">");
  } elseif ($szam1 > $szam2) {
    $jo = $relacios_jelek_nagyobb[0];
    $options = array($jo,"=","<","<=");
  } else {
    $jo = $relacios_jelek_kisebb[0];
    $options = array($jo,"=",">",">=");
  }
  
  if ($szam1 > 9999) {$szam1 = number_format($szam1,0,',','\,');}
  if ($szam2 > 9999) {$szam2 = number_format($szam2,0,',','\,');}
  
  $options = preg_replace( '/^<$/', '$<$', $options);
  $options = preg_replace( '/^>$/', '$>$', $options);
  $options = preg_replace( '/^=$/', '$=$', $options);
  $options = preg_replace( '/^!=$/', '$\\neq$', $options);
  $options = preg_replace( '/^<=$/', '$\\leq$', $options);
  $options = preg_replace( '/^>=$/', '$\\geq$', $options);
  
  $question = 'Melyik relációs jel írható a kérdőjel helyére?$$'.$szam1.'\quad?\quad'.$szam2.'$$';
  
  $correct = 0;
  $solution = '$'.$jo.'$';
  shuffleAssoc($options);
	$type = 'quiz';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Compare numbers numbers
function number_compare_compare($level)
{
  if ($level <= 3) {
    $hossz = rand(2,3); 
  } elseif ($level <= 6) {
    $hossz = rand(4,6);
  } else {
    $hossz = rand(7,10);
  }
  
  $szam1 = numGen($hossz,10);
  
  if (rand(1,4) == 1) {
    $szam2 = $szam1;
  } else {
    if (rand(1,4) == 1) {
      if (rand(1,2) == 1) {
        $hossz++;
      } else {
        $hossz--; 
      }
    }
    $szam2 = $szam1 + numGen(rand(floor($hossz/2),$hossz),10);
  }
  
  $relacios_jelek_egyenlo = array("legalább akkora, mint","nagyobb vagy egyenlő, mint","legfeljebb akkora, mint","kisebb vagy egyenlő, mint","egyenlő");
  $relacios_jelek_kisebb = array("kisebb, mint","legalább akkora, mint","nagyobb vagy egyenlő, mint","nem egyenlő");
  $relacios_jelek_nagyobb = array("nagyobb, mint","legfeljebb akkora, mint","kisebb vagy egyenlő, mint","nem egyenlő");
  
  shuffle($relacios_jelek_egyenlo);
  shuffle($relacios_jelek_kisebb);
  shuffle($relacios_jelek_nagyobb);
  
  if (rand(1,4) == 1) {
    $szam2 = $szam1;
  }
  
  if ($szam1 == $szam2) {
    $jo = $relacios_jelek_egyenlo[0];
    $options = array($jo,"nem egyenlő","kisebb, mint","nagyobb, mint");
  } elseif ($szam1 > $szam2) {
    $jo = $relacios_jelek_nagyobb[0];
    $options = array($jo,"egyenlő","kisebb, mint","legalább akkora, mint","nagyobb vagy egyenlő, mint");
  } else {
    $jo = $relacios_jelek_kisebb[0];
    $options = array($jo,"egyenlő","nagyobb, mint","legfeljebb akkora, mint","kisebb vagy egyenlő, mint");
  }
  
  if ($szam1 > 9999) {
    $szam1 = number_format($szam1,0,',','\,');
  }
  
  if ($szam2 > 9999) {
    $szam2 = number_format($szam2,0,',','\,');
  }
  
  $question = 'Melyik állítás igaz?$$A:'.$szam1.'\qquad B:'.$szam2.'$$';
  
  $correct = 0;
  $solution = '$'.$jo.'$';
  shuffleAssoc($options);
  
  $options = preg_replace( '/^/', '$A$ ', $options);
  $options = preg_replace( '/$/', ' $B.$', $options);
  $options = preg_replace( '/egyenlő \$B.\$$/', 'egyenlő $B$-vel.', $options);
  $type = 'quiz';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Define order of numbers
function number_compare_order($level)
{
  if ($level <= 3) {
    $hossz = rand(2,3);
    $darab = rand(2,3);
  } elseif ($level <= 6) {
    $hossz = rand(4,6);
    $darab = rand(4,5);
  } else {
    $hossz = rand(7,10);
    $darab = rand(6,7);
  }
  
  $betuk = array("A","B","C","D","E","F","G");
  $szam = numGen($hossz,10);
  
  for ($i=0; $i < $darab; $i++) {
  
    $ujszam = newNum($szam,$hossz);
  
    if ($i == 0) {
      $szamok[$betuk[$i]] = $ujszam;
    } else {
      while (!hasDigit($szamok,$ujszam)) {
        $ujszam = newNum($szam,$hossz);
      }
    }
  
    $szamok[$betuk[$i]] = $ujszam;
  }

  shuffle($betuk);
  
  $felsorolas = '$$\begin{align}';
  foreach ($szamok as $key => $value) {
    if ($value > 9999) {
      $valuenew = number_format($value,0,',','\,');
    } else {
      $valuenew = $value;
    }
    $felsorolas = $felsorolas.$key.'&:&'.$valuenew.'\\\\';
  }
  $felsorolas = $felsorolas.'\end{align}$$';
  
  $options = '';
  
  if (rand(1,2) == 1) {
    $irany = 'csökkenő';
    arsort($szamok);
  } else {
    $irany = 'növekvő';
    asort($szamok);
  }
  
  $question = 'Rendezd a számokat '.$irany.' sorrendbe, és írd le egymás mellé a számokat jelölő betűket!'.$felsorolas;
  
  $correct = '';
  foreach ($szamok as $key => $value) {
    $correct = $correct.$key;
  }
  
  $solution = '$'.$correct.'$';
  $type = 'text';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Define missing digit from comparison
function number_compare_missing_digit($level)
{
  if ($level <= 3) {
    $hossz = rand(2,3);
  } elseif ($level <= 6) {
    $hossz = rand(4,6);
  } else {
    $hossz = rand(7,10);
  }
  
  $relacios_jelek = array("<",">","=","!=","<=",">=");
  shuffle($relacios_jelek);
  $rel = $relacios_jelek[0];
  
  $szamjegyek = range(0, 9);
  
  if ($rel == ">") {
    $szamjegy = rand(2,7);
    $jo = $szamjegy-1;
    $rossz = array_slice($szamjegyek, $szamjegy);
  } elseif ($rel == ">=") {
    $szamjegy = rand(0,8);
    $jo = $szamjegy;
    $rossz = array_slice($szamjegyek, $szamjegy+1);
  } elseif ($rel == "<") {
    $szamjegy = rand(3,8);
    $jo = $szamjegy+1;
    $rossz = array_slice($szamjegyek, 0, $szamjegy+1);
  } elseif ($rel == "<=") {
    $szamjegy = rand(2,9);
    $jo = $szamjegy;
    $rossz = array_slice($szamjegyek, 0, $szamjegy);
  } else {
    $szamjegy = rand(0,9);
    $jo = $szamjegy;
    unset($szamjegyek[array_search($jo, $szamjegyek)]);
    $rossz = array_values($szamjegyek);
  }
  
  $szam = numGen($hossz,10);
  $helyiertek = rand(1,$hossz-1);
  
  if (rand(1,2) == 1) {
    $szam_bal = replaceDigit($szam,$helyiertek,$szamjegy);
    $szam_jobb = replaceDigit($szam,$helyiertek,'?');
  } else {
    $szam_bal = replaceDigit($szam,$helyiertek,'?');
    $szam_jobb = replaceDigit($szam,$helyiertek,$szamjegy);
    $rel = preg_replace( '/</', '>', $rel);
    $rel = preg_replace( '/>/', '<', $rel);
  }
  
  $options[0] = $jo;
  foreach ($rossz as $key => $value) {
    $options[$key+1] = $value;
  }
  
  shuffleAssoc($options);
  
  $correct = 0;
  $solution = '$'.$szamjegy.'$';
  
  if ($rel == "!=") {
    $negacio = ' nem ';
  } else {
    $negacio = ' ';
  }
  
  $rel = preg_replace( '/^<$/', '<', $rel);
  $rel = preg_replace( '/^>$/', '>', $rel);
  $rel = preg_replace( '/^=$/', '=', $rel);
  $rel = preg_replace( '/^!=$/', '\\neq', $rel);
  $rel = preg_replace( '/^<=$/', '\\leq', $rel);
  $rel = preg_replace( '/^>=$/', '\\geq', $rel);
  
  $question = 'Melyik számjegy'.$negacio.'írható a kérdőjel helyére?$$'.$szam_bal.$rel.$szam_jobb.'$$';

  $type = 'quiz';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}

// Calculate number of numbers between limits
function number_compare_between($level)
{
  if ($level <= 3) {
    $intervallum = rand(1,5);
    $hossz = rand(1,2); 
  } elseif ($level <= 6) {
    $intervallum = rand(6,15);
    $hossz = rand(3,6);
  } else {
    $intervallum = rand(16,20);
    $hossz = rand(7,10);
  }
  
  if ($level <= 3 && rand(1,3) == 1) {
    $szam_bal = 0;
  } else {
    $szam_bal = numGen($hossz,10);
  }
  
  $szam_jobb = $szam_bal + $intervallum;
  $correct = $szam_jobb - $szam_bal - 1;
  
  if (rand(1,2) == 1) {
    $hatarpont_bal = TRUE;
    $correct++;
  } else {
    $hatarpont_bal = FALSE;
  }
  
  if (rand(1,2) == 1) {
    $hatarpont_jobb = TRUE;
    $correct++;
  } else {
    $hatarpont_jobb = FALSE;
  }
  
  $opcio_kivalasztott = rand(0,3);
  
  if ($szam_bal > 9999) {$szam_bal = number_format($szam_bal,0,',','\,');}
  if ($szam_jobb > 9999) {$szam_jobb = number_format($szam_jobb,0,',','\,');}
  
  if ($hatarpont_bal) {
    if ($hatarpont_bal == 0 && rand(1,2) == 1) {
      if (rand(1,2 == 1)) {
        $szoveg_bal = '';
      }
    } else {
      $opcio_bal[0] = 'legalább $'.$szam_bal.'$';
      $opcio_bal[1] = 'nem kisebb, mint $'.$szam_bal.'$';
      $opcio_bal[2] = 'nem kisebb $'.$szam_bal.'$-'.By($szam_bal);
      $opcio_bal[3] = '$'.$szam_bal.'$-'.By($szam_bal).' nem kisebb';
      $szoveg_bal = $opcio_bal[$opcio_kivalasztott];
    }
  } else {
    $opcio_bal[0] = 'nagyobb, mint $'.$szam_bal.'$';
    $opcio_bal[1] = 'nagyobb, mint $'.$szam_bal.'$';
    $opcio_bal[2] = 'nagyobb $'.$szam_bal.'$-'.By($szam_bal);
    $opcio_bal[3] = '$'.$szam_bal.'$-'.By($szam_bal).' nagyobb';
    $szoveg_bal = $opcio_bal[$opcio_kivalasztott];
  }
  
  if ($hatarpont_jobb) {
    $opcio_jobb[0] = 'legfeljebb $'.$szam_jobb.'$';
    $opcio_jobb[1] = 'nem nagyobb, mint $'.$szam_jobb.'$';
    $opcio_jobb[2] = 'nem nagyobb $'.$szam_jobb.'$-'.By($szam_jobb);
    $opcio_jobb[3] = '$'.$szam_jobb.'$-'.By($szam_jobb).' nem nagyobb';
    $szoveg_jobb = $opcio_jobb[$opcio_kivalasztott];
  } else {
    $opcio_jobb[0] = 'kisebb, mint $'.$szam_jobb.'$';
    $opcio_jobb[1] = 'kisebb, mint $'.$szam_jobb.'$';
    $opcio_jobb[2] = 'kisebb $'.$szam_jobb.'$-'.By($szam_jobb);
    $opcio_jobb[3] = '$'.$szam_jobb.'$-'.By($szam_jobb).' kisebb';
    $szoveg_jobb = $opcio_jobb[$opcio_kivalasztott];
  }
  
  $options = '';
  
  $question = 'Hány olyan természetes szám van, amely '.$szoveg_bal.' és '.$szoveg_jobb.'?';
  
  $solution = '$'.$correct.'$';

  $type = 'int';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}
?>