<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AFA {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Get value of VAT of a pair of jeans
	function Generate($level) {

		$unit = rand($level, 3*$level)*10;
		$vat = rand(3+$level, 9+3*$level);

		$price = (100+$vat)*$unit;

		$question = 'A ruházati cikkek nettó árát $'.$vat.'\%$-kal növeli meg az áfa (általános forgalmi adó). A nettó
ár és az áfa összege a bruttó ár, amelyet a vásárló fizet a termék vásárlásakor. Egy nadrágért $'.$price.'\,\text{Ft}$-ot fizetünk.
Hány forint áfát tartalmaz a nadrág ára?';
		$correct = $unit*$vat;
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($unit, $vat);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($unit, $vat) {

		$percent = 100+$vat;
		$price = $percent*$unit;
		$vat_value = $vat*$unit;

		$page[] = 'A nadrág ára két részből tevődik össze: a nettó árból (ez a $100\%$), és az áfából (ez '.The($vat).' $'.$vat.'\%$).';
		$page[] = 'Ez összesen $'.$percent.'\%$, aminek az értéke $'.$price.'\,\text{Ft}$:$$'.$percent.'\%\quad\to\quad'.$price.'\,\text{Ft}$$';
		$page[] = 'Számoljuk ki az $1\%$ értékét! Ehhez el kell osztani '.The($price).' $'.$price.'$-'.Dativ($price).' $'.$percent
			.'$-'.With($percent).': $'.$price.':'.$percent.'='.$unit.'$. Azaz:$$1\%\quad\to\quad'.$unit.'\,\text{Ft}$$';
		$page[] = 'Az áfa $'.$vat.'\%$. Ennek az értékét úgy tudjuk kiszámolni, hogy '.The($unit).' $'.$unit.'$-'.Dativ($unit)
			.' megszorozzuk $'.$vat.'$-'.With($vat).': $'.$unit.'\cdot'.$vat.'='.$vat_value.'$. Azaz$$'.$vat.'\%\quad\to\quad'.$vat_value.'\,\text{Ft}$$';
		$page[] = 'Tehát a nadrág <span class="label label-success">$'.$vat_value.'$</span>$\,\text{Ft}$ áfát tartalmaz.';

		$hints[] = $page;

		return $hints;
	}
}

?>