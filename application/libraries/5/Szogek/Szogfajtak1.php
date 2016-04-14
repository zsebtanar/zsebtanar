<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szogfajtak1 {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define type of angle
	function Generate($level) {

		$options = array(
			'nullszög',
			'hegyesszög',
			'derékszög',
			'tompaszög',
			'egyenesszög',
			'homorúszög',
			'teljesszög',
			'forgásszög',
		);

		$angles = array(
			0,
			rand(5,85),
			90,
			rand(95,175),
			180,
			rand(185,355),
			360,
			rand(365,450)
		);

		$index 		= rand(0,count($angles)-1);

		$angle_type = $options[$index];
		$angle 		= $angles[$index];
		$solution 	= $angle_type;


		$question = 'Milyen típusú az alábbi szög?'.$this->DrawAngle($angle);

		$hints = $this->Hints();

		shuffle($options);
		$correct = array_search($angle_type, $options);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'options' 	=> $options,
			'solution'	=> $solution,
			'hints' 	=> $hints
		);
	}

	// Draw specific angle
	function DrawAngle($angle_deg, $showdegrees=FALSE) {

		// $angle_deg = 200;

		$width 		= 400;
		$height 	= 260;
		$stroke_width = 1;
		$color1 	= '#F2F2F2';
		$color2 	= 'black';

		$centerx 	= $width/2;
		$centery 	= $height/2;
		$radius1 	= 100;
		$radius2 	= 40;

		$svg = '<div class="img-question text-center">
					<svg width="'.$width.'" height="'.$height.'">
						<circle cx="'.$centerx.'" cy="'.$centery.'" r="'.$radius1.'" fill="'.$color1.'" />
						<line x1="'.$centerx.'" y1="'.$centery.'" x2="'.strval($centerx+$radius1).'" y2="'.$centery.'" stroke="'.$color2.'" stroke-width="'.$stroke_width.'" />';

		if ($angle_deg == 360) {

			$svg .= '<circle cx="'.$centerx.'" cy="'.$centery.'" r="'.$radius2.'" stroke="black" fill="none" />';

		} elseif ($angle_deg <= 360) {

			list($x1, $y1) = polarToCartesian($centerx, $centery, $radius1, $angle_deg);
			list($x2, $y2) = polarToCartesian($centerx, $centery, $radius2, $angle_deg);

			$large_arc_flag = ($angle_deg <= 180 ? 0 : 1);

			$svg .= '<path stroke="black" fill="none" d="M'.strval($centerx+$radius2).','.$centery.' A'.$radius2.','.$radius2.' 0 '.$large_arc_flag.',0 '.$x2.','.$y2.'" />
					<line x1="'.$centerx.'" y1="'.$centery.'" x2="'.$x1.'" y2="'.$y1.'" stroke="'.$color2.'" stroke-width="'.$stroke_width.'" />';
		} else {

			$angle = 0;
			$radius = 30;

			while ($angle < $angle_deg) {
				list($x1, $y1) = polarToCartesian($centerx, $centery, $radius, $angle);
				list($x2, $y2) = polarToCartesian($centerx, $centery, $radius+0.1, min($angle_deg, $angle+5));

				$svg .= '<path stroke="black" fill="none" d="M'.$x1.','.$y1.' A'.$radius.','.strval($radius+0.5).' 0 0,0 '.$x2.','.$y2.'" />';

				$angle += 5;
				$radius += .1;
			}

			list($x1, $y1) = polarToCartesian($centerx, $centery, $radius1, $angle_deg);
			$svg .= '<line x1="'.$centerx.'" y1="'.$centery.'" x2="'.$x1.'" y2="'.$y1.'" stroke="'.$color2.'" stroke-width="'.$stroke_width.'" />';
		}

		if ($showdegrees) {

			$svg .= '<text font-size="15" fill="black" x="'.strval($centerx+$radius1+18).'" y="'.strval($centery+7).'">$0°$</text>';
			$svg .= '<text font-size="15" fill="black" x="'.strval($centerx+5).'" y="'.strval($centery-$radius1-7).'">$90°$</text>';
			$svg .= '<text font-size="15" fill="black" x="'.strval($centerx-$radius1-25).'" y="'.strval($centery+7).'">$180°$</text>';
			$svg .= '<text font-size="15" fill="black" x="'.strval($centerx+5).'" y="'.strval($centery+$radius1+22).'">$270°$</text>';
		}


		$svg .= '</svg></div>';

		return $svg;
	}

	// Generate hints
	function Hints() {

		$hints[][] = 'A $0°$-os szöget <b>nullszög</b>nek nevezzük:'.$this->DrawAngle(0, $showdegrees=TRUE);
		$hints[][] = 'A $0°$-nál nagyobb, de $90°$-nál kisebb szöget <b>hegyesszög</b>nek nevezzük:'.$this->DrawAngle(45, $showdegrees=TRUE);
		$hints[][] = 'A $90°$-os szöget <b>derékszög</b>nek nevezzük:'.$this->DrawAngle(90, $showdegrees=TRUE);
		$hints[][] = 'A $90°$-nál nagyobb, de $180°$-nál kisebb szöget <b>tompaszög</b>nek nevezzük:'.$this->DrawAngle(135, $showdegrees=TRUE);
		$hints[][] = 'A $180°$-os szöget <b>egyenesszög</b>nek nevezzük:'.$this->DrawAngle(180, $showdegrees=TRUE);
		$hints[][] = 'A $180°$-nál nagyobb, de $360°$-nál kisebb szöget <b>homorúszög</b>nek nevezzük:'.$this->DrawAngle(270, $showdegrees=TRUE);
		$hints[][] = 'A $360°$-os szöget (ami pont ugyanott van, mint a $0°$) <b>teljesszög</b>nek nevezzük:'.$this->DrawAngle(360, $showdegrees=TRUE);
		$hints[][] = 'A $360°$-nál nagyobb szögeket pedig <b>forgásszög</b>nek nevezzük:'.$this->DrawAngle(405, $showdegrees=TRUE);

		return $hints;
	}
}

?>