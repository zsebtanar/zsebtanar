<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osszeg {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$start = rand(100,200)*1000;
		$increase = rand(1,9)*1000;
		$years = rand(4,9);
		$percent = rand(1,5);

		// // Original exercise
		// $start = 200000;
		// $increase = 5000;
		// $years = 4;
		// $percent = 2;

		$question = 'A kereskedelemmel foglalkozó cégek között több olyan is van, amely állandóan emelkedő fizetéssel jutalmazza a dolgozók munkavégzését. Péter munkát keres, és két cég ajánlata közül választhat:
			<ul>
				<li>I. ajánlat: Az induló havi fizetés $'.round2($start,0).'\,\text{Ft}$, amit havonta $'.round2($increase,0).'\,\text{Ft}$-tal emelnek '.NumText($years).' éven át.</li>
				<li>II. ajánlat: Az induló havi fizetés $'.round2($start,0).'\,\text{Ft}$, amit havonta $'.$percent.'\%$-kal emelnek '.NumText($years).' éven át.</li>
			</ul>
			Melyik ajánlatot válassza Péter, ha tervei szerint '.NumText($years).' évig a választott munkahelyen akar dolgozni, és azt az ajánlatot szeretné választani, amelyik a '.NumText($years).' év alatt nagyobb összjövedelmet kínál?';

		$options = ['Az I. ajánlatot.', 'A II. ajánlatot.'];

		list($hints, $correct) = $this->Hints($start, $increase, $years, $percent);
		$solution = $options[$correct];

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'options'	=> $options,
			'youtube'	=> 'v7QDOm25VJM'
		);
	}

	function Hints($start, $increase, $years, $percent) {

		$months = $years*12;
		$sum1 = (2*$start+($months-1)*$increase)/2*$months;
		$page[] = 'Az I. ajánlatban Péter havi fizetései egy $'.round2($increase,0).'$ differenciájú számtani sorozat egymást követő tagjai, ahol a sorozat első tagja $'.round2($start,0).'$.';
		$page[] = 'Péter '.NumText($years).' év alatt összesen $'.$years.'\cdot12='.$months.'$ fizetést kap.';
		$page[] = 'Ez azt jelenti, hogy a számtani sorozat első $'.$months.'$ tagjának összegét kell kiszámolni.';
		$page[] = '$$S_{'.$months.'}=\frac{2\cdot'.round2($start,0).'+'.strval($months-1).'\cdot'.round2($increase,0).'}{2}\cdot'.$months.'='.round2($sum1,0).'\,\text{Ft}$$';
		$page[] = 'Tehát ha Péter az I. ajánlatot választja, összesen $'.round2($sum1,0).'\,\text{Ft}$-ot kap.';
		$hints[] = $page;

		$page = [];
		$ratio = 1+$percent/100;
		$sum2 = round1($start*(pow($ratio,$months)-1)/($ratio-1));
		$page[] = 'Az II. ajánlatban Péter havi fizetései egy $'.round2($ratio,2).'$ hányadosú mértani sorozat egymást követő tagjai, ahol a sorozat első tagja $'.round2($start,0).'$.';
		$page[] = 'Az összjövedelem kiszámításához itt is a sorozat első $'.$months.'$ tagjának összegét kell meghatározni.';
		$page[] = '$$S_{'.$months.'}\'='.round2($start,0).'\cdot\frac{'.round2($ratio,2).'^{'.$months.'}-1}{'.round2($ratio,2).'-1}\approx'.round2($sum2,0).'\,\text{Ft}$$';
		$page[] = 'Tehát ha Péter a II. ajánlatot választja, összesen $'.round2($sum2,0).'\,\text{Ft}$-ot kap.';
		$page[] = 'Mivel $'.round2($sum1,0).($sum1>$sum2 ? '>' : '<').round2($sum2,0).'$, ezért Péternek '.($sum1>$sum2 ? 'az' : 'a').' <span class="label label-success">'.($sum1>$sum2 ? 'I.' : 'II.').' ajánlatot</span> célszerű választani.';
		$correct = ($sum1>$sum2 ? 0 : 1);

		$hints[] = $page;

		return array($hints,$correct);
	}
}

?>