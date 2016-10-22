<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teszt_kordiagram {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('draw');
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$q_no = rand(4,8);
		$opts_no = rand(2,5);
		$ppl = rand(7,13);

		$opts_text = range(chr(65),chr(65+$opts_no-1));

		$max_points = $ppl * $q_no;
		$points = rand(1,floor($max_points/5))*5;
		$rand = rand(1,2);

		// // Original exercise
		// $q_no = 6;
		// $opts_no = 3;
		// $ppl = 10;
		// $points = 35;
		// $rand = 1;

		if ($rand == 1) {
			$type = 'jó';
			$rate = $points/$max_points;
			$correct = round($points/$max_points*360);
		} else {
			$type = 'rossz';
			$rate = 1-$points/$max_points;
			$correct = round((1-$points/$max_points)*360);
		}

		$question = 'Egy '.NumText($q_no).'kérdéses tesztben minden kérdésnél a megadott '.NumText($opts_no).' lehetőség ('.StringArray($opts_text,'és').') közül kellett kiválasztani a helyes választ. A tesztet '.NumText($ppl).' diák írta meg. A teszt értékelésekor minden helyes válaszra $1$ pont, helytelen válaszra pedig $0$ pont jár. Tudjuk, hogy a '.NumText($ppl).' diák összesen $'.$points.'$ pontot szerzett. Ha az összes jó és az összes rossz válasz számából kördiagramot készítünk, mekkora körcikk szemlélteti a '.($type).' válaszok számát? <i>(Válaszát egész fokokra kerekítve adja meg!)</i>'.$this->PieChart();

		$solution = '$'.$correct.'°$';

		$page[] = 'Ha minden diák minden kérdésre jól válaszolt volna, összesen $'.$ppl.'\cdot'.$q_no.'='.$max_points.'$ pontot szerzett volna.';
		$page[] = 'A '.NumText($ppl).' diák összesen $'.$points.'$ pontot szerzett, ezért a jó válaszok száma $'.$points.'$, a rossz válaszok száma pedig $'.strval($max_points-$points).'$.';
		$page[] = 'A '.$type.' válaszok aránya: $$\frac{'.($type=='jó' ? $points : $max_points-$points).'}{'.$max_points.'}'.(round1($rate)==$rate ? '=' : '\approx').round2($rate).'$$';
		$page[] = 'Ezért a kördiagramon a '.$type.' válaszokhoz tartozó körcikk nagysága egész fokokra kerekítve $\frac{'.($type=='jó' ? $points : $max_points-$points).'}{'.$max_points.'}\cdot360°'.($rate*360==$correct ? '=' : '\approx').'$<span class="label label-success">$'.$correct.'$</span>$°$.'.$this->PieChart($correct);
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'labels'	=> ['right' => '$°$'],
			'youtube'	=> 'tMzy4WL53O4'
		);
	}

	function PieChart($angle_deg=NULL) {

		$width 	= 400;
		$height = 260;

		$cx = $width/2;
		$cy = $height/2;
		$r 	= 100;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';

		// Show angle
		if ($angle_deg) {

			if ($angle_deg == 360) {
				$svg .= DrawCircle($cx, $cy, $r, $stroke='black', $strokewidth=0, $fill='#CCC');
			} else {
				$svg .= DrawPieChart($cx, $cy, $r, 0, $angle_deg, $fill='#CCC');
			}

			list($x1, $y1) = polarToCartesian($cx, $cy, $r, 0);
			list($x2, $y2) = polarToCartesian($cx, $cy, $r, $angle_deg);
			$svg .= DrawLine($cx, $cy, $x1, $y1);
			$svg .= DrawLine($cx, $cy, $x2, $y2);

		}

		// Circle
		$svg .= DrawCircle($cx, $cy, $r);

		// Ticks for every 10°
		for ($i=0; $i < 36; $i++) { 
			$angle = $i*10;
			list($x1, $y1) = polarToCartesian($cx, $cy, $r+5, $angle);
			list($x2, $y2) = polarToCartesian($cx, $cy, $r-5, $angle);
			$svg .= DrawLine($x1, $y1, $x2, $y2);
		}

		// Show hint degrees
		$svg .= DrawText($cx+$r+10, $cy+7, '0°', 15);
		$svg .= DrawText($cx+$r+5, $cy-13, '10°', 15);
		$svg .= DrawText($cx-8, $cy-$r-7, '90°', 15);
		$svg .= DrawText($cx-$r-40, $cy+7, '180°', 15);
		$svg .= DrawText($cx-15, $cy+$r+22, '270°', 15);

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>