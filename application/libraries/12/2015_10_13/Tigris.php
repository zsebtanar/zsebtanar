<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tigris {

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

		if ($level <= 3) {

			$CI->load->library('11/Hatvany/Exponencialis_egyenlet', NULL, 'Exponencialis_egyenlet');
			$data = $CI->Exponencialis_egyenlet->Generate($level);

		} elseif ($level <= 6) {

			$CI->load->library('11/Hatvany/Exponencialis_egyenlet2', NULL, 'Exponencialis_egyenlet2');
			$data = $CI->Exponencialis_egyenlet2->Generate($level);

		} else {

			$CI->load->library('11/Kombinatorika/Kombinacio', NULL, 'Kombinacio');
			$data = $CI->Kombinacio->Generate($level);

		}

		return $data;
	}
}

?>