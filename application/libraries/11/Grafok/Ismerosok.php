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

		if ($level <= 3) {
			$num = rand(4,5);
		} elseif ($level <= 6) {
			$num = rand(5,6);
		} else {
			$num = rand(7,8);
		}

		$degrees 	= $this->Degrees($level, $num);
		$question 	= 'Egy '.NumText($num).'fős társaságban mindenkit megkérdeztek, hány ismerőse van a többiek között (az ismeretségek kölcsönösek). Az első '.NumText($num-1).' megkérdezett személy válasza: $'.implode(',', $degrees).'$. Hány ismerőse van '.The($num).' '.OrderText($num).' személynek a társaságban?';
		$hints 		= $this->Hints($degrees);

		$correct = $degrees[$num-1];
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Degrees($level, $num) {

		if ($level <= 3) {
			$group1 = 0;
			$group2 = rand(1,2);
			$group4 = ($num-$group1 >= 2 ? rand(0,1)*2 : 0);
			$group3 = $num-$group1-$group2-$group4;
		} elseif ($level <= 6) {
			$group1 = rand(1,2);
			$group2 = rand(1,2);
			$group4 = ($num-$group1 >= 2 ? rand(0,1)*2 : 0);
			$group3 = $num-$group1-$group2-$group4;
		} else {
			$group1 = rand(1,2);
			$group2 = rand(2,3);
			$group4 = ($num-$group1 >= 2 ? rand(0,1)*2 : 0);
			$group3 = $num-$group1-$group2-$group4;
		}

		// group1: they haven't played with anyone yet
		for ($i=0; $i < $group1; $i++) { 
			$degrees[] = 0;
		}

		// group2: they have played with everyone except group1
		for ($i=0; $i < $group2; $i++) { 
			$degrees[] = $num - $group1 - 1;
		}

		// group3: they have only played with group2
		for ($i=0; $i < $group3; $i++) { 
			$degrees[] = $group2;
		}

		// group4: they have played with group2 and group3 (0 or 2 ppl)
		for ($i=0; $i < $group4; $i++) { 
			$degrees[] = $group2 + 1;
		}

		return $degrees;
	}

	function Graph($degrees=[], $step=NULL) {

		$width 	= 400;
		$height = 300;

		$centerX = $width/2;
		$centerY = $height/2;
		$radius = 100;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';
		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		$points = count($degrees);
		$angle0 = ($points == 4 ? 45 : 90);
		for ($i=0; $i < $points; $i++) {
			$angle = $angle0 + $i*360/$points;
			if ($step && $i < $step) {
				$color = ($i == $step-1 ? '#B155CF' : 'black');
				$svg .= $this->DrawEdges($centerX, $centerY, $radius, $angle, $degrees, $i, $color);
			}
		}

		for ($i=0; $i < $points; $i++) {
			$angle = $angle0 + $i*360/$points;
			if ($step && $i < $step) {
				$color = ($i == $step-1 ? '#B155CF' : '#A1D490');
			} else {
				$color = 'white';
			}
			$svg .= $this->DrawNode($centerX, $centerY, $radius, $angle, $color);
		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function DrawNode($centerX, $centerY, $radius, $angle, $color) {

		list($x, $y) = polarToCartesian($centerX, $centerY, $radius, $angle);

		$svg = '<circle cx="'.$x.'" cy="'.$y.'" r="10" stroke="black" stroke-width="2" fill="'.$color.'" />';

		return $svg;
	}

	function DrawEdges($centerX, $centerY, $radius, $angle0, $degrees, $i, $color) {

		$points = count($degrees);
		$svg = '';
		$prev_degrees = 0;
		$zeros = 0;

		list($x1, $y1) = polarToCartesian($centerX, $centerY, $radius, $angle0);


		// calculate nodes with zero edges
		for ($j=0; $j < $i; $j++) { 
			$zeros += ($degrees[$j] == 0 ? 1 : 0);
		}

		// calculate previous degrees
		for ($j=0; $j < $i; $j++) { 
			$prev_degrees += ($degrees[$j] == count($degrees)-$zeros-1 ? 1 : 0);
		}

		if ($i < count($degrees)-1) {
			for ($j=1; $j <= $degrees[$i]-$prev_degrees; $j++) { 
				$angle = $angle0 + $j*360/$points;

				list($x2, $y2) = polarToCartesian($centerX, $centerY, $radius, $angle);

				$svg .= DrawLine($x1, $y1, $x2, $y2, $color, 2);
			}
		}

		return $svg;
	}

	function Hints($degrees) {

		$hints[][] = 'Rajzoljunk annyi kört, ahány fős a társaság!'
			.' Ha két ember ismeri egymást, akkor a nekik megfelelő két kört össze fogjuk kötni.'
			.' Ha valakinek már minden ismerősét bejelöltük, akkor vele "végeztünk", úgyhogy zöldre színezzük a neki megfelelő kört.'
			.$this->Graph($degrees, 0);

		for ($i=0; $i < count($degrees)-1 ; $i++) {
			if ($degrees[$i] == 0) {
				$text = The($i+1, TRUE).' '.OrderText($i+1).' ember egy embert sem ismet, úgyhogy az ő köréből nem húzunk sehova vonalat:';
			} else {


				// calculate nodes with zero edges
				$zeros = 0;
				for ($j=0; $j < $i; $j++) { 
					$zeros += ($degrees[$j] == 0 ? 1 : 0);
				}

				// calculate previous degrees
				$prev_degrees = 0;
				for ($j=0; $j < $i; $j++) { 
					$prev_degrees += ($degrees[$j] == count($degrees)-$zeros-1 ? 1 : 0);
				}
				$left_degrees = $degrees[$i] - $prev_degrees;

				if ($prev_degrees == 0) {
					$text = The($i+1, TRUE).' '.OrderText($i+1).' embernek $'.$degrees[$i].'$ ismerőse van, úgyhogy ennyi vonalat húzunk belőle a többi játékos felé '
						.'(Figyelem: csak ahhoz a körhöz húzhatunk vonalat, amivel még nem végeztünk!):';
				} elseif ($prev_degrees == $degrees[$i]) {
					$text = The($i+1, TRUE).' '.OrderText($i+1).' embernek $'.$degrees[$i].'$ ismerőse van, viszont már mindegyik be van húzva, úgyhogy készen vagyunk:';
				} else {
					$text = The($i+1, TRUE).' '.OrderText($i+1).' embernek $'.$degrees[$i].'$ ismerőse van, viszont ebből már $'.$prev_degrees.'$-'
						.Dativ($prev_degrees).' behúztunk, úgyhogy már csak $'.$left_degrees.'$-'.Dativ($left_degrees)
						.' kell (Figyelem: csak ahhoz a körhöz húzhatunk vonalat, amivel még nem végeztünk!):';
				}
			}
			$hints[][] = $text.$this->Graph($degrees, $i+1);
		}

		$hints[][] = 'Az ábra szerint '.The($i+1).' '.OrderText($i+1).' embernek $'.$degrees[count($degrees)-1].'$ ismerőse van. '
			.'Tehát a megoldás <span class="label label-success">$'.$degrees[count($degrees)-1].'$</span>.'
			.$this->Graph($degrees, count($degrees));

		return $hints;
	}
}

?>