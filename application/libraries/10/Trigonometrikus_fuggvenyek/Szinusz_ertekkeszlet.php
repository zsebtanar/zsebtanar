<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szinusz_ertekkeszlet {

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

		$a = rand(-$level, $level);
		$b = rand(-$level, $level);

		if ($level <= 3) {
			$a = rand(-$level, $level);
			$b = 1;
		} elseif ($level <= 6) {
			$a = 0;
			$b = pow(-1, rand(0,1)) * rand(3, $level);
		} else {
			$a = pow(-1, rand(0,1)) * rand(3, $level);
			$b = pow(-1, rand(0,1)) * rand(3, $level);
		}

		if ($a == 0) {
			$question = 'Adja meg a valós számok halmazán értelmezett $f(x)='.($b==1 ? '' : $b).'\sin x$ függvény értékkészletét!';
		} else {
			if ($b == 1) {
				$question = 'Adja meg a valós számok halmazán értelmezett $f(x)='.$a.'+\sin x$ függvény értékkészletét!';
			} else {
				$question = 'Adja meg a valós számok halmazán értelmezett $f(x)='.$a.($b>0 ? '+'.$b : $b).'\sin x$ függvény értékkészletét!';
			}
		}

		$range_from = $a-abs($b);
		$range_to 	= $a+abs($b);

		$hints 		= $this->Hints($a, $b);
		$correct 	= array($range_from, $range_to);
		$solution 	= '$['.$range_from.';'.$range_to.']$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'type'		=> 'range'
		);
	}

	function Hints($a, $b) {

		$value_min = min(-1, -$b, $a-abs($b));
		$value_max = max(1, $b, $a+abs($b));

		$hints[][] = 'Rajzoljuk fel először az $f(x)=\sin x$ függvényt!'
			.$this->DrawSineFunction(0, 1, 0, 0, $value_min, $value_max);

		$hints[][] = 'Az $f(x)=\sin x$ függvény $-1$ és $1$ közötti értékeket vesz fel, ezért az értékészlete $[-1;1]$ lesz.'
			.$this->DrawSineFunction(0, 1, 0, 0, $value_min, $value_max, 1);

		if ($b != 1) {
			$hints[][] = 'Most nézzük meg az $f(x)='.$b.'\sin x$ függvényt! Ezt úgy kapjuk, hogy az előző függvényt "megnyújtjuk" '
				.'$'.abs($b).'$-'.With(abs($b)).($b > 0 ? ':' : ', és tükrözzük az $y$ tengelyre:')
				.$this->DrawSineFunction(0, $b, 0, 1, $value_min, $value_max);

			$hints[][] = 'Az $f(x)='.$b.'\sin x$ függvény értékkészletét úgy kapjuk meg, hogy az eredeti függvény értékkészletének végpontjait megszorozzuk $'
				.strval(abs($b)).'$-'.With(abs($b)).'. Ezért az új függvény értékkészlete '
				.'$[-1\cdot'.abs($b).';1\cdot'.abs($b).']=[-'.abs($b).';'.abs($b).']$ lesz.'
				.($a == 0 ? ' Tehát a megoldás <span class="label label-success">$[-'.abs($b).';'.abs($b).']$</span>.' : '')
				.$this->DrawSineFunction(0, $b, 0, 1, $value_min, $value_max, 1);
		}

		if ($a != 0) {
			$hints[][] = 'Most nézzük meg az $f(x)='.$a.'+'.($b != 1 ? $b : '').'\sin x$ függvényt! '
				.'Ezt úgy kapjuk, hogy az előző függvényt "eltoljuk" '.($a>0 ? 'fölfelé' : 'lefelé').' $'.$a.'$-'.With($a).':'
				.$this->DrawSineFunction($a, $b, 0, $b, $value_min, $value_max);
			$hints[][] = 'Az $f(x)='.$a.'+'.($b != 1 ? $b : '').'\sin x$ függvény értékkészletét úgy kapjuk meg, hogy az előző függvény értékkészletének végpontjaihoz hozzáadunk $'
				.$a.'$-'.Dativ($a).'. Ezért az új függvény értékkészlete $[-'.abs($b).($a > 0 ? '+'.$a : $a).';'.abs($b).($a > 0 ? '+'.$a : $a).']=['.strval(-abs($b)+$a).';'.strval(abs($b)+$a).']$ lesz.'
				.' Tehát a megoldás <span class="label label-success">$['.strval(-abs($b)+$a).';'.strval(abs($b)+$a).']$</span>.'
				.$this->DrawSineFunction($a, $b, 0, $b, $value_min, $value_max, 1);
		}

		return $hints;
	}

	// Draw sine function (a+bsin(cx))
	function DrawSineFunction($a1=0, $b1=1, $a2=0, $b2=0, $value_min=-1, $value_max=1, $drawRange=0) {

		$width 	= 400;
		$height = 300;

		$xunits_neg = -2;	// Number of units on x-axis (unit length: pi/2)
		$xunits_pos = 2;

		$range = $value_max - $value_min;

		if ($range > 2) {
			$yunits_neg	= $value_min;	// Number of units on y-axis (unit length: 1)
			$yunits_pos	= $value_max;
			$yunit_original = 1;
		} else {
			$yunits_neg	= $value_min*2;	// Number of units on y-axis (unit length: 1/2)
			$yunits_pos	= $value_max*2;
			$yunit_original = 1/2;
		}

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
		$svg .= $this->DrawGuides($a1, $b1, $yunit_length, $yunit_original, $originy, $width);
		if ($b2) {
			$svg .= $this->DrawGuides($a2, $b2, $yunit_length, $yunit_original, $originy, $width);
		}

		// Draw function
		for ($i=0; $i < $width; $i++) {
			$xval1 = ($i-$originx)/$xunit_length*pi()/2;
			$xval2 = ($i+1-$originx)/$xunit_length*pi()/2;
			$yval1 = (-$a1+$b1*sin($xval1))*$yunit_length/$yunit_original+$originy;
			$yval2 = (-$a1+$b1*sin($xval2))*$yunit_length/$yunit_original+$originy;
			$svg .= DrawLine($i, $yval1, $i+1, $yval2, 'red', 2);
		}
		if ($b2) {
			for ($i=0; $i < $width; $i++) {
				$xval1 = ($i-$originx)/$xunit_length*pi()/2;
				$xval2 = ($i+1-$originx)/$xunit_length*pi()/2;
				$yval1 = (-$a2+$b2*sin($xval1))*$yunit_length/$yunit_original+$originy;
				$yval2 = (-$a2+$b2*sin($xval2))*$yunit_length/$yunit_original+$originy;
				$svg .= DrawLine($i, $yval1, $i+1, $yval2, 'grey', 1);
			}
		}

		// Draw range
		if ($drawRange) {
			$svg .= $this->DrawRange($a1, $b1, $yunit_length, $yunit_original, $originy, $width);
		}


		$svg .= '</svg></div>';

		return $svg;
	}

	function DrawPath($yval, $width) {

		$svg = '<g fill="none" stroke="grey" stroke-width="1"><path stroke-dasharray="5,5" d="M0 '.$yval.' l'.$width.' 0" /></g>';

		return $svg;
	}

	function DrawText($x, $y, $text) {

		$svg = '<text font-size="10" x="'.$x.'" y="'.$y.'" fill="black">$'.$text.'$</text>';

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

	function DrawGuides($a1, $b1, $yunit_length, $yunit_original, $originy, $width) {

		$svg = '';

		if (-$a1 != 0) {
			$yval = -$a1*$yunit_length/$yunit_original+$originy;
			$svg .= $this->DrawPath($yval, $width);
		}
		if (-$a1-$b1 != 0) {
			$yval = (-$a1-$b1)*$yunit_length/$yunit_original+$originy;
			$svg .= $this->DrawPath($yval, $width);
		}
		if (-$a1+$b1 != 0) {
			$yval = (-$a1+$b1)*$yunit_length/$yunit_original+$originy;
			$svg .= $this->DrawPath($yval, $width);
		}

		return $svg;
	}

	function DrawRange($a1, $b1, $yunit_length, $yunit_original, $originy, $width) {

		$yval1 = (-$a1-abs($b1))*$yunit_length/$yunit_original+$originy;
		$yval2 = (-$a1+abs($b1))*$yunit_length/$yunit_original+$originy;
		$xval = $width/3;

		$svg = DrawLine($xval, $yval1, $xval, $yval2, 'blue', 2);
		$svg .= DrawLine($xval, $yval1, $xval-5, $yval1+5, 'blue', 2);
		$svg .= DrawLine($xval, $yval1, $xval+5, $yval1+5, 'blue', 2);
		$svg .= DrawLine($xval, $yval2, $xval-5, $yval2-5, 'blue', 2);
		$svg .= DrawLine($xval, $yval2, $xval+5, $yval2-5, 'blue', 2);
		return $svg;
	}
}

?>