<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haromszog_szinusz {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');

		return;
	}

	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('10/Trigonometrikus_fuggvenyek/Haromszog_tangens', NULL, 'tangens');

		$node = 'BC';
		$length = rand(ceil($level/2),$level);
		$AB = $length+rand(ceil($level/2),$level);

		$question = 'Az $ABC$ derékszögű háromszög $'.$node.'$ befogója $'.$length.'$ cm, $AB$ átfogója $'.$AB.'$ cm hosszú. Számítsa ki az $ABC$ háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

		$alpha = toDeg(asin($length/$AB));
		$beta = 90-$alpha;

		$alphatext = str_replace('.', ',', round($alpha*100)/100);
		$betatext = str_replace('.', ',', round($beta*100)/100);

		$correct = array($alpha, $beta);
		$labels = array('$\alpha$', '$\beta$');
		$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

		$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$CI->tangens->Triangle();
		$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{'.$node.'}$ befogó $'.$length.'$ cm hosszú:'.$CI->tangens->Triangle([$node], [$length], ['blue']);
		$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{AB}$ átfogó $'.$AB.'$ cm hosszú:'.$CI->tangens->Triangle([$node,'AB'], [$length,$AB], ['blue','green']);

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
		$page[] = 'Tehát az $\alpha$ szög <span class="label label-success">$'.$alphatext.'°$</span>.';
		$hints[] = $page;

		$page = [];
		$page[] = 'Mivel a háromszög belső szögeinek összege $180°$, ezért a $\beta$ szöget már könnyen ki lehet számolni:$$\beta=180°-90°-'.$alphatext.'°$$';
		$page[] = 'Tehát a $\beta$ szög <span class="label label-success">$'.$betatext.'°$</span>.';
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
}

?>