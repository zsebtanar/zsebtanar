<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teszt_grafikon {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('draw');
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$q_no = rand(5,7);
		$opts_no = rand(2,4);
		$ppl = rand(7,14);

		$freq = $this->Frequencies($q_no, $opts_no, $ppl);

		$freq_max = array_map('max', $freq);
		$points = array_sum($freq_max) + rand(0,1);

		// // Original exercise
		// $q_no = 6;
		// $opts_no = 3;
		// $ppl = 10;
		// $freq = [
		// 	0 => [1,4,5],
		// 	1 => [6,3,1],
		// 	2 => [4,0,6],
		// 	3 => [7,2,1],
		// 	4 => [6,3,1],
		// 	5 => [1,6,3]
		// ];
		// $freq_max = array_map('max', $freq);
		// $points = 35;

		$opts_text = range(chr(65),chr(65+$opts_no-1));

		$question = 'Egy '.NumText($q_no).'kérdéses tesztben minden kérdésnél a megadott '.NumText($opts_no).' lehetőség ('.StringArray($opts_text,'és').') közül kellett kiválasztani a helyes választ. A tesztet '.NumText($ppl).' diák írta meg. Az alábbi diagram az egyes feladatokra adott válaszok eloszlását mutatja. A teszt értékelésekor minden helyes válaszra $1$ pont, helytelen válaszra pedig $0$ pont jár. Tudjuk, hogy a '.NumText($ppl).' diák összesen $'.$points.'$ pontot szerzett. Igaz-e, hogy minden kérdésre az a jó válasz, amit a legtöbben jelöltek be?'.$this->Diagram($freq, $q_no, $opts_no);

		$options = ['Igaz', 'Hamis'];
		$correct = ($freq_max == $points ? 0 : 1);
		$solution = $options[$correct];

		for ($i=0; $i < $q_no; $i++) {

			// Define most frequent options
			$max = [];
			for ($j=0; $j < $opts_no; $j++) { 
				if ($freq[$i][$j] == $freq_max[$i]) {
					$max[] = $opts_text[$j];
				}
			}

			$max_text = (count($max) == 1 ? $max[0] : StringArray($max,'és'));
			$page[] = The($i+1,TRUE).' '.OrderText($i+1).' kérdésre legtöbben '.($max[0] == 'A' ? 'az' : 'a').' '.$max_text.' választ jelölték meg, összesen $'.$freq_max[$i].'$-'.On2($freq_max[$i]).'.';
		}

		if (array_sum($freq_max) == $points) {
			$page[] = 'Ezeknek a számoknak az összege $'.implode('+', $freq_max).'='.array_sum($freq_max).'$, ami pont ugyanannyi, mint amennyi a feladatban szerepel, ezért az állítás <span class="label label-success">igaz</span>.';
		} else {
			$page[] = 'Ha igaz lenne az állítás, akkor ezeknek a számoknak az összege $'.$points.'$ lenne. Viszont az összeg $'.implode('+', $freq_max).'='.array_sum($freq_max).'$, ezért az állítás <span class="label label-danger">hamis</span>.';
		}
		
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'options'	=> $options,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Frequencies($q_no, $opts_no, $ppl) {

		for ($j=0; $j < $q_no; $j++) { 
			$freq[$j] = array_fill(0, $opts_no, 0);
		}

		for ($i=0; $i < $ppl; $i++) {
			for ($j=0; $j < $q_no; $j++) { 
				$answer = rand(0, $opts_no-1);
				$freq[$j][$answer]++;
			}
		}

		return $freq;
	}

	function Diagram($freq, $q_no, $opts_no) {

		$top = max(array_map('max', $freq)) + 1;

		$height = 300;
		$width 	= 500;

		$padX = 30;
		$padY = 50;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';

		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		// Y axis
		$unitY = ($height-$padY-5)/$top;
		for ($i=0; $i <= $top; $i++) { 
			$svg .= DrawLine($padX, $i*$unitY+5, $width, $i*$unitY+5, '#333');
			$svg .= DrawText($padX-10, $i*$unitY+10, $top-$i, 13);
		}

		// Y label
		$svg .= DrawText($padX-17, $height*.65, 'Válasz gyakorisága', 13, 'black', -90);

		// X axis
		$svg .= DrawLine($padX+3, 5, $padX+3, $top*$unitY+5, '#333');

		$width1 = 2 * ($q_no-1); // width between coloumn groups: 2 units
		$width2 = $opts_no * $q_no; // width of coloumns: 1 unit
		$width3 = 2; // padding on left & right: 1-1 unit
		$unitX = ($width-$padX)/($width1 + $width2 + $width3);
		foreach ($freq as $ind1 => $question) {

			// Label for question
			$centerX = $padX + $unitX // space on the left
				+ $ind1 * ($opts_no+2) * $unitX // space of previous questions
				+ $opts_no * $unitX/2;  // middle of current question
			$svg .= DrawText($centerX-10, $height-35, strval($ind1+1).'.', 13);

			// Coloumns
			foreach ($question as $ind2 => $option) {

				$rgb = round(225*(1-1/($opts_no-1)*$ind2));
				$col_height = $unitY * $freq[$ind1][$ind2];

				$col_X = $padX + $unitX // space on the left
					+ $ind1 * ($opts_no+2) * $unitX // space of previous questions
					+ $ind2 * $unitX;  // space for previous columns
				$svg .= DrawRectangle($col_X, $height-$padY-$col_height, $unitX, $col_height, 'rgb('.$rgb.','.$rgb.','.$rgb.')', $stroke='black', $strokewidth=1);
			}
		}

		// X label
		$svg .= DrawText($width*.4, $height-22, 'Feladat sorszáma', 13);

		// legend
		$opts_text = ['A', 'B', 'C', 'D', 'E', 'F'];
		$opts_colors = ['rgb(255,255,255)', '#FFF', '#999', '#444', '#555', '#666'];
		$unitLegend = $width/(4 + $opts_no-1);
		for ($i=0; $i < $opts_no; $i++) { 
			$centerX = 2*$unitLegend // space on left side
				+ $i*$unitLegend; // space of previous options
			$rgb = round(255*(1-1/($opts_no-1)*$i));
			$svg .= DrawRectangle($centerX, $height-13, 10, 10, 'rgb('.$rgb.','.$rgb.','.$rgb.')', $stroke='black', $strokewidth=1);
			$svg .= DrawText($centerX+15, $height-4, $opts_text[$i], 13);
		}	

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>