<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// SERIES


// Define difference of number sequence
function series_numerical_difference($level)
{
  if ($level <= 3) {
    $d = rand(-3,3);
    $ind1 = 1;
    $ind2 = 2;
    $a1 = rand(1,5);
  } elseif ($level <= 6) {
    $d = rand(-10, 10);
    $ind1 = rand(1,5);
    $ind2 = $ind1 + rand(1,5);
    $a1 = rand(-10, 10);
  } else {
    $d = rand(-20, 20);
    $ind1 = rand(1,10);
    $ind2 = $ind1 + rand(5,20);
    $a1 = rand(-20, 20);
  }

  $a2 = $a1 + ($ind2 - $ind1) * $d;

  $question = 'Egy sorozatban $a_{'.$ind1.'}='.$a1.'$, és $a_{'.$ind2.'}='.$a2.'$. Mekkora a $d$ értéke?';
  $correct = $d;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define member of number sequence by member and difference
function series_numerical_member_1($level)
{
  if ($level <= 3) {
    $d = rand(-3,3);
    $ind1 = 1;
    $ind2 = 2;
    $a1 = rand(1,5);
  } elseif ($level <= 6) {
    $d = rand(-10, 10);
    $ind1 = rand(1,5);
    $ind2 = $ind1 + rand(2,5);
    $a1 = rand(-10, 10);
  } else {
    $d = rand(-20, 20);
    $ind1 = rand(1,10);
    $ind2 = $ind1 + rand(5,20);
    $a1 = rand(-20, 20);
  }

  $a2 = $a1 + ($ind2 - $ind1) * $d;

  $question = 'Egy sorozatban $a_{'.$ind1.'}='.$a1.'$, és $d='.$d.'$. Mekkora az $a_{'.$ind2.'}$ értéke?';
  $correct = $a2;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define member of number sequence by two members
function series_numerical_member_2($level)
{
  if ($level <= 3) {
    $d = rand(-3, 3);
    $a1 = rand(1, 5);
    $ind1 = 1;
    $ind2 = 2;
    $ind3 = 3;
  } elseif ($level <= 6) {
    $d = rand(-10, 10);
    $a1 = rand(-10, 10);
    $indexes = range(1, 10);
  } else {
    $d = rand(-20, 20);
    $a1 = rand(-20, 20);
    $indexes = range(1, 20);
  }

  if ($level > 1) {
    shuffle($indexes);

    $ind1 = $indexes[0];
    $ind2 = $indexes[1];
    $ind3 = $indexes[2];
  }

  $a2 = $a1 + ($ind2 - $ind1) * $d;
  $a3 = $a1 + ($ind3 - $ind1) * $d;

  $question = 'Egy sorozatban $a_{'.$ind1.'}='.$a1.'$, és $a_{'.$ind2.'}='.$a2.'$. Mekkora az $a_{'.$ind3.'}$ értéke?';
  $correct = $a3;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define sum of number sequence by member and difference
function series_numerical_sum_1($level)
{
  if ($level <= 3) {
    $d = rand(-3, 3);
    $a_i = rand(1, 5);
    $i = 1;
    $j = rand(3,5);
  } elseif ($level <= 6) {
    $d = rand(-10, 10);
    $a_i = rand(-10, 10);
    $indexes = range(1, 10);
  } else {
    $d = rand(-20, 20);
    $a_i = rand(-20, 20);
    $indexes = range(1, 10);
  }

  if ($level > 1) {
    shuffle($indexes);

    $i = $indexes[0];
    $j = $indexes[1];
  }

  $a1 = $a_i - ($i-1) * $d;
  $S = (( 2*$a1 + ($j-1) * $d ) * $j) / 2;

  $question = 'Egy sorozatban $a_{'.$i.'}='.$a_i.'$, és $d='.$d.'$. Mekkora az $S_{'.$j.'}$ értéke?';
  $correct = $S;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define sum of number sequence by 2 members
function series_numerical_sum_2($level)
{
  if ($level <= 3) {
    $d = rand(-3, 3);
    $a_i = rand(1, 5);
    $i = 1;
    $j = rand(3,5);
    $k = $j;
  } elseif ($level <= 6) {
    $d = rand(-10, 10);
    $a_i = rand(-10, 10);
    $indexes = range(1, 10);
  } else {
    $d = rand(-20, 20);
    $a_i = rand(-20, 20);
    $indexes = range(1, 20);
  }

  if ($level > 1) {
    shuffle($indexes);

    $i = $indexes[0];
    $j = $indexes[1];
    $k = $indexes[2];
  }

  $a_j = $a_i + ($j-$i) * $d;
  $a_1 = $a_i - ($i-1) * $d;
  $S_k = (( 2*$a_1 + ($k-1) * $d ) * $k) / 2;

  $question = 'Egy sorozatban $a_{'.$i.'}='.$a_i.'$, és $a_{'.$j.'}='.$a_j.'$. Mekkora az $S_{'.$k.'}$ értéke?';
  $correct = $S_k;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define order of member of number sequence by some members
function series_numerical_order($level)
{
  $sgn = pow(-1, rand(1, 2));

  if ($level <= 3) {
    $d = $sgn * rand(1, 3);
    $a1 = rand(1, 5);
    $i = rand(5,7);
  } elseif ($level <= 6) {
    $d = $sgn * rand(1, 10);
    $a1 = rand(-10, 10);
    $i = rand(7, 10);
  } else {
    $d = $sgn * rand(1, 20);
    $a1 = rand(-20, 20);
    $i = rand(10, 20);
  }

  $a2 = $a1 + $d;
  $a3 = $a2 + $d;
  $ai = $a1 + ($i-1) * $d;

  $article = addArticle($ai);
  $question = 'Tekintsük az alábbi számtani sorozatot:$$'.$a1.','.$a2.','.$a3.',\ldots$$Hányadik tagja ennek a sorozatnak '.$article.' $'.$ai.'$?';
  $correct = $i;
  $solution = '$'.$correct.'.$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define sum by member, length and difference
function series_numerical_carrot($level)
{

  if ($level <= 3) {
    $d = rand(1, 3);
    $a1 = rand(1, 5);
    $n = rand(3,6);
    $i = 1;
  } elseif ($level <= 6) {
    $d = rand(1, 10);
    $a1 = rand(1, 10);
    $n = rand(7, 10);
    $i = rand(1,$n);
  } else {
    $d = rand(1, 20);
    $a1 = rand(1, 20);
    $n = rand(10, 20);
    $i = rand(1,$n);
  }

  $ai = $a1 + ($i-1) * $d;
  $Sn = (( 2*$a1 + ($n-1) * $d ) * $n) / 2;

  $suffix = addSuffixWith($d);
  $article = addArticle($ai);

  $question = 'Nagymama kertjében $'.$n.'$ sor répa van. Minden sorban $'.$d.'$-'.$suffix.' több répa van, mint az előzőben. Hány répa van összesen nagymama kertjében, ha tudjuk, hogy '.$article.' $'.$i.'$. sorban $'.$ai.'$ darab répa van?';
  $correct = $Sn;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define number of members by sum, member and difference
function series_numerical_members($level)
{
  if ($level <= 3) {
    $d = rand(1, 3);
    $a1 = rand(1, 5);
    $n = rand(3,6);
    $i = 1;
  } elseif ($level <= 6) {
    $d = rand(1, 10);
    $a1 = rand(1, 10);
    $n = rand(7, 10);
    $i = rand(1,$n);
  } else {
    $d = rand(1, 20);
    $a1 = rand(1, 20);
    $n = rand(10, 20);
    $i = rand(1,$n);
  }

  $ai = $a1 + ($i-1) * $d;
  $Sn = (( 2*$a1 + ($n-1) * $d ) * $n) / 2;

  $question = 'Egy számtani sorozatban $a_{'.$i.'}='.$ai.'$, és $d='.$d.'$. Az első néhány tagot összeadtuk, az eredmény $'.$Sn.'$ lett. Hány tagot adtunk össze?';
  $correct = $n;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Define last member of sum by sum, member and difference
function series_numerical_candy($level)
{
  if ($level <= 3) {
    $d = rand(1, 3);
    $a1 = rand(1, 5);
    $n = rand(3,6);
    $i = 1;
  } elseif ($level <= 6) {
    $d = rand(1, 10);
    $a1 = rand(1, 10);
    $n = rand(7, 10);
    $i = rand(1,$n);
  } else {
    $d = rand(1, 20);
    $a1 = rand(1, 20);
    $n = rand(10, 20);
    $i = rand(1,$n);
  }

  $ai = $a1 + ($i-1) * $d;
  $an = $a1 + ($n-1) * $d;
  $Sn = (( 2*$a1 + ($n-1) * $d ) * $n) / 2;

  $question = 'Harry Potter egy nap észrevette, hogy $'.$Sn.'$ db Bogoly Berti-féle mindenízű drazséjának nyoma veszett. Némi nyomozás után rájött, hogy legjobb barátja, Ron Weasley volt a tettes, aki végül beismerte, hogy egy ideje minden nap elcsent néhány szem drazsét. Arra nem emlékezett pontosan, hogy mikor kezdte, de azt tudta, hogy a kezdéstől számított $'.$i.'$. napon $'.$ai.'$ db-ot lopott, és minden nap $'.$d.'$ darabbal többet lopott, mint az előző nap. Hány drazsét lopott Ron Weasley az utolsó napon?';
  $correct = $an;
  $solution = '$'.$correct.'$';


  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

?>