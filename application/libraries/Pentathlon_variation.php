<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pentathlon_variation {

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

		$A = rand(2,3);
		$B = rand(2,5);
		$C = 12-$A-$B;

		$question = 'Az öttusa lovaglás számában egy akadálypályán tizenkét különböző akadályt kell a versenyzőnek átugratnia. Egy akadály a nehézsége alapján három csoportba sorolható: $A$, $B$ vagy $C$ típusú. Ádám a verseny előtti bemelegítéskor először '.The($A).' '.NumText($A).' darab $A$, majd '.The($B).' '.NumText($B).' darab $B$, végül '.The($C).' '.NumText($C).' darab $C$ típusú akadályon ugrat át, mindegyiken pontosan egyszer. Bemelegítéskor az egyes akadálytípusokon belül a sorrend szabadon megválasztható. Számítsa ki, hogy a bemelegítés során hányféle sorrendben ugrathatja át Ádám a tizenkét akadályt!';
		$correct = fact($A)*fact($B)*fact($C);
		$solution = '$'.$correct.'$';
		$type = 'int';

		$page[] = 'Az $A$ típusú akadályok lehetséges sorrendjeinek a száma $'.$A.'!='.fact($A).'$.';
		$page[] = 'A $B$ típusú akadályok lehetséges sorrendjeinek a száma $'.$B.'!='.fact($B).'$.';
		$page[] = 'A $C$ típusú akadályok lehetséges sorrendjeinek a száma $'.$C.'!='.fact($C).'$.';
		$page[] = 'A $12$ akadály lehetséges sorrendjeinek a száma ezek szorzata.';
		$page[] = 'Tehát Ádám összesen $'.fact($A).'\cdot'.fact($B).'\cdot'.fact($C).'=$<span class="label label-success">$'.$correct.'$</span> különböző sorrendben ugrathatja át a tizenkét akadályt.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type,
			'hints'		=> $hints
		);
	}
}

?>