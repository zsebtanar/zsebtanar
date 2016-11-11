<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modusz {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$set = $this->Set($level);
		$size = count($set);

		
		$modes = $this->Modusz($set);
		$correct = $modes;
		$solution = '$'.implode(';', $modes).'$';

		$question = 'Határozza meg az alábbi adatsor '.(count($modes)>1 ? 'összes ' : '').'móduszát!$$'.implode(';', $set).'$$';

		$page[] = '<div class="alert alert-info"><b>Módusz</b><br/>A módusz az a szám, amelyik a legtöbbször előfordul a számsorozatban (egy sorozatban több módusz is lehet).</div>';
		$text = 'Nézzük meg, melyik számból mennyi van:<ul>';

		$values = array_count_values($set);
		arsort($values);
		foreach ($values as $key => $value) {
			$text .= '<li>$'.$key.'\rightarrow'.$value.'$ darab</li>';
		}
		$text .= '</ul>';
		$page[] = $text;
		if (count($modes) > 1) {
			$page[] = 'A móduszok az a számok, amelyek a legtöbbször előfordulnak a számsorozatban: <span class="label label-success">$'.implode(';', $modes).'$</span>.';
		} else {
			$page[] = 'A módusz az a szám, amelyik a legtöbbször előfordul a számsorozatban, vagyis '.The($modes[0]).' <span class="label label-success">$'.$modes[0].'$</span>.';
		}
					
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type'		=> 'single_list',
			'hints' 	=> $hints
		);
	}

	function Set($level) {

		if ($level <= 1) {
			$length = rand(2,3);
		} elseif ($level <= 2) {
			$length = rand(2,3)*3;
		} else {
			$length = rand(10,16);
		}

		for ($i=0; $i < $length; $i++) {
			$set[] = rand(1,9);
		}

		sort($set);

		return $set;
	}

	function Modusz($array) {
		$values = array_count_values($array); 
		arsort($values);
		$mode = [];
		foreach($values as $key => $value) {
			if (count($mode) == 0) {
				$mode[] = $key;
				$freq = $value;
			} elseif ($value == $freq) {
				$mode[] = $key;
			}
		} 
		return $mode;
	}
}

?>