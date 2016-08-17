<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Koordinatageometria {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Solve equation for square root
	function Generate($level) {

		$CI =& get_instance();

		if ($level <= 3) {

			$CI->load->library('11/Koordinatageometria/Haromszog_sulypont', NULL, 'Koordinatageometria_Exercise');

		} elseif ($level <= 6) {

			$CI->load->library('11/Koordinatageometria/Egyenes_egyenlet', NULL, 'Koordinatageometria_Exercise');

		} else {

			$CI->load->library('11/Koordinatageometria/Kor_egyenlet', NULL, 'Koordinatageometria_Exercise');

		}

		$data = $CI->Koordinatageometria_Exercise->Generate($level);

		return $data;
	}
}

?>