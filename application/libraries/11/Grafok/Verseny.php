<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verseny {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
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

		$names 		= $this->Names($num);
		$degrees 	= $this->Degrees($level, $num);

		// // Original exercise
		// $num = 7;
		// $names = ['Anita', 'Orsi', 'Gabi', 'Szilvi', 'Kati', 'Zsuzsa', 'Flóra'];
		// $degrees = [6,1,1,1,1,2,2];

		$question 	= $this->Question($names, $degrees);
		$hints 		= $this->Hints($names, $degrees);

		$correct = $degrees[$num-1];
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Names($num) {

		$names = ['András', 'Betti', 'Nóri', 'Peti', 'Misi', 'Orsi', 'Gabi', 'Zoli'];
		shuffle($names);
		array_splice($names, $num);

		return $names;
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

	function Question($names, $degrees) {

		$num_text = array(
			4 => 'négyen',
			5 => 'öten',
			6 => 'hatan',
			7 => 'heten',
			8 => 'nyolcan'
		);

		$num_text2 = array(
			4 => 'negyedik',
			5 => 'ötödik',
			6 => 'hatodik',
			7 => 'hetedik',
			8 => 'nyolcadik'
		);

		$num = count($names);

		$question = 'Az iskolai asztaliteniszbajnokságon '.$num_text[$num].' indulnak. Mindenki mindenkivel egyszer játszik. Mostanáig ';
		$append = '';

		for ($i=0; $i < $num-1; $i++) {

			if ($degrees[$i] == 0) {

				$append .= $names[$i];

				if ($i < $num-3 && $degrees[$i+2] == $degrees[$i]) {
					$append .= ', ';
				} elseif ($i < $num-2 && $degrees[$i+1] == $degrees[$i]) {
					$append .= ' és ';
				} else {
					$append .= ' még senkivel sem játszott.';
				}

			} else {
				$question .= $names[$i];
				if ($i < $num-3 && $degrees[$i+2] == $degrees[$i]) {
					$question .= ', ';
				} elseif ($i < $num-2 && $degrees[$i+1] == $degrees[$i]) {
					$question .= ' és ';
				} elseif ($degrees[$i] == $num-1) {
					$question .= ' már mind a $'.strval($num-1).'$ mérkőzését lejátszotta, ';
				} elseif ($i == $num-2) {
					if ($degrees[$i] == $degrees[$i-1]) {
						$question .= ' pedig $'.$degrees[$i].'$-$'.$degrees[$i].'$ mérkőzésen vannak túl.';
					} else {
						$question .= ' pedig $'.$degrees[$i].'$ mérkőzésen van túl.';	
					}
				} else {
					$question .= ' $'.$degrees[$i].'$, ';
				}
			}
		}

		$question .= ' '.$append.' Hány mérkőzését játszotta le mostanáig a bajnokság '.$num_text2[$num].' résztvevője, '.$names[$num-1].'?';

		return $question;
	}

	function Graph($names, $degrees=[], $step=NULL) {

		$width 	= 400;
		$height = 300;

		$centerX = $width/2;
		$centerY = $height/2;
		$radius = 100;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';
		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		$points = count($names);
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
			$node = $names[$i];
			if ($step && $i < $step) {
				$color = ($i == $step-1 ? '#B155CF' : '#A1D490');
			} else {
				$color = 'white';
			}
			$svg .= $this->DrawNode($centerX, $centerY, $radius, $angle, $node, $color);
		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function DrawNode($centerX, $centerY, $radius, $angle, $node, $color) {

		list($x, $y) = polarToCartesian($centerX, $centerY, $radius, $angle);

		$svg = '<circle cx="'.$x.'" cy="'.$y.'" r="20" stroke="black" stroke-width="2" fill="'.$color.'" />';

		$node = str_split($node);
		$svg .= '<text font-size="15" x="'.strval($x).'" y="'.strval($y+7).'" fill="black">$'.$node[0].'$</text>';

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

				$svg .= $this->DrawEdge($x1, $y1, $x2, $y2, $color);
			}
		}

		return $svg;
	}

	function Hints($names, $degrees) {

		$hints[][] = 'Rajzoljunk annyi kört, ahány játékos van, és minden körbe írjuk bele a játékosok nevét!'
			.' Ha két játékos már játszott egymással, akkor a két kört össze fogjuk kötni.'
			.' Ha pedig egy játékossal "készen vagyunk", kiszínezzük a neki megfelelő kört.'
			.$this->Graph($names, $degrees, 0);

		for ($i=0; $i < count($names)-1 ; $i++) {
			if ($degrees[$i] == 0) {
				$text = $names[$i].' eddig egy meccset sem játszott, úgyhogy az ő köréből nem húzunk sehova vonalat:';
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
					$text = $names[$i].' eddig $'.$degrees[$i].'$ meccset játszott, úgyhogy ennyi vonalat húzunk belőle a többi játékos felé '
						.'(Figyelem: csak ahhoz a körhöz húzhatunk vonalat, amivel még nem végeztünk!):';
				} elseif ($prev_degrees == $degrees[$i]) {
					$text = $names[$i].' eddig $'.$degrees[$i].'$ meccset játszott, viszont már mindegyik be van húzva, úgyhogy készen vagyunk:';
				} else {
					$text = $names[$i].' eddig $'.$degrees[$i].'$ meccset játszott, viszont ebből már $'.$prev_degrees.'$-'
						.Dativ($prev_degrees).' behúztunk, úgyhogy már csak $'.$left_degrees.'$-'.Dativ($left_degrees)
						.' kell (Figyelem: csak ahhoz a körhöz húzhatunk vonalat, amivel még nem végeztünk!):';
				}
			}
			$hints[][] = $text.$this->Graph($names, $degrees, $i+1);
		}

		$hints[][] = 'Az ábra szerint '.$names[count($names)-1].' $'.$degrees[count($names)-1].'$ mérkőzést játszott eddig. '
			.'Tehát a megoldás <span class="label label-success">$'.$degrees[count($names)-1].'$</span>.'
			.$this->Graph($names, $degrees, count($names));

		return $hints;
	}

	function DrawEdge($x1, $y1, $x2, $y2, $color) {

		$svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="2" />';

		return $svg;
	}
}

?>