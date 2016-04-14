<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class 3_Szinusz_fv_ertekkeszlet {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	// Define range of sine function (a+bsin(x))
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('10/Trigonometrikus_fuggvenyek/Szinusz_ertekkeszlet', NULL, 'Szinusz_ertekkeszlet');
		$data = $CI->Szinusz_ertekkeszlet->Generate($level);

		return $data;
	}
}

?>