<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TV_jatek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		if ($level <= 3) {
			$questions = rand(2,3);
		} elseif ($level <= 6) {
			$questions = rand(3,4);
		} else {
			$questions = rand(4,5);
		}

		$prize 			= rand($level, 2*$level) * pow(10, $questions);

		$risk_factors 	= [10, 25, 50, 75, 100];
		$factor_no		= rand(2, count($risk_factors));
		shuffle($risk_factors);
		$risk_factors 	= array_splice($risk_factors, 0, $factor_no);
		sort($risk_factors);

		// $questions 		= 5;
		// $prize 			= 40000;
		// $risk_factors	= [50, 75, 100];

		$question = 'Egy televíziós játékban $'.$questions.'$ kérdést tehet fel a játékvezető. A játék során a versenyző, ha az első kérdésre jól válaszol, $'.BigNum($prize).'$ forintot nyer. Minden további kérdés esetén döntenie kell, hogy a játékban addig megszerzett pénzének '.NumArray($risk_factors).' százalékát teszi-e fel. Ha jól válaszol, feltett pénzének kétszeresét kapja vissza, ha hibázik, abba kell hagynia a játékot, és a fel nem tett pénzét viheti haza. ';

		$option = rand(1,4);
		if ($option == 1) {

			$question .= 'Mennyi pénzt visz haza az a játékos, aki mind az '.NumText($questions).' feltett kérdésre jól válaszol, s bátran kockáztatva mindig a legnagyobb tétet teszi meg?';
			list($hints, $correct) = $this->Hints($questions, $risk_factors, $prize, $option);
			

		} elseif ($option == 2) {

			$question .= 'Az a játékos, aki mindig helyesen válaszol, de óvatos, és a '.NumText($questions-1).' utolsó fordulóban pénzének csak $'.min($risk_factors).'\%$-át teszi fel, hány forintot visz haza?';
			list($hints, $correct) = $this->Hints($questions, $risk_factors, $prize, $option);

		} elseif ($option == 3) {

			shuffle($risk_factors);
			$risk1 = $risk_factors[0];
			$risk2 = $risk_factors[1];
			if ($risk2 == 100) {
				list($risk1, $risk2) = [$risk2, $risk1];
			}

			$question .= 'A vetélkedő során az egyik versenyző az első '.NumText($questions-1).' kérdésre jól válaszolt. A második kérdésnél a pénzének $'.$risk1.'\%$-át, a '.NumArray(range(3,$questions),'és','.').' kérdés esetén pénzének $'.$risk2.'\%$-át tette fel. Az $'.$questions.'.$ kérdésre sajnos rosszul válaszolt. Hány forintot vihetett haza ez a játékos?';
			list($hints, $correct) = $this->Hints($questions, $risk_factors, $prize, $option, $risk1, $risk2);

		} else {

			$question .= 'Egy versenyző mind '.The($questions).' $'.$questions.'$ fordulóban jól válaszol, és közben minden fordulóban azonos eséllyel teszi meg a játékban megengedett lehetőségek valamelyikét. Mennyi annak a valószínűsége, hogy az elnyerhető maximális pénzt viheti haza?';

			$page[] = 'A játékos az első válasz után összesen $'.count($risk_factors).'$-féle opció közül választhat.';
			$page[] = 'Akkor fogja hazavinni a legtöbb pénzt, ha minden kérdés után a pénze lehető legnagyobb részét, azaz $'.max($risk_factors).'\%$-át teszi fel.';
			for ($i=1; $i <= $questions-1; $i++) { 
				$page[] = '<ul><li>Annak a valószínűsége, hogy '.The($i).' '.OrderText($i).' kérdés után ezt az opciót választja, $\frac{1}{'.count($risk_factors).'}$.</li></ul>';
			}
			$page[] = '('.strtoupper(The($questions)).' '.OrderText($questions).' kérdés után már nem kell tétet tenni, mert vége van a játéknak.)';
			$page[] = 'Ezért annak a valószínűsége, hogy a játékos az elnyerhető maximális pénzt viheti haza, $\left(\frac{1}{'.count($risk_factors).'}\right)^'.strval($questions-1).'=$<span class="label label-success">$\frac{1}{'.pow(count($risk_factors), $questions-1).'}$</span>.';
			$hints[] = $page;
			$correct = [1, pow(count($risk_factors), $questions-1)];

		}

		if ($option < 4) {

			$solution = '$'.$correct.'$ Ft';

			return array(
				'question'  => $question,
				'correct'   => $correct,
				'solution'  => $solution,
				'labels'	=> ['right' => 'Ft'],
				'hints'		=> $hints
			);

		} else {

			$solution = '$\frac{1}{'.$correct[1].'$';

			return array(
				'question'  => $question,
				'correct'   => $correct,
				'solution'  => $solution,
				'type'		=> 'fraction',
				'hints'		=> $hints
			);

		}
	}

	function Hints($questions, $risk_factors, $prize, $option, $risk1=NULL, $risk2=NULL) {

		$money = 0;
		$remain = 0;

		$page[] = 'A játékosnak kezdetben $0$ Ft-ja van. Nézzük meg lépésenként, hogy mennyi pénze lesz az egyes kérdések után!';

		for ($i=0; $i < $questions; $i++) { 

			// Did player win round?
			if ($option == 1 || $option == 2) {
				$correct = TRUE;
			} elseif ($option == 3) {
				if ($i < $questions-1) {
					$correct = TRUE;
				} else {
					$correct = FALSE;
				}
			}
			
			if ($correct) {

				$total = $money + ($i == 0 ? $prize : 2*$prize);

				$page[] = '<b>'.strval($i+1).'. kérdés</b><br />A játékos az '.OrderText($i+1).' kérdésre jól válaszolt, ezért '
					.($i==0 ? '$'.BigNum($prize).'$ Ft-ot kap.' : 'az előzőleg feltett pénze kétszeresét, azaz $2\cdot'.BigNum($prize).'='.BigNum(2*$prize).'$ Ft-ot nyer vissza.');

				if ($i == $questions-1) {

					$hints[] = $page;
					$page = [];
					$page[] = 'Tehát a játékos nyereménye összesen $'.BigNum($money).'+'.BigNum($i==0 ? $prize : 2*$prize).'=$<span class="label label-success">$'.BigNum($total).'$</span> Ft.';

				} else{

					if ($option == 1) {
						$risk = max($risk_factors);
					} elseif ($option == 2) {
						$risk = min($risk_factors);
					} elseif ($option == 3) {
						if ($i == 0) {
							$risk = $risk1;
						} else {
							$risk = $risk2;
						}
					}

					$bet = $total * $risk/100;
					$remain = $total - $bet;

					$page[] = 'Ezért az '.OrderText($i+1).' kérdés után $'.BigNum($money).'+'.BigNum($i==0 ? $prize : 2*$prize).'='.BigNum($total).'$ Ft-ja van.';
					$page[] = 'Ennek a $'.$risk.'\%$-át, azaz $'.($risk != 100 ? BigNum($total).'\cdot'.round2($risk/100).'=' : '').BigNum($bet).'$ Ft-ot tesz fel a következő kérdésnél, '.($remain == 0 ? 'magának pedig nem tart meg semmit.' : '$'.BigNum($remain).'$ Ft-ot pedig megtart magának.');
				}

			} else {

				$page[] = '<b>'.strval($i+1).'. kérdés</b><br />A játékos az '.OrderText($i+1).' kérdésre rosszul válaszolt, ezért az előzőleg feltett pénzét elvesztette, és '.($remain == 0 ? '' : 'mindössze').' <span class="label label-success">$'.BigNum($remain).'$</span> Ft-ja maradt.';

			}

			$money 		= $remain;
			$prize		= $bet;
			$hints[] 	= $page;
			$page 		= [];
		}

		// print_r($hints);

		return array($hints, $total);
	}
}

?>