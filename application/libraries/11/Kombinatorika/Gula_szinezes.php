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

		$page[] = 'Az <b>alaplapot</b> <span class="label label-warning">kétféleképpen</span> lehet kiszínezni.';
		$page[] = 'Az <b>oldallapok</b> lehetnek ugyanolyan színűek, <span class="label label-info">mindegyik</span> '.$colors[0][0].', vagy <span class="label label-info">mindegyik</span> '.$colors[1][0].' (két eset).';

		if ($side == 3) {

			$page[] = 'Lehet <span class="label label-info">két</span> oldallap '.$colors[0][0].' és <span class="label label-info">egy</span> '.$colors[1][0].', vagy <span class="label label-info">két</span> oldallap '.$colors[1][0].' és <span class="label label-info">egy</span> '.$colors[0][0].' (két eset).';
			$page[] = 'Az oldallapokat tehát <span class="label label-warning">négyféleképpen</span> lehet kiszínezni.';
			$page[] = 'Összesen $2\cdot4=$<span class="label label-success">$8$</span>-féle különböző színezés készíthető.';

		} else {

			$page[] = 'Lehet <span class="label label-info">három</span> oldallap '.$colors[0][0].' és <span class="label label-info">egy</span> '.$colors[1][0].', vagy <span class="label label-info">három</span> oldallap '.$colors[1][0].' és <span class="label label-info">egy</span> '.$colors[0][0].' (két eset).';
			$page[] = 'Olyan festésből, amikor <span class="label label-info">két</span> oldallap '.$colors[0][0].' és <span class="label label-info">két</span> oldallap '.$colors[1][0].', szintén kétféle lehet, attól függően, hogy az ugyanolyan színű lapok szomszédosak vagy szemköztiek.';
			$page[] = 'Az oldallapokat tehát <span class="label label-warning">hatféleképpen</span> lehet kiszínezni.';
			$page[] = 'Összesen $2\cdot6=$<span class="label label-success">$12$</span>-féle különböző színezés készíthető.';

		}
		$hints[] = $page;

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'youtube'	=> 'AWSk0pLWlwk'
		);
	}
}

?>