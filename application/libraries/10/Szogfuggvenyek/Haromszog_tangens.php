<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haromszog_tangens {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$AC = rand($level, 2*$level);
		$BC = rand($level, 2*$level);

		if ($AC == $BC && rand(1,3) < 3) {
			while ($AC == $BC) {
				$BC = rand($level, 2*$level);
			}
		}

		// // Original exercise
		// $AC = 6;
		// $BC = 8;

		$question = 'Az $ABC$ derékszögű háromszög $AC$ befogója $'.$AC.'$ cm, $BC$ befogója $'.$BC.'$ cm hosszú. Számítsa ki az $ABC$ háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

		$alpha = toDeg(atan($AC/$BC));
		$beta = toDeg(atan($BC/$AC));

		$correct = array(round1($alpha), round1($beta));
		$labels = array('$\alpha$', '$\beta$');
		$solution = '$\alpha='.round2($alpha).'°$ és $\beta='.round2($beta).'°$.';

		$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$this->Triangle();
		$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{AC}$ befogó $'.$AC.'$ cm hosszú:'.$this->Triangle(['AC'], [$AC], ['blue']);
		$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{BC}$ befogó $'.$BC.'$ cm hosszú:'.$this->Triangle(['AC','BC'], [$AC,$BC], ['blue','green']);

		$page[] = 'Tudjuk, hogy az $\alpha$ szög <b>tangense</b> egyenlő a távolabbi és a közelebbi befogó hányadosával:'
			.'$$\tan\alpha=\frac{\textcolor{blue}{AC}}{\textcolor{green}{BC}}='
			.'\frac{\textcolor{blue}{'.$AC.'}}{\textcolor{green}{'.$BC.'}}'
			.'$$';
		$page[] = 'Az $\alpha$ szöget megkapjuk, ha a hányados <b>arkusz tangensét</b> vesszük (ami a tangens függvény inverze), és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\arctan\frac{\textcolor{blue}{'.$AC.'}}{\textcolor{green}{'.$BC.'}}\approx'.round2($alpha).'°$$';
		$page[] = '<b>Megjegyzés</b>: az eredményt a következőképpen lehet kiszámolni számológéppel:
			<ol>
				<li>Állítsuk be a gépet <b>DEG</b> módba (ha még nem tettük):<br /><kbd>MODE</kbd> <kbd>DEG</kbd></li>
				<li>Az arkusz tangenst a <b>tan<sup>-1</sup></b> gomb segítségével lehet kiszámolni:<br />'
			.'<kbd>'.$AC.'</kbd> <kbd>&divide;</kbd> <kbd>'.$BC.'</kbd> <kbd>=</kbd> <kbd>Shift</kbd> <kbd>tan<sup>-1</sup></kbd> <kbd>=</kbd></li>
			</ol>';
		$page[] = 'Tehát az $\alpha$ szög <span class="label label-success">$'.round2($alpha).'°$</span>.';
		$hints[] = $page;

		$page = [];
		$page[] = 'Mivel a háromszög belső szögeinek összege $180°$, ezért a $\beta$ szöget már könnyen ki lehet számolni:$$\beta=180°-90°-'.round2($alpha).'°$$';
		$page[] = 'Tehát a $\beta$ szög <span class="label label-success">$'.round2($beta).'°$</span>.';
		$hints[] = $page;

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'labels'	=> $labels,
			'type'		=> 'array'
		);
	}

	function Triangle($sides=[], $nodes=[], $colors=[]) {

		$width 	= 400;
		$height = 250;

		$paddingX = 50;
		$paddingY = 50;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		$Ax = $paddingX;
		$Ay = $height-$paddingY;

		$Bx = $width-$paddingX;
		$By = $height-$paddingY;

		list($Cx, $Cy) = Triangle($Ax, $Ay, $Bx, $By, 35, 55);

		$svg .= DrawLine($Ax, $Ay, $Bx, $By);
		$svg .= DrawLine($Ax, $Ay, $Cx, $Cy);
		$svg .= DrawLine($Cx, $Cy, $Bx, $By);

		$svg .= DrawText($Ax, $Ay+25, '$A$', 15);
		$svg .= DrawText($Bx, $By+25, '$B$', 15);
		$svg .= DrawText($Cx, $Cy-10, '$C$', 15);

		$svg .= DrawText($Ax+40, $Ay-7, '$\alpha$', 15);
		$svg .= DrawText($Bx-30, $By-10, '$\beta$', 15);
		$svg .= DrawArc($Ax, $Ay, $Bx, $By, $Cx, $Cy, 65);
		$svg .= DrawArc($Bx, $By, $Cx, $Cy, $Ax, $Ay, 55);

		list($P1x, $P1y) = LinePoint($Cx, $Cy, $Ax, $Ay, 25);
		list($P2x, $P2y) = LinePoint($Cx, $Cy, $Bx, $By, 25);
		list($P3x, $P3y) = Translate($P1x, $P1y, 25, $Cx, $Cy, $Bx, $By);

		$svg .= DrawLine($P1x, $P1y, $P3x, $P3y);
		$svg .= DrawLine($P2x, $P2y, $P3x, $P3y);

		foreach ($sides as $key => $side) {
			switch ($side) {
				case 'AC':
				case 'CA':
					$svg .= DrawLine($Ax, $Ay, $Cx, $Cy, $colors[$key], 2);
					$svg .= DrawText(($Ax+$Cx)/2-20, ($Ay+$Cy)/2, '$\textcolor{'.$colors[$key].'}{'.$nodes[$key].'}$', 15);
					break;
				case 'AB':
				case 'BA':
					$svg .= DrawLine($Ax, $Ay, $Bx, $By, $colors[$key], 2);
					$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+25, '$\textcolor{'.$colors[$key].'}{'.$nodes[$key].'}$', 15);
					break;
				case 'BC':
				case 'CB':
					$svg .= DrawLine($Bx, $By, $Cx, $Cy, $colors[$key], 2);
					$svg .= DrawText(($Bx+$Cx)/2+20, ($By+$Cy)/2, '$\textcolor{'.$colors[$key].'}{'.$nodes[$key].'}$', 15);
					break;
			}
		}

		$svg .= '</svg></div>';

		return $svg;
	}
}

?>