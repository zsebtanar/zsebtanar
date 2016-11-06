<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Elekszama {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	function Generate($level) {

		if ($level <= 3) {
			$size = rand(3,4);
		} elseif ($level <= 6) {
			$size = rand(5,6);
			$size = 5;
		} else {
			$size = rand(7,8);
		}

		list($graph, $degrees) = $this->Degrees($size);

		$question 	= 'Mekkora a gráf éleinek száma?'.$this->Graph($graph, $size);
		$correct 	= array_sum($degrees)/2;
		$hints 		= $this->Hints($degrees);
		$solution 	= '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Degrees($size) {

		$degrees = array_fill(0, $size, 0);
		$nodes_left = range($size-1,0);

		while (count($nodes_left) > 0) {

			$node1 = array_pop($nodes_left);

			if (rand(1,2) == 1 || count($nodes_left) == $size-1) {

				// Node is connected with every nodes left
				if (count($nodes_left) > 0) {
					foreach ($nodes_left as $node2) {
						$graph[$node1][] = $node2;
						$degrees[$node1]++;
						$degrees[$node2]++;
					}
				} else {
					$graph[$node1][] = [];
				}

			} else {

				// Person has no more nodes
				$graph[$node1][] = [];
			}
		}

		return array($graph, $degrees);
	}

	function Graph($graph, $step=NULL) {

		$width 	= 400;
		$height = 300;

		$centerX = $width/2;
		$centerY = $height/2;
		$radius = 100;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';
		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		$size = count($graph);
		$angle0 = ($size == 4 ? 45 : 90 + rand(0,$size-1) * 360/$size);

		// Edges
		for ($node1=0; $node1 < $size; $node1++) { 

			if ($node1 < $step) {

				$color = 'black';

				foreach ($graph[$node1] as $node2) {

					if (!is_array($node2)) {

						$angle1 = $angle0 + $node1 * 360/$size;
						$angle2 = $angle0 + $node2 * 360/$size;

						list($x1, $y1) = polarToCartesian($centerX, $centerY, $radius, $angle1);
						list($x2, $y2) = polarToCartesian($centerX, $centerY, $radius, $angle2);

						$svg .= DrawLine($x1, $y1, $x2, $y2, $color, 2);
					}
				}
			}
		}

		// Nodes
		for ($node=0; $node < $size; $node++) { 

			$angle = $angle0 + $node * 360/$size;
			
			$color = 'white';

			list($x, $y) = polarToCartesian($centerX, $centerY, $radius, $angle);

			$svg .= DrawCircle($x, $y, 10, 'black', $width=2, $color);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Hints($degrees) {

		$degrees_text = implode('+', $degrees);
		$degrees_sum = array_sum($degrees);
		$correct = $degrees_sum/2;

		$page[] = 'Az eredmény kiszámításához először adjuk össze minden csúcs fokszámát:$$'.$degrees_text.'='.$degrees_sum.'$$';
		$page[] = 'Mivel minden élt kétszer számoltunk, az eredményt el kell osztani $2$-vel.';
		$page[] = 'Tehát az élek száma $\frac{'.$degrees_sum.'}{2}=$<span class="label label-success">$'.$correct.'$</span>.';
		$hints[] = $page;

		return $hints;
	}
}

?>