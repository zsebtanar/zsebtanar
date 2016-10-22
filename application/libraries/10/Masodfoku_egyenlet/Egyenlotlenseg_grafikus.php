<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlotlenseg_grafikus {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		// solutions
		$numbers = array_diff(range(-$level, $level), [0]);
		shuffle($numbers);
		$x1 = $numbers[0];
		$x2 = $numbers[1];
		if ($x2 < $x1) {
			list($x1, $x2) = array($x2, $x1);
		}

		// number of solutions
		$no_solutions = rand(0,2);
		$x2 = ($no_solutions == 1 ? $x1 : $x2);

		// coefficients: a*x^2 + b*x + c = 0
		$a = pow(-1,rand(0,1)) * rand(1,$level);
		$b = -$a * ($x1 + $x2);
		$c = $a * ($x1 * $x2);

		// no solution if: b^2 - 4ac < 0 => b^2 < 4ac
		// b^2 / 4a < c (if a>0)
		// b^2 / 4a > c (if a<0)
		if ($no_solutions == 0) {
			$c = ($a>0 ? ceil(pow($b,2) / 4/$a)+1 : floor(pow($b,2) / 4/$a)-1);
		}

		// relation: <=, <, >, >=
		$relations 	= ['\leq', '<', '>', '\geq'];
		$relation 	= $relations[rand(0,3)];

		// // Original exercise
		// $x1 = -1;
		// $x2 = 2;
		// $a = 1;
		// $b = -1;
		// $c = -2;
		// $no_solutions = 2;
		// $relation = '\leq';

		$question 	= 'Oldja meg az alábbi egyenlőtlenséget a valós számok halmazán!'
			.$this->Equation_text($a, $b, $c, $x1, $x2, 0, $relation)
			// .$this->Equation_text($a, $b, $c, $x1, $x2, 1)
			// .$this->Graph($a, $b, $c, $x1, $x2, $relation, 1)
			;
		$correct 	= 0;
		$options 	= $this->Options($a, $b, $c, $x1, $x2, $relation, $no_solutions);
		$solution 	= '$'.$correct.'$';
		$hints		= $this->Hints($a, $b, $c, $x1, $x2, $relation, $no_solutions);

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'options'	=> $options,
			'hints'		=> $hints,
			'youtube'	=> 'YBaNfDtzJvU'
		);
	}

	function Options($a, $b, $c, $x1, $x2, $relation, $no_solutions) {

		if ($no_solutions == 0) {

			$options[0] = 'Nincs megoldás.';
			$options[]	= (rand(1,2)==1 ? '$x<'.$x1.'$' : '$x>'.$x2.'$');
			$options[]	= (rand(1,2)==1 ? '$x\neq'.$x2.'$' : '$x\leq'.$x1.'$');
			$options[]	= (rand(1,2)==1 ? '$x\leq'.$x1.'$ vagy $x\leq'.$x2.'$' : '$x\in\mathbb{R}$');

		} elseif ($no_solutions == 1) {

			switch ($relation) {
				case '\leq':

					$options[0] = ($a>0 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					$options[] 	= ($a<0 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					$options[] 	= (rand(1,2)==1 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= ($a>0 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					break;

				case '<':

					$options[0] = ($a>0 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					$options[] 	= ($a<0 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					$options[] 	= (rand(1,2)==1 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] = ($a>0 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					break;

				case '>':

					$options[0] = ($a<0 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					$options[] 	= ($a>0 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					$options[] 	= (rand(1,2)==1 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= ($a<0 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					break;

				case '\geq':

					$options[0] = ($a<0 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					$options[] 	= ($a>0 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					$options[]	= (rand(1,2)==1 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= ($a<0 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					break;

				
				default:
					# code...
					break;
			}

		} elseif ($no_solutions == 2) {

			switch ($relation) {
				case '\leq':

					$options[0] = ($a>0 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= ($a<0 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= ($a>0 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= (rand(1,2)==1 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');

					break;

				case '<':

					$options[0] = ($a>0 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= ($a<0 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= ($a>0 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= (rand(1,2)==1 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					break;

				case '>':

					$options[0] = ($a<0 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= ($a>0 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= ($a<0 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= (rand(1,2)==1 ? 'Nincs megoldás.' : '$x\neq'.$x1.'$');
					break;

				case '\geq':

					$options[0] = ($a<0 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= ($a>0 ? '$'.$x1.'\leq x\leq'.$x2.'$' : '$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$');
					$options[] 	= ($a<0 ? '$'.$x1.'< x <'.$x2.'$' : '$x<'.$x1.'$ vagy $x<'.$x2.'$');
					$options[] 	= (rand(1,2)==1 ? '$x='.$x1.'$' : '$x\in\mathbb{R}$');
					break;

				
				default:
					# code...
					break;
			}
		}

		shuffleAssoc($options);

		return $options;

	}

	function Equation_val($a, $b, $c, $x) {

		return $a*pow($x,2) + $b*$x + $c;
	}

	function Equation_text($a, $b, $c, $x1, $x2, $progress=0, $relation='=') {

		if ($progress == 0) {

			if (abs($a) == 1) {
				$equation = '$$'.($a > 0 ? '' : '-').'x^2';
			} else {
				$equation = '$$'.strval($a).'\cdot x^2';
			}

			if (abs($b) == 1) {
				$equation .= ($b>0 ? '+' : '-').'x';
			} elseif ($b != 0) {
				$equation .= ($b>0 ? '+' : '').strval($b).'\cdot x';
			}

			$equation .= ($c>0 ? '+' : '').strval($c).$relation.'0$$';
		
		} elseif ($progress == 1) {

			$a2 = ($a >= 0 ? $a : '('.$a.')');
			$b2 = ($b >= 0 ? $b : '('.$b.')');
			$c2 = ($c >= 0 ? $c : '('.$c.')');

			$b3 = -$b;
			$b4 = pow($b,2);
			$a3 = -4*$a*$c;
			$a4 = 2*$a;
			$b5 = $b4+$a3;
			$b6 = round(sqrt($b5));

			$equation = '$$\begin{eqnarray}x_{1,2}'.
				'&=&\frac{-'.$b2.'\pm\sqrt{'.$b2.'^2-4\cdot'.$a2.'\cdot'.$c2.'}}{2\cdot'.$a2.'}\\\\'.
				'&=&\frac{'.$b3.'\pm\sqrt{'.$b4.($a3>0 ? '+' : '').$a3.'}}{'.$a4.'}\\\\'.
				'&=&\frac{'.($b3 ? $b3 : '').'\pm\sqrt{'.$b5.'}}{'.$a4.'}\\\\'
				.($b5>0 ? '&=&\frac{'.($b3 ? $b3 : '').'\pm'.$b6.'}{'.$a4.'}' : '').
				'\end{eqnarray}$$';

		}

		return $equation;
	}

	function Hints($a, $b, $c, $x1, $x2, $relation, $no_solutions) {

		$page[] = 'Először oldjuk meg az alábbi egyenletet:'.
			$this->Equation_text($a, $b, $c, $x1, $x2);

		$page[] = '<div class="alert alert-info"><b>Megoldóképlet:</b><br />Az $a\cdot x^2+b\cdot x+c=0$ alakú egyenlet megoldásai:$$x_{1,2}=\frac{-b\pm\sqrt{b^2-4ac}}{2a}$$</div>';
		$hints[] = $page;

		$hints[][] = 'Behelyettesítve a következő kifejezést kapjuk:'.
			$this->Equation_text($a, $b, $c, $x1, $x2, 1);

		if ($no_solutions == 0) {

			$hints[][] = 'Láthatjuk, hogy a gyökjel alatti kifejezés negatív, ezért ennek a feladatnak <span class="alert alert-success">nincs megoldása</span>.';

		} elseif ($no_solutions == 1) {

			$page = [];
			$page[] = 'Mivel a gyökjel alatti kifejezés értéke $0$, ezért az egyenletnek csak egy megoldása van, az $x='.$x1.'$.';
			$page[] = 'Ábrázoljuk az eredeti egyenlőtlenséget grafikonon!';
			$page[] = 'Mivel a kifejezés másodfokú, ezért a képe egy parabola lesz.';
			$page[] = 'Mivel az $x^2$ együtthatója '.($a>0 ? 'pozitív' : 'negatív').', ezért a parabola '.($a>0 ? 'fölfelé' : 'lefelé').' áll.';
			$page[] = 'Mivel csak egy zérushely van, ezért a parabola csak egy pontban (a zérushelyen) érinti az $x$ tengelyt.';
			$hints[] = $page;

			$hints[][] = 'Ezek alapján már könnyen fel tudjuk rajzolni a grafikon alakját:'.
				$this->Graph($a, $b, $c, $x1, $x2, $relation);

			switch ($relation) {
				case '\leq':
					$hints[][] = 'Mivel csak a nemnegatív ($\leq0$) értékekre vagyunk kíváncsiak, ezért a megoldás '.
						($a>0 ? 'csak '.The($x1).' <span class="label label-success">$x='.$x1.'$</span> lesz.' : 'az egész számegyenes lesz, azaz <span class="label label-success">$x\in\mathbb{R}$</span> (ahol $\mathbb{R}$ a valós számok halmazát jelöli).').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				case '<':
					$hints[][] = 'Mivel csak a negatív ($<0$) értékekre vagyunk kíváncsiak, ezért a feladatnak '.
						($a>0 ? '<span class="label label-success">nincs megoldása</span> megoldás.' : The($x1).' $'.$x1.'$-'.On($x1).' kívül minden szám mgoldása lesz, tehát <span class="label label-success">$x\neq'.$x1.'$</span>.').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				case '>':
					$hints[][] = 'Mivel csak a pozitív ($>0$) értékekre vagyunk kíváncsiak, ezért a feladatnak '.
						($a<0 ? '<span class="label label-success">nincs megoldása</span> megoldás.' : The($x1).' $'.$x1.'$-'.On($x1).' kívül minden szám mgoldása lesz, tehát <span class="label label-success">$x\neq'.$x1.'$</span>.').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				case '\geq':
					$hints[][] = 'Mivel csak a nempozitív ($\geq0$) értékekre vagyunk kíváncsiak, ezért a megoldás '.
						($a<0 ? 'csak '.The($x1).' <span class="label label-success">$x='.$x1.'$</span> lesz.' : 'az egész számegyenes lesz, azaz <span class="label label-success">$x\in\mathbb{R}$</span> (ahol $\mathbb{R}$ a valós számok halmazát jelöli).').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				
				default:
					# code...
					break;
			}

		} elseif ($no_solutions == 2) {

			$page = [];
			$page[] = 'Mivel a gyökjel alatti kifejezés értéke pozitív, ezért az egyenletnek két megoldása van, az $x_1='.$x1.'$ és az $x_2='.$x2.'$.';
			$page[] = 'Ábrázoljuk az eredeti egyenlőtlenséget grafikonon!';
			$page[] = 'Mivel a kifejezés másodfokú, ezért a képe egy parabola lesz.';
			$page[] = 'Mivel az $x^2$ együtthatója '.($a>0 ? 'pozitív' : 'negatív').', ezért a parabola '.($a>0 ? 'fölfelé' : 'lefelé').' áll.';
			$page[] = 'Mivel két zérushely van, ezért a parabola két pontban (a zérushelyeken) metszi az $x$ tengelyt.';
			$hints[] = $page;

			$hints[][] = 'Ezek alapján már könnyen fel tudjuk rajzolni a grafikon alakját:'.
				$this->Graph($a, $b, $c, $x1, $x2, $relation);

			switch ($relation) {
				case '\leq':
					$hints[][] = 'Mivel csak a nemnegatív ($\leq0$) értékekre vagyunk kíváncsiak, ezért a megoldás '.
						($a>0 ? 'a két zérushely közti értékek lesznek (a végpontokkal együtt), azaz <span class="label label-success">$'.$x1.'\leq x\leq'.$x2.'$</span> lesz.' : The($x1).' $'.$x1.'$-'.By($x1).' kisebb vagy azzal egyenlő, illetve '.The($x2).' $'.$x2.'$-'.By($x1).' nagyobb vagy azzal egyenlő számok lesznek, azaz <span class="label label-success">$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$</span>.').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				case '<':
					$hints[][] = 'Mivel csak a negatív ($<0$) értékekre vagyunk kíváncsiak, ezért a feladatnak '.
						($a>0 ? 'a két zérushely közti értékek lesznek (a végpontok nélkül), azaz <span class="label label-success">$'.$x1.'< x <'.$x2.'$</span> lesz.' : The($x1).' $'.$x1.'$-'.By($x1).' kisebb, illetve '.The($x2).' $'.$x2.'$-'.By($x1).' nagyobb számok lesznek, azaz <span class="label label-success">$x<'.$x1.'$ vagy $x<'.$x2.'$</span>.').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				case '>':
					$hints[][] = 'Mivel csak a pozitív ($>0$) értékekre vagyunk kíváncsiak, ezért a feladatnak '.
						($a<0 ? 'a két zérushely közti értékek lesznek (a végpontok nélkül), azaz <span class="label label-success">$'.$x1.'< x <'.$x2.'$</span> lesz.' : The($x1).' $'.$x1.'$-'.By($x1).' kisebb, illetve '.The($x2).' $'.$x2.'$-'.By($x1).' nagyobb számok lesznek, azaz <span class="label label-success">$x<'.$x1.'$ vagy $x<'.$x2.'$</span>.').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				case '\geq':
					$hints[][] = 'Mivel csak a nempozitív ($\geq0$) értékekre vagyunk kíváncsiak, ezért a megoldás '.
						($a<0 ? 'a két zérushely közti értékek lesznek (a végpontokkal együtt), azaz <span class="label label-success">$'.$x1.'\leq x\leq'.$x2.'$</span> lesz.' : The($x1).' $'.$x1.'$-'.By($x1).' kisebb vagy azzal egyenlő, illetve '.The($x2).' $'.$x2.'$-'.By($x1).' nagyobb vagy azzal egyenlő számok lesznek, azaz <span class="label label-success">$x\leq'.$x1.'$ vagy $x\geq'.$x2.'$</span>.').
						$this->Graph($a, $b, $c, $x1, $x2, $relation, 1);
					break;

				
				default:
					# code...
					break;
			}

		}

		return $hints;
	}

	function Graph($a, $b, $c, $x1, $x2, $relation, $progress=0) {

		$padding1 	= 20;	// Padding left/bottom
		$padding2 	= 20;	// Padding right/top

		$x_avg = ($x1+$x2)/2;
		$x_min = min(0, floor($x_avg)-2, $x1-1);
		$x_max = max(0, ceil($x_avg)+2, $x2+1);

		$y_avg = $this->Equation_val($a,$b,$c,$x_avg);
		$diff  = (abs($y_avg) > 4 ? 1 : 2);
		$y_max = $this->Equation_val($a,$b,$c,$x1-$diff);

		$y_min = ($a > 0 ? floor($y_avg) : $y_max);
		$y_max = ($a > 0 ? $y_max : ceil($y_avg));

		$height = 300;
		$width 	= 400;
		$unitx 	= ($width-$padding1-$padding2)/($x_max-$x_min);
		$unity 	= ($height-$padding1-$padding2)/($y_max-$y_min);
		$unit 	= max(30, min($unitx, $unity));
		$width 	= $unit * ($x_max-$x_min) + $padding1 + $padding2;
		$height = $unit * ($y_max-$y_min) + $padding1 + $padding2;

		$originx	= $padding1+$unit*abs($x_min);
		$originy 	= $padding2+$unit*$y_max;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';
		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		// Draw guides
		$xpos 	= $padding1;
		while ($xpos <= $width-$padding2) {
			if ($xpos != $originx) {
				$svg .= DrawLine($xpos, 0, $xpos, $height, '#F2F2F2');
			}
			$xpos	+= $unit;
		}
		$ypos 	= $padding2;
		while ($ypos <= $height-$padding1) {
			if ($ypos != $originy) {
				$svg .= DrawLine(0, $ypos, $width, $ypos, '#F2F2F2');
				
			}
			$ypos += $unit;
		}

		// Draw axes
		$svg .= DrawLine(0, $originy, $width, $originy);
		$svg .= DrawLine($originx, 0, $originx, $height);

		// Draw arrows
		$svg .= DrawLine($width, $originy, $width-7, $originy-7);
		$svg .= DrawLine($width, $originy, $width-7, $originy+7);
		$svg .= DrawLine($originx, 0, $originx-7, 7);
		$svg .= DrawLine($originx, 0, $originx+7, 7);

		// Draw units
		$xpos 	= $padding1;
		$xval 	= $x_min;
		while ($xpos <= $width-$padding2) {
			if ($xpos != $originx) {
				$svg .= DrawLine($xpos, $originy-5, $xpos, $originy+5);
				$svg .= DrawText($xpos+5, $originy+17, $xval);
			}
			
			$xpos	+= $unit;
			$xval 	+= 1;
		}
		$ypos 	= $padding2;
		$yval	= $y_max;
		while ($ypos <= $height-$padding1) {
			if ($ypos != $originy) {
				$svg .= DrawLine($originx-5, $ypos, $originx+5, $ypos);
				
			}
			$svg .= DrawText($originx+10, $ypos-5, $yval);
			$ypos += $unit;
			$yval -= 1;
		}

		// Draw function
		for ($i=0; $i < $width; $i++) {
			$xval1 = ($i-$originx)/$unit;
			$xval2 = ($i+1-$originx)/$unit;
			$yval1 = -$this->Equation_val($a,$b,$c,$xval1)*$unit+$originy;
			$yval2 = -$this->Equation_val($a,$b,$c,$xval2)*$unit+$originy;

			$color = ($progress == 0 ? 'red' : 'blue');
			$svg .= DrawLine($i, $yval1, $i+1, $yval2, $color, 2);
		}

		if ($progress == 1) {

			// Calculate abscissas for zero points
			$x_zero1 = $x1 * $unit + $originx;
			$x_zero2 = $x2 * $unit + $originx;

			// Draw solution (segment)
			if (($a>0 && in_array($relation, ['\leq','<'])) ||
				($a<0 && in_array($relation, ['\geq','>']))) {
				$svg .= DrawLine($x_zero1, $originy, $x_zero2, $originy, $color='red', $width=2);
			} else {
				$svg .= DrawLine($x_zero2, $originy, $width, $originy, $color='red', $width=2);
				$svg .= DrawLine(0, $originy, $x_zero1, $originy, $color='red', $width=2);
			}

			// Draw solution (end points)
			if ($x1 != $x2) {
				$color = (in_array($relation, ['<','>']) ? 'white' : 'red');

				$svg .= DrawCircle($x_zero1, $originy, 4, 'red', 2, $color);
				$svg .= DrawCircle($x_zero2, $originy, 4, 'red', 2, $color);
			}

		}

		$svg .= '</svg></div>';

		return $svg;
	}

}

?>