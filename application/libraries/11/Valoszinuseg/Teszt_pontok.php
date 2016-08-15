<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teszt_pontok {

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

		$q_no = rand(5,7);
		$opts_no = rand(2,4);

		// // Original exercise
		// $q_no = 6;
		// $opts_no = 3;

		$opts_text = range(chr(65),chr(65+$opts_no-1));
		$prob = 1-pow(($opts_no-1)/$opts_no, $q_no);

		$question = 'Egy '.NumText($q_no).'kérdéses tesztben minden kérdésnél a megadott '.NumText($opts_no).' lehetőség ('.StringArray($opts_text,'és').') közül kellett kiválasztani a helyes választ. Az egyik diák nem készült fel a tesztre, válaszait tippelve, véletlenszerűen adja meg. Mekkora valószínűséggel lesz legalább egy jó válasza a tesztben? <i>(Válaszát két tizedesjegyre kerekítve adja meg!)</i>';

		$page[] = 'Annak a valószínűsége, hogy egy válasz hibás: $$\frac{'.strval($opts_no-1).'}{'.$opts_no.'}$$';
		$page[] = 'Annak a valószínűsége, hogy mind '.The($q_no).' '.NumText($q_no).' válasz hibás: $$\left(\frac{'.strval($opts_no-1).'}{'.$opts_no.'}\right)^{'.$q_no.'}$$';
		$page[] = 'Annak a valószínűsége, hogy legalább az egyik válasz jó: $$1-\left(\frac{'.strval($opts_no-1).'}{'.$opts_no.'}\right)^{'.$q_no.'}\approx'.round2($prob, 4).'$$';
		$page[] = 'Ennek a két tizedesjegyre kerekített értéke <span class="label label-success">$'.round2($prob).'$</span>.';
		$hints[] = $page;

		$correct = round1($prob,2);
		$solution = '$'.round2($prob,2).'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}
}

?>