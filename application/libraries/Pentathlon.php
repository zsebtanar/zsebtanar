<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pentathlon {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		$CI->load->helper('draw');
		
		return;
	}

	// Generate random number between 1 and 20
	function Generate($level) {

		$level = 5;

		if ($level <= 4) {

			$ppl = rand(20,40);
			$rounds = rand(round($ppl/2), round($ppl*4/5));
			$default = rand(3,7)*50;
			$extra = rand(5,9);

			$total = $ppl-1;
			$wins = rand(0, $total);
			$wins = ($wins == $rounds ? $wins+pow(-1,rand(0,1)) : $wins);
			$defeats = $total - $wins;
			$points = $default+($wins-$rounds)*$extra;
			$diff = $wins-$rounds;

			$question = 'Egy öttusaversenyen $'.$ppl.'$ résztvevő indult. A vívás az első szám, ahol mindenki mindenkivel egyszer mérkőzik meg. Aki $'.$rounds.'$ győzelmet arat, az $'.$default.'$ pontot kap. Aki ennél több győzelmet arat, az minden egyes további győzelemért $'.$extra.'$ pontot kap '.The($default).' $'.$default.'$ ponton felül. Aki ennél kevesebbszer győz, attól annyiszor vonnak le $'.$extra.'$ pontot '.The($default).' $'.$default.'$-'.From($default).', ahány győzelem hiányzik '.The($rounds).' $'.$rounds.'$-'.To($rounds).'. (A mérkőzések nem végződhetnek döntetlenre.) ';
			$type = 'int';

			if ($level <= 2) {

				$question .= 'Hány pontot kapott a vívás során Péter, akinek $'.$defeats.'$ veresége volt?';
				$correct = $points;
				$solution = '$'.$correct.'$';
				$total = $ppl-1;
				$diff = $wins-$rounds;

				if ($diff > 0) {

					$page[] = 'Péter $'.$total.'$ mérkőzésből $'.$defeats.'$-'.Dativ($defeats).' vesztett el, azaz a többi $'.$total.'-'.$defeats.'='.$wins.'$ mérkőzést megnyerte.';
					$page[] = 'Mivel Péter legalább $'.$rounds.'$ mérkőzést nyert, ezért kap $'.$default.'$ pontot.';
					$page[] = 'Az előírt $'.$rounds.'$ mérkőzésen túl további $'.$wins.'-'.$rounds.'='.$diff.'$ alkalommal nyert, ami további $'.$diff.'\cdot'.$extra.'='.strval($diff*$extra).'$ pontot jelent.';
					$page[] = 'Tehát Péter összesen $'.$default.'+'.strval($diff*$extra).'=$ <span class="label label-success">$'.$correct.'$</span> pontot kapott.';
					$hints[] = $page;

				} else {

					$diff *= -1;
					$page[] = 'Péter győztes meccseinek száma $'.$rounds.'$ helyett mindössze $'.$wins.'$ volt, ami $'.$diff.'$-'.With($diff).' kevesebb.';
					$page[] = 'Ezért a $'.$default.'$ pontnál $'.$diff.'\cdot'.$extra.'='.strval($diff*$extra).'$-'.By($diff*$extra).' kevesebb pontot kap.';
					$page[] = 'Tehát Péter összesen $'.$default.'-'.strval($diff*$extra).'=$ <span class="label label-success">$'.$correct.'$</span> pontot kapott.';
					$hints[] = $page;

				}
			
			} else {

				$question .= 'Hány győzelme volt Bencének, aki $'.$points.'$ pontot szerzett?';
				$correct = $wins;
				$solution = '$'.$correct.'$';

				if ($diff > 0) {

					$page[] = 'Bence $'.$default.'$ pontnál többet szerzett, ami azt jelenti, hogy legalább $'.$rounds.'$ mérkőzést nyert.';
					$page[] = 'Számoljuk ki, Bence pontjainak száma mennyivel több, mint $'.$default.'$:$$'.$points.'-'.$default.'='.strval($diff*$extra).'$$';
					$page[] = 'Ha ezt a számot elosztjuk $'.$extra.'$-'.With($extra).', megkapjuk, hogy hány további versenyt nyert Bence:$$'.strval($diff*$extra).':'.$extra.'='.$diff.'$$';
					$page[] = 'Tehát Bencének összesen $'.$rounds.'+'.$diff.'=$ <span class="label label-success">$'.$correct.'$</span> győzelme volt.';
					$hints[] = $page;

				} else {

					$diff *= -1;
					$page[] = 'Bence $'.$default.'$ pontnál kevesebbet szerzett, ami azt jelenti, hogy kevesebb, mint $'.$rounds.'$ mérkőzést nyert.';
					$page[] = 'Számoljuk ki, Bence pontjainak száma mennyivel kevesebb, mint $'.$default.'$:$$'.$default.'-'.$points.'='.strval($diff*$extra).'$$';
					$page[] = 'Ha ezt a számot elosztjuk $'.$extra.'$-'.With($extra).', megkapjuk, hogy hány további versenyt nyert Bence:$$'.strval($diff*$extra).':'.$extra.'='.$diff.'$$';
					$page[] = 'Tehát Bencének összesen $'.$rounds.'-'.$diff.'=$ <span class="label label-success">$'.$correct.'$</span> győzelme volt.';
					$hints[] = $page;

				}
			}
				
		} else {

			$min = rand(5,9);
			$sec = (rand(1,2) == 1 ? rand(0,2)*33 : rand(10,99));
			$sec = ($min == 5 && $sec < 66 ? 66 : $sec);
			$sec = ($min == 9 && $sec > 33 ? 33 : $sec);

			$q = rand(2,10);
			$a0 = pow(-1,rand(0,1)) * rand(1,10);
			$a1 = $a0 * $q;
			$a2 = $a1 * $q;
			$question = 'Az öttusa úszás számában $200$ métert kell úszni. Az elért időeredményekért járó pontszámot mutatja a grafikon.';
			$question .= $this->Graph();

			if ($level <= 6) {

				$question .= 'Hány pontot kapott Robi, akinek az időeredménye $2$ perc $'.$min.','.$sec.'$ másodperc?';
				$correct = $this->Point($min, $sec);
				$correct = array($a1, -$a1);
				$solution = '$x_1='.$a1.'$, és $x_2='.strval(-$a1).'$$';
				$type = 'equation2';

				$page[] = 'A mértani sorozatban minden tagot úgy tudunk kiszámolni, hogy megszorozzuk $\textcolor{blue}{q}$-val (a <i>hányadossal</i>) az előző számot.';
				$page[] = 'Tehát ha az első szám $'.$a0.'$, akkor'
					.'$$\begin{eqnarray}a_1&=&'.$a0.'\\\\'
					.' a_2&=&a_1\cdot\textcolor{blue}{q}='.$a0.'\cdot\textcolor{blue}{q}=\textcolor{red}{x} \\\\ '
					.' a_3&=&a_2\cdot\textcolor{blue}{q}=a_1\cdot\textcolor{blue}{q}^2='.$a2.'\end{eqnarray}$$';
				$page[] = 'Látjuk, hogy ha '.The($a2).' $'.$a2.'$-'.Dativ($a2).' elosztjuk $'.$a0.'$-'.With($a0)
					.', a hányados négyzetét kapjuk:$$\textcolor{blue}{q}^2='.$a2.':'.($a0<0 ? '('.$a0.')' : $a0).'='
					.strval(pow($q,2)).'$$';
				$page[] = 'Ha ebből négyzetgyököt vonunk, megkapjuk a $\textcolor{blue}{q}$ abszolútértékét:'
					.'$$|\textcolor{blue}{q}|=\sqrt{'.strval(pow($q,2)).'}='.abs($q).'$$';
				$page[] = 'Tehát a $q$ értéke $'.$q.'$, vagy $'.strval(-$q).'$.';
				$page[] = 'Így már az $\textcolor{red}{x}$ értékét is ki tudjuk számolni:'
					.'$$\begin{eqnarray}\textcolor{red}{x_1}&=&'.$a0.'\cdot'.($q<0 ? '('.$q.')' : $q).strval($a0*$q).'\\\\ \textcolor{red}{x_2}&=&'.$a0.'\cdot'.(-$q<0 ? '('.strval(-$q).')' : strval(-$q)).strval(-$a0*$q).'\end{eqnarray}$$';
				$page[] = 'Tehát az $x$ értéke <span class="label label-success">$'.strval($a0*$q).'$</span>, vagy <span class="label label-success">$'.strval(-$a0*$q).'$</span>.';
				$hints[] = $page;
			
			} else {

				$question .= 'Határozza meg a sorozat hányadosát!';
				$correct = array($q, -$q);
				$solution = '$q_1='.$q.'$, és $q_2='.strval(-$q).'$';
				$type = 'quotient2';

				$page[] = 'A mértani sorozatban minden tagot úgy tudunk kiszámolni, hogy megszorozzuk $\textcolor{blue}{q}$-val (a <i>hányadossal</i>) az előző számot.';
				$page[] = 'Tehát ha az első szám $'.$a0.'$, akkor'
					.'$$\begin{eqnarray}a_1&=&'.$a0.'\\\\'
					.' a_2&=&a_1\cdot\textcolor{blue}{q}='.$a0.'\cdot\textcolor{blue}{q}=\textcolor{red}{x} \\\\ '
					.' a_3&=&a_2\cdot\textcolor{blue}{q}=a_1\cdot\textcolor{blue}{q}^2='.$a2.'\end{eqnarray}$$';
				$page[] = 'Látjuk, hogy ha '.The($a2).' $'.$a2.'$-'.Dativ($a2).' elosztjuk $'.$a0.'$-'.With($a0)
					.', a hányados négyzetét kapjuk:$$\textcolor{blue}{q}^2='.$a2.':'.($a0<0 ? '('.$a0.')' : $a0).'='
					.strval(pow($q,2)).'$$';
				$page[] = 'Ha ebből négyzetgyököt vonunk, megkapjuk a $\textcolor{blue}{q}$ abszolútértékét:'
					.'$$|\textcolor{blue}{q}|=\sqrt{'.strval(pow($q,2)).'}='.abs($q).'$$';
				$page[] = 'Tehát a $q$ értéke <span class="label label-success">$'.$q.'$</span>, vagy <span class="label label-success">$'.strval(-$q).'$</span>.';
				$hints[] = $page;
			}

		}

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'type' 		=> $type,
			'hints'		=> $hints
		);
	}

	function Graph() {

		$width 	= 400;
		$height = 350;

		$paddingX = 30;
		$paddingY = 80;

		$lines = 11;
		$unitX = ($width-30-$paddingX)/($lines+1);
		$unitY = ($height-30-$paddingY)/$lines;

		$sec = ['33', '00', '66'];

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">'
					// .'<rect width="'.$width.'" height="'.$height.'" fill="black" fill-opacity="0.2" />'
					;

		// X axis
		$svg .= DrawLine(0, $height-$paddingY, $width, $height-$paddingY);
		$svg .= DrawLine($width, $height-$paddingY, $width-5, $height-$paddingY+5);
		$svg .= DrawLine($width, $height-$paddingY, $width-5, $height-$paddingY-5);
		$svg .= DrawText($width-20, $height-$paddingY+15,'idő');

		// Y axis
		$svg .= DrawLine($paddingX, 0, $paddingX, $height);
		$svg .= DrawLine($paddingX, 0, $paddingX-5, 5);
		$svg .= DrawLine($paddingX, 0, $paddingX+5, 5);
		$svg .= DrawText($paddingX+10, 15,'pontszám');

		for ($i=0; $i < $lines+1; $i++) { 

			$x = $paddingX + ($lines-$i+1)*$unitX;
			$y = $height-$paddingY - (min($lines, $i+1))*$unitY;
			$svg .= DrawLine($paddingX-5, $y, $x, $y, 'black', 0.5);
			$svg .= DrawLine($x, $height-$paddingY+5, $x, $y, 'black', 0.5);
			if ($i < $lines) {
				$svg .= DrawLine($x-$unitX, $y, $x, $y, 'black', 2);
				$svg .= DrawCircle($x-$unitX, $y, 3, 'black', 1, 'black');
				$svg .= DrawCircle($x, $y, 3, 'black', 1, 'white');
			}
			if ($i < $lines && ($i == 0 || $i == $lines-1 || rand(1,3) == 3)) {
				$svg .= DrawText($paddingX-25, $y+4, 313+$i);
			}
			$text = '2 perc '.strval(9-floor(($i+1)/3)).','.$sec[$i%3].' mp';
			$transform = 'rotate(-90 '.strval($x+3).','.strval($height-$paddingY+77).')';
			$svg .= DrawText($x+3, $height-$paddingY+77, $text, 10, 'black', $transform);

		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Point($min, $sec) {

		$min0 = 5;
		$sec0 = 66;
		$point0 = 313;

		while ($min <= 10) {
			# code...
		}
	}
}

?>