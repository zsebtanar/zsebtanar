<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masodfoku_egyenlet {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');
		
		return;
	}

	// Solve quadratic equation
	function Generate($level) {

		$CI =& get_instance();
		$CI->load->library('10/Masodfoku_egyenlet/Egyenlet1', NULL, 'Egyenlet1');
		$data = $CI->Egyenlet1->Generate($level);

		return $data;
	}
}

?>