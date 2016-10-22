<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlet1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Solve quadratic equation
	function Generate($level) {

		// Generate solutions
		$values = range(-$level, $level);
		$rand_keys = array_rand($values, 2);
		$rand_key2 = array_rand($values, 1);
		$x1 = $values[$rand_keys[0]];
		$x2 = $values[$rand_keys[1]];

		// Generate coefficients (using Viète-formulae)
		// ax^2+bx+c=d
		$a = rand(1,ceil($level/3))*(-1)^(rand(1,2));
		$b = -$a*($x1 + $x2);
		$d = rand(-$level, $level);
		$c = $a*$x1*$x2+$d;

		// // Original exercise
		// $x1 = -3;
		// $x2 = 7;
		// $a = 1;
		// $b = -$a*($x1 + $x2);
		// $d = 0;
		// $c = $a*$x1*$x2+$d;

		$equation = $this->Equation($a, $b, $c, $d, $x1, $x2);

		$question = 'Oldja meg '.The($a).' $'.$equation.'$ egyenletet a valós számok halmazán!';
		$hints = $this->Hints($a, $b, $c, $d, $x1, $x2);

		if ($x1 != $x2) {
			$correct = array($x1, $x2);
			$solution = 'Az első megoldás $x_1='.$x1.'$, a második $x_2='.$x2.'$.';
		} else {
			$correct = array($x1, NULL);
			$solution = 'Az egyenlet megoldása: $x='.$x1.'$.';
		}

		$labels = array('$x_1$', '$x_2$');

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'labels'	=> $labels,
			'hints' 	=> $hints,
			'type' 		=> 'list',
			'youtube'	=> 'GwtcU9kr5Xg'
		);
	}

	function Hints($a, $b, $c, $d, $x1, $x2) {

		if ($d != 0) {
			$text[] = 'Először '.($d > 0 ? 'vonjunk ki' : 'adjunk hozzá').' az egyenlet mindkét '.($d > 0 ? 'oldalából' : 'oldalához').' $'.abs($d).'$-'.Dativ($d).', hogy $0$ legyen a jobb oldalon!';
			$equation1 = $this->Equation($a, $b, $c, $d, $x1, $x2, $d);
			$text[] = '$$'.$equation1.'$$';
			$equation2 = $this->Equation($a, $b, $c-$d, 0, $x1, $x2);
			$text[] = '$$'.$equation2.'$$';
			// $hints[] = $text;
		}

		$text = [];
		$text[] = '<div class="alert alert-info"><b>Megoldóképlet:</b><br />Az $a\cdot x^2+b\cdot x+c=0$ alakú másodfokú egyenlet megoldásai:$$x_{1,2}=\frac{-b\pm\sqrt{b^2-4ac}}{2a}$$</div>';

		if ($d != 0) {
			$text[] = 'Az egyenletet az előbb a következő alakra egyszerűsítettűk:$$'.$equation2.'$$';
			$text[] = 'Ebben az egyenletben $a='.$a.'$, $b='.$b.'$, $c='.strval($c-$d).'$, ezért:';
		} else {
			$text[] = 'Jelen esetben $a='.$a.'$, $b='.$b.'$, $c='.strval($c-$d).'$, ezért:';
		}

		$c = $c-$d;
		$aa = ($a >= 0 ? $a : '('.$a.')');
		$bb = ($b >= 0 ? $b : '('.$b.')');
		$cc = ($c >= 0 ? $c : '('.$c.')');
		// print_r('a='.$a.', b='.$b.', c='.$c.', aa='.$aa.', bb='.$bb.', cc='.$cc.'<br/>');

		$text[] = '$$x_{1,2}=\frac{-'.$bb.'\pm\sqrt{'.$bb.'^2-4\cdot'.$aa.'\cdot'.$cc.'}}{2\cdot'.$aa.'}$$';

		if ($x1 != $x2) {
			$text[] = 'Ezt kiszámolva az egyik megoldás <span class="label label-success">$'.$x1.'$</span>,'
			.' a másik pedig <span class="label label-success">$'.$x2.'$</span>.';	
		} else {
			$text[] = 'Ezt kiszámolva az egyenlet megoldása <span class="label label-success">$'.$x1.'$</span>.';
		}

		$text[] = $this->SolverDetails($a, $aa, $b, $bb, $c, $cc);
		
		$hints[] = $text;

		return $hints;
	}

	// ax^2+bx+c-e=d-e (solution: x1, x2)
	function Equation($a, $b, $c, $d, $x1, $x2, $e=NULL) {

		if (abs($a) == 1) {
			$part1 = ($a == 1 ? '' : '-');
		} else {
			$part1 = $a;
		}

		if ($b != 0) {
			$part2 = ($b > 0 ? '+'.$b.'x' : $b.'x'); 
		} else {
			$part2 = '';
		}

		if ($c != 0) {
			$part3 = ($c > 0 ? '+'.$c : $c); 
		} else {
			$part3 = '';
		}

		if ($e != NULL) {
			$part4 = ($e > 0 ? '-'.$e : '+'.abs($e));
		} else {
			$part4 = '';
		}

		$equation = $part1.'x^2'.$part2.$part3.$part4.'='.$d.$part4;

		return $equation;
	}

	function SolverDetails($a, $aa, $b, $bb, $c, $cc) {

		// print_r('a='.$a.', b='.$b.', c='.$c.', aa='.$aa.', bb='.$bb.', cc='.$cc.'<br/>');

		$aacc1 = ($a*$c >= 0 ? '+4\cdot'.$a*$c : '-4\cdot('.$a*$c.')');
		$aacc2 = ($a*$c >= 0 ? '+'.strval(4*$a*$c) : '-('.strval(4*$a*$c).')');
		$sqr = pow($b,2)-4*$a*$c;
		$text[] = 'Először számoljuk ki a gyökjel alatti kifejezést!$$'
			.$bb.'^2-4\cdot'.$aa.'\cdot'.$cc.'='
			.pow($b,2).$aacc1.'='
			.pow($b,2).$aacc2.'='
			.($a*$c < 0 ? pow($b,2).'+'.abs(4*$a*$c).'=' : '')
			.$sqr.'$$';

		if ($sqr != 0) {
			$text[] = 'Az egyik megoldás:$$'
				.'x_1=\frac{-'.$bb.'+\sqrt{'.$sqr.'}}{2\cdot'.$aa.'}='
				.'\frac{'.strval(-$b).'+'.sqrt($sqr).'}{'.strval(2*$a).'}='
				.'\frac{'.strval(-$b+sqrt($sqr)).'}{'.strval(2*$a).'}='
				.strval(intval((-$b+sqrt($sqr))/(2*$a))).'$$';

			$text[] = 'A másik megoldás:$$'
				.'x_2=\frac{-'.$bb.'-\sqrt{'.$sqr.'}}{2\cdot'.$aa.'}='
				.'\frac{'.strval(-$b).'-'.sqrt($sqr).'}{'.strval(2*$a).'}='
				.'\frac{'.strval(-$b-sqrt($sqr)).'}{'.strval(2*$a).'}='
				.strval(intval((-$b-sqrt($sqr))/(2*$a))).'$$';
		} else {

			$text[] = 'Mivel a gyökjel alatti kifejezés értéke $0$, az egyenletnek csak egy megoldása van:$$'
				.'x=\frac{-'.$bb.'}{2'.$aa.'}='
				.'\frac{'.strval(-$b).'}{'.strval(2*$a).'}='
				.strval(intval(-$b/2/$a)).'$$';
		}

		return $text;
	}
}

?>