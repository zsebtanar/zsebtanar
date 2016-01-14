<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// --- NATURAL NUMBERS

// Define number value
function decimal_number_value($level)
{

	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place = rand(round($length/2),$length);
	$correct = $digits[$length-$place];

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
		"milliárdosok"
	);

	$num2 = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$article = (in_array($place, [1, 4]) ? 'az' : 'a');

	$question1 = 'Melyik számjegy áll '.$article.' '.$values[$place-1].' helyén az alábbi számban?$$'.$num2.'$$';
	$question2 = 'Mi '.$article.' '.$values[$place-1].' helyén álló szám alaki értéke?$$'.$num2.'$$';
	$rand = rand(1,2);
	$question = ($rand == 1 ? $question1 : $question2);

	$correct = $correct;
	$solution = '$'.$correct.'$';

	$explanation = decimal_explanation('number_value'.$rand, $num);
	if ($rand == 1) {
		$explanation[] = 'A feladatban '.$article.' <b>'.$values[$place-1].'</b> helyén álló számjegyre voltunk kíváncsiak,'
			.' ami '.$solution.'.';
	} else {
		$explanation[] = 'A feladatban '.$article.' <b>'.$values[$place-1].'</b> helyén álló számjegy alaki értéke volt a kérdés,'
			.' ami '.$solution.'.';
	}

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'explanation' => $explanation
	);
}

// Define place value I.
function decimal_place_value1($level)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place_value = rand(round($length/2),$length);
	$digit = $digits[$length-$place_value];

	$num = modifySameDigits($num,$length-$place_value);

	$num2 = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$question = 'Melyik helyen áll '.addArticle($digit).' $'.$digit.'$ az alábbi számban?$$'.$num2.'$$';
	$place_values = array(
		"egyesek",
		"tízesek",
		"százasok",
		"ezresek",
		"tízezresek",
		"százezresek",
		"milliósok",
		"tízmilliósok",
		"százmilliósok",
		"milliárdosok"
	);

	$options = array_slice($place_values,0,$length);
	$options = preg_replace( '/.$/', '$0 helyén.', $options);
	$options = preg_replace( '/^[^e]/', 'A $0', $options);
	$options = preg_replace( '/^e/', 'Az $0', $options);

	$correct = $place_value-1;
	$solution = $options[$place_value-1];
	$solution2 = preg_replace( '/^./', 'a', $solution);
	$solution2 = preg_replace( '/helyén.$/', '', $solution2);

	if ($level > 2) {
		shuffle($options);
		$correct = array_search($solution, $options);
	}

	$explanation = decimal_explanation('place_value1', $num);
	$explanation[] = 'A feladatban '.addArticle($digit).' $'.$digit.'$ helye volt a kérdés, ami <b>'.$solution2.'</b> helyén áll.';

	$type = 'quiz';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type,
		'explanation' => $explanation
	);
}

// Explanation for place/number/real value
function decimal_explanation($type, $num)
{
	$digits = str_split($num);
	$length = count($digits);
	$text = '';

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
		"milliárdosok"
	);

	for ($i=0; $i < $length; $i++) {

		$article = (in_array($i, [0,3]) ? 'Az' : 'A');
		$digit = $digits[$length-1-$i];
		$place_value = pow(10, $i);

		if ($type == 'place_value1' || $type == 'number_value1') {

			$text = $article.' <b>'.$values[$i].'</b> helyén '.addArticle($digit).' $'.$digit.'$ áll:';

		} elseif ($type == 'place_value2') {

			$text = $article.' <b>'.$values[$i].'</b> helyén '.addArticle($digit).' $'.$digit.'$ áll,'
				.' ennek a helyiértéke $'.strval($place_value).'$:';

		} elseif ($type == 'number_value2') {

			$text = $article.' <b>'.$values[$i].'</b> helyén álló számjegy alaki értéke $'.$digit.'$:';

		} elseif ($type == 'real_value1') {

			$text = $article.' <b>'.$values[$i].'</b> helyén '.addArticle($digit).' $'.$digit.'$ áll,'
				.' ennek a helyiértéke $'.strval($place_value).'$, ezért a valódi érték $'
				.strval($digit).'\cdot'.strval($place_value).'='.strval($place_value*$digit).'$ lesz:';

		} elseif ($type == 'real_value2') {

			$text = $article.' <b>'.$values[$i].'</b> helyén '.addArticle($digit).' $'.$digit.'$ áll,'
				.' ennek a helyiértéke $'.strval($place_value).'$, ezért az a számjegy $'
				.strval($digit).'\cdot'.strval($place_value).'='.strval($place_value*$digit).'$-'
				.addSuffixDativ($place_value*$digit).' ér:';

		}

		$text .= '$$'.markRed($num, $i).'$$';

		$explanation[] = $text;
	}

	return $explanation;
}

// Mark digit of number at specific place value
function markRed($num, $place)
{
	$digits = str_split($num);
	$length = count($digits);
	$num2 	= '';

	for ($i=0; $i < $length; $i++) {
		$digit = $digits[$length-1-$i];
		$num2 = ($i == $place ? '\textcolor{red}{'.$digit.'}' : $digit).$num2;
		if ($length > 4 && $i % 3 == 2) {
			$num2 = '\,'.$num2;
		}
	}

	return $num2;
}

// Define place value II.
function decimal_place_value2($level)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place_value = rand(round($length/2),$length);
	$digit = $digits[$length-$place_value];

	$num = modifySameDigits($num,$length-$place_value);

	$num = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$question = 'Mi '.addArticle($digit).' $'.$digit.'$ helyiértéke az alábbi számban?$$'.$num.'$$';

	$correct = pow(10, $place_value-1);
	$solution = '$'.$correct.'$';
	$solution = str_ireplace('\\,','\\\\,',$solution);

	$explanation = decimal_explanation('place_value2', $num);
	$explanation[] = 'A feladatban '.addArticle($digit).' $'.$digit.'$ helyiértéke volt a kérdés, aminek a helyiértéke '.$solution.'.';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'explanation' => $explanation 
	);
}

// Define real value
function decimal_real_value($level)
{
	if ($level == 1) {
		$length = 2; 
	} elseif ($level == 2) {
		$length = rand(3,4);
	} else {
		$length = rand(5,6);
	}

	$num = numGen($length,10);

	$digits = str_split($num);
	$place_value = rand(round($length/2),$length);
	$digit = $digits[$length-$place_value];

	$num = modifySameDigits($num,$length-$place_value);

	$article = (in_array($digit,array(5,1)) ? 'az' : 'a');

	$num2 = ($num > 9999 ? number_format($num,0,',','\,') : $num);

	$question1 = 'Mi '.$article.' $'.$digit.'$ valódi értéke az alábbi számban?$$'.$num2.'$$';
	$question2 = 'Mennyit ér '.$article.' $'.$digit.'$ az alábbi számban?$$'.$num2.'$$';
	$rand = rand(1,2);
	$question = ($rand == 1 ? $question1 : $question2);

	$correct = pow(10, $place_value-1)*$digit;
	$solution = '$'.$correct.'$';

	$explanation = decimal_explanation('real_value'.$rand, $num);

	if ($rand == 1) {
		$explanation[] = 'A feladatban '.$article.' $'.$digit.'$ valódi értéke volt a kérdés,'
			.' ami '.$solution.'.';
	} else {
		$explanation[] = 'A feladatban '.$article.' $'.$digit.'$ értéke volt a kérdés,'
			.' ami '.$solution.'.';
	}

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'explanation' => $explanation 
	);
}

// Count total (with letters)
function decimal_count_letters($level)
{

	if ($level == 1) {
		$limit_number = rand(1,2);
		$limit_place = 3;
		$limit_value = 3;
	} elseif ($level == 2) {
		$limit_number = rand(2,3);
		$limit_place = 4;
		$limit_value = 9;
	} else {
		$limit_number = rand(3,5);
		$limit_place = 5;
		$limit_value = 12;
	}

	$place_values = array(
		"egyes",
		"tízes",
		"százas",
		"ezres",
		"tízezres",
		"százezres",
		"milliós",
		"tízmilliós",
		"százmilliós"
	);

	$place_values = array_slice($place_values,0,$limit_place);

	$total = 0;
	$text = '';

	$db = 0;
	foreach ($place_values as $key => $place_value) {
		if ($db < $limit_number) {
			$value = rand(1,$limit_value);
			$total = $total + $value*pow(10,$key);
			$text = $text.' $'.$value.'$ '.$place_value;
		}
		$db++;
	}

	$text = preg_replace('/(\w)\s\$/', '\1, $', $text);
	$text = preg_replace('/,([^,]*)$/', ' és\1', $text);

	$options = '';
	$question = 'Mennyit ér '.$text.'?';
	$correct = $total;
	$solution = '$'.$total.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Count total (with numbers)
function decimal_count_numbers($level)
{
	$kitevok = array(0,1,2,3,4,5,6,7,8);

	if ($level == 1) {
		$maxdb = rand(1,2);
		$maxkitevo = 3;
		$maxertek = 3;
	} elseif ($level == 2) {
		$maxdb = rand(2,3);
		$maxkitevo = 4;
		$maxertek = 9;
	} elseif ($level == 3) {
		$maxdb = rand(3,4);
		$maxkitevo = 5;
		$maxertek = 12;
	}

	$kitevok = array_slice($kitevok,0,$maxkitevo);
	shuffleAssoc($kitevok);

	$osszeg = 0;
	$szoveg = '';

	$db = 0;
	foreach ($kitevok as $key => $value) {
		if ($db < $maxdb) {
			$alakiertek = rand(1,$maxertek);
			$helyiertek = pow(10,$kitevok[$key]);
			$osszeg = $osszeg + $alakiertek*$helyiertek;
			$szoveg = $szoveg.$alakiertek.'\cdot'.$helyiertek.'$$+$$';
		}
		$db++;
	}

	$szoveg = preg_replace('/000000000\$/', '\\,000\\,000\\,000$', $szoveg);
	$szoveg = preg_replace('/000000\$/', '\\,000\\,000$', $szoveg);
	$szoveg = preg_replace('/0000\$/', '0\\,000$', $szoveg);
	$szoveg = preg_replace('/^(.)/', '$\1', $szoveg);
	$szoveg = preg_replace('/\$\+\$\$$/', '', $szoveg);

	$options = '';
	$question = 'Mennyivel egyenlő '.$szoveg.'?';
	$correct = $osszeg;

	$osszeg = str_ireplace(',','\\\\,',number_format($osszeg));
	$solution = '$'.$osszeg.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Count total (with money)
function decimal_count_money($level)
{
  $penzek = array("tíz","húsz","ötven","száz","kétszáz","ötszáz","ezer","kétezer","ötezer","tízezer","húszezer");
  $ertekek = array(10,   20,    50,     100,   200,      500,     1000,  2000,     5000,    10000,    20000);
  $penzek = preg_replace('/^(.*)$/', '\1forintos', $penzek);
  
  if ($level == 1) {
    $maxfajta = rand(1,2);
    $maxhelyiertek = 4;
    $maxdb = array(3,2,1);
  } elseif ($level == 2) {
    $maxfajta = rand(2,3);
    $maxhelyiertek = 7;
    $maxdb = array(9,3,2);
  } elseif ($level == 3) {
    $maxfajta = rand(3,4);
    $maxhelyiertek = count($penzek);
    $maxdb = array(12,5,3);
  }
  
  $penzek = array_slice($penzek,0,$maxhelyiertek);
  shuffleAssoc($penzek);
  
  $osszeg = 0;
  $szoveg = '';
  
  $db = 0;
  foreach ($penzek as $key => $value) {
    if ($db < $maxfajta) {
      $alakiertek = rand(1,$maxdb[$key % 3]);   
      $osszeg = $osszeg + $alakiertek*$ertekek[$key];
      $szoveg = $szoveg.' $'.$alakiertek.'$ '.$value;
    }
    $db++;
  }
  
  $szoveg = preg_replace('/(\w)\s\$/', '\1, $', $szoveg);
  $szoveg = preg_replace('/,([^,]*)$/', ' és\1', $szoveg);
  
  $options = '';
  $question = 'Mennyit ér '.$szoveg.'?';
  $correct = $osszeg;
  $solution = '$'.$osszeg.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Count number of zeros in total
function decimal_count_zeros($level)
{
  $kitevok = array(0,1,2,3,4,5,6,7,8);
  
	if ($level == 1) {
		$maxdb = rand(1,2);
		$maxkitevo = 3;
		$maxertek = 3;
	} elseif ($level == 2) {
		$maxdb = rand(2,3);
		$maxkitevo = 4;
		$maxertek = 9;
	} elseif ($level == 3) {
		$maxdb = rand(3,4);
		$maxkitevo = 5;
		$maxertek = 12;
	}
  
  $kitevok = array_slice($kitevok,0,$maxkitevo);
  shuffleAssoc($kitevok);
  
  $osszeg = 0;
  $szoveg = '';
  
  $db = 0;
  foreach ($kitevok as $key => $value) {
    if ($db < $maxdb) {
      $alakiertek = rand(1,$maxertek);
      $helyiertek = pow(10,$kitevok[$key]);
      $osszeg = $osszeg + $alakiertek*$helyiertek;
      $szoveg = $szoveg.$alakiertek.'\cdot'.$helyiertek.'$$+$$';
    }
    $db++;
  }
  
  $nulla_db = 0;
  foreach(str_split($osszeg) as $value) {
    if ($value == 0) {
      $nulla_db++;
    }
  }

  $szoveg = preg_replace('/000000000\$/', '\\,000\\,000\\,000$', $szoveg);
  $szoveg = preg_replace('/000000\$/', '\\,000\\,000$', $szoveg);
  $szoveg = preg_replace('/0000\$/', '0\\,000$', $szoveg);
  $szoveg = preg_replace('/^(.)/', '$\1', $szoveg);
  $szoveg = preg_replace('/\$\+\$\$$/', '', $szoveg);
  
  $options = '';
  $question = 'Hány nulla szerepel az alábbi műveletsor eredményében: '.$szoveg.'?';
  $correct = $nulla_db;
  
  if ($osszeg > 9999) {
    $osszeg = str_ireplace(',','\\\\,',number_format($osszeg));
  }
  
  $solution = '$'.$nulla_db.'$ (az eredmény: $'.$osszeg.'$)';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Change number (with letters)
function decimal_change_letters($level)
{
  $mitvalt = array("százas","ezres","tízezres","százezres","milliós","tízmilliós","százmilliós");
  $mirevalt = array("tízest","százast","ezrest");
  $index = rand(0,2);
  $index = 2;
  $mirevalt = $mirevalt[$index];
  
  if ($level == 1) {
    $max_db = 1;
    $max_helyiertek = 1;
    $max_ertek = 3;
  } elseif ($level == 2) {
    $max_db = 2;
    $max_helyiertek = rand(2,3);
    $max_ertek = 9;
  } elseif ($level == 3) {
    $max_db = 3;
    $max_helyiertek = rand(3,6);
    $max_ertek = 12;
  }
  
  $mitvalt = array_slice($mitvalt,$index,$max_helyiertek,TRUE);
  shuffleAssoc($mitvalt);
  
  $osszeg = 0;
  $szoveg = '';
  
  $db = 0;
  foreach ($mitvalt as $key => $value) {
    if ($db < $max_db) {
      $k = rand(1,$max_ertek);
      $osszeg = $osszeg + $k*pow(10,$key+2);
      $szoveg = $szoveg.' $'.$k.'$ '.$value;
    }
    $db++;
  }
  
  $eredmeny = $osszeg/pow(10,$index+1);
  $correct = $eredmeny;
  if ($eredmeny > 9999) {
    $eredmeny = str_ireplace(',','\\\\,',number_format($eredmeny));
  }

  $szoveg = preg_replace('/(\w)\s\$/', '\1, $', $szoveg);
  $szoveg = preg_replace('/,([^,]*)$/', ' és\1', $szoveg);
  $options = '';
  $question = 'Hány darab  '.$mirevalt.' jelent '.$szoveg.'?';
  $solution = '$'.$eredmeny.'$ db '.$mirevalt.'.';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Change number (with money)
function decimal_change_money($level)
{
  $mirevalt = array("tízforintosra","százforintosra");
  $index = rand(0,1);
  $mirevalt = $mirevalt[$index];
  
  if ($level == 1) {
    $dbmax = 1;
    $mitvalt = array("százast","ezrest");
    $max = array(3,5);
  } elseif ($level == 2) {
    $dbmax = 2;
    $mitvalt = array("százast","ezrest","ötezrest");
    $max = array(9,6,1);
  } elseif ($level == 3) {
    $dbmax = 3;
    $mitvalt = array("százast","ezrest","ötezrest","tízezrest","húszezrest");
    $max = array(12,12,1,12,1);
  }
  
  $mitvalt = array_slice($mitvalt,$index);
  $max = array_slice($max,$index);
  shuffleAssoc($mitvalt);
  
  $osszeg = 0;
  $szoveg = '';
  
  $db = 0;
  foreach ($mitvalt as $key => $value) {
    if ($db < $dbmax) {
      $k = rand(1,$max[$key]);
      if ($value == "százast") {
        $osszeg = $osszeg + $k*100;
      } elseif ($value == "ezrest") {
        $osszeg = $osszeg + $k*1000;
      } elseif ($value == "ötezrest") {
        $osszeg = $osszeg + $k*5000;
      } elseif ($value == "tízezrest") {
        $osszeg = $osszeg + $k*10000;
      } elseif ($value == "húszezrest") {
        $osszeg = $osszeg + $k*20000;
      }
      $szoveg = $szoveg.' $'.$k.'$ '.$value;
    }
    $db++;
  }
  
  if ($mirevalt == "tízforintosra") {
    $eredmeny = $osszeg/10;
  } elseif ($mirevalt == "százforintosra") {
    $eredmeny = $osszeg/100;
  }
  
  $szoveg = preg_replace('/(\w)\s\$/', '\1, $', $szoveg);
  $szoveg = preg_replace('/,([^,]*)$/', ' és\1', $szoveg);
  
  $options = '';
  $question = 'Hány darab  '.$mirevalt.' lehet felváltani '.$szoveg.'?';
  $correct = $eredmeny;
  $solution = '$'.$eredmeny.'$ db '.$mirevalt.'.';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Write number (with numbers)
function decimal_write_numbers($level)
{
  if ($level == 1) {
    $hossz = rand(2,3); 
  } elseif ($level == 2) {
    $hossz = rand(3,4);
  } elseif ($level == 3) {
    $hossz = rand(4,5);
  }
  
  $szam = numGen($hossz,10);
  
  $szamjegyek = str_split($szam);
  $szamjegyek = array_reverse($szamjegyek);
  
  $szam_hatar = array('','ezer','millió','milliárd');
  $szamok1 = array('','egy','kettő','három','négy','öt','hat','hét','nyolc','kilenc');
  $szamok1b = array('','','két','három','négy','öt','hat','hét','nyolc','kilenc');
  $szamok2 = array('','tizen','huszon','harminc','negyven','ötven','hatvan','hetven','nyolcvan','kilencven');
  $szamok2b = array('','tíz','húsz','harminc','negyven','ötven','hatvan','hetven','nyolcvan','kilencven');
  
  $szakasz = 0;
  $szam_betu = '';
  foreach ($szamjegyek as $key => $value) {
    if ($key % 3 == 0) {
      if ($szam > 2000 && $szakasz > 0) {
        $szam_betu = $szam_hatar[$szakasz].'-'.$szam_betu;
      } else {
        $szam_betu = $szam_hatar[$szakasz].$szam_betu;
      }
      $szam_betu = $szamok1[$value].$szam_betu;
      $szakasz++;
    } elseif ($key % 3 == 1) {
      if ($szamjegyek[$key-1] == 0) {
        $szam_betu = $szamok2b[$value].$szam_betu;
      } else {
        $szam_betu = $szamok2[$value].$szam_betu;
      }
    } elseif ($key % 3 == 2) {
      $szam_betu = $szamok1b[$value].'száz'.$szam_betu;
    }
  }
  
  $options = '';
  $szam_betu = str_ireplace('egyezer','ezer', $szam_betu);
  $szam_betu = str_ireplace('kettőezer','kétezer', $szam_betu);
  $szam_betu = str_ireplace('kettőmillió','kétmillió', $szam_betu);
  $szam_betu = str_ireplace('kettőmilliárd','kétmilliárd', $szam_betu);
  
  $question = 'Írjuk le számjegyekkel az alábbi számot: <i>"'.$szam_betu.'"</i> !';
  $correct = $szam;
  
  if ($szam > 9999) {
    $szam = number_format($szam,0,',','\,');
  }
  
  $solution = '$'.$szam.'$';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Count hyphens in number
function decimal_write_hyphen($level)
{
  if ($level == 1) {
    $szam = rand(1,2500);
  } elseif ($level == 2) {
    $szam = numGen(rand(4,7),10);
  } elseif ($level == 3) {
    $szam = numGen(rand(7,10),10);
  }
  
  $szamjegyek = str_split($szam);
  $szamjegyek = array_reverse($szamjegyek);
  
  $szam_hatar = array('','ezer','millió','milliárd');
  $szamok1 = array('','egy','kettő','három','négy','öt','hat','hét','nyolc','kilenc');
  $szamok1b = array('','','két','három','négy','öt','hat','hét','nyolc','kilenc');
  $szamok2 = array('','tizen','huszon','harminc','negyven','ötven','hatvan','hetven','nyolcvan','kilencven');
  $szamok2b = array('','tíz','húsz','harminc','negyven','ötven','hatvan','hetven','nyolcvan','kilencven');
  
  $szakasz = 0;
  $szam_betu = '';
  foreach ($szamjegyek as $key => $value) {
    if ($key % 3 == 0) {
      if ($szam > 2000 && $szakasz > 0) {
        $szam_betu = $szam_hatar[$szakasz].'-'.$szam_betu;
      } else {
        $szam_betu = $szam_hatar[$szakasz].$szam_betu;
      }
      $szam_betu = $szamok1[$value].$szam_betu;
      $szakasz++;
    } elseif ($key % 3 == 1) {
      if ($szamjegyek[$key-1] == 0) {
        $szam_betu = $szamok2b[$value].$szam_betu;
      } else {
        $szam_betu = $szamok2[$value].$szam_betu;
      }
    } elseif ($key % 3 == 2 && $value > 0) {
      $szam_betu = $szamok1b[$value].'száz'.$szam_betu;
    }
  }
  
  $options = '';
  $szam_betu = str_ireplace('egyezer','ezer', $szam_betu);
  $szam_betu = str_ireplace('kettőezer','kétezer', $szam_betu);
  $szam_betu = str_ireplace('kettőmillió','kétmillió', $szam_betu);
  $szam_betu = str_ireplace('kettőmilliárd','kétmilliárd', $szam_betu);

  $szam_betu2 = preg_replace('/-/', '', $szam_betu);
  
  if ($szam > 9999) {
    $szam = number_format($szam,0,',','\,');
  }
  
  $question = 'Hány kötőjel hiányzik az alábbi kifejezésből?<br /><br /><div class="text-center"><i>"'.
  	$szam_betu2.'"</i></div>';
  
  $correct = count(preg_grep('/-/', str_split($szam_betu)));
  
  $solution = '<i>'.$szam_betu.'<\i>';
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