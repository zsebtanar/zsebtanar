<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oszthatosag {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define probability of divisibility
	function Generate($level) {

		if ($level <= 3) {
			$num = rand(3,10);
			$divisor = 3;
		} elseif ($level <= 6) {
			$num = rand(10,30);
			$divisor = rand(4,5);
		} else {
			$num = rand(30,50);
			$divisor = rand(6,9);
		}

		$option1 = (rand(1,2) == 1 ? 'kisebb' : 'nem nagyobb');
		$option2 = (rand(1,2) == 1 ? 'pozitív' : 'nemnegatív');
		$option3 = (rand(1,2) == 1 ? 'páros' : 'páratlan');

		$divisors = array(
			'nullával',
			'eggyel',
			'kettővel',
			'hárommal',
			'néggyel',
			'öttel',
			'hattal',
			'héttel',
			'nyolccal',
			'kilenccel'
		);

		list($hints, $good, $total) = $this->Hints($num, $option1, $option2, $option3, $divisor);

		$question = strtoupper(The($num)).' $'.$num.'$-'.By($num).' '.$option1.' '.$option2.' '
			.$option3.' számok közül egyet véletlenszerűen kiválasztunk. Mennyi a valószínűsége '
			.'annak, hogy '.$divisors[$divisor].' osztható számot választunk?';
		$correct = array($good, $total);
		$solution = '$\frac{'.$good.'}{'.$total.'}$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'type'		=> 'fraction'
		);
	}

	function Hints($num, $option1, $option2, $option3, $divisor) {

		$first = ($option2 == 'pozitív' ? 1 : 0);
		$last = ($option1 == 'kisebb' ? $num-1 : $num);

		$page[] = 'Először nézzük meg, hogy hány $'.$num.'$-'.By($num).' '.$option1.' '.$option2.' '
			.'szám van!';
		$page[] = 'Mivel '.$option2.' számokat nézünk, a számolást $'.$first.'$-'.By($first).' kezdjük.';
		$page[] = 'Továbbá, $'.$num.'$-'.By($num).' '.$option1.' számokat nézünk, azaz az utolsó szám '.The($last)
			.' $'.$last.'$ lesz:';

		$nums = '';
		for ($i=$first; $i <= $last; $i++) {
			$nums .= '$'.$i.($i == $last ? '' : ';').'$';
		}
		$page[] = '<div class="text-center">'.$nums.'</div>';
		$hints[] = $page;

		$page = [];
		$page[] = 'Most ebből válasszuk ki a '.$option3.' számokat!';
		$nums = '';
		$total = 0;
		for ($i=$first; $i <= $last; $i++) {
			if (($option3 == 'páros' && $i%2 == 0) ||
				($option3 == 'páratlan' && $i%2 == 1)) {
				$nums .= '$\textcolor{blue}{\fbox{'.$i.'}}'.($i == $last ? '' : ';').'$';
				$total++;
			} else {
				$nums .= '$'.$i.($i == $last ? '' : ';').'$';
			}
		}
		$page[] = '<div class="text-center">'.$nums.'</div>';
		$page[] = 'Ez összesen $'.$total.'$ szám; ez lesz az <b>összes</b> esetek száma.';
		$hints[] = $page;

		$page = [];
		$page[] = 'Most nézzük meg, hogy a kiválasztott számok közül hány osztható $'.$divisor.'$-'.With($divisor).'!';
		$nums = '';
		$good = 0;
		for ($i=$first; $i <= $last; $i++) {
			if (($option3 == 'páros' && $i%2 == 0) ||
				($option3 == 'páratlan' && $i%2 == 1)) {

				if ($i % $divisor == 0) {
					$nums .= '$\textcolor{red}{\fbox{'.$i.'}}'.($i == $last ? '' : ';').'$';
					$good++;
				} else {
					$nums .= '$\textcolor{blue}{\fbox{'.$i.'}}'.($i == $last ? '' : ';').'$';	
				}
			} else {
				$nums .= '$'.$i.($i == $last ? '' : ';').'$';
			}
		}
		$page[] = '<div class="text-center">'.$nums.'</div>';
		$page[] = 'Ez összesen $'.$good.'$ szám; ez lesz a <b>kedvező</b> esetek száma.';
		$hints[] = $page;

		$page = [];
		$page[] = 'A valószínűséget úgy kapjuk meg, ha a <b>kedvező</b> esetek számát elosztjuk'
			.' az <b>összes</b> esetek számával.';
		$page[] = 'Tehát a keresett valószínűség <span class="label label-success">$\frac{'.$good.'}{'.$total.'}$</span>.';
		$hints[] = $page;

		return array($hints, $good, $total);
	}
}

?>