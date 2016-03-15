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
 * Add modified suffix 'times' to number (szorosára/szeresére/szörösére)
 *
 * @param int $num Number (< 10^600)
 *
 * @return string $suffix Suffix
 */
function Times2($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 2:
    case 4:
    case 7:
    case 9:
      return 'szeresére';
    case 3:
    case 6:
    case 8:
      return 'szorosára';
    case 5:
      return 'szörösére';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'szeresére';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'szorosára';
  }

  if ($abs == 0) {
    return 'szorosára';
  } elseif (100 <= $abs && $abs < 1000) {
    return 'szorosára';
  } elseif (1000 <= $abs && $abs < 1000000) {
    return 'szeresére';
  } else {
    return 'szorosára';
  }
}

/**
 * Add modified suffix 'th' to number (od/ed/öd)
 *
 * @param int $num Number (< 10^600)
 *
 * @return string $suffix Suffix
 */
function Fraction($num)
{
  $abs = abs($num);

  switch ($abs % 10) {
    case 1:
    case 2:
    case 4:
    case 7:
    case 9:
      return 'ed';
    case 3:
    case 8:
      return 'ad';
    case 6:
      return 'od';
    case 5:
      return 'öd';
  }

  switch (($abs / 10) % 10) {
    case 1:
    case 4:
    case 5:
    case 7:
    case 9:
      return 'ed';
    case 2:
    case 3:
    case 6:
    case 8:
      return 'ad';
  }

  if ($abs == 0) {
    return 'ad';
  } elseif (100 <= $abs && $abs < 1000) {
    return 'ad';
  } elseif (1000 <= $abs && $abs < 1000000) {
    return 'ed';
  } else {
    return 'od';
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
    case 3:
      return 'mal';
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

/**
 * Write down number with letters
 *
 * @param int $num Number
 *
 * @return string $num_text Number with text
 */
function NumText($num) {
  
  $digits = str_split($num);
  $digits = array_reverse($digits);
  
  $num_group = array('','ezer','millió','milliárd');
  $nums1 = array('','egy','kettő','három','négy','öt','hat','hét','nyolc','kilenc');
  $nums1b = array('','','két','három','négy','öt','hat','hét','nyolc','kilenc');
  $nums2 = array('','tizen','huszon','harminc','negyven','ötven','hatvan','hetven','nyolcvan','kilencven');
  $nums2b = array('','tíz','húsz','harminc','negyven','ötven','hatvan','hetven','nyolcvan','kilencven');
  
  $place = 0;
  $num_text = '';
  foreach ($digits as $key => $value) {
    if ($key % 3 == 0) {
      if ($num > 2000 && $place > 0) {
        $num_text = $num_group[$place].'-'.$num_text;
      } else {
        $num_text = $num_group[$place].$num_text;
      }
      $num_text = $nums1[$value].$num_text;
      $place++;
    } elseif ($key % 3 == 1) {
      if ($digits[$key-1] == 0) {
        $num_text = $nums2b[$value].$num_text;
      } else {
        $num_text = $nums2[$value].$num_text;
      }
    } elseif ($key % 3 == 2) {
      $num_text = $nums1b[$value].'száz'.$num_text;
    }
  }
  
  $num_text = str_ireplace('egyezer','ezer', $num_text);
  $num_text = str_ireplace('kettőezer','kétezer', $num_text);
  $num_text = str_ireplace('kettőmillió','kétmillió', $num_text);
  $num_text = str_ireplace('kettőmilliárd','kétmilliárd', $num_text);

  return $num_text;
}

?>