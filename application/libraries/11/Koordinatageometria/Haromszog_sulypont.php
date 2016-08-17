<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haromszog_sulypont {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$A = [rand(-10,10), rand(-10,10)];
		$B = [rand(-10,10), rand(-10,10)];

		// // Original exercise
		// $A = [-3,-1];
		// $B = [3,7];

		$C[0] = -$A[0]-$B[0];
		$C[1] = -$A[1]-$B[1];

		$question 	= 'Az $ABC$ háromszög két csúcsa $A('.$A[0].';'.$A[1].')$ és $B('.$B[0].';'.$B[1].')$, súlypontja az origó. Határozza meg a $C$ csúcs koordinátáit!';
		$correct 	= $C;
		$solution 	= '$C('.$C[0].';'.$C[1].')$';

		$page[] = 'A háromszög súlypontjának koordinátái a csúcsok megfelelő koordinátáinak számtani közepe.';
		$page[] = 'Írjuk fel ezt az összefüggést először a $C(c_1;c_2)$ csúcs $x$-koordinájára:$$
		\begin{eqnarray}0&=&\frac{'.$A[0].($B[0]<0 ? '' : '+').$B[0].'+c_1}{3}\\\\
		3\cdot0&=&'.$A[0].($B[0]<0 ? '' : '+').$B[0].'+c_1\\\\
		'.strval(-$A[0]).(-$B[0]<0 ? '' : '+').strval(-$B[0]).'&=&c_1\\\\
		'.$C[0].'&=&c_1\\\\
		\end{eqnarray}$$';
		$page[] = 'Tehát a $C$ csúcs $x$ koordinátája <span class="label label-success">$'.$C[0].'$</span>.';
		$hints[] = $page;

		$page = [];
		$page[] = 'Írjuk fel ezt az összefüggést először a $C(c_1;c_2)$ csúcs $y$-koordinájára:$$
		\begin{eqnarray}0&=&\frac{'.$A[1].($B[1]<0 ? '' : '+').$B[1].'+c_2}{3}\\\\
		3\cdot0&=&'.$A[1].($B[1]<0 ? '' : '+').$B[1].'+c_2\\\\
		'.strval(-$A[1]).(-$B[1]<0 ? '' : '+').strval(-$B[1]).'&=&c_2\\\\
		'.$C[1].'&=&c_2\\\\
		\end{eqnarray}$$';
		$page[] = 'Tehát a $C$ csúcs $y$-koordinátája <span class="label label-success">$'.$C[1].'$</span>.';
		$hints[] = $page;		

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'labels' 	=> '$C$',
			'type'		=> 'coordinate'
		);
	}
}

?>