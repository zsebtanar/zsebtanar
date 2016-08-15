<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hurtrapez_szog {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		// Trapez data
		$bottom = rand(10,15);	// bottom
		$top 	= rand(4,9);	// top
		$side 	= rand(5,9);	// side

		$bottom += ($bottom-$top) % 2;	// modify bottom

		// // Original exercise
		// $bottom = 5;
		// $top = 2;
		// $side = 2.5;

		$question = 'Az $ABCD$ húrtrapéz oldalainak hossza: $AB='.$bottom.'\,\text{cm}$, $BC='.round2($side).'\,\text{cm}$, $CD='.$top.'\,\text{cm}$ és $DA='.round2($side).'\,\text{cm}$. Számítsa ki a trapéz $D$ csúcsnál lévő szögét! <i>(A választ egész fokra kerekítve adja meg!)</i>'.$this->Trapez($bottom, $top, $side);

		list($hints, $correct) = $this->Hints($bottom, $top, $side);
		$solution = '$'.$correct.'°$';

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'labels'	=> ['right' => '$°$']
		);
	}

	function Hints($bottom, $top, $side) {

		$hints[][] = 'Mivel a trapéz szimmetrikus, ezért az $A$ és $B$ csúcsnál lévő szögek, illetve a $C$ és $D$ csúcsnál lévő szögek ugyanakkorák:'.$this->Trapez($bottom, $top, $side, 1);
		$hints[][] = 'Vetítsük le a $CD$ szakaszt merőlegesen az $AB$ szakaszra. Az így kapott $EF$ szakasz ugyanakkora, mint a $CD$ szakasz:'.$this->Trapez($bottom, $top, $side, 2);

		$ae = ($bottom-$top)/2;
		$hints[][] = 'Az $AE$ szakasz hosszát úgy tudjuk kiszámolni, hogy az $AB$ szakasz hosszából kivonjuk az $EF$ szakasz hosszát, és elosztjuk $2$-vel: $$\frac{'.$bottom.'-'.$top.'}{2}=\frac{'.strval($bottom-$top).'}{2}='.round2($ae).'\,\text{cm}$$'.$this->Trapez($bottom, $top, $side, 3);

		$alpha = toDeg(acos($ae/$side));
		
		$hints[][] = 'Az $AE$ és $AD$ szakasz hányadosa az $A$ csúcsnál lévő $\alpha$ szög koszinuszával egyenlő:$$\cos\alpha=\frac{AE}{AD}=\frac{'.round2($ae).'}{'.round2($side).'}$$'.$this->Trapez($bottom, $top, $side, 4);

		$page[] = 'Ekkor az $\alpha$ szög a hányados arkusz koszinuszával egyenlő:$$\alpha=\arccos\left(\frac{'.round2($ae).'}{'.round2($side).'}\right)$$';
		$page[] = '<b>Megjegyzés</b>: az eredményt a következőképpen lehet kiszámolni számológéppel:
			<ol>
				<li>Állítsuk be a gépet <b>DEG</b> módba (ha még nem tettük):<br /><kbd>MODE</kbd> <kbd>DEG</kbd></li>
				<li>A koszinusz függvény inverzét a <b>cos<sup>-1</sup></b> gomb segítségével lehet kiszámolni:<br />'
			.'<kbd>'.round2($ae).'</kbd> <kbd>&divide;</kbd> <kbd>'.round2($side).'</kbd> <kbd>=</kbd> <kbd>Shift</kbd> <kbd>cos<sup>-1</sup></kbd> <kbd>=</kbd></li>
			</ol>';
		$hints[] = $page;

		$beta = 180-round1($alpha);

		$page = [];
		$page[] = 'Tehát az $A$ csúcsnál lévő szög nagysága $'.Round2($alpha).'°$.';
		$page[] = 'Tudjuk, hogy a trapéz két oldalsó szöge $180°$-ra egészíti ki egymást, ezért a $D$ csúcsnál lévő szöget úgy tudjuk kiszámolni, hogy a $180°$-ból kivonjuk az $\alpha$ szöget: $180°-'.round2($alpha).'°='.round2($beta).'°$, aminek az egészekre kerekített értéke <span class="label label-success">$'.round($beta).'°$</span>.';
		$hints[] = $page;

		return array($hints, round($beta));
	}

	function Trapez($bottom, $top, $side, $progress=0) {

		$width 	= 350;
		$height = 250;

		$paddingX_top 		= 110;
		$paddingX_bottom 	= 20;
		$paddingY 			= 50;
		$radius				= 40;

		$Ax = $paddingX_bottom;
		$Ay = $height - $paddingY;
		$Bx = $width - $paddingX_bottom;
		$By = $height - $paddingY;
		$Cx = $width - $paddingX_top;
		$Cy = $paddingY;
		$Dx = $paddingX_top;
		$Dy = $paddingY;

		$Ex = $paddingX_top;
		$Ey = $height - $paddingY;
		$Fx = $width - $paddingX_top;
		$Fy = $height - $paddingY;

		$svg = '<div class="img-question text-center">
				<svg width="'.$width.'" height="'.$height.'">'
				// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
		;

		// Sides
		$svg .= DrawLine($Ax, $Ay, $Bx, $By, 'black', 2);
		$svg .= DrawLine($Bx, $By, $Cx, $Cy, 'black', 2);
		$svg .= DrawLine($Cx, $Cy, $Dx, $Dy, 'black', 2);
		$svg .= DrawLine($Dx, $Dy, $Ax, $Ay, 'black', 2);

		// Nodes
		$svg .= DrawText($Ax-13, $Ay, '$A$', 12);
		$svg .= DrawText($Bx+13, $By, '$B$', 12);
		$svg .= DrawText($Cx+13, $Cy, '$C$', 12);
		$svg .= DrawText($Dx-13, $Dy, '$D$', 12);

		if ($progress == 0) {

			// Arcs
			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Dx, $Dy, $radius);
			$svg .= DrawArc($Bx, $By, $Cx, $Cy, $Ax, $Ay, $radius);
			$svg .= DrawArc($Cx, $Cy, $Dx, $Dy, $Bx, $By, $radius);
			$svg .= DrawArc($Dx, $Dy, $Ax, $Ay, $Cx, $Cy, $radius);

			// Lengths
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$bottom.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		} elseif ($progress == 1) {

			// Arcs
			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Dx, $Dy, $radius, 0, 0, NULL, 'red');
			$svg .= DrawArc($Bx, $By, $Cx, $Cy, $Ax, $Ay, $radius, 0, 0, NULL, 'red');
			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Dx, $Dy, $radius+3, 0, 0, NULL, 'red');
			$svg .= DrawArc($Bx, $By, $Cx, $Cy, $Ax, $Ay, $radius+3, 0, 0, NULL, 'red');
			$svg .= DrawArc($Cx, $Cy, $Dx, $Dy, $Bx, $By, $radius, 0, 0, NULL, 'blue');
			$svg .= DrawArc($Dx, $Dy, $Ax, $Ay, $Cx, $Cy, $radius, 0, 0, NULL, 'blue');

			// Lengths
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$bottom.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		} elseif ($progress == 2) {

			// Height
			$svg .= DrawPath($Dx, $Dy, $Ex, $Ey, $color1='blue', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);
			$svg .= DrawPath($Cx, $Cy, $Fx, $Fy, $color1='blue', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);

			$svg .= DrawLine($Ex, $Ey, $Fx, $Fy, 'red', 3);
			$svg .= DrawText($Ex, $Ey+20, '$E$', 12);
			$svg .= DrawText($Fx, $Fy+20, '$F$', 12);

			// Lengths
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$top.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		} elseif ($progress == 3) {

			// Height
			$svg .= DrawPath($Dx, $Dy, $Ex, $Ey, $color1='black', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);
			$svg .= DrawPath($Cx, $Cy, $Fx, $Fy, $color1='blue', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);
			
			$svg .= DrawLine($Ax, $Ay, $Ex, $Ey, 'red', 3);

			$svg .= DrawText($Ex, $Ey+20, '$E$', 12);
			$svg .= DrawText($Fx, $Fy+20, '$F$', 12);

			// AE length
			$ae = ($bottom-$top)/2;

			// Lengths
			$svg .= DrawText(($Ax+$Ex)/2-5, ($Ay+$Ey)/2+17, '$'.round2($ae).'$', 12, 'red');
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$top.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		} elseif ($progress == 4) {

			// Height
			$svg .= DrawPath($Dx, $Dy, $Ex, $Ey, $color1='black', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);
			$svg .= DrawPath($Cx, $Cy, $Fx, $Fy, $color1='blue', $width=1, $color2='white', $dasharray1=5, $dasharray2=3);
			
			$svg .= DrawLine($Ax, $Ay, $Ex, $Ey, 'red', 3);
			$svg .= DrawLine($Ax, $Ay, $Dx, $Dy, 'red', 3);

			$svg .= DrawText($Ex, $Ey+20, '$E$', 12);
			$svg .= DrawText($Fx, $Fy+20, '$F$', 12);

			// AE length
			$ae = ($bottom-$top)/2;

			// Arc
			$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Dx, $Dy, $radius, +10, 0, '$\color{red}{\alpha}$', 'red');


			// Lengths
			$svg .= DrawText(($Ax+$Ex)/2-5, ($Ay+$Ey)/2+17, '$'.round2($ae).'$', 12, 'red');
			$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+17, '$'.$top.'$', 12);
			$svg .= DrawText(($Bx+$Cx)/2+15, ($By+$Cy)/2-5, '$'.round2($side).'$', 12);
			$svg .= DrawText(($Cx+$Dx)/2+5, ($Cy+$Dy)/2-5, '$'.$top.'$', 12);
			$svg .= DrawText(($Dx+$Ax)/2-15, ($Dy+$Ay)/2-5, '$'.round2($side).'$', 12);

		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>