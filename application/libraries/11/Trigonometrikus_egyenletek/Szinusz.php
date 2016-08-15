<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szinusz {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	// Define range of sine function (a+bsin(x))
	function Generate($level) {

		$a_options = [2, 1, 0.5];
		$b_options = [-2, -1, 0, 1, 2];
		shuffle($a_options);
		shuffle($b_options);

		// sin(a*x) = b
		$a = $a_options[0];
		$b = $b_options[0];
		$a = ($level <= 3 ? 1 : $a);

		// // Original exercise
		// $a_options = [1, 0.5, 2];
		// $b_options = [1, -2, -1, 0, 2];
		// $a = $a_options[0];
		// $b = $b_options[0];

		$question = 'Oldja meg a $'.$this->SinFunctionText($a).'='.$b.'$ egyenletet a valós számok halmazán! Válassza ki a jó megoldást az alábbiak közül:';

		$hints = $this->Hints($a, $b);

		$options = $this->Options($a_options, $b_options);
		ShuffleAssoc($options);

		$correct = 0;

		$solution = $correct;

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'options'	=> $options,
			'solution'	=> $solution,
			'hints'		=> $hints
		);
	}

	function SinFunctionText($a) {
		if ($a == 1) {
			return '\sin x';
		} elseif ($a == 2) {
			return '\sin 2x';
		} elseif ($a == 0.5) {
			return '\sin \frac{x}{2}';
		} else {
			return '\sin '.strval($a).'x';
		}
	}

	function Hints($a, $b) {

		$hints[][] = 'Rajzoljuk fel először az $f(x)=\sin x$ függvényt!'
			.$this->DrawSineFunction($a, $b, 0);

		if ($a != 1) {

			$hints[][] = 'Most rajzoljuk fel az $f(x)='.$this->SinFunctionText($a).'$ függvényt! Ezt úgy kapjuk meg, ha az eredeti függvényt kétszeresére '.($a==2 ? '"zsugorítjuk"' : '"nyújtjuk"').' az $x$ tengely mentén:'
				.$this->DrawSineFunction($a, $b, 1);

		}

		if (abs($b) > 1) {

			$hints[][] = 'Látjuk, hogy a függvény sosem veszi fel '.The($b).' $'.$b.'$ értéket, ezért a feladatnak <span class="label label-success">nincs megoldása</span>.'.$this->DrawSineFunction($a, $b, 2);;

		} else {

			$sol = $this->Solution($a,$b,FALSE);
			$per = $this->Period($a,$b,FALSE);

			$hints[][] = 'Nézzük meg, hogy a függvény melyik pontokban veszi fel  '.The($b).' $'.$b.'$ értéket:'
				.$this->DrawSineFunction($a, $b, 2)
				;

			$hints[][] = 'Az ábráról könnyen leolvasható, hogy a függvény a $'.$sol.'$ helyen veszi fel '.' '.The($b).' $'.$b.'$-'.Dativ($b).', és az is, hogy a megoldások közti távolság $'.$per.'$.'
				.$this->DrawSineFunction($a, $b, 3)
				;

			$hints[][] = 'Tehát a megoldás <span class="label label-success">$'.($sol == '0' ? '' : $sol.'+').'k\cdot'.$per.'$</span>.'.$this->DrawSineFunction($a, $b, 3);
		}

		return $hints;
	}

	// Generate options for exercise
	function Options($a_options, $b_options) {

		$a = $a_options[0];
		$b = $b_options[0];

		$a2 = $a_options[1];
		$b2 = ($b == 0 ? 1 : -$b);

		if (abs($b) > 1) {

			$sol1 = $this->Solution($a, 1);
			$sol2 = $this->Solution($a, -1);
			$sol3 = $this->Solution($a2, 1);
			$sol4 = $this->Solution($a2, -1);

			$per1 = $this->Period($a, 1);
			$per2 = $this->Period($a, -1);
			$per3 = $this->Period($a2, 1);
			$per4 = $this->Period($a2, -1);

			return array(
				'Nincs megoldás.',
				'$'.($sol1 == '0' ? '' : $sol1.'+').'k\cdot'.$per1.'$',
				'$'.($sol2 == '0' ? '' : $sol2.'+').'k\cdot'.$per2.'$',
				'$'.($sol3 == '0' ? '' : $sol3.'+').'k\cdot'.$per3.'$',
				'$'.($sol4 == '0' ? '' : $sol4.'+').'k\cdot'.$per4.'$',
			);

		} else {

			$sol1 = $this->Solution($a, $b);
			$sol2 = $this->Solution($a, $b2);
			$sol3 = $this->Solution($a2, $b);
			$sol4 = $this->Solution($a2, $b2);

			$per1 = $this->Period($a, $b);
			$per2 = $this->Period($a, $b2);
			$per3 = $this->Period($a2, $b);
			$per4 = $this->Period($a2, $b2);

			return array(
				'$'.($sol1 == '0' ? '' : $sol1.'+').'k\cdot'.$per1.'$',
				'$'.($sol2 == '0' ? '' : $sol2.'+').'k\cdot'.$per2.'$',
				'$'.($sol3 == '0' ? '' : $sol3.'+').'k\cdot'.$per3.'$',
				'$'.($sol4 == '0' ? '' : $sol4.'+').'k\cdot'.$per4.'$',
				'Nincs megoldás.'
			);

		}
	}

	// Calculate solution for sin(ax)=b
	function Solution($a, $b, $inline=TRUE) {
		if ($b == 0) {
			$sol = '0';
		} elseif (abs($b) == 1) {
			if ($a == 1) {
				$sol = ($inline ? '\pi/2' : '\frac{\pi}{2}');
			} elseif ($a == 2) {
				$sol = ($inline ? '\pi/4' : '\frac{\pi}{4}');
			} elseif ($a == 0.5) {
				$sol = '\pi';
			}
		} else {
			$sol = '';
		}

		return ($b == 1 ? $sol : '-'.$sol);
	}

	// Calculate period of solution for sin(ax)=b
	function Period($a, $b, $inline=TRUE) {
		if ($b == 0) {
			if ($a == 1) {
				$sol = '\pi';
			} elseif ($a == 2) {
				$sol = ($inline ? '\pi/2' : '\frac{\pi}{2}');
			} elseif ($a == 0.5) {
				$sol = '2\pi';
			}
		} elseif (abs($b) == 1) {
			if ($a == 1) {
				$sol = '2\pi';
			} elseif ($a == 2) {
				$sol = '\pi';
			} elseif ($a == 0.5) {
				$sol = '4\pi';
			}
		} else {
			$sol = 'Nincs megoldás.';
		}

		return $sol;
	}

	// Draw sine function (sin(ax)=b)
	function DrawSineFunction($a, $b, $progress=0) {

		$width 	= 600;
		$height = 300;

		$xunits_neg = -4;	// Number of units on x-axis (unit length: pi/2)
		$xunits_pos = 4;

		$yunits_neg	= -2;	// Number of units on y-axis (unit length: 1/2)
		$yunits_pos	= 2;
		$yunit_original = 1/2;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';
		// $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		// Draw axes
		$padding1 		= 10;	// Padding left/bottom
		$padding2 		= 30;	// Padding right/top
		$xunit_length 	= ($width-$padding1-$padding2)/($xunits_pos-$xunits_neg);
		$yunit_length 	= ($height-$padding1-$padding2)/($yunits_pos-$yunits_neg);
		$originx		= $padding1+$xunit_length*abs($xunits_neg);
		$originy 		= $padding2+$yunit_length*$yunits_pos;
		$svg .= DrawLine(0, $originy, $width, $originy);
		$svg .= DrawLine($originx, 0, $originx, $height);

		// Draw arrows
		$svg .= DrawLine($width, $originy, $width-7, $originy-7);
		$svg .= DrawLine($width, $originy, $width-7, $originy+7);
		$svg .= DrawLine($originx, 0, $originx-7, 7);
		$svg .= DrawLine($originx, 0, $originx+7, 7);

		// Draw units
		$xpos 	= $padding1;
		$xval 	= $xunits_neg*pi()/2;
		while ($xpos <= $width-$padding2) {
			if ($xpos != $originx) {
				$svg .= DrawLine($xpos, $originy-5, $xpos, $originy+5);
			}
			$xtext 	= $this->Xvalue2Fraction($xval);
			$svg 	.= DrawText($xpos, $originy+20, $xtext);
			$xpos	+= $xunit_length;
			$xval 	+= pi()/2;
		}
		$ypos 	= $padding2;
		$yval	= $yunits_pos*$yunit_original;
		while ($ypos <= $height-$padding1) {
			if (round($ypos) != round($originy)) {
				$ytext	= $this->Yvalue2Fraction($yval);
				$svg 	.= DrawLine($originx-5, $ypos, $originx+5, $ypos);
				$svg 	.= DrawText($originx+20, $ypos-5, $ytext);
			}
			$ypos += $yunit_length;
			$yval -= $yunit_original;
		}

		// Draw guides
		$svg .= $this->DrawGuides(1, $yunit_length, $yunit_original, $originy, $width);
		$svg .= $this->DrawGuides(-1, $yunit_length, $yunit_original, $originy, $width);

		// Draw function
		if ($progress == 0) {
			
			for ($i=0; $i < $width; $i++) {
				$xval1 = ($i-$originx)/$xunit_length*pi()/2;
				$xval2 = ($i+1-$originx)/$xunit_length*pi()/2;
				$yval1 = -sin($xval1)*$yunit_length/$yunit_original+$originy;
				$yval2 = -sin($xval2)*$yunit_length/$yunit_original+$originy;
				$svg .= DrawLine($i, $yval1, $i+1, $yval2, 'red', 2);
			}

		} elseif ($progress == 1) {

			for ($i=0; $i < $width; $i++) {
				$xval1 = ($i-$originx)/$xunit_length*pi()/2;
				$xval2 = ($i+1-$originx)/$xunit_length*pi()/2;
				$yval1 = -sin($xval1)*$yunit_length/$yunit_original+$originy;
				$yval2 = -sin($xval2)*$yunit_length/$yunit_original+$originy;
				$svg .= ($i%3 == 0 ? DrawLine($i, $yval1, $i+1, $yval2, 'blue', 1) : '');
			}
			
			for ($i=0; $i < $width; $i++) {
				$xval1 = ($i-$originx)/$xunit_length*pi()/2;
				$xval2 = ($i+1-$originx)/$xunit_length*pi()/2;
				$yval1 = -sin($a*$xval1)*$yunit_length/$yunit_original+$originy;
				$yval2 = -sin($a*$xval2)*$yunit_length/$yunit_original+$originy;
				$svg .= DrawLine($i, $yval1, $i+1, $yval2, 'red', 2);
			}

		} else {

			for ($i=0; $i < $width; $i++) {
				$xval1 = ($i-$originx)/$xunit_length*pi()/2;
				$xval2 = ($i+1-$originx)/$xunit_length*pi()/2;
				$yval1 = -sin($a*$xval1)*$yunit_length/$yunit_original+$originy;
				$yval2 = -sin($a*$xval2)*$yunit_length/$yunit_original+$originy;
				$svg .= DrawLine($i, $yval1, $i+1, $yval2, 'blue', 2);
			}

			for ($i=0; $i < $width; $i++) {
				$xval1 = ($i-$originx)/$xunit_length*pi()/2;
				$yval1 = -sin($a*$xval1)*$yunit_length/$yunit_original+$originy;
				$yval2 = round(-$yunit_length/$yunit_original)+$originy;
				if (abs(sin($a*$xval1)-$b) < 0.00001) {
					$svg .= DrawCircle($i, $yval1, 5, 'red', 1, 'red');
					if ($progress == 3 && $b != 0) {
						$svg .= '<g fill="none" stroke="red" stroke-width="2"><path stroke-dasharray="5,5" d="M'.$i.' '.$originy.' l0 '.round(-$yunit_length/$yunit_original).'" /></g>';

						// for some mysterious reasons this is not working... (produces the same, though):
						// $svg .= DrawPath($i, $originy, $i, $yval2, $color1='red', $width=2, $color2='none', $dasharray1=5, $dasharray2=5);
					}
				}
			}

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	// Turns number (k*pi/2) into fraction
	function Xvalue2Fraction($num) {

		$pimult = round($num/pi()*2);
		// print_r($pimult.' ');

		$isfrac = (abs($pimult) % 2 == 1 ? 1 : 0);
		// print_r($isfrac.' ');

		if ($isfrac) {
			if ($pimult == -1) {
				$frac = '-&pi;/2';
			} elseif ($pimult == 1) {
				$frac = '&pi;/2';
			} else {
				$frac = $pimult.'&pi;/2';
			}
		} else {
			if ($pimult/2 == -1) {
				$frac = '-&pi;';
			} elseif ($pimult/2 == 1) {
				$frac = '&pi;';
			} elseif ($pimult == 0) {
				$frac = 0;
			} else {
				$frac = strval($pimult/2).'&pi;';
			}
		}

		return $frac;
	}

	// Turns number (k*1/2) into fraction
	function Yvalue2Fraction($num) {

		$mult = round($num*2);

		$isfrac = (abs($mult) % 2 == 1 ? 1 : 0);

		if ($isfrac) {
			if ($mult == -1) {
				$frac = '-1/2';
			} elseif ($mult == 1) {
				$frac = '1/2';
			} else {
				$frac = $mult.'/2';
			}
		} else {
			$frac = $mult/2;
		}

		return $frac;
	}

	function DrawGuides($a, $yunit_length, $yunit_original, $originy, $width) {

		$svg = '';

		if ($a != 0) {
			$yval = -$a*$yunit_length/$yunit_original+$originy;
			$svg = '<g fill="none" stroke="grey" stroke-width="1"><path stroke-dasharray="5,5" d="M0 '.$yval.' l'.$width.' 0" /></g>';
		}

		return $svg;
	}
}

?>