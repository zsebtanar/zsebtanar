<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- ROUND NUMBERS

// Round number down
function number_round_down($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("egyesekre","tízesekre","százasokra","ezresekre","tízezresekre","százezresekre","milliósokra","tízmilliósokra","százmilliósokra","milliárdosokra");
  
  $szam = numGen($hossz,10);
  $correct = floor($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1);
  
  $options = '';
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Kerekítsük lefelé '.$numb[$helyiertek-1].' az alábbi számot!$$'.$szam.'$$';
  
  if ($correct > 9999) {
    $solution = '$'.number_format($correct,0,',','\\,').'$';
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

// Round number up
function number_round_up($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("egyesekre","tízesekre","százasokra","ezresekre","tízezresekre","százezresekre","milliósokra","tízmilliósokra","százmilliósokra","milliárdosokra");
  
  $szam = numGen($hossz,10);
  $correct = ceil($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1);
  
  $options = '';
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Kerekítsük felfelé '.$numb[$helyiertek-1].' az alábbi számot!$$'.$szam.'$$';
  
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

// Round number
function number_round($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("egyesekre","tízesekre","százasokra","ezresekre","tízezresekre","százezresekre","milliósokra","tízmilliósokra","százmilliósokra","milliárdosokra");
  
  $szam = numGen($hossz,10);
  $correct = round($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1);
  
  $options = '';
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Kerekítsük '.$numb[$helyiertek-1].' az alábbi számot!$$'.$szam.'$$';
  
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

// Round number down - error
function number_round_error_down($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("egyesekre","tízesekre","százasokra","ezresekre","tízezresekre","százezresekre","milliósokra","tízmilliósokra","százmilliósokra","milliárdosokra");
  
  $szam = numGen($hossz,10);
  $szamkerek = floor($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1); 
  $correct = abs($szam-$szamkerek);;
  
  $options = '';
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Mekkora lesz a kerekítési hiba, ha az alábbi számot '.$numb[$helyiertek-1].' kerekítjük lefelé?$$'.$szam.'$$';
  
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

// Round number up - error
function number_round_error_up($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("egyesekre","tízesekre","százasokra","ezresekre","tízezresekre","százezresekre","milliósokra","tízmilliósokra","százmilliósokra","milliárdosokra");
  
  $szam = numGen($hossz,10);
  $szamkerek = ceil($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1); 
  $correct = abs($szam-$szamkerek);
  
  $options = '';
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Mekkora lesz a kerekítési hiba, ha az alábbi számot '.$numb[$helyiertek-1].' kerekítjük felfelé?$$'.$szam.'$$';
  
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

// Round number - error
function number_round_error($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("egyesekre","tízesekre","százasokra","ezresekre","tízezresekre","százezresekre","milliósokra","tízmilliósokra","százmilliósokra","milliárdosokra");
  
  $szam = numGen($hossz,10);
  $szamkerek = round($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1); 
  $correct = abs($szam-$szamkerek);
  
  $options = '';
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Mekkora lesz a kerekítési hiba, ha az alábbi számot '.$numb[$helyiertek-1].' kerekítjük?$$'.$szam.'$$';
  
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

// Calculate how many numbers can have same rounded form
function number_round_how_many($level)
{
  if ($level <= 3) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } else {
    $hossz = rand(7,10);
    $helyiertek = rand(6,min($hossz+1,10));
  }
  
  $numb = array("az egyesekre","a tízesekre","a százasokra","az ezresekre","a tízezresekre","a százezresekre","a milliósokra","a tízmilliósokra","a százmilliósokra","a milliárdosokra");
  
  $szam = numGen($hossz,10);
  $szamkerek = round($szam/pow(10,$helyiertek-1))*pow(10,$helyiertek-1); 
  
  $options = '';
  
  if ($szamkerek > 9999) { $szamkerek = number_format($szamkerek,0,',','\,');}
  
  $question = 'Hány olyan természetes szám van, aminek '.$numb[$helyiertek-1].' kerekített értéke $'.$szamkerek.'$?';
  
  if ($szamkerek == 0) {
    if ($helyiertek == 1) {
      $correct = 1;
    } else {
      $correct = 5*pow(10,$helyiertek-2);
    }
  } else {
    $correct = pow(10,$helyiertek-1);
  }
  
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

// Define which number cannot be rounded form
function number_round_not_rounded($level)
{
  if ($level <= 3) {
    $hossz = rand(2,3);
  } elseif ($level <= 6) {
    $hossz = rand(3,6);
  } else {
    $hossz = rand(7,10);
  }
  
  $szam = numGen($hossz,10);
  
  for ($i=0; $i < $hossz+1; $i++) {
    $options_jo[$i] = round($szam/pow(10,$i))*pow(10,$i);
    $options_fel[$i] = ceil($szam/pow(10,$i))*pow(10,$i);
    $options_le[$i] = floor($szam/pow(10,$i))*pow(10,$i);
    $options_rossz[$i] = round($szam/pow(10,$i)+1)*pow(10,$i);
    $options_rossz_fel[$i] = ceil($szam/pow(10,$i)+1)*pow(10,$i);
    $options_rossz_le[$i] = floor($szam/pow(10,$i)+1)*pow(10,$i);
  }
  
  $sikerult = FALSE;
  $numb = range(0,$hossz);
  shuffle($numb);
  
  foreach ($numb as $value) {
    if (!$sikerult && !in_array($options_fel[$value],$options_jo) && $options_le[$value] != 0) {
      $rossz = $options_fel[$value];
      $options_jo[$value] = $rossz;
      $sikerult = TRUE;
    } elseif (!$sikerult && !in_array($options_le[$value],$options_jo) && $options_le[$value] != 0) {
      $rossz = $options_le[$value];
      $options_jo[$value] = $rossz;
      $sikerult = TRUE;
    }
  }
  
  if (!$sikerult) {
    foreach ($numb as $value) {
      if (!$sikerult && !in_array($options_rossz[$value],$options_jo) && $options_rossz[$value] != 0) {
        $rossz = $options_rossz[$value];
        $options_jo[$value] = $rossz;
        $sikerult = TRUE;
      } elseif (!$sikerult && !in_array($options_rossz_le[$value],$options_jo) && $options_rossz_le[$value] != 0) {
        $rossz = $options_rossz_le[$value];
        $options_jo[$value] = $rossz;
        $sikerult = TRUE;
      } elseif (!$sikerult && !in_array($options_rossz_fel[$value],$options_jo) && $options_rossz_fel[$value] != 0) {
        $rossz = $options_rossz_fel[$value];
        $options_jo[$value] = $rossz;
        $sikerult = TRUE;
      }
    }
  }
  
  if (!$sikerult) {
    print_r('Feladatgenerálás sikertelen. Kérlek, frissítsd a honlapot!');
  }
  
  $options = array_unique($options_jo);
  $correct = array_search($rossz, $options_jo);
  
  if ($options[$correct] > 9999) {
    $solution = '$'.number_format($options[$correct],0,',','\\\\,').'$';
  } else {
    $solution = '$'.$options[$correct].'$';
  }
  
  foreach ($options as $key => $value) {
    if ($value > 9999) {
      $options[$key] = '$'.number_format($value,0,',','\,').'$';
    } else {
      $options[$key] = '$'.$value.'$';
    }
  }
  
  if ($szam > 9999) { $szam = number_format($szam,0,',','\,');}
  
  $question = 'Melyik nem lehet az alábbi szám kerekített értéke?$$'.$szam.'$$';
  $type = 'quiz';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}
?>