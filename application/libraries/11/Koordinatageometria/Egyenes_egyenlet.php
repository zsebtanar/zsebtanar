<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenes_egyenlet {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$m = pow(-1,rand(1,2)) * rand(1,2);
		$b = rand(-5,5);

		$A[0] = pow(-1,rand(1,2)) * rand(1,10); // Ax != 0
		$A[1] = $A[0]*$m + $b;
		$B[0] = -$A[0];
		$B[1] = $B[0]*$m + $b;

		// // Original exercise
		// $A = [-3,-1];
		// $B = [3,7];
		// $m = ($A[1]-$B[1])/($A[0]-$B[0]);
		// $b = $A[1] - $A[0]*$m;

		// print_r('m='.$m.', b='.$b.'<br />');
		// print_r('A('.$A[0].';'.$A[1].'), B('.$B[0].';'.$B[1].')	<br />');

		$mfrac['nom'] = ($B[1]-$A[1]) / gcd($B[1]-$A[1],$B[0]-$A[0]);
		$mfrac['denum'] = ($B[0]-$A[0]) / gcd($B[1]-$A[1],$B[0]-$A[0]);

		$question 	= 'Írja fel a hozzárendelési utasítását annak a lineáris függvénynek, mely $'.($A[0]<0 ? '('.$A[0].')' : $A[0]).'$-'.To($A[0]).' $'.($A[1]<0 ? '('.$A[1].')' : $A[1]).'$-'.Dativ($A[1]).' és $'.($B[0]<0 ? '('.$B[0].')' : $B[0]).'$-'.To($B[0]).' $'.($B[1]<0 ? '('.$B[1].')' : $B[1]).'$-'.Dativ($B[1]).' rendel! (A hozzárendelési utasítást $x\mapsto mx+b$ alakban adja meg!)';

		$page[] = 'A hozzárendelés egy $y=mx+b$ alakú lineáris függvény lesz.';
		$page[] = 'A függvény $'.($A[0]<0 ? '('.$A[0].')' : $A[0]).'$-'.To($A[0]).' $'.($A[1]<0 ? '('.$A[1].')' : $A[1]).'$-'.Dativ($A[1]).' rendel, azaz:$$'.$A[1].'='.$A[0].'\cdot m+b$$';
		$page[] = 'Továbbá azt is tudjuk, hogy $'.($B[0]<0 ? '('.$B[0].')' : $B[0]).'$-'.To($B[0]).' $'.($B[1]<0 ? '('.$B[1].')' : $B[1]).'$-'.Dativ($B[1]).' rendel, azaz:$$'.$B[1].'='.$B[0].'\cdot m+b$$';
		$hints[] = $page;

		$page = [];
		$page[] = '$$\begin{eqnarray}
			I.\quad& '.$A[1].'&=&'.$A[0].'\cdot m+b\\\\
			II.\quad& '.$B[1].'&=&'.$B[0].'\cdot m+b
		\end{eqnarray}$$
			Vonjuk ki az első egyenletből a másodikat! Ekkor a $b$-s tagok kiesnek:$$\begin{eqnarray}
			'.$A[1].(-$B[1]<0 ? '' : '+').strval(-$B[1]).'&=&('.$A[0].(-$B[0]<0 ? '' : '+').strval(-$B[0]).')\cdot m+b-b\\\\
			'.strval($A[1]-$B[1]).'&=&'.strval($A[0]-$B[0]).'\cdot m\\\\
			\frac{'.strval($A[1]-$B[1]).'}{'.strval($A[0]-$B[0]).'}&=&m\\\\
		\end{eqnarray}$$';
		$page[] = 'Tehát az $m$ értéke <span class="label label-success">$'.(round($m)==$m ? $m : '\frac{'.$mfrac['nom'].'}{'.$mfrac['denum'].'}').'$</span>.';
		$hints[] = $page;

		$page = [];
		$page[] = '$$\begin{eqnarray}
			I.\quad& '.$A[1].'&=&'.$A[0].'\cdot m+b\\\\
			II.\quad& '.$B[1].'&=&'.$B[0].'\cdot m+b
		\end{eqnarray}$$
		Most adjuk össze a két egyenletet! Ekkor az $m$-es tagok esnek ki:$$\begin{eqnarray}
			'.$A[1].($B[1]<0 ? '' : '+').$B[1].'&=&('.$A[0].($B[0]<0 ? '' : '+').$B[0].')\cdot m+b+b\\\\
			'.strval($A[1]+$B[1]).'&=&2\cdot b\\\\
			\frac{'.strval($A[1]+$B[1]).'}{2}&=&b\\\\
		\end{eqnarray}$$';
		$page[] = 'Tehát a $b$ értéke <span class="label label-success">$'.$b.'$</span>.';
		$hints[] = $page;

		$correct 	= [round1($m), $b];
		$solution 	= '$m='.round2($m).'\quad b='.$b.'$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'type'		=> 'array',
			'solution'  => $solution,
			'hints'		=> $hints,
			'labels'	=> ['$m$', '$b$']
		);
	}
}

?>