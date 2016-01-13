<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- COMPARE NUMBERS

// Define relation sign between numbers
function number_compare_sign($level)
{
  if ($level == 1) {
    $hossz = rand(2,3); 
  } elseif ($level == 2) {
    $hossz = rand(4,6);
  } elseif ($level == 3) {
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
  if ($level == 1) {
    $hossz = rand(2,3); 
  } elseif ($level == 2) {
    $hossz = rand(4,6);
  } elseif ($level == 3) {
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
  if ($level == 1) {
    $hossz = rand(2,3);
    $darab = rand(2,3);
  } elseif ($level == 2) {
    $hossz = rand(4,6);
    $darab = rand(4,5);
  } elseif ($level == 3) {
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
?>