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
			$labels = array('$\alpha$', '$\beta$');
			$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

			$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$this->Triangle();
			$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{AC}$ befogó $'.$AC.'$ cm hosszú:'.$this->Triangle(['AC'], [$AC], ['blue']);
			$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{BC}$ befogó $'.$AC.'$ cm hosszú:'.$this->Triangle(['AC','BC'], [$AC,$BC], ['blue','green']);

			$page[] = 'Egy szög <b>tangense</b> egyenlő a távolabbi és a közelebbi befogó hányadosával:'
				.'$$\tan\alpha=\frac{\textcolor{blue}{AC}}{\textcolor{green}{BC}}='
				.'\frac{\textcolor{blue}{'.$AC.'}}{\textcolor{green}{'.$BC.'}}'
				.'$$';
			$page[] = 'A szöget megkapjuk, ha a hányados <b>arkusz tangensét</b> vesszük (ami a tangens függvény inverze), és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\arctan\frac{\textcolor{blue}{'.$AC.'}}{\textcolor{green}{'.$BC.'}}='.$alphatext.'°$$';
			$page[] = '<b>Megjegyzés</b>: az eredményt a következőképpen lehet kiszámolni számológéppel:
				<ol>
					<li>Állítsuk be a gépet <b>DEG</b> módba (ha még nem tettük):<br /><kbd>MODE</kbd> <kbd>DEG</kbd></li>
					<li>Az arkusz tangenst a <b>tan<sup>-1</sup></b> gomb segítségével lehet kiszámolni:<br />'
				.'<kbd>'.$AC.'</kbd> <kbd>&divide;</kbd> <kbd>'.$BC.'</kbd> <kbd>=</kbd> <kbd>Shift</kbd> <kbd>tan<sup>-1</sup></kbd> <kbd>=</kbd></li>
				</ol>';
			$page[] = 'Tehát az $\alpha$ szög <span class="label label-success">$'.$alphatext.'°$</span>.';
			$hints[] = $page;

			$page = [];
			$page[] = 'Mivel a háromszög belső szögeinek összege $180°$, ezért a $\beta$ szöget már könnyen ki lehet számolni:$$\beta=180°-90°-'.$alphatext.'°$$';
			$page[] = 'Tehát a $\beta$ szög <span class="label label-success">$'.$betatext.'°$</span>.';
			$hints[] = $page;

		} elseif ($level <= 6) {

			$type = rand(0,1);
			$node = ($type ? 'AC' : 'BC');
			$length = rand(1,9);
			$AB = $length+rand(1,9);

			$question = 'Az $ABC$ derékszögű háromszög $'.$node.'$ befogója $'.$length.'$ cm, $AB$ átfogója $'.$AB.'$ cm hosszú. Számítsa ki az $ABC$ háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

			$alpha = ($type ? toDeg(acos($length/$AB)) : toDeg(asin($length/$AB)));
			$beta = 90-$alpha;

			$alphatext = str_replace('.', ',', round($alpha*100)/100);
			$betatext = str_replace('.', ',', round($beta*100)/100);

			$correct = array($alpha, $beta);
			$labels = array('$\alpha$', '$\beta$');
			$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

			$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$this->Triangle();
			$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{'.$node.'}$ befogó $'.$length.'$ cm hosszú:'.$this->Triangle([$node], [$length], ['blue']);
			$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{AB}$ átfogó $'.$AB.'$ cm hosszú:'.$this->Triangle([$node,'AB'], [$length,$AB], ['blue','green']);

			if ($type) {

				$page[] = 'Az $\alpha$ szög <b>koszinusza</b> a szög melletti befogó ($AC$) és az átfogó ($AB$) hányadosa:'
					.'$$\cos\alpha=\frac{\textcolor{blue}{AC}}{\textcolor{green}{AB}}='
					.'\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}'
					.'$$';
				$page[] = 'Az $\alpha$ szöget úgy kapjuk meg, ha a hányados <b>arkusz koszinuszát</b> vesszük (ami a koszinusz függvény inverze), és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\arccos\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}='.$alphatext.'°$$';
				$page[] = '<b>Megjegyzés</b>: az eredményt a következőképpen lehet kiszámolni számológéppel:
					<ol>
						<li>Állítsuk be a gépet <b>DEG</b> módba (ha még nem tettük):<br /><kbd>MODE</kbd> <kbd>DEG</kbd></li>
						<li>Az koszinusz függvény inverzét a <b>cos<sup>-1</sup></b> gomb segítségével lehet kiszámolni:<br />'
					.'<kbd>'.$length.'</kbd> <kbd>&divide;</kbd> <kbd>'.$AB.'</kbd> <kbd>=</kbd> <kbd>Shift</kbd> <kbd>cos<sup>-1</sup></kbd> <kbd>=</kbd></li>
					</ol>';

			} else {

				$page[] = 'Az $\alpha$ szög <b>szinusza</b> a szöggel szembeni befogó ($BC$) és az átfogó ($AB$) hányadosa:'
					.'$$\sin\alpha=\frac{\textcolor{blue}{BC}}{\textcolor{green}{AB}}='
					.'\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}'
					.'$$';
				$page[] = 'Az $\alpha$ szöget úgy kapjuk meg, ha a hányados <b>arkusz szinuszát</b> vesszük (ami a szinusz függvény inverze), és az eredményt két tizedesjegyre kerekítjük:$$\alpha=\arcsin\frac{\textcolor{blue}{'.$length.'}}{\textcolor{green}{'.$AB.'}}='.$alphatext.'°$$';
				$page[] = '<b>Megjegyzés</b>: az eredményt a következőképpen lehet kiszámolni számológéppel:
					<ol>
						<li>Állítsuk be a gépet <b>DEG</b> módba (ha még nem tettük):<br /><kbd>MODE</kbd> <kbd>DEG</kbd></li>
						<li>Az szinusz függvény inverzét a <b>sin<sup>-1</sup></b> gomb segítségével lehet kiszámolni:<br />'
					.'<kbd>'.$length.'</kbd> <kbd>&divide;</kbd> <kbd>'.$AB.'</kbd> <kbd>=</kbd> <kbd>Shift</kbd> <kbd>sin<sup>-1</sup></kbd> <kbd>=</kbd></li>
					</ol>';

			}

			$page[] = 'Tehát az $\alpha$ szög <span class="label label-success">$'.$alphatext.'°$</span>.';
			$hints[] = $page;

			$page = [];
			$page[] = 'Mivel a háromszög belső szögeinek összege $180°$, ezért a $\beta$ szöget már könnyen ki lehet számolni:$$\beta=180°-90°-'.$alphatext.'°$$';
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

			$correct = array($AB, $BC, $AC);
			$labels = array('$AB$', '$BC$', '$AC$');
			$solution = '$AB='.$AB.'$, $BC='.$BC.'$, $AC='.$AC.'$';

			$question = 'A $ABC$ derékszögű háromszög $AC$ befogója $'.$diff1.'$ cm-rel rövidebb, mint a $BC$ befogó. Az átfogó $'.$diff2.'$ cm-rel hosszabb, mint a $BC$ befogó. Számítsa ki a $ABC$ háromszög oldalainak hosszát!';

			$page[] = 'A feladat szerint az $AC$ befogó $'.$diff1.'$ cm-rel rövidebb, mint a $BC$ befogó, ezért ha az utóbbit $x$-szel jelöljük, akkor:'
				.'$$AC=x-'.$diff1.'$$';
			$page[] = 'Tudjuk továbbá, hogy az átfogó (amit jelöljünk $AB$-vel) $'.$diff2.'$ cm-rel hosszabb, mint az $x$, azaz:'
				.'$$AB=x+'.$diff2.'$$';
			$page[] = 'Mivel az $ABC$ háromszög derékszögű, igaz rá a <i>Pitagorasz-tétel</i>:'
				.'$$AC^2+BC^2=AB^2$$';
			$page[][] = 'A <i>Pitagorasz-tétel</i> azt mondja, hogy egy derékszögű háromszögben a két befogó ($AC$ és $BC$) négyzetösszege egyenlő az átfogó ($AB$) négyzetével:'.$this->Triangle();
			$hints[] = $page;

			$b = 2*($diff1+$diff2);
			$c = pow($diff1,2)-pow($diff2,2);

			$page = [];
			$page[] = '$$AC^2+BC^2=AB^2$$Írjuk fel a tagokat $x$ segítségével:'
				.'$$(x-'.$diff1.')^2+x^2=(x+'.$diff2.')^2$$';
			$page[] = 'Egyszerűsítés után ezt kapjuk:'
				.'$$x^2-'.$b.'x+'.$c.'=0$$';
			$page[] = array('Bontsuk fel a zárójeleket az $(a+b)^2=a^2+2ab+b^2$ összefüggés segítségével:'
					.'$$x^2-'.strval(2*$diff1).'x+'.pow($diff1,2).'+x^2=x^2+2'.strval(2*$diff2).'x+'.pow($diff2,2).'$$',
				'Vonjunk kis mindkét oldalból $x^2$-et:'
					.'$$x^2-'.strval(2*$diff1).'x+'.pow($diff1,2).'=2'.strval(2*$diff2).'x+'.pow($diff2,2).'$$',
				'Vonjunk kis mindkét oldalból $2'.strval(2*$diff2).'x$-et:'
					.'$$x^2-'.$b.'x+'.pow($diff1,2).'='.pow($diff2,2).'$$',
				'Vonjunk kis mindkét oldalból $'.pow($diff2,2).'$-et:'
					.'$$x^2-'.$b.'x+'.$c.'=0$$');
			$hints[] = $page;

			$D = sqrt(pow($b,2)-4*$c);
			$x1 = ($b+$D)/2;
			$x2 = ($b-$D)/2;

			$page = [];
			$page[] = '$$x^2-'.$b.'x+'.$c.'=0$$Az egyenlet két megoldása: $x_1='.$x1.'$, és $x_2='.$x2.'$.';
			$page[] = array('Írjuk fel az $ax^2+bx+c=0$ másodfokú egyenlet megoldóképetét:$$x_{1,2}=\frac{-b\pm\sqrt{b^2-4ac}}{2a}$$',
				'Most az $a=1$, $b=-'.$b.'$ és $c='.$c.'$:',
				'$$x_{1,2}=\frac{'.$b.'\pm\sqrt{(-'.$b.')^2-4\cdot'.$c.'}}{2}$$',
				'Először számoljuk ki a gyökjel alatti kifejezést:
					$$\sqrt{(-'.$b.')^2-4\cdot'.$c.'}=\sqrt{'.pow($b,2).'-'.strval(4*$c).'}=\sqrt{'.strval(pow($b,2)-4*$c).'}='.$D.'$$',
				'Az egyik megoldás:$$x_1=\frac{'.$b.'+'.$D.'}{2}=\frac{'.strval($b+$D).'}{2}='.$x1.'$$',
				'A másik megoldás:$$x_1=\frac{'.$b.'-'.$D.'}{2}=\frac{'.strval($b-$D).'}{2}='.$x2.'$$');
			$hints[] = $page;

			if ($x1 > $x2) {
				list($x1, $x2) = array($x2, $x1);
			}
			$page = [];
			$page[] = 'Az $x='.$x1.'$ megoldás nem jó, mert ha a $BC$ befogó <span class="label label-danger">$'.$x1.'$</span> cm hosszú,'
				.' akkor az $AC$ befogó $'.$diff1.'$ cm-rel rövidebb, azaz $'.$x1.'-'.$diff1.'='.strval($x1-$diff1).'$ lenne, ami nem lehetséges (egy háromszög oldala nem lehet $0$-nál kisebb).';
			$page[] = 'Tehát
				<ul>
					<li>a $BC$ befogó <span class="label label-success">$'.$x2.'$</span> cm,</li>
					<li>az $AC$ befogó $'.$x2.'-'.$diff1.'=$<span class="label label-success">$'.strval($x2-$diff1).'$</span> cm,</li>
					<li>az $AB$ átfogó $'.$x2.'+'.$diff2.'=$<span class="label label-success">$'.strval($x2+$diff2).'$</span> cm,</li>
				</ul>';
			$hints[] = $page;

		}

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