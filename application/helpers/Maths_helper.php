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

/**
 * Get greatest common divisor of two numbers
 *
 * source: http://networking.mydesigntool.com/viewtopic.php?tid=289&id=31
 *
 * @param int $x Number 1.
 * @param int $y Number 2.
 *
 * @return int $z Greatest common divisor
 */
function gcd($x, $y)
{
  $z = 1;
  $x = abs($x);
  $y = abs($y);

  if($x + $y == 0) {
    
    return "0";

  } else {

    while($x > 0) {
      $z = $x;
      $x = $y % $x;
      $y = $z;
    }

    return $z;
  }
}

/**
 * Combinations with repetitions
 *
 * @param array $array Array of elements.
 * @param int   $k     Size of selection.
 *
 * @return array $combos Combinations.
 */
function combos($arr, $k) {
  if ($k == 0) {
    return array(array());
  }

  if (count($arr) == 0) {
    return array();
  }

  $head = $arr[0];

  $combos = array();
  $subcombos = combos($arr, $k-1);
  foreach ($subcombos as $subcombo) {
    array_unshift($subcombo, $head);
    $combos[] = $subcombo;
  }
  array_shift($arr);
  $combos = array_merge($combos, combos($arr, $k));
  return $combos;
}

?>