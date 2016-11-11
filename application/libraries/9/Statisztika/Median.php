<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Median {

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

		$question = 'Határozza meg az alábbi adatsor mediánját!$$'.implode(';', $set).'$$';
		$result = $this->Median($set);
		$correct = $result['median'];
		$solution = '$'.round2($correct).'$';

		$page[] = '<div class="alert alert-info"><b>Medián</b><br/><ul><li>Ha <i>páratlan</i> sok elemünk van, akkor a medián a sorba rendezett számsorozat <i>középső</i> eleme.<br/></li><li>Ha <i>páros</i> sok elemünk van, akkor a medián a <i>középső két szám átlaga</i>.</li></ul></div>';
		$hints[] = $page;

		$page = [];
		$page[] = 'Most összesen $'.$size.'$ darab számunk van, ami egy '.($size%2==0 ? 'páros' : 'páratlan').' szám.';
		$page[] = 'Ezért a medián értéke '.($size%2==0 ? 'a két középső elem átlaga, vagyis $\frac{'.$result['low'].'+'.$result['high'].'}{2}=$' : 'a középső elem, vagyis ').'<span class="label label-success">$'.round2($correct).'$</span>.';			
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
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
			$set[] = rand(1, 9);
		}

		sort($set);

		return $set;
	}

	// Calculate median of array
	function Median($arr) {
		sort($arr);
		$count = count($arr); //total numbers in array
		$middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
		if($count % 2) { // odd number, middle is the median
			$result['median'] = $arr[$middleval];
		} else { // even number, calculate avg of 2 medians
			$result['low'] = $arr[$middleval];
			$result['high'] = $arr[$middleval+1];
			$result['median'] = (($result['low']+$result['high'])/2);
		}

		return $result;
	}
}

?>