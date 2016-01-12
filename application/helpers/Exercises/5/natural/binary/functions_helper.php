<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// --- BINARY NUMBERS

// Define number value
function binary_number_value($level)
{
  if ($level == 1) {
    $hossz = rand(2,4); 
  } elseif ($level == 2) {
    $hossz = rand(5,8);
  } elseif ($level == 3) {
    $hossz = rand(9,11);
  }
  
  $szam = numGen($hossz,2);
  
  while (count(array_unique(str_split($szam))) == 1) {
    $szam = numGen($hossz,2);
  }
  
  $szamjegyek = str_split($szam);
  $helyiertek = rand(round($hossz/2),$hossz);
  $jo = $szamjegyek[$hossz-$helyiertek];
  
  $numb = array("az egyesek","a kettesek","a négyesek","a nyolcasok","a tizenhatosok","a harminckettesek","a hatvannégyesek","a százhuszonnyolcasok","a kétszázötvenhatosok","az ötszáztizenkettesek","az ezerhuszonnégyesek");
  
  if (strlen($szam) > 8) {
    $szam = preg_replace( '/(\d{4})(\d{4})$/', '\\\\,\1\\\\,\2', $szam);
  }
  elseif (strlen($szam) > 4) {
    $szam = preg_replace( '/(\d{4})$/', '\\\\,\1', $szam);
  }
  
  if (rand(1,2) == 1) {
    $question = 'Melyik számjegy áll '.$numb[$helyiertek-1].' helyén az alábbi számban?$$'.$szam.'_2$$';
  } else {
    $question = 'Mi '.$numb[$helyiertek-1].' helyén álló szám alaki értéke?$$'.$szam.'_2$$';
  }
  
    $options = '';
    $correct = $jo;
    $solution = '$'.$jo.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

?>