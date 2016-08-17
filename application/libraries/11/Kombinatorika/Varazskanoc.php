<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Varazskanoc {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$type = rand(2,5); // number of candle types
		$mult = rand(2,4); // number of each type
		$candles = $type*$mult; // total number of candles
		$days = $type; // number of pulls
		$colors = ['piros', 'sárga', 'kék', 'zöld', 'lila'];
		shuffle($colors);

		// // Original exercise
		// $type = 3;
		// $mult = 2;
		// $colors = ['piros', 'lila', 'narancssárga'];
		// $candles = $type*$mult;
		// $days = $type;

		$question = 'Zsófi a gyertyák öntéséhez '.NumText($type).' különböző fajta „varázskanócot” használ. Mindegyik fajta „varázskanóc” fehér színű, de meggyújtáskor (a benne lévő anyagtól függően) az egyik fajta '.$colors[0].', a másik '.$colors[1].($type>=3 ? ', a harmadik '.$colors[2] : '').($type>=4 ? ', a negyedik '.$colors[3] : '').($type>=5 ? ', az ötödik '.$colors[4] : '').' lánggal ég. Zsófi hétfőn egy dobozba tesz $'.$candles.'$ darab gyertyát, mind '.The($type).' '.NumText($type).' fajtából '.NumText($mult).'-'.NumText($mult).' darabot. Keddtől kezdve minden nap véletlenszerűen kivesz egy gyertyát a dobozból, és meggyújtja. Számítsa ki annak a valószínűségét, hogy Zsófi az első '.NumText($days).' nap '.NumText($days).' különböző színű lánggal égő gyertyát gyújt meg!';

		$total_options = range($candles, $candles-$days+1);
		$total = fact($candles,$candles-$days+1);
		$choices = array_fill(0, $days, $mult);
		$good = fact($type) * pow($mult, $days);

		$page[] = '(Ha az azonos színű lánggal égőket megkülönböztetjük egymástól, akkor) Zsófi összesen $'.implode('\cdot', $total_options).'='.round2($total).'$-féleképpen választhatja ki az első '.NumText($days).' gyertyát. Ez lesz az <span class="label label-info">összes</span> esetek száma.';
		$page[] = 'A '.NumText($type).'féle szín sorrendje $'.$type.'!='.fact($type).'$-féle lehet.';
		$page[] = 'Egy adott színsorrend esetén $'.implode('\cdot', $choices).'='.pow($mult, $days).'$ választási lehetőség van.';
		$page[] = 'Ezért a <span class="label label-info">kedvező</span> esetek száma $'.fact($type).'\cdot'.pow($mult, $days).'='.round2($good).'$.';
		$page[] = 'A keresett valószínűség a kedvező és összes esetek hányadosa, azaz <span class="label label-success">$\frac{'.round2($good).'}{'.round2($total).'}$</span>.';
		$hints[] = $page;

		$correct = [$good, $total];
		$solution = '$\frac{'.$good.'}{'.$total.'}$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> 'fraction'
		);
	}
}

?>