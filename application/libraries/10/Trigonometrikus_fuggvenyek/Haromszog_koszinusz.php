<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Haromszog_koszinusz {

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

		$node = 'AC';
		$length = rand(ceil($level/2),$level);
		$AB = $length+rand(ceil($level/2),$level);

		$question = 'Az $ABC$ derékszögű háromszög $'.$node.'$ befogója $'.$length.'$ cm, $AB$ átfogója $'.$AB.'$ cm hosszú. Számítsa ki az $ABC$ háromszög hegyesszögeinek nagyságát legalább két tizedesjegy pontossággal!';

		$alpha = toDeg(acos($length/$AB));
		$beta = 90-$alpha;

		$alphatext = str_replace('.', ',', round($alpha*100)/100);
		$betatext = str_replace('.', ',', round($beta*100)/100);

		$correct = array(round1($alpha), round1($beta));
		$labels = array('$\alpha$', '$\beta$');
		$solution = '$\alpha='.$alphatext.'°$ és $\beta='.$betatext.'°$.';

		$hints[][] = 'Rajzoljunk egy derékszögű háromszöget:'.$CI->tangens->Triangle();
		$hints[][] = 'Tudjuk, hogy az $\textcolor{blue}{'.$node.'}$ befogó $'.$length.'$ cm hosszú:'.$CI->tangens->Triangle([$node], [$length], ['blue']);
		$hints[][] = 'Tudjuk, hogy az $\textcolor{green}{AB}$ átfogó $'.$AB.'$ cm hosszú:'.$CI->tangens->Triangle([$node,'AB'], [$length,$AB], ['blue','green']);

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