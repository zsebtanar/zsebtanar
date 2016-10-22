<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ottusa_2 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('9/Szamhalmazok/Ottusa_1', NULL, 'ottusa');

		$min = rand(5,9);
		$sec = (rand(1,3) == 1 ? rand(10,99) : rand(0,2)*33);
		$sec = ($min == 5 && $sec < 66 ? 66 : $sec);
		$sec = ($min == 9 && $sec >= 33 ? rand(0,32) : $sec);

		$question = 'Az öttusa úszás számában $200$ métert kell úszni. Az elért időeredményekért járó pontszámot mutatja a grafikon.';
		$question .= $CI->ottusa->Graph();

		$point = rand(314, 322);

		// // Original exercise
		// $point = 317;

		$question .= 'Péter $'.$point.'$ pontot kapott. Az alábbiak közül válassza ki Péter összes lehetséges időeredményét!';
		list($options, $correct, $times) = $this->Options($point);
		$solution = '';
		$type = 'multi';

		foreach ($times as $time) {
			$point2 = $CI->ottusa->Point($time[0], $time[1]);
			$hints[][] = 'Ha Péter időeredménye $2$ perc $'.$time[0].','.($time[1] == 0 ? '00' : $time[1]).'$ mp lett volna, akkor $'.$point2.'$ pontot kapott volna, tehát ez egy '.($point2 == $point ? '<span class="label label-success">jó</span>' : '<span class="label label-danger">rossz</span>').' megoldás.'.$CI->ottusa->Graph($time[0], $time[1], $point2);
		}

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'type' 		=> $type,
			'hints'		=> $hints,
			'youtube'	=> 'PuhyNKnH28U'
		);
	}

	function Options($point) {

		$CI =& get_instance();
		$CI->load->library('9/Szamhalmazok/Ottusa_1', NULL, 'ottusa');

		if ($point % 3 == 0) {

			$min = 8 - ($point - 315)/3;
			$sec = 33;

			$times[] = [$min, 0];
			$times[] = [$min, rand(1,32)];
			$times[] = [$min, 33];
			$times[] = [$min, rand(34,65)];
			$times[] = [$min, 66];

		} elseif ($point % 3 == 1) {

			$min = 9 - ($point - 313)/3;
			$sec = 0;

			$times[] = [$min-1, 66];
			$times[] = [$min-1, rand(67,99)];
			$times[] = [$min, 0];
			$times[] = [$min, rand(1,32)];
			$times[] = [$min, 33];

		} else {

			$min = 8 - ($point - 314)/3;
			$sec = 66;

			$times[] = [$min, 33];
			$times[] = [$min, rand(34,65)];
			$times[] = [$min, 66];
			$times[] = [$min, rand(67,99)];
			$times[] = [$min+1, 0];
		}

		shuffle($times);

		// // Original exercise
		// $times = [];
		// $times[] = [7,00];
		// $times[] = [7,60];
		// $times[] = [7,80];
		// $times[] = [8,00];

		foreach ($times as $time) {
			
			$min = $time[0];
			$sec = $time[1];

			$point2 = $CI->ottusa->Point($min, $sec);

			$correct[] = $point2 == $point;
			$options[] = '$2$ perc $'.$min.','.($sec == 0 ? '00' : $sec).'$ mp';
		}

		return array($options, $correct, $times);
	}
}

?>