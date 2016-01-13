<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- ROUND NUMBERS

// Round number down
function number_round_down($level)
{
  if ($level == 1) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level == 2) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } elseif ($level == 3) {
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
  if ($level == 1) {
    $hossz = rand(1,2);
    $helyiertek = rand(1,$hossz+1);
  } elseif ($level == 2) {
    $hossz = rand(3,6);
    $helyiertek = rand(3,$hossz+1);
  } elseif ($level == 3) {
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

?>