<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- ROMAN NUMBERS

// Convert roman number to decimal
function roman_convert_ROMto10($level)
{
  if ($level == 1) {
    $szam = rand(1,399);
  } elseif ($level == 2) {
    $szam = rand(400,3999);
  } elseif ($level == 3) {
    $szam = numGen(rand(5,6),10);
    while ($szam >= 4000000) {
      $szam = numGen(rand(5,6),10);
    }
  }
  
  $romai_szam = convertRoman($szam);
  
  $correct = $szam;

  if ($szam > 9999) {
    $szam = number_format($szam,0,',','\\\,');
  }
  
  $question = 'Írjuk fel az alábbi római számot tízes számrendszerben!$$\mathrm{'.$romai_szam.'}$$';
  $options = '';
  $answer = '$'.$szam.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'answer'	=> $answer,
		'type' 		=> $type
	);
}

// Convert decimal number to roman
function roman_convert_10toROM($level)
{
  if ($level == 1) {
    $szam = rand(1,399);
  } elseif ($level == 2) {
    $szam = rand(400,1999);
  } elseif ($level == 3) {
    $szam = rand(2000,3999);
  }
  
  $romai_szam = convertRoman($szam);
  
  $question = 'Írjuk fel római számokkal!$$'.$szam.'$$';
  $correct = $romai_szam;
  $options = '';
  $answer = '$\\\\mathrm{'.$romai_szam.'}$';
  $type = 'text';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'answer'  => $answer,
    'type'    => $type
  );
}
?>