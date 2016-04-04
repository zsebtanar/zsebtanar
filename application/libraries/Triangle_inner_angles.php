<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Triangle_inner_angles {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		if ($level <= 3) {

			$AC = rand(1,9);
			$BC = rand(1,9);

			$question = 'Az $ABC$ derékszögű háromszög $AC$ befogója $'.$AC.'$ cm, $BC$ befogója $'.$BC.'$ cm hosszú. Számítsa ki az $ABC$ háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

			$alpha = toDeg(atan($AC/$BC));
			$beta = toDeg(atan($BC/$AC));

			$alphatext = str_replace('.', ',', round($alpha*100)/100);
			$betatext = str_replace('.', ',', round($beta*100)/100);

			$correct = array($alpha, $beta);

			$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

			$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$this->Triangle();
			$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{AC}$ befogó $'.$AC.'$ cm hosszú:'.$this->Triangle(['AC'], [$AC], ['blue']);
			$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{BC}$ befogó $'.$AC.'$ cm hosszú:'.$this->Triangle(['AC','BC'], [$AC,$BC], ['blue','green']);

			$page[] = 'Egy szög <b>tangense</b> egyenlő a távolabbi és a közelebbi befogó hányadosával:'
				.'$$\tan\alpha=\frac{\textcolor{blue}{AC}}{\textcolor{green}{BC}}='
				.'\frac{\textcolor{blue}{'.$AC.'}}{\textcolor{green}{'.$BC.'}}='
				.'$$';
			$page[] = 'A szöget megkapjuk, ha a hányados <b>arkusztangensét</b> vesszük, és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\atan\frac{\textcolor{blue}{'.$AC.'}}{\textcolor{green}{'.$BC.'}}='.$alphatext.'°$$';
			$page[] = '<b>Megjegyzés</b>: az eredményt számológéppel a <kbd>tan<sup>-1</sup></kbd> gombbal lehet kiszámolni: '
				.'<kbd>'.$AC.'</kbd> <kbd>OSZTÁSJEL HIÁNYZIK!!!!!!!!!!!!</kbd> <kbd>'.$BC.'</kbd> <kbd>Shift</kbd> <kbd>tan<sup>-1</sup></kbd> <kbd>=</kbd>';
			$page[] = 'Tehát az $\alpha$ szög <span class="label label-success">$'.$alphatext.'°$</span>.';
			$hints[] = $page;

			$page = [];
			$page[] = 'Mivel a háromszög belső szögeinek összege $180°$, ezért a $\beta$ szöget már könnyen ki lehet számolni:$$180°-90°-'.$alphatext.'°$$';
			$page[] = 'Tehát a $\beta$ szög <span class="label label-success">$'.$betatext.'°$</span>.';
			$hints[] = $page;

		} elseif ($level <= 6) {

			$type = rand(0,1);
			$node = ($type ? 'AC' : 'BC');
			$length = rand(1,9);
			$AB = $length+rand(1,9);

			$question = 'Az $ABC$ derékszögű háromszög $'.$node.'$ befogója $'.$length.'$ cm, $AB$ átfogója $'.$AB.'$ cm hosszú. Számítsa ki az $ABC$ háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

			$alpha = ($type ? toDeg(acos($length/$AB)) : toDeg(asin($length/$AB));
			$beta = 90-$alpha;

			$alphatext = str_replace('.', ',', round($alpha*100)/100);
			$betatext = str_replace('.', ',', round($beta*100)/100);

			$correct = array($alpha, $beta);

			$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

			$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$this->Triangle();
			$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{'.$node.'}$ befogó $'.$length.'$ cm hosszú:'.$this->Triangle([$node], [$length], ['blue']);
			$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{AB}$ átfogó $'.$AC.'$ cm hosszú:'.$this->Triangle([$node,'AB'], [$length,$AB], ['blue','green']);

			if ($type) {

				$page[] = 'Egy szög <b>koszinusza</a> egyenlő a távolabbi és a közelebbi befogó hányadosával:'
					.'$$\cos\alpha=\frac{\textcolor{blue}{AC}}{\textcolor{green}{AB}}='
					.'\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}='
					.'$$';
				$page[] = 'A szöget megkapjuk, ha a hányados <b>arkuszkoszinuszát</b> vesszük, és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\acos\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}='.$alphatext.'°$$';
				$page[] = '<b>Megjegyzés</b>: az eredményt számológéppel a <kbd>cos<sup>-1</sup></kbd> gombbal lehet kiszámolni: '
					.'<kbd>'.$AC.'</kbd> <kbd>OSZTÁSJEL HIÁNYZIK!!!!!!!!!!!!</kbd> <kbd>'.$BC.'</kbd> <kbd>Shift</kbd> <kbd>cos<sup>-1</sup></kbd> <kbd>=</kbd>';

			} else {

				$page[] = 'Egy szög <b>szinusza</a> egyenlő a távolabbi és a közelebbi befogó hányadosával:'
					.'$$\sin\alpha=\frac{\textcolor{blue}{AC}}{\textcolor{green}{AB}}='
					.'\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}='
					.'$$';
				$page[] = 'A szöget megkapjuk, ha a hányados <b>arkuszszinuszát</b> vesszük, és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\asin\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}='.$alphatext.'°$$';
				$page[] = '<b>Megjegyzés</b>: az eredményt számológéppel a <kbd>sin<sup>-1</sup></kbd> gombbal lehet kiszámolni: '
				.'<kbd>'.$AC.'</kbd> <kbd>OSZTÁSJEL HIÁNYZIK!!!!!!!!!!!!</kbd> <kbd>'.$BC.'</kbd> <kbd>Shift</kbd> <kbd>sin<sup>-1</sup></kbd> <kbd>=</kbd>';

			}

			$page[] = 'Tehát az $\alpha$ szög <span class="label label-success">$'.$alphatext.'°$</span>.';
			$hints[] = $page;

			$page = [];
			$page[] = 'Mivel a háromszög belső szögeinek összege $180°$, ezért a $\beta$ szöget már könnyen ki lehet számolni:$$180°-90°-'.$alphatext.'°$$';
			$page[] = 'Tehát a $\beta$ szög <span class="label label-success">$'.$betatext.'°$</span>.';
			$hints[] = $page;

		} else {

			$type = rand(1,3);

			if ($type == 1) {
				$mult = rand(3,9);
				$AC = 3*$mult;
				$BC = 4*$mult;
				$AB = 5*$mult;
			} elseif ($type == 2) {
				$mult = rand(2,3);
				$AC = 5*$mult;
				$BC = 12*$mult;
				$AB = 13*$mult;
			} else {
				$mult = rand(2,3);
				$AC = 8*$mult;
				$BC = 15*$mult;
				$AB = 17*$mult;
			}

			$diff1 = $BC - $AC;
			$diff2 = $AB - $BC;

			$question = 'A $ABC$ derékszögű háromszög $AC$ befogója $'.$diff1.'$ cm-rel rövidebb, mint a $BC$ befogó. Az átfogó $'.$diff2.'$ cm-rel hosszabb, mint a $BC$ befogó. Számítsa ki a $ABC$ háromszög oldalainak hosszát!'
		}

		return array(
			'question'  => $question,
			'correct'   => $correct,
			'solution'  => $solution,
			'hints'		=> $hints,
			'type'		=> 'inner_angles'
		);
	}

	function Triangle($sides=[], $nodes=[], $colors=[]) {

		$width 	= 400;
		$height = 250;

		$paddingX = 50;
		$paddingY = 50;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					.'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		$Ax = $paddingX;
		$Ay = $height-$paddingY;

		$Bx = $width-$paddingX;
		$By = $height-$paddingY;

		list($Cx, $Cy) = Triangle($Ax, $Ay, $Bx, $By, 35, 55);

		$svg .= DrawLine($Ax, $Ay, $Bx, $By);
		$svg .= DrawLine($Ax, $Ay, $Cx, $Cy);
		$svg .= DrawLine($Cx, $Cy, $Bx, $By);

		$svg .= DrawText($Ax, $Ay+15, '$A$');
		$svg .= DrawText($Bx, $By+15, '$B$');
		$svg .= DrawText($Cx, $Cy-15, '$C$');

		$svg .= DrawText($Ax+10, $Ay-5, '$\alpha$');
		$svg .= DrawText($Bx-10, $By-5, '$\beta$');

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
					$svg .= DrawText(($Ax+$Bx)/2, ($Ay+$By)/2+15, '$\textcolor{'.$colors[$key].'}{'.$nodes[$key].'}$', 15);
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