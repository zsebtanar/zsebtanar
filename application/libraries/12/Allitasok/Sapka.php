<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sapka {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define member of arithmetic & geometric series
	function Generate($level) {

		$colors = (rand(1,2) == 1 ? ['fekete', 'fehér'] : ['fehér', 'fekete']);
		
		$type = rand(0,3);

		$ind = range(0,3);
		shuffle($ind);

		// // Original exercise
		// $colors = ['fekete', 'fehér'];
		// $type = 0;
		// $ind = range(0,3);

		if ($type == 0) {

			$question = 'Egy fiókban néhány sapka van. Tekintsük a következő állítást:<br />
				<i>„A fiókban minden sapka '.$colors[0].'.”</i>
				<br />Válassza ki az alábbiak közül az összes állítást, amely tagadása a fentinek!';

			$page[] = 'Az eredeti állítás azt jelenti, hogy a fiókban <i>mindegyik</i> sapka színe '.$colors[0].'.';
			$page[] = 'Ez az állítás akkor nem teljesül, ha van <i>legalább egy</i> olyan sapka a fiókban, ami <i>nem</i> '.$colors[0].'.';
			$page[] = 'Nézzük meg, hogy melyik állítás jelenti ugyanezt!';

			$opt[$ind[0]] = '<i>„A fiókban minden sapka '.$colors[1].'.”</i>';
			$opt[$ind[1]] = '<i>„A fiókban nincs '.$colors[1].' sapka.”</i>';
			$opt[$ind[2]] = '<i>„A fiókban van olyan sapka, amely nem '.$colors[0].'.”</i>';
			$opt[$ind[3]] = '<i>„A fiókban nem minden sapka '.$colors[0].'.”</i>';

			$page[3+$ind[0]] = The($ind[0]+1,TRUE).' <b>'.OrderText($ind[0]+1).'</b> állítás azt jelenti, hogy a fiókban <i>mindegyik</i> sapka színe '.$colors[1].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[1]] = The($ind[1]+1,TRUE).' <b>'.OrderText($ind[1]+1).'</b> állítás azt jelenti, hogy a sapkák közül <i>egyik sem</i> '.$colors[1].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[2]] = The($ind[2]+1,TRUE).' <b>'.OrderText($ind[2]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami <i>nem</i> '.$colors[0].', ami ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-success">igaz</span>.';
			$page[3+$ind[3]] = The($ind[3]+1,TRUE).' <b>'.OrderText($ind[3]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami <i>nem</i> '.$colors[0].', ami ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-success">igaz</span>.';
			$hints[] = $page;

			$correct[$ind[0]] = FALSE;
			$correct[$ind[1]] = FALSE;
			$correct[$ind[2]] = TRUE;
			$correct[$ind[3]] = TRUE;

			$ind1 = min($ind[2],$ind[3]);
			$ind2 = max($ind[2],$ind[3]);
			$solution = The($ind1,TRUE).' '.OrderText($ind1).' és '.The($ind2).' '.OrderText($ind2).' állítás igaz, a többi hamis.';

		} elseif ($type == 1) {

			$question = 'Egy fiókban néhány sapka van. Tekintsük a következő állítást:<br />
				<i>„A fiókban nincs '.$colors[1].' sapka.”</i>
				<br />Válassza ki az alábbiak közül az összes állítást, amely tagadása a fentinek!';

			$page[] = 'Az eredeti állítás azt jelenti, hogy a sapkák közül <i>egyik sem</i> '.$colors[1].'.';
			$page[] = 'Ez az állítás akkor nem teljesül, ha van <i>legalább egy</i> olyan sapka, ami '.$colors[1].'.';
			$page[] = 'Nézzük meg, hogy melyik állítás jelenti ugyanezt!';

			$opt[$ind[0]] = '<i>„A fiókban minden sapka '.$colors[0].'.”</i>';
			$opt[$ind[1]] = '<i>„A fiókban nincs '.$colors[0].' sapka.”</i>';
			$opt[$ind[2]] = '<i>„A fiókban van olyan sapka, amelyik '.$colors[1].'.”</i>';
			$opt[$ind[3]] = '<i>„A fiókban nem minden sapka '.$colors[0].'.”</i>';

			$page[3+$ind[0]] = The($ind[0]+1,TRUE).' <b>'.OrderText($ind[0]+1).'</b> állítás azt jelenti, hogy a fiókban <i>mindegyik</i> sapka színe '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[1]] = The($ind[1]+1,TRUE).' <b>'.OrderText($ind[1]+1).'</b> állítás azt jelenti, hogy a sapkák közül <i>egyik sem</i> '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[2]] = The($ind[2]+1,TRUE).' <b>'.OrderText($ind[2]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami '.$colors[1].', ami ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-success">igaz</span>.';
			$page[3+$ind[3]] = The($ind[3]+1,TRUE).' <b>'.OrderText($ind[3]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami <i>nem</i> '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$hints[] = $page;

			$correct[$ind[0]] = FALSE;
			$correct[$ind[1]] = FALSE;
			$correct[$ind[2]] = TRUE;
			$correct[$ind[3]] = FALSE;

			$solution = The($ind[2],TRUE).' '.OrderText($ind[2]).' állítás igaz, a többi hamis.';

		} elseif ($type == 2) {

			$question = 'Egy fiókban néhány sapka van. Tekintsük a következő állítást:<br />
				<i>„A fiókban van olyan sapka, amelyik '.$colors[1].'.”</i>
				<br />Válassza ki az alábbiak közül az összes állítást, amely tagadása a fentinek!';

			$page[] = 'Az eredeti állítás azt jelenti, hogy a sapkák közül <i>legalább az egyik</i> '.$colors[1].'.';
			$page[] = 'Ez az állítás akkor nem teljesül, ha a sapkák közül <i>egyik sem</i> '.$colors[1].'.';
			$page[] = 'Nézzük meg, hogy melyik állítás jelenti ugyanezt!';

			$opt[$ind[0]] = '<i>„A fiókban minden sapka '.$colors[0].'.”</i>';
			$opt[$ind[1]] = '<i>„A fiókban nincs '.$colors[1].' sapka.”</i>';
			$opt[$ind[2]] = '<i>„A fiókban van olyan sapka, amelyik '.$colors[0].'.”</i>';
			$opt[$ind[3]] = '<i>„A fiókban nem minden sapka '.$colors[1].'.”</i>';

			$page[3+$ind[0]] = The($ind[0]+1,TRUE).' <b>'.OrderText($ind[0]+1).'</b> állítás azt jelenti, hogy a fiókban <i>mindegyik</i> sapka színe '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[1]] = The($ind[1]+1,TRUE).' <b>'.OrderText($ind[1]+1).'</b> állítás azt jelenti, hogy a sapkák közül <i>egyik sem</i> '.$colors[1].', ami ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-success">igaz</span>.';
			$page[3+$ind[2]] = The($ind[2]+1,TRUE).' <b>'.OrderText($ind[2]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[3]] = The($ind[3]+1,TRUE).' <b>'.OrderText($ind[3]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami <i>nem</i> '.$colors[1].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$hints[] = $page;

			$correct[$ind[0]] = FALSE;
			$correct[$ind[1]] = TRUE;
			$correct[$ind[2]] = FALSE;
			$correct[$ind[3]] = FALSE;

			$solution = The($ind[1],TRUE).' '.OrderText($ind[1]).' állítás igaz, a többi hamis.';

		} elseif ($type == 3) {

			$question = 'Egy fiókban néhány sapka van. Tekintsük a következő állítást:<br />
				<i>„A fiókban nem minden sapka '.$colors[1].'.”</i>
				<br />Válassza ki az alábbiak közül az összes állítást, amely tagadása a fentinek!';

			$page[] = 'Az eredeti állítás azt jelenti, hogy a sapkák közül <i>legalább</i> az egyik <i>nem</i> '.$colors[1].'.';
			$page[] = 'Ez az állítás akkor nem teljesül, ha a sapkák közül <i>egyik sem</i> '.$colors[1].'.';
			$page[] = 'Nézzük meg, hogy melyik állítás jelenti ugyanezt!';

			$opt[$ind[0]] = '<i>„A fiókban minden sapka '.$colors[0].'.”</i>';
			$opt[$ind[1]] = '<i>„A fiókban nincs '.$colors[1].' sapka.”</i>';
			$opt[$ind[2]] = '<i>„A fiókban van olyan sapka, amelyik '.$colors[0].'.”</i>';
			$opt[$ind[3]] = '<i>„A fiókban nem minden sapka '.$colors[1].'.”</i>';

			$page[3+$ind[0]] = The($ind[0]+1,TRUE).' <b>'.OrderText($ind[0]+1).'</b> állítás azt jelenti, hogy a fiókban <i>mindegyik</i> sapka színe '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[1]] = The($ind[1]+1,TRUE).' <b>'.OrderText($ind[1]+1).'</b> állítás azt jelenti, hogy a sapkák közül <i>egyik sem</i> '.$colors[1].', ami ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-success">igaz</span>.';
			$page[3+$ind[2]] = The($ind[2]+1,TRUE).' <b>'.OrderText($ind[2]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami '.$colors[0].', ami nem ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$page[3+$ind[3]] = The($ind[3]+1,TRUE).' <b>'.OrderText($ind[3]+1).'</b> állítás azt jelenti, hogy a sapkák közül van <i>legalább egy</i>, ami <i>nem</i> '.$colors[1].', ami <i>nem</i> ugyanazt jelenti, mint az állítás ellentéte, ezért <span class="label label-danger">hamis</span>.';
			$hints[] = $page;

			$correct[$ind[0]] = FALSE;
			$correct[$ind[1]] = TRUE;
			$correct[$ind[2]] = FALSE;
			$correct[$ind[3]] = FALSE;

			$solution = The($ind[1],TRUE).' '.OrderText($ind[1]).' állítás igaz, a többi hamis.';

		}

		$options[] = $opt[0];
		$options[] = $opt[1];
		$options[] = $opt[2];
		$options[] = $opt[3];
		
		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'options'	=> $options,
			'type' 		=> 'multi',
			'hints'		=> $hints,
			'youtube'	=> 'f3NBvA09O_w'
		);
	}
}

?>