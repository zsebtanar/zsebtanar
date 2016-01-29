<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- BINARY NUMBERS

// Define number value
function binary_number_value($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
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

// Define place value I.
function binary_place_value1($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }
  
  $szam = numGen($hossz,2);
  
  $szamjegyek = str_split($szam);
  $helyiertek = rand(round($hossz/2),$hossz);
  
  $szam2 = '';
  foreach ($szamjegyek as $key => $value) {
    if ($key == $hossz-$helyiertek) {
      $value = '\textcolor{red}{'.$value.'}';
    }
    if ($hossz-$key == 4 || $hossz-$key == 8) {
      $szam2 = $szam2.'\,';
    }
    $szam2 = $szam2.$value;
  }
  
    $question = 'Melyik helyen áll a piros számjegy az alábbi számban?$$'.$szam2.'_2$$';
    $numb = array("egyesek","kettesek","négyesek","nyolcasok","tizenhatosok","harminckettesek","hatvannégyesek","százhuszonnyolcasok","kétszázötvenhatosok","ötszáztizenkettesek","ezerhuszonnégyesek");
  
    $options = array_slice($numb,0,$hossz);
    $options = preg_replace( '/.$/', '$0 helyén.', $options);
    $options = preg_replace( '/^[^{eö}]/', 'A $0', $options);
    $options = preg_replace( '/^(e)/', 'Az $0', $options);
    $options = preg_replace( '/^(ö)/', 'Az $0', $options);
  
     {
      shuffleAssoc($options);
    }
  
    $correct = $helyiertek-1;
    $solution = $options[$helyiertek-1];
    $type = 'quiz';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Define place value II.
function binary_place_value2($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }
  
  $szam = numGen($hossz,2);
  
  $szamjegyek = str_split($szam);
  $helyiertek = rand(round($hossz/2),$hossz);
  
  $szam2 = '';
  foreach ($szamjegyek as $key => $value) {
    if ($key == $hossz-$helyiertek) {
      $value = '\textcolor{red}{'.$value.'}';
    }
    if ($hossz-$key == 4 || $hossz-$key == 8) {
      $szam2 = $szam2.'\,';
    }
    $szam2 = $szam2.$value;
  }
  
    $question = 'Mi a piros számjegy helyiértéke az alábbi számban?$$'.$szam2.'_2$$';
    $numb = array(1,2,4,8,16,32,64,128,256,512,1024);
  
    $options = array_slice($numb,0,$hossz);
    $options = preg_replace( '/^(\d)/', '\$\1', $options);
    $options = preg_replace( '/(\d)$/', '\1\$', $options);
  
    $correct = $helyiertek-1;
    $solution = $options[$helyiertek-1];
    $solution = str_ireplace('\\,','\\\\,',$solution);
  
    shuffleAssoc($options);

    $type = 'quiz';
  
     {
      $correct = $numb[$helyiertek-1];
      $solution = $options[$helyiertek-1];
      $solution = str_ireplace('\\,','\\\\,',$solution);
      $options = '';
      $type = 'int';
    }



  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Define real value
function binary_real_value($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }
  
  $szam = numGen($hossz,2);
  
  $szamjegyek = str_split($szam);
  $helyiertek = rand(round($hossz/2),$hossz);
  $szamjegy = $szamjegyek[$hossz-$helyiertek];
  
  $szam2 = '';
  foreach ($szamjegyek as $key => $value) {
    if ($key == $hossz-$helyiertek) {
      $value = '\textcolor{red}{'.$value.'}';
    }
    if ($hossz-$key == 4 || $hossz-$key == 8) {
      $szam2 = $szam2.'\,';
    }
    $szam2 = $szam2.$value;
  }
  
  if (rand(1,2) == 1) {
    $question = 'Mennyit ér a piros számjegy az alábbi számban?$$'.$szam2.'_2$$';
  } else {
    $question = 'Mi a piros számjegy valódi értéke az alábbi számban?$$'.$szam2.'_2$$';
  }
  
  $numb = array(0,1,2,4,8,16,32,64,128,256,512,1024);
  
  $options = array_slice($numb,0,$hossz+1);
  $options = preg_replace( '/^(\d)/', '\$\1', $options);
  $options = preg_replace( '/(\d)$/', '\1\$', $options);
  
  if ($szamjegy == 0) {
    $solution = '$0$';
    $correct = 0;
  } else {
    $solution = $options[$helyiertek];
    $correct = pow(2,$helyiertek-1);
  }
  
  shuffleAssoc($options);
  
  $type = 'quiz';

  if ($level > 2 || $solution == 0) {
    $options = '';
    $type = 'int';
  }

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Convert binary to decimal
function binary_convert_2to10($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }
  
  $szam = numGen($hossz,2);
  $numb = array(0,1,2,4,8,16,32,64,128,256,512,1024);
  $szamjegyek = str_split($szam);
  
  $correct = 0;
  
  foreach ($szamjegyek as $key => $value) {
    if ($value == 1) {
      $correct = $correct + pow(2,$hossz-$key-1);
    }
  }
  
  if (strlen($szam) > 8) {
    $szam = preg_replace( '/(\d{4})(\d{4})$/', '\\\\,\1\\\\,\2', $szam);
  }
  elseif (strlen($szam) > 4) {
    $szam = preg_replace( '/(\d{4})$/', '\\\\,\1', $szam);
  }
  
  $question = 'Írjuk fel tízes számrendszerben!$$'.$szam.'_2$$';
  $solution = '$'.$correct.'$';
  
  $options = '';
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }
  
  $szam = numGen($hossz,2);
  $numb = array(0,1,2,4,8,16,32,64,128,256,512,1024);
  $szamjegyek = str_split($szam);
  
  $correct = 0;
  
  foreach ($szamjegyek as $key => $value) {
    if ($value == 1) {
      $correct = $correct + pow(2,$hossz-$key-1);
    }
  }
  
  if (strlen($szam) > 8) {
    $szam = preg_replace( '/(\d{4})(\d{4})$/', '\\\\,\1\\\\,\2', $szam);
  }
  elseif (strlen($szam) > 4) {
    $szam = preg_replace( '/(\d{4})$/', '\\\\,\1', $szam);
  }
  
  $question = 'Írjuk fel tízes számrendszerben!$$'.$szam.'_2$$';
  $solution = '$'.$correct.'$';
  
  $options = '';
  $type = 'int';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Convert binary to decimal (with bulbs)
function binary_convert_2to10_bulb($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }

  $szam = numGen($hossz,2);
  $numb = array(0,1,2,4,8,16,32,64,128,256,512,1024);
  $szamjegyek = str_split($szam);
  
  $correct = 0;
  
  foreach ($szamjegyek as $key => $value) {
    if ($value == 1) {
      $correct = $correct + pow(2,$hossz-$key-1);
    }
  }

  $szelesseg = floor(100/11*strlen($szam));
  $question = 'A lámpák egy kettes számrendszerbeli számot jelölnek.
        A bekapcsolt lámpa $1$-et, a kikapcsolt $0$-t jelent.
        Írjuk fel a szám tízes számrendszerbeli alakját!
        <div class="text-center">
          <img class="img-question" width="'.$szelesseg.'%" src="'.RESOURCES_URL.'/binary_bulb/create_image.php?function=binary_bulb&path='.RESOURCES_URL.'&num='.$szam.'">
        </div>';
  $solution = '$'.$correct.'$';
  $options = '';
  $type = 'int';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Convert binary to decimal (with fingers)
function binary_convert_2to10_finger($level)
{
  if ($level <= 3) {
    $hossz = rand(2,5); 
  } elseif ($level <= 6) {
    $hossz = rand(4,7);
  } else {
    $hossz = rand(7,10);
  }
  
  $szam = numGen($hossz,2);
  $numb = array(0,1,2,4,8,16,32,64,128,256,512,1024);
  $szamjegyek = str_split($szam);
  
  $correct = 0;
  
  foreach ($szamjegyek as $key => $value) {
    if ($value == 1) {
      $correct = $correct + pow(2,$hossz-$key-1);
    }
  }
  
  if ($hossz > 5) {
    $width = 80;
  } else {
    $width = 40;
  }

  $question = 'A kezünkkel egy kettes számrendszerbeli számot mutatunk.
        Minden ujj egy helyiértéket jelöl, a helyiértékek jobbról balra növekednek.
        Írjuk fel a szám tízes számrendszerbeli alakját!
        <div class="text-center">
          <img class="img-question" width="'.$width.'%" src="'.RESOURCES_URL.'/binary_finger/create_image.php?function=binary_finger&path='.RESOURCES_URL.'/binary_finger/&num='.$szam.'">
        </div>';
  $solution = '$'.$correct.'$';
  
  $options = '';
  $type = 'int';

  return array(
    'question'  => $question,
    'options'   => $options,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'    => $type
  );
}

// Convert decimal to binary
function binary_convert_10to2($level)
{
  if ($level <= 3) {
    $hossz = rand(2,4); 
  } elseif ($level <= 6) {
    $hossz = rand(5,8);
  } else {
    $hossz = rand(9,11);
  }
  
  $correct = numGen($hossz,2);
  $numb = array(0,1,2,4,8,16,32,64,128,256,512,1024);
  $szamjegyek = str_split($correct);
  
  $szam = 0;
  
  foreach ($szamjegyek as $key => $value) {
    if ($value == 1) {
      $szam = $szam + pow(2,$hossz-$key-1);
    }
  }
  
  $question = 'A $0$ és $1$ számjegyek segítségével váltsuk át kettes számrendszerbe az alábbi számot!$$'.$szam.'_{10}$$';
  $solution = '$'.$correct.'$';

  $options = '';
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