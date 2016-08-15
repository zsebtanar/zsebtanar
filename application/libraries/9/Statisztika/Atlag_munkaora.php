<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atlag_munkaora {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		// Average number of work hours
		$avg = 8;

		list($hours, $unknown) = $this->Hours($avg);

		// // Original exercise
		// $hours = array(
		// 	6 => 4,
		// 	7 => 5,
		// 	8 => 3,
		// 	9 => 7,
		// 	10 => 3
		// );
		// $unknown = [8,9];

		$question = 'A Péter szerződésében szereplő napi $8$ óra munkaidő rugalmas, azaz lehetnek olyan napok, amikor $8$ óránál többet, és olyanok is, amikor kevesebbet dolgozik. $6$ óránál kevesebbet, illetve $10$ óránál többet sosem dolgozik egy nap. Az alábbi táblázatban Péter januári munkaidő-kimutatásának néhány adata látható.
			<table class="table table-bordered">
				<tr>
					<td>Napi munkaidő (óra)</td>
					<td>$6$</td>
					<td>$7$</td>
					<td>$8$</td>
					<td>$9$</td>
					<td>$10$</td>
				</tr>
				<tr>
					<td>Hány munkanapon dolgozott ennyi órát?</td>';

		$unknown_left = 2;
		for ($i=6; $i < 11; $i++) {
			if (in_array($i, $unknown)) {
				$question .= '<td>'.($unknown_left==2 ? '$A$' : '$B$').'</td>';
				$unknown_left--;
			} else {
				$question .= '<td>$'.$hours[$i].'$</td>';
			}
		}

		$question .= '</tr></table>
		Számítsa ki a táblázatból hiányzó két adatot, ha tudjuk, hogy január hónap $'.array_sum($hours).'$ munkanapján Péter átlagosan naponta $'.$avg.'$ órát dolgozott!';

		$hints = $this->Hints($hours, $unknown,$avg);

		$correct = [$hours[$unknown[0]], $hours[$unknown[1]]];
		$solution = '$A='.$hours[$unknown[0]].'$, $B='.$hours[$unknown[1]].'$.';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> 'array',
			'labels'	=> ['$A$', '$B$']
		);
	}

	function Hours($avg) {

		$days = 1;

		while ($days > 0) {

			$days = rand(15,22);

			$hours = array(
				6 => 0,
				7 => 0,
				8 => 0,
				9 => 0,
				10 => 0
			);

			$total = $days * $avg;

			while ($total > 0) {

				$hour = rand(6,10);

				if ($total >= $hour && $days > 0) {

					// Enough total number of hours left
					$hours[$hour]++;
					$total -= $hour;
					$days--;

				} else {

					// Not enough total number of hours left
					while ($total > 0) {

						$hour = rand(6,9);

						if ($hours[$hour] > 0) {

							$hours[$hour]--;
							$hours[$hour+1]++;
							$total--;
						}

					}
				}
			}
		}

		$range = range(6,10);
		shuffle($range);
		$unknown[0] = $range[0];
		$unknown[1] = $range[1];
		sort($unknown);

		return array($hours, $unknown);
	}

	function Hints($hours, $unknown, $avg) {

		$days = array_sum($hours);
		$total = $days * $avg;

		$total1 = 0;
		$total2 = 0;

		foreach ($hours as $hour => $day) {
			if (in_array($hour, $unknown)) {
				$hours2[] = $hour;
				$days2[] = $day;
				$total2 += $hour * $day;
			} else {
				$hours1[] = $hour;
				$days1[] = $day;
				$total1 += $hour * $day;
			}
		}

		$total_days2 = array_sum($days2);
		$total_days1 = array_sum($days1);

		$page[] = 'Péter havi munkaideje $'.$days.'\cdot'.$avg.'='.$total.'$ óra.';
		$page[] = 'Azon '.The($total_days1).' $'.$total_days1.'$ napon, amikor $'.$hours1[0].',\,'.$hours1[1].'$ vagy $'.$hours1[2].'$ órát dolgozott, összesen $$'.$days1[0].'\cdot'.$hours1[0].'+'.$days1[1].'\cdot'.$hours1[1].'+'.$days1[2].'\cdot'.$hours1[2].'='.$total1.'$$órát dolgozott.';
		$page[] = 'Tehát azon '.The($total_days2).' $'.$total_days2.'$ napon, amikor $'.$hours2[0].'$ vagy $'.$hours2[1].'$ órát dolgozott, összesen $$'.$total.'-'.$total1.'='.$total2.'$$ órát dolgozott.';
		$hints[] = $page;

		$page = [];
		for ($i=$total_days2; $i > 0; $i--) { 

			$unknown_hour = $i*$unknown[0] + ($total_days2-$i)*$unknown[1];
			$diff = $total2 - $unknown_hour;

			if ($i == $total_days2) {
				$text = 'Ha mind '.The($i).' <span class="label label-info">$'.$i.'$</span> napon $'.$unknown[0].'$ órát dolgozott volna, akkor összesen $'.$i.'\cdot'.$unknown[0].'='.$unknown_hour.'$ órát dolgozott volna.';
			} else {
				$text = 'Ha <span class="label label-info">$'.$i.'$</span> napon $'.$unknown[0].'$ órát, és <span class="label label-info">$'.strval($total_days2-$i).'$</span> napon $'.$unknown[1].'$ órát dolgozott volna, akkor összesen $'.$i.'\cdot'.$unknown[0].'+'.strval($total_days2-$i).'\cdot'.$unknown[1].'='.$unknown_hour.'$ órát dolgozott volna.';
			}

			if ($diff == 0) {
				$text .= ' Ez pontosan annyi, amennyit az előbb számoltunk, ezért Péter januárban <span class="label label-success">$'.$hours[$unknown[0]].'$</span> napon dolgozott $'.$unknown[0].'$ órát, és <span class="label label-success">$'.$hours[$unknown[1]].'$</span> napon dolgozott $'.$unknown[1].'$ órát.';
			} else {
				$text .= ' Ez $'.abs($diff).'$-'.With($diff).' '.($diff>0 ? 'kevesebb' : 'több').' mint $'.$total2.'$, tehát ez nem jó megoldás.';
			}

			$page[] = $text;

			if ($diff == 0) {
				break;
			}

		}

		$hints[] = $page;

		return $hints;
	}
}

?>