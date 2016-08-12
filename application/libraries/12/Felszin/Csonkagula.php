<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csonkagula {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$a = rand(10,15);	// alaplap oldalhossz (doboz teteje)
		$b = rand(4,9);		// fedőlap oldalhossz (doboz alja)
		$c = rand(5,9);		// oldalél

		$area = rand(75,95)/100;	// 1 kg anyag felülete

		$a += ($a-$b) % 2;	// trapéz számoláshoz

		// // Original exercise
		// $a = 13;
		// $b = 7;
		// $c = 8;
		// $area = 0.93;

		$question = 'Egy műanyag termékeket gyártó üzemben szabályos hatoldalú csonkagúla alakú, felül nyitott virágtartó dobozokat készítenek egy kertészet számára (lásd az ábrát). A csonkagúla alaplapja $'.$a.'\,\text{cm}$ oldalú szabályos hatszög, fedőlapja $'.$b.'\,\text{cm}$ oldalú szabályos hatszög, az oldalélei $'.$c.'\,\text{cm}$ hosszúak. Egy műanyagöntő gép $1$ kg alapanyagból (a virágtartó doboz falának megfelelő anyagvastagság mellett) $'.round2($area).'\,\text{m}^2$ felületet képes készíteni. Számítsa ki, hány virágtartó doboz készíthető $1$ kg alapanyagból!'.$this->Vase(6);
		list($hints, $correct) = $this->Hints($a, $b, $c, $area);
		$solution = '$'.$correct.'$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints
		);
	}

	function Hints($a, $b, $c, $area) {

		$sides = 6;

		$triangle = (sqrt(3)*pow($b,2))/4;
		$T_bottom = $sides*$triangle;

		$page[] = '<b>1. lépés:</b> Számoljuk ki az alaplap területét!';
		$page[] = 'Az alaplap $'.$sides.'$ db $'.$b.'\,\text{cm}$ oldalú szabályos háromszögből áll.'.$this->Bottom($b);
		$hints[] = $page;

		$page = [];
		$page[] = 'Számoljuk ki egy háromszög területét!'.$this->Bottom($b,1);
		$hints[] = $page;

		$page = [];
		$page[] = '<div class="alert alert-info"><strong>Háromszög területe:</strong><br />Ha egy háromszög oldala $a$, a hozzá tartozó magasság $m_a$, akkor a területe:$$T_{\triangle}=\frac{a\cdot m_a}{2}$$<b>Szabályos háromszög magassága</b><br />Ha egy szabályos háromszög oldala $a$, akkor a magassága $$m_a=\frac{\sqrt{3}}{2}\cdot a$$</div>';
		$page[] = 'Jelen esetben $a=7$, ezért:$$\begin{eqnarray}T_{\triangle}=\frac{a\cdot m_a}{2}&=&\frac{'.$b.'\cdot\left(\frac{\sqrt{3}}{2}\cdot'.$b.'\right)}{2} \\\\ &=&\frac{\sqrt{3}\cdot'.strval(pow($b,2)).'}{4}\\\\ &\approx&'.round2($triangle).'\,\text{cm}^2\end{eqnarray}$$';
		$page[] = 'Tehát az alaplap területe$$T_{\text{alaplap}}='.$sides.'\cdot T_{\triangle}='.$sides.'\cdot'.round2($triangle).'\approx'.round2($T_bottom).'\,\text{cm}^2$$';
		$hints[] = $page;

		$page = [];
		$page[] = '<b>2. lépés:</b> Számoljuk ki az oldallapok területét!';
		$page[] = 'A doboznak mind a $'.$sides.'$ oldala egy szimmetrikus trapéz, aminek az alapjai $'.$a.'$ ill. $'.$b.'\,\text{cm}$, oldalai pedig $'.$c.'\,\text{cm}$ hosszúak.'.$this->Side($a, $b, $c);
		$hints[] = $page;

		$page = [];
		$page[] = 'Az alsó oldalt mérjük rá a felsőre, és nézzük meg, hány centi marad jobb és baloldalon:'.$this->Side($a, $b, $c, 1);
		$hints[] = $page;

		$d = ($a-$b)/2;
		$e = pow($c,2)-pow($d,2);
		$m = sqrt($e);

		$page = [];
		$page[] = 'Ekkor a jobb és a bal oldalon egy olyan derékszögű háromszöget kaptunk, aminek az átfogója $'.$c.'\,\text{cm}$, az egyik befogója pedig $'.$d.'\,\text{cm}$.'.$this->Side($a, $b, $c, 2);
		$page[] = 'A másik befogót ($x$) - ami egyben a trapéz magassága - Pitagorasz-tétellel tudjuk kiszámolni:$$\begin{eqnarray}
				x^2+'.$d.'^2&=&'.$c.'^2\\\\
				x^2+'.strval(pow($d,2)).'&=&'.strval(pow($c,2)).'\\\\
				x^2&=&'.$e.'\\\\
				x&=&\sqrt{'.$e.'}\\\\
				x&'.(round1($m)==$m ? '=' : '\approx').'&'.round2($m).
				'\end{eqnarray}$$';
		$hints[] = $page;

		$T_side = ($a+$b)/2*$m;
		$T_all = $T_bottom + $sides * $T_side;

		$page = [];
		$page[] = '<div class="alert alert-info"><strong>Trapéz területe:</strong><br />Ha egy trapéz két párhuzamos oldala $a$ és $c$, és magassága $m$, akkor a területe:$$T=\frac{a+c}{2}\cdot m$$</div>';
		$page[] = 'Egy oldal területe:$$T=\frac{'.$a.'+'.$b.'}{2}\cdot'.strval(round2($m)).'='.round2($T_side).'$$';
		$page[] = 'Mivel összesen $'.$sides.'$ oldal van, ezért az oldallapok összterülete:$$T_{\text{oldallapok}}='.$sides.'\cdot'.round2($T_side).'='.round2($sides*$T_side).'$$';
		$page[] = 'Ekkor egy virágtartó doboz összterülete:$$\begin{eqnarray}T_{\text{össz}}&=&T_{\text{alaplap}}+T_{\text{oldallapok}}\\\\ &\approx&'.round2($T_bottom).'+'.round2($sides*$T_side).'='.round2($T_all).'\end{eqnarray}$$';
		$hints[] = $page;

		$pieces = $area*10000/$T_all;

		$page = [];
		$page[] = 'A gép $1$ kg alapanyagból $'.round2($area).'\,\text{m}^2$ felületet képes készíteni, ami összesen $'.round2($area).'\cdot10000='.round2($area*10000).'\,\text{cm}^2$.';
		$page[] = 'Számoljuk ki, hogy $1$ kg anyagból hány doboz készíthető:$$\frac{'.round2($area*10000).'}{'.round2($T_all).'}\approx'.round2($pieces).'$$';
		$page[] = 'Tehát $1$ kg alapanyagból <span class="label label-success">$'.floor($pieces).'$</span> darab doboz készíthető.';
		$hints[] = $page;

		return array($hints, floor($pieces));
	}

	function Vase($sides) {

		$height 		= 100;	// height of vase
		$radius_top 	= 100;	// radius of top circle
		$radius_bottom 	= 50;	// radius of bottom circle

		$alfa0 			= array(
			3 => rand(20, 40),
			4 => rand(-10, 10),
			5 => rand(-5, 5),
			6 => rand(-10, 10)
		);						// starting angle of vase nodes
		$alfa0			= $alfa0[$sides];

		$visible		= array(
			3 => [1,1,1],
			4 => [1,0,1,1],
			5 => [1,0,0,1,1],
			6 => [1,0,0,1,1,1]
		);						// ids of visible edges
		$visible 		= $visible[$sides];

		$perspective	= 0.4;	// 0 - view from side
								// 1 - view from top

		$padding_y		= 50;
		$canvas_width  	= 400;
		$canvas_height 	= 2*50 + $height + $perspective * ($radius_top + $radius_bottom);

		$svg = '<div class="img-question text-center">
					<svg width="'.$canvas_width.'" height="'.$canvas_height.'">'
					// .'<rect width="'.$canvas_width.'" height="'.$canvas_height.'" fill="black" fill-opacity="0.2" />'
		;

		$center_top_x 		= $canvas_width/2;
		$center_top_y 		= $padding_y + $perspective * $radius_top;

		$center_bottom_x 	= $canvas_width/2;
		$center_bottom_y	= $center_top_y + $height;

		
		for ($i=0; $i < $sides; $i++) {

			$alfa = $alfa0 + $i*360/$sides;

			// Calculate points
			list($Px, $Py) 	= Rotate($center_top_x, $center_top_y, $center_top_x+$radius_top, $center_top_y, $alfa);
			list($Qx, $Qy) 	= Rotate($center_bottom_x, $center_bottom_y, $center_bottom_x+$radius_bottom, $center_bottom_y, $alfa);

			$Py = $center_top_y + $perspective * ($Py - $center_top_y);
			$Qy = $center_bottom_y + $perspective * ($Qy - $center_bottom_y);

			$points_top[] 		= [$Px, $Py];
			$points_bottom[] 	= [$Qx, $Qy];

			// Draw top circle
			if ($i > 0) {
				$svg .= DrawLine($points_top[$i-1][0], $points_top[$i-1][1], $Px, $Py, 'black', 2);
			}
			if ($i == $sides-1) {
				$svg .= DrawLine($points_top[0][0], $points_top[0][1], $Px, $Py, 'black', 2);
			}

			// Draw bottom circle
			if ($i > 0) {
				if ($visible[$i] && $visible[$i-1]) {
					$svg .= DrawLine($points_bottom[$i-1][0], $points_bottom[$i-1][1], $Qx, $Qy, 'black', 2);
				} else {
					$svg .= DrawPath($points_bottom[$i-1][0], $points_bottom[$i-1][1], $Qx, $Qy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
				}
			}
			if ($i == $sides-1) {
				if ($visible[$i] && $visible[0]) {
					$svg .= DrawLine($points_bottom[0][0], $points_bottom[0][1], $Qx, $Qy, 'black', 2);
				} else {
					$svg .= DrawPath($points_bottom[0][0], $points_bottom[0][1], $Qx, $Qy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
				}
			}

			// Draw sides
			if ($visible[$i]) {
				$svg .= DrawLine($Px, $Py, $Qx, $Qy, 'black', 2);
			} else {
				$svg .= DrawPath($Px, $Py, $Qx, $Qy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
			}
		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Bottom($a, $option=0) {

		$width 	= 250;
		$height = 250;

		$centerX = $width/2;
		$centerY = $height/2;
		$radius = 100;

		$svg = '<div class="img-question text-center">
				<svg width="'.$width.'" height="'.$height.'">'
				// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
			;

		for ($i=0; $i < 6; $i++) {

			$alfa = $i*60;

			// Rotate point
			list($Px, $Py) = Rotate($centerX, $centerY, $centerX+$radius, $centerY, $alfa);

			$points[] = [$Px, $Py];

			// Draw side
			if ($i > 0) {
				$svg .= DrawLine($points[$i-1][0], $points[$i-1][1], $Px, $Py, 'black', 2);

				$side_x = ($points[$i-1][0] + $Px)/2;
				$side_y = ($points[$i-1][1] + $Py)/2;

				// Node
				list($Ex, $Ey) = LinePoint($centerX, $centerY, $side_x, $side_y, $radius);
				$svg .= DrawText($Ex, $Ey+7, '$'.$a.'$', 12);

			}
			if ($i == 5) {
				$svg .= DrawLine($points[0][0], $points[0][1], $Px, $Py, 'black', 2);

				$side_x = ($points[0][0] + $Px)/2;
				$side_y = ($points[0][1] + $Py)/2;

				// Node
				list($Ex, $Ey) = LinePoint($centerX, $centerY, $side_x, $side_y, $radius);
				$svg .= DrawText($Ex, $Ey+7, '$'.$a.'$', 12);
			}

			// Draw edge
			$svg .= DrawPath($centerX, $centerY, $Px, $Py, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
		}

		if ($option == 1) {

			$A = $points[4];
			$B = $points[5];

			$svg .= DrawLine($A[0], $A[1], $B[0], $B[1], 'blue', 2);
			$svg .= DrawLine($A[0], $A[1], $centerX, $centerY, 'blue', 2);
			$svg .= DrawLine($B[0], $B[1], $centerX, $centerY, 'blue', 2);

			$svg .= DrawPath($centerX, $centerY, ($A[0]+$B[0])/2, ($A[1]+$B[1])/2, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Side($a, $b, $c, $option=0) {

		$width 	= 250;
		$height = 250;

		$paddingX_top 		= 20;
		$paddingX_bottom 	= 70;
		$paddingY 			= 50;

		$Ax = $paddingX_top;
		$Ay = $paddingY;
		$Bx = $paddingX_bottom;
		$By = $height - $paddingY;
		$Cx = $width - $Bx;
		$Cy = $By;
		$Dx = $width - $Ax;
		$Dy = $Ay;

		$Ex = $paddingX_bottom;
		$Ey = $Ay;
		$Fx = $width - $Ex;
		$Fy = $Ay;

		$svg = '<div class="img-question text-center">
				<svg width="'.$width.'" height="'.$height.'">'
				// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
		;

		// Sides
		$svg .= DrawLine($Ax, $Ay, $Bx, $By, 'black', 2);
		$svg .= DrawLine($Bx, $By, $Cx, $Cy, 'black', 2);
		$svg .= DrawLine($Cx, $Cy, $Dx, $Dy, 'black', 2);
		$svg .= DrawLine($Dx, $Dy, $Ax, $Ay, 'black', 2);
		
		if ($option == 0) {

			// Nodes
			$svg .= DrawText(($Ax+$Bx)/2-13, ($Ay+$By)/2+7, '$'.$c.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2-3, ($By+$Cy)/2+20, '$'.$b.'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+10, ($Cy+$Dy)/2+7, '$'.$c.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-2, ($Dy+$Ay)/2-10, '$'.$a.'$', 12);

		} elseif ($option == 1) {

			// Nodes
			$svg .= DrawText(($Ax+$Bx)/2-13, ($Ay+$By)/2+7, '$'.$c.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2-3, ($By+$Cy)/2+20, '$'.$b.'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+10, ($Cy+$Dy)/2+7, '$'.$c.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-2, ($Dy+$Ay)/2-10, '$'.$b.'$', 12);
			$svg .= DrawText(($Ex+$Ax)/2-2, ($Ey+$Ay)/2-10, '$'.strval(($a-$b)/2).'$', 12);
			$svg .= DrawText(($Fx+$Dx)/2-2, ($Fy+$Dy)/2-10, '$'.strval(($a-$b)/2).'$', 12);

			// Extra edges
			$svg .= DrawPath($Bx, $By, $Ex, $Ey, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);
			$svg .= DrawPath($Cx, $Cy, $Fx, $Fy, $color1='black', $width=1, $color2='none', $dasharray1=5, $dasharray2=5);

		} elseif ($option == 2) {

			$svg .= DrawLine($Ax, $Ay, $Bx, $By, 'blue', 2);
			$svg .= DrawLine($Bx, $By, $Ex, $Ey, 'blue', 2);
			$svg .= DrawLine($Ex, $Ey, $Ax, $Ay, 'blue', 2);

			$svg .= DrawText(($Ax+$Bx)/2-13, ($Ay+$By)/2+7, '$'.$c.'$', 12, 'blue');
			$svg .= DrawText(($Bx+$Ex)/2+10, ($By+$Ey)/2+7, '$x$', 12, 'blue');
			$svg .= DrawText(($Ex+$Ax)/2-2, ($Ey+$Ay)/2-10, '$'.strval(($a-$b)/2).'$', 12, 'blue');

		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>