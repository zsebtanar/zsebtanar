<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ismerosok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	// Define degree of unknown point of graph
	function Generate($level) {

		if ($level <= 1) {
			$size = rand(4,5);
		} elseif ($level <= 2) {
			$size = rand(5,6);
		} else {
			$size = rand(7,8);
		}

		// // Original exercise
		// $size = 6;

		list($graph, $degrees) = $this->Degrees($size);

		$degrees_known = $degrees;
		unset($degrees_known[$size-1]);
		rsort($degrees_known);

		$question = 'Egy '.NumText($size).'fős társaságban mindenkit megkérdeztek, hány ismerőse van a többiek között (az ismeretségek kölcsönösek). Az első '.NumText($size-1).' megkérdezett személy válasza: $'.implode(',', $degrees_known).'$. Hány ismerőse van '.The($size).' '.OrderText($size).' személynek a társaságban?';

		$hints = $this->Hints($graph, $degrees);

		$correct = $degrees[$size-1];
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'youtube'	=> 'VOifB9KfSoA'
		);
	}

	function Degrees($size) {

		$degrees = array_fill(0, $size, 0);
		$nodes_left = range($size-1,0);

		// // Original exercise
		// $options = [1,1,2,1,2,1];

		while (count($nodes_left) > 0) {

			$node1 = array_pop($nodes_left);
			// $type = array_pop($options);

			// if ($type == 1 || count($nodes_left) == $size-1) {
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
		$angle0 = ($size == 4 ? 45 : 90);

		// Edges
		for ($node1=0; $node1 < $size; $node1++) { 

			if ($node1 < $step) {

				$color = ($node1 == $step-1 ? '#B155CF' : 'black');

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
			
			if ($node < $step-1) {
				$color = '#A1D490';
			} elseif ($node == $step-1) {
				$color = '#B155CF';
			} else {
				$color = 'white';
			}

			list($x, $y) = polarToCartesian($centerX, $centerY, $radius, $angle);

			$svg .= DrawCircle($x, $y, 10, 'black', $width=2, $color);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Hints($graph, $degrees) {

		$size = count($graph);

		$hints[][] = 'Rajzoljunk annyi kört, ahány fős a társaság! Ha két ember ismeri egymást, akkor a nekik megfelelő két kört össze fogjuk kötni. Ha valakinek már minden ismerősét bejelöltük, akkor vele "végeztünk", úgyhogy zöldre színezzük a neki megfelelő kört:'.$this->Graph($graph, 0);

		for ($node=0; $node < $size-1 ; $node++) {

			if (isset($graph[$node])) {

				$prev_degree = 0;
				foreach ($graph as $prev_node => $prev_edges) {
					if (in_array($node, $prev_edges)) {
						$prev_degree++;
					}
					if ($prev_node == $node) {
						break;
					}
				}
				$degree_left = $degrees[$node]-$prev_degree;

				$hints[][] = 'Most vizsgáljuk meg azt a személyt, akinek $'.$degrees[$node].'$ ismerőse van. Mivel őt '.($prev_degree==0 ? 'még senkivel nem kötöttük össze' : 'már $'.$prev_degree.'$ személlyel összekötöttük').($degree_left>0 ? ', és összesen $'.$degree_left.'$ olyan személy maradt, akivel még nem végeztünk, ez azt jelenti, hogy minden lehetséges emberrel össze kell kötnünk őt:' : ', ez azt jelenti, hogy az összes ismerősét bejelöltük:').$this->Graph($graph, $node+1);

			} else {

				$hints[][] = 'Most vizsgáljuk meg azt a személyt, akinek $'.$degrees[$node].'$ ismerőse van.Mivel őt már $'.$degrees[$node].'$ emberrel összekötöttük, ez azt jelenti, hogy az összes ismerősét bejelöltük:'.$this->Graph($graph, $node+1);
			}
		}

		$hints[][] = 'Az ábráról leolvasható, hogy '.The($size).' '.OrderText($size).' személynek <span class="label label-success">$'.$degrees[count($degrees)-1].'$</span> ismerőse van.'.$this->Graph($graph, $size);

		return $hints;
	}
}

?>