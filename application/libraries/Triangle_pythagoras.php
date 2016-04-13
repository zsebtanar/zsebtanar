<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Triangle_pythagoras {

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
		$CI->load->library('Triangle_tangent');

		if ($level <= 3) {
			$mult = rand(3,9);
			$AC = 3*$mult;
			$BC = 4*$mult;
			$AB = 5*$mult;
		} elseif ($level <= 6) {
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
		$page[][] = 'A <i>Pitagorasz-tétel</i> azt mondja, hogy egy derékszögű háromszögben a két befogó ($AC$ és $BC$) négyzetösszege egyenlő az átfogó ($AB$) négyzetével:'.$CI->triangle_tangent->Triangle();
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