<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- NUMBER LINE

// Calculate stepsize of number line
function number_line_stepsize($level)
{
  if ($level <= 3) {
    $osztaskoz = pow(10,rand(1,4)-1);
    $kulonbseg = 1;
    $minpontok = 4;
    $kezdoertek = rand(0,2)*$osztaskoz;
  } elseif ($level <= 6) {
    $osztaskoz = 5*pow(10,rand(1,4)-1);
    $kulonbseg = rand(2,3);
    $minpontok = 7;
    $kezdoertek = rand(3,5)*$osztaskoz;
  } else {
    $osztaskoz = rand(2,9)*pow(10,rand(1,4)-1);
    $kulonbseg = rand(3,4);
    $minpontok = 9;
    $kezdoertek = rand(6,9)*$osztaskoz;
  }
  
  if (strlen($osztaskoz) == 4) {
    $pontok = rand($minpontok,9);
  } elseif (strlen($osztaskoz) == 3) {
    $pontok = rand($minpontok,11);
  } else {
    $pontok = rand($minpontok,15);
  }
  
  $poz1 = rand(1,$pontok-$kulonbseg);
  $poz2 = $poz1 + $kulonbseg;
  $poz3 = rand(1,$pontok);
  while ($poz3 == $poz1 || $poz3 == $poz2) {
    $poz3 = rand(1,$pontok);
  }

  $ertek1 = $kezdoertek + ($poz1-1)*$osztaskoz;
  $ertek2 = $kezdoertek + ($poz2-1)*$osztaskoz;
  $ertek3 = $kezdoertek + ($poz3-1)*$osztaskoz;
  
  $question = 'Mekkora az alábbi számegyenes osztásköze?
        <div class="text-center">
          <img class="img-question" width="100%" src="'.RESOURCES_URL.'/number_line/create_image.php?function=num_line_stepsize&poz1='.$poz1.'&ertek1='.$ertek1.'&poz2='.$poz2.'&ertek2='.$ertek2.'&pontok='.$pontok.'">
        </div>';
  
  $correct = $osztaskoz;
  $solution = '$'.$correct.'$';
  
  $options = '';
	$type = 'int';

	return array(
		'question' 	=> $question,
		'options' 	=> $options,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type' 		=> $type
	);
}

// Calculate position of number on number line
function number_line_position($level)
{
  if ($level <= 3) {
    $osztaskoz = pow(10,rand(1,4)-1);
    $kulonbseg = 1;
    $minpontok = 4;
  } elseif ($level <= 6) {
    $osztaskoz = 5*pow(10,rand(1,4)-1);
    $kulonbseg = rand(1,2);
    $minpontok = 7;
  } else {
    $osztaskoz = rand(2,9)*pow(10,rand(1,4)-1);
    $kulonbseg = rand(2,4);
    $minpontok = 9;
  }
  
  if (strlen($osztaskoz) == 4) {
    $pontok = rand($minpontok,9);
  } elseif (strlen($osztaskoz) == 3) {
    $pontok = rand($minpontok,11);
  } else {
    $pontok = rand($minpontok,15);
  }
  
  $poz1 = rand(1,$pontok-$kulonbseg);
  $poz2 = $poz1 + $kulonbseg;
  $poz3 = rand(1,$pontok);
  while ($poz3 == $poz1 || $poz3 == $poz2) {
    $poz3 = rand(1,$pontok);
  }
  
  $kezdoertek = rand(0,9)*$osztaskoz;
  $ertek1 = $kezdoertek + ($poz1-1)*$osztaskoz;
  $ertek2 = $kezdoertek + ($poz2-1)*$osztaskoz;
  $ertek3 = $kezdoertek + ($poz3-1)*$osztaskoz;
  
  $question = 'Melyik számot jelöli a kérdőjel?
        <div class="text-center">
          <img class="img-question" width="100%" src="'.RESOURCES_URL.'/number_line/create_image.php?function=num_line_position&poz1='.$poz1.'&ertek1='.$ertek1.'&poz2='.$poz2.'&ertek2='.$ertek2.'&poz3='.$poz3.'&pontok='.$pontok.'">
        </div>';
  
  $correct = $ertek3;
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

// Define operation shows on number line
function number_line_operation($level)
{
  if ($level <= 3) {
    $osztaskoz = pow(10,rand(1,4)-1);
    $pont12kulonbseg = 1;
    $minpontokszama = 4;
    $szinesiveke = 1;
    $muvelethossz = rand(2,2);
    $kezdoertek = rand(0,2)*$osztaskoz;
  } elseif ($level <= 6) {
    $osztaskoz = 5*pow(10,rand(1,4)-1);
    $pont12kulonbseg = rand(1,2);
    $minpontokszama = 7;
    $szinesiveke = 1;
    $muvelethossz = rand(3,4);
    $kezdoertek = rand(3,5)*$osztaskoz;
  } else {
    $osztaskoz = rand(2,9)*pow(10,rand(1,4)-1);
    $pont12kulonbseg = rand(2,4);
    $minpontokszama = 9;
    $szinesiveke = 0;
    $muvelethossz = rand(5,6);
    $kezdoertek = rand(6,9)*$osztaskoz;
  }
  
  if (strlen($osztaskoz) == 4) {
    $pontok = rand($minpontokszama,9);
  } elseif (strlen($osztaskoz) == 3) {
    $pontok = rand($minpontokszama,11);
  } else {
    $pontok = rand($minpontokszama,15);
  }
  
  $poz1 = rand(1,$pontok-$pont12kulonbseg);
  $poz2 = $poz1 + $pont12kulonbseg;
  
  $ertek1 = $kezdoertek + ($poz1-1)*$osztaskoz;
  $ertek2 = $kezdoertek + ($poz2-1)*$osztaskoz;
  
  $kevertpontok = range(1,$pontok,1);
  shuffle($kevertpontok);
  
  $muveletHosszu = '';
  $muveletRovid = '';
  for ($i=0; $i < $muvelethossz; $i++) { 
    if ($i == 0) {
      $szam = $kezdoertek + ($kevertpontok[$i]-1)*$osztaskoz;
      $muveletHosszu = $muveletHosszu.$szam;
      $muveletRovid = $muveletRovid.$kevertpontok[$i];
    } else {
      $muveletRovid = $muveletRovid.'_'.$kevertpontok[$i];
      $szam = abs($kevertpontok[$i]-$kevertpontok[$i-1])*$osztaskoz;
      if ($kevertpontok[$i] > $kevertpontok[$i-1]) {
        $muveletHosszu = $muveletHosszu.'+'.$szam;
      } else {
        $muveletHosszu = $muveletHosszu.'-'.$szam;
      }
    }
  }
  
  $question = 'Melyik műveletsort ábrázoltuk a számegyenesen?
        <div class="text-center">
          <img class="img-question" width="100%" src="'.RESOURCES_URL.'/number_line/create_image.php?function=num_line_operation&poz1='.$poz1.'&ertek1='.$ertek1.'&poz2='.$poz2.'&ertek2='.$ertek2.'&pontok='.$pontok.'&szinese='.$szinesiveke.'&muveletek='.$muveletRovid.'">
        </div>';
  
  $correct = $muveletHosszu;
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

// Define position of snail on number line
function number_line_snail($level)
{
  if ($level <= 3) {
    $unit = rand(1, 2);
    $no = rand(2, 3);
  } elseif ($level <= 6) {
    $unit = rand(2, 3);
    $no = rand(4, 6);
  } else {
    $unit = rand(3, 5);
    $no = rand(7, 10);
  }

  if ($no * $unit > 10) {
    $length = 'centiméter';
  } else {
    $length = 'deciméter';
  }

  $diff = ($no-1) * $unit;

  $question = 'Csiga Béla és Csiga Boglárka elhatározták, felmásznak két szomszédos, függőlegesen álló, egyenes nádszálra, hogy többet lássanak a világból. Egy idő múlva Boglárka rémülten észlelte, hogy Béla már sokkal magasabbra jutott. Béla a földtől számítva '.$no.'-'.Times($no).' akkora utat tett meg, mint ő, és így éppen '.$diff.' '.$length.'rel előzte meg őt. Hány '.$length.'re volt ekkor a földtől Béla?';

  $options = '';
  $correct = $no * $unit;
  $solution = '$'.$correct.'$ '.$length.'re';
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