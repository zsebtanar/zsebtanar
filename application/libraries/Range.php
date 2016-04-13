<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Range {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define range, mean, standard deviation
	function Generate($level) {

		$set = $this->Set($level);

		$question = 'Határozza meg az alábbi adatsor terjedelmét!';
		$correct = array(min($set), max($set));
		$solution = 'az adatsor terjedelme: $['.min($set).';'.max($set).']$';
		$type = 'range';
		$hints = $this->Hints($set);

		$question .= '$$'.implode(';', $set).'$$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type'		=> $type,
			'hints' 	=> $hints
		);
	}

	function Set($level) {

		$length = rand(2*$level,3*$level);

		for ($i=0; $i < $length; $i++) {
			$set[] = rand(1,5);
		}

		sort($set);

		return $set;
	}

	function Hints($set) {

		$page[] = 'A terjedelemhez meg kell határoznunk az adatsor minimumát és maximumát.';
		$page[] = 'A minimum: $'.min($set).'$';
		$page[] = 'A maximum: $'.max($set).'$';
		$page[] = 'Az adatsor terjedelme: <span class="label label-success">$['.min($set).';'.max($set).']$</span>.';
		$hints[] = $page;

		return $hints;
	}
}

?>