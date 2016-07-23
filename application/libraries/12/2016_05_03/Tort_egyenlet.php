<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tort_egyenlet {

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
		$CI->load->library('9/Egyenletek/Tortek', NULL, 'Tortek');
		$data = $CI->Tortek->Generate($level);

		return $data;
	}
}

?>