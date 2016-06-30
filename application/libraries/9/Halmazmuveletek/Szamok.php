<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szamok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define members of intersection/union/difference of sets
	function Generate($level) {

		if ($level <= 3) {
			$max_num 	= 10;
			$set_size1 	= rand(2,4);
			$set_size2	= rand(2,4);
		} elseif ($level <= 6) {
			$max_num 	= 15;
			$set_size1 	= rand(4,6);
			$set_size2	= rand(4,6);
		} else {
			$max_num 	= 20;
			$set_size1 	= rand(6,8);
			$set_size2	= rand(6,8);
		}

		$set1 = $this->DefineSet($max_num, $set_size1);
		$set2 = $this->DefineSet($max_num, $set_size2);

		list($operation, $result) = $this->SetOperation($set1, $set2, $level);

		$question = 'Tekintsük a következő két halmazt: $G=\{'.implode(";", $set1).'\}$ és $H=\{'.implode(";", $set2).'\}$. Elemei felsorolásával adja meg a $'.$operation.'$ halmazt! <i>(Például: 2;3;4)</i>';
		$correct = $result;
		$solution = '$'.implode("$$;$$", $result).'$';

		$hints = $this->Hints($set1, $set2, $result, $operation);
		// print_r($solution);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'type'		=> 'list2'
		);
	}

	function DefineSet($max_num, $set_size) {

		$num_list = range(1, $max_num);

		shuffle($num_list);

		$set = array_slice($num_list, 0, $set_size);

		return $set;
	}

	function SetOperation($set1, $set2, $level) {

		$result = [];

		$operations = ['G\setminus H', 'H\setminus G', 'G\cap H'];
		if ($level >= 6) {
			array_push($operations, 'G\cup H');
		}

		while (count($result) == 0) {

			shuffle($operations);

			$operation = $operations[0];

			switch ($operation) {
				case 'G\setminus H': // G\H
					$result = array_diff($set1, $set2);
					break;
				case 'H\setminus G': // H\G
					$result = array_diff($set2, $set1);
					break;
				case 'G\cup H': // union(H,G)
					$result = array_unique(array_merge($set2, $set1));
					break;
				case 'G\cap H': // intersect(H,G)
					$result = array_intersect($set2, $set1);
					break;
			}
		}

		$result = array_values($result);

		return array($operation, $result);
	}

	function Hints($set1, $set2, $result, $operation) {

		$page[] = 'Ábrázoljuk a számokat <b>Venn-diagramm</b>ban!';
		$hints[] = $page;
		$page 	= [];

		$left 	= array_diff($set1, $set2);
		$center = array_intersect($set1, $set2);
		$right 	= array_diff($set2, $set1);

		if (count($left) > 0) {
			$page[0] = 'Azokat az osztókat, amik benne vannak a $G$ halmazban, de nincsenek benne a $H$-ban, a <b>bal</b> oldalra írjuk:'
				.$this->VennDiagram($left);
			$hints[] = $page;
		}

		if (count($right) > 0) {
			$page[0] = 'Azokat az osztókat, amik benne vannak a $H$ halmazban, de nincsenek benne a $H$-ban, a <b>jobb</b> oldalra írjuk:'
				.$this->VennDiagram($left, [], $right);
			$hints[] = $page;
		}

		$page 	= [];

		if (count($center) > 0) {
			$page[0] = 'Azokat az osztókat, amik a $G$ és $H$ halmazban is benne vannak, <b>középre</b> írjuk:'
				.$this->VennDiagram($left, $center, $right);
			$hints[] = $page;
		}

		switch ($operation) {
			case 'G\setminus H': // G\H
				$text = 'Az $G\setminus H$ azokat az osztókat jelöli, amik a $G$ halmazban benne vannak, de a $H$ halmazban nem, vagyis a <b>bal</b> oldali részt:';
				break;
			case 'H\setminus G': // H\G
				$text = 'Az $H\setminus G$ azokat az osztókat jelöli, amik a $H$ halmazban benne vannak, de a $G$ halmazban nem, vagyis a <b>jobb</b> oldali részt:';
				break;
			case 'G\cup H': // union(H,G)
				$text = 'Az $G\cup H$ azokat az osztókat jelöli, amik a $G$ vagy a $H$ halmazban vannak, vagyis az <b>összes</b> osztót:';
				break;
			case 'G\cap H': // intersect(H,G)
				$text = 'Az $G\cap H$ azokat az osztókat jelöli, amik a $G$ és $H$ halmazban is benne vannak, vagyis a <b>középső részt</b>:';
				break;
		}

		$page[0] = $text.$this->VennDiagram($left, $center, $right, $operation);
		$hints[] = $page;

		$page[0] = 'Tehát a $'.$operation.'$ halmaz eleme'.(count($result)>1 ? 'i' : '').' <span class="label label-success">$'.implode(";", $result).'$</span>.';
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
					<text font-size="15" fill="black" x="80" y="50">$G$</text>
					<text font-size="15" fill="black" x="300" y="50">$H$</text>';

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
			case 'G\setminus H': // G\H
				$svg .= '<path d = "M 195 73 A 100 100 1 0 0 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 0 1 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case 'H\setminus G': // H\G
				$svg .= '<path d = "M 195 73 A 100 100 1 0 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 0 1 1 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case 'G\cup H': // union(H,G)
				$svg .= '<path d = "M 195 73 A 100 100 0 1 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 1 1 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
			case 'G\cap H': // intersect(H,G)
				$svg .= '<path d = "M 195 73 A 100 100 0 0 1 195 226" stroke="blue" stroke-width="4" fill="none" />
					<path d = "M 195 73 A 100 100 1 0 0 195 226" stroke="blue" stroke-width="4" fill="none" />';
				break;
		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>