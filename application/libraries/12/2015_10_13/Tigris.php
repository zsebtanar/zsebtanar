<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tigris {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$year_diff 	= rand($level, 2*$level);
		$year2 		= 2014 + $year_diff;

		$a = rand($level, 5*$level)*100;
		$b = rand(75, 95)/100;
		$c = round(pow($b, $year_diff)*100)/100;
		$d = $a*$c;

		$b2 = str_replace('.', ',', $b);
		$c2 = str_replace('.', ',', $c);
		$d2 = str_replace('.', ',', $d);
		
		$question = 'Egy $2014$ végén készült előrejelzés szerint az Indiában élő tigrisek $t$ száma az elkövetkező években (az egyes évek végén) megközelítőleg a következő összefüggés szerint alakul: $t(x) = '.$a.' \cdot '.$b2.'^x$, ahol $x$ a $2014$ óta eltelt évek számát jelöli. Számítsa ki, hogy az előrejelzés alapján $'.$year2.'$ végére hány százalékkal csökken a tigrisek száma a $2014$-es év végi adathoz képest! A megoldást egész számokban adja meg!';

		$page[] = '<b>1. megoldás:</b> Ha az $x$ helyére $0$-t írunk, megkapjuk, hogy a tigrisek számát $2014$-ben:$$t(0)='.$a.'\cdot'.$b2.'^0='.$a.'\cdot1='.$a.'$$';
		$page[] = '$2014$ és $'.$year2.'$ között összesen $'.$year2.'-2014='.$year_diff.'$ év telik el.';
		$page[] = 'Ezért ha az $x$ helyére $'.$year_diff.'$-'.Dativ($year_diff).' írunk, megkapjuk, hogy az előrejelzés szerint $'.$year2.'$-'.In($year2).' hány tigris lesz:'
			.'$$t('.$year_diff.')='.$a.'\cdot'.$b2.'^{'.$year_diff.'}\approx'.$a.'\cdot'.$c2.'='.$d2.'$$';
		$page[] = 'Osszuk el a $t('.$year_diff.')$-'.Dativ($year_diff).' elosztjuk $t(0)$-lal:'
			.'$$\frac{t('.$year_diff.')}{t(0)}=\frac{'.$d.'}{'.$a.'}='.$c2.'$$';
		$page[] = 'Ez azt jelenti, hogy $'.$year2.'$-'.In($year2).' a tigrisek száma a $2014$-hez képest csak $'.strval($c*100).'\%$ lesz, vagyis a számuk $100\%-'.strval($c*100).'\%=$<span class="label label-success">$'.strval(100*(1-$c)).'$</span>$\%$-kal csökken.';
		$hints[] = $page;

		$page = [];
		$page[] = '<b>2. megoldás:</b> A tigrisek száma minden évben az előző évinek $'.$b2.'$-'.Times2($b*100).' változik.';
		$page[] = 'Ekkor $'.$year_diff.'$ év alatt a változás $'.$b2.'^'.$year_diff.'\approx'.$c2.'$ lesz.';
		$page[] = 'Azaz, a tigrisek száma $100\%-'.strval($c*100).'\%=$<span class="label label-success">$'.strval(100*(1-$c)).'$</span>$\%$-kal csökken.';
		$hints[] = $page;

		$correct = round(100*(1-pow($b, $year_diff)));
		$solution = '$'.strval(round((1-$c)*100)).'$';

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