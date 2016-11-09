<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szamjegyek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		if ($level <= 1) {
			$digit1 = 'X';
			$digit2 = rand(1,9);
			$digit3 = rand(1,9);
			$correct = 9;
		} elseif ($level <= 2) {
			$digit1 = rand(1,9);
			$digit2 = 'X';
			$digit3 = rand(1,9);
			$correct = 10;
		} else {
			$digit1 = rand(1,9);
			$digit2 = rand(1,9);
			$digit3 = 'X';
			$correct = 10;
		}

		$question = 'Hányféle számjegy kerülhet az $X$ helyére az alábbi háromjegyű számban?$$'.$digit1.$digit2.$digit3.'$$';
		$solution = '$'.$correct.'$';

		$hints = $this->Hints($digit1, $digit2, $digit3, $level, $correct);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function Hints($digit1, $digit2, $digit3, $level, $correct) {

		$page[]	= 'Összesen $10$ darab számjegy van: $0,1,2,3,4,5,6,7,8,9$.';

		if ($level <= 1) {
			$page[]	= 'Mivel $0$-val nem kezdünk számjegyet, ezért az első helyre csak $9$ lehetőség közül választhatunk:$$\textcolor{red}{1}'.$digit2.$digit3.',\textcolor{red}{2}'.$digit2.$digit3.',\textcolor{red}{3}'.$digit2.$digit3.',\textcolor{red}{4}'.$digit2.$digit3.',\textcolor{red}{5}'.$digit2.$digit3.',\textcolor{red}{6}'.$digit2.$digit3.',\textcolor{red}{7}'.$digit2.$digit3.',\textcolor{red}{8}'.$digit2.$digit3.',\textcolor{red}{9}'.$digit2.$digit3.'$$';
			$page[]	= 'Mivel $0$-val nem kezdünk számjegyet, ezért az első helyre csak $9$ lehetőség közül választhatunk:$$\textcolor{red}{1}'.$digit2.$digit3.',\textcolor{red}{2}'.$digit2.$digit3.',\textcolor{red}{3}'.$digit2.$digit3.',\textcolor{red}{4}'.$digit2.$digit3.',\textcolor{red}{5}'.$digit2.$digit3.',\textcolor{red}{6}'.$digit2.$digit3.',\textcolor{red}{7}'.$digit2.$digit3.',\textcolor{red}{8}'.$digit2.$digit3.',\textcolor{red}{9}'.$digit2.$digit3.'$$';

		} elseif ($level <= 2) {
			$page[]	= 'Mivel az $X$ nem az első számjegy, ezért az összes számjegy lehetséges:$$'.$digit1.'\textcolor{red}{0}'.$digit3.','.$digit1.'\textcolor{red}{1}'.$digit3.','.$digit1.'\textcolor{red}{2}'.$digit3.','.$digit1.'\textcolor{red}{3}'.$digit3.','.$digit1.'\textcolor{red}{4}'.$digit3.','.$digit1.'\textcolor{red}{5}'.$digit3.','.$digit1.'\textcolor{red}{6}'.$digit3.','.$digit1.'\textcolor{red}{7}'.$digit3.','.$digit1.'\textcolor{red}{8}'.$digit3.','.$digit1.'\textcolor{red}{9}'.$digit3.'$$';
		} else {
			$page[]	= 'Mivel az $X$ nem az első számjegy, ezért az összes számjegy lehetséges:$$'.$digit1.$digit2.'\textcolor{red}{0},'.$digit1.$digit2.'\textcolor{red}{1},'.$digit1.$digit2.'\textcolor{red}{2},'.$digit1.$digit2.'\textcolor{red}{3},'.$digit1.$digit2.'\textcolor{red}{4},'.$digit1.$digit2.'\textcolor{red}{5},'.$digit1.$digit2.'\textcolor{red}{6},'.$digit1.$digit2.'\textcolor{red}{7},'.$digit1.$digit2.'\textcolor{red}{8},'.$digit1.$digit2.'\textcolor{red}{9}$$';
		}

		$page[] = 'Tehát az $X$ helyére <span class="label label-success">$'.$correct.'$</span> különböző számjegy kerülhet.';
		$hints[] = $page;

		return $hints;
	}
}

?>