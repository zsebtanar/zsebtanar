<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tigris_tenyeszt {

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
			$male 	= rand(2,3);
			$female = $male + rand(1,2);
		} elseif ($level <= 6) {
			$male 	= rand(3,4);
			$female = $male + rand(2,3);
		} else {
			$male 	= rand(4,5);
			$female = $male + rand(3,4);
		}

		// // Original exercise
		// $male = 4;
		// $female = 5;

		$total	= $male + $female;
		$min 	= $male-1;

		$question = 'Egy állatkert a tigrisek fennmaradása érdekében tenyésztő programba kezd. Beszereznek $'.$male.'$ hím és $'.$female.'$ nőstény kölyöktigrist, melyeket egy kisebb és egy nagyobb kifutóban kívánnak elhelyezni a következő szabályok mindegyikének betartásával:
			<ol type="I">
				<li>'.NumText($min).By($min).' kevesebb tigris egyik kifutóban sem lehet;</li>
				<li>a nagyobb kifutóba több tigris kerül, mint a kisebbikbe;</li>
				<li>mindkét kifutóban hím és nőstény tigrist is el kell helyezni;</li>
				<li>egyik kifutóban sem lehet több hím, mint nőstény tigris.</li>
			</ol>
			Hányféleképpen helyezhetik el a $'.$total.'$ tigrist a két kifutóban?<br />(A tigriseket megkülönböztetjük egymástól, és két elhelyezést eltérőnek tekintünk, ha van olyan tigris, amelyik az egyik elhelyezésben más kifutóban van, mint a másik elhelyezésben.)'
		;

		for ($i=$min; $i <= $total; $i++) { 
			
			if ($i == $min) {
				$page[] = 'Válogassuk szét az eseteket aszerint, hogy a kisebbik kifutóban hány tigris van!';
			} else {
				$page = [];	
			}
			
			$page[] = '<b>'.strval($i-$min+1).'. eset:</b> a kisebbik kifutóban $'.$i.'$ tigris van, a nagyobbikban pedig $'.strval($total-$i).'$.'.($i == $min ? ' (Ennél kevesebb az I) feltétel miatt sehol sem lehet.)' : '');

			if ($i >= $total-$i) {

				$page[] = 'Ez az eset azért <span class="label label-danger">nem jó</span>, mert a II) feltétel szerint a kisebb kifutóban kevesebb tigrisnek kell lennie, mint a nagyobbikban. (És itt meg is állhatunk, mert ha tovább növeljük a tigrisek számát, ez a feltétel úgy sem fog teljesülni.)';
				$hints[] = $page;
				break;

			} 

			$page[] = 'Most válogassuk szét az eseteket aszerint, hogy '.The($i).' $'.$i.'$ tigris közül hány nőstény!';


			for ($j=0; $j <= $i; $j++) {

				$text = '<ul><li><i>'.strtoupper(The($i)).' $'.$i.'$ tigris közül $'.$j.'$ hím és $'.strval($i-$j).'$ nőstény.</i><br />';

				if ($i == 0 || $j == 0) {

					$text .= 'Ez az eset azért <span class="label label-danger">nem jó</span>, mert a III) feltétel miatt nősténynek és hímnek is kell lennie a kifutóban.';

				} elseif ($j > $i-$j) {

					$text .= 'Ez az eset azért <span class="label label-danger">nem jó</span>, mert a IV) feltétel miatt nem lehet a kifutóban több hím, mint nőstény.';

				} elseif ($male-$j > $female-$i+$j) {

					$text .= 'Ez az eset azért <span class="label label-danger">nem jó</span>, mert ha ez azt jelentené, hogy a másik kifutóban $'.strval($male-$j).'$ hím és $'.strval($female-$i+$j).'$ nőstény van, viszont a IV) feltétel miatt nem lehet a kifutóban több hím, mint nőstény.';

				} else {

					$female_opt = binomial_coeff($female, $i-$j);
					$male_opt	= binomial_coeff($male, $j);
					$answers[] 	= $female_opt * $male_opt;

					$text .= 'Ez az eset <span class="label label-success">jó</span>, mert minden feltételnek megfelel.
						<ul>
							<li>$'.$male.'$ hím közül $'.$j.'$-'.Dativ($j).' összesen ${'.$male.'\choose '.$j.'}='.$male_opt.'$-féleképpen lehet kiválasztani;</li>
							<li>$'.$female.'$ nőstény közül $'.strval($i-$j).'$-'.Dativ($i-$j).' összesen ${'.$female.'\choose '.strval($i-$j).'}='.$female_opt.'$-féleképpen lehet kiválasztani;</li>
							<li>Ha már tudjuk, hogy a kisebbik kifutóban melyik tigrisek vannak, akkor már azt is tudjuk, hogy a nagyobbikban melyikek vannak, tehát ezt nem kell külön kiszámolni.</li>
							<li>Ebben az esetben összesen $'.$male_opt.'\cdot'.$female_opt.'=$<span class="label label-info">$'.strval($female_opt*$male_opt).'$</span> különböző lehetőség.</li>
						</ul>';
				}

				$text .= '</li></ul>';
				$page[] = $text;

			}

			$hints[] = $page;
		}

		$page = [];
		$page[] = 'Tehát összesen $'.implode('+', $answers).'=$<span class="label label-success">$'.array_sum($answers).'$</span>-féleképpen lehet elhelyezni a tigriseket a két kifutóban.';
		$hints[] = $page;

		$correct = array_sum($answers);
		$solution = '$'.$correct.'$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'youtube'	=> 'ymyhQ4Dm9wA'
		);
	}
}

?>