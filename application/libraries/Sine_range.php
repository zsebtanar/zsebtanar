<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sine_range {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		
		return;
	}

	// Define range of sine function (a+bsin(cx))
	function Generate($level) {

		$num = rand(max(0,2*($level-2)), min(20,3*$level));

		$question = $this->DrawSineFunction();
		$correct = $num;
		$solution = '$'.$correct.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution
		);
	}

	// Draw sine function (a+bsin(cx))
	function DrawSineFunction($a=0, $b=1, $c=1, $xmin=-3.14, $xmax=3.14, $ymin=-1, $ymax=1) {

		$width 	= 400;
		$height = 300;

		$xmin 	= round($xmin/pi()*2);	// Unit on x-axis: pi/2
		$xmax 	= round($xmax/pi()*2); 
		$ymin	= 2*$ymin;				// Unit on y-axis: 1/2
		$ymax	= 2*$ymax;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">';
		$svg .= '<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />';

		// Draw axes
		$padding1 		= 10;	// Padding left/bottom
		$padding2 		= 30;	// Padding right/top
		$unit_lengthx 	= ($width-$padding1-$padding2)/($xmax-$xmin);
		$unit_lengthy 	= ($height-$padding1-$padding2)/($ymax-$ymin);
		$originx		= $padding1+$unit_lengthx*abs($xmin);
		$originy 		= $padding2+$unit_lengthy*$ymax;
		$svg .= $this->DrawLine(0, $originy, $width, $originy);
		$svg .= $this->DrawLine($originx, 0, $originx, $height);

		// Draw arrows
		$svg .= $this->DrawLine($width, $originy, $width-7, $originy-7);
		$svg .= $this->DrawLine($width, $originy, $width-7, $originy+7);
		$svg .= $this->DrawLine($originx, 0, $originx-7, 7);
		$svg .= $this->DrawLine($originx, 0, $originx+7, 7);

		// Draw units
		$xpos 	= $padding1;
		$xval 	= $xmin*pi()/2;
		while ($xpos <= $width-$padding2) {
			if ($xpos != $originx) {
				$xtext 	= $this->Xvalue2Fraction($xval);
				$svg 	.= $this->DrawLine($xpos, $originy-5, $xpos, $originy+5);
				$svg 	.= $this->DrawText($xpos, $originy+15, $xtext);
			}
			$xpos	+= $unit_lengthx;
			$xval 	+= pi()/2;
		}
		$ypos 	= $padding2;
		$yval	= $ymin/2;
		while ($ypos <= $height-$padding1) {
			if ($ypos != $originy) {
				$ytext	= $this->Yvalue2Fraction($yval);
				$svg 	.= $this->DrawLine($originx-5, $ypos, $originx+5, $ypos);
				$svg 	.= $this->DrawText($originx+10, $ypos, $ytext);
			}
			$ypos += $unit_lengthy;
			$yval 	+= 1/2;
		}

		// Draw function
		for ($i=0; $i < $width; $i++) { 
			$xval1 = ($i-$originx)/$unit_lengthx*pi()/2;
			$xval2 = ($i+1-$originx)/$unit_lengthx*pi()/2;
			$yval1 = ($a+$b*sin($c*$xval1))*$unit_lengthy*2+$originy;
			$yval2 = ($a+$b*sin($c*$xval2))*$unit_lengthy*2+$originy;
			$svg .= $this->DrawLine($i, $yval1, $i+1, $yval2, 'red');
		}


		$svg .= '</svg></div>';

		return $svg;
	}

	function DrawLine($x1, $y1, $x2, $y2, $color='black') {

		$svg = '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="'.$color.'" stroke-width="1" />';

		return $svg;
	}

	function DrawText($x, $y, $text) {

		$svg = '<text font-size="15" x="'.$x.'" y="'.$y.'" fill="black">'.$text.'</text>';

		return $svg;
	}

	// Turns number (k*pi/2) into fraction
	function Xvalue2Fraction($num) {

		$pimult = round($num/pi()*2);
		print_r($pimult.' ');

		$isfrac = (abs($pimult) % 2 == 1 ? 1 : 0);
		print_r($isfrac.' ');

		if ($isfrac) {
			if ($pimult == -1) {
				$frac = '-pi/2';
			} elseif ($pimult == 1) {
				$frac = 'pi/2';
			} else {
				$frac = $pimult.'pi/2';
			}
		} else {
			if ($pimult/2 == -1) {
				$frac = '-pi';
			} elseif ($pimult/2 == 1) {
				$frac = 'pi';
			} else {
				$frac = strval($pimult/2).'pi';
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
				$frac = $mult.'1/2';
			}
		} else {
			$frac = $mult/2;
		}

		return $frac;
	}
}

?>