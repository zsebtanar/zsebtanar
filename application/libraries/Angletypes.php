<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angletypes {

	// Define type of angle
	function Generate($level) {

		$options = array(
			'nullszög',
			'hegyesszög',
			'derékszög',
			'tompaszög',
			'egyenesszög',
			'homorúszög',
			'teljesszög'
		);

		$angles = array(
			0,
			rand(5,85),
			90,
			rand(95,175),
			180,
			rand(185,355),
			360
		);

		$index 		= rand(0,count($angles)-1);

		$angle_type = $options[$index];
		$angle 		= $angles[$index];
		$solution 	= $angle_type;


		$question = 'Milyen típusú az alábbi szög?'.$this->SVG($angle);

		$explanation = $this->Explanation();
		
		shuffle($options);
		$correct = array_search($angle_type, $options);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'options' 	=> $options,
			'solution'	=> $solution,
			'explanation' => $explanation
		);
	}

	// Generate filled arc for specific angle
	function SVG($angle_deg, $showdegrees=FALSE) {

		$width 		= 400;
		$height 	= 260;
		$stroke_width = 1;
		$color1 	= '#D1D1D1';
		$color2 	= '#5C5C5C';

		$centerx 	= $width/2;
		$centery 	= $height/2;
		$radius 	= 100;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">
						<circle cx="'.$centerx.'" cy="'.$centery.'" r="'.$radius.'" fill="'.$color1.'" />';

		if ($angle_deg == 0) {

			$svg .= '<line x1="'.$centerx.'" y1="'.$centery.'" x2="'.strval($centerx+$radius).'" y2="'.$centery.'" stroke="'.$color2.'" stroke-width="'.$stroke_width.'" />';

		} elseif ($angle_deg == 360) {

			$svg .= '<circle cx="'.$centerx.'" cy="'.$centery.'" r="'.$radius.'" fill="'.$color2.'" />';

		} else {

			$angle_rad 	= $angle_deg * pi() / 180.0;
			$x 			= $centerx + $radius * cos($angle_rad);
			$y 			= $centery - $radius * sin($angle_rad);
			$large_arc_flag = ($angle_deg <= 180 ? 0 : 1);

			$svg .= '<path fill="'.$color2.'" d="M'.strval($centerx+$radius).','.$centery.' A'.$radius.','.$radius.' 0 '.$large_arc_flag.',0 '.$x.','.$y.' L '.$centerx.' '.$centery.'" />';
		}

		if ($showdegrees) {

			$svg .= '<text x="'.strval($centerx+$radius+10).'" y="'.strval($centery+7).'">0°</text>';
			$svg .= '<text x="'.strval($centerx-10).'" y="'.strval($centery-$radius-7).'">90°</text>';
			$svg .= '<text x="'.strval($centerx-$radius-45).'" y="'.strval($centery+7).'">180°</text>';
			$svg .= '<text x="'.strval($centerx-15).'" y="'.strval($centery+$radius+20).'">270°</text>';
		}


		$svg .= '</svg></div>';

		return $svg;
	}

	// Generate explanation
	function Explanation() {

		$explanation[][] = 'A $0°$-os szöget <b>nullszög</b>nek nevezzük:'.$this->SVG(0, $showdegrees=TRUE);
		$explanation[][] = 'A $0°$-nál nagyobb, de $90°$-nál kisebb szöget <b>hegyesszög</b>nek nevezzük:'.$this->SVG(45, $showdegrees=TRUE);
		$explanation[][] = 'A $90°$-os szöget <b>derékszög</b>nek nevezzük:'.$this->SVG(90, $showdegrees=TRUE);
		$explanation[][] = 'A $90°$-nál nagyobb, de $180°$-nál kisebb szöget <b>tompaszög</b>nek nevezzük:'.$this->SVG(135, $showdegrees=TRUE);
		$explanation[][] = 'A $180°$-os szöget <b>egyenesszög</b>nek nevezzük:'.$this->SVG(180, $showdegrees=TRUE);
		$explanation[][] = 'A $180°$-nál nagyobb, de $360°$-nál kisebb szöget <b>homorúszög</b>nek nevezzük:'.$this->SVG(270, $showdegrees=TRUE);
		$explanation[][] = 'A $360°$-os szöget (ami pont ugyanott van, mint a $0°$) <b>teljesszög</b>nek nevezzük:'.$this->SVG(360, $showdegrees=TRUE)
						.'A $360°$-nál nagyobb szögeket pedig <b>forgásszög</b>nek nevezzük.';

		return $explanation;
	}
}

?>