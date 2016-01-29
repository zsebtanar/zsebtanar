<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// --- FRACTIONS


// Compare fraction with 1
function fraction_compare_1($level)
{
  if ($level <= 3) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level <= 6) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } else {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $rand = rand(1,3);

  if ($rand == 1) {
    list($num, $denom) = array($denom, $num);
  } elseif ($rand == 2) {
    $num = $denom;
  }

  $frac = $num/$denom;

  $question = 'Melyik relációs jel kerül a kérdőjel helyére?$$\frac{'.$num.'}{'.$denom.'}\qquad?\qquad1$$';
  $options = array(0 => '>', 1 => '<', 2 => '=');

  if ($frac > 1) {
    $correct = 0;
  } elseif ($frac < 1) {
    $correct = 1;
  } else {
    $correct = 2;
  }

  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'options'   => $options,
  );
}

// Compare fractions
function fraction_compare($level)
{
  if ($level <= 3) {
    $num1 = rand(1,2);
    $num2 = rand(1,2);
    $denom1 = rand(1,3);
    $denom2 = rand(1,3);
  } elseif ($level <= 6) {
    $num1 = rand(3,5);
    $num2 = rand(3,5);
    $denom1 = rand(5,10);
    $denom2 = rand(5,10);
  } else {
    $num1 = rand(5,10);
    $num2 = rand(5,10);
    $denom1 = rand(10,20);
    $denom2 = rand(10,20);
  }

  $frac1 = $num1/$denom1;
  $frac2 = $num2/$denom2;

  $question = 'Melyik relációs jel kerül a kérdőjel helyére? $$\frac{'.$num1.'}{'.$denom1.'}\qquad?\qquad\frac{'.$num2.'}{'.$denom2.'}$$';
  $options = array(0 => '>', 1 => '<', 2 => '=');
  if ($frac1 > $frac2) {
    $correct = 0;
  } elseif ($frac1 < $frac2) {
    $correct = 1;
  } else {
    $correct = 2;
  }
  $solution = '$'.$options[$correct].'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
    'options'   => $options
	);
}

// Add fractions
function fraction_add($level)
{
  if ($level <= 3) {
    $num1 = rand(1,2);
    $num2 = rand(1,2);
    $denom1 = rand(1,3);
    $denom2 = rand(1,3);
  } elseif ($level <= 6) {
    $num1 = rand(3,5);
    $num2 = rand(3,5);
    $denom1 = rand(5,10);
    $denom2 = rand(5,10);
  } else {
    $num1 = rand(5,10);
    $num2 = rand(5,10);
    $denom1 = rand(10,20);
    $denom2 = rand(10,20);
  }

  $frac1 = $num1/$denom1;
  $frac2 = $num2/$denom2;

  $num = $num1*$denom2 + $num2*$denom1;
  $denom = $denom1*$denom2;
  $gcd = gcd($num, $denom);
  
  if ($gcd) {
    $num /= $gcd;
    $denom /= $gcd;    
  }

  $question = 'Mennyi lesz az alábbi művelet eredménye? $$\frac{'.$num1.'}{'.$denom1.'}+\frac{'.$num2.'}{'.$denom2.'}$$';
  $correct = array($num, $denom);
  $solution = '$\\frac{'.$num.'}{'.$denom.'}$';
  $type = 'fraction';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}

// Mixed fractions
function fraction_mixed($level)
{
  if ($level <= 3) {
    $int = rand(1,3);
    $num1 = rand(1,2);
    $denom1 = rand(1,3);
  } elseif ($level <= 6) {
    $int = rand(5,10);
    $num1 = rand(3,5);
    $denom1 = rand(5,10);
  } else {
    $int = rand(10,20);
    $num1 = rand(5,10);
    $denom1 = rand(10,20);
  }

  $num2 = $int*$denom1 + $num1;
  $denom2 = $denom1;
  $gcd = gcd($num2, $denom2);
  
  if ($gcd) {
    $num2 /= $gcd;
    $denom2 /= $gcd;    
  }

  $question = 'Alakítsd át közönséges törtté!$$'.$int.'\frac{'.$num1.'}{'.$denom1.'}$$';
  $correct = array($num, $denom);
  $solution = '$\\frac{'.$num2.'}{'.$denom2.'}$';
  $type = 'fraction';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}

// Subtract fractions
function fraction_subtract($level)
{
  if ($level <= 3) {
    $num1 = rand(1,2);
    $num2 = rand(1,2);
    $denom1 = rand(1,3);
    $denom2 = rand(1,3);
  } elseif ($level <= 6) {
    $num1 = rand(3,5);
    $num2 = rand(3,5);
    $denom1 = rand(5,10);
    $denom2 = rand(5,10);
  } else {
    $num1 = rand(5,10);
    $num2 = rand(5,10);
    $denom1 = rand(10,20);
    $denom2 = rand(10,20);
  }

  $frac1 = $num1/$denom1;
  $frac2 = $num2/$denom2;

  $num = $num1*$denom2 - $num2*$denom1;
  $denom = $denom1*$denom2;
  $gcd = gcd($num, $denom);
  
  if ($gcd) {
    $num /= $gcd;
    $denom /= $gcd;    
  }

  $question = 'Mennyi lesz az alábbi művelet eredménye? $$\frac{'.$num1.'}{'.$denom1.'}-\frac{'.$num2.'}{'.$denom2.'}$$';
  $solution = '$\\frac{'.$num.'}{'.$denom.'}$';
  $correct = array($num, $denom);
  $type = 'fraction';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}

// Multiply fractions
function fraction_multiply($level)
{
  if ($level <= 3) {
    $num1 = rand(1,2);
    $num2 = rand(1,2);
    $denom1 = rand(1,3);
    $denom2 = rand(1,3);
  } elseif ($level <= 6) {
    $num1 = rand(3,5);
    $num2 = rand(3,5);
    $denom1 = rand(5,10);
    $denom2 = rand(5,10);
  } else {
    $num1 = rand(5,10);
    $num2 = rand(5,10);
    $denom1 = rand(10,20);
    $denom2 = rand(10,20);
  }

  $frac1 = $num1/$denom1;
  $frac2 = $num2/$denom2;

  $num = $num1*$num2;
  $denom = $denom1*$denom2;
  $gcd = gcd($num, $denom);
  
  if ($gcd) {
    $num /= $gcd;
    $denom /= $gcd;    
  }

  $question = 'Mennyi lesz az alábbi művelet eredménye? $$\frac{'.$num1.'}{'.$denom1.'}\cdot\frac{'.$num2.'}{'.$denom2.'}$$';
  $solution = '$\\frac{'.$num.'}{'.$denom.'}$';
  $correct = array($num, $denom);
  $type = 'fraction';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}

// Divide fractions
function fraction_divide($level)
{
  if ($level <= 3) {
    $num1 = rand(1,2);
    $num2 = rand(1,2);
    $denom1 = rand(1,3);
    $denom2 = rand(1,3);
  } elseif ($level <= 6) {
    $num1 = rand(3,5);
    $num2 = rand(3,5);
    $denom1 = rand(5,10);
    $denom2 = rand(5,10);
  } else {
    $num1 = rand(5,10);
    $num2 = rand(5,10);
    $denom1 = rand(10,20);
    $denom2 = rand(10,20);
  }

  $frac1 = $num1/$denom1;
  $frac2 = $num2/$denom2;

  $num = $num1*$denom2;
  $denom = $denom1*$num2;
  $gcd = gcd($num, $denom);
  
  if ($gcd) {
    $num /= $gcd;
    $denom /= $gcd;    
  }

  $question = 'Mennyi lesz az alábbi művelet eredménye? $$\frac{'.$num1.'}{'.$denom1.'}:\frac{'.$num2.'}{'.$denom2.'}$$';
  $solution = '$\\frac{'.$num.'}{'.$denom.'}$';
  $correct = array($num, $denom);
  $type = 'fraction';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'type'      => $type
  );
}

// Complex fraction exercise (iceberg)
function fraction_iceberg($level)
{
  $part = rand(5,15); // weight of 1/9 iceberg;
  $over = $part;
  $under = 8*$part;
  $difference = $under - $over;
  $whole = 9*$part;

  $question = 'A jéghegyeknek csak $1/9$ része van a vízfelszín felett. ';
  if ($level <= 3) {
    $question .= 'Hány tonnás az a jéghegy, amelynek víz feletti része $'.$over.'$ tonna tömegű? ';
  } elseif ($level <= 6) {
    $question .= 'Hány tonnás az a jéghegy, amelynek víz alatti része $'.$under.'$ tonna tömegű? ';
  } else {
    $question .= 'Hány tonnás az a jéghegy, amelynek víz alatti része $'.$difference.'$ tonnával nehezebb, mint a víz feletti része? ';
  }

  $question .= '<i>(</i><a href="#" data-toggle="popover" data-trigger="focus" data-content="6 osztályos gimnáziumi felvételi feladatsor (2001. október 8.) 1. feladat"><i>Forrás</i></a><i>)</i>';

  $correct = $whole;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}
?>