<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Osztok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define members of intersection/union/difference of sets
	function Generate($level) {

		list($num1, $factors1) = $this->GetNumber($level);
		$num2 = $num1;
		while ($num2 == $num1) {
			list($num2, $factors2) = $this->GetNumber($level);
		}

		$divisors1 = divisors($num1);
		$divisors2 = divisors($num2);

		list($operation, $result) = $this->SetOperation($divisors1, $divisors2, $level);

		$question = 'Az $A$ halmaz elemei '.The($num1).' $'.$num1.'$ pozitív osztói, '
			.'a $B$ halmaz elemei '.The($num2).' $'.$num2.'$ pozitív osztói. '
			.'Adja meg '.($operation == 'B\setminus A' ? 'a' : 'az').' $'.$operation.'$ halmazt elemei felsorolásával! <i>(Például: 2;3;4)</i>';
		$correct = $result;
		$solution = '$'.implode("$$;$$", $result).'$';

		$hints = $this->Hints($num1, $divisors1, $num2, $divisors2, $result, $operation);
		// print_r($solution);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'type'		=> 'list2'
		);
	}

	function GetNumber($level) {

		$num = 1;
		$factors = [];

		if ($level <= 3) {
			$primes = [2, 2, 3, 3, 5];
		} elseif ($level <= 6) {
			$primes = [2, 2, 3, 3, 5, 7];
		} else {
			$primes = [2, 2, 3, 5, 7, 11, 13];
		}

		for ($i=0; $i < 2; $i++) {
			shuffle($primes);
			$prime = $primes[0];

			$num *= $prime;

			$factors[$prime] = (isset($factors[$prime]) ? $factors[$prime] + 1 : 1);
		}

		return array($num, $factors);
	}

	function SetOperation($divisors1, $divisors2, $level) {

		$result = [];

		$operations = ['A\setminus B', 'B\setminus A', 'A\cap B'];
		if ($level >= 6) {
			array_push($operations, 'A\cup B');
		}

		while (count($result) == 0) {

			shuffle($operations);

			$operation = $operations[0];

			switch ($operation) {
				case 'A\setminus B': // A\B
					$result = array_diff($divisors1, $divisors2);
					break;
				case 'B\setminus A': // B\A
					$result = array_diff($divisors2, $divisors1);
					break;
				case 'A\cup B': // union(B,A)
					$result = array_unique(array_merge($divisors2, $divisors1));
					break;
				case 'A\cap B': // intersect(B,A)
					$result = array_intersect($divisors2, $divisors1);
					break;
			}
		}

		$result = array_values($result);

		return array($operation, $result);
	}

	function Hints($num1, $divisors1, $num2, $divisors2, $result, $operation) {

		$page[] = 'Írjuk fel '.The($num1).' $'.$num1.'$ összes pozitív osztóját:$$A=\{'.implode(";", $divisors1).'\}$$';
		$page[] = 'Írjuk fel '.The($num2).' $'.$num2.'$ összes pozitív osztóját:$$B=\{'.implode(";", $divisors2).'\}$$';
		$page[] = 'Ábrázoljuk az osztókat <b>Venn-diagramm</b>ban!';
		$hints[] = $page;
		$page 	= [];

		$left 	= array_diff($divisors1, $divisors2);
		$center = array_intersect($divisors1, $divisors2);
		$right 	= array_diff($divisors2, $divisors1);

		if (count($left) > 0) {
			$page[0] = 'Azokat az osztókat, amik benne vannak az $A$ halmazban, de nincsenek benne a $B$-ben, a <b>bal</b> oldalra írjuk:'
				.$this->VennDiagram($left);
			$hints[] = $page;
		}

		if (count($right) > 0) {
			$page[0] = 'Azokat az osztókat, amik benne vannak a $B$ halmazban, de nincsenek benne a $B$-ben, a <b>jobb</b> oldalra írjuk:'
				.$this->VennDiagram($left, [], $right);
			$hints[] = $page;
		}

		if (count($center) > 0) {
			$page[0] = 'Azokat az osztókat, amik az $A$ és $B$ halmazban is benne vannak, <b>középre</b> írjuk:'
				.$this->VennDiagram($left, $center, $right);
			$hints[] = $page;
		}

		switch ($operation) {
			case 'A\setminus B': // A\B
				$text = 'Az $A\setminus B$ azokat az osztókat jelöli, amik az $A$ halmazban benne vannak, de a $B$ halmazban nem, vagyis a <b>bal</b> oldali részt:';
				break;
			case 'B\setminus A': // B\A
				$text = 'Az $B\setminus A$ azokat az osztókat jelöli, amik a $B$ halmazban benne vannak, de az $A$ halmazban nem, vagyis a <b>jobb</b> oldali részt:';
				break;
			case 'A\cup B': // union(B,A)
				$text = 'Az $A\cup B$ azokat az osztókat jelöli, amik az $A$ vagy a $B$ halmazban vannak, vagyis az <b>összes</b> osztót:';
				break;
			case 'A\cap B': // intersect(B,A)
				$text = 'Az $A\cap B$ azokat az osztókat jelöli, amik az $A$ és $B$ halmazban is benne vannak, vagyis a <b>középső részt</b>';
				break;
		}

		$page[0] = $text.$this->VennDiagram($left, $center, $right, $operation);
		$page[1] = 'Tehát '.($operation == 'B\setminus A' ? 'a' : 'az').' $'.$operation.'$ halmaz elemei <span class="label label-success">$'.implode(";", $result).'$</span>.';

		$hints[] = $page;

		return $hints;
	}

	function VennDiagram($left=[], $center=[], $right=[], $operation=NULL) {

		$width 	= 400;
		$height = 300;

		$radius = 100;

		// Predefine coordinates
		$leftx = [120, 100, 100, 60, 150, 140, 70, 70];
		$lefty = [170, 120, 220, 150, 220, 90, 100, 195];

		$centerx = [200, 185, 170, 190];
		$centery = [170, 120, 160, 210];

		$rightx = [300, 250, 240, 300, 250, 265, 290, 330];
		$righty = [170, 130, 220, 120, 90, 180, 220, 150];

		$svg = '<div class="img-question text-center">
					<svg width="400" height="300">'
					// .'<rect width="400" height="300" fill="black" fill-opacity="0.2" />'
					.'<circle cx="130" cy="150" r="100" stroke="black" stroke-width="1" fill="none" />
					<circle cx="260" cy="150" r="100" stroke="black" stroke-width="1" fill="none" />
					<text font-size="15" fill="black" x="80" y="50">$A$</text>
					<text font-size="15" fill="black" x="300" y="50">$B$</text>';

		foreach ($left as $key => $value) {
			$svg .= '<text font-size="15" fill="black" x="'.$leftx[$key].'" y="'.$lefty[$key].'">$'.$value.'$</text>';
		}

		foreach ($center as $key => $value) {
			$svg .= '<text font-size="15" fill="black" x="'.$centerx[$key].'" y="'.$centery[$key].'">$'.$value.'$</text>';
		}

		foreach ($right as $key => $value) {
			$svg .= '<text font-size="15" fill="black" x="'.$rightx[$key].'" y="'.$righty[$key].'">$'.$value.'$</text>';
		}

		switch ($operation) {
			case 'A\setminus B': // A\B
				$svg .= '<path d = "M 195 73 A 100 100 1 0 0 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 0 1 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case 'B\setminus A': // B\A
				$svg .= '<path d = "M 195 73 A 100 100 1 0 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 0 1 1 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case 'A\cup B': // union(B,A)
				$svg .= '<path d = "M 195 73 A 100 100 0 1 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 1 1 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case 'A\cap B': // intersect(B,A)
				$svg .= '<path d = "M 195 73 A 100 100 0 0 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 1 0 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>