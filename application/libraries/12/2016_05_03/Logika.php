<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logika {

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
		$CI->load->library('12/Allitasok/Sapka', NULL, 'Allitasok');
		$data = $CI->Allitasok->Generate($level);

		return $data;
	}
}

?>