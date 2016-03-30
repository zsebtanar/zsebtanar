<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Series_mixed {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define member of arithmetic & geometric series
	function Generate($level) {

		if ($level <= 3) {

			$d = rand(-20,20);
			$a0 = rand(-20,20);
			$a1 = $a0 + $d;
			$a2 = $a1 + $d;
			$question = 'Egy számtani sorozat három egymást követő tagja ebben a sorrendben $'.$a0.';x$ és $'.$a2.'$. ';
			$type = 'int';

			if (1 == 1) {

				$question .= 'Határozza meg az $x$ értékét!';
				$correct = $a1;
				$solution = '$'.$correct.'$';

				$page[] = 'A számtani sorozatban minden tagot úgy tudunk kiszámolni, hogy hozzáadunk $\textcolor{blue}{d}$-t (a <i>differenciát</i>) az előző számhoz:$$a_1\xrightarrow{+\textcolor{blue}{d}}a_2\xrightarrow{+\textcolor{blue}{d}}a_3$$';
				$page[] = 'Tehát ha az első szám $'.$a0.'$, akkor'
					.'$$\begin{eqnarray}a_1&=&'.$a0.'\\\\'
					.' a_2&=&a_1+\textcolor{blue}{d}='.$a0.'+\textcolor{blue}{d}=\textcolor{red}{x} \\\\ '
					.' a_3&=&a_2+\textcolor{blue}{d}=a_1+2\cdot \textcolor{blue}{d}='.$a2.'\end{eqnarray}$$';
				$page[] = 'Látjuk, hogy ha '.The($a2).' $'.$a2.'$-'.From($a2).' kivonunk $'.$a0.'$-'.Dativ($a0)
					.', a differencia $2$-szeresét kapjuk:$$2\cdot\textcolor{blue}{d}='.$a2.'-'.($a0<0 ? '('.$a0.')' : $a0).'='
					.($a0<0 ? $a2.'+'.abs($a0).'=' : '').strval(2*$d).'$$';
				$page[] = 'Ha ezt a különbséget elosztjuk $2$-vel, megkapjuk a $\textcolor{blue}{d}$ értékét:'
					.'$$\textcolor{blue}{d}='.strval(2*$d).':2='.$d.'$$';
				$page[] = 'Így már az $\textcolor{red}{x}$ értékét is ki tudjuk számolni:'
					.'$$\textcolor{red}{x}='.$a0.'+'.($d<0 ? '('.$d.')='.$a0.'-'.abs($d) : $d).'='.$a1.'$$';
				$page[] = 'Tehát az $x$ értéke <span class="label label-success">$'.$a1.'$</span>.';
				$hints[] = $page;
			
			} else {

				$question .= 'Határozza meg a sorozat differenciáját!';
				$correct = $d;
				$solution = '$'.$correct.'$';

				$page[] = 'A számtani sorozatban minden tagot úgy tudunk kiszámolni, hogy hozzáadunk $\textcolor{blue}{d}$-t (a <i>differenciát</i>) az előző számhoz:$$a_1\xrightarrow{+\textcolor{blue}{d}}a_2\xrightarrow{+\textcolor{blue}{d}}a_3$$';
				$page[] = 'Tehát ha az első szám $'.$a0.'$, akkor'
					.'$$\begin{eqnarray}a_1&=&'.$a0.'\\\\'
					.' a_2&=&a_1+\textcolor{blue}{d}='.$a0.'+\textcolor{blue}{d}=x \\\\ '
					.' a_3&=&a_2+\textcolor{blue}{d}=a_1+2\cdot \textcolor{blue}{d}='.$a2.'\end{eqnarray}$$';
				$page[] = 'Látjuk, hogy ha '.The($a2).' $'.$a2.'$-'.From($a2).' kivonunk $'.$a0.'$-'.Dativ($a0)
					.', a differencia $2$-szeresét kapjuk:$$2\cdot\textcolor{blue}{d}='.$a2.'-'.($a0<0 ? '('.$a0.')' : $a0).'='
					.($a0<0 ? $a2.'+'.abs($a0).'=' : '').strval(2*$d).'$$';
				$page[] = 'Ha ezt a különbséget elosztjuk $2$-vel, megkapjuk a $\textcolor{blue}{d}$ értékét:'
					.'$$\textcolor{blue}{d}='.strval(2*$d).':2='.$d.'$$';
				$page[] = 'Tehát a $d$ értéke <span class="label label-success">$'.$d.'$</span>.';
				$hints[] = $page;
			}
				
		} elseif ($level <= 6) {

			$q = rand(2,10);
			$a0 = pow(-1,rand(0,1)) * rand(1,10);
			$a1 = $a0 * $q;
			$a2 = $a1 * $q;
			$question = 'Egy mértani sorozat három egymást követő tagja ebben a sorrendben $'.$a0.';x$ és $'.$a2.'$. ';

			if (rand(1,2) == 1) {

				$question .= 'Határozza meg az $x$ értékét!';
				$correct = array($a1, -$a1);
				$solution = '$x_1='.$a1.'$, és $x_2='.strval(-$a1).'$$';
				$type = 'equation2';

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
					.'$$\begin{eqnarray}\textcolor{red}{x_1}&=&'.$a0.'\cdot'.($q<0 ? '('.$q.')' : $q).strval($a0*$q).'\\\\ \textcolor{red}{x_2}&=&'.$a0.'\cdot'.(-$q<0 ? '('.strval(-$q).')' : strval(-$q)).strval(-$a0*$q).'\end{eqnarray}$$';
				$page[] = 'Tehát az $x$ értéke <span class="label label-success">$'.strval($a0*$q).'$</span>, vagy <span class="label label-success">$'.strval(-$a0*$q).'$</span>.';
				$hints[] = $page;
			
			} else {

				$question .= 'Határozza meg a sorozat hányadosát!';
				$correct = array($q, -$q);
				$solution = '$q_1='.$q.'$, és $q_2='.strval(-$q).'$';
				$type = 'quotient2';

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
			}

		} else {
			
			$a1 = rand(-100,100);
			$a3 = rand(-100,100);
			$a3 = (abs($a1-$a3) < 6 ? $a1 + 6 : $a3);
			$a2 = ($a1 < $a3 ? rand($a1+1, $a3-1) : rand($a3+1, $a1-1));
			$sum = $a1 + $a2 + $a3;
			$a2 = ($a2 + 3 - $sum%3 >= max($a1, $a3) ? $a2 - $sum%3 : $a2 + 3 - $sum%3);

			$avg = ($a1 + $a2 + $a3)/3;
			$diff = $avg - $a2;

			$question = strtoupper(The($a1)).' $'.$a1.';x$ és $'.$a3.'$ számokról tudjuk, hogy a három szám átlaga $'.abs($diff).'$-'.With(abs($diff)).' '.($diff > 0 ? 'nagyobb' : 'kisebb').', mint a mediánja, továbbá $'.$a1.($a1<$a3 ? '\lt ' : '\gt ').'x'.($a1<$a3 ? '\lt ' : '\gt ').$a3.'$. Határozza meg az $x$ értékét!';
			$correct = $a2;
			$solution = '$'.$correct.'$';
			$type = 'int';

			$page[] = 'Írjuk fel az átlagot ($A$):$$'.($a3 > 0 ? 'A=\frac{'.$a1.'+x+'.$a3.'}{3}' : '\begin{eqnarray}A&=&\frac{'.$a1.'+x+('.$a3.')}{3}\\\\ &=&\frac{'.$a1.'+x'.$a3.'}{3}\end{eqnarray}').'$$';
			$page[] = 'Három szám közül a medián ($M$) a nagyság szerinti középső, vagyis $M=x$.';
			$page[] = 'A feladat szerint az átlag $'.abs($diff).'$-'.With(abs($diff)).' '.($diff > 0 ? 'nagyobb' : 'kisebb').', mint a medián, azaz:$$A=M'.($diff > 0 ? '-' : '+').abs($diff).'$$';
			$hints[] = $page;

			$page = [];
			$page[] = 'Helyettesítsük be $M$ és $A$ értékét, és fejezzük ki $x$-et:$$x=\frac{'.$a1.'+x'.($a3>0 ? '+' : '').$a3.'}{3}'.($diff > 0 ? '-' : '+').abs($diff).'$$';
			$page[] = 'Szorozzuk meg mindkét oldalt $3$-mal:$$3\cdot x=('.$a1.'+x'.($a3>0 ? '+' : '').$a3.')'.($diff > 0 ? '-' : '+').abs(3*$diff).'$$';
			$page[] = 'Elhagyhatjuk a zárójelet:$$3\cdot x='.$a1.'+x'.($a3>0 ? '+' : '').$a3.($diff > 0 ? '-' : '+').abs(3*$diff).'$$';
			$page[] = 'Vonjunk ki mindkét oldalból $x$-et:$$2\cdot x='.$a1.($a3>0 ? '+' : '').$a3.($diff > 0 ? '-' : '+').abs(3*$diff).'$$';
			$page[] = 'Végezzük el a műveleteket a jobb oldalon:$$2\cdot x='.strval($a1+$a3-3*$diff).'$$';
			$page[] = 'Osszuk el mindkét oldalt $2$-vel:$$x=\frac{'.strval($a1+$a3-3*$diff).'}{2}='.strval(($a1+$a3-3*$diff)/2).'$$';
			$page[] = 'Tehát a megoldás <span class="label label-success">$'.$a2.'$</span>.';
			$hints[] = $page;

		}

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