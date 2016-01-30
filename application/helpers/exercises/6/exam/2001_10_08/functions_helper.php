<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// EXAM


// Calculate fraction of iceberg
function exam_iceberg($level)
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

// Find winner of race
function exam_race($level)
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