<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gula_szinezes {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$side = rand(3,4);
		$colors = [['piros','pirossal'], ['sárga','sárgával'], ['kék','kékkel'], ['zöld','zölddel']];
		shuffle($colors);

		// // Original exercise
		// $side = 4;
		// $colors = [['kék','kékkel'], ['zöld','zölddel']];

		$question = 'Zsófi szabályos '.($side==3 ? 'háromszög' : 'négyzet').'alapú gúla alakú gyertyák lapjait szeretné kiszínezni. Mindegyik lapot (az alaplapot és az oldallapokat is) egy-egy színnel, '.$colors[0][1].' vagy '.$colors[1][1].' fogja színezni. Hányféle különböző gyertyát tud Zsófi ilyen módon elkészíteni? (Két gyertyát különbözőnek tekintünk, ha forgatással nem vihetők egymásba.)';

		$correct = ($side==3 ? 8 : 12);
		$solution = '$'.$correct.'$';

		$page[] = 'Az alaplapot kétféleképpen lehet kiszínezni.';
		$page[] = 'Az oldallapok lehetnek ugyanolyan színűek, mindegyik '.$colors[0][0].', vagy mindegyik '.$colors[1][0].' (két eset).';

		if ($side == 3) {

			$page[] = 'Lehet két oldallap '.$colors[0][0].' és egy '.$colors[1][0].', vagy két oldallap '.$colors[1][0].' és egy '.$colors[0][0].' (két eset).';
			$page[] = 'Az oldallapokat tehát négyféleképpen lehet kiszínezni.';
			$page[] = 'Összesen $2\cdot4=8$-féle különböző színezés készíthető.';

		} else {

			$page[] = 'Lehet három oldallap '.$colors[0][0].' és egy '.$colors[1][0].', vagy három oldallap '.$colors[1][0].' és egy '.$colors[0][0].' (két eset).';
			$page[] = 'Olyan festésből, amikor két oldallap '.$colors[0][0].' és két oldallap '.$colors[1][0].', szintén kétféle lehet, attól függően, hogy az ugyanolyan színű lapok szomszédosak vagy szemköztiek.';
			$page[] = 'Az oldallapokat tehát hatféleképpen lehet kiszínezni.';
			$page[] = 'Összesen $2\cdot6=12$-féle különböző színezés készíthető.';

		}
		$hints[] = $page;

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}
}

?>