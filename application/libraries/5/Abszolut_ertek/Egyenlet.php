<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egyenlet {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define solution of equation for absolute values
	function Generate($level) {

		if ($level <= 3) {
			$diff = pow(-1,rand(1,2))*rand(2,3);
			$abs_val = rand(1,3);
		} elseif ($level <= 6) {
			$diff = pow(-1,rand(1,2))*rand(4,6);
			$abs_val = rand(1,6);
		} else {
			$diff = pow(-1,rand(1,2))*rand(7,15);
			$abs_val = rand(1,15);
		}

		$solution1 = $abs_val-$diff;
		$solution2 = -$abs_val-$diff;

		$question = 'Az $x$-nél $'.abs($diff).'$-'.With(abs($diff)).' '.($diff<0 ? 'kisebb' : 'nagyobb')
			.' számnak az abszolútértéke $'.$abs_val.'$. Adja meg $x$ lehetséges értékeit!';

		$page[] = 'A feladat azt mondja, hogy ha az '.($diff<0 ? '$x$-ből kivonok' : '$x$-hez hozzáadok')
			.' $'.abs($diff).'$-'.Dativ(abs($diff)).', és ennek az abszolút értékét veszem, $'
			.$abs_val.'$ lesz az eredmény.';
		$page[] = 'Írjuk fel ezt egyenlettel:'
			.'$$|x'.($diff < 0 ? '-' : '+').abs($diff).'|='.$abs_val.'$$';
		$page[] = 'Válasszuk szét az eseteket aszerint, hogy az $x'.($diff < 0 ? '-' : '+').abs($diff).'$'
			.' pozitív vagy negatív-e.';
		$hints[] = $page;

		$page = [];
		$page[] = '<b>1. eset:</b> $x'.($diff < 0 ? '-' : '+').abs($diff).'>0$';
		$page[] = 'Ekkor az abszolút érték jel elhagyható:'
			.'$$x'.($diff < 0 ? '-' : '+').abs($diff).'='.$abs_val.'$$';
		$page[] = ($diff < 0 ? 'Adjunk hozzá mindkét oldalhoz' : 'Vonjunk ki mindkét oldalból')
			.' $'.abs($diff).'$-'.Dativ(abs($diff)).'!:'
			.'$$x='.$abs_val.($diff < 0 ? '+' : '-').abs($diff).'='.$solution1.'$$';
		$page[] = 'Tehát az egyik megoldás <span class="label label-success">$'.$solution1.'$</span>.';
		$hints[] = $page;

		$page = [];
		$page[] = '<b>2. eset:</b> $x'.($diff < 0 ? '-' : '+').abs($diff).'<0$';
		$page[] = 'Ekkor az abszolút érték a kifejezés $-1$-szeresét jelenti:'
			.'$$-(x'.($diff < 0 ? '-' : '+').abs($diff).')='.$abs_val.'$$';
		$page[] = 'Szorozzuk meg mindkét oldalt $-1$-gyel:'
			.'$$x'.($diff < 0 ? '-' : '+').abs($diff).'=-'.$abs_val.'$$';
		$page[] = ($diff < 0 ? 'Adjunk hozzá mindkét oldalhoz' : 'Vonjunk ki mindkét oldalból')
			.' $'.abs($diff).'$-'.Dativ(abs($diff)).'!:'
			.'$$x=-'.$abs_val.($diff < 0 ? '+' : '-').abs($diff).'='.$solution2.'$$';
		$page[] = 'Tehát a másik megoldás <span class="label label-success">$'.$solution2.'$</span>.';
		$hints[] = $page;

		$correct = array($solution1, $solution2);
		$labels = array('$x_1$', '$x_2$');
		$solution = 'a megoldások: $x_1='.$solution1.'$, $x_2='.$solution2.'$';

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'labels'	=> $labels,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'type' 		=> 'list'
		);
	}
}

?>