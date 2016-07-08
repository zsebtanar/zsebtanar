<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sorozatok {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Define member of arithmetic & geometric series
	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 3) {

			if (rand(1,2) == 1) {

				$CI->load->library('12/Szamtani_sorozat/Tag2', NULL, 'Szamtani_tag');
				$data = $CI->Szamtani_tag->Generate($level);
			
			} else {

				$CI->load->library('12/Szamtani_sorozat/Differencia', NULL, 'Szamtani_diff');
				$data = $CI->Szamtani_diff->Generate($level);

			}
			
		} elseif ($level <= 6) {

			if (rand(1,2) == 1) {

				$CI->load->library('12/Mertani_sorozat/Tag', NULL, 'Mertani_tag');
				$data = $CI->Mertani_tag->Generate($level);

			} else {

				$CI->load->library('12/Mertani_sorozat/Hanyados', NULL, 'Szamtani_hanyados');
				$data = $CI->Szamtani_hanyados->Generate($level);

			}

		} else {
			
			$CI->load->library('19/Statisztika/Median_atlag', NULL, 'Median_atlag');
			$data = $CI->Median_atlag->Generate($level);

		}

		return $data;
	}
}

?>