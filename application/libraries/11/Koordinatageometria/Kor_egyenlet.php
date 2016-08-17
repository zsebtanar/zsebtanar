<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kor_egyenlet {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		list($A, $B, $axis) = $this->Exercise($level);

		// // Original exercise
		// $A = [-3,-1];
		// $B = [3,7];
		// $axis = 'x';

		list($C, $r, $P1, $P2) = $this->Solution($A, $B, $axis);

		$question = 'Adott az $A('.$A[0].';'.$A[1].')$ és a $B('.$B[0].';'.$B[1].')$ pont. Számítsa ki, hogy az $'.$axis.'$ tengely melyik pontjából látható derékszögben az $AB$ szakasz!';
		$hints = $this->Hints($A, $B, $axis);
		$correct = [$P1, $P2];
		$solution = '$P_1('.$P1[0].';'.$P1[1].')$, $P_2('.$P2[0].';'.$P2[1].')$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> 'coordinatelist',
			'labels'	=> ['$P_1$', '$P_2$']
		);
	}

	function Exercise($level) {

		$axis = (rand(1,2)==1 ? 'x' : 'y');

		// Calculate distances
		// dist1 : distance between circle and axis
		// dist2 : distance between circle and point
		if ($level <= 3) {
			list($dist1,$dist2) = (rand(1,2)==1 ? [3,4] : [4,3]);
		} elseif ($level <= 6) {
			list($dist1,$dist2) = (rand(1,2)==1 ? [5,12] : [12,5]);
		} else {
			list($dist1,$dist2) = (rand(1,2)==1 ? [8,15] : [15,8]);
		}

		$shift = rand(-3,3); // shift circle along axis

		// // Original exercise
		// $axis = 'x';
		// $shift = 0;
		// $dist1 = 3;
		// $dist2 = 4;

		$A = ($axis == 'x' ? [$shift-$dist1,$dist1-$dist2] : [$dist1-$dist2,$shift-$dist1]);
		$B = ($axis == 'x' ? [$shift+$dist1,$dist2+$dist1] : [$dist2+$dist1,$shift+$dist1]);

		return [$A, $B, $axis];
	}

	function Hints($A, $B, $axis) {

		list($C, $r, $P1, $P2) = $this->Solution($A, $B, $axis);

		$page[] = '<div class="alert alert-info"><b>Thalész-tétel megfordítása</b><br />Ha egy $AB$ szakasz valamely $P$ pontból derékszögben látszik, akkor az $AB$ átmérőjű körnek egyik pontja a $P$ pont.</div>';
		$page[] = 'A kérdéses pontot $P$-vel jelölve (a fenti tétel miatt) az $ABP$ háromszög köré írt körének átmérője az $AB$ szakasz.';
		$page[] = 'A kör és az $'.$axis.'$ tengely metszéspontja a $P$ pont.';
		$page[] = 'Ábrázoljuk az $A$ és $B$ pontokat:'.$this->Graph($A, $B, $axis, 1);
		$hints[] = $page;

		$page = [];
		$page[] = 'A kör középpontja az $AB$ szakasz felezőpontja:$$\left(\frac{'.$A[0].($B[0]<0 ? '' : '+').$B[0].'}{2};\frac{'.$A[1].($B[1]<0 ? '' : '+').$B[1].'}{2}\right)=('.$C[0].';'.$C[1].')$$'.$this->Graph($A, $B, $axis, 2);
		$hints[] = $page;

		$page = [];
		$page[] = 'A kör sugara az $AB$ szakasz hosszának fele:$$\begin{eqnarray}
			r=\frac{AB}{2}&=&\frac{\sqrt{('.$A[0].(-$B[0]<0 ? '' : '+').strval(-$B[0]).')^2+('.$A[1].(-$B[1]<0 ? '' : '+').strval(-$B[1]).')^2}}{2}\\\\
			&=&\frac{\sqrt{('.strval($A[0]-$B[0]).')^2+('.strval($A[1]-$B[1]).')^2}}{2}\\\\
			&=&\frac{\sqrt{'.pow($A[0]-$B[0],2).'+'.pow($A[1]-$B[1],2).'}}{2}='.$r.'\end{eqnarray}$$';
		$page[] = 'A háromszög köré írható kör egyenlete:$$'.($C[0]==0 ? 'x^2' : '(x'.($C[0]<0 ? '' : '+').$C[0].')^2').'+'.($C[1]==0 ? 'y^2' : '(y'.($C[1]<0 ? '' : '+').$C[1].')^2').'='.($r<0 ? '('.$r.')^2' : $r.'^2').'$$'.$this->Graph($A, $B, $axis, 3);
		$hints[] = $page;

		$page = [];
		$page[] = 'A kör $'.$axis.'$ tengellyel való metszéspontját az $'.($axis=='x' ? 'y' : 'x').'=0$ helyettesítéssel kapjuk, így $$'.
				($axis=='y' ?
					($C[0]<0 ?
						'('.$C[0].')^2' : 
						$C[0].'^2'
					) :
					($C[0]==0 ?
						'x^2' :
						'(x'.($C[0]<0 ? '' : '+').$C[0].')^2'
					)
				).
				'+'.
				($axis=='x' ?
					($C[1]<0 ?
						'('.$C[1].')^2' : 
						$C[1].'^2'
					) :
					($C[1]==0 ?
						'y^2' :
						'(y'.($C[1]<0 ? '' : '+').$C[1].')^2'
					)
				).
				'='.($r<0 ? '('.$r.')^2' : $r.'^2').'$$';
		$page[] = 'Ennek a megoldása $x_1='.($axis=='x' ? $P1[0] : $P1[1]).'$ és $x_2='.($axis=='x' ? $P2[0] : $P2[1]).'$.';
		$page[] = 'Tehát a két metszéspont: $P_1($<span class="label label-success">$'.$P1[0].'$</span>$;$<span class="label label-success">$'.$P1[1].'$</span>$)$ és $P_2($<span class="label label-success">$'.$P2[0].'$</span>$;$<span class="label label-success">$'.$P2[1].'$</span>$)$.'.$this->Graph($A, $B, $axis, 4);
		$hints[] = $page;

		return $hints;
	}

	function Solution($A, $B, $axis) {

		// Center of circle
		$C[0] = ($A[0]+$B[0]) / 2;
		$C[1] = ($A[1]+$B[1]) / 2;

		// Radius of circle
		$r = sqrt( pow($A[0]-$C[0],2) + pow($A[1]-$C[1],2) );

		if ($axis == 'x') {

			// (x-cx)^2 + (y-cy)^2 = r^2
			// (x-cx)^2 + cy^2 = r^2
			// |x-cx| = sqrt(r^2 - cy^2)

			$P1[0] = sqrt(pow($r,2) - pow($C[1],2)) + $C[0];
			$P1[1] = 0;

			$P2[0] = - sqrt(pow($r,2) - pow($C[1],2)) + $C[0];
			$P2[1] = 0;

		} else {

			// (x-cx)^2 + (y-cy)^2 = r^2
			// cx^2 + (y-cy)^2 = r^2
			// |y-cy| = sqrt(r^2 - cx^2)

			$P1[1] = sqrt(pow($r,2) - pow($C[0],2)) + $C[1];
			$P1[0] = 0;

			$P2[1] = - sqrt(pow($r,2) - pow($C[0],2)) + $C[1];
			$P2[0] = 0;

		}

		return array($C, $r, $P1, $P2);
	}

	function Graph($A, $B, $axis, $progress=0) {

		list($C, $r, $P1, $P2) = $this->Solution($A, $B, $axis);

		$bottom = min(0, $C[1]-$r);
		$top 	= max(0, $C[1]+$r);
		$left 	= min(0, $C[0]-$r);
		$right 	= max(0, $C[0]+$r);

		// print_r('bottom: '.$bottom.'<br />');
		// print_r('top: '.$top.'<br />');
		// print_r('left: '.$left.'<br />');		
		// print_r('right: '.$right.'<br />');

		$linesx = $top - $bottom + 3;
		$linesy = $right - $left + 3;

		$unit 	= 500 / $linesy;

		$width 	= $unit * $linesy;
		$height = $unit * $linesx;

		// print_r('width: '.$width.', height: '.$height.'<br />');

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		// Origo
		$OO = $this->CanvasCoordinates([0,0], $height, $left, $bottom, $unit);
		if ($unit < 35) {
			$fontsize_axis = 0; // Hide text if coordinate system is too big
			$axis_width = 1;
		} elseif ($unit < 45) {
			$fontsize_axis = 10;
			$axis_width = 2;
		} elseif ($unit < 50) {
			$fontsize_axis = 11;
			$axis_width = 2;
		} else {
			$fontsize_axis = 12;
			$axis_width = 2;
		}
		$fontsize_label = round($fontsize_axis * 1.2);

		// Guides
		for ($i=0; $i < $linesy; $i++) { 
			$x = (0.5+$i)*$unit;
			$svg .= DrawLine($x, 0, $x, $height, '#F2F2F2');
			$num = $i+$left-1;
			if ($num == 0) {
				$svg .= DrawText($OO[0]+$unit/3, $OO[1]+$unit/2, '$0$', $fontsize_axis);
			} else {
				$svg .= DrawLine($x, $OO[1]-5, $x, $OO[1]+5, 'black', $axis_width);
				$svg .= DrawText($x, $OO[1]+$unit/2, '$'.$num.'$', $fontsize_axis);
			}
		}

		for ($i=0; $i < $linesx; $i++) { 
			$y = (0.5+$i)*$unit;
			$svg .= DrawLine(0, $y, $width, $y, '#F2F2F2');
			$num = $top-$i+1;
			if ($num != 0) {
				$svg .= DrawLine($OO[0]+5, $y, $OO[0]-5, $y, 'black', $axis_width);
				if ($num <= 9 && $num > 0) {
					$shift = $unit/3;
				} elseif ($num > -10 || $num > 9) {
					$shift = $unit/2;
				} else {
					$shift = $unit/1.7;
				}
				$svg .= DrawText($OO[0]-$shift, $y+$unit/5, '$'.$num.'$', $fontsize_axis);
			}
		}

		// Axes
		$svg .= DrawVector($OO[0], $height, $OO[0], 0, 'black', 10, $axis_width);
		$svg .= DrawVector(0, $OO[1], $width, $OO[1], 'black', 10, $axis_width);
		$svg .= DrawText($width-$unit/2, $OO[1]-$unit/3, '$x$', $fontsize_label);
		$svg .= DrawText($OO[0]+$unit/2, $unit/3, '$y$', $fontsize_label);

		// Calculate canvas coordinates for nodes
		$CC = $this->CanvasCoordinates($C, $height, $left, $bottom, $unit);
		$AA = $this->CanvasCoordinates($A, $height, $left, $bottom, $unit);
		$BB = $this->CanvasCoordinates($B, $height, $left, $bottom, $unit);

		$PP1 = $this->CanvasCoordinates($P1, $height, $left, $bottom, $unit);
		$PP2 = $this->CanvasCoordinates($P2, $height, $left, $bottom, $unit);

		// print_r('C('.$CC[0].';'.$CC[1].')<br />');
		// print_r('A('.$AA[0].';'.$AA[1].')<br />');
		// print_r('B('.$BB[0].';'.$BB[1].')<br />');

		// Calculate coordinates (labels)
		$Clabel = $this->LabelCoordinates($A, $B, 'center');
		$Alabel = $this->LabelCoordinates($B, $A);
		$Blabel = $this->LabelCoordinates($A, $B);

		$P1label = $this->LabelCoordinates($C, $P1, 'minus');
		$P2label = $this->LabelCoordinates($C, $P2, 'minus');

		// Calculate canvas coordinates for labels
		$CClabel = $this->CanvasCoordinates($Clabel, $height, $left, $bottom, $unit);
		$AAlabel = $this->CanvasCoordinates($Alabel, $height, $left, $bottom, $unit);
		$BBlabel = $this->CanvasCoordinates($Blabel, $height, $left, $bottom, $unit);

		$PP1label = $this->CanvasCoordinates($P1label, $height, $left, $bottom, $unit);
		$PP2label = $this->CanvasCoordinates($P2label, $height, $left, $bottom, $unit);

		if ($progress == 1) {

			// Draw points
			$svg .= DrawCircle($AA[0], $AA[1], 3, 'red', 1, 'red');
			$svg .= DrawCircle($BB[0], $BB[1], 3, 'red', 1, 'red');

			// Draw labels
			$svg .= DrawText($AAlabel[0], $AAlabel[1], '$A$', $fontsize_label);
			$svg .= DrawText($BBlabel[0], $BBlabel[1], '$B$', $fontsize_label);

		} elseif ($progress == 2) {

			// AB
			$svg .= DrawLine($AA[0], $AA[1], $BB[0], $BB[1], 'blue', 2);

			// Draw points
			$svg .= DrawCircle($AA[0], $AA[1], 3, 'red', 1, 'red');
			$svg .= DrawCircle($BB[0], $BB[1], 3, 'red', 1, 'red');
			$svg .= DrawCircle($CC[0], $CC[1], 3, 'red', 1, 'red');

			// Draw labels
			$svg .= DrawText($AAlabel[0], $AAlabel[1], '$A$', $fontsize_label);
			$svg .= DrawText($BBlabel[0], $BBlabel[1], '$B$', $fontsize_label);
			$svg .= DrawText($CClabel[0], $CClabel[1], '$C$', $fontsize_label);

		} elseif ($progress == 3) {

			// Circle
			$svg .= DrawCircle($CC[0], $CC[1], $r*$unit, 'blue', 2);

			// Draw points
			$svg .= DrawCircle($AA[0], $AA[1], 3, 'red', 1, 'red');
			$svg .= DrawCircle($BB[0], $BB[1], 3, 'red', 1, 'red');
			$svg .= DrawCircle($CC[0], $CC[1], 3, 'red', 1, 'red');

			// Draw labels
			$svg .= DrawText($AAlabel[0], $AAlabel[1], '$A$', $fontsize_label);
			$svg .= DrawText($BBlabel[0], $BBlabel[1], '$B$', $fontsize_label);
			$svg .= DrawText($CClabel[0], $CClabel[1], '$C$', $fontsize_label);

		} elseif ($progress == 4) {

			// Axes
			if ($axis == 'x') {
				$svg .= DrawLine(0, $OO[1], $width, $OO[1], 'limegreen', 2);
			} else {
				$svg .= DrawLine($OO[0], $height, $OO[0], 0, 'limegreen', 2);
			}

			// Circle
			$svg .= DrawCircle($CC[0], $CC[1], $r*$unit, 'blue', 2);

			// Draw points
			$svg .= DrawCircle($PP1[0], $PP1[1], 3, 'red', 1, 'red');
			$svg .= DrawCircle($PP2[0], $PP2[1], 3, 'red', 1, 'red');

			// Draw labels
			$svg .= DrawText($PP1label[0], $PP1label[1], '$P_1$', $fontsize_label);
			$svg .= DrawText($PP2label[0], $PP2label[1], '$P_2$', $fontsize_label);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function CanvasCoordinates($P, $height, $left, $bottom, $unit) {

		$PP[0] = (1.5 - ($left - $P[0]))*$unit;
		$PP[1] = $height - (1.5 - ($bottom - $P[1]))*$unit;

		return $PP;
	}

	function LabelCoordinates($P, $Q, $type='node') {

		if ($type == 'center') {

			$C[0] = ($P[0] + $Q[0]) / 2;
			$C[1] = ($P[1] + $Q[1]) / 2;

			list($C1[0], $C1[1]) = Rotate($C[0], $C[1], $P[0], $P[1], -45);
			list($R[0], $R[1]) = LinePoint($C[0], $C[1], $C1[0], $C1[1], 0.7);

		} elseif ($type == 'minus') {

			$length = Length($P[0], $P[1], $Q[0], $Q[1]);

			list($R[0], $R[1]) = LinePoint($P[0], $P[1], $Q[0], $Q[1], $length-0.7);

		} else {

			$length = Length($P[0], $P[1], $Q[0], $Q[1]);

			list($R[0], $R[1]) = LinePoint($P[0], $P[1], $Q[0], $Q[1], $length+0.7);

		}

		return $R;
	}
}

?>