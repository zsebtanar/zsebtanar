<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Add suffix 'times' to number (szor/szer/ször)
 *
 * @param int $num Number (< 10^600)
 *
 * @return string $suffix Suffix
 */
function Times($num)
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
function The($num)
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
function Dativ($num)
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
function By($num)
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
function With($num)
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
 * Add suffix 'to' to number (hoz/hez/höz)
 *
 * @param int $num Number (<10^6)
 *
 * @return string $suffix Suffix
 */
function To($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 4:
    case 7:
    case 9:
      return 'hez';
    case 2:
    case 5:
      return 'höz';
    case 3:
    case 6:
    case 8:
      return 'hoz';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'hez';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'hoz';
  }

  if ($abs == 0) {
    return 'hoz';
  } elseif (1000 <= $abs && $abs < 1000000) {
    return 'hez';
  } else {
    return 'hoz';
  }
}

/**
 * Add suffix 'in' to number (ban/ben)
 *
 * @param int $num Number (<10^6)
 *
 * @return string $suffix Suffix
 */
function In($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 2:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'ben';
    case 3:
    case 6:
    case 8:
      return 'ban';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'ben';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'ban';
  }

  if ($abs == 0) {
    return 'ban';
  } elseif (1000 <= $abs && $abs < 1000000) {
    return 'ben';
  } else {
    return 'ban';
  }
}

?>