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
  if ($level <= 3) {
    $no_of_people = 3;
  } elseif ($level <= 6) {
    $no_of_people = 4;
  } else {
    $no_of_people = 5;
  }

  $names_all = array('Aladár', 'Béla', 'Cili', 'Dénes', 'Endre');
  $wrong = array('ketten', 'hárman', 'négyen');

  $names = array_slice($names_all, 0, $no_of_people);

  $names_text1 = implode(', ', array_slice($names, 0, $no_of_people-1));
  $names_text2 = $names[count($names) - 1];

  $solution = RaceSolution($no_of_people);

  $question = 'Egy versenyről '.$names_text1.' és '.$names_text2.' így számolt be:';

  $statements = RaceQuestion($names, $solution);
  $question .= $statements;
  $question .= 'Tudjuk, hogy csak egyikük mondott igazat, '.$wrong[$no_of_people-3].' hamisat állítottak. Ki lett a győztes ezen a versenyen, ha tudjuk, hogy nem volt holtverseny?';

  $options = $names;
  $correct = $solution['winner']-1;
  $solution = $names[$correct];

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution,
    'options'   => $options
  );
}

/**
 * Generate solution for race exercise
 *
 * Number of people (n) is having a race. After the race n statements are given 
 * regarding the winner, among which 1 is true and the rest is false. Generate a
 * possible solution which is possible to solve.
 *
 * @param int $n Number of people.
 *
 * @return array $solution Solution for race (statements of participants).
 */
function RaceSolution($n)
{
  $people = range(1, $n);
  $options = array(TRUE, FALSE);

  $names_all = combos($people, $n); // List of people appearing in statements.
  $statements_all = combos($options, $n); // Whether people won or not.

  shuffle($names_all);
  shuffle($statements_all);

  foreach ($names_all as $names) {
    foreach ($statements_all as $statements) {

      if (RaceCheckStatements($names, $statements)) {

        $hasSolution = FALSE;
        foreach ($people as $true) { // Who was right?

          $winner = RaceCheckSolution($names, $statements, $true);
          if ($winner) {

            if ($hasSolution) { // multiple solutions!

              break;

            } else {

              $solution['names'] = $names; 
              $solution['statements'] = $statements; 
              $solution['true'] = $true;
              $solution['winner'] = $winner;

              $hasSolution = TRUE;


            }
          }
        }

        if ($hasSolution) {

          return $solution; 

        }
      }
    }
  }

  return NULL;
}

/**
 * Check statements for race exercise
 *
 * @param array $names      List of people appearing in statements.
 * @param array $statements Whether people won or not.
 *
 * @return bool $unique Whether statements are unique (has no duplication).
 */
function RaceCheckStatements($names, $statements)
{

  foreach ($names as $key => $value) {
    if (isset($target[$value][(int)$statements[$key]])) { // multiple statement!!!
      return FALSE;
    } else {
      $target[$value][(int)$statements[$key]] = 1;
    }
  }

  return TRUE;
}

/**
 * Check solution for race exercise
 *
 * @param array $names      List of people appearing in statements.
 * @param array $statements Whether people won or not.
 * @param int   $true       Index of true statement.
 *
 * @return bool $winner Index of winner (NULL if there is no unique solution)
 */
function RaceCheckSolution($names, $statements, $true)
{

  foreach ($statements as $key => $value) { // Invert wrong statements
    if ($key+1 != $true) {
      $statements[$key] = !$value;
    }
  }

  $winner = FALSE;
  $solution = array();

  foreach ($names as $key => $value) {
    if (!isset($solution[$value])) { // person not checked yet

      if ($statements[$key]) { // person won
        if ($winner) { // there is already a winner
          return FALSE;
        }
        $winner = $value;
      }
      $solution[$value] = $statements[$key];

    } else { // person already checked


      if ($solution[$value] != $statements[$key]) { // contradiction!
        return FALSE;
      }
    }
  }

  if (!$winner && count($solution) == (count($names)-1)) { // missing person is the winner
    $people = range(1, count($names));
    foreach (array_keys($solution) as $person) {
      unset($people[$person-1]);
    }
    if (count($people) == 1) {
      $winner = array_values($people)[0]; 
    } else {
      die('No winner found!!!');
    }
  }

  return $winner;
}

/**
 * Check solution for race exercise
 *
 * @param array $names    List of names
 * @param array $solution Solution
 *
 * @return string $statements Statements of people.
 */
function RaceQuestion($names, $solution)
{

  $statements = '';
  $statements .= '<table style="margin:15px">';

  for ($i=0; $i<count($names); $i++) {
    
    $statements .= '<tr><td><b>'.$names[$i].'</b>:&nbsp;&nbsp;</td>';
    $name = $names[$solution['names'][$i]-1];

    if ($i == $solution['names'][$i]-1) { // statement about self
      if ($solution['statements'][$i]) {
        $rand = rand(1, 3);
        if ($rand == 1) {
          $statements .= '<td>Én győztem.</td>';
        } elseif ($rand == 2) {
          $statements .= '<td>Én lettem a győztes.</td>';
        } elseif ($rand == 3) {
          $statements .= '<td>Én nyertem meg a versenyt.</td>';
        }
      } else {
        $rand = rand(1, 3);
        if ($rand == 1) {
          $statements .= '<td>Nem én győztem.</td>';
        } elseif ($rand == 2) {
          $statements .= '<td>Sajnos nem én lettem a győztes.</td>';
        } elseif ($rand == 3) {
          $statements .= '<td>Nem én nyertem meg a versenyt.</td>';
        }
      }
    } else {
      if ($solution['statements'][$i]) {
        $rand = rand(1, 3);
        if ($rand == 1) {
          $statements .= '<td>A győztes '.$name.' lett.</td>';
        } elseif ($rand == 2) {
          $statements .= '<td>A versenyt '.$name.' nyerte meg.</td>';
        } elseif ($rand == 3) {
          $statements .= '<td>'.$name.' lett az első.</td>';
        }
      } else {
        $rand = rand(1, 3);
        if ($rand == 1) {
          $statements .= '<td>Nem '.$name.' győzött.</td>';
        } elseif ($rand == 2) {
          $statements .= '<td>A nyertes nem '.$name.' lett.</td>';
        } elseif ($rand == 3) {
          $statements .= '<td>A versenyt nem '.$name.' nyerte meg.</td>';
        }
      }
    }
    $statements .= '</tr>';
  }

  $statements .= '</table>';

  return $statements;
}


?>