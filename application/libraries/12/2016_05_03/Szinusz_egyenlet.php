<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Szinusz_egyenlet {

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
		$CI->load->library('11/Trigonometrikus_egyenletek/Szinusz', NULL, 'Szinusz');
		$data = $CI->Szinusz->Generate($level);

		return $data;
	}
}

?>