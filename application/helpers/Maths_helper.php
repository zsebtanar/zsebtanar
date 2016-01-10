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
function numGen($len, $numSys) {
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

?>