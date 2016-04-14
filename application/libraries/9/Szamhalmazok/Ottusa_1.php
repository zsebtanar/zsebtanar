<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ottusa_1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	// Generate random number between 1 and 20
	function Generate($level) {

		$min = rand(5,9);
		$sec = (rand(1,3) == 1 ? rand(10,99) : rand(0,2)*33);
		$sec = ($min == 5 && $sec < 66 ? 66 : $sec);
		$sec = ($min == 9 && $sec >= 33 ? rand(0,32) : $sec);

		$question = 'Az öttusa úszás számában $200$ métert kell úszni. Az elért időeredményekért járó pontszámot mutatja a grafikon.';
		$question .= $this->Graph();

		$question .= 'Hány pontot kapott Robi, akinek az időeredménye $2$ perc $'.$min.','.($sec == 0 ? '00' : $sec).'$ másodperc?';
		$point = $this->Point($min, $sec);
		$correct = $point;
		$solution = '$'.$correct.'$';
		$type = 'int';

		$hints[][] = 'Keressük meg a grafikon $x$ tengelyén a $2$ perc $'.$min.','.($sec == 0 ? '00' : $sec).'$ másodpercet!'.$this->Graph($min, $sec);
		$hints[][] = 'Keressük meg a neki megfelelő pontszámot az $y$ tengelyen!'.$this->Graph($min, $sec, $point);
		$hints[][] = 'Tehát Robi <span class="label label-success">'.$point.'</span> pontot kapott.';
			
		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type,
			'hints'		=> $hints
		);
	}

	function Graph($min=NULL, $sec=NULL, $point=NULL) {

		$width 	= 400;
		$height = 350;

		$paddingX = 30;
		$paddingY = 80;

		$lines = 11;
		$unitX = ($width-30-$paddingX)/($lines+1);
		$unitY = ($height-30-$paddingY)/$lines;

		$secs = ['33', '00', '66'];
		$show = [323, 320, 315, 313];

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		// X axis
		$svg .= DrawLine(0, $height-$paddingY, $width, $height-$paddingY);
		$svg .= DrawLine($width, $height-$paddingY, $width-5, $height-$paddingY+5);
		$svg .= DrawLine($width, $height-$paddingY, $width-5, $height-$paddingY-5);
		$svg .= DrawText($width-20, $height-$paddingY+15,'idő');

		// Y axis
		$svg .= DrawLine($paddingX, 0, $paddingX, $height-60);
		$svg .= DrawLine($paddingX, 0, $paddingX-5, 5);
		$svg .= DrawLine($paddingX, 0, $paddingX+5, 5);
		$svg .= DrawText($paddingX+10, 15,'pontszám');

		for ($i=0; $i < $lines+1; $i++) { 

			$x = $paddingX + ($lines-$i+1)*$unitX;
			$y = $height-$paddingY - (min($lines, $i+1))*$unitY;
			$svg .= DrawPath($paddingX-5, $y, $x, $y, 'black', 0.5, 'none', 5, 5);
			$svg .= DrawPath($x, $height-$paddingY+5, $x, $y, 'black', 0.5, 'none', 5, 5);
			if ($i < $lines) {
				$svg .= DrawLine($x-$unitX, $y, $x, $y, 'black', 2);
				$svg .= DrawCircle($x-$unitX, $y, 3, 'black', 1, 'black');
				$svg .= DrawCircle($x, $y, 3, 'black', 1, 'white');
			}
			if (in_array(313+$i, $show)) {
				$svg .= DrawText($paddingX-25, $y+4, 313+$i);
			} elseif ($point !== NULL && $i < $lines) {
				$svg .= DrawText($paddingX-25, $y+4, 313+$i, 10, 'blue');
			}
			$text = '2 perc '.strval(9-floor(($i+1)/3)).','.$secs[$i%3].' mp';
			$svg .= DrawText($x+3, $height-$paddingY+77, $text, 10, 'black', -90);

		}

		// Draw time
		if ($min !== NULL && $sec !== NULL) {

			$time = $min*100 + $sec;
			$x1 = $paddingX + $unitX*($lines+1)*(400 - (933-$time))/400;
			$y1 = $height - $paddingY;

			$svg .= DrawCircle($x1, $y1, 3, 'blue', 1, 'blue');
		}

		if ($point !== NULL) {

			$x2 = $paddingX;
			$y2 = $height - $paddingY - ($point - 312)*$unitY;

			$svg .= DrawPath($x1, $y1, $x1, $y2, 'blue', 2, 'none', 5, 5);
			$svg .= DrawPath($x1, $y2, $x2, $y2, 'blue', 2, 'none', 5, 5);

			$svg .= DrawCircle($x2, $y2, 3, 'blue', 1, 'blue');
		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Point($min, $sec) {

		if ($sec < 33) {
			$point = 313 + (9-$min)*3;
		} elseif ($sec < 66) {
			$point = 315 + (8-$min)*3;
		} else {
			$point = 314 + (8-$min)*3;
		}

		return $point;
	}
}

?>