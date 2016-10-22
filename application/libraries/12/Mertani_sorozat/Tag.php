<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag {

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

		// // Original exercise
		// $q = 3/4;
		// $a0 = 32;

		$a1 = $a0 * $q;
		$a2 = $a1 * $q;
		$question = 'Egy mértani sorozat három egymást követő tagja ebben a sorrendben $'.$a0.';x$ és $'.$a2.'$. ';

		$question .= 'Határozza meg az $x$ értékét!';
		$correct = array($a1, -$a1);
		$solution = '$x_1='.$a1.'$, és $x_2='.strval(-$a1).'$$';
		$labels = array('$x_1$', '$x_2$');

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
		$page[] = 'Tehát a $q$ értéke $'.$q.'$, vagy $'.strval(-$q).'$.';
		$page[] = 'Így már az $\textcolor{red}{x}$ értékét is ki tudjuk számolni:'
			.'$$\begin{eqnarray}\textcolor{red}{x_1}&=&'.$a0.'\cdot'.($q<0 ? '('.$q.')' : $q).'='.strval($a0*$q).'\\\\ \textcolor{red}{x_2}&=&'.$a0.'\cdot'.(-$q<0 ? '('.strval(-$q).')' : strval(-$q)).'='.strval(-$a0*$q).'\end{eqnarray}$$';
		$page[] = 'Tehát az $x$ értéke <span class="label label-success">$'.strval($a0*$q).'$</span>, vagy <span class="label label-success">$'.strval(-$a0*$q).'$</span>.';

		// $page[] = 'A mértani sorozatban minden tagot úgy tudunk kiszámolni, hogy megszorozzuk $\textcolor{blue}{q}$-val (a <i>hányadossal</i>) az előző számot:$$a_1\xrightarrow{\cdot\textcolor{blue}{q}}a_2\xrightarrow{\cdot\textcolor{blue}{q}}a_3$$';
		// $page[] = 'Tehát ha az első szám $'.$a0.'$, akkor'
		// 	.'$$\begin{eqnarray}a_1&=&'.$a0.'\\\\'
		// 	.' a_2&=&a_1\cdot\textcolor{blue}{q}='.$a0.'\cdot\textcolor{blue}{q}=\textcolor{red}{x} \\\\ '
		// 	.' a_3&=&a_2\cdot\textcolor{blue}{q}=a_1\cdot\textcolor{blue}{q}^2='.$a2.'\end{eqnarray}$$';
		// $page[] = 'Látjuk, hogy ha '.The($a2).' $'.$a2.'$-'.Dativ($a2).' elosztjuk $'.$a0.'$-'.With($a0)
		// 	.', a hányados négyzetét kapjuk:$$\textcolor{blue}{q}^2='.$a2.':'.($a0<0 ? '('.$a0.')' : $a0).'=\frac{9}{16}$$';
		// $page[] = 'Ha ebből négyzetgyököt vonunk, megkapjuk a $\textcolor{blue}{q}$ abszolútértékét:'
		// 	.'$$|\textcolor{blue}{q}|=\sqrt{\frac{9}{16}}=\frac{3}{4}$$';
		// $page[] = 'Tehát a $q$ értéke $\frac{3}{4}$, vagy $-\frac{3}{4}$.';
		// $page[] = 'Így már az $\textcolor{red}{x}$ értékét is ki tudjuk számolni:'
		// 	.'$$\begin{eqnarray}\textcolor{red}{x_1}&=&'.$a0.'\cdot\frac{3}{4}=8\cdot3='.strval($a0*$q).'\\\\ \textcolor{red}{x_2}&=&'.$a0.'\cdot\left(-\frac{3}{4}\right)=8\cdot(-3)='.strval(-$a0*$q).'\end{eqnarray}$$';
		// $page[] = 'Tehát az $x$ értéke <span class="label label-success">$'.strval($a0*$q).'$</span>, vagy <span class="label label-success">$'.strval(-$a0*$q).'$</span>.';

		$hints[] = $page;
	
		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'labels'	=> $labels,
			'type' 		=> 'list',
			'hints'		=> $hints,
			'youtube'	=> '0uM4dWKk24g'
		);
	}
}

?>