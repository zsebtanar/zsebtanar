<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Random number generator
 *
 * Generates number of $len digits in $numSys numeral system (e.g. value is 10 for
 * decimal system).
 *
 * @param int $len    No. of digits.
 * @param int $numSys Numeral system.
 *
 * @return int $num Random number.
 */
function numGen($len, $numSys)
{
	if ($len > 1) {
		// first digit non-0
		$num = rand(1, $numSys-1);
	} else {
		$num = rand(0, $numSys-1);
	}
	for ($i=0; $i<$len-1; $i++) {

		$digit = rand(0, $numSys-1);

		// for small numbers, last two digit differs
		while ($len < 4 && $i == 0 && $digit == $num) {
			$digit = rand(0, $numSys-1);
		}

		$num .= $digit;
	}
	return $num;
}

/**
 * Modify same digits
 *
 * Modifies all digits in $num number that is equal to the one on $pos position.
 *
 * @param int $num Number.
 * @param int $pos Position.
 *
 * @return int $new New number.
 */
function modifySameDigits($num, $pos)
{
	$digits = str_split($num);
	$digit = $digits[$pos];
	foreach ($digits as $key => $value) {
		while ($value == $digit && $key != $pos) {
			if ($key == 0) {
				$value = rand(1, 9);   
			} else {
				$value = rand(0, 9);
			}
			$digits[$key] = $value;
		}
	}
	$new = implode("", $digits); 
	return $new;
}

/**
 * Associative array shuffle
 *
 * Shuffle for associative arrays, preserves key=>value pairs.
 * (Based on (Vladimir Kornea of typetango.com)'s function) 
 *
 * @param array &$array Array.
 *
 * @return NULL
 */
function shuffleAssoc(&$array)
{
  $keys = array_keys($array);
  shuffle($keys);
  foreach ($keys as $key) {
      $new[$key] = $array[$key];
  }
  $array = $new;
  return;
}

/**
 * Convert to Roman number
 *
 * @param int $num Number.
 *
 * @return string $rom Roman number.
 */
function convertRoman($num)
{
  $values = array(
      1000000 => "M",
       900000 => "CM",
       500000 => "D",
       400000 => "CD",
       100000 => "C",
        90000 => "XC",
        50000 => "L",
        40000 => "XL",
        10000 => "X",
         9000 => "IX",
         5000 => "V",
         4000 => "IV",
         1000 => "M",
          900 => "CM",
          500 => "D",
          400 => "CD",
          100 => "C",
           90 => "XC",
           50 => "L",
           40 => "XL",
           10 => "X",
            9 => "IX",
            5 => "V",
            4 => "IV",
            1 => "I",
    );

  $rom = "";
  while ($num > 0) {
      foreach ($values as $key => $value) {
          if ($num >= $key) {
              if ($key > 1000) {
                  $rom = $rom.'\overline{'.$value.'}';
              } else {
                  $rom = $rom.$value;
              }
              $num -= $key;
              break;
          }
      }
  }
  return $rom;
}

/**
 * Add suffix 'times' to number (szor/szer/ször)
 *
 * @param int $num Number (< 10^600)
 *
 * @return string $suffix Suffix
 */
function addSuffixTimes($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 2:
    case 4:
    case 7:
    case 9:
      return 'szer';
    case 3:
    case 6:
    case 8:
      return 'szor';
    case 5:
      return 'ször';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'szer';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'szor';
  }

  if ($abs == 0) {
    return 'szor';
  } elseif (100 <= $abs && $abs < 1000) {
    return 'szor';
  } elseif (1000 <= $abs && $abs < 1000000) {
    return 'szer';
  } else {
    return 'szor';
  }
}

/**
 * Add article to number
 *
 * @param int $num Number
 *
 * @return string $article Article
 */
function addArticle($num)
{
  if ($num <= 0) {
    return 'a';
  }

  $digits = str_split($num);
  $digit = $digits[0];
  $len = count($digits);

  if ($len % 3 == 1) {
    return (in_array($digit, ['1','5']) ? 'az' : 'a');
  } elseif ($digit == '5') {
      return 'az';
  } else {
    return 'a';
  }
}

/**
 * Add suffix dativus to number (at/et/öt/t)
 *
 * @param int $num Number (< 10^600)
 *
 * @return string $suffix Suffix
 */
function addSuffixDativ($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 4:
    case 7:
    case 9:
      return 'et';
    case 2:
      return 't';
    case 3:
    case 8:
      return 'at';
    case 5:
      return 'öt';
    case 6:
      return 'ot';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'et';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'at';
  }

  if ($abs == 0) {
    return 't';
  } elseif (100 <= $abs && $abs < 1000) {
    return 'at';
  } elseif (1000 <= $abs && $abs < 1000000) {
    return 'et';
  } else {
    return 't';
  }
}

/**
 * Add suffix 'by' to number (nál/nél)
 *
 * @param int $num Number (<10^600)
 *
 * @return string $suffix Suffix
 */
function addSuffixBy($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 2:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'nél';
    case 3:
    case 6:
    case 8:
      return 'nál';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'nél';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'nál';
  }

  if ($abs == 0) {
    return 'nál';
  }
  elseif (1000 <= $abs && $abs < 1000000) {
    return 'nél';
  }
  else {
    return 'nál';
  }
}

/**
 * Add suffix 'with' to number (val/vel)
 *
 * @param int $num Number (<10^6)
 *
 * @return string $suffix Suffix
 */
function addSuffixWith($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 4:
      return 'gyel';
    case 2:
      return 'vel';
    case 5:
    case 7:
      return 'tel';
    case 6:
      return 'tal';
    case 8:
      return 'cal';
    case 9:
      return 'cel';
  }

  switch (($abs / 10) % 10) {
    case 1:
      return 'zel';
    case 2:
      return 'szal';
    case 3:
      return 'cal';
    case 4:
    case 5:
    case 7:
    case 9:
      return 'nel';
    case 6:
    case 8:
      return 'nal';
  }

  if ($abs == 0) {
    return 'val';
  }
  elseif (1000 <= $abs && $abs < 1000000) {
    return 'rel';
  }
  else {
    return 'val';
  }
}


/**
 * Generate new random number based on given number
 *
 * @param int $num Number
 * @param int $len Length of number
 *
 * @return int $new New number
 */
function newNum($num,$len)
{
  if (rand(1,3) == 1) {
    $new = numGen(rand($len-1,$len+1),10);
  } else {
    if (rand(1,2) == 1) {
      $ujhossz = floor($len/2); 
    } else {
      $ujhossz = $len - 1;
    }
    
    $new = $num + numGen(rand(1,$ujhossz),10);
  }
  return $new;
}

/**
 * Check if number has digit
 *
 * @param array $digits Digits of number
 * @param int   $digit  Digit to check
 *
 * @return bool TRUE if digit occurs.
 */
function hasDigit($digits,$digit)
{
  $ugyanaze = 0;
  foreach ($digits as $value) {
    if ($value == $digit) {
      $ugyanaze = 1;
    }
  }
  if ($ugyanaze == 0) {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
 * Replace digit in number
 *
 * @param int $num   Number.
 * @param int $pos   Position of digit to replace.
 * @param int $digit New digit.
 *
 * @return int $num Modified number.
 */
function replaceDigit($num,$pos,$digit)
{
  $digits = str_split($num);
  $hossz = strlen($num);
  foreach ($digits as $key => $value) {
      if ($hossz-$pos == $key) {
          $digits[$key] = $digit;
      }
  }
  $num = implode("", $digits);
  if ($hossz > 4) {
      $num = preg_replace( '/(.)(.{9})$/', '\1\,\2', $num);
      $num = preg_replace( '/(.)(.{6})$/', '\1\,\2', $num);
      $num = preg_replace( '/(.)(.{3})$/', '\1\,\2', $num);
  }
  return $num;
}

?>