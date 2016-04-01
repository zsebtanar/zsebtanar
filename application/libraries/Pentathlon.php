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

		$level = 7;

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

		} elseif ($level <= 8) {

			$min = rand(5,9);
			$sec = (rand(1,3) == 1 ? rand(10,99) : rand(0,2)*33);
			$sec = ($min == 5 && $sec < 66 ? 66 : $sec);
			$sec = ($min == 9 && $sec >= 33 ? rand(0,32) : $sec);

			$question = 'Az öttusa úszás számában $200$ métert kell úszni. Az elért időeredményekért járó pontszámot mutatja a grafikon.';
			$question .= $this->Graph();

			if ($level <= 6) {

				$question .= 'Hány pontot kapott Robi, akinek az időeredménye $2$ perc $'.$min.','.($sec == 0 ? '00' : $sec).'$ másodperc?';
				$point = $this->Point($min, $sec);
				$correct = $point;
				$solution = '$'.$correct.'$';
				$type = 'int';

				$hints[][] = 'Keressük meg a grafikon $x$ tengelyén a $2$ perc $'.$min.','.($sec == 0 ? '00' : $sec).'$ másodpercet!'.$this->Graph($min, $sec);
				$hints[][] = 'Keressük meg a neki megfelelő pontszámot az $y$ tengelyen!'.$this->Graph($min, $sec, $point);
				$hints[][] = 'Tehát Robi <span class="label label-success">'.$point.'</span> pontot kapott.';
			
			} else {

				$point = rand(314, 322);

				$question .= 'Péter $'.$point.'$ pontot kapott. Az alábbiak közül válassza ki Péter összes lehetséges időeredményét!';
				list($options, $correct, $times) = $this->Options($point);
				$solution = '';
				$type = 'multi';

				foreach ($times as $time) {
					$point2 = $this->Point($time[0], $time[1]);
					$hints[][] = 'Ha Péter időeredménye $2$ perc $'.$time[0].','.($time[1] == 0 ? '00' : $time[1]).'$ lett volna, akkor $'.$point2.'$ pontot kapott volna, tehát ez egy '.($point2 == $point ? '<span class="label label-success">jó</span>' : '<span class="label label-danger">rossz</span>').' megoldás.'.$this->Graph($time[0], $time[1], $point2);
				}
			}
			
		} else {

		}

		if (isset($options)) {

			return array(
				'question' 	=> $question,
				'correct' 	=> $correct,
				'solution'	=> $solution,
				'options'	=> $options,
				'type' 		=> $type,
				'hints'		=> $hints
			);

		} else {

			return array(
				'question' 	=> $question,
				'correct' 	=> $correct,
				'solution'	=> $solution,
				'type' 		=> $type,
				'hints'		=> $hints
			);
		}
	}

	function Graph($min=NULL, $sec=NULL, $point=NULL) {

		$width 	= 400;
		$height = 350;

		$paddingX = 30;
		$paddingY = 80;

		$lines = 11;
		$unitX = ($width-30-$paddingX)/($lines+1);
		$unitY = ($height-30-$paddingY)/$lines;

		$secs = ['33', '00', '66'];
		$show = [323, 320, 315, 313];

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
		$svg .= DrawLine($paddingX, 0, $paddingX, $height-60);
		$svg .= DrawLine($paddingX, 0, $paddingX-5, 5);
		$svg .= DrawLine($paddingX, 0, $paddingX+5, 5);
		$svg .= DrawText($paddingX+10, 15,'pontszám');

		for ($i=0; $i < $lines+1; $i++) { 

			$x = $paddingX + ($lines-$i+1)*$unitX;
			$y = $height-$paddingY - (min($lines, $i+1))*$unitY;
			$svg .= DrawPath($paddingX-5, $y, $x, $y, 'black', 0.5, 'none', 5, 5);
			$svg .= DrawPath($x, $height-$paddingY+5, $x, $y, 'black', 0.5, 'none', 5, 5);
			if ($i < $lines) {
				$svg .= DrawLine($x-$unitX, $y, $x, $y, 'black', 2);
				$svg .= DrawCircle($x-$unitX, $y, 3, 'black', 1, 'black');
				$svg .= DrawCircle($x, $y, 3, 'black', 1, 'white');
			}
			if (in_array(313+$i, $show)) {
				$svg .= DrawText($paddingX-25, $y+4, 313+$i);
			} elseif ($point !== NULL && $i < $lines) {
				$svg .= DrawText($paddingX-25, $y+4, 313+$i, 10, 'blue');
			}
			$text = '2 perc '.strval(9-floor(($i+1)/3)).','.$secs[$i%3].' mp';
			$transform = 'rotate(-90 '.strval($x+3).','.strval($height-$paddingY+77).')';
			$svg .= DrawText($x+3, $height-$paddingY+77, $text, 10, 'black', $transform);

		}

		// Draw time
		if ($min !== NULL && $sec !== NULL) {

			$time = $min*100 + $sec;
			$x1 = $paddingX + $unitX*($lines+1)*(400 - (933-$time))/400;
			$y1 = $height - $paddingY;

			$svg .= DrawCircle($x1, $y1, 3, 'blue', 1, 'blue');
		}

		if ($point !== NULL) {

			$x2 = $paddingX;
			$y2 = $height - $paddingY - ($point - 312)*$unitY;

			$svg .= DrawPath($x1, $y1, $x1, $y2, 'blue', 2, 'none', 5, 5);
			$svg .= DrawPath($x1, $y2, $x2, $y2, 'blue', 2, 'none', 5, 5);

			$svg .= DrawCircle($x2, $y2, 3, 'blue', 1, 'blue');
		}

		$svg .= '</svg></div>';

		return $svg;
	}

	function Point($min, $sec) {

		if ($sec < 33) {
			$point = 313 + (9-$min)*3;
		} elseif ($sec < 66) {
			$point = 315 + (8-$min)*3;
		} else {
			$point = 314 + (8-$min)*3;
		}

		return $point;
	}

	function Options($point) {

		if ($point % 3 == 0) {

			$min = 8 - ($point - 315)/3;
			$sec = 33;

			$times[] = [$min, 0];
			$times[] = [$min, rand(1,32)];
			$times[] = [$min, 33];
			$times[] = [$min, rand(34,65)];
			$times[] = [$min, 66];

		} elseif ($point % 3 == 1) {

			$min = 9 - ($point - 313)/3;
			$sec = 0;

			$times[] = [$min-1, 66];
			$times[] = [$min-1, rand(67,99)];
			$times[] = [$min, 0];
			$times[] = [$min, rand(1,32)];
			$times[] = [$min, 33];

		} else {

			$min = 8 - ($point - 314)/3;
			$sec = 66;

			$times[] = [$min, 33];
			$times[] = [$min, rand(34,65)];
			$times[] = [$min, 66];
			$times[] = [$min, rand(67,99)];
			$times[] = [$min+1, 0];
		}

		shuffle($times);

		foreach ($times as $time) {
			
			$min = $time[0];
			$sec = $time[1];

			$point2 = $this->Point($min, $sec);

			$correct[] = $point2 == $point;
			$options[] = '$2$ perc $'.$min.','.($sec == 0 ? '00' : $sec).'$ mp';
		}

		return array($options, $correct, $times);
	}
}

?>