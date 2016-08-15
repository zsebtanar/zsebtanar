<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tortek {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		// Generate equation
		$x = rand(-$level, $level);

		// c = c
		$c = rand(-$level,$level);

		// l[0] + l[1]*(x+l[2]) = r1 + r2;
		$l[1] = pow(-1,rand(0,1)) * rand(2,3*$level);
		$l[2] = pow(-1,rand(0,1)) * rand(1,3*$level);
		$l[0] = $c - $l[1] * ($x+$l[2]);
		// print('l0='.$l[0].'<br />');
		// print('l1='.$l[1].'<br />');
		// print('l2='.$l[2].'<br />');

		$r1 = pow(-1,rand(0,1)) * rand(1,3*$level);
		$r2 = $c - $r1;

		// l[0] + l[1]*(x+l[2]) = (x+r[0])/r[1] + (x+r[2])/r[3];
		$r[1] = pow(-1,rand(0,1)) * rand(2,3*$level);
		$r[3] = pow(-1,rand(0,1)) * rand(2,3*$level);
		$r[1] = ($r1 * $r[1] == $x ? $r[1]+1 : $r[1]);
		$r[3] = ($r2 * $r[3] == $x ? $r[3]+1 : $r[3]);
		$r[0] = $r1 * $r[1] - $x;
		$r[2] = $r2 * $r[3] - $x;
		// print('r0='.$r[0].'<br />');
		// print('r1='.$r[1].'<br />');
		// print('r2='.$r[2].'<br />');
		// print('r3='.$r[3].'<br />');

		// // Original exercise
		// $x = -2;
		// $l = [7,-2,5];
		// $r = [6,4,2,2];

		$question 	= 'Oldja meg az alábbi egyenletet a valós számok halmazán!'
			.$this->Equation($l, $r, 0)
			;
		$correct 	= $x;
		$solution 	= '$'.$correct.'$';
		$hints 		= $this->Hints($l, $r, $x);

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}

	
	function Equation($l, $r, $progress=0) {

		$lcm = lcm(abs($r[1]), abs($r[3]));

		$r[4] = $lcm / $r[1];
		$r[5] = $lcm / $r[3];
		$r[6] = $r[4] * $r[0];
		$r[7] = $r[5] * $r[2];
		$r[8] = $r[4] + $r[5];
		$r[9] = $r[6] + $r[7];


		$l[3] = $lcm * $l[0];
		$l[4] = $lcm * $l[1];
		$l[5] = $l[4] * $l[2];
		$l[6] = $l[3] + $l[5];

		$r[10] = $r[8] - $l[4];
		$l[7] = $l[6] - $r[9];
		

		if ($progress == 0) {

			// l[0] + l[1]*(x+l[2]) = (x+r[0])/r[1] + (x+r[2])/r[3];
			$equation = '$$'.$l[0].($l[1] > 0 ? '+' : '').$l[1].'\cdot(x'.($l[2] > 0 ? '+' : '').$l[2].')='.
						($r[1] > 0 ? '' : '-').'\frac{x'.($r[0] > 0 ? '+' : '').$r[0].'}{'.abs($r[1]).'}'.
						($r[3] > 0 ? '+' : '-').'\frac{x'.($r[2] > 0 ? '+' : '').$r[2].'}{'.abs($r[3]).'}$$';

		} elseif ($progress == 1) {

			// l[0] + l[1]*(x+l[2]) = r[4]*(x+r[0])/lcm + r[5]*(x+r[2])/lcm;
			$equation = '$$'.$l[0].($l[1] > 0 ? '+' : '').$l[1].'\cdot(x'.($l[2] > 0 ? '+' : '').$l[2].')=';

			$equation .= ($r[4] > 0 ? '' : '-');
			if (abs($r[4]) == 1) {
				$equation .= '\frac{x'.($r[0] > 0 ? '+' : '').$r[0].'}{'.$lcm.'}';
			} else {
				$equation .= '\frac{'.abs($r[4]).'\cdot(x'.($r[0] > 0 ? '+' : '').$r[0].')}{'.$lcm.'}';
			}

			$equation .= ($r[5] > 0 ? '+' : '-');
			if (abs($r[5]) == 1) {
				$equation .= '\frac{x'.($r[2] > 0 ? '+' : '').$r[2].'}{'.$lcm.'}$$';
			} else {
				$equation .= '\frac{'.abs($r[5]).'\cdot(x'.($r[2] > 0 ? '+' : '').$r[2].')}{'.$lcm.'}$$';
			}

		} elseif ($progress == 2) {

			// lcm*l[0] + lcm*l[1]*(x+l[2]) = r[4]*(x+r[0] + r[5]*(x+r[2]);
			// l[3] 	+ l[4]*(x+l[2]) 	= r[4]*(x+r[0] + r[5]*(x+r[2]);

			$equation = '$$'.$l[3].($l[4] > 0 ? '+' : '').$l[4].'\cdot(x'.($l[2] > 0 ? '+' : '').$l[2].')=';

			$equation .= ($r[4] > 0 ? '' : '-');
			if (abs($r[4]) == 1) {
				$equation .= '(x'.($r[0] > 0 ? '+' : '').$r[0].')';
			} else {
				$equation .= abs($r[4]).'\cdot(x'.($r[0] > 0 ? '+' : '').$r[0].')';
			}

			$equation .= ($r[5] > 0 ? '+' : '-');
			if (abs($r[5]) == 1) {
				$equation .= '(x'.($r[2] > 0 ? '+' : '').$r[2].')$$';
			} else {
				$equation .= abs($r[5]).'\cdot(x'.($r[2] > 0 ? '+' : '').$r[2].')$$';
			}

		} elseif ($progress == 3) {

			// l[3] + l[4]*x + l[4]*l[2] = r[4]*x + r[4]*r[0] + r[5]*x + r[5]*r[2];
			// l[3] + l[4]*x + l[5] 	 = r[4]*x + r[6]	  + r[5]*x + r[7];
			$equation = '$$'.$l[3].($l[4] > 0 ? '+' : '').$l[4].'\cdot x'.($l[5] > 0 ? '+' : '').$l[5].'=';

			$equation .= ($r[4] > 0 ? '' : '-').
					(abs($r[4]) != 1 ? abs($r[4]).'\cdot ' : '').'x'.
					($r[6] > 0 ? '+' : '').$r[6];

			$equation .= ($r[5] > 0 ? '+' : '-').
					(abs($r[5]) != 1 ? abs($r[5]).'\cdot ' : '').'x'.
					($r[7] > 0 ? '+' : '').$r[7].'$$';

		} elseif ($progress == 4) {

			// (l[3] + l[5]) + l[4]*x = (r[4] + r[5]) * x + (r[6] + r[7]);
			// l[6] 		 + l[4]*x = r[8]*x 		 	  + r[9];
			if ($l[6] == 0) {
				$equation = '$$'.$l[4].'\cdot x=';
			} else {
				$equation = '$$'.$l[6].($l[4] > 0 ? '+' : '').$l[4].'\cdot x=';
			}

			if ($r[8] == 0) {
				if ($r[9] == 0) {
					$equation .= '0$$';
				} else {
					$equation .= $r[9].'$$';
				}
			} else {
				if ($r[9] == 0) {
					$equation .= ($r[8] > 0 ? '' : '-').
						(abs($r[8]) != 1 ? abs($r[8]).'\cdot ' : '').'x$$';
				} else {
					$equation .= ($r[8] > 0 ? '' : '-').
						(abs($r[8]) != 1 ? abs($r[8]).'\cdot ' : '').'x'.
						($r[9] > 0 ? '+' : '').$r[9].'$$';
				}
			}

		} elseif ($progress == 5) {

			// l[6] - r[9] = (r[8] - l[4]) * x;
			// l[7] 	   = r[10] * x;
			$equation = '$$'.$l[7].'='.$r[10].'\cdot x$$';
			if ($r[10] == 0) {
				// r10 = r8 - l4 = lcm/r1 + lcm/r3 - lcm*l1
				die('Hibás feladat! Kérlek, jelezd a hibát a zsebtanar@gmail.com-on!');
			}
			
		}

		return $equation;	
	}	

	function Hints($l, $r, $x) {

		
		$lcm = lcm($r[1], $r[3]);

		if (abs($r[1]) != abs($r[3])) {
			$page[] = 'Hozzuk közös nevezőre az egyenlet jobb oldalát (a közös nevező $'.$lcm.'$ lesz):'.
				$this->Equation($l, $r, 1);
		}

		$page[] = 'Szorozzuk meg mindkét oldalt $'.$lcm.'$-'.With($lcm).'!'.
			$this->Equation($l, $r, 2);

		$page[] = 'Bontsuk fel a zárójeleket mindkét oldalon!'.
			$this->Equation($l, $r, 3);

		$page[] = 'Vonjuk össze az egynemű tagokat!'.
			$this->Equation($l, $r, 4);

		$page[] = 'Gyűjtsük össze az $x$-et tartalmazó tagokat jobb oldalra, a többit pedig balra!'.
			$this->Equation($l, $r, 5);

		$page[] = 'Ha mindkét oldalt leosztjuk az $x$ együtthatójával, azt kapjuk, hogy a megoldás <span class="label label-success">$'.$x.'$</span>.';

		$hints[] = $page;

		return $hints;
	}
}

?>