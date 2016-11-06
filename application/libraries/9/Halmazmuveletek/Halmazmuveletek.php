<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Halmazmuveletek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		// Select operation
		$options = ['$A\setminus B$', '$B\setminus A$', '$A\cap B$', '$A\cup B$'];

		if ($level <= 3) {
			$correct = 0;
		} elseif ($level <= 6) {
			$correct = 1;
		} else {
			$correct = 2;
		}

		$operation = $options[$correct];

		$question 	= 'Melyik halmazművelet eredménye látható az alábbi ábrán?'.$this->VennDiagram($operation);
		$solution 	= $operation;
		$hints 		= $this->Hints($options);

		shuffleAssoc($options);
		
		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'hints'		=> $hints
		);
	}

	function Hints($operations) {

		$hints[][] = 'Nézzük meg, melyik művelet mit jelent!';

		foreach ($operations as $operation) {
			switch ($operation) {
				case '$A\setminus B$': // A\B
					$hints[][] = 'Az $A\setminus B$ azokat a számokat jelöli, amik a $A$ halmazban benne vannak, de a $B$ halmazban nem, vagyis az $A$ és $B$ halmaz <b>különbségét</b>:'.$this->VennDiagram($operation);
					break;
				case '$B\setminus A$': // B\A
					$hints[][] = 'Az $B\setminus A$ azokat a számokat jelöli, amik a $B$ halmazban benne vannak, de a $A$ halmazban nem, vagyis a $B$ és $A$ halmaz <b>különbségét</b>:'.$this->VennDiagram($operation);
					break;
				case '$A\cup B$': // union(B,A)
					$hints[][] = 'Az $A\cup B$ azokat a számokat jelöli, amik a $A$ vagy a $B$ halmazban vannak, vagyis a két halmaz <b>unióját</b> osztót:'.$this->VennDiagram($operation);
					break;
				case '$A\cap B$': // intersect(B,A)
					$hints[][] = 'Az $A\cap B$ azokat a számokat jelöli, amik a $A$ és $B$ halmazban is benne vannak, vagyis a két halmaz <b>metszetét</b>:'.$this->VennDiagram($operation);
					break;
			}
		}

		return $hints;
	}

	function VennDiagram($operation=NULL) {

		$width 	= 400;
		$height = 300;

		$radius = 100;

		$svg = '<div class="img-question text-center">
					<svg width="400" height="300">'
					// .'<rect width="400" height="300" fill="black" fill-opacity="0.2" />'
					.'<circle cx="130" cy="150" r="100" stroke="black" stroke-width="1" fill="none" />
					<circle cx="260" cy="150" r="100" stroke="black" stroke-width="1" fill="none" />
					<text font-size="15" fill="black" x="80" y="50">$A$</text>
					<text font-size="15" fill="black" x="300" y="50">$B$</text>';

		switch ($operation) {
			case '$A\setminus B$': // A\B
				$svg .= '<path d = "M 195 73 A 100 100 1 0 0 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 0 1 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case '$B\setminus A$': // B\A
				$svg .= '<path d = "M 195 73 A 100 100 1 0 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 0 1 1 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case '$A\cup B$': // union(B,A)
				$svg .= '<path d = "M 195 73 A 100 100 0 1 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 1 1 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case '$A\cap B$': // intersect(B,A)
				$svg .= '<path d = "M 195 73 A 100 100 0 0 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 1 0 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>