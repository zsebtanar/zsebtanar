<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tigris1 {

	// Class tiger1ructor
	function __tiger1ruct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$year_diff 	= rand($level, 2*$level);
		$year2 = 2014 + $year_diff;

		$tiger1 = rand($level, 5*$level)*100;
		$rate1 = rand(750, 950)/1000;

		// // Original exercise
		// $year_diff = 2;
		// $year2 = 2014 + $year_diff;
		// $tiger1 = 3600;
		// $rate1 = 0.854;

		$rate2 = round1(pow($rate1, $year_diff),3);
		$rate2_percent = round1(100*$rate2,0);
		$tiger2 = round($tiger1*$rate2);

		$question = 'Egy $2014$ végén készült előrejelzés szerint az Indiában élő tigrisek $t$ száma az elkövetkező években (az egyes évek végén) megközelítőleg a következő összefüggés szerint alakul: $t(x) = '.$tiger1.' \cdot '.round2($rate1,3).'^x$, ahol $x$ a $2014$ óta eltelt évek számát jelöli. Számítsa ki, hogy az előrejelzés alapján $'.$year2.'$ végére hány százalékkal csökken a tigrisek száma a $2014$-es év végi adathoz képest! <i>(A megoldást egészekre kerekítve adja meg!)</i>';

		$page[] = '<b>1. megoldás:</b> Ha az $x$ helyére $0$-t írunk, megkapjuk a tigrisek számát $2014$-ben:$$t(0)='.$tiger1.'\cdot'.round2($rate1,3).'^0='.$tiger1.'\cdot1='.$tiger1.'$$';
		$page[] = '$2014$ és $'.$year2.'$ között összesen $'.$year2.'-2014='.$year_diff.'$ év telik el.';
		$page[] = 'Ezért ha az $x$ helyére $'.$year_diff.'$-'.Dativ($year_diff).' írunk, megkapjuk, hogy az előrejelzés szerint $'.$year2.'$-'.In($year2).' hány tigris lesz:'
			.'$$t('.$year_diff.')='.$tiger1.'\cdot'.round2($rate1,3).'^{'.$year_diff.'}'.($rate2_percent == 100*$rate2 ? '=' : '\approx').$tiger1.'\cdot'.round2($rate2,3).($tiger1*$rate2 == $tiger2 ? '=' : '\approx').$tiger2.'$$';
		$page[] = 'Osszuk el a $t('.$year_diff.')$-'.Dativ($year_diff).' elosztjuk $t(0)$-lal:'
			.'$$\frac{t('.$year_diff.')}{t(0)}=\frac{'.round2($tiger2,0).'}{'.$tiger1.'}\approx'.round2($rate2,3).($rate2_percent == 100*$rate2 ? '=' : '\approx').$rate2_percent.'\%$$';
		$page[] = 'Ez azt jelenti, hogy $'.$year2.'$-'.In($year2).' a tigrisek száma $2014$-hez képest csak $'.$rate2_percent.'\%$ lesz, vagyis a számuk $100\%-'.$rate2_percent.'\%=$<span class="label label-success">$'.strval(100-$rate2_percent).'$</span>$\%$-kal csökken.';
		$hints[] = $page;

		$page = [];
		$page[] = '<b>2. megoldás:</b> A tigrisek száma minden évben az előző évinek $'.round2($rate1,3).'$-'.Times2($rate1*1000).' változik.';
		$page[] = 'Ekkor $'.$year_diff.'$ év alatt a változás $'.round2($rate1,3).'^'.$year_diff.($rate2_percent == $rate2*100 ? '=' : '\approx').round2($rate2,3).($rate2_percent == 100*$rate2 ? '=' : '\approx').$rate2_percent.'\%$ lesz.';
		$page[] = 'Azaz, a tigrisek száma $100\%-'.$rate2_percent.'\%=$<span class="label label-success">$'.strval(100-$rate2_percent).'$</span>$\%$-kal csökken.';
		$hints[] = $page;

		$correct = 100-$rate2_percent;
		$solution = '$'.$correct.'$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'labels'	=> ['right' => '$\%$'],
			'hints'		=> $hints
		);
	}
}

?>