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

		$A[0] = rand(-10,10);
		$A[1] = $A[0]*$m + $b;
		$B[0] = -$A[0];
		$B[1] = $B[0]*$m + $b;

		// // Original exercise
		// $A = [-3,-1];
		// $B = [3,7];
		// $m = ($A[1]-$B[1])/($A[0]-$B[0]);
		// $b = $A[1] - $A[0]*$m;

		$mfrac['nom'] = ($B[1]-$A[1]) / gcd($B[1]-$A[1],$B[0]-$A[0]);
		$mfrac['denum'] = ($B[0]-$A[0]) / gcd($B[1]-$A[1],$B[0]-$A[0]);

		$question 	= 'Írja fel a hozzárendelési utasítását annak a lineáris függvénynek, mely $'.$A[0].'$-'.To($A[0]).' $'.$A[1].'$-'.Dativ($A[1]).' és $'.$B[0].'$-'.To($B[0]).' $'.$B[1].'$-'.Dativ($A[1]).' rendel! (A hozzárendelési utasítást $x\rightarrow mx+b$ alakban adja meg!)';

		$page[] = 'A lineáris függvényt $y=mx+b$ alakban keressük.';
		$page[] = 'A feladat alapján írjuk fel az első összefüggést:$$'.$A[1].'='.$A[0].'\cdot m+b$$';
		$page[] = 'Hasonló módon felírhatjuk a második összefüggést:$$'.$B[1].'='.$B[0].'\cdot m+b$$';
		$hints[] = $page;

		$page = [];
		$page[] = 'Vonjuk ki az első egyenletből a másodikat! Ekkor a $b$-s tagok esnek ki:$$\begin{eqnarray}
			'.$A[1].(-$B[1]<0 ? '' : '+').strval(-$B[1]).'&=&('.$A[0].(-$B[0]<0 ? '' : '+').strval(-$B[0]).')\cdot m+b-b\\\\
			'.strval($A[1]-$B[1]).'&=&'.strval($A[0]-$B[0]).'\cdot m\\\\
			\frac{'.strval($A[1]-$B[1]).'}{'.strval($A[0]-$B[0]).'}&=&m\\\\
		\end{eqnarray}$$';
		$page[] = 'Tehát az $m$ értéke <span class="label label-success">$'.(round($m)==$m ? $m : '\frac{'.$mfrac['nom'].'}{'.$mfrac['denum'].'}').'$</span>.';
		$hints[] = $page;

		$page = [];
		$page[] = 'Most adjuk össze a két egyenletet! Ekkor az $m$-es tagok kiesnek:$$\begin{eqnarray}
			'.$A[1].($B[1]<0 ? '' : '+').$B[1].'&=&('.$A[0].($B[0]<0 ? '' : '+').$B[0].')\cdot m+b+b\\\\
			'.strval($A[1]+$B[1]).'&=&2\cdot b\\\\
			\frac{'.strval($A[1]+$B[1]).'}{2}&=&b\\\\
		\end{eqnarray}$$';
		$page[] = 'Tehát a $b$ értéke <span class="label label-success">$'.$b.'$</span>.';
		$hints[] = $page;

		$correct 	= [$m, $b];
		$solution 	= '$m='.$m.'\quad b='.$b.'$';

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