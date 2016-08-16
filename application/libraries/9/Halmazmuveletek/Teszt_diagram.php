<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teszt_diagram {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('text');
		$CI->load->helper('draw');
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	function Generate($level) {

		$names_all = ['András', 'Béla', 'Csilla', 'János', 'Gergő', 'Nóra'];
		shuffle($names_all);
		$names = array_slice($names_all, 0, 3);

		$abc = rand(0,3);
		$ab = rand(0,3);
		$bc = rand(0,3);
		$ca = rand(0,3);
		$single = rand(0,3);

		// // Original exercise
		// $names = ['Éva', 'János', 'Nóra'];
		// $abc = 1;
		// $ab = 2;
		// $bc = 1;
		// $ca = 0;
		// $single = 2;

		$question = $names[0].', '.$names[1].' és '.$names[2].' tesztet írnak. A teszt értékelésekor minden helyes válaszra $1$ pont, helytelen válaszra pedig $0$ pont jár. '.
			($abc==0 ? 'Egyetlen kérdése sem volt,' : NumText($abc,TRUE).' olyan kérdés volt,').' amelyre mindhárman jól válaszoltak. '.
			($abc+$ab==0 ? 'Egy kérdés sem volt,' : NumText($abc+$ab,TRUE).' olyan kérdés volt,').' amit '.$names[0].' és '.$names[1].' is jól válaszolt meg, '.
			($abc+$bc==0 ? 'egy kérdés sem volt,' : NumText($abc+$bc).' olyan kérdés volt,').' amit '.$names[1].' és '.$names[2].' is, és '.
			($abc+$ca==0 ? 'egy sem,' : NumText($abc+$ca).' olyan,').' amire '.$names[2].' és '.$names[0].' is jó választ adott. '.
			($single==2 ? 'Két' : NumText($single,TRUE)).' olyan kérdés volt, amelyet csak egyvalaki oldott meg helyesen hármuk közül. '.
			'Hány pontot szereztek ők hárman összesen ezen a teszten?';

		$correct = 3*$abc + 2*($ab+$bc+$ca) + $single;
		$hints = $this->Hints($names, $abc, $ab, $bc, $ca, $single, $correct);
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($names, $abc, $ab, $bc, $ca, $single, $correct) {

		$hints[][] = 'Írjuk egy Venn-diagram megfelelő részeibe a legalább két diák által jól megoldott feladatok számát.'.$this->VennDiagram($names, $abc, $ab, $bc, $ca);
		$hints[][] = '$'.$abc.'$ olyan kérdés volt, amire mindhárman jól válaszoltak, ezt középre írjuk:'.$this->VennDiagram($names, $abc, $ab, $bc, $ca, 1);
		$hints[][] = '$'.strval($abc+$ab).'$ olyan kérdés volt, amire '.$names[0].' és '.$names[1].' is jól válaszolt, ezért hozzájuk $'.$ab.'$-'.Dativ($ab).' írunk, mert így lesz ennyi a két szám összege:'.$this->VennDiagram($names, $abc, $ab, $bc, $ca, 2);
		$hints[][] = '$'.strval($abc+$bc).'$ olyan kérdés volt, amire '.$names[1].' és '.$names[2].' is jól válaszolt, ezért hozzájuk $'.$bc.'$-'.Dativ($bc).' írunk, mert így lesz ennyi a két szám összege:'.$this->VennDiagram($names, $abc, $ab, $bc, $ca, 3);
		$hints[][] = '$'.strval($abc+$ca).'$ olyan kérdés volt, amire '.$names[2].' és '.$names[0].' is jól válaszolt, ezért hozzájuk $'.$ca.'$-'.Dativ($ca).' írunk, mert így lesz ennyi a két szám összege:'.$this->VennDiagram($names, $abc, $ab, $bc, $ca, 4);
		$hints[][] = 'Azért a $'.$single.'$ feladatért, amit csak egy diák oldott meg helyesen, $\color{green}{'.$single.'}$ pont jár. Így a három tanuló összesen $\color{red}{3\cdot'.$abc.'}\color{black}{+}\color{blue}{2\cdot('.$ab.'+'.$bc.'+'.$ca.')}\color{black}{+}\color{green}{'.$single.'}\color{black}{=}$<span class="label label-success">$'.$correct.'$</span> pontot szerzett.'.$this->VennDiagram($names, $abc, $ab, $bc, $ca, 5);

		return $hints;
	}

	function VennDiagram($names, $abc, $ab, $bc, $ca, $progress=0) {

		$height = 300;
		$width 	= 400;
		$radius = 80;

		$shiftx = 55;
		$shifty = 45;

		$names = convert_accented_characters($names);

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';

		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		// Circles
		$svg .= DrawCircle($width/2-$shiftx, $height/2-$shifty, $radius);
		$svg .= DrawCircle($width/2+$shiftx, $height/2-$shifty, $radius);
		$svg .= DrawCircle($width/2, $height/2+$shifty, $radius);

		// Labels
		$letters = array_map('str_split', $names);
		$svg .= DrawText($width/2-$shiftx*2.3, $height/2-$shifty*2.3, '$'.$letters[0][0].'$', 13);
		$svg .= DrawText($width/2+$shiftx*2.3, $height/2-$shifty*2.3, '$'.$letters[1][0].'$', 13);
		$svg .= DrawText($width/2, $height/2+$shifty*3.2, '$'.$letters[2][0].'$', 13);

		$svg .= ($progress>=1 ? DrawText($width/2, $height/2-5, '$\color{red}{'.$abc.'}$', 13) : '');
		$svg .= ($progress>=2 ? DrawText($width/2, $height/2-$shifty*1.2, '$\color{blue}{'.$ab.'}$', 13) : '');
		$svg .= ($progress>=3 ? DrawText($width/2+$shiftx*0.7, $height/2+15, '$\color{blue}{'.$bc.'}$', 13) : '');
		$svg .= ($progress>=4 ? DrawText($width/2-$shiftx*0.7, $height/2+15, '$\color{blue}{'.$ca.'}$', 13) : '');

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>