<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gula {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$pyramid_base = rand(6,10); // alaplap
		$pyramid_side = ceil($pyramid_base/2) + rand(4,10); // oldalél
		$cube_edge = rand(10,20); // kockaél

		// // Original exercise
		// $pyramid_base = 6;
		// $pyramid_side = 5;
		// $cube_edge = 11;

		$question = 'Zsófi gyertyákat szeretne önteni, hogy megajándékozhassa a barátait. Öntőformának egy négyzet alapú szabályos gúlát választ, melynek alapéle $'.$pyramid_base.'\,\text{cm}$, oldaléle $'.$pyramid_side.'\,\text{cm}$ hosszúságú. Egy szaküzletben $'.$cube_edge.'\,\text{cm}$ oldalú, kocka alakú tömbökben árulják a gyertyának való viaszt. Ezt megolvasztva és az olvadt viaszt a formába öntve készülnek a gyertyák. (A számítások során tekintsen el az olvasztás és öntés során bekövetkező térfogatváltozástól.) Legfeljebb hány gyertyát önthet Zsófi egy $'.$cube_edge.'\,\text{cm}$ oldalú, kocka alakú tömbből?'.$this->Pyramid($pyramid_base, $pyramid_side);

		list($hints, $correct) = $this->Hints($pyramid_base, $pyramid_side, $cube_edge);

		$solution = '$'.$correct.'$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}

	function Hints($pyramid_base, $pyramid_side, $cube_edge) {

		$cube_volume = pow($cube_edge, 3);
		$m_triangle = sqrt(pow($pyramid_side,2) - pow($pyramid_base/2,2));
		$m_pyramid = sqrt(pow($m_triangle,2) - pow($pyramid_base/2,2));
		$pyramid_volume = 1/3 * pow($pyramid_base,2) * $m_pyramid;
		$pieces = $cube_volume / $pyramid_volume;

		$page[] = 'Számoljuk ki az oldallap magasságát ($m_{\triangle}$) Pitagorasz-tétellel:
		$$\begin{eqnarray}
		m_{\triangle}^2+\left(\frac{'.$pyramid_base.'}{2}\right)^2&=&'.$pyramid_side.'^2\\\\
		m_{\triangle}^2&=&'.$pyramid_side.'^2-\left(\frac{'.$pyramid_base.'}{2}\right)^2\\\\
		m_{\triangle}&=&\sqrt{'.$pyramid_side.'^2-\left(\frac{'.$pyramid_base.'}{2}\right)^2}
		\end{eqnarray}$$
		A műveletet elvégezve azt kapjuk, hogy $m_{\triangle}'.(round($m_triangle)==$m_triangle ? '=' : '\approx').round2($m_triangle).'\,\text{cm}$.'.$this->Pyramid($pyramid_base, $pyramid_side, 1);
		$hints[] = $page;

		$page = [];
		$page[] = 'Számoljuk ki a gúla magasságát ($m$) Pitagorasz-tétellel:
		$$\begin{eqnarray}
		m^2+\left(\frac{'.$pyramid_base.'}{2}\right)^2&=&m_{\triangle}^2\\\\
		m^2&=&m_{\triangle}^2-\left(\frac{'.$pyramid_base.'}{2}\right)^2\\\\
		m&=&\sqrt{m_{\triangle}^2-\left(\frac{'.$pyramid_base.'}{2}\right)^2}\\\\
		m&=&\sqrt{'.round2($m_triangle).'^2-'.round2($pyramid_base/2).'^2}
		\end{eqnarray}$$
		A műveletet elvégezve azt kapjuk, hogy $m'.(round($m_pyramid)==$m_pyramid ? '=' : '\approx').round2($m_pyramid).'\,\text{cm}$.'.$this->Pyramid($pyramid_base, $pyramid_side, 2);
		$hints[] = $page;

		$page = [];
		$page[] = '<div class="alert alert-info"><b>Gúla térfogata</b><br />Ha egy gúla alapterülete $T_a$, és magassága $m$, akkor a térfogata:$$V=\frac{1}{3}\cdot T_a\cdot m$$</div>';
		$page[] = 'Jelen esetben alap egy $'.$pyramid_base.'\,\text{cm}$ oldalú négyzet, aminek a területe:$$T_a='.$pyramid_base.'\cdot'.$pyramid_base.'='.pow($pyramid_base,2).'\,\text{cm}^2$$';
		$page[] = 'Ezt a fenti képletbe behelyettesítve:$$V=\frac{1}{3}\cdot '.pow($pyramid_base,2).'\cdot'.round2($m_pyramid).'\approx'.round2($pyramid_volume).'\,\text{cm}^3$$';
		$page[] = 'Tehát egy darab gyertya térfogata $$V_{gyertya}\approx'.round2($pyramid_volume).'\,\text{cm}^3$$';
		$hints[] = $page;

		$page = [];
		$page[] = 'Egy $'.$cube_edge.'\,\text{cm}$ oldalú kocka térfogata:$$V_{kocka}='.$cube_edge.'\cdot'.$cube_edge.'\cdot'.$cube_edge.'='.$cube_volume.'\,\text{cm}^3$$';
		$page[] = 'Számoljuk ki, hogy egy kockából hány gyertya készíthető:$$\frac{V_{kocka}}{V_{gyertya}}=\frac{'.round2($cube_volume).'}{'.round2($pyramid_volume).'}\approx'.round2($pieces).'$$';
		$page[] = 'Tehát egy kockából alapanyagból legfeljebb <span class="label label-success">$'.floor($pieces).'$</span> darab doboz készíthető.';
		$hints[] = $page;

		return array($hints, floor($pieces));
	}

	function Pyramid($pyramid_base, $pyramid_side, $progress=0) {

		$sides = 4; // sides of pyramid
		$height	= 150; // height of pyramid
		$radius = 150; // radius of base circle
		$alfa0 = 15; // starting angle of nodes
		$visible = [1,0,1,1]; // ids of visible edges
		$perspective = 0.4;	// 0 (view from side) ... 1 (view from top)

		$padding_y = 20;
		$padding_x = 20;
		$canvas_width = 2*$padding_y + 2 * $radius;
		$canvas_height 	= 2*$padding_x + $height + $perspective * $radius;

		$svg = '<div class="img-question text-center">
					<svg width="'.$canvas_width.'" height="'.$canvas_height.'">';

		// $svg .= '<rect width="'.$canvas_width.'" height="'.$canvas_height.'" fill="black" fill-opacity="0.2" />';

		$center_x = $canvas_width/2;
		$center_y_top = $padding_y;

		$center_x = $canvas_width/2;
		$center_y_bottom = $center_y_top + $height;

		$node_top = [$center_x, $center_y_top];
		
		for ($i=0; $i < $sides; $i++) {

			$alfa = $alfa0 + $i*360/$sides;

			// Calculate bottom nodes
			list($Px, $Py) 	= Rotate($center_x, $center_y_bottom, $center_x+$radius, $center_y_bottom, $alfa);

			$Py = $center_y_bottom + $perspective * ($Py - $center_y_bottom);

			$nodes_bottom[] = [$Px, $Py];

			// Draw bottom edges
			if ($i > 0) {
				if ($visible[$i] && $visible[$i-1]) {
					$svg .= DrawLine($nodes_bottom[$i-1][0], $nodes_bottom[$i-1][1], $Px, $Py, 'black', 2);
				} else {
					$svg .= DrawPath($nodes_bottom[$i-1][0], $nodes_bottom[$i-1][1], $Px, $Py, 'black', 1, 'none', 5, 5);
				}
			}
			if ($i == $sides-1) {
				if ($visible[$i] && $visible[0]) {
					$svg .= DrawLine($nodes_bottom[0][0], $nodes_bottom[0][1], $Px, $Py, 'black', 2);
				} else {
					$svg .= DrawPath($nodes_bottom[0][0], $nodes_bottom[0][1], $Px, $Py, 'black', 1, 'none', 5, 5);
				}
			}

			// Draw sides
			if ($visible[$i]) {
				$svg .= DrawLine($node_top[0], $node_top[1], $Px, $Py, 'black', 2);
			} else {
				$svg .= DrawPath($node_top[0], $node_top[1], $Px, $Py, 'black', 1, 'none', 5, 5);
			}
		}

		$b = $nodes_bottom;
		$t = $node_top;

		if ($progress == 1) {

			// Draw labels
			$svg .= DrawText(($b[0][0]+$t[0])/2+20, ($b[0][1]+$t[1])/2-5, '$'.$pyramid_side.'$', 13); // side
			$svg .= DrawText(($b[2][0]+$b[3][0])/2-10, ($b[2][1]+$b[3][1])/2+25, '$'.$pyramid_base.'$', 13); // base
			$svg .= DrawText($b[3][0]/4+$b[0][0]/4*3+10, $b[3][1]/4+$b[0][1]/4*3+25, '$\frac{'.$pyramid_base.'}{2}$', 13); // base/2
			$svg .= DrawText(($t[0]+($b[3][0]+$b[0][0])/2)/2-10, ($t[1]+($b[3][1]+$b[0][1])/2)/2+20, '$m_{\triangle}$', 13); // m_triangle

			// Draw side
			$points = [$b[0], $t, [($b[3][0]+$b[0][0])/2,($b[3][1]+$b[0][1])/2]];
			$svg .= DrawPolygon($points, $stroke='black', $strokewidth=2, $fill='blue', $opacity=.5);
		
		} elseif ($progress == 2) {

			$b = $nodes_bottom;
			$t = $node_top;

			// Draw labels
			$svg .= DrawText(($b[2][0]+$b[3][0])/2-10, ($b[2][1]+$b[3][1])/2+25, '$'.$pyramid_base.'$', 13); // base
			$svg .= DrawText(($center_x+$b[3][0])/2, ($center_y_bottom+$b[3][1])/2, '$\frac{'.$pyramid_base.'}{2}$', 13); // base/2
			$svg .= DrawText(($t[0]+($b[3][0]+$b[0][0])/2)/2+30, ($t[1]+($b[3][1]+$b[0][1])/2)/2+10, '$m_{\triangle}$', 13); // m_triangle
			$svg .= DrawText($center_x-20, ($center_y_bottom+$center_y_top)/2+10, '$m$', 13); // m

			// Draw side
			$points = [$t, [($b[3][0]+$b[0][0])/2,($b[3][1]+$b[0][1])/2], [$center_x,$center_y_bottom]];
			$svg .= DrawPolygon($points, $stroke='black', $strokewidth=2, $fill='blue', $opacity=.5);
		
		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>