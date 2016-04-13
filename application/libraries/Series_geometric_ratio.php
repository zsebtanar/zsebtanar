<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Series_geometric_ratio {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define member of arithmetic & geometric series
	function Generate($level) {

		$q = rand(2,2*$level);
		$a0 = pow(-1,rand(0,1)) * rand(1,$level);
		$a1 = $a0 * $q;
		$a2 = $a1 * $q;
		$question = 'Egy mértani sorozat három egymást követő tagja ebben a sorrendben $'.$a0.';x$ és $'.$a2.'$. ';

		$question .= 'Határozza meg a sorozat hányadosát!';
		$correct = array($q, -$q);
		$solution = '$q_1='.$q.'$, és $q_2='.strval(-$q).'$';
		$labels = array('$q_1$', '$q_2$');

		$page[] = 'A mértani sorozatban minden tagot úgy tudunk kiszámolni, hogy megszorozzuk $\textcolor{blue}{q}$-val (a <i>hányadossal</i>) az előző számot:$$a_1\xrightarrow{\cdot\textcolor{blue}{q}}a_2\xrightarrow{\cdot\textcolor{blue}{q}}a_3$$';
		$page[] = 'Tehát ha az első szám $'.$a0.'$, akkor'
			.'$$\begin{eqnarray}a_1&=&'.$a0.'\\\\'
			.' a_2&=&a_1\cdot\textcolor{blue}{q}='.$a0.'\cdot\textcolor{blue}{q}=\textcolor{red}{x} \\\\ '
			.' a_3&=&a_2\cdot\textcolor{blue}{q}=a_1\cdot\textcolor{blue}{q}^2='.$a2.'\end{eqnarray}$$';
		$page[] = 'Látjuk, hogy ha '.The($a2).' $'.$a2.'$-'.Dativ($a2).' elosztjuk $'.$a0.'$-'.With($a0)
			.', a hányados négyzetét kapjuk:$$\textcolor{blue}{q}^2='.$a2.':'.($a0<0 ? '('.$a0.')' : $a0).'='
			.strval(pow($q,2)).'$$';
		$page[] = 'Ha ebből négyzetgyököt vonunk, megkapjuk a $\textcolor{blue}{q}$ abszolútértékét:'
			.'$$|\textcolor{blue}{q}|=\sqrt{'.strval(pow($q,2)).'}='.abs($q).'$$';
		$page[] = 'Tehát a $q$ értéke <span class="label label-success">$'.$q.'$</span>, vagy <span class="label label-success">$'.strval(-$q).'$</span>.';
		$hints[] = $page;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'labels'	=> $labels,
			'type' 		=> 'list',
			'hints'		=> $hints
		);
	}
}

?>